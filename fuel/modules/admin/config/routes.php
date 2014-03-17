<?php 

return array(
	'admin/(:segment)'                              => '$1/admin/$1/index',
    'admin/settings/(:segment)'                     => '$1/admin/settings',
    'admin/(:segment)/(:segment)'                  	=> '$1/admin/$1/$2',
    'admin/(:segment)/(:segment)/(:any)'            => '$1/admin/$1/$2/$3',
);