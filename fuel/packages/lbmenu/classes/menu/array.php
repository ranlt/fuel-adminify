<?php

namespace LbMenu;

class Menu_Array extends \LbMenu\Menu
{
	/**
	 * Load the menu from slug
	 * @param  string $menu 
	 * @return array      
	 */
	protected function load($menu = null, $strict = false)
	{
		\Config::load('menu', true);
		$menu = $menu ? : $this->menu;
		$menu = \LbMenu\Helper_Array::find($menu);

		if ($menu === null)
		{
			if ($strict)
			{
				throw new \Exception('Menu '.$menu.' not found');
			}
			return false;
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
		if ($this->menu === false) return '';

		$theme = \LbMenu\Helper_Array::getTheme($this->menu);
        $html = $this->buildMenu($this->menu, $theme);
        echo $html;
	}

	/**
	 * Build the menu in HTML from theme config
	 * @param  array  $menu 
	 * @param  array  $theme 
	 * @param  boolean $main  
	 * @return string         
	 */
	public function buildMenu($menu, $theme, $main = true)
	{
		$output = "";

		if (!empty($menu['children']))
		{
			$children = $menu['children'];
			foreach($children as $child)
			{

                // Construct Text
                $menuLang = \LbMenu\Helper_Array::getLang($child);

                $content = $this->themeReplaceInnerItem($child, $menuLang, $theme);

                // Construct Item
            	$item = $this->themeReplaceItem($child, $menuLang, $theme, $content);

                // If contains submenu
                $submenu = (!empty($child['children'])) ? $this->buildMenu($child, $theme, false) : '';

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
	 * @param  array $child    
	 * @param  array $menuLang 
	 * @param  array $theme    
	 * @param  string $output   
	 * @return string           
	 */
	public function themeReplaceMenu($child, $menuLang, $theme, $output)
	{
		$depth = \LbMenu\Helper_Array::getDepth($child)-1;
		$arrKeys = array(
			'{menu}',
			'{depth}',
		);
		$arrValues = array(
			$output,
			$depth,
		);

		$key = ($depth >= 2) ? 'sub_menu' : 'menu';
		$depthKey = '_depth-'.$depth;
 		$key = $this->searchThemeKey($child['slug'], $key, $theme, $depthKey);
		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	/**
	 * Process for replace vars in Submenu
	 * @param  array $child    
	 * @param  array $menuLang 
	 * @param  string $item     
	 * @param  string $submenu  
	 * @return string           
	 */
	public function themeReplaceSubmenu($child, $menuLang, $theme, $item, $submenu)
	{
		$depth = \LbMenu\Helper_Array::getDepth($child)-1;

		$arrKeys = array(
			'{submenu}',
			'{depth}',
		);

		$arrValues = array(
			$submenu,
			$depth,
		);

		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $item));
	}
	/**
	 * Process for replace vars in Inner Item
	 * @param  array $child    
	 * @param  array $menuLang 
	 * @param  array $theme    
	 * @return string           
	 */
	public function themeReplaceInnerItem($child, $menuLang, $theme)
	{
		if (empty($menuLang['text'])) return '';

		$depth =\LbMenu\Helper_Array::getDepth($child)-1;

		$arrKeys = array(
			'{link}', 
			'{text}', 
			'{title}', 
			'{depth}',
		);

		$arrValues = array(
			$this->generateLink($child),
			$menuLang['text'],
			$menuLang['title'],
			$depth,
		);

		$key = ($depth >= 2) ? 'sub_menu_item_inner' : 'menu_item_inner';
		$childrenKey = (!empty($child['children'])) ? '_with_children' : '';
		$depthKey = '_depth-'.$depth;
 		$key = $this->searchThemeKey($child['slug'], $key, $theme, $depthKey, $childrenKey);

		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	/**
	 * Process for replace vars in Item
	 * @param  array $child    
	 * @param  Lang $menuLang 
	 * @param  array $theme    
	 * @param  string $content  
	 * @return string           
	 */
	public function themeReplaceItem($child, $menuLang, $theme, $content)
	{
		$depth = \LbMenu\Helper_Array::getDepth($child)-1;

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
			$child['link'],
			$menuLang['text'],
			$menuLang['title'],
			$depth,
			$this->isActive($child),
		);

		$key = ($depth >= 2) ? 'sub_menu_item' : 'menu_item';
		$childrenKey = (!empty($child['children'])) ? '_with_children' : '';
		$depthKey = '_depth-'.$depth;
 		$key = $this->searchThemeKey($child['slug'], $key, $theme, $depthKey, $childrenKey);
		return $this->themeReplaceEav($child, $theme, str_replace($arrKeys, $arrValues, $theme[$key]));
	}

	/**
	 * Replace all EAV attributes
	 * @param  array $child   
	 * @param  array $theme   
	 * @param  string $content 
	 * @return string          
	 */
	public function themeReplaceEav($child, $theme, $content)
	{
		$arrKeys = array();
		$arrValues = array();

		$eavs = $child['eav'];

		!isset($theme['attributes']) and $theme['attributes'] = array();
		foreach((array)$theme['attributes'] as $attribute)
		{
			$arrKeys[] = '{'.$attribute['key'].'}';

			// Get the value
			$value = '';
			foreach($eavs as $k => $v)
			{
				if ($k == $attribute['key']) {
					$value = $v;
					break;
				}
			}
			$arrValues[] = $value;
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
	 * @param  array $child 
	 * @return string        
	 */
	public function generateLink($child)
	{
		// Normal link
		if (!$child['use_router'])
		{
			$link = $child['link'];
		} 
		// Use router
		else
		{
			$params = $child['named_params'];
			$link = \Router::get($child['link'], $params);
		}

		$link = (substr($link, 0, 1) != '/') ? '/'.$link : $link;
		return $link;	
	}

	/**
	 * Output if the menu/link is active
	 * @param  array  $child 
	 * @return boolean      
	 */
	public function isActive($child)
	{
		return ($this->checkActive($child)) ? (\Config::get('menu.output.active') ? : 'active') : $this->hasActive($child);
	}

	/**
	 * Output if the menu has a active link in children
	 * @param  array  $child 
	 * @return boolean        
	 */
	public function hasActive($child)
	{
		foreach((array)$child['children'] as $childTmp)
		{
			if ($this->checkActive($childTmp))
				return \Config::get('menu.output.has_active') ? : 'has_active';
		}

		return '';
	}


	/**
	 * Check if the menu/link is active
	 * @param  array $child 
	 * @return boolean       
	 */
	public function checkActive($child)
	{
		$link = str_replace(\Uri::base(), '', $this->generateLink($child));
		if (!empty($link)) 
		{
			$uriArr = explode('/', \Uri::string());

			// Explore the uri segments
			do {
				$uri = '/'.implode('/', $uriArr);

				// If it's the correct uri => output active
				if ($uri == $link)
					return true;

				// Else search if the uri is in the menu
				$searchArr = explode('.', \Arr::search($this->dump_tree, $uri));
				// If it's, the menu is not active
				if(array_pop($searchArr) == 'link')
				{
					return false;
				}

				array_pop($uriArr);
			} while(!empty($uriArr));
		}

		return false; 
	}
}