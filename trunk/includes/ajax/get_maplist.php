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
	
	// ISSET
	if( isset($_GET['mode']) ){ $mode = addslashes($_GET['mode']); }else{ $mode = null; }
	if( isset($_GET['sort']) ){ $sort = addslashes($_GET['sort']); }else{ $sort = null; }
	if($mode){
		$_SESSION['adminserv']['mode'] = $mode;
	}
	
	// DATA
	if( AdminServ::initialize() ){
		$out = AdminServ::getMapList($sort);
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>