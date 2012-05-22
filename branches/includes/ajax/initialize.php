<?php
	// INCLUDES
	require_once '../../config/displayserv.cfg.php';
	// ServerConfig
	if(DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG !== null){
		$serverConfig = DisplayServConfig::USE_ADMINSERV_SERVER_CONFIG;
	}else{
		$serverConfig = '../../config/servers.cfg.php';
	}
	if( file_exists($serverConfig) ){
		require_once $serverConfig;
	}
	require_once '../displayserv.inc.php';
	DisplayServ::getClass();
	
	// DATA
	$out = array();
	if( class_exists('ServerConfig') ){
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
			
			foreach(ServerConfig::$SERVERS as $serverName => $serverValues){
				// CONNEXION
				$client = new IXR_Client_Gbx;
				if( !$client->InitWithIp($serverValues['address'], $serverValues['port']) ){
					//echo 'Le serveur n\'est pas accessible.';
				}
				else{
					if( !$client->query('Authenticate', 'User', 'User') ){
						//echo 'Echec d\'authentification.';
					}
					else{
						$client->query('GetStatus');
						$out['server']['status'] = $client->getResponse();
						$client->query('GetServerName');
						$out['server']['name'] = $client->getResponse();
						$client->query('GetSystemInfo');
						$out['server']['system'] =  $client->getResponse();
						$client->query('GetMaxPlayers');
						$out['server']['max_players'] =  $client->getResponse();
						$client->query('GetPlayerList', 50, 0);
						$out['player'] = $client->getResponse();
					}
				}
				
				
				
				$client->Terminate();
			}
		}
	}
	
	
	echo json_encode($out);
?>