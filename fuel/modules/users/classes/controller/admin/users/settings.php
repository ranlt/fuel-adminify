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

class Controller_Admin_Users_Settings extends \Controller_Base_Admin 
{

    public function before() {
        parent::before();
    }

    public function action_index()
    {
       /*
        if(!\Warden::can(array('create', 'update', 'delete'), 'users'))
        {
            \Session::set_flash('notice', 'Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('/');
        }
        */


        if (\Input::method() == 'POST')
        {


            //fields for general settngs
            \Config::set('warden.lifetime', (int)\Input::post('warden_lifetime'));
            \Config::set('warden.default_role', \Input::post('warden_default_role'));
            \Config::set('warden.trackable', (\Input::post('warden_trackable') == 1) ? true : false);
            \Config::set('warden.profilable', (\Input::post('warden_profilable') == 1) ? true : false);

            //fields for recover
            \Config::set('warden.recoverable.in_use', (\Input::post('warden_recoverable_in_use') == 1) ? true : false);
            \Config::set('warden.recoverable.reset_password_within', \Input::post('warden_recoverable_reset_password_within'));
            \Config::set('warden.recoverable.url', \Input::post('warden_recoverable_url'));
            
            //fields for confirm
            \Config::set('warden.confirmable.in_use', (\Input::post('warden_confirmable_in_use') == 1) ? true : false);
            \Config::set('warden.confirmable.confirm_within', \Input::post('warden_confirmable_confirm_within'));
            \Config::set('warden.confirmable.url', \Input::post('warden_confirmable_url'));
            
            //fields for lock
            \Config::set('warden.lockable.in_use', (\Input::post('warden_lockable_in_use') == 1) ? true : false);
            \Config::set('warden.lockable.maximum_attempts', (int)\Input::post('warden_lockable_maximum_attempts'));
            \Config::set('warden.lockable.lock_strategy', \Input::post('warden_lockable_lock_strategy'));
            \Config::set('warden.lockable.unlock_strategy', \Input::post('warden_lockable_unlock_strategy'));
            \Config::set('warden.lockable.unlock_in', \Input::post('warden_lockable_unlock_in'));
            \Config::set('warden.lockable.url', \Input::post('warden_lockable_url'));
            
            
            
            
            if(\Config::save('warden', 'warden'))
            {
                \Messages::success('User Settings are saved successfully.');
            }
            else
            {
                \Messages::error('User Settings could not saved.');
            }
        }
        $this->theme->get_partial('page_header', 'partials/page_header')->set('title', 'User Settings');
        return $this->theme
                ->get_template()
                ->set('title', 'User Settings')
                ->set(  'content', 
                        \Theme::instance()->view('admin/settings/form')
                    );

    }
	
}