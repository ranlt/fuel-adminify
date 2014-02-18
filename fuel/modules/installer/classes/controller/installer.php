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

namespace Installer;

use \PHPSecLib\Crypt_AES;
use \PHPSecLib\Crypt_Hash;




/**
 * Administration dashboard
 */
class Controller_Installer extends \Controller {
    /*
     * Crypto object used to encrypt/decrypt
     *
     * @var object
     */
    private static $crypter = null;

    /*
     * Hash object used to generate hashes
     *
     * @var object
     */
    private static $hasher = null;

    /*
     * Crypto configuration
     *
     * @var array
     */
    private static $config = array();

	public function before() {
		parent::before();

		$this->theme = \Theme::instance();
		$this->theme->active('installer');
		$this->theme->set_template('layouts/default');

        $this->theme->set_partial('navigation', 'partials/navigation')->set('active', '');
        $this->theme->set_partial('alert_messages', 'partials/alert_messages');
        $this->theme->get_template()->set('title', 'Installer');

        if(\Config::get('website.installed')) {
            \Response::redirect('/');
        }
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
	 * Installer start point
	 */
	public function action_index() {

		
        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'start');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('start')
                    );
	}

    /**
     * Check file permissions
     */
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

    /**
     * Database configuration
     */
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
                \Config::load(\Fuel::$env.'/db', 'database', true);
                
                //set the database information
                \Config::set('database.default.connection.dsn', 'mysql:host='.\Input::post('db_host').';dbname='.\Input::post('db_name'));
                \Config::set('database.default.connection.username', \Input::post('db_username'));
                \Config::set('database.default.connection.password', \Input::post('db_password'));
                
                // save the database config
                \Config::save(\Fuel::$env.'/db', 'database');

                
                \Config::load(\Fuel::$env.'/config', 'env_config', true);
                \Config::set('env_config.security.token_salt',\Security::generate_token());
                \Config::save(\Fuel::$env.'/config', 'env_config');
                
                /*
                \Config::load('crypt', 'crypt');
                \Config::set('crypt.crypto_key',\Security::generate_token());
                \Config::set('crypt.crypto_iv',\Security::generate_token());
                \Config::set('crypt.crypto_hmac',\Security::generate_token());
                \Config::save('crypt', 'crypt');
                */
                
                static::$crypter = new Crypt_AES();
                static::$hasher = new Crypt_Hash('sha256');

                // load the config
                \Config::load('crypt', true);
                static::$config = \Config::get('crypt', array ());

                // generate random crypto keys if we don't have them or they are incorrect length
                $update = false;
                foreach(array('crypto_key', 'crypto_iv', 'crypto_hmac') as $key)
                {
                    if ( empty(static::$config[$key]) or (strlen(static::$config[$key]) % 4) != 0)
                    {
                        $crypto = '';
                        for ($i = 0; $i < 8; $i++)
                        {
                            $crypto .= static::safe_b64encode(pack('n', mt_rand(0, 0xFFFF)));
                        }
                        static::$config[$key] = $crypto;
                        $update = true;
                    }
                }

                // update the config if needed
                if ($update === true)
                {
                    // load the file config
                    \Config::load('file', true);
                    \Config::save('crypt', static::$config);
                    chmod(APPPATH.'config'.DS.'crypt.php', \Config::get('file.chmod.files', 0666));        
                }

                static::$crypter->enableContinuousBuffer();

                static::$hasher->setKey(static::safe_b64decode(static::$config['crypto_hmac']));

                try {
                    //check database connection
                    \Database_Connection::instance()->connect();
                    
                    //create migration table
                    if(!\DBUtil::table_exists('migration')) {
                        $create = \DBUtil::create_table('migration', array(
                            'type' => array('constraint' => 25, 'type' => 'varchar'),
                            'name' => array('constraint' => 50, 'type' => 'varchar'),
                            'migration' => array('constraint' => 100, 'type' => 'varchar'),
                        ));
                    } 
                    
                    $data['next_step']  = true;
                    \Messages::success('Database settings have been saved successfully.');
                    \Messages::success('Connection to the Database was successful.');
                    \Messages::success('Migration table has been created successfully.');
                    \Messages::success('All Security tokens have been created and saved successfully.');

                } catch (\Database_Exception $e) {
                    //ups! something went wrong
                    \Messages::error($e->getMessage());  
                }
            }
            else
            {   
                $data['next_step']  = false;
                foreach ($val->error() as $field => $error)
                {
                    \Messages::error($error->get_message());
                }
            }
        }

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'settings');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('settings', $data)
                    );
	}

    /** 
     * run all existing migrations
     */
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

        //set flash message and go to the next phase
        \Messages::success('All tables has been created successfully.');
        \Response::redirect('installer/admin');

        //if you want to show some information after running the all migrations
        //you can use the pre defined view - currently it includes only the button 
        //to the next phase - feel free to extend it
        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'database');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('database')
                    );
	}

    /**
     * create the admin user
     */
	public function action_admin() {

        $data['next_step']  = false;


        if( \Input::post() ) {

            $val = \Validation::forge();
            $val->add_field('username', 'Username', 'required|valid_string[alpha,numeric]');
            $val->add_field('password', 'Password', 'required')->add_rule('min_length', 6);
            $val->add_field('email', 'E-Mail', 'required|valid_email');
            $val->set_message('required', 'The field :label is required.');
            $val->set_message('valid_string', 'The field :label require an alphanumeric string ( only a-z / A-Z / 0-9).');

            
            if ($val->run())
            {

                $data = array(
                    'username' => \Input::post('username'),
                    'email'    => \Input::post('email'),
                    'password' => \Input::post('password'),
                );

                try {
                    
                    $user = new \Warden\Model_User($data);

                    $user->is_confirmed = true;
                    
                    $roles = \Warden\Model_Role::find('all');
                    foreach ($roles as $role_key => $role_value) 
                    {
                        //\Debug::dump("post: ",$selected_role);
                        $user->roles[$role_key] = \Model_Role::find((int)$role_key);
                    }
                    
                    $user->save();
                
                
                    
                    $data['next_step']  = true;
                    \Messages::success('Admin User has been created and assigned to the Default and Admin Role.');

                } catch (Exception $e) {
                    //ups! something went wrong
                    \Messages::error($e->getMessage());  
                }
            }
            else
            {   
                $data['next_step']  = false;
                foreach ($val->error() as $field => $error)
                {
                    \Messages::error($error->get_message());
                }
            }
        }

        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'admin');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('admin_form', $data)
                    );
	}

	public function action_finish() {

        //set installed to true - this will be checked in the routes
        //to disable the access to the installer
        \Config::load('website', 'website');
        \Config::set('website.installed', true);
        \Config::save('website', 'website');


        $this->theme->get_partial('navigation', 'partials/navigation')->set('active', 'finish');
        return $this->theme
                ->get_template()
                ->set(  'content', 
                        \Theme::instance()->view('finish')
                    );
	}

    //get all existing migration files
    //started in the app migration directory
    //then package migrations
    //last but not least the modules migrations
    public function getMigrationsAvailable() {
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

    /**
     * Part of the Fuel framework.
     *
     * @package    Fuel
     * @version    1.7
     * @author     Fuel Development Team
     * @license    MIT License
     * @copyright  2010 - 2013 Fuel Development Team
     * @link       http://fuelphp.com
     */
    private static function safe_b64encode($value)
    {
        $data = base64_encode($value);
        $data = str_replace(array('+','/','='), array('-','_',''), $data);
        return $data;
    }

    private static function safe_b64decode($value)
    {
        $data = str_replace(array('-','_'), array('+','/'), $value);
        $mod4 = strlen($data) % 4;
        if ($mod4)
        {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}

/* End of file admin.php */
