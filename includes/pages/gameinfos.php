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
	$gameInfos = AdminServ::getGameInfos();
	$currGamInf = $gameInfos['curr'];
	$nextGamInf = $gameInfos['next'];
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Informations de jeu</h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content gameinfos">
			<?php
				// Général
				echo AdminServUI::getGameInfosGeneralForm($currGamInf, $nextGamInf);
				// Modes de jeux
				echo AdminServUI::getGameInfosGameModeForm($currGamInf, $nextGamInf);
			?>
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