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
			$showColor = false;
			if( defined('AdminServConfig::COLORS_CHAT') ){
				$showColor = AdminServConfig::COLORS_CHAT;
			}
			Utils::addCookieData('adminserv_user', array(USER_THEME, USER_LANG, $nickname, $color), AdminServConfig::COOKIE_EXPIRE);
			
			if($nickname){
				if( substr($nickname, 0, 1) !== '$' ){ $nickname = '$fff'.$nickname; }
				$nickname = str_replace('$s', '', $nickname);
				if($showColor){
					$nickname = ':$g$ff0'.$nickname.'$f00$g$s';
				}
				else{
					$nickname = ':$z$s'.$nickname.'$fff$z$s';
				}
			}
			
			if($showColor){
				$message = '$s$ff0[$fffAdmin'.$nickname.'$ff0] '.$color.$message;
			}
			else{
				$message = '$z$s[$fffAdmin'.$nickname.'] '.$color.$message;
			}
			
			$_SESSION['adminserv']['chat_dst'] = $destination;
			if($destination === 'server'){
				if( !$client->query('ChatSendServerMessage', $message) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
			else{
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