<?php
	require_once './adminserv.cfg.php';
	require_once './servers.cfg.php';
	
	// On vérifie qu'une configuration existe
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) ){
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE === true ){
				header('Location: ../?p=servers');
			}
			else{
				// info : Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"
			}
		}
		else{
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE === true ){
				header('Location: ../?p=addserver');
			}
			else{
				// info : Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"
			}
		}
	}
	else{
		// error : Le fichier de configuration des serveurs n'est pas reconnu par AdminServ.
	}
?>