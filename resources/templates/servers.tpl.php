<section class="cadre">
	<h1><?php echo Utils::t('Servers list'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
	<table id="serverList">
		<thead>
			<tr>
				<th class="thleft"><?php echo Utils::t('Server name'); ?></th>
				<th><?php echo Utils::t('Address'); ?></th>
				<th><?php echo Utils::t('Port'); ?></th>
				<th><?php echo Utils::t('MatchSettings'); ?></th>
				<th><?php echo Utils::t('SuperAdmin level'); ?></th>
				<th><?php echo ucwords( Utils::t('Admin level') ); ?></th>
				<th><?php echo Utils::t('User level'); ?></th>
				<th class="thright"><?php echo Utils::t('Manage'); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="table-separation"><td colspan="8"></td></tr>
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
							.'<td class="imgleft"><img src="'. AdminServConfig::$PATH_RESOURCES .'images/16/servers.png" alt="" />'.$serverName.'</td>'
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
					<input class="button dark" type="submit" name="editserver" id="editserver" value="<?php echo Utils::t('Modify'); ?>" />
				</div>
			</div>
		</div>
	</div>
	</form>
</section>