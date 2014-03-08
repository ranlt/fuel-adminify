<?php
return array(


	'_root_'  			=> 'main/index',  // The default route
	'_404_'   			=> 'main/404',    // The main 404 route
	
	'login'            	=> 'users/login',
    'logout'            => 'users/logout',
    'register'          => 'users/register',
    'confirm'           => 'users/confirm',
    'confirm/(:any)'    => 'users/confirm/$1',

    'admin/(:segment)'                              => '$1/admin/$1/index',
    'admin/settings/(:segment)'                     => '$1/admin/settings',
    'admin/(:segment)/(:segment)'                  	=> '$1/admin/$1/$2',
    'admin/(:segment)/(:segment)/(:any)'            => '$1/admin/$1/$2/$3',
	
    /*'admin/menu'                    => array('menu/admin/index', 'name' => 'menu_admin_menu'),                          
    'admin/menu/view/:id'           => array('menu/admin/index', 'name' => 'menu_admin_submenu'), 
    'admin/menu/add'                => array('menu/admin/index/add', 'name' => 'menu_admin_add'),                    
    'admin/menu/add/parent/:parent' => array('menu/admin/index/add', 'name' => 'menu_admin_add_to_parent'),
    'admin/menu/add/:id'            => array('menu/admin/index/add', 'name' => 'menu_admin_edit'),                  
    'admin/menu/delete/:id'         => array('menu/admin/index/delete', 'name' => 'menu_admin_delete'),   */
	//'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);