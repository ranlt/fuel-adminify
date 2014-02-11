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

namespace Fuel\Migrations;

class Createtables
{

	function up()
	{
		// create Fuel Depot tables

		
		// create FuelPHP system tables

		// table sessions
		\DBUtil::create_table('sessions', array(
			'session_id' => array('type' => 'varchar', 'constraint' => 40),
			'previous_id' => array('type' => 'varchar', 'constraint' => 40),
			'user_agent' => array('type' => 'text'),
			'ip_hash' => array('type' => 'char', 'constraint' => 32),
			'created' => array('type' => 'int', 'constraint' => 10, 'unsigned' => true),
			'updated' => array('type' => 'int', 'constraint' => 10, 'unsigned' => true),
			'payload' => array('type' => 'longtext'),
		), array('session_id'));
		\DBUtil::create_index('sessions', 'previous_id', 'previous_id', 'UNIQUE');

		
	}


	function down()
	{		
		// drop FuelPHP system tables
		\DBUtil::drop_table('sessions');
	}

}
