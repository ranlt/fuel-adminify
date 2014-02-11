<?php

namespace Fuel\Migrations;

class Create_Attribute
{
	public function up()
	{
		\DBUtil::create_table('menu_attribute', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'id_menu' => array('constraint' => 11, 'type' => 'int'),
			'key' => array('constraint' => 255, 'type' => 'varchar'),
			'value' => array('constraint' => 255, 'type' => 'varchar'),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('menu_attribute');
	}
}