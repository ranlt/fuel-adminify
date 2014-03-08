<?php

namespace LbMenu;

class Menu
{
	protected static $_instance;

	protected static $_instances = array();

	protected $menu = array();

	/**
	 * Create Menu object
	 *
	 * @param   string    Identifier for this menu
	 * @param   array     Configuration array
	 * @return  Menu
	 */
	public static function forge($name = 'default', $items = array())
	{
		if ($exists = static::instance($name))
		{
			\Error::notice('Menu with this name exists already, cannot be overwritten.');
			return $exists;
		}

		static::$_instances[$name] = new static($name, $items);

		if ($name == 'default')
		{
			static::$_instance = static::$_instances[$name];
		}

		return static::$_instances[$name];
	}

	/**
	 * Return a specific instance, or the default instance (is created if necessary)
	 *
	 * @param   string  driver id
	 * @return  Menu
	 */
	public static function instance($instance = null)
	{
		if ($instance !== null)
		{
			if ( ! array_key_exists($instance, static::$_instances))
			{
				return false;
			}

			return static::$_instances[$instance];
		}

		if (static::$_instance === null)
		{
			static::$_instance = static::forge();
		}

		return static::$_instance;
	}	

	public function __construct($menu)
	{
		$this->menu = $this->load($menu);
	}

	protected function load()
	{
		return array();
	}

	public function render()
	{
		return '';
	}
}