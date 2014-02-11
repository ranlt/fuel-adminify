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

class Controller_Admin_Users_Permissions extends \Controller_Base_Admin 
{

    public function before()
    {
        parent::before();

        \Theme::instance()->set_partial('subnavigation', 'partials/permissions_subnavigation');

        /*if(!\Warden::can(array('read'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        } */    
    }

	public function action_index()
	{
            
        $config = array(
                    'pagination_url' => \Fuel\Core\Uri::base().'admin/users/permissions/index/',
                    'total_items' => count(\Warden\Model_Permission::find('all')),
                    'per_page' => 20,
                    'uri_segment' => 4

                    );

        $pagination = \Pagination::forge('permissions_pagination', $config);
		$data['permissions'] = \Warden\Model_Permission::find('all', array(
                                                            'limit' => $pagination->per_page,
                                                            'offset' => $pagination->offset)
                                                         );
                        
        $data['pagination'] = $pagination->render();
                
        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Manage Permissions')
                ->set(  'content', 
                        \Theme::instance()->view('admin/permissions/index', $data)
                    );
	}
 
	public function action_create($id = null)
	{
        /*if(!\Warden::can(array('create'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }*/

        $permission = new \Warden\Model_Permission();
                
                
		if (\Input::method() == 'POST')
		{
			
            
            $val = \Validation::forge();
            $val->add_callable('myvalidation');
            $val->add_field('name', 'Name', 'required|min_length[3]|max_length[20]');
            $val->add_field('resource', 'Resource', 'required|min_length[3]|max_length[30]');
            $val->add_field('action', 'Action', 'required|min_length[3]|max_length[30]');
            $val->add_field('description', 'Description', 'required|min_length[3]|max_length[100]');
            if ( $val->run() )
            {
                $permission = new \Warden\Model_Permission(array(
                        'name'      => $val->validated('name'),
                        'resource'  => $val->validated('resource'),
                        'action'	=> $val->validated('action'),
                        'description'    => $val->validated('description'),
                ));

                if( $permission->save() )
                {
                    
                    $permission->save();
                    \Messages::success('Permission successfully created.');
                    \Response::redirect('admin/users/permissions');
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
        
        $data['permission'] = $permission;

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Create Permission')
                ->set(  'content', 
                        \Theme::instance()->view('admin/permissions/create', $data)
                    );
	}
   
    public function action_edit($id = null)
	{
        /*if(!\Warden::can(array('update'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }*/
        
        $permission   = \Warden\Model_Permission::find_by_id($id);

        
            
        if (\Input::method() == 'POST')
        {
            $permission = \Warden\Model_Permission::find_by_id($id);
           
            $val = \Validation::forge();
            $val->add_callable('myvalidation');
            $val->add_field('name', 'Name', 'required|min_length[3]|max_length[20]');
            $val->add_field('resource', 'Resource', 'required|min_length[3]|max_length[30]');
            $val->add_field('action', 'Action', 'required|min_length[3]|max_length[30]');
            $val->add_field('description', 'Description', 'required|min_length[3]|max_length[100]');
            

            if($val->run())
            {
                
                $permission->name        = \Input::post('name');
                $permission->resource        = \Input::post('resource');
                $permission->action        = \Input::post('action');
                $permission->description        = \Input::post('description');
                
                

                try
                {
                        
                    if($permission->save())
                    {
                        \Messages::success('Updated permission #' . $id);
                        \Response::redirect('admin/users/permissions');
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

            
        $data['permission'] = $permission;

        return \Theme::instance()
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin/permissions/edit', $data)
                    );

	}
            
	public function action_delete($id = null)
	{
        /*if(!\Warden::can(array('delete'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }*/

		if ($user = \Warden\Model_Permission::find_by_id($id))
		{
			$user->delete();

			\Messages::success('Deleted permission #'.$id);
		}
		else
		{
			\Messages::error('Could not delete permission #'.$id);
		}
                
        \Response::redirect('admin/users/permissions');        
	}
}