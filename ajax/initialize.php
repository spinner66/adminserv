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
			$out['servers'] = count(ServerConfig::$SERVERS);
			$out['label']['server'] = 'Server';
			$out['label']['name'] = 'Nom du serveur';
			$out['label']['login'] = 'Login serveur';
			$out['label']['connect'] = 'Connecté sur';
			$out['label']['status'] = 'Statut';
			$out['label']['gamemode'] = 'Mode de jeu';
			$out['label']['currentmap'] = 'Map en cours';
			$out['label']['players'] = 'Joueurs';
		}
	}
	echo json_encode($out);
?>