<?php
	/**
	* Récupère la liste des maps en local
	*/
	
	// INCLUDES
	session_start();
	require_once '../../config/adminserv.cfg.php';
	require_once '../adminserv.inc.php';
	AdminServUI::getClass();
	
	// ISSET
	if( isset($_GET['path']) ){ $path = addslashes($_GET['path']); }else{ $path = null; }
	if( isset($_GET['op']) ){ $operation = addslashes($_GET['op']); }else{ $operation = null; }
	if( isset($_GET['select']) ){ $selection = $_GET['select']; }else{ $selection = null; }
	
	// DATA
	$out = null;
	
	// Faire une sélection
	if($operation == 'setSelection' && $selection != null){
		// On récupère tout le dossier et on supprime les maps non sélectionnées
		$maps = AdminServ::getLocalMapList($path);
		if( count($selection) > 0 ){
			foreach($maps['lst'] as $id => $values){
				if( !in_array($id, $selection) ){
					unset($maps['lst'][$id]);
				}
			}
		}
		// Enregistrement de la sélection du MatchSettings
		AdminServ::saveMatchSettingSelection($maps);
	}
	
	// Récupérer le tableau pour faire une sélection
	else if($operation == 'getSelection'){
		$out = AdminServ::getLocalMapList($path);
	}
	
	// Sélectionner tout le dossier
	else{
		// Import du dossier + enregistrement de la sélection
		$mapsImport = AdminServ::getLocalMapList($path);
		AdminServ::saveMatchSettingSelection($mapsImport);
	}
	
	
	// Retour
	if($operation == 'getSelection'){
		echo json_encode($out);
	}
	else{
		echo json_encode($_SESSION['adminserv']['matchset_maps_selected']);
	}
?>