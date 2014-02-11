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

class Create_userdata {

  public function up() {
    //Create Default User role
    
    $default_role = new \Warden\Model_Role();
    $default_role->name   = "Users";
    $default_role->description   = "Default Role";

    $default_role->save();

    //create initial permissions
    $init_permissions = array(
      array('name' => 'controlpanel',   'resource' => 'controlpanel', 'action' => 'execute',  'description' => 'access to the control panel'),
      array('name' => 'users - create', 'resource' => 'users',        'action' => 'create',   'description' => 'Create User'),
      array('name' => 'users - update', 'resource' => 'users',        'action' => 'update',   'description' => 'Update User'),
      array('name' => 'users - delete', 'resource' => 'users',        'action' => 'delete',   'description' => 'Delete User'),
      array('name' => 'users - read',   'resource' => 'users',        'action' => 'read',     'description' => 'List users'),
      array('name' => 'Roles - Read',   'resource' => 'roles',        'action' => 'read',     'description' => 'List User Roles'),
      array('name' => 'Roles - Create', 'resource' => 'roles',        'action' => 'create',   'description' => 'Create User Role'),
      array('name' => 'Roles - Update', 'resource' => 'roles',        'action' => 'update',   'description' => 'Update User Role'),
      array('name' => 'Roles - Delete', 'resource' => 'roles',        'action' => 'delete',   'description' => 'Delete User Roles')
    );

    foreach ($init_permissions as $init_permission) {
      
      $new_permission = new \Warden\Model_Permission(array(
                        'name'            => $init_permission['name'],
                        'resource'        => $init_permission['resource'],
                        'action'          => $init_permission['action'],
                        'description'     => $init_permission['description'],
      ));

      $new_permission->save();
    }

     //create admin role
    $admin_role = new \Warden\Model_Role();
    $admin_role->name   = "Admin";
    $admin_role->description   = "Administrator Role";


    //assign all permissions to the admin role
    $permissions    = \Warden\Model_Permission::find('all', array('order_by' => array('resource' => 'asc')));

        $roles_permissions = array();
        foreach($permissions as $key => $value)
        {
            $roles_permissions[$value->resource][] = array( "id"            => (int)$value->id, 
                                                            "resource"      => $value->resource,
                                                            "action"        => $value->action,
                                                            "name"          => $value->name,
                                                            "description"   => $value->description
                                                                    );
        }

        foreach($roles_permissions as $resource => $actions) {
          foreach($actions as $key => $action) {
            $admin_role->permissions[$action['id']] = \Warden\Model_Permission::find((int)$action['id']);
          }
        }

        //save / create admin role with all assigned permissions
        $admin_role->save();

  }

  public function down() {
    return true;        

  }

}