<?php
	/**
	* Récupère la liste des maps du serveur
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/servers.cfg.php';
	require_once '../../config/adminserv.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// DATA
	if( AdminServ::initialize() ){
		$out = AdminServ::getMapList();
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>