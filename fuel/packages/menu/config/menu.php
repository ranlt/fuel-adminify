<?php
/**
 * FuelPHP DbMenu Package
 *
 * @author     Phil Foulston
 * @version    1.0
 * @package    Fuel
 * @subpackage DbMenu
 */

return array (
    'db'            => array (
                        'table'             => 'menu',
                        'table_category'    => 'menu_categories'
                    ),
    
    'bootstrap'     => array( 
                        'active'            => true,
                        'ul_class'          => 'class="dropdown-menu"',
                        'first_class'       => 'class="dropdown"',
                        'second_class'      => 'class="dropdown-submenu"',
                        'dropdown_icon'     => '<b class="caret"></b>',
                        'first_link_class'  => 'dropdown-toggle',
                        'first_link_toggle' => 'dropdown'
            
                    ),
);

/* End of file dbmenu.php */