<?php
	/**
	* Récupère la liste des niveaux admin suivant le serveur sélectionné
	*
	* @param string srv -> Le nom du serveur sélectionné
	*/
	
	// INCLUDES
	session_start();
	require_once '../../'.$_SESSION['adminserv']['path'].'config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	require_once '../class/utils.class.php';
	
	// ISSET
	if( isset($_GET['srv']) ){ $serverName = $_GET['srv']; }else{ $serverName = null; }
	
	$out = array();
	if($serverName != null){
		$servers = ServerConfig::$SERVERS;
		$authenticate = array('SuperAdmin', 'Admin', 'User');
		
		// Niveaux
		foreach($servers[$serverName]['adminlevel'] as $levelName => $levelValues){
			if($levelName != null){
				if( in_array($levelName, $authenticate) && $levelValues != 'none' ){
					if( AdminServ::userAllowedInAdminLevel($serverName, $levelName) ){
						$out['levels'][] = $levelName;
					}
				}
			}
		}
		
		// Dernier niveau utilisé
		$out['last'] = Utils::readCookieData('adminserv', 1);
	}
	
	echo json_encode($out);
?>