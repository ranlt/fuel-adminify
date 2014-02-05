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

class Controller_Admin_Main extends \Controller_Admin
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
								\Theme::instance()->view('admin/main/index')
							);
	}	
}