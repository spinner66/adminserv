<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error('Vous n\'êtes pas autorisé à configurer les serveurs.');
		Utils::redirection(false);
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur
		if( AdminServServerConfig::hasServer() ){
			// Si on n'autorise pas la configuration en ligne
			if( OnlineConfig::ACTIVATE !== true ){
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false);
			}
			else{
				if( OnlineConfig::ADD_ONLY === true ){
					Utils::redirection(false, './?p=addserver');
				}
			}
		}
		else{
			// Si on n'autorise pas la configuration en ligne
			if( OnlineConfig::ACTIVATE !== true ){
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false);
			}
			else{
				if( OnlineConfig::ADD_ONLY === true ){
					Utils::redirection(false, './?p=addserver');
				}
			}
		}
	}
	else{
		AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
		Utils::redirection(false);
	}
	
	
	// EDITION
	if( isset($_POST['editserver']) ){
		$serverId = AdminServServerConfig::getServerId($_POST['server'][0]);
		Utils::redirection(false, '?p=addserver&id='.$serverId);
	}
	
	
	// DUPLIQUER
	if( isset($_POST['duplicateserver']) ){
		// GET
		$getServerData = AdminServServerConfig::getServer($_POST['server'][0]);
		$serverAdmLvl_SA = $getServerData['adminlevel']['SuperAdmin'];
		$serverAdmLvl_ADM = $getServerData['adminlevel']['Admin'];
		$serverAdmLvl_USR = $getServerData['adminlevel']['User'];
		if( is_array($serverAdmLvl_SA) ){ $serverAdmLvl_SA = implode(', ', $serverAdmLvl_SA); }
		if( is_array($serverAdmLvl_ADM) ){ $serverAdmLvl_ADM = implode(', ', $serverAdmLvl_ADM); }
		if( is_array($serverAdmLvl_USR) ){ $serverAdmLvl_USR = implode(', ', $serverAdmLvl_USR); }
		
		// SET
		$setServerData = array(
			'name' => $_POST['server'][0] . ' - copie',
			'address' => $getServerData['address'],
			'port' => $getServerData['port'],
			'matchsettings' => $getServerData['matchsettings'],
			'adminlevel' => array(
				'SuperAdmin' => $serverAdmLvl_SA,
				'Admin' => $serverAdmLvl_ADM,
				'User' => $serverAdmLvl_USR,
			)
		);
		if( AdminServServerConfig::saveServerConfig($setServerData) ){
			AdminServ::info('Le serveur a bien été dupliqué.');
			Utils::redirection(false, '?p=servers');
		}
		else{
			AdminServ::error('Impossible de dupliquer le serveur.');
		}
	}
	
	
	// SUPPRESSION
	if( isset($_POST['deleteserver']) ){
		$servers = ServerConfig::$SERVERS;
		unset($servers[$_POST['server'][0]]);
		AdminServServerConfig::saveServerConfig(array(), -1, $servers);
		AdminServ::info('Le serveur "'.$_POST['server'][0].'" a été supprimé.');
		Utils::redirection(false, '?p=servers');
	}
	
	
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Liste des serveurs</h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
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
					// MatchSettings
					if($serverData['matchsettings']){
						$matchSettings = $serverData['matchsettings'];
					}else{
						$matchSettings = 'Aucun';
					}
					
					// Niveaux admins
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
							else if($serverData['adminlevel'][$level] === 'all'){
								$adminLevelsStatus[] = 'Tous';
							}
							else if($serverData['adminlevel'][$level] === 'none'){
								$adminLevelsStatus[] = 'Enlevé';
							}
							else{
								$adminLevelsStatus[] = 'Manquant';
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
				$showServerList .= '<tr class="no-line"><td class="center" colspan="8">Aucun serveur</td></tr>';
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
					<input class="button dark" type="submit" name="deleteserver" id="deleteserver" value="Supprimer" />
					<input class="button dark" type="submit" name="duplicateserver" id="duplicateserver" value="Dupliquer" />
					<input class="button dark" type="submit" name="editserver" id="editserver" value="Modifier" />
				</div>
			</div>
		</div>
	</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>