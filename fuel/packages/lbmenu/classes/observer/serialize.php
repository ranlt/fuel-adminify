<?php

namespace LbMenu;

class Observer_Serialize extends \Orm\Observer
{

	protected $_source;

	public function __construct($class)
	{
		$props = $class::observers(get_class($this));
		$this->_source = $props['source'];
	}

	public function before_save(\Orm\Model $model)
	{
		$value = is_array($model->{$this->_source}) ? $model->{$this->_source} : array();
		$model->{$this->_source} = base64_encode(serialize($value));
	}

	public function after_load(\Orm\Model $model)
	{
		$model->{$this->_source} = unserialize(base64_decode($model->{$this->_source}));
	}
}