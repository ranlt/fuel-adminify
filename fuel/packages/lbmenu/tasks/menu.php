<?php

namespace Fuel\Tasks;

class Menu
{

    public function __construct()
    {

    }

    /**
     * Delete menu / link
     * 
     * Cmd : oil refine menu:delete id|slug
     * 
     * @param  mixed $id
     * @return bool     
     */
    public function delete($id)
    {
        $menu = \LbMenu\Helper_Menu::find($id);
        if (is_null($menu))
        {
            \Cli::write('Menu not found.', 'red');
            return false;
        }

        if (\LbMenu\Helper_Menu::delete($menu))
        {
            \Cli::write('Menu deleted');
        }
        else
        {
            return false;
        }
    }

    /**
     * Create menu / link
     *
     * Cmd : oil refine menu:create --packages=lbMenu "text" -[slug|link|is_blank|theme|use_router|named_params|language|title|small_desc|eav|parent_id]="value"
     *
     * named_params and eav has array value, example : -eav="key1:value|key2:value2|.."
     * parent_id can be the id or slug of the parent menu
     * 
     * @param  string $text 
     * @return boolean      
     */
    public function create($text = '')
    {
        \Config::load('menu', true);

        if (empty($text))
        {
            \Cli::write('You must specify the menu text in first argument');
            return false;
        }
        else if (!is_string($text))
        {
            \Cli::write('The menu text must be a string');
            return false;
        }

        // Configure parent_id
        $parent_id = $this->configureParentId();

        // Configure EAV
        $eavArr = $this->configureEav();

        // Configure Named params
        $namedParamsArr = $this->configureNamedParams();

        // Set data
        $params = array(
            'slug' => \Cli::option('slug', $text),
            'link' => \Cli::option('link', '#'),
            'is_blank' => \Cli::option('is_blank', false),
            'theme' => \Cli::option('theme', \Config::get('menu.theme_default')),
            'use_router' => \Cli::option('use_router', false),
            'named_params' => $namedParamsArr,
            'language' => \Cli::option('language', \Config::get('language')),
            'title' => \Cli::option('title', ''),
            'text' => $text,
            'small_desc' => \Cli::option('small_desc', ''),
            'eav' => $eavArr,
            'parent_id' => $parent_id,
        );

        $menu = \LbMenu\Helper_Menu::forge();
        $saveArr = \LbMenu\Helper_Menu::manage($menu, $params, true);

        if ($saveArr['response'])
        {
            \Cli::write('Menu created with ID : #'.$saveArr['menu']->id.' ('.$saveArr['menu']->slug.')');
        }
        else
        {
            \Cli::write('Error in creating menu');
        }
    }

    /**
     * Update a menu
     *
     * Cmd : oil refine menu:update --packages=lbMenu "id|slug" -[slug|link|is_blank|theme|use_router|named_params|language|title|text|small_desc|eav|parent_id]="value"
     *
     * named_params and eav has array value, example : -eav="key1:value|key2:value2|.."
     * parent_id can be the id or slug of the parent menu
     * 
     * @param  string $id 
     * @return boolean     
     */
    public function update($id = '')
    {
        \Config::load('menu', true);

        if (empty($id))
        {
            \Cli::write('You must specify the menu id or slug in first argument');
            return false;
        }

        // Configure parent_id
        $parent_id = $this->configureParentId();

        // Configure EAV
        $eavArr = $this->configureEav();

        // Configure Named params
        $namedParamsArr = $this->configureNamedParams();

        // Set data
        $params = array(
            'slug' => \Cli::option('slug'),
            'link' => \Cli::option('link'),
            'is_blank' => \Cli::option('is_blank'),
            'theme' => \Cli::option('theme'),
            'use_router' => \Cli::option('use_router'),
            'named_params' => $namedParamsArr,
            'language' => \Cli::option('language'),
            'title' => \Cli::option('title'),
            'text' => \Cli::option('text'),
            'small_desc' => \Cli::option('small_desc'),
            'eav' => $eavArr,
            'parent_id' => $parent_id,
        );

        $menu = \LbMenu\Helper_Menu::find($id);
        $saveArr = \LbMenu\Helper_Menu::manage($menu, $params, true);

        if ($saveArr['response'])
        {
            \Cli::write('Menu updated with ID : #'.$saveArr['menu']->id.' ('.$saveArr['menu']->slug.')');
        }
        else
        {
            \Cli::write('Error in updating menu');
        }
    }

