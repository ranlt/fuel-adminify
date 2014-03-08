<?php

namespace LbMenu;


/**
 *  This class contains some helping functions for the package
 */
class Helper_Menu 
{

    /**
     * Return the correct theme for menu
     * @param  Menu $menu          
     * @param  mixed $themeOverride 
     * @return array                
     */
    public static function getTheme($menu, $themeOverride = null)
    {
        \Config::load('menu', true);
        $themesConf = \Config::get('menu.themes');
        $themeFallback = \Config::get('menu.theme_fallback');
        $themeDefault = \Config::get('menu.theme_default');
        // If no override theme
        if ($themeOverride === null)
        {
            if (!$menu->is_new())
            {
                // Get the theme of the root
                $menu = ($menu->is_root()) ? $menu : $menu->root()->get_one();
                $theme = (!empty($menu->theme) && isset($themesConf[$menu->theme])) ? $menu->theme : $themeFallback;
            }
            else
            {
                // New menu : Get default theme
               if (isset($themesConf[$themeDefault]))
                {
                    $theme = $themeDefault;
                }
                else if (!isset($themesConf[$themeFallback]))
                {
                    throw new \Exception("No menu theme found.");
                }
                else
                {
                    $theme = $themeFallback;
                }
            }
        }
        // If override by theme name
        else if (is_string($themeOverride))
        {
            if (isset($themesConf[$themeOverride]))
            {
                $theme = $themeOverride;
            }
            else if (!isset($themesConf[$themeFallback]))
            {
                throw new \Exception("No menu theme found.");
            }
            else
            {
                $theme = $themeFallback;
            }
        }
        // If override by theme array
        else if (is_array($themeOverride))
        {
            !isset($themeOverride['name']) and $themeOverride['name'] = 'Theme override';
            return $themeOverride;
        }


        $themesConf[$theme]['name'] = $theme;
        
        return $themesConf[$theme];
    }

    /**
     * Return the Lang object of the menu
     * @param  mixed  $menu         
     * @param  boolean $language     
     * @param  boolean $forceCurrent 
     * @return mixed                
     */
    public static function getLang($menu, $language = false, $forceCurrent = false)
    {
        // Get default language
        $language == false and $language = \Config::get('language');

        // Return the current menu lang
        if ($forceCurrent)
        {
            if (is_array($menu))
            {
                if (empty($menu['menu_langs']))
                {
                    $lang = new \LbMenu\Model_Lang(array('language' => $language));
                    return $lang->to_array();
                }
                else
                {
                    return current($menu['menu_langs']);
                }
            }
            else
            {
                return (empty($menu->menu_langs)) ? new \LbMenu\Model_Lang(array('language' => $language)) : current($menu->menu_langs);
            }
        } 

        if (is_array($menu))
        {
            // If langs not loaded
            if (!isset($menu['menu_langs']))
            {
                $langs = \LbMenu\Model_Lang::query()->where('id_menu', $menu['id'])->get();
                foreach($langs as $k => $lang)
                {
                    $langs[$k] = $lang->to_array();
                }
                $menu['menu_langs'] = $langs;
            }
            // Search for menu lang
            foreach((array)$menu['menu_langs'] as $menuLang)
            {
                if ($menuLang['language'] == $language) return $menuLang;
            }

            // Language not found, return the current
            return array_shift($menu['menu_langs']);
        }
        else
        {
            // Search for menu lang
            foreach((array)$menu->menu_langs as $menuLang)
            {
                if ($menuLang->language == $language) return $menuLang;
            }
        }

        if (is_array($menu))
        {
            // Not found, forge new MenuLang
            $lang = new \LbMenu\Model_Lang(array('language' => $language));
            return $lang->to_array();
        }
        else
        {
            // Not found, forge new MenuLang
            return new \LbMenu\Model_Lang(array('language' => $language));
        }
    }

    public static function recursiveGetLang($menuArr)
    {
        // Get language data
        $menuLang = self::getLang($menuArr);

        // Unset useless data
        unset($menuLang['created_at']);
        unset($menuLang['updated_at']);
        unset($menuLang['id']);
        unset($menuLang['id_menu']);
        unset($menuArr['menu_langs']);

        // Merge lang
        $menuArr = array_merge($menuArr, $menuLang);
        
        // Do the same for children
        foreach((array)$menuArr['children'] as $k => $child)
        {
            $menuArr['children'][$k] = self::recursiveGetLang($child);
        }

        return $menuArr;
    }

    /**
     * ALL helper functions for manage the Menu model
     */

    public static function forge()
    {
        return new \LbMenu\Model_Menu();
    }

    public static function find($id)
    {
        return (is_numeric($id)) ? \LbMenu\Model_Menu::find($id) : \LbMenu\Model_Menu::query()->where('slug', $id)->get_one();
    }

