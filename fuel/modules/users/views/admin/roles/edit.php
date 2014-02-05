<?php 
	$data['role']			= $role;
	$data['permissions']	= $permissions;
 
 	echo render('admin/roles/_form', $data); 
?>


