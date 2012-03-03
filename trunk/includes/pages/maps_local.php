<?php
	// GAME
		if(SERVER_VERSION_NAME == 'TmForever'){
			$queries = array(
				'insert' => 'InsertChallenge',
				'add' => 'AddChallenge'
			);
		}
		else{
			$queries = array(
				'insert' => 'InsertMap',
				'add' => 'AddMap'
			);
		}
	
	
	// LECTURE
	$mapsDirectoryPath = AdminServ::getMapsDirectoryPath();
	$mapsDirectoryList = AdminServTemplate::getMapsDirectoryList($mapsDirectoryPath, $directory);
	
	
	// ACTIONS
	if( isset($_POST['addMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['add'], $mapsDirectoryPath.$map) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
		}
	}
	else if( isset($_POST['insertMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['insert'], $mapsDirectoryPath.$map) ){
				AdminServ::error( '['.$client->getErrorCode().'] '.$client->getErrorMessage() );
				break;
			}
		}
	}
	else if( isset($_POST['downloadMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		// Si on télécharge plusieurs fichiers, on envoi un zip
		if( count($_POST['map']) > 1){
			$struct = array();
			foreach($_POST['map'] as $map){
				$struct[] = $mapsDirectoryPath.$map;
			}
			$zipError = null;
			$zipFileName = 'maps.zip';
			if( !Zip::create($zipFileName, $struct, $zipError) ){
				AdminServ::error($zipError);
			}
			else{
				File::download($zipFileName);
				if( $result = File::delete($zipFileName) !== true ){
					AdminServ::error($result);
				}
			}
		}
		// Sinon on envoi le fichier seul
		else{
			File::download($mapsDirectoryPath.$_POST['map'][0]);
		}
	}
	else if( isset($_POST['ApplyRenameChallenge']) && $_POST['RenameChallengeFileOLD'] != null && $_POST['RenameChallengeFile'] != null ){
		for($i = 0; $i < count($_POST['RenameChallengeFile']); $i++){
			if( ! @rename($tracksDirectory.$_POST['RenameChallengeFileOLD'][$i], $tracksDirectory.$_POST['RenameChallengeFile'][$i]) ){
				AdminServ::error($lang[$i18n.'_impossible_renommer_fichier'].' <i>'.$_POST['RenameChallengeFileOLD'][$i].'</i>');
				break;
			}
		}
	}
	else if( isset($_POST['ApplyMoveChallenge']) && $_POST['MoveChallengeFileName'] != null && $_POST['MoveChallengeFileDirectory'] != null ){
		$moveChallengeFileNameList = explode(',', $_POST['MoveChallengeFileName']);
		for($i = 0; $i < count($moveChallengeFileNameList); $i++){
			// Si on a sélectionné "Dossier parent"
			if($_POST['MoveChallengeFileDirectory'] == '../'){
				$trackDirectoryExplode = explode('/', $tracksDirectory);
				$trackDirectoryParent = null;
				for($j = 0; $j < count($trackDirectoryExplode)-2; $j++){
					$trackDirectoryParent .= $trackDirectoryExplode[$j].'/';
				}
				if( ! @rename($tracksDirectory.$moveChallengeFileNameList[$i], $trackDirectoryParent.$moveChallengeFileNameList[$i]) ){
					AdminServ::error($lang[$i18n.'_impossible_deplacer_fichier'].' <i>'.$moveChallengeFileNameList[$i].'</i>');
					break;
				}
			}
			// Sinon on déplace vers le dossier choisit
			else{
				if( ! @rename($tracksDirectory.$moveChallengeFileNameList[$i], $tracksDirectory.$_POST['MoveChallengeFileDirectory'].'/'.$moveChallengeFileNameList[$i]) ){
					AdminServ::error($lang[$i18n.'_impossible_deplacer_fichier'].' <i>'.$moveChallengeFileNameList[$i].'</i>');
					break;
				}
			}
		}
	}
	else if( isset($_POST['deleteMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( ! @unlink($mapsDirectoryPath.$map) ){
				AdminServ::error('Impossible de supprimer la map : '.$map);
				break;
			}
		}
	}
	
	
	// MAPLIST
	$mapsList = AdminServ::getLocalMapList($mapsDirectoryPath.$directory);
	
	
	// HTML
	$client->Terminate();
	AdminServTemplate::getHeader();
?>
<section class="maps hasFolders">
	<section class="cadre left menu">
		<?php include_once AdminServConfig::PATH_INCLUDES .'pages/maps_menu.inc.php'; ?>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right local">
		<h1>Local</h1>
		<div class="title-detail">
			<ul>
				<li class="path"><?php echo $mapsDirectoryPath.$directory; ?></li>
				<li><input type="checkbox" name="checkAll" id="checkAll" value=""<?php if( !is_array($mapsList['lst']) ){ echo ' disabled="disabled"'; } ?> /></li>
			</ul>
		</div>
		
		<form method="post" action="?p=maps-local">
		<div id="maplist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><a href="?sort=name">Map</a></th>
						<th><a href="?sort=env">Environnement</a></th>
						<th><a href="?sort=author">Auteur</a></th>
						<th class="thright"></th>
					</tr>
					<tr class="table-separation"></tr>
				</thead>
				<tbody>
				<?php
					$showMapList = null;
					
					// Liste des maps local
					if( is_array($mapsList['lst']) && count($mapsList['lst']) > 0 ){
						$i = 0;
						foreach($mapsList['lst'] as $id => $map){
							// Ligne
							$showMapList .= '<tr class="'; if($i%2){ $showMapList .= 'even'; }else{ $showMapList .= 'odd'; } $showMapList .= '">'
								.'<td class="imgleft"><img src="'.$mapsList['cfg']['path_rsc'].'images/16/map.png" alt="" /><span title="'.$map['FileName'].'">'.$map['Name'].'</span></td>'
								.'<td class="imgcenter"><img src="'.$mapsList['cfg']['path_rsc'].'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</td>'
								.'<td>'.$map['Author'].'</td>'
								.'<td class="checkbox"><input type="checkbox" name="map[]" value="'.$map['FileName'].'" /></td>'
							.'</tr>';
							$i++;
						}
					}
					else{
						$showMapList .= '<tr class="no-line"><td class="center" colspan="4">'.$mapsList['lst'].'</td></tr>';
					}
					
					// Affichage
					echo $showMapList;
				?>
				</tbody>
			</table>
		</div>
		
		<div class="options">
			<div class="fleft">
				<span class="nb-line"><?php echo $mapsList['nbm']; ?></span>
			</div>
			<div class="fright">
				<div class="selected-files-label locked">
					<span class="selected-files-title">Pour la sélection</span>
					<span class="selected-files-count">(0)</span>
					<div class="selected-files-option">
						<input class="button dark" type="submit" name="deleteMap" id="deleteMap" value="Supprimer" />
						<input class="button dark" type="submit" name="moveMap" id="moveMap" value="Déplacer" />
						<input class="button dark" type="button" name="renameMap" id="renameMap" value="Renommer" />
						<input class="button dark" type="submit" name="downloadMap" id="downloadMap" value="Télécharger" />
						<input class="button dark" type="submit" name="insertMap" id="insertMap" value="Insérer" />
						<input class="button dark" type="submit" name="addMap" id="addMap" value="Ajouter" />
					</div>
				</div>
			</div>
			<div id="form-rename-map" class="option-form" hidden="hidden"></div>
			<div id="form-move-map" class="option-form" hidden="hidden"></div>
		</div>
		</form>
	</section>
</section>
<?php
	AdminServTemplate::getFooter();
?>