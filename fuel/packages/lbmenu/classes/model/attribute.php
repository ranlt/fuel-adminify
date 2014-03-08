<?php

namespace LbMenu;

class Model_Attribute extends \Orm\Model
{
    protected static $_properties = array(
        'id',				// primary key
        'id_menu',			// foreign key
        'key',				// attribute column
        'value',			// value column
    );

    protected static $_table_name = 'menu_attribute';
}