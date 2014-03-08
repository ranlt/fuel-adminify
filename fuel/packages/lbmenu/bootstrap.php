<?php

/**
 * LbMenu : Manage and Build Menu
 *
 * @package    LbMenu
 * @version    v1.00
 * @author     Julien Huriez
 * @license    MIT License
 * @copyright  2013 Julien Huriez
 * @link   https://github.com/jhuriez/fuel-lbMenu-package
 */
Autoloader::add_core_namespace('LbMenu');

Autoloader::add_classes(array(
    'LbMenu\\Menu' => __DIR__ . '/classes/menu.php',
    'LbMenu\\Menu_Db' => __DIR__ . '/classes/menu/db.php',
    'LbMenu\\Menu_Array' => __DIR__ . '/classes/menu/array.php',
    'LbMenu\\Helper_Menu' => __DIR__ . '/classes/helper/menu.php',
    'LbMenu\\Helper_Array' => __DIR__ . '/classes/helper/array.php',
    'LbMenu\\Model_Menu' => __DIR__ . '/classes/model/menu.php',
    'LbMenu\\Model_Lang' => __DIR__ . '/classes/model/lang.php',
    'LbMenu\\Model_Attribute' => __DIR__ . '/classes/model/attribute.php',
    'LbMenu\\Observer_Serialize' => __DIR__ . '/classes/observer/serialize.php',
));

// Load config
\Config::load('menu', true);

/* End of file bootstrap.php */
