<?php

namespace Fuel\Migrations;

class Create_menu
{
	public function up()
	{
		\DBUtil::create_table('menu_menu', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'slug' => array('constraint' => 255, 'type' => 'varchar'),
			'link' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'active' => array('type' => 'boolean'),
			'is_blank' => array('type' => 'boolean'),
			'theme' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'use_router' => array('type' => 'boolean'),
			'named_params' => array('constraint' => 255, 'type' => 'varchar', 'null' => true),
			'left_id' => array('constraint' => 11, 'type' => 'int'),
			'right_id' => array('constraint' => 11, 'type' => 'int'),
			'tree_id' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('menu_menu');
	}
}