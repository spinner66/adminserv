<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error( Utils::t('You are not allowed to configure the servers') );
		Utils::redirection();
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur
		if( AdminServServerConfig::hasServer() ){
			// Si on n'autorise pas la configuration en ligne
			if( OnlineConfig::ACTIVATE !== true ){
				AdminServ::info( Utils::t('No server available. For add this, configure "config/servers.cfg.php" file.') );
				Utils::redirection();
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
				AdminServ::info( Utils::t('No server available. For add this, configure "config/servers.cfg.php" file.') );
				Utils::redirection();
			}
			else{
				if( OnlineConfig::ADD_ONLY === true ){
					Utils::redirection(false, './?p=addserver');
				}
			}
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file doesn\'t reconized by AdminServ.') );
		Utils::redirection();
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
		
		// SET
		$setServerData = array(
			'name' => trim( htmlspecialchars( addslashes($_POST['server'][0] . ' - '.Utils::t('copy') ) ) ),
			'address' => trim($getServerData['address']),
			'port' => intval($getServerData['port']),
			'matchsettings' => trim($getServerData['matchsettings']),
			'adminlevel' => array(
				'SuperAdmin' => $getServerData['adminlevel']['SuperAdmin'],
				'Admin' => $getServerData['adminlevel']['Admin'],
				'User' => $getServerData['adminlevel']['User'],
			)
		);
		if( AdminServServerConfig::saveServerConfig($setServerData) ){
			AdminServ::info( Utils::t('This server has been duplicated.') );
			Utils::redirection(false, '?p='.USER_PAGE);
		}
		else{
			AdminServ::error( Utils::t('Unable to duplicate server.') );
		}
	}
	
	
	// SUPPRESSION
	if( isset($_POST['deleteserver']) ){
		$servers = ServerConfig::$SERVERS;
		unset($servers[$_POST['server'][0]]);
		AdminServServerConfig::saveServerConfig(array(), -1, $servers);
		AdminServ::info('Le serveur "'.$_POST['server'][0].'" a été supprimé.');
		Utils::redirection(false, '?p='.USER_PAGE);
	}
	
	
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Servers list'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
	<table id="serverList">
		<thead>
			<tr>
				<th class="thleft"><a href="?sort="><?php echo Utils::t('Server name'); ?></a></th>
				<th><a href="?sort="><?php echo Utils::t('Address'); ?></a></th>
				<th><?php echo Utils::t('Port'); ?></th>
				<th><?php echo Utils::t('MatchSettings'); ?></th>
				<th><?php echo Utils::t('SuperAdmin level'); ?></th>
				<th><?php echo Utils::t('Admin level'); ?></th>
				<th><?php echo Utils::t('User level'); ?></th>
				<th class="thright"><?php echo Utils::t('Manage'); ?></th>
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
						$matchSettings = Utils::t('None');
					}
					
					// Niveaux admins
					$adminLevels = array('SuperAdmin', 'Admin', 'User');
					$adminLevelsStatus = array();
					foreach($adminLevels as $level){
						if( array_key_exists($level, $serverData['adminlevel']) ){
							if( is_array($serverData['adminlevel'][$level]) ){
								$adminLevelsStatus[] = Utils::t('IP address');
							}
							else if($serverData['adminlevel'][$level] === 'local'){
								$adminLevelsStatus[] = Utils::t('Local network');
							}
							else if($serverData['adminlevel'][$level] === 'all'){
								$adminLevelsStatus[] = Utils::t('All');
							}
							else if($serverData['adminlevel'][$level] === 'none'){
								$adminLevelsStatus[] = Utils::t('Removed');
							}
							else{
								$adminLevelsStatus[] = Utils::t('Missing');
							}
						}
						else{
							$adminLevelsStatus[] = Utils::t('Missing');
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
				$showServerList .= '<tr class="no-line"><td class="center" colspan="8">'.Utils::t('No server').'</td></tr>';
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
							echo $countServerList.' '.Utils::t('servers');
						}
						else{
							echo $countServerList.' '.Utils::t('server');
						}
					}
				?>
			</span>
		</div>
		<div class="fright">
			<div class="selected-files-label locked">
				<span class="selected-files-title"><?php echo Utils::t('For the selection'); ?></span>
				<div class="selected-files-option">
					<input class="button dark" type="submit" name="deleteserver" id="deleteserver" value="<?php echo Utils::t('Delete'); ?>" />
					<input class="button dark" type="submit" name="duplicateserver" id="duplicateserver" value="<?php echo Utils::t('Duplicate'); ?>" />
					<input class="button dark" type="submit" name="editserver" id="editserver" value="<?php echo Utils::('Modify'); ?>" />
				</div>
			</div>
		</div>
	</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>