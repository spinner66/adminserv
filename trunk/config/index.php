<?php
	require_once './adminserv.cfg.php';
	require_once './servers.cfg.php';
	require_once '.'. AdminServConfig::PATH_INCLUDES .'adminserv.inc.php';
	AdminServUI::getClass('.'. AdminServConfig::PATH_INCLUDES);
	
	// On vérifie qu'une configuration existe
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
		if( AdminServ::hasServer() ){
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE === true ){
				if( OnlineConfig::ADD_ONLY === true ){
					Utils::redirection(false, '../?p=addserver');
				}
				else{
					Utils::redirection(false, '../?p=servers');
				}
			}
			else{
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false, '..');
			}
		}
		else{
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE === true ){
				if( OnlineConfig::ADD_ONLY === true ){
					Utils::redirection(false, '../?p=addserver');
				}
				else{
					Utils::redirection(false, '../?p=servers');
				}
			}
			else{
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false, '..');
			}
		}
	}
	else{
		AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
	}
?>