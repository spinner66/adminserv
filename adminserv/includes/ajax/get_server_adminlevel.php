<?php
	/**
	* Récupère la liste des niveaux admin suivant le serveur sélectionné
	*
	* @param string srv -> Le nom du serveur sélectionné
	*/
	
	// INCLUDES
	require_once '../../config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	require_once '../class/utils.class.php';
	
	// GET
	if( isset($_GET['srv']) ){ $serverName = $_GET['srv']; }else{ $serverName = null; }
	
	$out = array();
	if($serverName != null){
		$servers = ServerConfig::$SERVERS;
		$authenticate = array('SuperAdmin', 'Admin', 'User');
		
		// Niveaux
		foreach($servers[$serverName]['adminlevel'] as $levelName => $levelValues){
			if($levelName != null){
				if( in_array($levelName, $authenticate) ){
					if( AdminServ::userAllowedInAdminLevel($serverName, $levelName) ){
						$out['levels'][] = $levelName;
					}
				}
			}
			if($levelValues != null){
				if( in_array($levelValues, $authenticate) ){
					if( AdminServ::userAllowedInAdminLevel($serverName, $levelValues) ){
						$out['levels'][] = $levelValues;
					}
				}
			}
		}
		
		// Dernier niveau utilisé
		$out['last'] = Utils::readCookieData('adminserv', 1);
	}
	
	echo json_encode($out);
?>