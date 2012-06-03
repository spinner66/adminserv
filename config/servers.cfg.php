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
	);
}
?>