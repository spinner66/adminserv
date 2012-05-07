<?php
	// SESSION
	if( !isset($_SESSION['adminserv']['allow_config_servers']) ){
		AdminServ::error('Vous n\'êtes pas autorisé à configurer les serveurs.');
		Utils::redirection(false);
	}
	
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
		if( AdminServServerConfig::hasServer() ){
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE !== true ){
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false);
			}
		}
		else{
			// Si on autorise la configuration en ligne
			if( OnlineConfig::ACTIVATE !== true ){
				AdminServ::info('Aucun serveur n\'est disponible. Pour en ajouter un, il faut configurer le fichier "config/servers.cfg.php"');
				Utils::redirection(false);
			}
		}
	}
	else{
		AdminServ::error('Le fichier de configuration des serveurs n\'est pas reconnu par AdminServ.');
		Utils::redirection(false);
	}
	
	
	// ENREGISTREMENT
	if( isset($_POST['saveserver']) ){
		// Variables
		$serverName = trim( htmlspecialchars( addslashes($_POST['addServerName']) ) );
		$serverAddress = trim($_POST['addServerAddress']);
		$serverPort = intval($_POST['addServerPort']);
		$serverMatchSet = trim($_POST['addServerMatchSet']);
		$serverAdmLvl_SA = $_POST['addServerAdmLvlSA'];
		$serverAdmLvl_ADM = $_POST['addServerAdmLvlADM'];
		$serverAdmLvl_USR = $_POST['addServerAdmLvlUSR'];
		if( is_array($serverAdmLvl_SA) ){ $serverAdmLvl_SA = explode(',', trim($serverAdmLvl_SA)); }else{ $serverAdmLvl_SA = trim($serverAdmLvl_SA); }
		if( is_array($serverAdmLvl_ADM) ){ $serverAdmLvl_ADM = explode(',', trim($serverAdmLvl_ADM)); }else{ $serverAdmLvl_ADM = trim($serverAdmLvl_ADM); }
		if( is_array($serverAdmLvl_USR) ){ $serverAdmLvl_USR = explode(',', trim($serverAdmLvl_USR)); }else{ $serverAdmLvl_USR = trim($serverAdmLvl_USR); }
		$serverData = array(
			'name' => $serverName,
			'address' => $serverAddress,
			'port' => $serverPort,
			'matchsettings' => $serverMatchSet,
			'adminlevel' => array(
				'SuperAdmin' => $serverAdmLvl_SA,
				'Admin' => $serverAdmLvl_ADM,
				'User' => $serverAdmLvl_USR,
			)
		);
		
		// Édition
		if($id !== -1){
			if( AdminServServerConfig::saveServerConfig($serverData, $id) ){
				AdminServ::info('Le serveur a bien été modifié.');
				Utils::redirection(false, '?p=servers');
			}
			else{
				AdminServ::error('Impossible de modifier le serveur.');
			}
		}
		else{
			if( AdminServServerConfig::saveServerConfig($serverData) ){
				AdminServ::info('Le serveur a bien été ajouté.');
			}
			else{
				AdminServ::error('Impossible d\'ajouter le serveur.');
			}
		}
	}
	
	
	// LECTURE
	$serverName = null;
	$serverAddress = 'localhost';
	$serverPort = 5000;
	$serverMatchSet = null;
	$serverAdmLvl_SA = 'all';
	$serverAdmLvl_ADM = 'all';
	$serverAdmLvl_USR = 'all';
	if($id !== -1){
		define('IS_SERVER_EDITION', true);
		$serverName = AdminServServerConfig::getServerName($id);
		if($serverName){
			$serverData = AdminServServerConfig::getServer($serverName);
			$serverAddress = $serverData['address'];
			$serverPort = $serverData['port'];
			$serverMatchSet = $serverData['matchsettings'];
			$serverAdmLvl_SA = $serverData['adminlevel']['SuperAdmin'];
			$serverAdmLvl_ADM = $serverData['adminlevel']['Admin'];
			$serverAdmLvl_USR = $serverData['adminlevel']['User'];
			if( is_array($serverAdmLvl_SA) ){ $serverAdmLvl_SA = implode(', ', $serverAdmLvl_SA); }
			if( is_array($serverAdmLvl_ADM) ){ $serverAdmLvl_ADM = implode(', ', $serverAdmLvl_ADM); }
			if( is_array($serverAdmLvl_USR) ){ $serverAdmLvl_USR = implode(', ', $serverAdmLvl_USR); }
		}
	}
	
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php if( defined('IS_SERVER_EDITION') ){ echo 'Éditer un serveur'; }else{ echo Utils::t('Add server'); } ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; if($id !== -1){ echo '&id='.$id; } ?>">
		<div class="content">
			<fieldset>
				<legend>Informations de connexion</legend>
				<table>
					<tr>
						<td class="key"><label for="addServerName">Nom du serveur</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerName" id="addServerName" value="<?php echo $serverName; ?>" />
						</td>
						<td class="help">
							Nom du serveur sans couleur
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAddress">Adresse</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAddress" id="addServerAddress" value="<?php echo $serverAddress; ?>" />
						</td>
						<td class="help">
							Adresse IP ou nom de domaine
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerPort">Port XMLRPC</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerPort" id="addServerPort" value="<?php echo $serverPort; ?>" />
						</td>
						<td class="help">
							Port permettant le contrôle à distance
						</td>
					</tr>
				</table>
			</fieldset>
			
			<fieldset>
				<legend>Informations optionnelles</legend>
				<table>
					<tr>
						<td class="key"><label for="addServerMatchSet">MatchSettings du serveur</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerMatchSet" id="addServerMatchSet" value="<?php echo $serverMatchSet; ?>" />
						</td>
						<td class="help">
							Nom du MatchSettings courant du serveur
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlSA">Niveau "SuperAdmin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlSA" id="addServerAdmLvlSA" value="<?php echo $serverAdmLvl_SA; ?>" />
						</td>
						<td rowspan="3" class="help">
							Valeurs possibles pour les niveaux admins :<br />
							all => accès à tous<br />
							local => accès au réseau local<br />
							192.168.0.1, 192.168.0.2 => accès à une ou plusieurs adresses IP<br />
							none => accès enlevé de la liste
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlADM">Niveau "Admin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlADM" id="addServerAdmLvlADM" value="<?php echo $serverAdmLvl_ADM; ?>" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlUSR">Niveau "User"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlUSR" id="addServerAdmLvlUSR" value="<?php echo $serverAdmLvl_USR; ?>" />
						</td>
					</tr>
				</table>
			</fieldset>
		</div>
		
		<div class="fright save">
			<input class="button light" type="submit" name="saveserver" id="saveserver" value="Enregistrer" />
		</div>
	</form>
</section>
<?php
	AdminServUI::getFooter();
?>