<?php
	// GAMEDATA
	if( AdminServ::isAdminLevel('Admin') ){
		if( !$client->query('GameDataDirectory') ){
			AdminServ::error();
		}
		else{
			$gameDataDirectory = $client->getResponse();
			$playlistDirectory = Folder::read($gameDataDirectory.'Config', array(), array(), intval(AdminServConfig::RECENT_STATUS_PERIOD * 3600) );
		}
	}
	
	// ACTIONS
	// Vider la liste
	if( isset($_GET['clean']) ){
		$clean = strtolower($_GET['clean']);
		if($clean == 'banlist'){
			if( !$client->query('CleanBanList') ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Clean banlist');
			}
		}
		else if($clean == 'ignorelist'){
			if( !$client->query('CleanIgnoreList') ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Clean ignorelist');
			}
		}
		else if($clean == 'guestlist'){
			if( !$client->query('CleanGuestList') ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Clean guestlist');
			}
		}
		else if($clean == 'blacklist'){
			if( !$client->query('CleanBlackList') ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Clean blacklist');
			}
		}
	}
	// Blacklister
	else if( isset($_POST['blackListPlayer']) ){
		// Création du tableau de joueurs à blacklister
		$blackListPlayer = array();
		if( isset($_POST['banlist']) && count($_POST['banlist']) > 0 ){
			$blackListPlayer = array_merge($blackListPlayer, $_POST['banlist']);
		}
		if( isset($_POST['guestlist']) && count($_POST['guestlist']) > 0 ){
			$blackListPlayer = array_merge($blackListPlayer, $_POST['guestlist']);
		}
		if( isset($_POST['ignorelist']) && count($_POST['ignorelist']) > 0 ){
			$blackListPlayer = array_merge($blackListPlayer, $_POST['ignorelist']);
		}
		$blackListPlayer = array_unique($blackListPlayer);
		
		// BlackList de toutes les listes
		if( count($blackListPlayer) > 0 ){
			foreach($blackListPlayer as $player){
				if( !$client->query('BlackList', $player) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Blacklist player: '.$player);
				}
			}
		}
	}
	// Retirer un joueur d'une ou plusieurs listes
	else if( isset($_POST['removeList']) ){
		if( isset($_POST['banlist']) && count($_POST['banlist']) > 0 ){
			foreach($_POST['banlist'] as $player){
				if( !$client->query('UnBan', $player) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Unban player: '.$player);
				}
			}
		}
		else if( isset($_POST['blacklist']) && count($_POST['blacklist']) > 0 ){
			foreach($_POST['blacklist'] as $player){
				if( !$client->query('UnBlackList', $player) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Unblacklist player: '.$player);
				}
			}
		}
		else if( isset($_POST['guestlist']) && count($_POST['guestlist']) > 0 ){
			foreach($_POST['guestlist'] as $player){
				if( !$client->query('RemoveGuest', $player) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Remove guest player: '.$player);
				}
			}
		}
		else if( isset($_POST['ignorelist']) && count($_POST['ignorelist']) > 0 ){
			foreach($_POST['ignorelist'] as $player){
				if( !$client->query('UnIgnore', $player) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Unignore player: '.$player);
				}
			}
		}
	}
	
	
	// AJOUTER
	else if( isset($_POST['addPlayer']) ){
		// Variables
		$addPlayerList = $_POST['addPlayerList'];
		$addPlayerLogin = strtolower( trim($_POST['addPlayerLogin']) );
		$addPlayerTypeList = $_POST['addPlayerTypeList'];
		
		// PlayerLogin
		if($addPlayerList != 'none' && $addPlayerList != 'more'){
			$playerlogin = $addPlayerList;
		}else{
			$playerlogin = $addPlayerLogin;
		}
		
		// Requête
		if($playerlogin != Utils::t('Player login') ){
			// Inviter
			if($addPlayerTypeList == 'guestlist'){
				if( !$client->query('AddGuest', $playerlogin) ){
					AdminServ::error();
				}
				else{
					AdminServLogs::add('action', 'Add guest player: '.$playerlogin);
				}
			}
			// Blacklister
			else if($addPlayerTypeList == 'blacklist'){
				if( !$client->query('BlackList', $playerlogin) ){
					AdminServ::error();
				}
				else{
					AdminServLogs::add('action', 'Add blacklist player: '.$playerlogin);
				}
			}
		}
	}
	
	
	// PLAYLISTS LOCAL
	else if( isset($_POST['savePlaylist']) && isset($_POST['playlist']) && count($_POST['playlist'] > 0) ){
		$i = 0;
		foreach($_POST['playlist'] as $playlist){
			$playlistEx = explode('|', $playlist);
			$type = $playlistEx[0];
			$filename = $playlistEx[1];
			
			// Guestlist
			if($type == 'guestlist'){
				if( !$client->query('SaveGuestList', $filename) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Save guestlist');
				}
			}
			// BlackList
			elseif($type == 'blacklist'){
				if( !$client->query('SaveBlackList', $filename) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Save blacklist');
				}
			}
			$i++;
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	else if( isset($_POST['loadPlaylist']) && isset($_POST['playlist']) && count($_POST['playlist'] > 0) ){
		$i = 0;
		foreach($_POST['playlist'] as $playlist){
			$playlistEx = explode('|', $playlist);
			$type = $playlistEx[0];
			$filename = $playlistEx[1];
			
			// Guestlist
			if($type == 'guestlist'){
				if( !$client->query('LoadGuestList', $filename) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Load guestlist');
				}
			}
			// BlackList
			elseif($type == 'blacklist'){
				if( !$client->query('LoadBlackList', $filename) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Load blacklist');
				}
			}
			$i++;
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	else if( isset($_POST['deletePlaylist']) && isset($_POST['playlist']) && count($_POST['playlist'] > 0) ){
		foreach($_POST['playlist'] as $playlist){
			$playlistEx = explode('|', $playlist);
			$filename = $playlistEx[1];
			
			if( !File::delete($gameDataDirectory.'Config/'.$filename) ){
				AdminServ::error(Utils::t('Unable to delete the playlist').' : '.$filename);
				break;
			}
			else{
				AdminServLogs::add('action', 'Delete playlist: '.$filename);
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	else if( isset($_POST['createPlaylistValid']) && isset($_POST['createPlaylistName']) && $_POST['createPlaylistName'] != null ){
		// Fichier
		$filename = Str::replaceChars($_POST['createPlaylistName']);
		
		// Guestlist
		if( $filename != Str::replaceChars(Utils::t('Playlist name')) ){
			if($_POST['createPlaylistType'] == 'guestlist'){
				if( !$client->query('SaveGuestList', $filename) ){
					AdminServ::error();
				}
				else{
					AdminServLogs::add('action', 'Create playlist (guestlist): '.$filename);
				}
			}
			// Blacklist
			elseif($_POST['createPlaylistType'] == 'blacklist'){
				if( !$client->query('SaveBlackList', $filename) ){
					AdminServ::error();
				}
				else{
					AdminServLogs::add('action', 'Create playlist (blacklist): '.$filename);
				}
			}
		}
		
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	// LECTURE
	$client->addCall('GetBanList', array(AdminServConfig::LIMIT_PLAYERS_LIST, 0) );
	$client->addCall('GetBlackList', array(AdminServConfig::LIMIT_PLAYERS_LIST, 0) );
	$client->addCall('GetGuestList', array(AdminServConfig::LIMIT_PLAYERS_LIST, 0) );
	$client->addCall('GetIgnoreList', array(AdminServConfig::LIMIT_PLAYERS_LIST, 0) );
	if( !$client->multiquery() ){
		AdminServ::error();
	}
	else{
		$queriesData = $client->getMultiqueryResponse();
		$banList = $queriesData['GetBanList'];
		$blackList = $queriesData['GetBlackList'];
		$guestList = $queriesData['GetGuestList'];
		$ignoreList = $queriesData['GetIgnoreList'];
		$countBanList = count($banList);
		$countBlackList = count($blackList);
		$countGuestList = count($guestList);
		$countIgnoreList = count($ignoreList);
	}
	
	// Liste des joueurs présent sur le serveur
	$playerListOptions = AdminServUI::getPlayerList();
	$playerCount = AdminServ::getNbPlayers();
?>