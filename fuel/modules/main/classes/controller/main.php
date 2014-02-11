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