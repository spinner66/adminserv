<?php
	// ENREGISTREMENT
	if( isset($_POST['savesrvopts']) ){
		$ClientInputsMaxLatency = $_POST['ClientInputsMaxLatency'];
		if($ClientInputsMaxLatency == 'more'){
			$ClientInputsMaxLatency = $_POST['ClientInputsMaxLatencyValue'];
		}
		$ChangeAuthPassword = null;
		if( isset($_POST['ChangeAuthPassword']) && $_POST['ChangeAuthPassword'] != null ){
			$ChangeAuthLevel = $_POST['ChangeAuthLevel'];
			$ChangeAuthPassword = trim($_POST['ChangeAuthPassword']);
		}
		$struct = AdminServ::getServerOptionsStructFromPOST();
		
		// Requêtes
		if( !$client->query('SetServerOptions', $struct) ){
			AdminServ::error();
		}
		else{
			if(SERVER_VERSION_NAME == 'ManiaPlanet'){
				$client->addCall('SetClientInputsMaxLatency', array(intval($ClientInputsMaxLatency)) );
				$client->addCall('DisableHorns', array(array_key_exists('DisableHorns', $_POST)) );
			}
			$client->addCall('SetHideServer', array( (int)array_key_exists('HideServer', $_POST)) );
			$client->addCall('SetBuddyNotification', array('', array_key_exists('BuddyNotification', $_POST)) );
			if($ChangeAuthPassword){
				$client->addCall('ChangeAuthPassword', array($ChangeAuthLevel, $ChangeAuthPassword) );
			}
			if( !$client->multiquery() ){
				AdminServ::error();
			}
			else{
				if($ChangeAuthPassword){
					if(USER_ADMINLEVEL === $ChangeAuthLevel){
						$_SESSION['adminserv']['password'] = $ChangeAuthPassword;
					}
					AdminServ::info( Utils::t('You changed the password "!authLevel", remember it at the next connection!', array('!authLevel' => $ChangeAuthLevel)) );
					AdminServLogs::add('action', 'Change authentication password for '.$ChangeAuthLevel.' level');
				}
				else{
					AdminServLogs::add('action', 'Save server options');
				}
				Utils::redirection(false, '?p='.USER_PAGE);
			}
		}
	}
	
	
	// LECTURE
	$srvOpt = AdminServ::getServerOptions();
	$adminLevels = AdminServ::getServerAdminLevel();
	
	
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
						<td class="preview">[<span id="serverNameHtml"><?php echo $srvOpt['NameHtml']; ?></span>]</td>
					</tr>
					<tr class="serverComment">
						<td class="key"><label for="ServerComment"><?php echo Utils::t('Comment'); ?></label></td>
						<td class="value" colspan="3">
							<textarea class="width3" name="ServerComment" id="ServerComment" maxlength="255"><?php echo $srvOpt['Comment']; ?></textarea>
						</td>
						<td class="preview">[<span id="serverCommentHtml"><?php echo $srvOpt['CommentHtml']; ?></span>]</td>
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
							<input class="text width1" type="number" min="0" name="NextMaxPlayers" id="NextMaxPlayers" value="<?php echo $srvOpt['NextMaxPlayers']; ?>" />
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
							<input class="text width1" type="number" min="0" name="NextMaxSpectators" id="NextMaxSpectators" value="<?php echo $srvOpt['NextMaxSpectators']; ?>" />
						</td>
						<td class="preview"></td>
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
							<input class="text width1" type="number" min="0" name="NextCallVoteTimeOut" id="NextCallVoteTimeOut" value="<?php echo TimeDate::millisecToSec($srvOpt['NextCallVoteTimeOut']); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="CallVoteRatio"><?php echo Utils::t('Vote ratio'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="number" min="0" max="1" step=".1" name="CallVoteRatio" id="CallVoteRatio" value="<?php echo $srvOpt['CallVoteRatio']; ?>" />
						</td>
					</tr>
					<?php if(SERVER_VERSION_NAME == 'ManiaPlanet'){ ?>
						<tr>
							<td class="key"><label for="ClientInputsMaxLatency"><?php echo Utils::t('Client inputs max latency'); ?></label></td>
							<td class="value" colspan="4">
								<select name="ClientInputsMaxLatency" id="ClientInputsMaxLatency"<?php if($srvOpt['ClientInputsMaxLatency'] > 0){ echo ' hidden="hidden"'; } ?>>
									<option value="0"><?php echo Utils::t('Automatic'); ?></option>
									<option value="more"><?php echo Utils::t('Choose number'); ?></option>
								</select>
								<input class="text" type="number" min="0" name="ClientInputsMaxLatencyValue" id="ClientInputsMaxLatencyValue" value="<?php echo $srvOpt['ClientInputsMaxLatency']; ?>"<?php if($srvOpt['ClientInputsMaxLatency'] == 0){ echo ' hidden="hidden"'; } ?> />
								<a class="returnDefaultValue" href="?p=<?php echo USER_PAGE; ?>"<?php if($srvOpt['ClientInputsMaxLatency'] == 0){ echo ' hidden="hidden"'; } ?>><?php echo Utils::t('Return to the default value'); ?></a>
							</td>
						</tr>
					<?php } ?>
					<tr>
						<td class="key"><label for="HideServer"><?php echo Utils::t('Hidden server'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="HideServer" id="HideServer"<?php if($srvOpt['HideServer'] != 0){ echo ' checked="checked"'; } ?> value="" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="AllowMapDownload"><?php echo Utils::t('Map download'); ?></label></td>
						<td class="value" colspan="4">
							<input class="text" type="checkbox" name="AllowMapDownload" id="AllowMapDownload"<?php if(SERVER_VERSION_NAME == 'TmForever' && $srvOpt['AllowChallengeDownload'] != 0){ echo ' checked="checked"'; }else if(SERVER_VERSION_NAME == 'ManiaPlanet' && $srvOpt['AllowMapDownload'] != 0){ echo ' checked="checked"'; } ?> value="" />
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
			
			<?php if( AdminServ::isAdminLevel('SuperAdmin') ){ ?>
				<fieldset class="srvopts_changeauthpassword">
					<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/players.png" alt="" /><?php echo Utils::t('Change authentication password'); ?></legend>
					<table>
						<tr>
							<td class="key"><label for="ChangeAuthLevel"><?php echo Utils::t('Admin level'); ?></label></td>
							<td class="value col2">
								<select name="ChangeAuthLevel" id="ChangeAuthLevel">
									<?php
										if( isset($adminLevels['levels']) && count($adminLevels['levels']) > 0 ){
											foreach($adminLevels['levels'] as $levelId => $levelName){
												echo '<option value="'.$levelName.'">'.$levelName.'</option>';
											}
										}
									?>
								</select>
								<span class="changeauthpassword-arrow"> </span>
								<input class="text" type="password" name="ChangeAuthPassword" id="ChangeAuthPassword" value="" />
							</td>
						</tr>
					</table>
				</fieldset>
			<?php } ?>
		</div>
		<div class="fright save">
			<input class="button light" type="submit" name="savesrvopts" id="savesrvopts" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>