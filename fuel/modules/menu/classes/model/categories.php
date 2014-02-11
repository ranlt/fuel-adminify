<?php
/**
 * Part of Fuel Adminify.
 *
 * @package     fuel-adminify
 * @version     2.0
 * @author      Marcus Reinhardt - Pseudoagentur
 * @license     MIT License
 * @copyright   2014 Pseudoagentur
 * @link        http://www.pseudoagentur.de
 * @github      https://github.com/Pseudoagentur/fuel-adminify
 */

namespace Menu;


class Model_Categories extends \Orm\Model
{
    protected static $_table_name = 'menu_categories';   
    protected static $_has_many = array(
        'menu' => array(
            'key_from' => 'id',
            'model_to' => 'Menu\Model_Menu',
            'key_to' => 'catid',
            'cascade_save' => true,
            'cascade_delete' => false,
            )
        );
    
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => false,
        ),
    );
   
}

