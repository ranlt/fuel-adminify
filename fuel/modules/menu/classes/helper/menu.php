<?php

namespace Menu;


/**
 *  This class contains some helping functions for the module which manage menu in backend
 */
class Helper_Menu 
{

    /**
     * Get all menus to array for DynaTree Plugin
     * @return array
     */
    public static function getMenuTree($selectId, $showNone = false, $idMenu = null, $showAllRoot = false)
    {
    	\Lang::load('menu', true);
        $showAllRoot and $roots = \LbMenu\Model_Menu::forge()->roots()->get();
        
        $currentMenu = ($idMenu) ? \LbMenu\Model_Menu::find($idMenu) : null;

        $tree = array();
        $treeRoot = ($showNone) ? array(array('title' => __('menu.root'), 'key' => 'none')) : array();

        if ($showNone && empty($selectId))
        {
            $tree[0]['activate'] = true;
            $tree[0]['focus'] = true;
            $tree[0]['expand'] = true;
            $tree[0]['select'] = true;
        }

        if ($showAllRoot)
        {
            foreach ($roots as $root)
            {
                $tree[] = current(self::reformatMenuTree($root->dump_tree(), $selectId, $currentMenu));
            }
        }        
        else
        {
            $menu = \LbMenu\Helper_Menu::find($selectId);
            $tree[] = current(self::reformatMenuTree($menu->root()->get_one()->dump_tree(), $selectId, $currentMenu));
        }

       	($showNone and $treeRoot[0]['children'] = $tree) or $treeRoot = $tree;

        return $treeRoot;
    }

    /**
     * Replace key "name" to "title, and "id" to "key" for Dynatree Plugin data
     * @param array $menus
     * @return array
     */
    public static function reformatMenuTree($menus, $selectId, $currentMenu = null)
    {
        $res = array();
        foreach ($menus as $k => $menu)
        {
        	$menuObj = \LbMenu\Model_Menu::find($menu['id']);
            $menuLang = \LbMenu\Helper_Menu::getLang($menuObj);

        	if ($currentMenu && ($menuObj->is_descendant_of($currentMenu) || $menuObj->id == $currentMenu->id))
        		continue;

            if (isset($menu['children']) && ! empty($menu['children']))
            {
                $menu['children'] = self::reformatMenuTree($menu['children'], $selectId, $currentMenu);
            }

            if ($menu['id'] == $selectId)
            {
                $menu['activate'] = true;
                $menu['focus'] = true;
                $menu['expand'] = true;
                $menu['select'] = true;
            }
            
            $menu['title'] = $menuLang->text;
            $menu['key'] = $menu['id'];
            
            $res[] = $menu;
        }

        return $res;
    }

    /**
     * Generate the breadcrumb menu 
     * @param  [type] $menu [description]
     * @return [type]       [description]
     */
    public static function generateBreadcrumb($menu)
    {
    	$liArr = array();
    	self::getParent($menu, $liArr);

    	$resLi = '';
    	$res = '<ul class="breadcrumb">';
    	$res .= '<li><a href="'.\Router::get('menu_backend_menu').'">'.__('menu.root').'</a></li>';
		foreach($liArr as $liMenu)
		{
                $liMenuLang = \LbMenu\Helper_Menu::getLang($liMenu);
				$res .= '<li><a href="'.\Router::get('menu_backend_submenu', array('id' => $liMenu->id)).'">'.ucfirst($liMenuLang->text).'</a></li>';
		}
        $menuLang = \LbMenu\Helper_Menu::getLang($menu);
		$res .= '<li class="active">'. ucfirst($menuLang->text) .'</li>';
    	$res .= '</ul>';

    	return $res;
    }

    // For get all parent
    public static function getParent($menu, &$liArr)
    {
		if ($menu->is_child())
		{
			array_unshift($liArr, $menu->parent()->get_one());
			self::getParent($menu->parent()->get_one(), $liArr);
		}
    }

    /**
     * Return EAV from theme
     * @param  mixed $theme     
     * @param  boolean $isUpdate 
     * @return array         
     */
    public static function getEav($menu = false, $theme = false, $isUpdate = false, $parent = false, $idRoot = false)
    {

        // Get theme from string
        if (is_string($theme))
        {
            \Config::load('menu', true);
            $themeArr = \Config::get('menu.themes.'.$theme);
            $themeArr['name'] = $theme;
            $theme = $themeArr;
        }
        // Get theme from Menu
        else if (!$theme)
        {
            if (!$parent)
            {   
                if (!$idRoot)
                    // Get theme from menu
                    $theme = \LbMenu\Helper_Menu::getTheme($menu); 
                else
                    // Get theme from menu root
                    $theme = \LbMenu\Helper_Menu::getTheme(\LbMenu\Model_Menu::find($idRoot)); 
            } 
            else {
                // Get theme from menu parent
                $parent = \LbMenu\Model_Menu::find($parent);
                $theme = \LbMenu\Helper_Menu::getTheme($parent);
            }
        }
        // Get Menu Object
        $isUpdate and (is_numeric($menu)) and $menu = \LbMenu\Model_Menu::find($menu);

        // Get all eav value from object or default value
        $eav = (isset($theme['attributes'])) ? $theme['attributes'] : array();
        foreach($eav as $k => $attribute)
        {
            if ($isUpdate)
                $eav[$k]['value'] = (isset($menu->{$eav[$k]['key']}) ? $menu->{$eav[$k]['key']} : '');
            else
                $eav[$k]['value'] = (isset($eav[$k]['default']) ? $eav[$k]['default'] : '');
        }

        return array('eav' => $eav, 'theme_name' => $theme['name']);
    }
}