<?php
	// INCLUDES
	session_start();
	if( isset($_SESSION['adminserv']['path']) ){ $adminservPath = $_SESSION['adminserv']['path']; }
	else{ $adminservPath = null; }
	$pathConfig = '../../'.$adminservPath.'config/';
	require_once $pathConfig.'adminserv.cfg.php';
	require_once $pathConfig.'extension.cfg.php';
	require_once $pathConfig.'servers.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_POST['nic']) ){ $nickname = $_POST['nic']; }else{ $nickname = null; }
	if( isset($_POST['clr']) ){ $color = $_POST['clr']; }else{ $color = null; }
	if( isset($_POST['msg']) ){ $message = $_POST['msg']; }else{ $message = null; }
	if( isset($_POST['dst']) ){ $destination = $_POST['dst']; }else{ $destination = null; }
	
	// DATA
	$out = null;
	if($message != null && $destination != null){
		if( AdminServ::initialize(false) ){
			
			if($color != null){
				Utils::addCookieData('adminserv_user', array(USER_THEME, USER_LANG, $nickname, $color), AdminServConfig::COOKIE_EXPIRE);
			}
			
			if($nickname != null){
				Utils::addCookieData('adminserv_user', array(USER_THEME, USER_LANG, $nickname, $color), AdminServConfig::COOKIE_EXPIRE);
				if( substr($nickname, 0, 1) !== '$' ){ $nickname = '$fff'.$nickname; }
				$nickname = ':$z$s'.str_replace('$s', '', $nickname).'$fff$z$s';
			}
			
			// Affichage du message final
			$message = '<Admin'.$nickname.'> '.$color.$message;
			
			// Destination
			$_SESSION['adminserv']['chat_dst'] = $destination;
			if($destination === 'server'){
				// Envoi du message au serveur
				if( !$client->query('ChatSendServerMessage', $message) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
			else{
				// Envoi du message au joueur
				if( !$client->query('ChatSendServerMessageToLogin', $message, $destination) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
		}
		$client->Terminate();
	}
	
	// OUT
	echo json_encode($out);
?>