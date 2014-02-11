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

namespace Users;

class Controller_Admin_Users_Roles extends \Controller_Base_Admin 
{

    public function before()
    {
        parent::before();

        \Theme::instance()->set_partial('subnavigation', 'partials/roles_subnavigation');

        /*if(!\Warden::can(array('read'), 'roles'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }  */   
    }

	public function action_index()
	{
            
        $config = array(
                    'pagination_url' => \Fuel\Core\Uri::base().'admin/users/roles/index/',
                    'total_items' => count(\Warden\Model_Role::find('all')),
                    'per_page' => 20,
                    'uri_segment' => 4

                    );

        $pagination = \Pagination::forge('roles_pagination', $config);
		$data['roles'] = \Warden\Model_Role::find('all', array(
                                                            'limit' => $pagination->per_page,
                                                            'offset' => $pagination->offset)
                                                         );
                        
        $data['pagination'] = $pagination->render();
                
        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Manage User Roles')
                ->set(  'content', 
                        \Theme::instance()->view('admin/roles/index', $data)
                    );
	}
 
	public function action_create($id = null)
	{
        /*if(!\Warden::can(array('create'), 'roles'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }*/


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


        $role = new \Warden\Model_Role();
        
		if (\Input::method() == 'POST')
		{
			

            $val = \Validation::forge();
            $val->add_callable('myvalidation');
            $val->add_field('name', 'Name', 'required|min_length[3]|max_length[20]|unique[roles.name]');
            $val->add_field('description', 'Description', 'trim|required');
            if ( $val->run() )
            {
                $role = new \Warden\Model_Role(array(
                        'name'          => $val->validated('name'),
                        'description'   => $val->validated('description'),
                ));

                foreach (\Input::post('permission') as $selected_permission) 
                {
                    if(isset($role->permissions[$selected_permission]))
                    {
                        unset($role->permissions);
                    }

                    $role->permissions[$selected_permission] = \Model_Permission::find((int)$selected_permission);
                }

                if( $role->save() )
                {
                    \Messages::success('Role successfully created.');
                    \Response::redirect('admin/users/roles');
                }
                else
                {
                    \Messages::error('Ups. Something going wrong, please try again.');
                }
            }
            else
            {
                    \Messages::error($val->error());
            }
        }
        
        $data['role']           = $role;
        $data['permissions']    = $roles_permissions;

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Create User')
                ->set(  'content', 
                        \Theme::instance()->view('admin/roles/create', $data)
                    );
	}
   
    public function action_edit($id = null)
	{
        /*if(!\Warden::can(array('update'), 'roles'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }*/
        

        $role   = \Warden\Model_Role::find_by_id($id);
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

        if (\Input::method() == 'POST')
        {
            $role = \Warden\Model_Role::find_by_id($id);
           
            $val = \Validation::forge();
            $val->add_callable('myvalidation');

            if(\Input::post('name') == $role->name)
            {
                $val->add_field('name', 'Name', 'required|min_length[3]|max_length[20]');
            }
            else
            {
                $val->add_field('name', 'Name', 'required|min_length[3]|max_length[20]|unique[roles.name]');
            }

            if(\Input::post('description'))
            {
                $val->add_field('description', 'Description', 'trim');
            }

            if($val->run())
            {
                
                $role->name         = \Input::post('name');
                $role->description	= \Input::post('description');
                

                try
                {
                    
                    foreach (\Input::post('permission') as $selected_permission) 
                    {
                        if(isset($role->permissions[$selected_permission]))
                        {
                            unset($role->permissions);
                        }

                        $role->permissions[$selected_permission] = \Model_Permission::find((int)$selected_permission);
                    }
                        
                    if($role->save())
                    {
                        \Messages::success('Updated role #' . $id);
                        \Response::redirect('admin/users/roles');
                    }
                    else
                    {
                        \Messages::warning("Nothing changed.");
                    }
                    
                }
                catch (\Orm\ValidationFailed $e)
                {
                    \Messages::error($e->getMessage());
                }
            } 
            else
            {
                \Messages::error($val->error());
            }       
        }

            
        \Breadcrumb::set("Edit Role: ".$role->name,"",4);
        $data['role'] = $role;
        $data['permissions']    = $roles_permissions;

        return \Theme::instance()
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin/roles/edit', $data)
                    );

	}
            
	public function action_delete($id = null)
	{
        /*if(!\Warden::can(array('delete'), 'roles'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }*/

		if ($role = \Warden\Model_Role::find_by_id($id))
		{
			$role->delete();

			\Messages::success('Deleted role #'.$id);
		}
		else
		{
			\Messages::error('Could not delete role #'.$id);
		}
                
        \Response::redirect('admin/users/roles');        
	}
        
     
        

}