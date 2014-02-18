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
);