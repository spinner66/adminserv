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
	$langCode = AdminServUI::getLang();
	$langFile = '../lang/'.$langCode.'.php';
	if( file_exists($langFile) ){
		require_once $langFile;
	}
	
	// ISSET
	if( isset($_GET['path']) ){ $path = addslashes($_GET['path']); }else{ $path = null; }
	if( isset($_GET['op']) ){ $operation = addslashes($_GET['op']); }else{ $operation = null; }
	if( isset($_GET['select']) ){ $selection = $_GET['select']; }else{ $selection = null; }
	
	// DATA
	$out = null;
	if( AdminServ::initialize() && $path != null ){
		// Maps
		if($path == 'currentServerSelection'){
			$mapsImport = AdminServ::getMapList();
		}
		else{
			$mapsImport = AdminServ::getLocalMapList($path);
		}
		
		// Faire une sélection
		if($operation == 'setSelection'){
			// On supprime les maps non sélectionnées
			if( $selection != null && count($selection) > 0 ){
				foreach($mapsImport['lst'] as $id => $values){
					if( !in_array($id, $selection) ){
						unset($mapsImport['lst'][$id]);
					}
				}
			}
			else{
				foreach($mapsImport['lst'] as $id => $values){
					unset($mapsImport['lst'][$id]);
				}
			}
		}
		
		// Enregistrement de la sélection du MatchSettings
		if($operation != 'getSelection'){
			AdminServ::saveMatchSettingSelection($mapsImport);
		}
		
		$client->Terminate();
	}
	
	// OUT
	if($operation == 'getSelection'){
		echo json_encode($mapsImport);
	}
	else{
		echo json_encode($_SESSION['adminserv']['matchset_maps_selected']);
	}
?>