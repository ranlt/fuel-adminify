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

class Controller_Base_Admin extends Controller
{

	/**
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{

		parent::before();

		$result = array();

		// users need to be logged in to access this controller
		if ( ! \Warden::check())
		{
			$result = array(
				'message' => 'You need to be logged in to access that page.',
				'url' => '/users/login',
			);
		}
		elseif ( ! \Warden::can('execute', 'controlpanel'))
		{
			$result = array(
				'message' => 'Access denied. You need to be a member of staff to access that page.',
				'url' => '/',
			);
		}

		

		if ( ! empty($result))
		{
			if (\Input::is_ajax())
			{
				$this->response(array($result['message']), 403);
			}
			else
			{
				\Messages::error($result['message']);
				\Response::redirect($result['url']);
			}
		}

		parent::before();

		// load the theme template
        $this->theme = \Theme::instance();

        $this->theme->active('admin');

        $request 	= \Request::active();

        // set the page template
        $this->theme->set_template('layouts/default');
        $this->theme->set_partial('navigation', 'partials/navigation');
        $this->theme->set_partial('sidebar', 'partials/sidebar');
        //set the active module as page title - can be overwritten in the module action
		$this->theme->set_partial('page_header', 'partials/page_header')->set('title', ucfirst($request->module));
        $this->theme->set_partial('footer', 'partials/footer');
        $this->theme->set_partial('alert_messages', 'partials/alert_messages');
        $user = \Warden::current_user();
        $this->current_user = $user->username;
        View::set_global('current_user', $this->current_user);
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
}
