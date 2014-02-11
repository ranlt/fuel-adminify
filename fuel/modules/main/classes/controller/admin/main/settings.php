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

namespace Main;

class Controller_Admin_Main_Settings extends \Controller_Base_Admin
{

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
            \Config::set('website.website_title', \Input::post('website_title'));
            
            if(\Config::save('website', 'website'))
            {
                \Messages::success('Website Settings are saved successfully.');
            }
            else
            {
                \Messages::error('Website Settings could not saved.');
            }
        }




        return \Theme::instance()
                ->get_template()
                ->set('title', 'Main Settings')
                ->set(  'content', 
                        \Theme::instance()->view('admin/settings/form')
                    );

    }
	
}