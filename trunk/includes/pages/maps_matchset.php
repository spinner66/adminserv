<?php
	// MAPLIST
	$mapsList = AdminServ::getMapList();
	
	
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu hasFolders">
	<section class="cadre left menu">
		<nav class="vertical-nav">
			<?php echo $mapsMenu; ?>
		</nav>
	</section>
	
	<section class="cadre middle folders">
		<?php echo $mapsDirectoryList; ?>
	</section>
	
	<section class="cadre right matchset">
		<h1>MatchSettings</h1>
		<div class="title-detail">
			<ul>
				<li class="path"><?php echo $mapsDirectoryPath.$directory; ?></li>
				<li><input type="checkbox" name="" id="" value="" /></li>
			</ul>
		</div>
		
		<div id="matchsetlist">
			<table>
				<thead>
					<tr>
						<th class="thleft"><a href="?sort=name">Nom</a></th>
						<th><a href="?sort=env">Modifié le</a></th>
						<th class="thright"></th>
					</tr>
					<tr class="table-separation"></tr>
				</thead>
				<tbody>
				<?php
					$showMapList = null;
					
					// Liste des joueurs
					if( is_array($mapsList) && count($mapsList) > 0 ){
						$i = 0;
						foreach($mapsList['lst'] as $id => $map){
							// Ligne
							$showMapList .= '<tr class="'; if($i%2){ $showMapList .= 'even'; }else{ $showMapList .= 'odd'; } if($id == $mapsList['cid']){ $showMapList .= ' current'; } $showMapList .= '">'
								.'<td class="imgleft"><img src="'.$mapsList['cfg']['path_rsc'].'images/16/map.png" alt="" /><span title="'.$map['FileName'].'">'.$map['Name'].'</span></td>'
								.'<td class="imgcenter"><img src="'.$mapsList['cfg']['path_rsc'].'images/env/'.strtolower($map['Environnement']).'.png" alt="" />'.$map['Environnement'].'</td>'
								.'<td class="checkbox">'; if($id != $mapsList['cid']){ $showMapList .= '<input type="checkbox" name="map[]" value="'.$map['FileName'].'" />'; } $showMapList .= '</td>'
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
						<input class="button dark" type="button" name="rename" id="rename" value="Supprimer" />
						<input class="button dark" type="button" name="move" id="move" value="Placer après la map en cours" />
					</div>
				</div>
			</div>
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>