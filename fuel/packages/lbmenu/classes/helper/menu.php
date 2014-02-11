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

}