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
	
	
	// EDITION
	if( isset($_POST['editserver']) ){
		$serverId = AdminServServerConfig::getServerId($_POST['server'][0]);
		Utils::redirection(false, '?p=addserver&id='.$serverId);
	}
	
	
	// DUPLIQUER
	if( isset($_POST['duplicateserver']) ){
		// GET
		$getServerData = AdminServServerConfig::getServer($_POST['server'][0]);
		
		// SET
		$setServerData = array(
			'name' => trim( htmlspecialchars( addslashes($_POST['server'][0] . ' - '.Utils::t('copy') ) ) ),
			'address' => trim($getServerData['address']),
			'port' => intval($getServerData['port']),
			'matchsettings' => trim($getServerData['matchsettings']),
			'adminlevel' => array(
				'SuperAdmin' => $getServerData['adminlevel']['SuperAdmin'],
				'Admin' => $getServerData['adminlevel']['Admin'],
				'User' => $getServerData['adminlevel']['User'],
			)
		);
		if( AdminServServerConfig::saveServerConfig($setServerData) ){
			$action = Utils::t('This server has been duplicated.');
			AdminServ::info($action);
			AdminServLogs::add('action', $action);
			Utils::redirection(false, '?p='.USER_PAGE);
		}
		else{
			AdminServ::error( Utils::t('Unable to duplicate server.') );
		}
	}
	
	
	// SUPPRESSION
	if( isset($_POST['deleteserver']) ){
		$servers = ServerConfig::$SERVERS;
		unset($servers[$_POST['server'][0]]);
		if( ($result = AdminServServerConfig::saveServerConfig(array(), -1, $servers)) !== true ){
			AdminServ::error( Utils::t('Unable to delete server.').' ('.$result.')');
		}
		else{
			$action = Utils::t('The "!serverName" server has been deleted.', array('!serverName' => $_POST['server'][0]));
			AdminServ::info($action);
			AdminServLogs::add('action', $action);
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	
	
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
?>