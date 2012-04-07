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
	
	// ISSET
	if( isset($_GET['mode']) ){ $mode = addslashes($_GET['mode']); }else{ $mode = null; }
	if( isset($_GET['sort']) ){ $sort = addslashes($_GET['sort']); }else{ $sort = null; }
	if($mode){
		$_SESSION['adminserv_mode'] = $mode;
	}
	
	// DATA
	if( AdminServ::initialize() ){
		$out = AdminServ::getCurrentServerInfo($sort);
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>