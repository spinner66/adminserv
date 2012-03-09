<?php
	// SERVERLIST
	$serverList = ServerConfig::$SERVERS;
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1>Liste des serveurs</h1>
	<table>
		<thead>
			<tr>
				<th class="thleft"><a href="?sort=">Nom du serveur</a></th>
				<th><a href="?sort=">Adresse</a></th>
				<th>Port</th>
				<th>MatchSettings</th>
				<th>Niveau SuperAdmin</th>
				<th>Niveau Admin</th>
				<th>Niveau User</th>
				<th class="thright">Gestion</th>
			</tr>
			<tr class="table-separation"></tr>
		</thead>
		<tbody>
		<?php
			$showServerList = null;
			
			// Liste des joueurs
			if( is_array($serverList) && count($serverList) > 0 ){
				$i = 0;
				foreach($serverList as $serverName => $serverData){
					// Données
					if($serverData['matchsettings']){ $matchSettings = $serverData['matchsettings']; }else{ $matchSettings = 'Aucun'; }
					$adminLevelSA = $serverData['adminlevel']['SuperAdmin'];
					if( is_array($adminLevelSA) ){ $adminLevelSA = implode(', ', $adminLevelSA); }else if($adminLevelSA == null){ $adminLevelSA = 'Tous'; }
					$adminLevelAD = $serverData['adminlevel']['Admin'];
					if( is_array($adminLevelAD) ){ $adminLevelAD = implode(', ', $adminLevelAD); }else if($adminLevelAD == null){ $adminLevelAD = 'Tous'; }
					$adminLevelUS = $serverData['adminlevel']['User'];
					if( is_array($adminLevelUS) ){ $adminLevelUS = implode(', ', $adminLevelUS); }else if($adminLevelUS == null){ $adminLevelUS = 'Tous'; }
					
					// Ligne
					$showServerList .= '<tr class="'; if($i%2){ $showServerList .= 'even'; }else{ $showServerList .= 'odd'; } $showServerList .= '">'
						.'<td class="imgleft"><img src="'. AdminServConfig::PATH_RESSOURCES .'images/16/servers.png" alt="" />'.$serverName.'</td>'
						.'<td>'.$serverData['address'].'</td>'
						.'<td>'.$serverData['port'].'</td>'
						.'<td>'.$matchSettings.'</td>'
						.'<td>'.$adminLevelSA.'</td>'
						.'<td>'.$adminLevelAD.'</td>'
						.'<td>'.$adminLevelUS.'</td>'
						.'<td></td>'
					.'</tr>';
					$i++;
				}
			}
			else{
				$showServerList .= '<tr class="no-line"><td class="center" colspan="6">Aucun serveur</td></tr>';
			}
			
			// Affichage
			echo $showServerList;
		?>
		</tbody>
	</table>
</section>
<?php
	AdminServUI::getFooter();
?>