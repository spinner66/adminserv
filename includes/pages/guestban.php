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
			// Guestlist
			if($_POST['playlistType'][$i] == 'guestlist'){
				if( !$client->query('SaveGuestList', $playlist) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Save guestlist');
				}
			}
			// BlackList
			else{
				if( !$client->query('SaveBlackList', $playlist) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Save blacklist');
				}
			}
			$i++;
		}
	}
	else if( isset($_POST['loadPlaylist']) && isset($_POST['playlist']) && count($_POST['playlist'] > 0) ){
		$i = 0;
		foreach($_POST['playlist'] as $playlist){
			// Guestlist
			if($_POST['playlistType'][$i] == 'guestlist'){
				if( !$client->query('LoadGuestList', $playlist) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Load guestlist');
				}
			}
			// BlackList
			else{
				if( !$client->query('LoadBlackList', $playlist) ){
					AdminServ::error();
					break;
				}
				else{
					AdminServLogs::add('action', 'Load blacklist');
				}
			}
			$i++;
		}
	}
	else if( isset($_POST['deletePlaylist']) && isset($_POST['playlist']) && count($_POST['playlist'] > 0) ){
		foreach($_POST['playlist'] as $playlist){
			if( !File::delete($gameDataDirectory.'Config/'.$playlist) ){
				AdminServ::error(Utils::t('Unable to delete the playlist').' : '.$playlist);
				break;
			}
			else{
				AdminServLogs::add('action', 'Delete playlist: '.$playlist);
			}
		}
		
		Utils::redirection(false, '?p='. USER_PAGE);
	}
	else if( isset($_POST['createPlaylistValid']) && isset($_POST['createPlaylistName']) && $_POST['createPlaylistName'] != null ){
		// Fichier
		$filename = Str::replaceChars($_POST['createPlaylistName']);
		
		// Guestlist
		if($_POST['createPlaylistType'] == 'guestlist'){
			if( !$client->query('SaveGuestList', $filename) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Create playlist (guestlist): '.$filename);
			}
		}
		// Blacklist
		else{
			if( !$client->query('SaveBlackList', $filename) ){
				AdminServ::error();
			}
			else{
				AdminServLogs::add('action', 'Create playlist (blacklist): '.$filename);
			}
		}
		
		Utils::redirection(false, '?p='. USER_PAGE);
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
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre left">
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
	<div id="banlist">
		<h1>Banlist<?php if($countBanList > 0){ echo ' ('.$countBanList.')'; } ?></h1>
		<div class="title-detail">
			<ul>
				<li><a class="cleanList" href="?p=<?php echo USER_PAGE; ?>&amp;clean=banlist" data-empty="<?php echo Utils::t('The list is already empty.'); ?>"><?php echo Utils::t('Clean the list'); ?></a></li>
				<li class="last"><input type="checkbox" name="checkAllBanlist" id="checkAllBanlist" value=""<?php if($countBanList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft"><?php echo Utils::t('Login'); ?></th>
					<th><?php echo Utils::t('IP address'); ?></th>
					<th><?php echo Utils::t('Client'); ?></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$showBanList = null;
					
					// Liste des joueurs
					if( $countBanList > 0 ){
						$i = 0;
						foreach($banList as $player){
							// Ligne
							$showBanList .= '<tr class="'; if($i%2){ $showBanList .= 'even'; }else{ $showBanList .= 'odd'; } $showBanList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td>'.$player['IPAddress'].'</td>'
								.'<td>'.$player['ClientName'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="banlist[]" value="'.$player['Login'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showBanList .= '<tr class="no-line"><td class="center" colspan="4">'.Utils::t('No player').'</td></tr>';
					}
					
					// Affichage
					echo $showBanList;
				?>
			</tbody>
		</table>
	</div>
	
	<div id="blacklist">
		<h1>Blacklist<?php if($countBlackList > 0){ echo ' ('.$countBlackList.')'; } ?></h1>
		<div class="title-detail">
			<ul>
				<li><a class="cleanList" href="?p=<?php echo USER_PAGE; ?>&amp;clean=blacklist" data-empty="<?php echo Utils::t('The list is already empty.'); ?>"><?php echo Utils::t('Clean the list'); ?></a></li>
				<li class="last"><input type="checkbox" name="checkAllBlacklist" id="checkAllBlacklist" value=""<?php if($countBlackList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft"><?php echo Utils::t('Login'); ?></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$showBlackList = null;
					
					// Liste des joueurs
					if( $countBlackList > 0 ){
						$i = 0;
						foreach($blackList as $player){
							// Ligne
							$showBlackList .= '<tr class="'; if($i%2){ $showBlackList .= 'even'; }else{ $showBlackList .= 'odd'; } $showBlackList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="blacklist[]" value="'.$player['Login'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showBlackList .= '<tr class="no-line"><td class="center" colspan="2">'.Utils::t('No player').'</td></tr>';
					}
					
					// Affichage
					echo $showBlackList;
				?>
			</tbody>
		</table>
	</div>
	
	<div id="guestlist">
		<h1>Guestlist<?php if($countGuestList > 0){ echo ' ('.$countGuestList.')'; } ?></h1>
		<div class="title-detail">
			<ul>
				<li><a class="cleanList" href="?p=<?php echo USER_PAGE; ?>&amp;clean=guestlist" data-empty="<?php echo Utils::t('The list is already empty.'); ?>"><?php echo Utils::t('Clean the list'); ?></a></li>
				<li class="last"><input type="checkbox" name="checkAllGuestlist" id="checkAllGuestlist" value=""<?php if($countGuestList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft"><?php echo Utils::t('Login'); ?></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$showGuestList = null;
					
					// Liste des joueurs
					if( $countGuestList > 0 ){
						$i = 0;
						foreach($guestList as $player){
							// Ligne
							$showGuestList .= '<tr class="'; if($i%2){ $showGuestList .= 'even'; }else{ $showGuestList .= 'odd'; } $showGuestList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="guestlist[]" value="'.$player['Login'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showGuestList .= '<tr class="no-line"><td class="center" colspan="2">'.Utils::t('No player').'</td></tr>';
					}
					
					// Affichage
					echo $showGuestList;
				?>
			</tbody>
		</table>
	</div>
	
	<div id="ignorelist">
		<h1>Ignorelist<?php if($countIgnoreList > 0){ echo ' ('.$countIgnoreList.')'; } ?></h1>
		<div class="title-detail">
			<ul>
				<li><a class="cleanList" href="?p=<?php echo USER_PAGE; ?>&amp;clean=ignorelist" data-empty="<?php echo Utils::t('The list is already empty.'); ?>"><?php echo Utils::t('Clean the list'); ?></a></li>
				<li class="last"><input type="checkbox" name="checkAllIgnorelist" id="checkAllIgnorelist" value=""<?php if($countIgnoreList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft"><?php echo Utils::t('Login'); ?></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
				<?php
					$showIgnoreList = null;
					
					// Liste des joueurs
					if( $countIgnoreList > 0 ){
						$i = 0;
						foreach($ignoreList as $player){
							// Ligne
							$showIgnoreList .= '<tr class="'; if($i%2){ $showIgnoreList .= 'even'; }else{ $showIgnoreList .= 'odd'; } $showIgnoreList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="ignorelist[]" value="'.$player['Login'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showIgnoreList .= '<tr class="no-line"><td class="center" colspan="2">'.Utils::t('No player').'</td></tr>';
					}
					
					// Affichage
					echo $showIgnoreList;
				?>
			</tbody>
		</table>
	</div>
	
	<div class="options">
		<div class="fright">
			<div class="selected-files-label locked">
				<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
				<span class="selected-files-count">(0)</span>
				<div class="selected-files-option">
					<input class="button dark" type="submit" name="blackListPlayer" id="blackListPlayer" value="<?php echo Utils::t('Blacklist'); ?>" />
					<input class="button dark" type="submit" name="removeList" id="removeList" value="<?php echo Utils::t('Remove from the list'); ?>" />
				</div>
			</div>
		</div>
	</div>
	</form>
</section>

<section class="cadre right">
	<h1><?php echo Utils::t('Add'); ?></h1>
	<div class="content last addPlayer">
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
			<div>
				<select class="width2" name="addPlayerList" id="addPlayerList"<?php if($playerCount == 0){ echo ' hidden="hidden"'; } ?>>
					<option value="none"><?php echo Utils::t('Select a player'); ?></option>
					<?php echo $playerListOptions; ?>
					<option value="more"><?php echo Utils::t('Enter another login'); ?></option>
				</select>
				<input class="text width2" type="text" name="addPlayerLogin" id="addPlayerLogin" data-default-value="<?php echo Utils::t('Player login'); ?>" value="<?php echo Utils::t('Player login'); ?>"<?php if($playerCount != 0){ echo ' hidden="hidden"'; } ?> />
				<select class="addPlayerTypeList" name="addPlayerTypeList" id="addPlayerTypeList">
					<option value="none"><?php echo Utils::t('Add in the'); ?></option>
					<option value="guestlist">Guestlist</option>
					<option value="blacklist">Blacklist</option>
				</select>
				<input class="button light" type="submit" name="addPlayer" id="addPlayer" value="<?php echo Utils::t('Add'); ?>" />
			</div>
		</form>
	</div>
	
	<div id="playlists">
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
			<h1><?php echo Utils::t('Playlists'); ?>
				<div id="form-new-playlist" hidden="hidden">
					<select name="createPlaylistType" id="createPlaylistType">
						<option value="none"><?php echo Utils::t('Type'); ?></option>
						<option value="guestlist">Guestlist</option>
						<option value="blacklist">Blacklist</option>
					</select>
					<input class="text" type="text" name="createPlaylistName" id="createPlaylistName" data-playlistname="<?php echo Utils::t('Playlist name'); ?>" value="<?php echo Utils::t('Playlist name'); ?>" />
					<input class="button light" type="submit" name="createPlaylistValid" id="createPlaylistValid" value="<?php echo Utils::t('Create'); ?>" />
				</div>
			</h1>
		</form>
		<div class="title-detail">
			<ul>
				<li><a id="clickNewPlaylist" href="" data-cancel="<?php echo Utils::t('Cancel'); ?>" data-newplaylist="<?php echo Utils::t('New playlist'); ?>"><?php echo Utils::t('New playlist'); ?></a></li>
				<li class="last"><input type="checkbox" name="checkAllPlaylists" id="checkAllPlaylists" value="" /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<table>
			<thead>
				<tr>
					<th class="thleft"><?php echo Utils::t('Playlist'); ?></th>
					<th><?php echo Utils::t('Type'); ?></th>
					<th><?php echo Utils::t('Contains'); ?></th>
					<th><?php echo Utils::t('Modified'); ?></th>
					<th class="thright"></th>
				</tr>
			</thead>
			<tbody>
			<?php
				$showPlaylists = null;
				
				// Liste des playlists
				if( isset($playlistDirectory['files']) && count($playlistDirectory['files']) > 0 ){
					$i = 0;
					$defaultFilename = array(
						'guestlist.txt',
						'blacklist.txt',
						'guestlist.xml',
						'blacklist.xml',
					);
					foreach($playlistDirectory['files'] as $file){
						$ext = File::getDoubleExtension($file['filename']);
						if( in_array($file['filename'], $defaultFilename) || ($isDoubleExt = in_array($ext, AdminServConfig::$PLAYLIST_EXTENSION)) ){
							// Playlist data
							$data = AdminServ::getPlaylistData($gameDataDirectory.'Config/'.$file['filename']);
							if( isset($data['logins']) ){
								$countDataLogins = count($data['logins']);
								if($countDataLogins > 1){
									$nbPlayers = $countDataLogins.' '.Utils::t('players');
								}
								else{
									$nbPlayers = '1 '.Utils::t('player');
								}
							}
							else{
								$nbPlayers = '0 '.Utils::t('player');
							}
							
							// Filename
							if($isDoubleExt){
								$filename = substr($file['filename'], 0, -13);
							}
							else{
								$filename = substr($file['filename'], 0, -4);
							}
							
							// Line
							$showPlaylists .= '<tr class="'; if($i%2){ $showPlaylists .= 'even'; }else{ $showPlaylists .= 'odd'; } $showPlaylists .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/finishgrey.png" alt="" /><span title="'.$file['filename'].'">'.$filename.'</span></td>'
								.'<td class="center">'.ucfirst($data['type']).'</td>'
								.'<td class="center">'.$nbPlayers.'</td>'
								.'<td class="center">'.date('d-m-Y', $file['mtime']).'</td>'
								.'<td class="checkbox">'
									.'<input type="checkbox" name="playlist[]" value="'.$file['filename'].'" />'
									.'<input type="hidden" name="playlistType[]" value="'.$data['type'].'" />'
								.'</td>'
							.'</tr>';
							$i++;
						}
					}
				}
				else{
					$showPlaylists .= '<tr class="no-line"><td class="center" colspan="4">'.Utils::t('No playlist').'</td></tr>';
				}
				
				// Affichage
				echo $showPlaylists;
			?>
			</tbody>
		</table>
		
		<div class="options">
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="deletePlaylist" id="deletePlaylist" value="<?php echo Utils::t('Delete'); ?>" />
						<input class="button dark" type="submit" name="loadPlaylist" id="loadPlaylist" value="<?php echo Utils::t('Load'); ?>" />
						<input class="button dark" type="submit" name="savePlaylist" id="savePlaylist" value="<?php echo Utils::t('Save '); ?>" />
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
</section>
<?php
	AdminServUI::getFooter();
?>