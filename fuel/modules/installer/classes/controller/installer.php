<?php
/**
 * Part of Fuel Depot.
 *
 * @package    FuelDepot
 * @version    1.0
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2012 Fuel Development Team
 * @link       http://depot.fuelphp.com
 */

namespace Installer;

/**
 * Administration dashboard
 */
class Controller_Installer extends \Controller_Base_Public {

	public function before() {
		parent::before();

		$this->theme = \Theme::instance();
		$this->theme->active('installer');
		$this->theme->set_template('layouts/default');

        $this->theme->set_partial('navigation', 'partials/navigation')->set('active', '');
        $this->theme->set_partial('alert_messages', 'partials/alert_messages');
        $this->theme->get_template()->set('title', 'Installer');
	}

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index() {

		//check directory permissions

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'start');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('start')
                    );
	}

	public function action_systemcheck() {

		$data['php']		= (version_compare(phpversion(), '5.3.3', '<') ) ? false : true;
		$data['config']		= \File::get_permissions(APPPATH.'config/');
		$data['tmp']		= \File::get_permissions(APPPATH.'tmp/');
		$data['cache']		= \File::get_permissions(APPPATH.'cache/');
		$data['logs']		= \File::get_permissions(APPPATH.'logs/');
		$data['next_step']	= ($data['php'] && $data['config'] == "0777" && $data['tmp'] == "0777" && $data['cache'] == "0777" && $data['logs'] == "0777" ) ? true : false;

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'systemcheck');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('systemcheck', $data)
                    );
	}

	public function action_settings() {

        $data['next_step']  = false;


        if( \Input::post() ) {

            $val = \Validation::forge();
            $val->add_field('db_host', 'Database Host', 'required');
            $val->add_field('db_username', 'Database Username', 'required');
            $val->add_field('db_password', 'Database Password', 'required');
            $val->add_field('db_name', 'Database Name', 'required');
            $val->set_message('required', 'The field :label is required.');

            
            if ($val->run())
            {
                \Config::load('db', 'database');
                // update some config item
                \Config::set('database.default.connection.dsn', 'mysql:host='.\Input::post('db_host').';dbname='.\Input::post('db_name'));
                \Config::set('database.default.connection.username', \Input::post('db_username'));
                \Config::set('database.default.connection.password', \Input::post('db_password'));
                // save the updated config group 'foo' (note: it will save everything in that group!)
                \Config::save('db', 'database');
                // save the updated config group 'bar' to config file 'custom' in the module 'foo'

                $data['next_step']  = true;

            }
            else
            {   
                $data['next_step']  = false;
                foreach ($val->error() as $field => $error)
                {
                    \Messages::error($error->get_message());
                    // The field Title is required and must contain a value.
                }
                //\Debug::dump($val->error());
            }
        }

        //\Debug::dump(\Input::post());

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'settings');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('settings', $data)
                    );
	}

	public function action_database() {

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'database');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('database')
                    );
	}

	public function action_admin() {

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'admin');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin_form')
                    );
	}

	public function action_finish() {

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'finish');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('finish')
                    );
	}

}

/* End of file admin.php */
