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
	if($operation == 'select' && $selection != null){
		if( isset($_SESSION['adminserv']['matchset_maps_selected']) ){
			$maps = AdminServ::getLocalMapList($path);
			if( count($selection) > 0 ){
				foreach($maps['lst'] as $id => $values){
					if( !in_array($id, $selection) ){
						unset($maps['lst'][$id]);
					}
				}
			}
			AdminServ::updateMatchSetSelection($maps);
		}
		else{
			// Liste
			$maps = AdminServ::getLocalMapList($path);
			if( count($selection) > 0 ){
				foreach($maps['lst'] as $id => $values){
					if( !in_array($id, $selection) ){
						unset($maps['lst'][$id]);
					}
				}
			}
			
			// Nombre de maps
			$nbm = count($maps['lst']);
			if($nbm > 1){
				$maps['nbm'] = $nbm.' maps';
			}
			else{
				$maps['nbm'] = $nbm.' map';
			}
			$_SESSION['adminserv']['matchset_maps_selected'] = $maps;
		}
	}
	else{
		if( isset($_SESSION['adminserv']['matchset_maps_selected']) ){
			$mapsImport = AdminServ::getLocalMapList($path);
			AdminServ::updateMatchSetSelection($mapsImport);
		}
		else{
			$_SESSION['adminserv']['matchset_maps_selected'] = AdminServ::getLocalMapList($path);
		}
	}
	echo json_encode($_SESSION['adminserv']['matchset_maps_selected']);
?>