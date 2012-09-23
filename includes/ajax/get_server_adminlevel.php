<?php
	/**
	* Récupère la liste des niveaux admin suivant le serveur sélectionné
	*
	* @param string srv -> Le nom du serveur sélectionné
	*/
	
	// INCLUDES
	session_start();
	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }
	else{ $adminservPath = null; }
	require_once '../../'.$adminservPath.'config/servers.cfg.php';
	require_once '../adminserv.inc.php';
	require_once '../class/utils.class.php';
	
	// ISSET
	if( isset($_GET['srv']) ){ $serverName = $_GET['srv']; }else{ $serverName = null; }
	
	$out = array();
	if($serverName != null){
		$out = AdminServ::getServerAdminLevel($serverName);
	}
	
	echo json_encode($out);
?>