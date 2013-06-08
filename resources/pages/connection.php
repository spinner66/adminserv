<?php
	// Si on demande le mot de passe pour la config en ligne
	if( !isset($_SESSION['adminserv']['check_password']) && !isset($_SESSION['adminserv']['get_password']) ){
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
					if($password == null){
						AdminServ::error( Utils::t('Please put a password.') );
					}
					else{
						// Sessions & Cookies
						$_SESSION['adminserv']['sid'] = AdminServServerConfig::getServerId($serverName);
						$_SESSION['adminserv']['name'] = $serverName;
						$_SESSION['adminserv']['password'] = $password;
						$_SESSION['adminserv']['adminlevel'] = $adminLevel;
						Utils::addCookieData('adminserv', array($_SESSION['adminserv']['sid'], $adminLevel), AdminServConfig::COOKIE_EXPIRE);
						
						// Redirection
						if($_SESSION['adminserv']['sid'] != -1 && $_SESSION['adminserv']['name'] != null && $_SESSION['adminserv']['password'] != null && $_SESSION['adminserv']['adminlevel'] != null){
							Utils::redirection();
						}
						else{
							AdminServ::error( Utils::t('Connection error: invalid session.') );
						}
					}
				}
			}
			else{
				if(OnlineConfig::ACTIVATE === true){
					Utils::redirection(false, './config/');
				}
				else{
					AdminServ::info( Utils::t('No server available. To add one, configure "config/servers.cfg.php" file.') );
				}
			}
		}
		else{
			if(OnlineConfig::ACTIVATE === true && !isset($_GET['error']) ){
				Utils::redirection(false, './config/');
			}
			else{
				AdminServ::error( Utils::t('The servers configuration file isn\'t recognized by AdminServ.') );
			}
		}
	}
	else if( isset($_SESSION['adminserv']['get_password']) ){
		AdminServ::info( Utils::t('It\'s your first connection and no server configured. Choose a password to configure your servers.') );
	}
	
	// HTML
	if( isset($_GET['error']) ){
		AdminServ::error($_GET['error']);
	}
	else if( isset($_GET['info']) ){
		AdminServ::info($_GET['info']);
	}
	AdminServUI::getHeader();
	
	
	// Demande de password
	if( isset($_SESSION['adminserv']['check_password']) ){
?>
<section class="config-servers">
	<form method="post" action="./config/">
		<fieldset>
			<legend><?php echo Utils::t('Servers configuration'); ?></legend>
			<div class="connection-label">
				<label for="checkPassword"><?php echo Utils::t('Password'); ?> :</label>
				<input class="text" type="password" name="checkPassword" id="checkPassword" value="" />
			</div>
			<div class="connection-login">
				<input class="button light" type="submit" name="configcheckpassword" id="configcheckpassword" value="<?php echo Utils::t('Connection'); ?>" />
			</div>
			<div class="connection-cancel">
				<a class="button light" href="./?logout"><?php echo Utils::t('Cancel'); ?></a>
			</div>
		</fieldset>
	</form>
</section>
<?php
	}
	// Demande de création password
	else if( isset($_SESSION['adminserv']['get_password']) ){
?>
<section class="config-servers no-server">
	<form method="post" action="./config/">
		<fieldset>
			<legend><?php echo Utils::t('Online configuration'); ?></legend>
			<div class="connection-label">
				<label for="savePassword"><?php echo Utils::t('Password'); ?> :</label>
				<input class="text" type="password" name="savePassword" id="savePassword" value="" />
			</div>
			<div class="connection-login">
				<input class="button light" type="submit" name="configsavepassword" id="configsavepassword" value="<?php echo Utils::t('Save'); ?>" />
			</div>
		</fieldset>
	</form>
</section>
<?php
	}
	// Affichage de DisplayServ
	else{
?>
<section>
	<?php
		if(AdminServConfig::USE_DISPLAYSERV){
			$themeColor = null;
			if( AdminServUI::hasTheme() && ($theme = AdminServUI::getTheme()) ){
				if( isset(ExtensionConfig::$THEMES[$theme]) && isset(ExtensionConfig::$THEMES[$theme][0]) ){
					$themeColor = ExtensionConfig::$THEMES[$theme][0];
				}
			}
	?>
		<link rel="stylesheet" href="<?php echo AdminServConfig::PATH_RESOURCES; ?>css/displayserv.css" />
		<script src="<?php echo AdminServConfig::PATH_RESOURCES; ?>js/displayserv.js"></script>
		<script>
			$(document).ready(function(){
				$('#displayserv').displayServ({
					color: '<?php echo $themeColor; ?>'
				});
			});
		</script>
		<div id="displayserv"></div>
	<?php
		}
	?>
</section>
<?php
	}
	AdminServUI::getFooter();
?>