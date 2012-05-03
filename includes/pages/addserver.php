<?php
	// VERIFICATION
	if( class_exists('ServerConfig') ){
		// Si la configuration contient au moins 1 serveur et qu'il n'est pas l'exemple
		if( AdminServ::hasServer() ){
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
	
	}
	
	
	// LECTURE
	if($id !== -1){
		define('IS_SERVER_EDITION', true);
	}
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="cadre">
	<h1><?php if( defined('IS_SERVER_EDITION') ){ echo 'Éditer un serveur'; }else{ echo Utils::t('Add server'); } ?></h1>
	<form method="post" action="?p=<?php echo USER_PAGE; ?>">
		<div class="content">
			<fieldset>
				<legend>Informations de connexion</legend>
				<table>
					<tr>
						<td class="key"><label for="addServerName">Nom du serveur</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerName" id="addServerName" value="" />
						</td>
						<td class="help">
							Nom du serveur sans couleur
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAddress">Adresse</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAddress" id="addServerAddress" value="" />
						</td>
						<td class="help">
							Adresse IP ou nom de domaine
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerPort">Port XMLRPC</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerPort" id="addServerPort" value="" />
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
							<input class="text width3" type="text" name="addServerMatchSet" id="addServerMatchSet" value="" />
						</td>
						<td class="help">
							Nom du MatchSettings courant du serveur
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlSA">Niveau "SuperAdmin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlSA" id="addServerAdmLvlSA" value="all" />
						</td>
						<td rowspan="3" class="help">
							Valeurs possibles pour les niveaux admins :<br />
							all => accès à tous<br />
							local => accès au réseau local<br />
							192.168.0.1, 192.168.0.2 => accès à une ou plusieurs adresses IP
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlADM">Niveau "Admin"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlADM" id="addServerAdmLvlADM" value="all" />
						</td>
					</tr>
					<tr>
						<td class="key"><label for="addServerAdmLvlUSR">Niveau "User"</label></td>
						<td class="value">
							<input class="text width3" type="text" name="addServerAdmLvlUSR" id="addServerAdmLvlUSR" value="all" />
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