    /**
     * Add or modify an eav from menu
     *
     * Cmd : oil refine menu:eav --packages=lbMenu id|slug key value
     * 
     * @param  mixed $id    
     * @param  string $key  
     * @param  string $value 
     */
    public function eav($id, $key, $value)
    {
        $menu = \LbMenu\Helper_Menu::find($id);
        $params['eav'] = array($key => $value);
        $saveArr = \LbMenu\Helper_Menu::manage($menu, $params, true);
        if ($saveArr['response'])
        {
            \Cli::write('Menu EAV updated with ID : #'.$saveArr['menu']->id.' ('.$saveArr['menu']->slug.')');
        }
        else
        {
            \Cli::write('Error in updating EAV menu');
        }
    }

    /**
     * Add or modify an route params from menu
     *
     * Cmd : oil refine menu:route_params --packages=lbMenu id|slug type(add|edit|delete) key [value]
     * 
     * @param  mixed $id    
     * @param  string $type  
     * @param  string $key  
     * @param  string $value 
     */
    public function route_params($id, $type, $key, $value='')
    {
        $menu = \LbMenu\Helper_Menu::find($id);
        $namedParams = $menu->named_params;

        switch($type)
        {
            case 'add':
            case 'update':
                $namedParams[$key] = $value;
            break;

            case 'delete':
                unset($namedParams[$key]);
            break;
        }
        $params = array('named_params' => $namedParams);

        $saveArr = \LbMenu\Helper_Menu::manage($menu, $params, true);
        if ($saveArr['response'])
        {
            \Cli::write('Route params updated with ID : #'.$saveArr['menu']->id.' ('.$saveArr['menu']->slug.')');
        }
        else
        {
            \Cli::write('Error in updating route params');
        }
    }

    /**
     * Show the dump tree in console
     *
     * Cmd : oil refine menu:dump_tree --packages=lbMenu
     * @return [type] [description]
     */
    public function dump_tree()
    {
        $roots = \LbMenu\Model_Menu::forge()->roots()->get();
        \Cli::write('');
        \Cli::write('  Dump tree ');
        \Cli::write('');
        foreach($roots as $root)
        {
            $this->showMenuTree($root->dump_tree());
        }
    }
    protected function showMenuTree($menus)
    {
        $res = array();
        foreach ($menus as $k => $menu)
        {
            $menuObj = \LbMenu\Model_Menu::find($menu['id']);
            $menuLang = \LbMenu\Helper_Menu::getLang($menuObj);

            // Write seperator
            $sep = '  ';
            $sep .= $menuObj->is_root() ? '|-' : '|';
            for($i=0;$i<$menuObj->depth()-1;$i++)
            {
                $sep .= '  |';
            }
            $sep .= $menuObj->is_root() ? '' : '  |-';

            \Cli::write($sep . '(' . $menuObj->id . ') ' . $menuLang->text);

            if (isset($menu['children']) && ! empty($menu['children']))
            {
                $this->showMenuTree($menu['children']);
            }

            $res[] = $menu;
        }

        return $res;
    }

    // Some helping functions for tasks
     
    /**
     * Configure the menu parent
     * @return int
     */
    protected function configureParentId()
    {
        if (\Cli::option('parent_id', false))
        {
            if(!is_numeric(\Cli::option('parent_id')))
            {
                $menuParent = \LbMenu\Helper_Menu::find(\Cli::option('parent_id'));
                $parent_id = $menuParent->id;
            }
            else
            {
                $parent_id = \Cli::option('parent_id');
            }
        }
        else
        {
            $parent_id = false;
        }

        return $parent_id;
    }

    /**
     * Configure the EAV
     * @return array
     */
    protected function configureEav()
    {
        $eavArr = array();
        foreach(explode('|', \Cli::option('eav', '')) as $eav)
        {
            if (!empty($eav))
            {
                list($attribute, $value) = explode(':', $eav);
                $eavArr[$attribute] = $value;
            }
        }
        return $eavArr;
    }

    /**
     * Configure the Named Params for route
     * @return array
     */
    protected function configureNamedParams()
    {
        if (\Cli::option('named_params') === null) return false;

        $namedParamsArr = array();
        foreach(explode('|', \Cli::option('named_params', '')) as $namedParam)
        {
            if (!empty($namedParam))
            {
                list($key, $value) = explode(':', $namedParam);
                $namedParamsArr[$key] = $value;
            }
        }

        return $namedParamsArr;
    }
}