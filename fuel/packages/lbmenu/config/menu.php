<?php

return array(
	'output' => array(
		'active' => 'active',
		'has_active' => 'has_active',
	),

	'theme_default' => 'default',
	'theme_fallback' => 'default',
	'themes' => array(

		// Default simple HTML Menu
		'default' => array(
	        'menu' => '<ul class="menu">{menu}</ul>',
	        'menu_item' => '<li class="item depth-1 {active}">{item} {submenu}</li>',
	        'menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',

	        'sub_menu' => '<ul class="sub-menu depth-{depth}">{menu}</ul>',
	        'sub_menu_item' => '<li class="sub-item depth-{depth} {active}">{item} {submenu}</li>',
	        'sub_menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
		),

		// Menu navbar for Bootstrap 2
		'bootstrap-2' => array(
			'menu' => '<ul class="nav">{menu}</ul>',
			'menu_item' => '<li class="{active}">{item} {submenu}</li>',
			'menu_item_with_children' => '<li class="{active} dropdown">{item} {submenu}</li>',

			'menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
			'menu_item_inner_with_children' => '<a href="{link}" title="{title}" class="dropdown-toggle" data-toggle="dropdown">{text} <b class="caret"></b></a>',
		
			'sub_menu' => '<ul class="dropdown-menu">{menu}</ul>',
			'sub_menu_item' => '<li class="{active}">{item} {submenu}</li>',
			'sub_menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
		),

		// Menu navbar for Bootstrap 3
		'bootstrap-3' => array(
			'menu' => '<ul class="nav navbar-nav">{menu}</ul>',
			'menu_item' => '<li class="{active}">{item} {submenu}</li>',
			'menu_item_with_children' => '<li class="{active} dropdown">{item} {submenu}</li>',

			'menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
			'menu_item_inner_with_children' => '<a href="{link}" title="{title}" class="dropdown-toggle" data-toggle="dropdown">{text} <b class="caret"></b></a>',
		
			'sub_menu' => '<ul class="dropdown-menu">{menu}</ul>',
			'sub_menu_item' => '<li class="{active}">{item} {submenu}</li>',
			'sub_menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
		),

		// Menu for Sb-admin template
		'sb-admin' => array(
	        'menu' => '<ul class="nav" id="side-menu">{menu}</ul>',
						
	        'menu_item' => '<li class="{active} {item_class}">{item} {submenu}</li>',
	        'menu_item_inner' => '<a href="{link}" title="{title}"><i class="fa {b_icon} fa-fw"></i> {text}</a>',
	        'menu_item_inner_with_children' => '<a href="{link}" title="{title}"><i class="fa {b_icon} fa-fw"></i> {text} <span class="fa arrow"></span></a>',

	        'sub_menu' => '<ul class="nav">{menu}</ul>',
	        'sub_menu_item' => '<li class="{active}">{item} {submenu}</li>',
	        'sub_menu_item_inner' => '<a href="{link}" title="{title}"><i class="fa {b_icon} fa-fw"></i> {text}</a>',
	        'sub_menu_item_inner_with_children' => '<a href="{link}" title="{title}"><i class="fa {b_icon} fa-fw"></i> {text} <span class="fa arrow"></span></a>',

	        'sub_menu_depth-2' => '<ul class="nav nav-second-level">{menu}</ul>',
	        'sub_menu_depth-3' => '<ul class="nav nav-third-level">{menu}</ul>',

	        'attributes' => array(
	        	array(
        			'key' => 'b_icon',
        			'label' => 'FontAwesome icon',
        			'default' => 'fa-bars'
        		),
	        	array(
        			'key' => 'item_class',
        			'label' => 'Classes de l\'item',
        			'default' => 'item'
        		),
        	),
		),
	),

	'menus' => array(
		'frontend' => array(
			'slug' => 'frontend',
			'theme' => 'default',
			'text' => 'Frontend',
			'children' => array(
				'homepage' => array(
					'slug' => 'homepage',
					'text' => 'Homepage',
					'menu_langs' => array(
						'fr' => array(
							'text' => 'Page d\'accueil',
						),
						'en' => array(
							'text' => 'Homepage',
						),
					),
					'link' => '/',
					'eav' => array('b_icon' => 'fa-home'),
				),
				'page' => array(
					'slug' => 'page',
					'text' => 'Pages',
					'eav' => array('b_icon' => 'fa-file-o'),
					'children' => array(
						'intro' => array(
							'slug' => 'intro',
							'text' => 'Introduction',
							'link' => '/page/intro',
							'eav' => array('b_icon' => 'fa-fil-o'),
						),
						'conclusion' => array(
							'slug' => 'conclusion',
							'text' => 'Conclusion',
							'link' => '/page/conclusion',
							'eav' => array('b_icon' => 'fa-fil-o'),
						),
					),
				),
				'contact' => array(
					'slug' => 'contact',
					'link' => '/contact',
					'text' => 'Contact',
					'eav' => array('b_icon' => 'fa-phone'),
				),
			),
		),
	),
);