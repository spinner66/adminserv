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
			AdminServ::info( Utils::t('No server available. For add this, configure "config/servers.cfg.php" file.') );
			Utils::redirection();
		}
		else{
			if( OnlineConfig::ADD_ONLY === true || OnlineConfig::PASSWORD == null ){
				Utils::redirection(false, './?p=addserver');
			}
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file doesn\'t reconized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['savepassword']) ){
		$current = md5($_POST['changePasswordCurrent']);
		$new = md5($_POST['changePasswordNew']);
		if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }else{ $adminservPath = null; }
		$pathConfig = $adminservPath.'config/';
		
		if(OnlineConfig::PASSWORD !== $current){
			AdminServ::error( Utils::t('The current password doesn\'t match.') );
		}
		else{
			if( ($result = AdminServServerConfig::savePasswordConfig($pathConfig.'adminserv.cfg.php', $new)) !== true ){
				AdminServ::error( Utils::t('Unable to save password.').' ('.$result.')');
			}
			else{
				$info = Utils::t('The password has been changed.');
				AdminServ::info($info);
				AdminServLogs::add('action', $info);
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
?>