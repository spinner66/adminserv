<?php
	// INCLUDES
	$serverConfig = '../../config/servers.cfg.php';
	if( file_exists($serverConfig) ){
		require_once $serverConfig;
	}
	
	// DATA
	$out = array();
	if( class_exists('ServerConfig') ){
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
			$out['servers'] = count(ServerConfig::$SERVERS);
			$out['label']['server'] = 'Serveur';
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