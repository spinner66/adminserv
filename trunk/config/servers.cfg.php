<?php
class ServerConfig {
	public static $SERVERS = array(
		/********************* SERVER CONFIGURATION *********************/
		
		'new server name' => array(
			'address'		=> 'localhost',
			'port'			=> 5000,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => 'public', 'Admin' => 'public', 'User' => 'public')
		),
		/*
		'new server name' => array(
			'address'		=> 'localhost',
			'port'			=> 5000,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => 'public', 'Admin' => 'public', 'User' => 'public')
		),
		*/
	);
}
?>