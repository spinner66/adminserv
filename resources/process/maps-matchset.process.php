<?php
	// MATCHSETLIST
	$matchsetList = AdminServ::getLocalMatchSettingList($currentDir, $directory);
	
	
	// ACTIONS
	if( isset($_POST['saveMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('SaveMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Save matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['loadMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('LoadMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Load matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['addMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('AppendPlaylistFromMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Append playlist from matchsettings: '.$matchset);
			}
		}
	Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['insertMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !$client->query('InsertPlaylistFromMatchSettings', $mapsDirectoryPath.$matchset) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Insert playlist from matchsettings: '.$matchset);
			}
		}
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
	else if( isset($_POST['editMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		AdminServLogs::add('action', 'Edit matchsettings: '.$_POST['matchset'][0]);
		// Redirection sur la page de création d'un matchsettings
		Utils::redirection(false, '?p=maps-creatematchset'.$hasDirectory.'&f='.$_POST['matchset'][0]);
	}
	else if( isset($_POST['deleteMatchset']) && isset($_POST['matchset']) && count($_POST['matchset']) > 0 ){
		foreach($_POST['matchset'] as $matchset){
			if( !File::delete($mapsDirectoryPath.$matchset) ){
				AdminServ::error(Utils::t('Unable to delete the playlist').' : '.$matchset);
			}
			else{
				AdminServLogs::add('action', 'Delete matchsettings: '.$matchset);
			}
		}
		
		$hasDirectory = null;
		if($directory){
			$hasDirectory = '&d='.$directory;
		}
		Utils::redirection(false, '?p='.USER_PAGE .$hasDirectory);
	}
?>