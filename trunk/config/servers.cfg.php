<?php
class ServerConfig {
	public static $SERVERS = array(
		/********************* SERVER CONFIGURATION *********************/
		
		'ям Private' => array(
			'address'		=> 'localhost',
			'port'			=> 2086,
			'matchsettings'	=> 'rmp',
			'adminlevel'	=> array('SuperAdmin' => array('192.168.0.2', '192.168.0.3'), 'Admin' => 'local', 'User' => 'all')
		),
		'ям Relay #1' => array(
			'address'		=> 'localhost',
			'port'			=> 2087,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'all')
		),
		'ям Relay #2' => array(
			'address'		=> 'localhost',
			'port'			=> 2088,
			'matchsettings'	=> '',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'all')
		),
	);
}
?>