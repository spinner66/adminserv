<?php
	/**
	* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/servers.cfg.php';
	require_once '../../config/adminserv.cfg.php';
	require_once '../../config/extension.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// DATA
	if( AdminServ::initialize(false) ){
		$out = AdminServ::getCurrentServerInfo();
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>