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

class Controller_Admin_Users extends \Controller_Base_Admin
{

    public function before()
    {
        parent::before();

        \Theme::instance()->set_partial('subnavigation', 'partials/subnavigation');

        if(!\Warden::can(array('read'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }     
    }

	public function action_index()
	{
            
        $config = array(
                    'pagination_url' => \Fuel\Core\Uri::base().'admin/users/index/',
                    'total_items' => count(\Warden\Model_User::find('all')),
                    'per_page' => 20,
                    'uri_segment' => 4

                    );

        $pagination = \Pagination::forge('users_pagination', $config);
		$data['users'] = \Warden\Model_User::find('all', array(
                                                            'limit' => $pagination->per_page,
                                                            'offset' => $pagination->offset)
                                                         );
                        
        $data['pagination'] = $pagination->render();
        
        $this->theme->get_partial('page_header', 'partials/page_header')->set('title', 'Manage Users');
        return $this->theme
                ->get_template()
                ->set('title', 'Manage Users')
                ->set(  'content', 
                        \Theme::instance()->view('admin/users/index', $data)
                    );                
        
	}
 
	public function action_create($id = null)
	{

        

        if(!\Warden::can(array('create'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }

        $user = new \Warden\Model_User();
        
        $roles = \Warden\Model_Role::find('all');
        
        $userroles = array();
        
        foreach($roles as $key => $value)
        {             
            $userroles[$key] = $value->name;
        }
                
		if (\Input::method() == 'POST')
		{
			if ( ! \Security::check_token()) {
                \Messages::error('Ups. Security Token is missing!');
                return false;
            }
            
            $val = \Validation::forge();
            $val->add_callable('myvalidation');
            $val->add_field('username', 'Username', 'required|min_length[3]|max_length[20]|unique[users.username]');
            $val->add_field('password', 'Password', 'required|min_length[6]|max_length[20]');
            $val->add_field('email', 'E-Mail', 'required|valid_email|unique[users.email]');
            if ( $val->run() )
            {
                $user = new \Warden\Model_User(array(
                        'username' => $val->validated('username'),
                        'password' => $val->validated('password'),
                        'email'	   => $val->validated('email'),
                ));

                if( $user->save() )
                {
                    foreach (\Input::post('role') as $selected_role) 
                    {
                        //\Debug::dump("post: ",$selected_role);
                        $user->roles[$selected_role] = \Model_Role::find((int)$selected_role);
                    }
                    $user->save();
                    \Messages::success('Account successfully created.');
                    \Response::redirect('admin/users');
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
        
        $data['user'] = $user;
        $data['roles'] = $userroles;

        return \Theme::instance()
                ->get_template()
               // ->set(  'title', 'Create User')
                ->set(  'content', 
                        \Theme::instance()->view('admin/users/create', $data)
                    );
	}
   
    public function action_edit($id = null)
	{
        if(!\Warden::can(array('update'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }
        
        $user   = \Warden\Model_User::find_by_id($id);
        $roles  = \Warden\Model_Role::find()->get();

        $userroles = array();
        foreach($roles as $key => $value)
        {
            $userroles[$key] = $value->name;
        }
            
        if (\Input::method() == 'POST')
        {
            $user = \Warden\Model_User::find_by_id($id);
           
            $val = \Validation::forge();
            $val->add_callable('myvalidation');

            if(\Input::post('username'))
            {
                $val->add_field('username', 'Username', 'required|min_length[3]|max_length[20]');
            }

            if(\Input::post('email'))
            {
                $val->add_field('email', 'E-Mail', 'required|valid_email');
            }

            if($val->run())
            {
                
                $user->username        = \Input::post('username');
                $user->email	       = \Input::post('email');
                $user->is_confirmed    = (\Input::post('is_confirmed') == 1) ? 1 : 0;
                
                if(\Input::post('password'))
                {
                    $user->encrypted_password  =  \Warden::encrypt_password( \Input::post('password') );
                }

                try
                {
                    foreach (\Input::post('role') as $selected_role) 
                    {
                        if(isset($user->roles[$selected_role]))
                        {
                            unset($user->roles);
                        }
                        $user->roles[$selected_role] = \Model_Role::find((int)$selected_role);
                    }
                        
                    if($user->save())
                    {
                        \Messages::success('Updated user #' . $id);
                        \Response::redirect('admin/users');
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

            
        \Breadcrumb::set("Edit User: ".$user->username,"",3);
        $data['user'] = $user;
        $data['roles'] = $userroles;

        return \Theme::instance()
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin/users/edit', $data)
                    );

	}
            
	public function action_delete($id = null)
	{
        if(!\Warden::can(array('delete'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }

		if ($user = \Warden\Model_User::find_by_id($id))
		{
			$user->delete();

			\Messages::success('Deleted user #'.$id);
		}
		else
		{
			\Messages::error('Could not delete user #'.$id);
		}
                
        \Response::redirect('admin/users');        
	}
        
    public function action_activate($id = null)
	{
        if(!\Warden::can('update', 'users'))
        {
            \Messages::set_flash('notice', 'Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }

		$user = \Warden\Model_User::find_by_id($id);

		$user->is_confirmed = 1;
        $user->confirmation_token = NULL;
        if ($user->save())
        {
                \Messages::success('User activated!');
                \Response::redirect('admin/users');
        }

        else
        {
                \Messages::error('Ups, something going wrong.');
                \Response::redirect('admin/users');
        }

        \Response::redirect('admin/users');
	}

    
    public function action_deactivate($id = null)
	{
        if(!\Warden::can(array('create', 'update', 'delete'), 'users'))
        {
            \Messages::set_flash('notice', 'Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }

		$user = \Warden\Model_User::find_by_id($id);

		$user->is_confirmed = 0;
        $user->confirmation_token = NULL;
        if ($user->save())
        {
            \Messages::success('User deactivated!');
            \Response::redirect('admin/users');
        }

        else
        {
            \Messages::error('Ups, something going wrong.');
            \Response::redirect('admin/users');
        }
	}

    public function action_inactive()
    {
            
        $config = array(
                    'pagination_url' => \Fuel\Core\Uri::base().'admin/users/inactive/',
                    'total_items' => count(\Warden\Model_User::find('all', array('where' => array(array('is_confirmed', 0))))),
                    'per_page' => 20,
                    'uri_segment' => 4

                    );

        $pagination = \Pagination::forge('users_pagination', $config);
        $data['users'] = \Warden\Model_User::find('all', array(
                                                            'where'     => array(array('is_confirmed', 0)),
                                                            'limit'     => $pagination->per_page,
                                                            'offset'    => $pagination->offset)
                                                         );
                        
        $data['pagination'] = $pagination->render();
                
        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Inactive Users')
                ->set(  'content', 
                        \Theme::instance()->view('admin/users/index', $data)
                    );
    }   
        

}