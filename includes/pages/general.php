<?php
	// ACTIONS
	if( isset($_POST['BanLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('Ban', $player) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
		}
	}
	else if( isset($_POST['KickLoginList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('Kick', $player) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
		}
	}
	else if( isset($_POST['ForcePlayerList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('ForceSpectator', $player, 2) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
			else{
				if( !$client->query('ForceSpectator', $player, 0) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
	}
	else if( isset($_POST['ForceSpectatorList']) && count($_POST['player']) > 0 ){
		foreach($_POST['player'] as $player){
			if( !$client->query('ForceSpectator', $player, 1) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
			else{
				if(!$client->query('ForceSpectator', $player, 0) ){
					AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
					break;
				}
			}
		}
	}
	
	// Info serveur
	$serverInfo = AdminServ::getCurrentServerInfo();
	
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="cadre left">
	<h1>Map en cours</h1>
	<div class="content">
		<table class="current_map">
			<tr>
				<td class="key">Nom</td>
				<td class="value" id="map_name"><?php echo $serverInfo['map']['name']; ?></td>
			</tr>
			<tr>
				<td class="key">Auteur</td>
				<td class="value" id="map_author"><?php echo $serverInfo['map']['author']; ?></td>
			</tr>
			<tr>
				<td class="key">Environnement</td>
				<td class="value" id="map_enviro"><?php echo $serverInfo['map']['enviro']; ?><img src="<?php echo AdminServConfig::PATH_RESSOURCES .'images/env/'.strtolower($serverInfo['map']['enviro']); ?>.png" alt="" /></td>
			</tr>
			<tr>
				<td class="key">Map UId</td>
				<td class="value" id="map_uid"><?php echo $serverInfo['map']['uid']; ?></td>
			</tr>
			<tr>
				<td class="key">Mode de jeu</td>
				<td class="value<?php echo ' '.strtolower($serverInfo['srv']['game_mode']); ?>" id="map_gamemode"><?php echo $serverInfo['srv']['game_mode']; ?></td>
			</tr>
		</table>
		<?php
			if($serverInfo['map']['thumb'] != null){
				echo '<div id="map_thumbnail">'
					.'<img src="data:image/jpeg;base64,'.$serverInfo['map']['thumb'].'" alt="No thumbnail" />'
				.'</div>';
			}
		?>
		<div class="fclear"></div>
	</div>
	
	<h1>Serveur</h1>
	<div class="content">
		<table>
			<tr>
				<td class="key">Nom du serveur</td>
				<td class="value" id="server_name"><?php echo $serverInfo['srv']['name']; ?></td>
			</tr>
			<tr>
				<td class="key">Statut</td>
				<td class="value" id="server_status"><?php echo $serverInfo['srv']['status']; ?></td>
			</tr>
			<tr>
				<td class="key">Login serveur</td>
				<td class="value"><?php echo SERVER_LOGIN; ?></td>
			</tr>
			<tr>
				<td class="key">Connecté sur</td>
				<td class="value<?php echo ' '.strtolower(SERVER_VERSION_NAME); ?>" id="srv_version_name"><?php echo SERVER_VERSION_NAME; ?></td>
			</tr>
			<tr>
				<td class="key">Version dédié</td>
				<td class="value"><?php echo SERVER_BUILD.' ('. SERVER_VERSION .')'; ?></td>
			</tr>
		</table>
	</div>
	
	<?php if( AdminServ::isAdminLevel('SuperAdmin') ){ ?>
		<h1>Statistiques</h1>
		<div class="content last">
			<table>
				<tr>
					<td class="key">Démarré depuis</td>
					<td class="value" id="network_uptime"><?php echo $serverInfo['net']['uptime']; ?></td>
				</tr>
				<tr>
					<td class="key">Nombre de connexions</td>
					<td class="value" id="network_nbrconnection"><?php echo $serverInfo['net']['nbrconnection']; ?></td>
				</tr>
				<tr>
					<td class="key">Temps de connexion moyen</td>
					<td class="value" id="network_meanconnectiontime"><?php echo $serverInfo['net']['meanconnectiontime']; ?></td>
				</tr>
				<tr>
					<td class="key">Nombre de joueurs moyen</td>
					<td class="value" id="network_meannbrplayer"><?php echo $serverInfo['net']['meannbrplayer']; ?></td>
				</tr>
				<tr>
					<td class="key">Taux de récéption</td>
					<td class="value" id="network_recvnetrate"><?php echo $serverInfo['net']['meannbrplayer']; ?></td>
				</tr>
				<tr>
					<td class="key">Taux d'envoi</td>
					<td class="value" id="network_sendnetrate"><?php echo $serverInfo['net']['sendnetrate']; ?></td>
				</tr>
				<tr>
					<td class="key">Réception totale</td>
					<td class="value" id="network_totalreceivingsize"><?php echo $serverInfo['net']['totalreceivingsize']; ?></td>
				</tr>
				<tr>
					<td class="key">Emission totale</td>
					<td class="value" id="network_totalsendingsize"><?php echo $serverInfo['net']['totalsendingsize']; ?></td>
				</tr>
			</table>
		</div>
	<?php } ?>
</section>

<section class="cadre right">
	<h1>Joueurs</h1>
	<div class="title-detail">
		<ul>
			<li><a href="">Mode détail</a></li>
			<li><input type="checkbox" name="checkAll" id="checkAll" value="" /></li>
		</ul>
	</div>
	
	<!-- Liste des joueurs -->
	<form method="post" action=".">
	<div id="playerlist">
		<table>
			<thead>
				<tr>
					<th class="thleft"><a href="?sort=nickname">Pseudo</a></th>
					<th><a href="?sort=login">Login</a></th>
					<th><a href="?sort=status">Statut</a></th>
					<th class="thright"></th>
				</tr>
				<tr class="table-separation"></tr>
			</thead>
			<tbody>
			<?php
				$showPlayerList = null;
				
				// Liste des joueurs
				if( is_array($serverInfo['ply']) && count($serverInfo['ply']) > 0 ){
					$i = 0;
					foreach($serverInfo['ply'] as $player){
						// Ligne
						$showPlayerList .= '<tr class="'; if($i%2){ $showPlayerList .= 'even'; }else{ $showPlayerList .= 'odd'; } $showPlayerList .= '">'
							.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/solo.png" alt="" />'.$player['NickName'].'</td>'
							.'<td>'.$player['Login'].'</td>'
							.'<td>'.$player['PlayerStatus'].'</td>'
							.'<td class="checkbox"><input type="checkbox" name="player[]" value="'.$player['Login'].'" /></td>'
						.'</tr>';
						$i++;
					}
				}
				else{
					$showPlayerList .= '<tr class="no-line"><td class="center" colspan="4">'.$serverInfo['ply'].'</td></tr>';
				}
				
				// Affichage
				echo $showPlayerList;
			?>
			</tbody>
		</table>
	</div>
	
	<div class="options">
		<div class="fleft">
			<span class="nb-line"><?php echo $serverInfo['nbp']; ?></span>
		</div>
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
	</form>
</section>

<?php
	AdminServTemplate::getFooter();
?>