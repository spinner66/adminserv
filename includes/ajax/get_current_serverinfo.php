<?php
	/**
	* Récupère les informations du serveur actuel (map, serveur, stats, joueurs)
	*/
	
	// INCLUDES
	session_start();
	$pathConfig = '../../'.$_SESSION['adminserv']['path'].'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
	require_once $pathConfig.'extension.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	$lang = AdminServUI::getLang();
	if( file_exists('../lang/'.$lang.'.php') ){
		require_once '../lang/'.$lang.'.php';
	}
	
	// ISSET
	if( isset($_GET['mode']) ){ $mode = addslashes($_GET['mode']); }else{ $mode = null; }
	if( isset($_GET['sort']) ){ $sort = addslashes($_GET['sort']); }else{ $sort = null; }
	if($mode){
		$_SESSION['adminserv']['mode'] = $mode;
	}
	
	// DATA
	if( AdminServ::initialize() ){
		$out = AdminServ::getCurrentServerInfo($sort);
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>