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
	
	
	// ACTIONS
	if( isset($_POST['addMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['add'], $mapsDirectoryPath.$map) ){
				AdminServ::error();
				break;
			}
		}
	}
	else if( isset($_POST['insertMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !$client->query($queries['insert'], $mapsDirectoryPath.$map) ){
				AdminServ::error();
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
	else if( isset($_POST['renameMapValid']) && isset($_POST['map']) && count($_POST['map']) > 0 && isset($_POST['renameMapList']) && count($_POST['renameMapList']) > 0 ){
		$i = 0;
		foreach($_POST['renameMapList'] as $newMapName){
			if( File::rename($mapsDirectoryPath.$_POST['map'][$i], $mapsDirectoryPath.$newMapName) !== true ){
				AdminServ::error('Impossible de renommer la map : '.$newMapName);
				break;
			}
			$i++;
		}
	}
	else if( isset($_POST['moveMapValid']) && isset($_POST['moveDirectoryList']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		// Chemin
		if($_POST['moveDirectoryList'] == '.'){
			$newPath = $mapsDirectoryPath;
		}
		else{
			$newPath = $_POST['moveDirectoryList'];
		}
		
		// Déplacement
		foreach($_POST['map'] as $map){
			if( File::rename($mapsDirectoryPath.$directory.$map, $newPath.$map) !== true ){
				AdminServ::error('Impossible de déplacer la map : '.$map);
				break;
			}
		}
	}
	else if( isset($_POST['deleteMap']) && isset($_POST['map']) && count($_POST['map']) > 0 ){
		foreach($_POST['map'] as $map){
			if( !File::delete($mapsDirectoryPath.$map) ){
				AdminServ::error('Impossible de supprimer la map : '.$map);
				break;
			}
		}
	}
	
	
	// MAPLIST
	$mapsList = AdminServ::getLocalMapList($mapsDirectoryPath.$directory);
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList($menuList); ?>
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
		
		<form method="post" action="?p=maps-local<?php if($directory){ echo '&amp;d='.$directory; } ?>">
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
						<input class="button dark" type="submit" name="deleteMap" id="deleteMap" value="Supprimer" data-confirm="Voulez-vous vraiment supprimer cette sélection ?" />
						<input class="button dark" type="button" name="moveMap" id="moveMap" value="Déplacer" />
						<input class="button dark" type="button" name="renameMap" id="renameMap" value="Renommer" />
						<input class="button dark" type="submit" name="downloadMap" id="downloadMap" value="Télécharger" />
						<input class="button dark" type="submit" name="insertMap" id="insertMap" value="Insérer" />
						<input class="button dark" type="submit" name="addMap" id="addMap" value="Ajouter" />
					</div>
				</div>
			</div>
			<div id="form-rename-map" class="option-form" hidden="hidden" data-cancel="Annuler" data-rename="Renommer"></div>
			<div id="form-move-map" class="option-form" hidden="hidden" data-cancel="Annuler" data-move="Déplacer" data-inthefolder="dans le dossier :" data-root="Racine"></div>
		</div>
		</form>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>