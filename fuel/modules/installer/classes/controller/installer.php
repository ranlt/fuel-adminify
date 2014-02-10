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
class Controller_Installer extends \Controller {

	public function before() {
		parent::before();

		$this->theme = \Theme::instance();
		$this->theme->active('installer');
		$this->theme->set_template('layouts/default');

        $this->theme->set_partial('navigation', 'partials/navigation')->set('active', '');
        $this->theme->set_partial('alert_messages', 'partials/alert_messages');
        $this->theme->get_template()->set('title', 'Installer');
	}

    public function after($response)
    {
        // If no response object was returned by the action,
        if (empty($response) or  ! $response instanceof Response)
        {
            // render the defined template
            $response = \Response::forge(\Theme::instance()->render());
        }

        return parent::after($response);
    }

	/**
	 * The index action.
	 *
	 * @access  public
	 * @return  void
	 */
	public function action_index() {

		
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
                //get environment specific database config
                \Config::load(\FueL::$env.'/db', 'database', true);
                
                //set the database information
                \Config::set('database.default.connection.dsn', 'mysql:host='.\Input::post('db_host').';dbname='.\Input::post('db_name'));
                \Config::set('database.default.connection.username', \Input::post('db_username'));
                \Config::set('database.default.connection.password', \Input::post('db_password'));
                
                // save the database config
                \Config::save(\FueL::$env.'/db', 'database');
                
                //check database connection
                try {
                    
                    \Database_Connection::instance()->connect();
                    if(!\DBUtil::table_exists('migration')) {
                        $create = \DBUtil::create_table('migration', array(
                            
                            
                            'type' => array('constraint' => 25, 'type' => 'varchar'),
                            'name' => array('constraint' => 50, 'type' => 'varchar'),
                            'migration' => array('constraint' => 100, 'type' => 'varchar'),
                            
                        ));

                        \Debug::dump($create);
                    } 
                    
                    $data['next_step']  = true;
                } catch (\Database_Exception $e) {
                    \Messages::error($e->getMessage());  


                }

                /*

                
                */
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


        //\Debug::dump(\Migrate::latest());
        $migrations = $this->getMigrationsAvailable();
        $result = array();
        //add all found migrations to the database
        //1. app migrations 
        //2. package migrations
        //3. module migrations
        foreach ($migrations as $type => $components) {
            foreach ($components as $component => $files) {
                foreach ($files as $file) {
                   $migrationArr = explode('_', $file);
                   $result[] = \Migrate::version($migrationArr[0], $component, $type);
                }
            }
        }
        \Debug::dump($result);
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

    public function getMigrationsAvailable()
    {
        \Config::load('migrations', true);

        $migrations = array();

        // loop through app to find migrations
        foreach (glob(APPPATH.\Config::get('migrations.folder').'*_*.php') as $migration)
        {
            // Convert path to array
            $migration = str_replace(array('/', '\\'), DS, $migration);
            $migration = substr($migration, 0, strlen($migration)-4);
            $migration = explode(DS, substr($migration, strlen(APPPATH)));
            $fileName = explode('_', $migration[1]);
            $migrations['app']['default'][] = $migration[1];
            
        }

        // loop through packages to find migrations
        foreach(\Config::get('package_paths') as $packagePath)
        {
            foreach (glob($packagePath.'*'.DS.\Config::get('migrations.folder').'*_*.php') as $migration)
            {
                // Convert path to array
                $migration = str_replace(array('/', '\\'), DS, $migration);
                $migration = substr($migration, 0, strlen($migration)-4);
                $migration = explode(DS, substr($migration, strlen(APPPATH)+3));
                $fileName = explode('_', $migration[3]);

                $migrations['package'][$migration[1]][] = $migration[3];

            }
        }

        // loop through modules to find migrations
        foreach(\Config::get('module_paths') as $modulePath)
        {
            foreach (glob($modulePath.'*'.DS.\Config::get('migrations.folder').'*_*.php') as $migration)
            {
                // Convert path to array
                $migration = str_replace(array('/', '\\'), DS, $migration);
                $migration = substr($migration, 0, strlen($migration)-4);
                $migration = explode(DS, substr($migration, strlen(APPPATH)+3));
                $fileName = explode('_', $migration[3]);

                $migrations['module'][$migration[1]][] = $migration[3];
                
            }
        }

        return $migrations;
    }
}

/* End of file admin.php */
