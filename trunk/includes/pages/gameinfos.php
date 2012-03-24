<?php
	// ENREGISTREMENT
	if( isset($_POST['savegameinfos']) ){
		// Variables
		
		if($_POST['NextFinishTimeoutValue'] < 2){
			if($_POST['NextFinishTimeout'] == 0){ $FinishTimeout = 0; }
			else if($_POST['NextFinishTimeout'] == 1){ $FinishTimeout = 1; }
		}
		else{ $FinishTimeout = TimeDate::secToMillisec( intval($_POST['NextFinishTimeoutValue']) ); }
		if( array_key_exists('NextDisableRespawn', $_POST) === true ){ $DisableRespawn = false; }
		else{ $DisableRespawn = true; }
		if($_POST['NextForceShowAllOpponentsValue'] < 2){
			if($_POST['NextForceShowAllOpponents'] == 0){ $NextForceShowAllOpponents = 0; }
			else if($_POST['NextForceShowAllOpponents'] == 1){ $NextForceShowAllOpponents = 1; }
		}
		else{ $NextForceShowAllOpponents = intval($_POST['NextForceShowAllOpponentsValue']); }
		$struct = array(
			'GameMode' => intval($_POST['NextGameMode']),
			'ChatTime' => TimeDate::secToMillisec( intval($_POST['NextChatTime'] - 8) ),
			'RoundsPointsLimit' => intval($_POST['NextRoundsPointsLimit']),
			'RoundsUseNewRules' => array_key_exists('NextRoundsUseNewRules', $_POST),
			'RoundsForcedLaps' => intval($_POST['NextRoundsForcedLaps']),
			'TimeAttackLimit' => TimeDate::secToMillisec( intval($_POST['NextTimeAttackLimit']) ),
			'TimeAttackSynchStartPeriod' => TimeDate::secToMillisec( intval($_POST['NextTimeAttackSynchStartPeriod']) ),
			'TeamPointsLimit' => intval($_POST['NextTeamPointsLimit']),
			'TeamMaxPoints' => intval($_POST['NextTeamMaxPoints']),
			'TeamUseNewRules' => array_key_exists('NextTeamUseNewRules', $_POST),
			'LapsNbLaps' => intval($_POST['NextLapsNbLaps']),
			'LapsTimeLimit' => TimeDate::secToMillisec( intval($_POST['NextLapsTimeLimit']) ),
			'FinishTimeout' => $FinishTimeout,
			'AllWarmUpDuration' => intval($_POST['NextAllWarmUpDuration']),
			'DisableRespawn' => $DisableRespawn,
			'ForceShowAllOpponents' => $NextForceShowAllOpponents,
			'RoundsPointsLimitNewRules' => intval($_POST['NextRoundsPointsLimit']),
			'TeamPointsLimitNewRules' => $_POST['NextTeamPointsLimit'],
			'CupPointsLimit' => intval($_POST['NextCupPointsLimit']),
			'CupRoundsPerMap' => intval($_POST['NextCupRoundsPerMap']),
			'CupNbWinners' => intval($_POST['NextCupNbWinners']),
			'CupWarmUpDuration' => intval($_POST['NextCupWarmUpDuration'])
		);
		
		// Requêtes
		if( !$client->query('SetGameInfos', $struct) ){
			AdminServ::error();
		}
		else{
			// RoundCustomPoints
			if( isset($_POST['NextRoundCustomPoints']) && $_POST['NextRoundCustomPoints'] != null){
				$NextRoundCustomPoints = explode(',', $_POST['NextRoundCustomPoints']);
				$NextRoundCustomPointsArray = array();
				if( count($NextRoundCustomPoints) > 0 ){
					foreach($NextRoundCustomPoints as $point){
						$NextRoundCustomPointsArray[] = intval( trim($point) );
					}
				}
				if( !$client->query('SetRoundCustomPoints', $NextRoundCustomPointsArray) ){
					AdminServ::error();
				}
			}
			
			// MatchSettings
			if(SERVER_MATCHSET){
				$mapsDirectory = AdminServ::getMapsDirectoryPath();
				if( array_key_exists('SaveCurrentMatchSettings', $_POST) ){
					if( !$client->query('SaveMatchSettings', $mapsDirectory . SERVER_MATCHSET) ){
						AdminServ::error();
					}
				}
			}
		}
	}
	
	
	// LECTURE
	if( !$client->query('GetGameInfos') ){
		AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
	}
	else{
		$gamInf = $client->getResponse();
		$curtGamInf = $gamInf['CurrentGameInfos'];
		$nextGamInf = $gamInf['NextGameInfos'];
		
		// Complétion du tableau gamInf pour ManiaPlanet
		if(SERVER_VERSION_NAME == 'ManiaPlanet'){
			// Nb de WarmUp
			if( !$client->query('GetAllWarmUpDuration') ){
				AdminServ::error();
			}
			else{
				$GetAllWarmUpDuration = $client->getResponse();
				$curtGamInf['AllWarmUpDuration'] = $GetAllWarmUpDuration['CurrentValue'];
				$nextGamInf['AllWarmUpDuration'] = $GetAllWarmUpDuration['NextValue'];
				
				// Respawn
				if( !$client->query('GetDisableRespawn') ){
					AdminServ::error();
				}
				else{
					$DisableRespawn = $client->getResponse();
					$curtGamInf['DisableRespawn'] = $DisableRespawn['CurrentValue'];
					$nextGamInf['DisableRespawn'] = $DisableRespawn['NextValue'];
					
					// ForceShowAllOpponents
					if( !$client->query('GetForceShowAllOpponents') ){
						AdminServ::error();
					}
					else{
						$ForceShowAllOpponents = $client->getResponse();
						$curtGamInf['ForceShowAllOpponents'] = $ForceShowAllOpponents['CurrentValue'];
						$nextGamInf['ForceShowAllOpponents'] = $ForceShowAllOpponents['NextValue'];
						
						// ScriptName
						if( !$client->query('GetScriptName') ){
							AdminServ::error();
						}
						else{
							$ScriptName = $client->getResponse();
							$curtGamInf['ScriptName'] = $ScriptName['CurrentValue'];
							$nextGamInf['ScriptName'] = $ScriptName['NextValue'];
						
							// Mode Cup
							if( !$client->query('GetCupPointsLimit') ){
								AdminServ::error();
							}
							else{
								$CupPointsLimit = $client->getResponse();
								$curtGamInf['CupPointsLimit'] = $CupPointsLimit['CurrentValue'];
								$nextGamInf['CupPointsLimit'] = $CupPointsLimit['NextValue'];
								if( !$client->query('GetCupRoundsPerMap') ){
									AdminServ::error();
								}
								else{
									$CupRoundsPerMap = $client->getResponse();
									$curtGamInf['CupRoundsPerMap'] = $CupRoundsPerMap['CurrentValue'];
									$nextGamInf['CupRoundsPerMap'] = $CupRoundsPerMap['NextValue'];
									if( !$client->query('GetCupNbWinners') ){
										AdminServ::error();
									}
									else{
										$CupNbWinners = $client->getResponse();
										$curtGamInf['CupNbWinners'] = $CupNbWinners['CurrentValue'];
										$nextGamInf['CupNbWinners'] = $CupNbWinners['NextValue'];
										if( !$client->query('GetCupWarmUpDuration') ){
											AdminServ::error();
										}
										else{
											$CupWarmUpDuration = $client->getResponse();
											$curtGamInf['CupWarmUpDuration'] = $CupWarmUpDuration['CurrentValue'];
											$nextGamInf['CupWarmUpDuration'] = $CupWarmUpDuration['NextValue'];
										}
									}
								}
							}
						}
					}
				}
			}
		}
		
		// RoundCustomPoints
		if( !$client->query('GetRoundCustomPoints') ){
			AdminServ::error();
		}
		else{
			$RoundCustomPoints = $client->getResponse();
			$RoundCustomPointsList = null;
			foreach($RoundCustomPoints as $point){
				$RoundCustomPointsList .= $point.',';
			}
			$RoundCustomPointsList = substr($RoundCustomPointsList, 0, -1);
			$curtGamInf['RoundCustomPoints'] = $RoundCustomPointsList;
			$nextGamInf['RoundCustomPoints'] = $RoundCustomPointsList;
		}
	}
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Informations de jeu</h1>
	<form method="post" action="?p=gameinfos">
		<div class="content">
			<fieldset class="gameinfos_general">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/restartrace.png" alt="" />Général</legend>
				<table>
					<tr>
						<td class="key"><label for="NextGameMode">Mode de jeu</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrGameMode" id="CurrGameMode" readonly="readonly" value="<?php echo AdminServ::getGameModeName($curtGamInf['GameMode']); ?>" />
						</td>
						<td class="value">
							<select class="width2" name="NextGameMode" id="NextGameMode">
								<?php echo AdminServUI::getGameModeList($nextGamInf['GameMode']); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextChatTime">Temps de fin de map <span>(sec)</span></label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrChatTime" id="CurrChatTime" readonly="readonly" value="<?php echo TimeDate::millisecToSec($curtGamInf['ChatTime'] + 8000); ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextChatTime" id="NextChatTime" value="<?php echo TimeDate::millisecToSec($nextGamInf['ChatTime'] + 8000); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextFinishTimeout">Temps de fin de round/lap <span>(sec)</span></label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrFinishTimeout" id="CurrFinishTimeout" readonly="readonly" value="<?php if($curtGamInf['FinishTimeout'] == 0){ echo 'Par défaut (15sec)'; }else if($curtGamInf['FinishTimeout'] == 1){ echo 'Auto (en fonction de la map)'; }else{ echo TimeDate::millisecToSec($curtGamInf['FinishTimeout']); } ?>" />
						</td>
						<td class="value next">
							<select class="width2" name="NextFinishTimeout" id="NextFinishTimeout"<?php if($nextGamInf['FinishTimeout'] > 1){ echo ' hidden="hidden"'; } ?>>
								<option value="0"<?php if($nextGamInf['FinishTimeout'] == 0){ echo ' selected="selected"'; } ?>>Par défaut (15sec)</option>
								<option value="1"<?php if($nextGamInf['FinishTimeout'] == 1){ echo ' selected="selected"'; } ?>>Auto (en fonction de la map)</option>
								<option value="more">Choisir le temps</option>
							</select>
							<input class="text width2" type="text" name="NextFinishTimeoutValue" id="NextFinishTimeoutValue" value="<?php if($nextGamInf['FinishTimeout'] > 1){ echo TimeDate::millisecToSec($nextGamInf['FinishTimeout']); } ?>"<?php if($nextGamInf['FinishTimeout'] < 2){ echo ' hidden="hidden"'; } ?> />
						</td>
						<td class="preview"><a class="returnDefaultValue" href="?p=gameinfos"<?php if($nextGamInf['FinishTimeout'] < 2){ echo ' hidden="hidden"'; } ?>>Revenir à la valeur par défaut</a></td>
					</tr>
					<tr>
						<td class="key"><label for="NextAllWarmUpDuration">Nombre de WarmUp</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrAllWarmUpDuration" id="CurrAllWarmUpDuration" readonly="readonly" value="<?php echo $curtGamInf['AllWarmUpDuration']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextAllWarmUpDuration" id="NextAllWarmUpDuration" value="<?php echo $nextGamInf['AllWarmUpDuration']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextDisableRespawn">Respawn</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrDisableRespawn" id="CurrDisableRespawn" readonly="readonly" value="<?php if($curtGamInf['DisableRespawn'] === false){ echo 'Activé'; }else{ echo 'Désactivé'; } ?>" />
						</td>
						<td class="value">
							<input class="text" type="checkbox" name="NextDisableRespawn" id="NextDisableRespawn"<?php if($nextGamInf['DisableRespawn'] === false){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextForceShowAllOpponents">Forcer l'affichage des adversaires</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrForceShowAllOpponents" id="CurrForceShowAllOpponents" readonly="readonly" value="<?php if($curtGamInf['ForceShowAllOpponents'] == 0){ echo 'Laisser le choix au joueur'; }else if($curtGamInf['ForceShowAllOpponents'] == 1){ echo 'Tous les adversaires'; }else{ echo $curtGamInf['ForceShowAllOpponents'].' adversaires minimum'; } ?>" />
						</td>
						<td class="value next">
							<select class="width2" name="NextForceShowAllOpponents" id="NextForceShowAllOpponents"<?php if($nextGamInf['ForceShowAllOpponents'] > 1){ echo ' hidden="hidden"'; } ?>>
								<option value="0"<?php if($nextGamInf['ForceShowAllOpponents'] == 0){ echo ' selected="selected"'; } ?>>Laisser le choix au joueur</option>
								<option value="1"<?php if($nextGamInf['ForceShowAllOpponents'] == 1){ echo ' selected="selected"'; } ?>>Tous les adversaires</option>
								<option value="more">Choisir le nombre d'adversaires</option>
							</select>
							<input class="text width2" type="text" name="NextForceShowAllOpponentsValue" id="NextForceShowAllOpponentsValue" value="<?php if($nextGamInf['ForceShowAllOpponents'] > 1){ echo $nextGamInf['ForceShowAllOpponents']; } ?>"<?php if($nextGamInf['ForceShowAllOpponents'] < 2){ echo ' hidden="hidden"'; } ?> />
						</td>
						<td class="preview"><a class="returnDefaultValue" href="?p=gameinfos"<?php if($nextGamInf['ForceShowAllOpponents'] < 2){ echo ' hidden="hidden"'; } ?>>Revenir à la valeur par défaut</a></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-script" class="gameinfos_script" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/options.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[0]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextScriptName">Nom du script</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrScriptName" id="CurrScriptName" readonly="readonly" value="<?php echo $curtGamInf['ScriptName']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextScriptName" id="NextScriptName" value="<?php echo $nextGamInf['ScriptName']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-rounds" class="gameinfos_round" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/rt_rounds.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[1]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextRoundsUseNewRules">Utiliser les nouvelles règles</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrRoundsUseNewRules" id="CurrRoundsUseNewRules" readonly="readonly" value="<?php if($curtGamInf['RoundsUseNewRules'] != null){ echo 'Activé'; }else{ echo 'Désactivé'; } ?>" />
						</td>
						<td class="value">
							<input class="text" type="checkbox" name="NextRoundsUseNewRules" id="NextRoundsUseNewRules"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextRoundsPointsLimit">Limite de points</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrRoundsPointsLimit" id="CurrRoundsPointsLimit" readonly="readonly" value="<?php echo $curtGamInf['RoundsPointsLimit']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextRoundsPointsLimit" id="NextRoundsPointsLimit" value="<?php echo $nextGamInf['RoundsPointsLimit']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextRoundCustomPoints">Limite de points personnalisée</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrRoundCustomPoints" id="CurrRoundCustomPoints" readonly="readonly" value="<?php echo $curtGamInf['RoundCustomPoints']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextRoundCustomPoints" id="NextRoundCustomPoints" value="<?php echo $nextGamInf['RoundCustomPoints']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextRoundsForcedLaps">ForcedLaps</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrRoundsForcedLaps" id="CurrRoundsForcedLaps" readonly="readonly" value="<?php echo $curtGamInf['RoundsForcedLaps']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextRoundsForcedLaps" id="NextRoundsForcedLaps" value="<?php echo $nextGamInf['RoundsForcedLaps']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-timeattack" class="gameinfos_timeattack" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/rt_timeattack.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[2]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextTimeAttackLimit">Limite de temps <span>(sec)</span></label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrTimeAttackLimit" id="CurrTimeAttackLimit" readonly="readonly" value="<?php echo TimeDate::millisecToSec($curtGamInf['TimeAttackLimit']); ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextTimeAttackLimit" id="NextTimeAttackLimit" value="<?php echo TimeDate::millisecToSec($nextGamInf['TimeAttackLimit']); ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextTimeAttackSynchStartPeriod">Temps de synchronisation du départ</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrTimeAttackSynchStartPeriod" id="CurrTimeAttackSynchStartPeriod" readonly="readonly" value="<?php echo $curtGamInf['TimeAttackSynchStartPeriod']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextTimeAttackSynchStartPeriod" id="NextTimeAttackSynchStartPeriod" value="<?php echo $nextGamInf['TimeAttackSynchStartPeriod']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-team" class="gameinfos_team" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/rt_team.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[3]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextTeamUseNewRules">Utiliser les nouvelles règles</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrTeamUseNewRules" id="CurrTeamUseNewRules" readonly="readonly" value="<?php if($curtGamInf['TeamUseNewRules'] != null){ echo 'Activé'; }else{ echo 'Désactivé'; } ?>" />
						</td>
						<td class="value">
							<input class="text" type="checkbox" name="NextTeamUseNewRules" id="NextTeamUseNewRules"<?php if($nextGamInf['TeamUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextTeamPointsLimit">Limite de points</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrTeamPointsLimit" id="CurrTeamPointsLimit" readonly="readonly" value="<?php echo $curtGamInf['TeamPointsLimit']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextTeamPointsLimit" id="NextTeamPointsLimit" value="<?php echo $nextGamInf['TeamPointsLimit']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextTeamMaxPoints">Points maximum</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrTeamMaxPoints" id="CurrTeamMaxPoints" readonly="readonly" value="<?php echo $curtGamInf['TeamMaxPoints']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextTeamMaxPoints" id="NextTeamMaxPoints" value="<?php echo $nextGamInf['TeamMaxPoints']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-laps" class="gameinfos_laps" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/rt_laps.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[4]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextLapsNbLaps">Nombre de tours</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrLapsNbLaps" id="CurrLapsNbLaps" readonly="readonly" value="<?php echo $curtGamInf['LapsNbLaps']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextLapsNbLaps" id="NextLapsNbLaps" value="<?php echo $nextGamInf['LapsNbLaps']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextLapsTimeLimit">Limite de temps <span>(sec)</span></label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrLapsTimeLimit" id="CurrLapsTimeLimit" readonly="readonly" value="<?php echo $curtGamInf['LapsTimeLimit']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextLapsTimeLimit" id="NextLapsTimeLimit" value="<?php echo $nextGamInf['LapsTimeLimit']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset id="gameMode-cup" class="gameinfos_cup" hidden="hidden">
				<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/rt_cup.png" alt="" /><?php echo ExtensionConfig::$GAMEMODES[6]; ?></legend>
				<table class="game_infos">
					<tr>
						<td class="key"><label for="NextCupPointsLimit">Limite de points</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrCupPointsLimit" id="CurrCupPointsLimit" readonly="readonly" value="<?php echo $curtGamInf['CupPointsLimit']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextCupPointsLimit" id="NextCupPointsLimit" value="<?php echo $nextGamInf['CupPointsLimit']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextCupRoundsPerMap">Nombre de round par map</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrCupRoundsPerMap" id="CurrCupRoundsPerMap" readonly="readonly" value="<?php echo $curtGamInf['CupRoundsPerMap']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextCupRoundsPerMap" id="NextCupRoundsPerMap" value="<?php echo $nextGamInf['CupRoundsPerMap']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextCupNbWinners">Nombre de vainqueur</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrCupNbWinners" id="CurrCupNbWinners" readonly="readonly" value="<?php echo $curtGamInf['CupNbWinners']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextCupNbWinners" id="NextCupNbWinners" value="<?php echo $nextGamInf['CupNbWinners']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="NextCupWarmUpDuration">Nombre de WarmUp</label></td>
						<td class="value">
							<input class="text width2" type="text" name="CurrCupWarmUpDuration" id="CurrCupWarmUpDuration" readonly="readonly" value="<?php echo $curtGamInf['CupWarmUpDuration']; ?>" />
						</td>
						<td class="value">
							<input class="text width2" type="text" name="NextCupWarmUpDuration" id="NextCupWarmUpDuration" value="<?php echo $nextGamInf['CupWarmUpDuration']; ?>" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		<?php if(SERVER_MATCHSET){ ?>
			<div class="fleft options-checkbox">
				<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>">Sauvegarder le MatchSettings courant</label>
			</div>
		<?php } ?>
		<div class="fright save">
			<input class="button light" type="submit" name="savegameinfos" id="savegameinfos" value="Enregistrer" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>