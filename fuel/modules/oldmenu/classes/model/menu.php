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


class Model_Menu extends \Orm\Model
{
    protected static $_table_name = 'menu';   
    
    protected static $_belongs_to = array(
        'menu_categories' => array(
            'key_from' => 'catid',
            'model_to' => 'Menu\Model_Categories',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        )
    );


    protected static $_properties = array(
        'id',
        'catid',
        'parent',
        'title',
        'link',
        'position',
        'active',
        'divider',
        'menuicon',
        'created_at',
        'updated_at'
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

    public static function get_Menu($catid, $parent) 
    { 
        
        $result = Model_Menu::find('all', array(
                'where' => array(
                            array('catid', $catid),
                            array('parent', $parent)
                        ),
                'order_by' => array('position')        
        ));

        return $result;
    } 
}
?>