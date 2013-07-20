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
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file isn\'t recognized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['saveserver']) ){
		// Variables
		$serverName = Str::replaceSpecialChars( htmlspecialchars(addslashes($_POST['addServerName'])), false);
		$serverAddress = trim($_POST['addServerAddress']);
		$serverPort = intval($_POST['addServerPort']);
		$serverMapsBasePath = trim($_POST['addServerMapsBasePath']);
		$serverMatchSet = trim($_POST['addServerMatchSet']);
		$serverAdmLvl = array(
			'SuperAdmin' => $_POST['addServerAdmLvlSA'],
			'Admin' => $_POST['addServerAdmLvlADM'],
			'User' => $_POST['addServerAdmLvlUSR']
		);
		$isNotAnArray = array('all', 'local', 'none');
		foreach($serverAdmLvl as $admLvlId => $admLvlValue){
			if( !in_array($admLvlValue, $isNotAnArray) ){
				$serverAdmLvl[$admLvlId] = explode(',', $admLvlValue);
			}
			else{
				$serverAdmLvl[$admLvlId] = trim($admLvlValue);
			}
		}
		$serverData = array(
			'name' => $serverName,
			'address' => $serverAddress,
			'port' => $serverPort,
			'mapsbasepath' => $serverMapsBasePath,
			'matchsettings' => $serverMatchSet,
			'adminlevel' => array()
		);
		foreach($serverAdmLvl as $admLvlId => $admLvlValue){
			$serverData['adminlevel'][$admLvlId] = $admLvlValue;
		}
		
		// Édition
		if($id !== -1){
			if( ($result = AdminServServerConfig::saveServerConfig($serverData, $id)) !== true ){
				AdminServ::error( Utils::t('Unable to modify the server.').' ('.$result.')');
			}
			else{
				$action = Utils::t('This server has been modified.');
				AdminServ::info($action);
				AdminServLogs::add('action', $action);
				Utils::redirection(false, '?p=servers');
			}
		}
		// Ajout
		else{
			if( ($result = AdminServServerConfig::saveServerConfig($serverData)) !== true ){
				AdminServ::error( Utils::t('Unable to add the server.').' ('.$result.')');
			}
			else{
				$action = Utils::t('This server has been added.');
				AdminServ::info($action);
				AdminServLogs::add('action', $action);
				Utils::redirection(false, '?p='.USER_PAGE);
			}
		}
	}
	
	
	// LECTURE
	$serverName = null;
	$serverAddress = 'localhost';
	$serverPort = 5000;
	$serverMapsBasePath = null;
	$serverMatchSet = 'MatchSettings/';
	$serverAdmLvl = array(
		'SuperAdmin' => 'all',
		'Admin' => 'all',
		'User' => 'all'
	);
	if($id !== -1){
		define('IS_SERVER_EDITION', true);
		$serverName = AdminServServerConfig::getServerName($id);
		if($serverName){
			$serverData = AdminServServerConfig::getServer($serverName);
			$serverAddress = $serverData['address'];
			$serverPort = $serverData['port'];
			$serverMapsBasePath = (isset($serverData['mapsbasepath'])) ? $serverData['mapsbasepath'] : '';
			$serverMatchSet = $serverData['matchsettings'];
			foreach($serverData['adminlevel'] as $admLvlId => $admLvlValue){
				if( is_array($admLvlValue) ){
					$serverAdmLvl[$admLvlId] = implode(', ', $admLvlValue);
				}
				else{
					$serverAdmLvl[$admLvlId] = $admLvlValue;
				}
			}
		}
	}
?>