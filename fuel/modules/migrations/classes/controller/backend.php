<?php

namespace Migration;

class Controller_Backend extends \Controller_Hybrid
{
    public $module = 'migration';
    public $template = 'template';
    public $dataGlobal = array();

    public function before() {
        // Theme config
        \Config::load('theme', true, false, true);
        $config = \Config::get('theme');
        $config['view_ext'] = '.php';

        // Set template 
        $this->theme = \Theme::instance($this->module, $config);
        $this->theme->set_template($this->template)->set('moduleName', $this->module);

        if (\Input::is_ajax())
        {
            return parent::before();
        }
        else
        {
            parent::before();
        }
        
        // Load language
        \Lang::load('migration', true);
        
        // Message class exist ?
        $this->dataGlobal['use_message'] = $this->use_message = class_exists('\Messages');

        // Assets
        $this->theme->asset->css(array(
            'modules/'.$this->module.'/bootstrap/css/bootstrap.css',
            'modules/'.$this->module.'/bootstrap/css/bootstrap-glyphicons.css',
            'modules/'.$this->module.'/layout.css',
            ), array(), 'layout', false);
        
        $this->theme->asset->js(array(
            'modules/'.$this->module.'/bootstrap.js',
            ), array(), 'js_footer', false);
        
        $this->theme->asset->js(array(
            'modules/'.$this->module.'/jquery.min.js',
            'modules/'.$this->module.'/jquery-ui.min.js',
            ), array(), 'js_top', false);
    }
    
    public function action_404()
    {
        $messages = array('Uh Oh!', 'Huh ?');
        $data['notfound_title'] = $messages[array_rand($messages)];
        $this->theme->set_partial('content', '404')->set($data);
    }
    
    public function after($response)
    {
        $this->theme->get_template()->set($this->dataGlobal);
        // If nothing was returned set the theme instance as the response
        if (empty($response))
        {
            $response = \Response::forge($this->theme);
        }

        if (!$response instanceof \Response)
        {
            $response = \Response::forge($response);
        }
        
        return parent::after($response);
    }
}