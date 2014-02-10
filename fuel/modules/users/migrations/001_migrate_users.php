<?php

namespace Fuel\Migrations;

class Migrate_users {

    public function up() {

    	$fields = array(
	      'id'    => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
	      'email' => array('constraint' => 255, 'type' => 'varchar'),
	      'username' => array('constraint' => 32, 'type' => 'varchar'),
	      'encrypted_password' => array('constraint' => 60, 'type' => 'varbinary'),
	      'authentication_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => \DB::expr('NULL'))
	    );

	    $fields = array_merge($fields, array(
	      'created_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
	      'updated_at' => array('type' => 'timestamp ON UPDATE CURRENT_TIMESTAMP', 'default' => \DB::expr('CURRENT_TIMESTAMP'))
	    ));

	    \DBUtil::create_table('users', $fields, array('id'), false, 'InnoDB', 'utf8_unicode_ci');

	    \DB::query("ALTER TABLE ".\DB::table_prefix('users')."
	                  ADD UNIQUE index_users_on_email(email),
	                  ADD UNIQUE index_users_on_username(username)",
	               \DB::UPDATE)->execute();

    }

    public function down() {
        
		/*    	
		\DBUtil::drop_table('roles_users');
    	\DBUtil::drop_table('roles_permissions');
    	\DBUtil::drop_table('profiles');
    	\DBUtil::drop_table('permissions');
    	\DBUtil::drop_table('roles');
    	\DBUtil::drop_table('users');
		*/
    	return true;
    }
}