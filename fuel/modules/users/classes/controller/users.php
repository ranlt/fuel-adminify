<?php

namespace Users;

class Controller_Users extends \Controller_Base_Public 
{

    public function before()
    {
        parent::before();

        \Lang::load('users', null, null, true);

    }
    
    /**
     * Index function - only a redirect to the login page
     * @access public
     * @param  [number] $userid 
     * @return void
     */
    public function action_index($userid=NULL)
    {
        \Response::redirect('login');
    }
    
    /**
     * Login function
     * @return void
     */
	public function action_login()
	{
        //check that the current user is not already logged in
        if ( \Warden::check())
        {
            \Messages::warning( \Lang::get('login_already') );
            \Response::redirect('/');
        }
        
        
        if (\Input::method() === 'POST') 
        {
            try
            {
                $remember   = (\Input::post('remember_me')==1) ? true : false;
                $user       = \Warden::authenticate(\Input::post('username_or_email'), \Input::post('password'), $remember);
                if($user)
                {
                    //login was successful
                    \Messages::success( \Lang::get('login_success') );
                    \Response::redirect('/');
                }
                else
                {
                    //invalid username and/or password
                    \Messages::error( \Lang::get('login_error') );
                    \Response::redirect('login');

                }
            } 
            catch (\Warden\Warden_Failure $ex)
            {
                //something goes wrong in the warden package
                \Messages::error($ex->getMessage());
                \Response::redirect('login');
            }
        }

        return \Theme::instance()
                        ->get_template()
                        ->set(  'content', 
                                \Theme::instance()->view('login')
                            );
	}
	
	/**
	 * The logout action.
	 * 
	 * @access  public
	 * @return  void
	 */
	public function action_logout()
	{		
		\Warden::logout();
        \Messages::success( \Lang::get('logout_success') );
		\Response::redirect('/');
	}
    
    /**
     * the register action
     * @return void
     */
    public function action_register()
    {
        //check that the current user is not already logged in
        if ( \Warden::check())
        {
            \Messages::warning( \Lang::get('login_already') );
            \Response::redirect('/');
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
                                		  )
            );
	
            if( $user->save() )
            {
                
                \Messages::success( \Lang::get('register_success') );
                \Response::redirect('/');
            }
            else
            {
                \Messages::error( \Lang::get('register_error') );
            }
        }
        else
        {
                \Messages::error( $val->error());
        }

        return \Theme::instance()
                    ->get_template()
                    ->set(  'content', 
                            \Theme::instance()->view('register')
                        );
    }

    /**
     * THe password forgot action
     * 
     * @access public
     * @return void
     */
    public function action_forgot() 
    {
        if ( \Warden::check())
        {
            \Response::redirect('/');
        }
        $val = \Validation::forge();
        $val->add_field('email', 'E-Mail', 'required|valid_email');
        
        if ( $val->run() )
        {
            $user = \Warden\Model_User::find('first', array('where' => array('email' => \Input::post('email'))));
            if ($user) 
            {
                try 
                {
                    $user->send_reset_password_instructions();
                    \Messages::info( \Lang::get('forgot_success') );
                    \Response::redirect('/');
                } 
                catch (Exception $ex) 
                {
                    \Messages::error($ex->getMessage());
                    // echo sprintf('Oops, something went wrong: %s', $ex->getMessage());
                }
            }
            else
            {
                \Messages::error( \Lang::get('forgot_error') );
            }
        }
        else
        {
                \Messages::error($val->error());
        }
        

        return \Theme::instance()
                    ->get_template()
                    ->set(  'content', 
                            \Theme::instance()->view('forgot')
                        );

    }

    /**
     * The Password reset action
     * 
     * @access public
     * @return void
     */
    public function action_reset()
    {
        // Resetting the password
        if ( \Warden::check())
        {
            \Response::redirect('/');
        }
        $val = \Validation::forge();
        $val->add_field('new_password', 'Password', 'required|min_length[6]|max_length[20]');
        if ( $val->run() )
        {
            try 
            {
                $user = \Warden\Model_User::reset_password_by_token(\Uri::segment(3), \Input::post('new_password'));

                if ($user) {
                    \Messages::success( \Lang::get('reset_success') );
                    \Response::redirect('login');

                } else {
                    \Messages::error( \Lang::get('reset_error') );
                    \Response::redirect('/');

                }
            } 
            catch (Exception $ex) 
            {
                // something went wrong
            // echo sprintf('Oops, something went wrong: %s', $ex->getMessage());
                \Messages::error($ex->getMessage());
                \Response::redirect('/');


            }
        }
        else
        {
            \Messages::error($val->error());
        }
        
        return \Theme::instance()
                    ->get_template()
                    ->set(  'content', 
                            \Theme::instance()->view('reset')
                        );
    }


    /**
     * The account confirmation action
     * 
     * @access public
     * @param String    token   the confirm token
     * @return void
     */
    public function action_confirm($token=null) 
    {
        
        if ( \Warden::check())
        {
            \Response::redirect('/');
        }
        
        if($token!=null)
        {
            try {
                \Warden\Model_User::confirm_by_token($token);


                    \Messages::success( \Lang::get('confirm_success') );
                    \Response::redirect('login');


            } catch (\Warden\Warden_Failure $ex) {
                // something went wrong
                \Messages::error($ex->getMessage());
            \Response::redirect('confirm');

            }
        }
        
        $val = \Validation::forge();
        $val->add_field('email', 'E-Mail', 'required|valid_email');
        
        if ( $val->run() )
        {
            $user = \Warden\Model_User::find('first', array('where' => array('email' => \Input::post('email'))));
            if ($user) 
            {
                if($user->is_confirmed == 1)
                {    
                    \Messages::error( \Lang::get('confirm_error') );
                }
                else
                {
                    try 
                    {
                        $user->confirmation_token = null;
                        $user->save();
                        $user->send_confirmation_instructions();
                        \Messages::info( \Lang::get('confirm_success') );
                        \Response::redirect('/');
                    } 
                    catch (Exception $ex) 
                    {
                        \Messages::error($ex->getMessage());
                        // echo sprintf('Oops, something went wrong: %s', $ex->getMessage());
                    }
                }
            }
            else
            {
                \Messages::error( \Lang::get('confirm_error') );
            }
        }
        else
        {
                \Messages::error($val->error());
        }
        
        
       return \Theme::instance()
                    ->get_template()
                    ->set(  'content', 
                            \Theme::instance()->view('confirm')
                        );
    }


    /**
     * 
     * The user profile action
     * 
     * @access public
     * @return void
     */
    public function action_profile()
    {
        //check that the current user is already logged in
        if ( !\Warden::check() )
        {
            \Messages::warning( 'Sorry, but you have to logged in to change your profile.' );
            \Response::redirect('/');
        }

        


    }
            
        
        
}