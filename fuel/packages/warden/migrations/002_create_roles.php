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

class Create_roles {

    public function up() {

      $fields = array(
        'id'   => array('constraint' => 11, 'type' => 'int', 'unsigned' => true, 'auto_increment' => true),
        'name' => array('constraint' => 20, 'type' => 'varchar'),
        'description' => array('constraint' => 100, 'type' => 'varchar')
      );

      if(!\DBUtil::table_exists('roles')) {
  		  \DBUtil::create_table('roles', $fields, array('id'), false, 'InnoDB', 'utf8_unicode_ci');

      	\DB::query("ALTER TABLE ".\DB::table_prefix('roles')."
          ADD UNIQUE index_roles_on_name(name)",
     		\DB::UPDATE)->execute();
      }

      if(!\DBUtil::table_exists('roles_users')) {
  	    \DBUtil::create_table('roles_users', array(
            'role_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
            'user_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true)
          ), 
          array('role_id', 'user_id'), false, 'InnoDB', 'utf8_unicode_ci'
        );

      	\DB::query("ALTER TABLE ".\DB::table_prefix('roles_users')."
          ADD KEY index_roles_users_on_user_id(user_id),
          ADD CONSTRAINT fk_index_roles_users_on_user_id
            FOREIGN KEY (user_id)
            REFERENCES ".\DB::table_prefix('users')." (id) ON DELETE CASCADE,
          ADD CONSTRAINT fk_index_roles_users_on_role_id
            FOREIGN KEY (role_id)
            REFERENCES ".\DB::table_prefix('roles')." (id) ON DELETE CASCADE",
          \DB::UPDATE
        )
        ->execute();
      }

      return true;
    }

    public function down() {
    	return true;
    }
}