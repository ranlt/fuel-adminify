<?php

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 * 
 * @package  app
 * @extends  Controller
 */

namespace main;

class Controller_Main extends \Controller_Base_Public
{


	public function before()
	{

		parent::before();

		

	}


	/**
	 * The basic welcome message
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		return \Theme::instance()
						->get_template()
						->set(	'content', 
								\Theme::instance()->view('main/index')
							);
	}

	/**
	 * The 404 action for the application.
	 * 
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return \Theme::instance()
						->get_template()
						->set(	'content', 
								\Theme::instance()->view('main/404')
							);
	}

	
}