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

class Controller_Base_User extends Controller_Base_Public
{
	/**
	 * @param   none
	 * @throws  none
	 * @returns	void
	 */
	public function before()
	{
		// users need to be logged in to access this controller
		if ( ! \Warden::check())
		{
			\Messages::error('Access denied. Please login first');
			\Response::redirect('login');
		}

		parent::before();
	}

}
