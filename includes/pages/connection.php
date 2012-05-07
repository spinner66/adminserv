<?php
	// Si on ne demande pas de mot de passe pour la config en ligne
	if( !isset($_SESSION['adminserv']['check_password']) ){
		// On vérifie qu'une configuration existe, sinon on la créer
		if( class_exists('ServerConfig') ){
			// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
			if( AdminServServerConfig::hasServer() ){
				// Connexion
				if( isset($_POST['as_server']) && isset($_POST['as_password']) && isset($_POST['as_adminlevel']) ){
					// Récupération des valeurs
					$serverName = $_POST['as_server'];
					$password = addslashes( htmlspecialchars( trim($_POST['as_password']) ) );
					if(AdminServConfig::MD5_PASSWORD){
						$password = md5($password);
					}
					$adminLevel = addslashes( htmlspecialchars($_POST['as_adminlevel']) );
					
					// Vérification des valeurs
					if($password === ''){
						AdminServ::error('Veuillez mettre un mot de passe.');
					}
					else{
						// Sessions & Cookies
						$_SESSION['adminserv']['sid'] = AdminServServerConfig::getServerId($serverName);
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
				if(OnlineConfig::ACTIVATE === true){
					Utils::redirection(false, './config/');
				}
				else{
					AdminServ::info('Aucun serveur disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				}
			}
		}
		else{
			if(OnlineConfig::ACTIVATE === true && !isset($_GET['error']) ){
				Utils::redirection(false, './config/');
			}
			else{
				AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
			}
		}
	}
	else{
		$hasServer = AdminServServerConfig::hasServer();
		if( !$hasServer ){
			AdminServ::info('Aucun serveur disponible. Entrez le mot de passe pour accédez à la configuration des serveurs.'); 
		}
	}
	
	// HTML
	if( isset($_GET['error']) ){
		AdminServ::error($_GET['error']);
	}
	else if( isset($_GET['info']) ){
		AdminServ::info($_GET['info']);
	}
	AdminServUI::getHeader();
	
	
	// CONFIG PASSWORD
	if( isset($_SESSION['adminserv']['check_password']) ){
?>
<section class="config-check-password<?php if($hasServer){ echo ' has-server'; } ?>">
	<form method="post" action="./config/">
		<fieldset>
			<legend>Configuration des serveurs</legend>
			<?php if($hasServer){ ?>
				<div class="connexion-cancel">
					<a class="button light" href="./?logout">Annuler</a>
				</div>
			<?php } ?>
			<div class="connexion-label">
				<label for="checkPassword">Mot de passe :</label>
				<input class="text" type="password" name="checkPassword" id="checkPassword" value="" />
			</div>
			<div class="connexion-login">
				<input class="button light" type="submit" name="configcheckpassword" id="configcheckpassword" value="Connexion" />
			</div>
		</fieldset>
	</form>
</section>
<?php
	}
	else{
?>
<section>
	<!-- TODO : displayServ -->
</section>
<?php
	}
	AdminServUI::getFooter();
?>