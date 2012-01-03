<?php
	// GAMEDATA
	$hiddenFiles = array(
		'gbx',
		'dedicated_cfg.txt',
		'checksum.txt',
		'servers.txt',
		'php',
		'dat',
		'log',
		'cfg',
		'cfg~'
	);
	
	$localMode = false;
	if( AdminServ::isAdminLevel('Admin') ){
		if( !$client->query('GameDataDirectory') ){
			echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$gameDataDirectory = $client->getResponse();
			$playlistDirectory = FileFolder::readDirectory($gameDataDirectory.'Config', array(), $hiddenFiles, AdminServConfig::RECENT_STATUS_PERIOD);
			if($playlistDirectory !== false){
				$localMode = true;
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
					echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
			// Blacklister
			else if($addPlayerTypeList == 'blacklist'){
				if( !$client->query('BlackList', $playerlogin) ){
					echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
				}
			}
		}
	}
	// Liste des joueurs présent sur le serveur
	$playerList = AdminServTemplate::getPlayerList();
	
	
	// LECTURE
	if( !$client->query('GetBanList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$banList = $client->getResponse();
	}
	if( !$client->query('GetBlackList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$blackList = $client->getResponse();
	}
	if( !$client->query('GetGuestList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$guestList = $client->getResponse();
	}
	if( !$client->query('GetIgnoreList', AdminServConfig::LIMIT_PLAYERS_LIST, 0) ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$ignoreList = $client->getResponse();
	}
	
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section>
	<div class="cadre left">
		<div class="playlist">
			<h1>Banlist</h1>
			<div class="title-detail">
				<ul>
					<li><a href="">Vider la liste</a></li>
					<li><input type="checkbox" name="" id="" value="" /></li>
				</ul>
			</div>
			<table>
				<tr>
					<th class="thleft">Login</th>
					<th>Adresse IP</th>
					<th>Client</th>
					<th class="thright"></th>
				</tr>
				<?php
					$showBanList = null;
					
					// Liste des joueurs
					if( count($banList) > 0 ){
						$i = 0;
						foreach($banList as $player){
							// Ligne
							$showBanList .= '<tr class="'; if($i%2){ $showBanList .= 'even'; }else{ $showBanList .= 'odd'; } $showBanList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td>'.$player['IPAddress'].'</td>'
								.'<td>'.$player['ClientName'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
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
			</table>
		</div>
		
		<div class="playlist">
			<h1>Blacklist</h1>
			<div class="title-detail">
				<ul>
					<li class="last"><a href="">Vider la liste</a></li>
				</ul>
			</div>
			<table>
				<tr>
					<th class="thleft">Login</th>
					<th class="thright"></th>
				</tr>
				<?php
					$showBlackList = null;
					
					// Liste des joueurs
					if( count($blackList) > 0 ){
						$i = 0;
						foreach($blackList as $player){
							// Ligne
							$showBlackList .= '<tr class="'; if($i%2){ $showBlackList .= 'even'; }else{ $showBlackList .= 'odd'; } $showBlackList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
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
			</table>
		</div>
		
		<div class="playlist">
			<h1>Guestlist</h1>
			<div class="title-detail">
				<ul>
					<li class="last"><a href="">Vider la liste</a></li>
				</ul>
			</div>
			<table>
				<tr>
					<th class="thleft">Login</th>
					<th class="thright"></th>
				</tr>
				<?php
					$showGuestList = null;
					
					// Liste des joueurs
					if( count($guestList) > 0 ){
						$i = 0;
						foreach($guestList as $player){
							// Ligne
							$showGuestList .= '<tr class="'; if($i%2){ $showGuestList .= 'even'; }else{ $showGuestList .= 'odd'; } $showGuestList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
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
			</table>
		</div>
		
		<div class="playlist">
			<h1>Ignorelist</h1>
			<div class="title-detail">
				<ul>
					<li class="last"><a href="">Vider la liste</a></li>
				</ul>
			</div>
			<table>
				<tr>
					<th class="thleft">Login</th>
					<th class="thright"></th>
				</tr>
				<?php
					$showIgnoreList = null;
					
					// Liste des joueurs
					if( count($ignoreList) > 0 ){
						$i = 0;
						foreach($ignoreList as $player){
							// Ligne
							$showIgnoreList .= '<tr class="'; if($i%2){ $showIgnoreList .= 'even'; }else{ $showIgnoreList .= 'odd'; } $showIgnoreList .= '">'
								.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['Login'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
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
			</table>
		</div>
	</div>
	<div class="cadre right">
		<h1>Playlists</h1>
		<div class="title-detail">
			<ul>
				<li><a href="">Nouvelle playlist</a></li>
				<li><input type="checkbox" name="" id="" value="" /></li>
			</ul>
		</div>
		<table>
			<tr>
				<th class="thleft">Playlist</th>
				<th>Type</th>
				<th>Contient</th>
				<th>Modifié le</th>
				<th class="thright"></th>
			</tr>
			<?php
				$showPlaylists = null;
				
				// Liste des playlists
				if( isset($playlistDirectory['files']) && count($playlistDirectory['files']) > 0 ){
					$i = 0;
					foreach($playlistDirectory['files'] as $file){
						$ext = FileFolder::getFilenameExtension($file['filename']);
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
		</table>
		
		<h1>Ajouter</h1>
		<div class="content last addPlayer">
			<form method="post" action="?p=guestban">
				<div>
					<select class="width2<?php if($playerList == null){ echo ' displaynone'; } ?>" name="addPlayerList" id="addPlayerList">
						<option value="none">Sélectionnez un joueur</option>
						<option value="more">Entrez un autre login</option>
					</select>
					<input class="text width2<?php if($playerList != null){ echo ' displaynone'; } ?>" type="text" name="addPlayerLogin" id="addPlayerLogin" data-default-value="Login joueur" value="Login joueur" />
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
	</div>
</section>
<?php
	AdminServTemplate::getFooter();
?>