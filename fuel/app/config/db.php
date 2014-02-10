<?php
return array(
	'active' => 'default',
	'default' => 
	array(
		'type' => 'pdo',
		'connection' => 
		array(
			'persistent' => false,
			'compress' => false,
			'dsn' => 'mysql:host=test;dbname=test',
			'username' => 'test',
			'password' => 'test',
		),
		'identifier' => '`',
		'table_prefix' => '',
		'charset' => 'utf8',
		'collation' => false,
		'enable_cache' => true,
		'profiling' => false,
		'readonly' => false,
	),
	'redis' => 
	array(
		'default' => 
		array(
			'hostname' => '127.0.0.1',
			'port' => 6379,
			'timeout' => NULL,
			'database' => 0,
		),
	),
);
