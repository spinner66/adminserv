<?php
	// ENREGISTREMENT
	if( isset($_POST['savegameinfos']) ){
		// Variables
		$struct = AdminServ::getGameInfosStructFromPOST();
		
		// Requêtes
		if( !$client->query('SetGameInfos', $struct) ){
			AdminServ::error();
		}
		else{
			// Team info
			if( isset($_POST['teamInfo1Name']) ){
				$team1Name = $_POST['teamInfo1Name'];
				$team1Color = $_POST['teamInfo1Color'];
				$team1Country = $_POST['teamInfo1Country'];
				$team2Name = $_POST['teamInfo2Name'];
				$team2Color = $_POST['teamInfo2Color'];
				$team2Country = $_POST['teamInfo2Country'];
				
				
				if( !$client->query('SetTeamInfo', 'Unused', (double)0.0, 'World', $team1Name, (double)0.0, $team1Country, $team2Name, (double)0.66, $team2Country) ){
					AdminServ::error();
				}
			}
			
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
			
			AdminServLogs::add('action', 'Save game infos');
			//Utils::redirection(false, '?p='.USER_PAGE);
		}
	}
	
	
	// LECTURE
	$gameInfos = AdminServ::getGameInfos();
	$gameInfosData = array($gameInfos['curr'], $gameInfos['next']);
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php echo Utils::t('Game information'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content gameinfos">
			<?php echo AdminServUI::getGameInfosGeneralForm($gameInfosData); ?>
			
			<?php if( AdminServ::isGameMode('Team', $gameInfos['next']['GameMode']) || AdminServ::isGameMode('Script', $gameInfos['next']['GameMode']) ){ ?>
				<fieldset class="gameinfos_teaminfos">
					<legend><img src="<?php echo AdminServConfig::PATH_RESSOURCES; ?>images/16/players.png" alt="" /><?php echo Utils::t('Team infos'); ?></legend>
					<table>
						<tr>
							<td class="key"><label for="teamInfo1Name"><?php echo Utils::t('Team 1'); ?></label></td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo1Name" id="teamInfo1Name" value="<?php echo Utils::t('Blue'); ?>" />
								<div class="colorSelectorWrapper">
									<div id="colorPickerTeam1" class="colorSelector" title="<?php echo Utils::t('Color'); ?>"></div>
									<input type="hidden" name="teamInfo1Color" id="teamInfo1Color" value="0000ff" />
								</div>
							</td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo1Country" id="teamInfo1Country" value="World|France" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="teamInfo2Name"><?php echo Utils::t('Team 2'); ?></label></td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo2Name" id="teamInfo2Name" value="<?php echo Utils::t('Red'); ?>" />
								<div class="colorSelectorWrapper">
									<div id="colorPickerTeam2" class="colorSelector" title="<?php echo Utils::t('Color'); ?>"></div>
									<input type="hidden" name="teamInfo2Color" id="teamInfo2Color" value="ff0000" />
								</div>
							</td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo2Country" id="teamInfo2Country" value="World|France" />
							</td>
							<td class="preview"></td>
						</tr>
					</table>
				</fieldset>
			<?php } ?>
			
			<?php echo AdminServUI::getGameInfosGameModeForm($gameInfosData); ?>
		</div>
		<?php if(SERVER_MATCHSET){ ?>
			<div class="fleft options-checkbox">
				<input class="text inline" type="checkbox" name="SaveCurrentMatchSettings" id="SaveCurrentMatchSettings"<?php if(AdminServConfig::AUTOSAVE_MATCHSETTINGS === true){ echo ' checked="checked"'; } ?> value="" /><label for="SaveCurrentMatchSettings" title="<?php echo SERVER_MATCHSET; ?>"><?php echo Utils::t('Save the current MatchSettings'); ?></label>
			</div>
		<?php } ?>
		<div class="fright save">
			<input class="button light" type="submit" name="savegameinfos" id="savegameinfos" value="<?php echo Utils::t('Save'); ?>" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>