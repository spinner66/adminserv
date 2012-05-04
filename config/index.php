<?php
	session_start();
	require_once './adminserv.cfg.php';
	require_once './servers.cfg.php';
	require_once '.'. AdminServConfig::PATH_INCLUDES .'adminserv.inc.php';
	AdminServUI::getClass();
	
	// On vérifie qu'une configuration existe
	if( class_exists('ServerConfig') ){
		// Si on autorise la configuration en ligne
		if( OnlineConfig::ACTIVATE === true ){
			$allowRedirect = false;
			if( OnlineConfig::CHECK_IP == null && OnlineConfig::CHECK_PASSWORD == null ){
				$allowRedirect = true;
			}
			else{
				// Test adresse ip local
				if(OnlineConfig::CHECK_IP != null){
					if(OnlineConfig::CHECK_IP === 'localhost'){
						if( Utils::isLocalhostIP() ){
							$allowRedirect = true;
						}
						else{
							$allowRedirect = false;
						}
					}
					else{
						if( $_SERVER['REMOTE_ADDR'] === OnlineConfig::CHECK_IP ){
							$allowRedirect = true;
						}
						else{
							$allowRedirect = false;
						}
					}
				}
				// Test password
				if(OnlineConfig::CHECK_PASSWORD != null){
					if( isset($_POST['configcheckpassword']) ){
						if($_POST['checkPassword'] === OnlineConfig::CHECK_PASSWORD){
							$allowRedirect = true;
						}
						else{
							$allowRedirect = false;
						}
					}
					else{
						// Suppression des sessions existante et démarrage d'une nouvelle
						session_unset();
						session_destroy();
						session_start();
						
						// Demande de password
						$_SESSION['adminserv']['check_password'] = true;
						Utils::redirection(false, '..');
					}
				}
			}
		
			// Redirection vers les pages
			if($allowRedirect){
				session_unset();
				session_destroy();
				session_start();
				$_SESSION['adminserv']['allow_config_servers'] = true;
				
				if( OnlineConfig::ADD_ONLY === true || AdminServ::hasServer() === false ){
					Utils::redirection(false, '../?p=addserver');
				}
				else{
					Utils::redirection(false, '../?p=servers');
				}
			}
			else{
				AdminServ::error('Vous n\'êtes pas autorisé à configurer les serveurs.');
				Utils::redirection(false, '..');
			}
		}
		else{
			AdminServ::info('La configuration en ligne est désactivée. Utilisez le fichier "./config/servers.cfg.php".');
			Utils::redirection(false, '..');
		}
	}
	else{
		AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
		Utils::redirection(false, '..');
	}
?>