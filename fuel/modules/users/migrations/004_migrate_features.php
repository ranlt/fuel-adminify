<?php

namespace Fuel\Migrations;

class Migrate_features {

    public function up() {

		\DBUtil::create_table('profiles', array(
          'id'   => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
          'user_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true)
      ), array('id'), false, 'InnoDB', 'utf8_unicode_ci');

      \DB::query("ALTER TABLE ".\DB::table_prefix('profiles')."
                    ADD KEY index_profiles_on_user_id(user_id),
                    ADD CONSTRAINT fk_index_profiles_on_user_id
                        FOREIGN KEY (user_id)
                        REFERENCES ".\DB::table_prefix('users')." (id) ON DELETE CASCADE",
                 \DB::UPDATE)->execute();

  		$fields = array(
	  		'remember_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => null),

      		'reset_password_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => null),
      		'reset_password_sent_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),

      		'is_confirmed' => array('constraint' => 1, 'type' => 'tinyint', 'unsigned' => true, 'default' => '0'),
      		'confirmation_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => \DB::expr('NULL')),
      		'confirmation_sent_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),

      		'sign_in_count' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'default' => '0'),
      		'current_sign_in_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
	      	'last_sign_in_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
	      	'current_sign_in_ip' => array('constraint' => 10, 'type' => 'int', 'unsigned' => true, 'default' => '0'),
	      	'last_sign_in_ip' => array('constraint' => 10, 'type' => 'int', 'unsigned' => true, 'default' => '0'),

	      	'unlock_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => \DB::expr('NULL')),
      		'locked_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00')
		);
      	
      	\DBUtil::add_fields('users', $fields);
      	
      	\DBUtil::create_index('users', 'remember_token', 'index_users_on_remember_token', 'unique');
      	\DBUtil::create_index('users', 'reset_password_token', 'index_users_on_reset_password_token', 'unique');
      	\DBUtil::create_index('users', 'confirmation_token', 'index_users_on_confirmation_token', 'unique');
      	\DBUtil::create_index('users', 'unlock_token', 'index_users_on_unlock_token', 'unique');


    }

    public function down() {
        
		/*    	
		\DBUtil::drop_table('roles_users');
    	\DBUtil::drop_table('roles_permissions');
    	
    	\DBUtil::drop_table('permissions');
    	\DBUtil::drop_table('roles');
    	\DBUtil::drop_table('users');
		*/
		
		\DBUtil::drop_table('profiles');
		$fields = array(
	  		'remember_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => null),

      		'reset_password_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => null),
      		'reset_password_sent_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),

      		'is_confirmed' => array('constraint' => 1, 'type' => 'tinyint', 'unsigned' => true, 'default' => '0'),
      		'confirmation_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => \DB::expr('NULL')),
      		'confirmation_sent_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00'),

      		'sign_in_count' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'default' => '0'),
      		'current_sign_in_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
	      	'last_sign_in_at' => array('type' => 'timestamp', 'default' => '0000-00-00 00:00:00'),
	      	'current_sign_in_ip' => array('constraint' => 10, 'type' => 'int', 'unsigned' => true, 'default' => '0'),
	      	'last_sign_in_ip' => array('constraint' => 10, 'type' => 'int', 'unsigned' => true, 'default' => '0'),

	      	'unlock_token' => array('constraint' => 60, 'type' => 'varbinary', 'null' => true, 'default' => \DB::expr('NULL')),
      		'locked_at' => array('type' => 'datetime', 'default' => '0000-00-00 00:00:00')
		);
		\DBUtil::drop_fields('users', array_keys($fields));
    	return true;
    }
}