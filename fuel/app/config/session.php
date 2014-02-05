<?php

return array(
	'auto_initialize' => true,
	'driver' => 'file',
	'match_ip' => false,
	'match_ua' => true,
	'cookie_domain' => '',
	'cookie_path' => '/',
	'cookie_http_only' => NULL,
	'encrypt_cookie' => true,
	'expire_on_close' => false,
	'expiration_time' => 7200,
	'rotation_time' => 300,
	'flash_id' => 'flash',
	'flash_auto_expire' => true,
	'flash_expire_after_get' => true,
	'post_cookie_name' => '',
	'header_header_name' => 'Session-Id',
	'enable_cookie' => true,
	'cookie' => 
	array(
		'cookie_name' => 'fuelcid',
	),
	'file' => 
	array(
		'cookie_name' => 'fuelfid',
		'path' => '/tmp',
		'gc_probability' => 5,
	),
	'memcached' => 
	array(
		'cookie_name' => 'fuelmid',
		'servers' => 
		array(
			'default' => 
			array(
				'host' => '127.0.0.1',
				'port' => 11211,
				'weight' => 100,
			),
		),
	),
	'db' => 
	array(
		'cookie_name' => 'fueldid',
		'database' => null,
		'table' => 'sessions',
		'gc_probability' => 5,
	),
	'redis' => 
	array(
		'cookie_name' => 'fuelrid',
		'database' => 'default',
	),
);

/* End of file session.php */