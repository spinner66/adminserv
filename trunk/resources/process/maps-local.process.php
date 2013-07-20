<?php
	// GAME
	if(SERVER_VERSION_NAME == 'TmForever'){
		$queries = array(
			'insert' => 'InsertChallenge',
			'add' => 'AddChallenge'
		);
	}
	else{
		$queries = array(
			'insert' => 'InsertMap',
			'add' => 'AddMap'
		);
	}
	
	// ACTIONS
	if( isset($_POST['addMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['add'], $mapsDirectoryPath.$map) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Add map: '.$map);
			}
		}
	}
	else if( isset($_POST['insertMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['insert'], $mapsDirectoryPath.$map) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Insert map: '.$map);
			}
		}
	}
	else if( isset($_POST['downloadMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		// Si on télécharge plusieurs fichiers, on envoi un zip
		$countMaps = count($_POST['map']);
		if($countMaps > 1){
			$struct = array();
			foreach($_POST['map'] as $map){
				$struct[] = $mapsDirectoryPath.$map;
			}
			$zipError = null;
			$zipFileName = 'maps.zip';
			if( !Zip::create($zipFileName, $struct, $zipError) ){
				AdminServ::error($zipError);
			}
			else{
				File::download($zipFileName);
				AdminServLogs::add('action', 'Download packmap ('.$countMaps.' maps)');
				if( $result = File::delete($zipFileName) !== true ){
					AdminServ::error($result);
				}
			}
		}
		// Sinon on envoi le fichier seul
		else{
			File::download($mapsDirectoryPath.$_POST['map'][0]);
			AdminServLogs::add('action', 'Download map: '.$_POST['map'][0]);
		}
	}
	else if( isset($_POST['renameMapValid']) && isset($_POST['map']) && count($_POST['map']) > 0 && isset($_POST['renameMapList']) && count($_POST['renameMapList']) > 0 ){
		$i = 0;
		foreach($_POST['renameMapList'] as $newMapName){
			$result = File::rename($mapsDirectoryPath.$_POST['map'][$i], $mapsDirectoryPath.$directory.$newMapName);
			if($result !== true ){
				AdminServ::error(Utils::t('Unable to rename the map').' : '.$newMapName.' ('.$result.')');
				break;
			}
			else{
				AdminServLogs::add('action', 'Rename map: '.$_POST['map'][$i].' to '.$newMapName);
			}
			$i++;
		}
		
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['renameAutoValid']) && isset($_POST['map']) && count($_POST['map']) > 0 && isset($_POST['renameMapList']) && count($_POST['renameMapList']) > 0 ){
		$i = 0;
		foreach($_POST['renameMapList'] as $newMapName){
			$result = File::rename($mapsDirectoryPath.$_POST['map'][$i], $mapsDirectoryPath.$directory.Str::replaceChars($newMapName));
			if($result !== true){
				AdminServ::error(Utils::t('Unable to rename the map').' : '.$newMapName.' ('.$result.')');
				break;
			}
			else{
				AdminServLogs::add('action', 'Rename map (auto): '.$_POST['map'][$i].' to '.$newMapName);
			}
			$i++;
		}
		
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['moveMapValid']) && isset($_POST['moveDirectoryList']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		// Chemin
		if($_POST['moveDirectoryList'] == '.'){
			$newPath = $mapsDirectoryPath;
		}
		else{
			$newPath = $_POST['moveDirectoryList'];
		}
		
		// Déplacement
		foreach($_POST['map'] as $map){
			$result = File::rename($mapsDirectoryPath.$map, $newPath.basename($map) );
			if($result !== true ){
				AdminServ::error(Utils::t('Unable to move the map').' : '.$map.' ('.$result.')');
				break;
			}
			else{
				AdminServLogs::add('action', 'Move map: '.$map.' to '.$newPath.basename($map) );
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['deleteMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			$result = File::delete($mapsDirectoryPath.$map);
			if($result !== true){
				AdminServ::error(Utils::t('Unable to delete the map').' : '.$map.' ('.$result.')');
				break;
			}
			else{
				AdminServLogs::add('action', 'Delete map: '.$map);
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	// Save MatchSettings
	if( (isset($_POST['addMap']) || isset($_POST['insertMap'])) && SERVER_MATCHSET ){
		if( isset($_POST['SaveCurrentMatchSettings']) && array_key_exists('SaveCurrentMatchSettings', $_POST) ){
			if( !$client->query('SaveMatchSettings', $mapsDirectoryPath . SERVER_MATCHSET) ){
				AdminServ::error();
			}
		}
	}
	
	
	// MAPLIST
	$sort = null;
	if( isset($_GET['sort']) && $_GET['sort'] != null){
		$sort = addslashes($_GET['sort']);
	}
	$mapsList = AdminServ::getLocalMapList($currentDir, $directory, $sort);
?>