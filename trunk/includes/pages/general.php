<?php
	// Info serveur
	$serverInfo = AdminServ::getCurrentServerInfo();
	
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section>
	<div class="cadre left">
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
			<div id="map_thumbnail"><img src="data:image/jpeg;base64,<?php echo $serverInfo['map']['thumb']; ?>" alt="No thumbnail" /></div>
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
					<td class="value"><?php echo SERVER_BUILD; ?></td>
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
	</div>
	
	<div class="cadre right">
		<h1>Joueurs</h1>
		<div class="title-detail">
			<ul>
				<li><a href="">Mode détail</a></li>
				<li><input type="checkbox" name="" id="" value="" /></li>
			</ul>
		</div>
		
		<!-- Liste des joueurs -->
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
						<input class="button dark" type="button" name="delete" id="delete" value="Supprimer" />
						<input class="button dark" type="button" name="archive" id="archive" value="Créer une archive" />
						<input class="button dark" type="button" name="rename" id="rename" value="Renommer" />
						<input class="button dark" type="button" name="move" id="move" value="Déplacer" />
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
	AdminServTemplate::getFooter();
?>