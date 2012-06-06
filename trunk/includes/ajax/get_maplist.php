<?php
	/**
	* Récupère la liste des maps du serveur
	*/
	
	// INCLUDES
	session_start();
	$pathConfig = '../../'.$_SESSION['adminserv']['path'].'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
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