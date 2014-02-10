<?php

namespace Fuel\Migrations;

class Migrate_permissions {

    public function up() {

		\DBUtil::create_table('permissions', array(
	      'id'   => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
	      'name' => array('constraint' => 20, 'type' => 'varchar'),
	      'resource' => array('constraint' => 30, 'type' => 'varchar'),
	      'action' => array('constraint' => 30, 'type' => 'varchar'),
	      'description' => array('constraint' => 100, 'type' => 'varchar')
	    ), array('id'), false, 'InnoDB', 'utf8_unicode_ci');

	    \DB::query("ALTER TABLE ".\DB::table_prefix('permissions')."
	                  ADD UNIQUE index_permissions_on_name(name),
	                  ADD UNIQUE index_permissions_on_resource_and_action(resource, action)",
	               \DB::UPDATE)->execute();

	    \DBUtil::create_table('roles_permissions', array(
	      'role_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
	      'permission_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true)
	    ), array('role_id', 'permission_id'), false, 'InnoDB', 'utf8_unicode_ci');

	    \DB::query("ALTER TABLE ".\DB::table_prefix('roles_permissions')."
	                  ADD KEY index_roles_permissions_on_permission_id(permission_id),
	                  ADD CONSTRAINT fk_index_roles_permissions_on_role_id
	                      FOREIGN KEY (role_id)
	                      REFERENCES ".\DB::table_prefix('roles')." (id) ON DELETE CASCADE,
	                  ADD CONSTRAINT fk_index_roles_permissions_on_permission_id
	                      FOREIGN KEY (permission_id)
	                      REFERENCES ".\DB::table_prefix('permissions')." (id) ON DELETE CASCADE",
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