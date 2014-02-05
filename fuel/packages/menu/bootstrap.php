<?php
/**
 * FuelPHP DbMenu Package
 *
 * @author     Phil Foulston
 * @version    1.0
 * @package    Fuel
 * @subpackage DbMenu
 */

Autoloader::add_core_namespace('Menu');

Autoloader::add_classes(array(
	'Menu\\Menu'             => __DIR__.'/classes/menu.php',
));


/* End of file bootstrap.php */