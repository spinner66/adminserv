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
	if( isset($_POST['method']) ){ $method = $_POST['method']; }else{ $method = null; }
	if( isset($_POST['params']) ){ $params = $_POST['params']; }else{ $params = null; }
	
	// DATA
	$out = null;
	if( AdminServ::initialize() ){
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			if($method == 'set' && $params != null){
				$scriptSettings = array();
				if( count($params) > 0 ){
					foreach($params as $param){
						switch($param['type']){
							case 'boolean':
								$value = (bool)$param['value'];
								break;
							case 'int':
								$value = (int)$param['value'];
								break;
							case 'float':
								$value = (float)$param['value'];
								break;
							default:
								$value = (string)$param['value'];
						}
						$scriptSettings[$param['name']] = $value;
					}
				}
				
				if( !$client->query('SetModeScriptSettings', $scriptSettings) ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
				else{
					$out = true;
				}
			}
			else{
				if( !$client->query('GetModeScriptInfo') ){
					$out = '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
				else{
					$out = $client->getResponse();
				}
			}
		}
	}
	
	// OUT
	$client->Terminate();
	echo json_encode($out);
?>