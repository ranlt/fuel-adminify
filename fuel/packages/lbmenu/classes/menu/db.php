<?php

namespace LbMenu;

class Menu_Db extends \LbMenu\Menu
{
	/**
	 * Load the menu from id, slug or object
	 * @param  mixed $menu 
	 * @return Menu       
	 */
	protected function load($menu = null, $strict = false)
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
			if ($strict)
			{
				throw new \Exception('Menu '.$menu.' not found');
			} 
			return false;
		}

		return $menu;
	}

    /**
     * Dump the menu as array with language data
     * @return array            
     */
    public function dump()
    {
    	if ($this->menu === false) return array();

        $menuArr = current($this->menu->dump_tree());
        $menuArr = \LbMenu\Helper_Menu::recursiveGetLang($menuArr);
        return $menuArr;
    }

	/**
	 * Render the Menu
	 * @param  array $theme
	 * @return string        
	 */
	public function render($theme = null)
	{
		if ($this->menu === false) return '';

		$theme = \LbMenu\Helper_Menu::getTheme($this->menu);
		$this->dump_tree = current($this->menu->dump_tree());
        $html = $this->buildMenu($this->dump_tree, $theme);
        echo $html;
	}

	/**
	 * Build the menu in HTML from theme config
	 * @param  array  $menu 
	 * @param  array  $theme 
	 * @param  boolean $main  
	 * @return string         
	 */
	public function buildMenu($menu, $theme, $main = true, $depth = 0)
	{
		$depth++;
		$output = "";
		if (!empty($menu['children']))
		{
			$children = $menu['children'];
			foreach($children as $child)
			{

                // Construct Text
                $menuLang = \LbMenu\Helper_Menu::getLang($child);
                $content = $this->themeReplaceInnerItem($child, $menuLang, $theme, $depth);

                // Construct Item
            	$item = $this->themeReplaceItem($child, $menuLang, $theme, $content, $depth);

                // If contains submenu
                $submenu = (!empty($child['children'])) ? $this->buildMenu($child, $theme, false, $depth) : '';

                // Construct Submenu
            	$output .= $this->themeReplaceSubmenu($child, $menuLang, $theme, $item, $submenu, $depth);
			}
		}
        else
        {
            return '';
        }

        // Show the menu
		return $this->themeReplaceMenu($child, $menuLang, $theme, $output, $depth);
	}

	/**
	 * Process for replace vars in Menu
	 * @param  array $child    
	 * @param  array $menuLang 
	 * @param  array $theme    
	 * @param  string $output   
	 * @return string           
	 */
	public function themeReplaceMenu($child, $menuLang, $theme, $output, $depth)
	{
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
	public function themeReplaceSubmenu($child, $menuLang, $theme, $item, $submenu, $depth)
	{
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
	public function themeReplaceInnerItem($child, $menuLang, $theme, $depth)
	{
		if (empty($menuLang['text'])) return '';

		$depth = isset($child['path']) ? count(explode('/', $child['path']))-1 : 0;

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
	public function themeReplaceItem($child, $menuLang, $theme, $content, $depth)
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

		// If EAV not loaded
		if (!isset($child['menu_attributes']))
		{
            $attributes = \LbMenu\Model_Attribute::query()->where('id_menu', $child['id'])->get();
            foreach($attributes as $k => $attribute)
            {
                $attributes[$k] = $attribute->to_array();
            }
            $child['menu_attributes'] = $attributes;
		}

		$eavs = $child['menu_attributes'];
		!isset($theme['attributes']) and $theme['attributes'] = array();
		foreach((array)$theme['attributes'] as $attribute)
		{
			$arrKeys[] = '{'.$attribute['key'].'}';

			// Get the value
			$value = '';
			foreach($eavs as $eav)
			{
				if ($eav['key'] == $attribute['key']) {
					$value = $eav['value'];
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