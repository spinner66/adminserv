<?php
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Liste des serveurs</h1>
	<table id="serverList">
		<thead>
			<tr>
				<th class="thleft"><a href="?sort=">Nom du serveur</a></th>
				<th><a href="?sort=">Adresse</a></th>
				<th>Port</th>
				<th>MatchSettings</th>
				<th>Niveau SuperAdmin</th>
				<th>Niveau Admin</th>
				<th>Niveau User</th>
				<th class="thright">Gestion</th>
			</tr>
			<tr class="table-separation"></tr>
		</thead>
		<tbody>
		<?php
			$showServerList = null;
			
			// Liste des serveurs
			if( is_array($serverList) && count($serverList) > 0 ){
				$i = 0;
				foreach($serverList as $serverName => $serverData){
					// Données
					if($serverData['matchsettings']){
						$matchSettings = $serverData['matchsettings'];
					}
					else{
						$matchSettings = 'Aucun';
					}
					$adminLevels = array('SuperAdmin', 'Admin', 'User');
					$adminLevelsStatus = array();
					foreach($adminLevels as $level){
						if( array_key_exists($level, $serverData['adminlevel']) ){
							if( is_array($serverData['adminlevel'][$level]) ){
								$adminLevelsStatus[] = 'Adresse IP';
							}
							else if($serverData['adminlevel'][$level] === 'local'){
								$adminLevelsStatus[] = 'Réseau local';
							}
							else if($serverData['adminlevel'][$level] === null){
								$adminLevelsStatus[] = 'Tous';
							}
						}
						else{
							$adminLevelsStatus[] = 'Manquant';
						}
					}
					
					// Ligne
					$showServerList .= '<tr class="'; if($i%2){ $showServerList .= 'even'; }else{ $showServerList .= 'odd'; } $showServerList .= '">'
						.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/servers.png" alt="" />'.$serverName.'</td>'
						.'<td>'.$serverData['address'].'</td>'
						.'<td>'.$serverData['port'].'</td>'
						.'<td>'.$matchSettings.'</td>'
						.'<td>'.$adminLevelsStatus[0].'</td>'
						.'<td>'.$adminLevelsStatus[1].'</td>'
						.'<td>'.$adminLevelsStatus[2].'</td>'
						.'<td class="checkbox"><input type="radio" name="server[]" value="'.$serverName.'" /></td>'
					.'</tr>';
					$i++;
				}
			}
			else{
				$showServerList .= '<tr class="no-line"><td class="center" colspan="6">Aucun serveur</td></tr>';
			}
			
			// Affichage
			echo $showServerList;
		?>
		</tbody>
	</table>
	
	<div class="options">
		<div class="fleft">
			<span class="nb-line">
				<?php
					if( is_array($serverList) && count($serverList) > 0 ){
						$countServerList = count($serverList);
						if($countServerList > 1 ){
							echo $countServerList.' serveurs';
						}
						else{
							echo $countServerList.' serveur';
						}
					}
				?>
			</span>
		</div>
		<div class="fright">
			<div class="selected-files-label locked">
				<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
				<div class="selected-files-option">
					<input class="button dark" type="submit" name="" id="" value="Supprimer" />
					<input class="button dark" type="submit" name="" id="" value="Modifier" />
				</div>
			</div>
		</div>
	</div>
</section>
<?php
	AdminServUI::getFooter();
?>