    public static function delete($menu)
    {
        return $menu->delete_tree();
    }

    public static function manage($menu, $data, $return_data = false)
    {
        \Config::load('menu', true);
        $isUpdate = ($menu->is_new()) ? false : true;

        // Set EAV
        if (isset($data['eav']))
        {
            foreach($data['eav'] as $attribute => $value)
            {
                $menu->{$attribute} = $value;
            }
        } 

        // Set language
        $data['language'] = (isset($data['language']) ? $data['language'] : ($isUpdate ? false : \Config::get('language')));

        // Get MenuLang or create it
        $menuLang = \LbMenu\Helper_Menu::getLang($menu, $data['language']);
        $data['language'] = $menuLang->language;

        if (!$isUpdate)
        {
            // Set default value
            $data = array_merge($data, array(
                'slug' => isset($data['slug']) ? $data['slug'] : $data['text'],
                'link' => (isset($data['link'])) ? $data['link'] : '#',
                'is_blank' => (isset($data['is_blank'])) ? $data['is_blank'] : false,
                'theme' => (isset($data['theme'])) ? $data['theme'] : \Config::get('menu.theme_default'),
                'use_router' => (isset($data['use_router'])) ? $data['use_router'] : false,
                'named_params' => (isset($data['named_params'])) ? $data['named_params'] : array(),
                'title' => isset($data['title']) ? $data['title'] : '',
                'small_desc' => isset($data['small_desc']) ? $data['small_desc'] : '',
            ));
        }
        else
        {
            // Set Menu value
            $data = array_merge($data, array(
                'slug' => isset($data['slug']) ? $data['slug'] : $menu->slug,
                'link' => (isset($data['link'])) ? $data['link'] : $menu->link,
                'is_blank' => (isset($data['is_blank'])) ? $data['is_blank'] : $menu->is_blank,
                'theme' => (isset($data['theme'])) ? $data['theme'] : $menu->theme,
                'use_router' => (isset($data['use_router'])) ? $data['use_router'] : $menu->use_router,
                'named_params' => (isset($data['named_params']) && is_array($data['named_params'])) ? $data['named_params'] : $menu->named_params,
                'text' => isset($data['text']) ? $data['text'] : $menuLang->text,
                'title' => isset($data['title']) ? $data['title'] : $menuLang->title,
                'small_desc' => isset($data['small_desc']) ? $data['small_desc'] : $menuLang->small_desc,
            )); 
        }

        // Set from form
        $menu->from_array(array(
            'slug'         => \Inflector::friendly_title($data['slug'], '-', true),
            'link'         => $data['link'],
            'is_blank'     => $data['is_blank'],
            'theme'        => $data['theme'],
            'use_router'   => $data['use_router'],
            'named_params' => $data['named_params'],
        ));

        $menuLang->from_array(array(
            'text'       => $data['text'],
            'title'      => $data['title'],
            'small_desc' => $data['small_desc'],
            'language'   => $data['language'],
        ));
        $menu->menu_langs[] = $menuLang;

        // Set the parent category
        if (isset($data['parent_id']) && $data['parent_id'] != false)
        {
            // If change parent
            if ($menu->is_new() || $data['parent_id'] != $menu->parent()->get_one()->id)
            {
                $menuParent = self::find($data['parent_id']);
                if ($menu->is_new() || $menu->get_tree_id() == $menuParent->get_tree_id())
                    $menu->child($menuParent);
            }
        }

        // Slug must be unique
        if ($response = $menu->save())
        {
            $countDoublon = self::getOccurence($menu->id, 'menu_menu', 'slug', $menu->slug);
            if ($countDoublon > 1)
            {
                $menu->slug .= '-'.($countDoublon);
                $menu->save();
            }
        }

        if ($return_data)
        {
            return array(
                'response' => $response,
                'menu' => $menu,
                'menuLang' => $menuLang,
            );
        }
        else
        {
            return $response;
        }
    }


    /**
     * Get occurence in table
     * @param  int  $id       Identifiant de l'objet
     * @param  string  $table    Nom de la table
     * @param  string  $attribute Nom de l'attribut
     * @param  string  $value    La valeur unique
     * @param  integer $count    le nombre d'occurence
     * @return int            Retourne le nombre d'occurence
     */
    public static function getOccurence($id, $table, $attribute, $value, $count=0)
    {
        $whereAttribute = ($count > 1) ? $value.'-'.$count : $value;
        $res = \DB::select('*')->from($table)->where($attribute, '=', $whereAttribute)->where('id', '!=', $id)->execute()->as_array();
        
        if (!empty($res))
        {
            $count++;
            return self::getOccurence($id, $table, $attribute, $value, $count);
        }
        else
        {
            return $count;
        }
    }

}