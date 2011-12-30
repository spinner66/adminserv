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
	AdminServTemplate::getClass();
	
	
	// DATA
	AdminServ::initialize(false);
	$out = AdminServ::getCurrentServerInfo();
	
	
	// OUT
	echo json_encode($out);
?>