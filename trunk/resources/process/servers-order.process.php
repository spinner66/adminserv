<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error( Utils::t('You are not allowed to configure the servers') );
		Utils::redirection();
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si on n'autorise pas la configuration en ligne
		if( OnlineConfig::ACTIVATE !== true ){
			AdminServ::info( Utils::t('No server available. To add one, configure "config/servers.cfg.php" file.') );
			Utils::redirection();
		}
		else{
			if( OnlineConfig::ADD_ONLY === true ){
				Utils::redirection(false, './?p=addserver');
			}
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file isn\'t recognized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ACTIONS
	if( isset($_POST['save']) && isset($_POST['list']) && $_POST['list'] != null ){
		$serverList = ServerConfig::$SERVERS;
		$list = explode(',', $_POST['list']);
		
		$i = 0;
		$newServerList = array();
		foreach($list as $listServerName){
			$newServerList[$listServerName] = array(
				'address' => $serverList[$listServerName]['address'],
				'port' => $serverList[$listServerName]['port'],
				'mapsbasepath' => (isset($serverList[$listServerName]['mapsbasepath'])) ? $serverList[$listServerName]['mapsbasepath'] : '',
				'matchsettings' => $serverList[$listServerName]['matchsettings'],
				'adminlevel' => $serverList[$listServerName]['adminlevel']
			);
			$i++;
		}
		
		AdminServServerConfig::saveServerConfig(array(), -1, $newServerList);
		AdminServLogs::add('action', 'Order server list');
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
?>