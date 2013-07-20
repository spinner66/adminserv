<section class="cadre">
	<h1><?php echo Utils::t('Game information'); ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content gameinfos">
			<?php echo AdminServUI::getGameInfosGeneralForm($gameInfosData); ?>
			
			<?php if(SERVER_VERSION_NAME == 'ManiaPlanet' && AdminServ::checkDisplayTeamMode($gameInfos['next']['GameMode'], $gameInfos['next']['ScriptName']) ){ ?>
				<fieldset class="gameinfos_teaminfos">
					<legend><img src="<?php echo AdminServConfig::$PATH_RESOURCES; ?>images/16/players.png" alt="" /><?php echo Utils::t('Team infos'); ?></legend>
					<table>
						<tr>
							<td class="key"><label for="teamInfo1Name"><?php echo Utils::t('Team 1'); ?></label></td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo1Name" id="teamInfo1Name" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team1']['name']; }else{ echo Utils::t('Blue'); } ?>" />
								<div class="colorSelectorWrapper">
									<div id="colorPickerTeam1" class="colorSelector" title="<?php echo Utils::t('Color'); ?>"></div>
									<input type="hidden" name="teamInfo1Color" id="teamInfo1Color" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team1']['color']; }else{ echo '0.667'; } ?>" />
									<input type="hidden" name="teamInfo1ColorHex" id="teamInfo1ColorHex" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team1']['colorhex']; }else{ echo '#0000ff'; } ?>" />
								</div>
							</td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo1Country" id="teamInfo1Country" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team1']['country']; }else{ echo 'World|France'; } ?>" />
							</td>
							<td class="preview"></td>
						</tr>
						<tr>
							<td class="key"><label for="teamInfo2Name"><?php echo Utils::t('Team 2'); ?></label></td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo2Name" id="teamInfo2Name" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team2']['name']; }else{ echo Utils::t('Red'); } ?>" />
								<div class="colorSelectorWrapper">
									<div id="colorPickerTeam2" class="colorSelector" title="<?php echo Utils::t('Color'); ?>"></div>
									<input type="hidden" name="teamInfo2Color" id="teamInfo2Color" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team2']['color']; }else{ echo '0'; } ?>" />
									<input type="hidden" name="teamInfo2ColorHex" id="teamInfo2ColorHex" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team2']['colorhex']; }else{ echo '#ff0000'; } ?>" />
								</div>
							</td>
							<td class="value">
								<input class="text width2" type="text" name="teamInfo2Country" id="teamInfo2Country" value="<?php if($hasTeamInfo){ echo $getTeamInfo['team2']['country']; }else{ echo 'World|France'; } ?>" />
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