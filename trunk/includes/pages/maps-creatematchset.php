<?php
	// LECTURE
	$gameInfos = AdminServ::getGameInfos();
	$currGamInf = null;
	$nextGamInf = $gameInfos['next'];
	
	
	
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right creatematchset">
		<h1>Créer un MatchSettings</h1>
		
		<h2>Maps</h2>
		<div class="maps">
			
		</div>
		<div class="selection">
			
		</div>
		
		<h2>Informations de jeu</h2>
		<div class="content gameinfos">
			<?php
				// Général
				echo AdminServUI::getGameInfosGeneralForm($currGamInf, $nextGamInf);
				// Modes de jeux
				echo AdminServUI::getGameInfosGameModeForm($currGamInf, $nextGamInf);
			?>
		</div>
		
		<h2>HotSeat</h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="hotSeatGameMode">Mode de jeu</label></td>
						<td class="value">
							<select class="width2" name="hotSeatGameMode" id="hotSeatGameMode">
								<?php echo AdminServUI::getGameModeList(1); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatTimeLimit">Limite de temps</label></td>
						<td class="value">
							<input class="text width2" type="text" name="hotSeatTimeLimit" id="hotSeatTimeLimit" value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="hotSeatCountRound">Nombre de round</label></td>
						<td class="value">
							<input class="text width2" type="text" name="hotSeatCountRound" id="hotSeatCountRound" value="" />
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<h2>Filtres</h2>
		<div class="content">
			<fieldset>
				<table>
					<tr>
						<td class="key"><label for="filterIsLan">Lan</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsLan" id="filterIsLan"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsInternet">Internet</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsInternet" id="filterIsInternet"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsSolo">Solo</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsSolo" id="filterIsSolo"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterIsHotSeat">HotSeat</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterIsHotSeat" id="filterIsHotSeat"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterSortIndex">Index de tri</label></td>
						<td class="value">
							<input class="text width2" type="text" name="filterSortIndex" id="filterSortIndex" value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterRandomMaps">Ordre des maps aléatoire</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="filterRandomMaps" id="filterRandomMaps"<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="filterDefaultGameMode">Mode de jeu par défaut</label></td>
						<td class="value">
							<select class="width2" name="filterDefaultGameMode" id="filterDefaultGameMode">
								<?php echo AdminServUI::getGameModeList(1); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>