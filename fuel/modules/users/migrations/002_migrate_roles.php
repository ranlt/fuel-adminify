<?php

namespace Fuel\Migrations;

class Migrate_roles {

    public function up() {

		\DBUtil::create_table('roles', array(
      		'id'   => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
      		'name' => array('constraint' => 20, 'type' => 'varchar'),
  			'description' => array('constraint' => 100, 'type' => 'varchar')
    	), array('id'), false, 'InnoDB', 'utf8_unicode_ci');

    	\DB::query("ALTER TABLE ".\DB::table_prefix('roles')."
              ADD UNIQUE index_roles_on_name(name)",
   		\DB::UPDATE)->execute();

	    \DBUtil::create_table('roles_users', array(
	      'role_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
	      'user_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true)
	    ), array('role_id', 'user_id'), false, 'InnoDB', 'utf8_unicode_ci');

    	\DB::query("ALTER TABLE ".\DB::table_prefix('roles_users')."
                  ADD KEY index_roles_users_on_user_id(user_id),
                  ADD CONSTRAINT fk_index_roles_users_on_user_id
                      FOREIGN KEY (user_id)
                      REFERENCES ".\DB::table_prefix('users')." (id) ON DELETE CASCADE,
                  ADD CONSTRAINT fk_index_roles_users_on_role_id
                      FOREIGN KEY (role_id)
                      REFERENCES ".\DB::table_prefix('roles')." (id) ON DELETE CASCADE",
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