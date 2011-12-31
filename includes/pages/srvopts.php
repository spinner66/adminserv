<?php
	// ENREGISTREMENT
	if( isset($_POST['savesrvopts']) ){
		// Variables
		$IsP2PUpload = array_key_exists('IsP2PUpload', $_POST);
		$IsP2PDownload = array_key_exists('IsP2PDownload', $_POST);
		$NextCallVoteTimeOut = TimeDate::secToMillisec( intval($_POST['NextCallVoteTimeOut']) );
		$HideServer = array_key_exists('HideServer', $_POST);
		$AllowMapDownload = array_key_exists('AllowMapDownload', $_POST);
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
			'AllowMapDownload' => $AllowMapDownload,
			'AutoSaveReplays' => $AutoSaveReplays,
			'HideServer' => $HideServer
		);
		
		// Requête
		if( !$client->query('SetServerOptions', $struct) ){
			echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
	}
	
	
	// LECTURE
	if( !$client->query('GetServerOptions') ){
		echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
	}
	else{
		$srvOpt = $client->getResponse();
		$srvOpt['Name'] = stripslashes($srvOpt['Name']);
		$srvOpt['NameHtml'] = TmNick::toHtml($srvOpt['Name'], 10, false, false, '#666');
		$srvOpt['Comment'] = stripslashes($srvOpt['Comment']);
		$srvOpt['CommentHtml'] = TmNick::toHtml('$i'.nl2br($srvOpt['Comment']), 10, false, false, '#666');
		if($srvOpt['CurrentLadderMode'] !== 0){ $srvOpt['CurrentLadderModeName'] = 'Forcé'; }
		else{ $srvOpt['CurrentLadderModeName'] = 'Inactif'; }
		if($srvOpt['CurrentVehicleNetQuality'] !== 0){ $srvOpt['CurrentVehicleNetQualityName'] = 'Haute'; }
		else{ $srvOpt['CurrentVehicleNetQualityName'] = 'Rapide'; }
		if( !$client->query('GetBuddyNotification', '') ){
			echo '['.$client->getErrorCode().'] '.$client->getErrorMessage();
		}
		else{
			$srvOpt['BuddyNotification'] = $client->getResponse();
		}
	}
	
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section>
	<div class="cadre">
		<h1>Options du serveur</h1>
		<form method="post" action="?p=srvopts">
			<div class="content">
				<fieldset class="srvopts_general">
					<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/servers.png" alt="" />Général</legend>
					<table>
						<tr class="serverName">
							<td class="key"><label for="ServerName">Nom du serveur</label></td>
							<td class="value" colspan="3">
								<input class="text width3" type="text" name="ServerName" id="ServerName" maxlength="75" value="<?php echo $srvOpt['Name']; ?>" />
							</td>
							<td class="preview" id="serverNameHtml">[<?php echo $srvOpt['NameHtml']; ?>]</td>
						</tr>
						<tr class="serverComment">
							<td class="key"><label for="ServerComment">Commentaire</label></td>
							<td class="value" colspan="3">
								<textarea class="width3" name="ServerComment" id="ServerComment" maxlength="255"><?php echo $srvOpt['Comment']; ?></textarea>
							</td>
							<td class="preview" id="serverCommentHtml">[<?php echo $srvOpt['CommentHtml']; ?>]</td>
						</tr>
						<tr>
							<td class="key"><label for="ServerPassword">Mot de passe joueur</label></td>
							<td class="value" colspan="3">
								<input class="text width3" type="text" name="ServerPassword" id="ServerPassword" value="<?php echo $srvOpt['Password']; ?>" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="SpectatorPassword">Mot de passe spectateur</label></td>
							<td class="value" colspan="3">
								<input class="text width3" type="text" name="SpectatorPassword" id="SpectatorPassword" value="<?php echo $srvOpt['PasswordForSpectator']; ?>" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="NextMaxPlayers">Nb max de joueurs</label></td>
							<td class="value col2">
								<input class="text width1" type="text" name="CurrentMaxPlayers" id="CurrentMaxPlayers" readonly="readonly" value="<?php echo $srvOpt['CurrentMaxPlayers']; ?>" />
							</td>
							<td class="key col3"><label for="NextMaxPlayers">Prochaine valeur</label></td>
							<td class="value">
								<input class="text width1" type="text" name="NextMaxPlayers" id="NextMaxPlayers" value="<?php echo $srvOpt['NextMaxPlayers']; ?>" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="NextMaxSpectators">Nb max de spectateurs</label></td>
							<td class="value col2">
								<input class="text width1" type="text" name="CurrentMaxSpectators" id="CurrentMaxSpectators" readonly="readonly" value="<?php echo $srvOpt['CurrentMaxSpectators']; ?>" />
							</td>
							<td class="key col3"><label for="NextMaxSpectators">Prochaine valeur</label></td>
							<td class="value">
								<input class="text width1" type="text" name="NextMaxSpectators" id="NextMaxSpectators" value="<?php echo $srvOpt['NextMaxSpectators']; ?>" />
							</td>
							<td class="preview"></td>
						</tr>
					</table>
				</fieldset>
				
				<fieldset class="srvopts_advanced">
					<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/options.png" alt="" />Avancé</legend>
					<table>
						<tr>
							<td class="key"><label for="IsP2PUpload">P2P Upload</label></td>
							<td class="value col2">
								<input class="text" type="checkbox" name="IsP2PUpload" id="IsP2PUpload"<?php if($srvOpt['IsP2PUpload'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
							<td class="key col3"><label for="IsP2PDownload">P2P Download</label></td>
							<td class="value">
								<input class="text" type="checkbox" name="IsP2PDownload" id="IsP2PDownload"<?php if($srvOpt['IsP2PDownload'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="NextLadderMode">Mode ladder</label></td>
							<td class="value col2">
								<input class="text width1" type="text" name="CurrentLadderMode" id="CurrentLadderMode" readonly="readonly" value="<?php echo $srvOpt['CurrentLadderModeName']; ?>" />
							</td>
							<td class="key col3"><label for="NextLadderMode">Prochaine valeur</label></td>
							<td class="value">
								<select class="width1" name="NextLadderMode" id="NextLadderMode">
									<?php
										echo '<option value="0"'; if($srvOpt['NextLadderMode'] == 0){ echo ' selected="selected"'; } echo '>Inactif</option>';
										echo '<option value="1"'; if($srvOpt['NextLadderMode'] == 1){ echo ' selected="selected"'; } echo '>Forcé</option>';
									?>
								</select>
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="NextVehicleNetQuality">Qualité des véhicules</td>
							<td class="value col2">
								<input class="text width1" type="text" name="CurrentVehicleNetQuality" id="CurrentVehicleNetQuality" readonly="readonly" value="<?php echo $srvOpt['CurrentVehicleNetQualityName']; ?>" />
							</td>
							<td class="key col3"><label for="NextVehicleNetQuality">Prochaine valeur</td>
							<td class="value">
								<select class="width1" name="NextVehicleNetQuality" id="NextVehicleNetQuality">
									<?php
										echo '<option value="0"'; if($srvOpt['NextVehicleNetQuality'] == 0){ echo ' selected="selected"'; } echo '>Rapide</option>';
										echo '<option value="1"'; if($srvOpt['NextVehicleNetQuality'] == 1){ echo ' selected="selected"'; } echo '>Haute</option>';
									?>
								</select>
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="NextCallVoteTimeOut">Expiration du vote <span>(sec)</span></label></td>
							<td class="value col2">
								<input class="text width1" type="text" name="CurrentCallVoteTimeOut" id="CurrentCallVoteTimeOut" readonly="readonly" value="<?php echo TimeDate::millisecToSec($srvOpt['CurrentCallVoteTimeOut']); ?>" />
							</td>
							<td class="key col3"><label for="NextCallVoteTimeOut">Prochaine valeur</label></td>
							<td class="value">
								<input class="text width1" type="text" name="NextCallVoteTimeOut" id="NextCallVoteTimeOut" value="<?php echo TimeDate::millisecToSec($srvOpt['NextCallVoteTimeOut']); ?>" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="CallVoteRatio">Ratio vote</label></td>
							<td class="value" colspan="4">
								<input class="text width1" type="text" name="CallVoteRatio" id="CallVoteRatio" value="<?php echo $srvOpt['CallVoteRatio']; ?>" />
							</td>
						</tr>
						<tr>
							<td class="key"><label for="HideServer">Serveur caché</label></td>
							<td class="value" colspan="4">
								<input class="text" type="checkbox" name="HideServer" id="HideServer"<?php if($srvOpt['HideServer'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
						</tr>
						<tr>
							<td class="key"><label for="AllowMapDownload">Téléchargement des maps</label></td>
							<td class="value" colspan="4">
								<input class="text" type="checkbox" name="AllowMapDownload" id="AllowMapDownload"<?php if($srvOpt['AllowChallengeDownload'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
						</tr>
						<tr>
							<td class="key"><label for="AutoSaveReplays">Sauvegarde auto des replays</label></td>
							<td class="value" colspan="4">
								<input class="text" type="checkbox" name="AutoSaveReplays" id="AutoSaveReplays"<?php if($srvOpt['AutoSaveReplays'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
						</tr>
						<tr>
							<td class="key"><label for="BuddyNotification">Notifications des amis</label></td>
							<td class="value" colspan="4">
								<input class="text" type="checkbox" name="BuddyNotification" id="BuddyNotification"<?php if($srvOpt['BuddyNotification'] != 0){ echo ' checked="checked"'; } ?> value="" />
							</td>
						</tr>
					</table>
				</fieldset>
			</div>
			<div class="fright save">
				<input class="button light" type="submit" name="savesrvopts" id="savesrvopts" value="Enregistrer" />
			</div>
			<div class="fclear"></div>
		</form>
	</div>
</section>
<?php
	AdminServTemplate::getFooter();
?>