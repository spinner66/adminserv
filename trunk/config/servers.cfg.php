<?php
class ServerConfig {
	public static $SERVERS = array(
		/********************* SERVER CONFIGURATION *********************/
		
		'ям Private' => array(
			'address'		=> 'localhost',
			'port'			=> 2086,
			'mapsbasepath'	=> '',
			'matchsettings'	=> 'rmp',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'all')
		),
		'ям Shootmania private' => array(
			'address'		=> 'localhost',
			'port'			=> 2087,
			'mapsbasepath'	=> '',
			'matchsettings'	=> 'rmsm',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'none')
		),
		'ям Rally-Infernal' => array(
			'address'		=> 'webtof.org',
			'port'			=> 2086,
			'mapsbasepath'	=> '',
			'matchsettings'	=> 'MatchSettings/rally-infernal',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'all')
		),
		'ям Shootmania' => array(
			'address'		=> 'webtof.org',
			'port'			=> 2087,
			'mapsbasepath'	=> '',
			'matchsettings'	=> 'MatchSettings/rm-server1',
			'adminlevel'	=> array('SuperAdmin' => 'all', 'Admin' => 'all', 'User' => 'none')
		),
	);
}
?>