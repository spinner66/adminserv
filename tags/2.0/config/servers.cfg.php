<?php
class ServerConfig {
	public static $SERVERS = array(
		/********************* SERVER CONFIGURATION *********************/
		
		'ям Private' => array(
			'address'		=> 'localhost',
			'port'			=> 2086,
			'matchsettings'	=> 'matchsettings',
			'adminlevel'	=> array('SuperAdmin' => null, 'Admin' => null, 'User' => null)
		),
		'ям Private 2' => array(
			'address'		=> 'localhost',
			'port'			=> 2086,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => null, 'Admin' => null)
		),
		'ям Private 3' => array(
			'address'		=> 'localhost',
			'port'			=> 2086,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => null)
		),
		/*
		'new server name' => array(
			'address'		=> 'localhost',
			'port'			=> 5000,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => null, 'Admin' => null, 'User' => null)
		),
		*/
	);
}
?>