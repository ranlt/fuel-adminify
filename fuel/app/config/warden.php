<?php
return array(
	'default_role' => 'Users',
	'password' => 
	array(
		'validate' => true,
		'min_length' => 6,
		'max_length' => 32,
	),
	'rememberable' => 
	array(
		'in_use' => true,
		'key' => '__warden_remember_me_token__',
		'ttl' => 1209600,
	),
	'profilable' => true,
	'trackable' => true,
	'recoverable' => 
	array(
		'in_use' => true,
		'reset_password_within' => '+1 week',
		'url' => '',
	),
	'confirmable' => 
	array(
		'in_use' => true,
		'confirm_within' => '+1 week',
		'url' => '',
	),
	'lockable' => 
	array(
		'in_use' => true,
		'maximum_attempts' => 10,
		'lock_strategy' => 'sign_in_count',
		'unlock_strategy' => 'both',
		'unlock_in' => '+1 week',
		'url' => '',
	),
	'http_authenticatable' => 
	array(
		'in_use' => false,
		'method' => 'digest',
		'realm' => 'Protected by Warden',
		'users' => 
		array(
		),
	),
);
