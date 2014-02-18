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

}