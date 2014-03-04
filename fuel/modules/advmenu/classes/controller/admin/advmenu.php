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

namespace Advmenu;

class Controller_Admin_Advmenu extends \Controller_Base_Admin
{

    public function before()
    {
        parent::before();

        \Theme::instance()->set_partial('subnavigation', 'partials/subnavigation');
/*        \Theme::instance()->set_partial('subnavigation', 'partials/subnavigation');

        if(!\Warden::can(array('read'), 'users'))
        {
            \Messages::warning('Ups. You have not the permission to do this action.');
            \Fuel\Core\Response::redirect('admin');
        }     */
    }

    public function action_index()
    {

        

        return \Theme::instance()
                ->get_template()
                ->set(  'title', 'Manage Menues')
                ->set(  'content', 
                        \Theme::instance()->view('admin/index')
                    );
       

    }
	
}