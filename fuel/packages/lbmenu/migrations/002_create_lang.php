<?php

namespace Fuel\Migrations;

class Create_lang
{
	public function up()
	{
		\DBUtil::create_table('menu_lang', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'text' => array('constraint' => 255, 'type' => 'varchar'),
			'title' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'small_desc' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'language' => array('constraint' => 255, 'type' => 'varchar'),
			'id_menu' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('menu_lang');
	}
}