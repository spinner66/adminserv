<?php
	// ENREGISTREMENT
	if( isset($_POST['savesrvopts']) ){
		// Clés
		if(SERVER_VERSION_NAME == 'TmForever'){
			$keys = array(
				'allowMapDownload' => 'AllowChallengeDownload'
			);
		}
		else{
			$keys = array(
				'allowMapDownload' => 'AllowMapDownload'
			);
		}
		
		// Variables
		$IsP2PUpload = array_key_exists('IsP2PUpload', $_POST);
		$IsP2PDownload = array_key_exists('IsP2PDownload', $_POST);
		$NextCallVoteTimeOut = TimeDate::secToMillisec( intval($_POST['NextCallVoteTimeOut']) );
		$HideServer = array_key_exists('HideServer', $_POST);
		$AllowMapDownload = array_key_exists($keys['allowMapDownload'], $_POST);
		$AutoSaveReplays = array_key_exists('AutoSaveReplays', $_POST);
		if($_POST['CallVoteRatio'] == 0){ $CallVoteRatio = 0.0; }
		else if($_POST['CallVoteRatio'] == 1){ $CallVoteRatio = 1.0; }
		else{ $CallVoteRatio = $_POST['CallVoteRatio']; }
		$struct = array(
			'Name' => stripslashes($_POST['ServerName']),
			'Comment' => stripslashes($_POST['ServerComment']),
			'Password' => trim($_POST['ServerPassword']),
			'PasswordForSpectator' => trim($_POST['SpectatorPassword']),
			'NextMaxPlayers' => intval($_POST['NextMaxPlayers']),
			'NextMaxSpectators' => intval($_POST['NextMaxSpectators']),
			'IsP2PUpload' => $IsP2PUpload,
			'IsP2PDownload' => $IsP2PDownload,
			'NextLadderMode' => intval($_POST['NextLadderMode']),
			'NextVehicleNetQuality' => intval($_POST['NextVehicleNetQuality']),
			'NextCallVoteTimeOut' => $NextCallVoteTimeOut,
			'CallVoteRatio' => floatval($CallVoteRatio),
			$keys['allowMapDownload'] => $AllowMapDownload,
			'AutoSaveReplays' => $AutoSaveReplays,
			'HideServer' => $HideServer
		);
		
		// Requêtes
		$client->addCall('SetServerOptions', $struct);
		$client->addCall('SetBuddyNotification', array('', array_key_exists('BuddyNotification', $_POST)) );
		$client->addCall('DisableHorns', array(array_key_exists('DisableHorns', $_POST)) );
		if( !$client->multiquery() ){
			AdminServ::error();
		}
		else{
			AdminServLogs::add('action', 'Save server options');
			Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	
	
	// LECTURE
	if(SERVER_VERSION_NAME == 'TmForever'){
		$client->addCall('GetServerOptions', array(1) );
	}
	else{
		$client->addCall('GetServerOptions');
	}
	$client->addCall('GetBuddyNotification', array('') );
	$client->addCall('GetHideServer');
	if(SERVER_VERSION_NAME == 'ManiaPlanet'){
		$client->addCall('AreHornsDisabled');
	}
	if( !$client->multiquery() ){
		AdminServ::error();
	}
	else{
		$queriesData = $client->getMultiqueryResponse();
		$srvOpt = $queriesData['GetServerOptions'];
		$srvOpt['Name'] = stripslashes($srvOpt['Name']);
		$srvOpt['NameHtml'] = TmNick::toHtml($srvOpt['Name'], 10, false, false, '#666');
		$srvOpt['Comment'] = stripslashes($srvOpt['Comment']);
		$srvOpt['CommentHtml'] = TmNick::toHtml('$i'.nl2br($srvOpt['Comment']), 10, false, false, '#666');
		if($srvOpt['CurrentLadderMode'] !== 0){ $srvOpt['CurrentLadderModeName'] = Utils::t('Forced'); }
		else{ $srvOpt['CurrentLadderModeName'] = Utils::t('Inactive'); }
		if($srvOpt['CurrentVehicleNetQuality'] !== 0){ $srvOpt['CurrentVehicleNetQualityName'] = Utils::t('High'); }
		else{ $srvOpt['CurrentVehicleNetQualityName'] = Utils::t('Fast'); }
		$srvOpt['BuddyNotification'] = $queriesData['GetBuddyNotification'];
		$srvOpt['HideServer'] = $queriesData['GetHideServer'];
		$srvOpt['DisableHorns'] = null;
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			$srvOpt['DisableHorns'] = $queriesData['AreHornsDisabled'];
		}
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Server options'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<fieldset class="srvopts_general">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/servers.png" alt="" /><?php echo Utils::t('General'); ?></legend>
				<table>
					<tr class="serverName">
						<td class="key"><label for="ServerName"><?php echo Utils::t('Server name'); ?></label></td>
						<td class="value" colspan="3">
							<input class="text width3" type="text" name="ServerName" id="ServerName" maxlength="75" value="<?php echo $srvOpt['Name']; ?>" />
						</td>
						<td class="preview" id="serverNameHtml">[<?php echo $srvOpt['NameHtml']; ?>]</td>
					</tr>
					<tr class="serverComment">
						<td class="key"><label for="ServerComment"><?php echo Utils::t('Comment'); ?></label></td>
						<td class="value" colspan="3">
							<textarea class="width3" name="ServerComment" id="ServerComment" maxlength="255"><?php echo $srvOpt['Comment']; ?></textarea>
						</td>
						<td class="preview" id="serverCommentHtml">[<?php echo $srvOpt['CommentHtml']; ?>]</td>
					</tr>
					<tr>
						<td class="key"><label for="ServerPassword"><?php echo Utils::t('Player password'); ?></label></td>
						<td class="value" colspan="3">
							<input class="text width3" type="text" name="ServerPassword" id="ServerPassword" value="<?php echo $srvOpt['Password']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="SpectatorPassword"><?php echo Utils::t('Spectator password'); ?></label></td>
						<td class="value" colspan="3">
							<input class="text width3" type="text" name="SpectatorPassword" id="SpectatorPassword" value="<?php echo $srvOpt['PasswordForSpectator']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextMaxPlayers"><?php echo Utils::t('Nb max of players'); ?></label></td>
						<td class="value col2">
							<input class="text width1" type="text" name="CurrentMaxPlayers" id="CurrentMaxPlayers" readonly="readonly" value="<?php echo $srvOpt['CurrentMaxPlayers']; ?>" />
						</td>
						<td class="key col3"><label for="NextMaxPlayers"><?php echo Utils::t('Next value'); ?></label></td>
						<td class="value">
							<input class="text width1" type="text" name="NextMaxPlayers" id="NextMaxPlayers" value="<?php echo $srvOpt['NextMaxPlayers']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextMaxSpectators"><?php echo Utils::t('Nb max of spectators'); ?></label></td>
						<td class="value col2">
							<input class="text width1" type="text" name="CurrentMaxSpectators" id="CurrentMaxSpectators" readonly="readonly" value="<?php echo $srvOpt['CurrentMaxSpectators']; ?>" />
						</td>
						<td class="key col3"><label for="NextMaxSpectators"><?php echo Utils::t('Next value'); ?></label></td>
						<td class="value">
							<input class="text width1" type="text" name="NextMaxSpectators" id="NextMaxSpectators" value="<?php echo $srvOpt['NextMaxSpectators']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend><?php echo Utils::t('Teams'); ?></legend>
				<table>
					<tr>
						<td class="key"><label for=""><?php echo Utils::t('Team 1'); ?></label></td>
						<td class="value">
							<input class="text width1" type="text" name="" id="" maxlength="75" value="" />
						</td>
						<td class="value">
							<select class="width1" name="" id="">
								<option value="">Couleur</option>
							</select>
						</td>
						<td class="value">
							<select class="width1" name="" id="">
								<option value="">Pays</option>
							</select>
						</td>
					</tr>
					<tr>
						<td class="key"><label for=""><?php echo Utils::t('Team 2'); ?></label></td>
						<td class="value">
							<input class="text width1" type="text" name="" id="" maxlength="75" value="" />
						</td>
						<td class="value">
							<select class="width1" name="" id="">
								<option value="">Couleur</option>
							</select>
						</td>
						<td class="value">
							<select class="width1" name="" id="">
								<option value="">Pays</option>
							</select>
						</td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset class="srvopts_advanced">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/options.png" alt="" /><?php echo Utils::t('Advanced'); ?></legend>
				<table>
					<tr>
						<td class="key"><label for="IsP2PUpload"><?php echo Utils::t('P2P Upload'); ?></label></td>
						<td class="value col2">
							<input class="text" type="checkbox" name="IsP2PUpload" id="IsP2PUpload"<?php if($srvOpt['IsP2PUpload'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="key col3"><label for="IsP2PDownload"><?php echo Utils::t('P2P Download'); ?></label></td>
						<td class="value">
							<input class="text" type="checkbox" name="IsP2PDownload" id="IsP2PDownload"<?php if($srvOpt['IsP2PDownload'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextLadderMode"><?php echo Utils::t('Ladder mode'); ?></label></td>
						<td class="value col2">
							<input class="text width1" type="text" name="CurrentLadderMode" id="CurrentLadderMode" readonly="readonly" value="<?php echo $srvOpt['CurrentLadderModeName']; ?>" />
						</td>
						<td class="key col3"><label for="NextLadderMode"><?php echo Utils::t('Next value'); ?></label></td>
						<td class="value">
							<select class="width1" name="NextLadderMode" id="NextLadderMode">
								<?php
									echo '<option value="0"'; if($srvOpt['NextLadderMode'] == 0){ echo ' selected="selected"'; } echo '>'.Utils::t('Inactive').'</option>';
									echo '<option value="1"'; if($srvOpt['NextLadderMode'] == 1){ echo ' selected="selected"'; } echo '>'.Utils::t('Forced').'</option>';
								?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextVehicleNetQuality"><?php echo Utils::t('Vehicles quality'); ?></label></td>
						<td class="value col2">
							<input class="text width1" type="text" name="CurrentVehicleNetQuality" id="CurrentVehicleNetQuality" readonly="readonly" value="<?php echo $srvOpt['CurrentVehicleNetQualityName']; ?>" />
						</td>
						<td class="key col3"><label for="NextVehicleNetQuality"><?php echo Utils::t('Next value'); ?></label></td>
						<td class="value">
							<select class="width1" name="NextVehicleNetQuality" id="NextVehicleNetQuality">
								<?php
									echo '<option value="0"'; if($srvOpt['NextVehicleNetQuality'] == 0){ echo ' selected="selected"'; } echo '>'.Utils::t('Fast').'</option>';
									echo '<option value="1"'; if($srvOpt['NextVehicleNetQuality'] == 1){ echo ' selected="selected"'; } echo '>'.Utils::t('High').'</option>';
								?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextCallVoteTimeOut"><?php echo Utils::t('Vote expiration'); ?> <span>(<?php echo Utils::t('sec'); ?>)</span></label></td>
						<td class="value col2">
							<input class="text width1" type="text" name="CurrentCallVoteTimeOut" id="CurrentCallVoteTimeOut" readonly="readonly" value="<?php echo TimeDate::millisecToSec($srvOpt['CurrentCallVoteTimeOut']); ?>" />
						</td>
						<td class="key col3"><label for="NextCallVoteTimeOut"><?php echo Utils::t('Next value'); ?></label></td>
						<td class="value">
							<input class="text width1" type="text" name="NextCallVoteTimeOut" id="NextCallVoteTimeOut" value="<?php echo TimeDate::millisecToSec($srvOpt['NextCallVoteTimeOut']); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="CallVoteRatio"><?php echo Utils::t('Vote ratio'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text width1" type="text" name="CallVoteRatio" id="CallVoteRatio" value="<?php echo $srvOpt['CallVoteRatio']; ?>" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="HideServer"><?php echo Utils::t('Hidden server'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="HideServer" id="HideServer"<?php if($srvOpt['HideServer'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="AllowMapDownload"><?php echo Utils::t('Map download'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="AllowMapDownload" id="AllowMapDownload"<?php if($srvOpt['AllowChallengeDownload'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
					</tr>
					<tr<?php if( AdminServ::isAdminLevel('Admin') && !AdminServ::isAdminLevel('SuperAdmin') ){ echo ' hidden="hidden"'; } ?>>
						<td class="key"><label for="AutoSaveReplays"><?php echo Utils::t('Replays auto save'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="AutoSaveReplays" id="AutoSaveReplays"<?php if($srvOpt['AutoSaveReplays'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="BuddyNotification"><?php echo Utils::t('Buddy notification'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="BuddyNotification" id="BuddyNotification"<?php if($srvOpt['BuddyNotification'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
					</tr>
					<?php if(SERVER_VERSION_NAME == 'ManiaPlanet'){ ?>
						<tr>
							<td class="key"><label for="DisableHorns"><?php echo Utils::t('Disable horns'); ?></label></td>
							<td class="value" colspan="4">
								<input class="text" type="checkbox" name="DisableHorns" id="DisableHorns"<?php if($srvOpt['DisableHorns'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
						</tr>
					<?php } ?>
				</table>
			</fieldset>
		</div>
		<div class="fright save">
			<input class="button light" type="submit" name="savesrvopts" id="savesrvopts" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>