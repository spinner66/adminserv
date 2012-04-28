<?php
	// LECTURE
	$gameInfos = AdminServ::getGameInfos();
	$currGamInf = null;
	$nextGamInf = $gameInfos['next'];
	
	
	
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right creatematchset">
		<h1>Créer un MatchSettings</h1>
		
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
						<td class="key"><label for="">Mode de jeu</label></td>
						<td class="value">
							<select class="width2" name="" id="">
								<?php echo AdminServUI::getGameModeList(); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Limite de temps</label></td>
						<td class="value">
							<input class="text width2" type="text" name="" id="" value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Nombre de tour</label></td>
						<td class="value">
							<input class="text width2" type="text" name="" id="" value="" />
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
						<td class="key"><label for="">Lan</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="" id=""<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Internet</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="" id=""<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Solo</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="" id=""<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">HotSeat</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="" id=""<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Index de tri</label></td>
						<td class="value">
							<input class="text width2" type="text" name="" id="" value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Ordre des maps aléatoire</label></td>
						<td class="value">
							<input class="text" type="checkbox" name="" id=""<?php if($nextGamInf['RoundsUseNewRules'] != null){ echo ' checked="checked"'; } ?> value="" />
						</td>
						<td class="preview"></td>
					</tr>
					<tr>
						<td class="key"><label for="">Mode de jeu par défaut</label></td>
						<td class="value">
							<select class="width2" name="" id="">
								<?php echo AdminServUI::getGameModeList(); ?>
							</select>
						</td>
						<td class="preview"></td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<h2>Maps</h2>
		<div class="content">
			
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>