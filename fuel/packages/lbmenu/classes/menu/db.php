<?php

namespace LbMenu;

class Menu_Db extends \LbMenu\Menu
{
	/**
	 * Load the menu from id, slug or object
	 * @param  mixed $menu 
	 * @return Menu       
	 */
	protected function load($menu = null)
	{
		$menu = $menu ? : $this->menu;

		if (is_numeric($menu))
		{
			$menu = \LbMenu\Model_Menu::find($menu);
		}
		else if (is_string($menu))
		{
			$menu = \LbMenu\Model_Menu::query()->where('slug', $menu)->get_one();
		}

		if ($menu === null)
		{
			throw new \Exception('Menu '.$menu.' not found');
		}

		return $menu;
	}


	/**
	 * Render the Menu
	 * @param  array $theme
	 * @return string        
	 */
	public function render($theme = null)
	{
		$theme = \LbMenu\Helper_Menu::getTheme($this->menu);
        $html = $this->buildMenu($this->menu, $theme);
        echo $html;
	}

	/**
	 * Build the menu in HTML from theme config
	 * @param  Menu  $menu 
	 * @param  array  $theme 
	 * @param  boolean $main  
	 * @return string         
	 */
	public function buildMenu($menu, $theme, $main = true)
	{
        $output = "";
        // Check if it's a link or not
        if ($menu->has_children())
        {
            $children = $menu->children()->get();
            foreach($children as $child)
            {
                // Construct Text
                $menuLang = $child->getMenuLang();
                $content = $this->themeReplaceInnerItem($child, $menuLang, $theme);

                // Construct Item
            	$item = $this->themeReplaceItem($child, $menuLang, $theme, $content);

                // If contains submenu
                $submenu = ($child->has_children()) ? $this->buildMenu($child, $theme, false) : '';

                // Construct Submenu
            	$output .= $this->themeReplaceSubmenu($child, $menuLang, $theme, $item, $submenu);
            }
        }
        else
        {
            return '';
        }

        // Show the menu
		return $this->themeReplaceMenu($child, $menuLang, $theme, $output);
	}

	/**
	 * Process for replace vars in Menu
	 * @param  Menu $child    
	 * @param  Lang $menuLang 
	 * @param  array $theme    
	 * @param  string $output   
	 * @return string           
	 */
	public function themeReplaceMenu($child, $menuLang, $theme, $output)
	{
		$arrKeys = array(
			'{menu}',
			'{depth}',
		);
		$arrValues = array(
			$output,
			$child->depth(),
		);

		$key = ($child->depth() >= 2) ? 'sub_menu' : 'menu';
		$depthKey = '_depth-'.$child->depth();
 		$key = $this->searchThemeKey($child->slug, $key, $theme, $depthKey);
		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	/**
	 * Process for replace vars in Submenu
	 * @param  Menu $child    
	 * @param  Lang $menuLang 
	 * @param  string $item     
	 * @param  string $submenu  
	 * @return string           
	 */
	public function themeReplaceSubmenu($child, $menuLang, $theme, $item, $submenu)
	{
		$arrKeys = array(
			'{submenu}',
			'{depth}',
		);

		$arrValues = array(
			$submenu,
			$child->depth(),
		);

		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $item));
	}

	/**
	 * Process for replace vars in Inner Item
	 * @param  Menu $child    
	 * @param  Lang $menuLang 
	 * @param  array $theme    
	 * @return string           
	 */
	public function themeReplaceInnerItem($child, $menuLang, $theme)
	{
		if (empty($menuLang->text)) return '';

		$arrKeys = array(
			'{link}', 
			'{text}', 
			'{title}', 
			'{depth}',
			'{active}'
		);

		$arrValues = array(
			$this->generateLink($child),
			$menuLang->text,
			$menuLang->title,
			$child->depth(),
			$this->isActive($child),
		);

		$key = ($child->depth() >= 2) ? 'sub_menu_item_inner' : 'menu_item_inner';
		$childrenKey = ($child->has_children()) ? '_with_children' : '';
		$depthKey = '_depth-'.$child->depth();
 		$key = $this->searchThemeKey($child->slug, $key, $theme, $depthKey, $childrenKey);

		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	/**
	 * Process for replace vars in Item
	 * @param  Menu $child    
	 * @param  Lang $menuLang 
	 * @param  array $theme    
	 * @param  string $content  
	 * @return string           
	 */
	public function themeReplaceItem($child, $menuLang, $theme, $content)
	{
		$arrKeys = array(
			'{item}',
			'{link}', 
			'{text}', 
			'{title}', 
			'{depth}',
			'{active}'
		);

		$arrValues = array(
			$content,
			$child->link,
			$menuLang->text,
			$menuLang->title,
			$child->depth(),
			$this->isActive($child),
		);

		$key = ($child->depth() >= 2) ? 'sub_menu_item' : 'menu_item';
		$depthKey = '_depth-'.$child->depth();
 		$key = $this->searchThemeKey($child->slug, $key, $theme, $depthKey);
		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	public function themeReplaceEav($child, $theme, $content)
	{
		$arrKeys = array();
		$arrValues = array();
		foreach((array)$theme['attributes'] as $attribute)
		{
			$arrKeys[] = '{'.$attribute['key'].'}';
			$arrValues[] = (isset($child->{$attribute['key']}) ? $child->{$attribute['key']} : '');
		}

		return str_replace($arrKeys, $arrValues, $content);		
	}

	/**
	 * Check if the key exist.
	 * In priority : [key]_with_children_depth-[n]
	 * Next 	   : [key]_depth-[n]
	 * Next        : [key]_with_children
	 * And finally : [key]
	 * 
	 * @param  string $key         
	 * @param  array  $theme       
	 * @param  string $depthKey    
	 * @param  string $childrenKey 
	 * @return string              
	 */
	public function searchThemeKey($slug, $key, $theme, $depthKey = '', $childrenKey = '')
	{
		$slug .= '|';

		if (isset($theme[$slug.$key.$childrenKey.$depthKey])) return $slug.$key.$childrenKey.$depthKey;
		else if (isset($theme[$key.$childrenKey.$depthKey])) return $key.$childrenKey.$depthKey;

		else if (isset($theme[$slug.$key.$depthKey])) return $slug.$key.$depthKey;
		else if (isset($theme[$key.$depthKey])) return $key.$depthKey;

		else if (isset($theme[$slug.$key.$childrenKey])) return $slug.$key.$childrenKey;
		else if (isset($theme[$key.$childrenKey])) return $key.$childrenKey;

		else if (isset($theme[$slug.$key])) return $slug.$key;
		else return $key;
	}

	/**
	 * Generate the link in inner_item.
	 * @param  Menu $child 
	 * @return string        
	 */
	public function generateLink($child)
	{
		// Normal link
		if ($child->use_router == false) return $child->link;

		// Use router
		$params = unserialize(base64_decode($child->named_params));
		$link = \Router::get($child->link, $params);
		return $link;		
	}

	/**
	 * Set if the menu/link is active
	 * @param  Menu  $child 
	 * @return boolean      
	 */
	public function isActive($child)
	{
		$link = str_replace(\Uri::base(), '', $this->generateLink($child));
		if ('/'.\Uri::string() == $link || \Uri::string() == $link) return 'active';
		return '';
	}
}