<?php

namespace LbMenu;


/**
 *  This class contains some helping functions for the package with Array Driver
 */
class Helper_Array
{

    /**
     * Return the correct theme for menu
     * @param  array $menu          
     * @param  mixed $themeOverride 
     * @return array                
     */
    public static function getTheme($menu, $themeOverride = null)
    {
        !is_array($menu) and $menu = self::find($menu);

        \Config::load('menu', true);
        $themesConf = \Config::get('menu.themes');
        $themeFallback = \Config::get('menu.theme_fallback');
        $themeDefault = \Config::get('menu.theme_default');
        // If no override theme
        if ($themeOverride === null)
        {
            // Get the theme of the root
            $menu = (self::isRoot($menu['slug'])) ? $menu : self::getRoot($menu['slug']);
            $theme = (isset($menu['theme']) && isset($themesConf[$menu['theme']])) ? $menu['theme'] : $themeFallback;
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
     * Return the Lang array of the menu
     * @param  array  $menu         
     * @param  boolean $language     
     * @param  boolean $forceCurrent 
     * @return mixed                
     */
    public static function getLang($menu, $language = false)
    {
        !is_array($menu) and $menu = self::find($menu);

        // Get default language
        $language == false and $language = \Config::get('language');
        if (is_array($menu))
        {
            $menuLang = array();
            // Search by language
            $menuLang['text'] = self::getLangAttribute($menu, 'text', '');
            $menuLang['title'] = self::getLangAttribute($menu, 'title', '');
            $menuLang['small_desc'] = self::getLangAttribute($menu, 'small_desc', '');

            return $menuLang;
        }
        else
        {
            return array();
        }
    }

    /**
     * ALL helper functions for manage the Menu Array
     */
    
    /**
     * If the menu is root or not
     * @param  array  $menu 
     * @return boolean       
     */
    public static function isRoot($menu)
    {
        !is_array($menu) and $menu = self::find($menu);

        $path = self::find($menu['slug'], true);
        $pathArr = explode('.', $path);
        $res = (count($pathArr) > 1) ? false : true;
        return $res;
    }

    /**
     * Get the menu root
     * @param  array $menu 
     * @return array       
     */
    public static function getRoot($menu)
    {
        !is_array($menu) and $menu = self::find($menu);

        $path = self::find($menu['slug'], true);
        $pathArr = explode('.', $path);
        $rootSlug = $pathArr[0];

        return self::find($rootSlug);
    }

    /**
     * Find a menu, and initialize it
     * @param  string  $menu    
     * @param  boolean $getPath 
     * @return array|null          
     */
    public static function find($menu, $getPath=false)
    {
        $menus = self::getMenus();

        do 
        {
            $path = \Arr::search($menus, $menu);
            $pathArr = explode('.', $path);
            if (end($pathArr) != 'slug')
            {
                \Arr::delete($menus, $path);
            } 
            else
            {
                array_pop($pathArr);
                return ($getPath) ? implode('.', $pathArr) : self::loadDefault(\Arr::get($menus, implode('.', $pathArr)));
            }
        } while($path !== null);
    }

    /**
     * Get all menus
     * @return array
     */
    public static function getMenus()
    {
        return \Config::get('menu.menus');
    }

    /**
     * Get the depth of the menu/link
     * @param  array $menu
     * @return int       
     */
    public static function getDepth($menu)
    {
        !is_array($menu) and $menu = $menu['slug'];

        $path = self::find($menu['slug'], true);
        $path = str_replace('.children', '', $path);
        return count(explode('.', $path));
    }

    /**
     * Load default value in the array if not defined
     * @param  array $menu 
     * @return array       
     */
    public static function loadDefault($menu)
    {
        !is_array($menu) and $menu = self::find($menu);

        self::getLangAttribute($menu, 'text') === NULL and $menu['text'] = '';
        self::getLangAttribute($menu, 'title')  === NULL and $menu['title'] = '';
        self::getLangAttribute($menu, 'small_desc')  === NULL and $menu['small_desc'] = '';

        (!isset($menu['is_blank'])) and $menu['is_blank'] = false;
        (!isset($menu['use_router'])) and $menu['use_router'] = false;
        (!isset($menu['link'])) and $menu['link'] = '#';
        (!isset($menu['eav'])) and $menu['eav'] = array();
        (!isset($menu['named_params'])) and $menu['named_params'] = array();
        (!isset($menu['children'])) and $menu['children'] = array();

        $menu['depth'] = self::getDepth($menu);

        foreach($menu['children'] as $k => $children)
        {
            $menu['children'][$k] = self::loadDefault($children);
        }

        return $menu;
    }

    /**
     * Get the lang attribute
     * 
     * @param  array  $menu      
     * @param  string  $attribute
     * @param  mixed  $return    
     * @param  boolean $language 
     * @return mixed             
     */
    public static function getLangAttribute($menu, $attribute, $return = null, $language = false)
    {
        !is_array($menu) and $menu = self::find($menu);

        if ($language === false)
        {
            $language = \Config::get('language');
            return (isset($menu['menu_langs'][$language][$attribute]) ? $menu['menu_langs'][$language][$attribute] : (isset($menu[$attribute]) ? $menu[$attribute] : $return ));
        }
        else
        {
            return (isset($menu[$attribute]) ? $menu[$attribute] : (isset($menu['menu_langs'][$language][$attribute]) ? $menu['menu_langs'][$language][$attribute] : $return ));
        }
    }
}