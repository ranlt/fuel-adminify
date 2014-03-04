<?php

return array(
	'theme_default' => 'default',
	'theme_fallback' => 'default',
	'themes' => array(

		'default' => array(
	        'menu' => '<ul class="menu">{menu}</ul>',
	        'menu_item' => '<li class="item depth-1 {active}">{item} {submenu}</li>',
	        'menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',

	        'sub_menu' => '<ul class="sub-menu depth-{depth}">{menu}</ul>',
	        'sub_menu_item' => '<li class="sub-item depth-{depth} {active}">{item} {submenu}</li>',
	        'sub_menu_item_inner' => '<a href="{link}" title="{title}">{text}</a>',
		),

		'admin' => array(
	        'menu' => '<ol class="sortable ui-sortable">{menu}</ol>',
	        'menu_item' => '<li><div><span class="disclose"><span></span></span> {item}</div> {submenu}</li>',
	        'menu_item_inner' => '{text}',

	        'sub_menu' => '<ol>{menu}</ol>',
	        'sub_menu_item' => '<li><div><span class="disclose"><span></span></span> {item}</div> {submenu}</li>',
	        'sub_menu_item_inner' => '{text}',
		),

		'sb-admin' => array(
	        'menu' => '<nav class="navbar-default navbar-static-side" role="navigation">
	        				<div class="sidebar-collapse">
	        					<ul class="nav" id="side-menu">
	        						{menu}
        						</ul>
    						</div>
						</nav>',
						
	        'menu_item' => '<li class="{active} {item-class}">{item} {submenu}</li>',
	        'menu_item_inner' => '<a href="{link}" title="{title}"><i class="fa {b-icon} fa-fw"></i> {text}</a>',
	        'menu_item_inner_with_children' => '<a href="{link}" title="{title}"><i class="fa {b-icon} fa-fw"></i> {text} <span class="fa arrow"></span></a>',

	        'sub_menu' => '<ul class="nav">{menu}</ul>',
	        'sub_menu_item' => '<li class="{active}">{item} {submenu}</li>',
	        'sub_menu_item_inner' => '<a href="{link}" title="{title}"><i class="fa {b-icon} fa-fw"></i> {text}</a>',
	        'sub_menu_item_inner_with_children' => '<a href="{link}" title="{title}"><i class="fa {b-icon} fa-fw"></i> {text} <span class="fa arrow"></span></a>',

	        'sub_menu_depth-2' => '<ul class="nav nav-second-level">{menu}</ul>',
	        'sub_menu_depth-3' => '<ul class="nav nav-third-level">{menu}</ul>',

	        'attributes' => array(
	        	array(
        			'key' => 'b-icon',
        			'label' => 'FontAwesome icon',
        			'default' => 'fa-bars'
        		),
	        	array(
        			'key' => 'item-class',
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
					'eav' => array('b-icon' => 'fa-home'),
				),
				'page' => array(
					'slug' => 'page',
					'text' => 'Pages',
					'eav' => array('b-icon' => 'fa-file-o'),
					'children' => array(
						'intro' => array(
							'slug' => 'intro',
							'text' => 'Introduction',
							'link' => '/page/intro',
							'eav' => array('b-icon' => 'fa-fil-o'),
						),
						'conclusion' => array(
							'slug' => 'conclusion',
							'text' => 'Conclusion',
							'link' => '/page/conclusion',
							'eav' => array('b-icon' => 'fa-fil-o'),
						),
					),
				),
				'contact' => array(
					'slug' => 'contact',
					'link' => '/contact',
					'text' => 'Contact',
					'eav' => array('b-icon' => 'fa-phone'),
				),
			),
		),
	),
);