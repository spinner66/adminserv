<?php
	// On vérifie qu'une configuration existe, sinon on la créer
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
		if( isset(ServerConfig::$SERVERS) && count(ServerConfig::$SERVERS) > 0 && !isset(ServerConfig::$SERVERS['new server name']) && !isset(ServerConfig::$SERVERS['']) ){
			// Connexion
			if( isset($_POST['as_server']) && isset($_POST['as_password']) && isset($_POST['as_adminlevel']) ){
				// Récupération des valeurs
				$serverName = $_POST['as_server'];
				$password = addslashes( htmlspecialchars( trim($_POST['as_password']) ) );
				$adminLevel = addslashes( htmlspecialchars($_POST['as_adminlevel']) );
				
				// Vérification des valeurs
				if($password === ''){
					AdminServ::error('Veuillez mettre un mot de passe.');
				}
				else{
					// Sessions & Cookies
					$_SESSION['adminserv']['sid'] = AdminServ::getServerId($serverName);
					$_SESSION['adminserv']['name'] = $serverName;
					$_SESSION['adminserv']['password'] = $password;
					$_SESSION['adminserv']['adminlevel'] = $adminLevel;
					Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], $adminLevel, Utils::readCookieData('adminserv', 2), Utils::readCookieData('adminserv', 3), Utils::readCookieData('adminserv', 4), Utils::readCookieData('adminserv', 5) ), AdminServConfig::COOKIE_EXPIRE);
					
					// Redirection
					if($_SESSION['adminserv']['sid'] != -1 && $_SESSION['adminserv']['name'] != null && $_SESSION['adminserv']['password'] != null && $_SESSION['adminserv']['adminlevel'] != null){
						Utils::redirection(false);
					}else{
						AdminServ::error('Erreur de connexion : session invalide.');
					}
				}
			}
		}
		else{
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE === true ){
				Utils::redirection(false, '?p=servers');
			}
			else{
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
			}
		}
	}
	else{
		AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
	}
	
	
	// HTML
	if( isset($_GET['error']) ){
		AdminServ::error($_GET['error']);
	}
	AdminServTemplate::getHeader();
?>
<section>
	<!-- TODO : displayServ -->
</section>
<?php
	AdminServTemplate::getFooter();
?>