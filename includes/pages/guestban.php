<?php
	// GAMEDATA
	$localMode = false;
	if( AdminServ::isAdminLevel('Admin') ){
		if( !$client->query('GameDataDirectory') ){
			AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
		}
		else{
			$gameDataDirectory = $client->getResponse();
			$playlistDirectory = FileFolder::readDirectory($gameDataDirectory.'Config', array(), AdminServConfig::$PLAYLIST_HIDDEN_FILES, AdminServConfig::RECENT_STATUS_PERIOD);
			if($playlistDirectory !== false){
				$localMode = true;
			}
		}
	}
	
	// ACTIONS
	// Vider la liste
	if( isset($_GET['clean']) ){
		$clean = $_GET['clean'];
		if($clean == 'banlist'){
			if( !$client->query('CleanBanList') ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
			}
		}
		else if($clean == 'ignorelist'){
			if( !$client->query('CleanIgnoreList') ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
			}
		}
		else if($clean == 'guestlist'){
			if( !$client->query('CleanGuestList') ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
			}
		}
		else if($clean == 'blacklist'){
			if( !$client->query('CleanBlackList') ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
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
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
	}
	// Retirer un joueur d'une ou plusieurs listes
	else if( isset($_POST['removeList']) ){
		if( isset($_POST['banlist']) && count($_POST['banlist']) > 0 ){
			foreach($_POST['banlist'] as $player){
				if( !$client->query('UnBan', $player) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
		else if( isset($_POST['blacklist']) && count($_POST['blacklist']) > 0 ){
			foreach($_POST['blacklist'] as $player){
				if( !$client->query('UnBlackList', $player) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
		else if( isset($_POST['guestlist']) && count($_POST['guestlist']) > 0 ){
			foreach($_POST['guestlist'] as $player){
				if( !$client->query('RemoveGuest', $player) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
		else if( isset($_POST['ignorelist']) && count($_POST['ignorelist']) > 0 ){
			foreach($_POST['ignorelist'] as $player){
				if( !$client->query('UnIgnore', $player) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
	}
	
	
	// AJOUTER
	if( isset($_POST['addPlayer']) ){
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
		if($playerlogin != 'Login joueur'){
			// Inviter
			if($addPlayerTypeList == 'guestlist'){
				if( !$client->query('AddGuest', $playerlogin) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				}
			}
			// Blacklister
			else if($addPlayerTypeList == 'blacklist'){
				if( !$client->query('BlackList', $playerlogin) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				}
			}
		}
	}
	// Liste des joueurs présent sur le serveur
	$playerList = AdminServUI::getPlayerList();
	
	
	// LECTURE
	if( !$client->query('GetBanList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
	}
	else{
		$banList = $client->getResponse();
		$countBanList = count($banList);
	}
	if( !$client->query('GetBlackList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
	}
	else{
		$blackList = $client->getResponse();
		$countBlackList = count($blackList);
	}
	if( !$client->query('GetGuestList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
	}
	else{
		$guestList = $client->getResponse();
		$countGuestList = count($guestList);
	}
	if( !$client->query('GetIgnoreList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
	}
	else{
		$ignoreList = $client->getResponse();
		$countIgnoreList = count($ignoreList);
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre left">
	<form method="post" action="?p=guestban">
	<div id="banlist">
		<h1>Banlist<?php if($countBanList > 0){ echo ' ('.$countBanList.')'; } ?></h1>
		<div class="title-detail">
			<ul>
				<li><a class="cleanList" href="?p=guestban&amp;clean=banlist" data-empty="La liste est déjà vide.">Vider la liste</a></li>
				<li><input type="checkbox" name="checkAllBanlist" id="checkAllBanlist" value=""<?php if($countBanList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft">Login</th>
					<th>Adresse IP</th>
					<th>Client</th>
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
						$showBanList .= '<tr class="no-line"><td class="center" colspan="4">Aucun joueur</td></tr>';
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
				<li><a class="cleanList" href="?p=guestban&amp;clean=blacklist" data-empty="La liste est déjà vide.">Vider la liste</a></li>
				<li><input type="checkbox" name="checkAllBlacklist" id="checkAllBlacklist" value=""<?php if($countBlackList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft">Login</th>
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
						$showBlackList .= '<tr class="no-line"><td class="center" colspan="2">Aucun joueur</td></tr>';
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
				<li><a class="cleanList" href="?p=guestban&amp;clean=guestlist" data-empty="La liste est déjà vide.">Vider la liste</a></li>
				<li><input type="checkbox" name="checkAllGuestlist" id="checkAllGuestlist" value=""<?php if($countGuestList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft">Login</th>
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
						$showGuestList .= '<tr class="no-line"><td class="center" colspan="2">Aucun joueur</td></tr>';
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
				<li><a class="cleanList" href="?p=guestban&amp;clean=ignorelist" data-empty="La liste est déjà vide.">Vider la liste</a></li>
				<li><input type="checkbox" name="checkAllIgnorelist" id="checkAllIgnorelist" value=""<?php if($countIgnoreList == 0){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		<table>
			<thead>
				<tr>
					<th class="thleft">Login</th>
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
						$showIgnoreList .= '<tr class="no-line"><td class="center" colspan="2">Aucun joueur</td></tr>';
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
				<span class="selected-files-title">Pour la sélection</span>
				<span class="selected-files-count">(0)</span>
				<div class="selected-files-option">
					<input class="button dark" type="submit" name="blackListPlayer" id="blackListPlayer" value="Blacklister" />
					<input class="button dark" type="submit" name="removeList" id="removeList" value="Retirer de la liste" />
				</div>
			</div>
		</div>
	</div>
	</form>
</section>

<section class="cadre right">
	<h1>Ajouter</h1>
	<div class="content last addPlayer">
		<form method="post" action="?p=guestban">
			<div>
				<select class="width2" name="addPlayerList" id="addPlayerList"<?php if($playerList == null){ echo ' hidden="hidden"'; } ?>>
					<option value="none">Sélectionnez un joueur</option>
					<option value="more">Entrez un autre login</option>
				</select>
				<input class="text width2" type="text" name="addPlayerLogin" id="addPlayerLogin" data-default-value="Login joueur" value="Login joueur"<?php if($playerList != null){ echo ' hidden="hidden"'; } ?> />
				<select class="addPlayerTypeList" name="addPlayerTypeList" id="addPlayerTypeList">
					<option value="none">Ajouter à la</option>
					<option value="guestlist">Guestlist</option>
					<option value="blacklist">Blacklist</option>
				</select>
				<input class="button light" type="submit" name="addPlayer" id="addPlayer" value="Ajouter" />
				<div class="fclear"></div>
			</div>
		</form>
	</div>
	
	<div id="playlists">
		<h1>Playlists</h1>
		<div class="title-detail">
			<ul>
				<li><a href="">Nouvelle playlist</a></li>
				<li><input type="checkbox" name="checkAllPlaylists" id="checkAllPlaylists" value="" /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=guestban">
			<table>
				<thead>
					<tr>
						<th class="thleft">Playlist</th>
						<th>Type</th>
						<th>Contient</th>
						<th>Modifié le</th>
						<th class="thright"></th>
					</tr>
				</thead>
				<tbody>
				<?php
					$showPlaylists = null;
					
					// Liste des playlists
					if( isset($playlistDirectory['files']) && count($playlistDirectory['files']) > 0 ){
						$i = 0;
						foreach($playlistDirectory['files'] as $file){
							$ext = File::getExtension($file['filename']);
							if($ext == 'txt' || $ext = 'text' || $ext == 'xml'){
								$data = AdminServ::getPlaylistData($gameDataDirectory.'Config/'.$file['filename']);
								if( isset($data['logins']) ){
									$countDataLogins = count($data['logins']);
									if($countDataLogins > 1){
										$nbPlayers = $countDataLogins.' joueurs';
									}
									else{
										$nbPlayers = '1 joueur';
									}
								}
								else{
									$nbPlayers = '0 joueur';
								}
								
								// Ligne
								$showPlaylists .= '<tr class="'; if($i%2){ $showPlaylists .= 'even'; }else{ $showPlaylists .= 'odd'; } $showPlaylists .= '">'
									.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/finishgrey.png" alt="" /><span title="'.$file['filename'].'">'.substr($file['filename'], 0, -4).'</span></td>'
									.'<td class="center">'.ucfirst($data['type']).'</td>'
									.'<td class="center">'.$nbPlayers.'</td>'
									.'<td class="center">'.date('d-m-Y', $file['mtime']).'</td>'
									.'<td class="checkbox"><input type="checkbox" name="playlistFile[]" value="'.$file['filename'].'" /></td>'
								.'</tr>';
								$i++;
							}
						}
					}
					else{
						$showPlaylists .= '<tr class="no-line"><td class="center" colspan="4">Aucune playlist</td></tr>';
					}
					
					// Affichage
					echo $showPlaylists;
				?>
				</tbody>
			</table>
		</form>
		
		<div class="options">
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title">Pour la sélection</span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="BanLoginList" id="BanLoginList" value="Bannir" />
						<input class="button dark" type="submit" name="KickLoginList" id="KickLoginList" value="Kicker" />
						<input class="button dark" type="submit" name="ForceSpectatorList" id="ForceSpectatorList" value="Spectateur" />
						<input class="button dark" type="submit" name="ForcePlayerList" id="ForcePlayerList" value="Joueur" />
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	AdminServUI::getFooter();
?>