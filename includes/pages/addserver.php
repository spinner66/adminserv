<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error( Utils::t('You are not allowed to configure the servers') );
		Utils::redirection();
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si on n'autorise pas la configuration en ligne
		if( OnlineConfig::ACTIVATE !== true ){
			AdminServ::info( Utils::t('No server available. To add one, configure "config/servers.cfg.php" file.') );
			Utils::redirection();
		}
	}
	else{
		AdminServ::error( Utils::t('The servers configuration file isn\'t recognized by AdminServ.') );
		Utils::redirection();
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['saveserver']) ){
		// Variables
		$serverName = Str::replaceSpecialChars( htmlspecialchars(addslashes($_POST['addServerName'])), false);
		$serverAddress = trim($_POST['addServerAddress']);
		$serverPort = intval($_POST['addServerPort']);
		$serverMatchSet = trim($_POST['addServerMatchSet']);
		$serverAdmLvl_SA = $_POST['addServerAdmLvlSA'];
		$serverAdmLvl_ADM = $_POST['addServerAdmLvlADM'];
		$serverAdmLvl_USR = $_POST['addServerAdmLvlUSR'];
		$isNotAnArray = array('all', 'local', 'none');
		if( !in_array($serverAdmLvl_SA, $isNotAnArray) ){ $serverAdmLvl_SA = explode(',', $serverAdmLvl_SA); }else{ $serverAdmLvl_SA = trim($serverAdmLvl_SA); }
		if( !in_array($serverAdmLvl_ADM, $isNotAnArray) ){ $serverAdmLvl_ADM = explode(',', $serverAdmLvl_ADM); }else{ $serverAdmLvl_ADM = trim($serverAdmLvl_ADM); }
		if( !in_array($serverAdmLvl_USR, $isNotAnArray) ){ $serverAdmLvl_USR = explode(',', $serverAdmLvl_USR); }else{ $serverAdmLvl_USR = trim($serverAdmLvl_USR); }
		$serverData = array(
			'name' => $serverName,
			'address' => $serverAddress,
			'port' => $serverPort,
			'matchsettings' => $serverMatchSet,
			'adminlevel' => array(
				'SuperAdmin' => $serverAdmLvl_SA,
				'Admin' => $serverAdmLvl_ADM,
				'User' => $serverAdmLvl_USR,
			)
		);
		
		// Édition
		if($id !== -1){
			if( ($result = AdminServServerConfig::saveServerConfig($serverData, $id)) !== true ){
				AdminServ::error( Utils::t('Unable to modify the server.').' ('.$result.')');
			}
			else{
				$action = Utils::t('This server has been modified.');
				AdminServ::info($action);
				AdminServLogs::add('action', $action);
				Utils::redirection(false, '?p=servers');
			}
		}
		else{
			// Ajout
			if( ($result = AdminServServerConfig::saveServerConfig($serverData)) !== true ){
				AdminServ::error( Utils::t('Unable to add the server.').' ('.$result.')');
			}
			else{
				$action = Utils::t('This server has been added.');
				AdminServ::info($action);
				AdminServLogs::add('action', $action);
				Utils::redirection(false, '?p='.USER_PAGE);
			}
		}
	}
	
	
	// LECTURE
	$serverName = null;
	$serverAddress = 'localhost';
	$serverPort = 5000;
	$serverMatchSet = 'MatchSettings/';
	$serverAdmLvl_SA = 'all';
	$serverAdmLvl_ADM = 'all';
	$serverAdmLvl_USR = 'all';
	if($id !== -1){
		define('IS_SERVER_EDITION', true);
		$serverName = AdminServServerConfig::getServerName($id);
		if($serverName){
			$serverData = AdminServServerConfig::getServer($serverName);
			$serverAddress = $serverData['address'];
			$serverPort = $serverData['port'];
			$serverMatchSet = $serverData['matchsettings'];
			$serverAdmLvl_SA = $serverData['adminlevel']['SuperAdmin'];
			$serverAdmLvl_ADM = $serverData['adminlevel']['Admin'];
			$serverAdmLvl_USR = $serverData['adminlevel']['User'];
			if( is_array($serverAdmLvl_SA) ){ $serverAdmLvl_SA = implode(', ', $serverAdmLvl_SA); }
			if( is_array($serverAdmLvl_ADM) ){ $serverAdmLvl_ADM = implode(', ', $serverAdmLvl_ADM); }
			if( is_array($serverAdmLvl_USR) ){ $serverAdmLvl_USR = implode(', ', $serverAdmLvl_USR); }
		}
	}
	
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php if( defined('IS_SERVER_EDITION') ){ echo Utils::t('Edit server'); }else{ echo Utils::t('Add server'); } ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; if($id !== -1){ echo '&id='.$id; } ?>">
		<div class="content">
			<fieldset>
				<legend><?php echo Utils::t('Connection information'); ?></legend>
				<table>
					<tr>
						<td class="key"><label for="addServerName"><?php echo Utils::t('Server name'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerName" id="addServerName" value="<?php echo $serverName; ?>" />
						</td>
						<td class="info">
							<?php echo Utils::t('Server name without color'); ?>
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAddress"><?php echo Utils::t('Address'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAddress" id="addServerAddress" value="<?php echo $serverAddress; ?>" />
						</td>
						<td class="info">
							<?php echo Utils::t('IP address or domain name'); ?>
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerPort"><?php echo Utils::t('XMLRPC port'); ?></label></td>
						<td class="value">
							<input class="text width3" type="number" name="addServerPort" id="addServerPort" value="<?php echo $serverPort; ?>" />
						</td>
						<td class="info">
							<?php echo Utils::t('Port for remote control'); ?>
						</td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend><?php echo Utils::t('Optionnal information'); ?></legend>
				<table>
					<tr>
						<td class="key"><label for="addServerMatchSet"><?php echo Utils::t('Server MatchSettings'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerMatchSet" id="addServerMatchSet" value="<?php echo $serverMatchSet; ?>" />
						</td>
						<td class="info">
							<?php echo Utils::t('Current server MatchSettings name'); ?>
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlSA"><?php echo Utils::t('SuperAdmin level'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlSA" id="addServerAdmLvlSA" value="<?php echo $serverAdmLvl_SA; ?>" />
						</td>
						<td rowspan="3" class="info">
							<?php echo Utils::t('Possible values for the admin level:'); ?><br />
							<?php echo Utils::t('all => all access'); ?><br />
							<?php echo Utils::t('local => local network access'); ?><br />
							<?php echo Utils::t('192.168.0.1, 192.168.0.2 => access to one or more IP address'); ?><br />
							<?php echo Utils::t('none => removed from the access list'); ?>
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlADM"><?php echo Utils::t('Admin level'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlADM" id="addServerAdmLvlADM" value="<?php echo $serverAdmLvl_ADM; ?>" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlUSR"><?php echo Utils::t('User level'); ?></label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlUSR" id="addServerAdmLvlUSR" value="<?php echo $serverAdmLvl_USR; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<div class="fright save">
			<input class="button light" type="submit" name="saveserver" id="saveserver" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>