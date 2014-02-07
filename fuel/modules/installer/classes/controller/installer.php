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

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'settings');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('settings')
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
