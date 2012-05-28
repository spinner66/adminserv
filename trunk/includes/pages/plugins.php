<?php
	AdminServUI::getHeader();
?>
<section class="plugins hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServPlugin::getMenuList(); ?>
	</section>
	
	<section class="cadre right list">
		<?php
			if( $plugin = AdminServPlugin::getCurrentPlugin() ){
				echo '<h1>'.AdminServPlugin::getName($plugin).'</h1>';
				AdminServPlugin::getPlugin($plugin);
			}
			else{
		?>
			<h1>Plugins</h1>
			<div class="content">
				<p>Les plugins sont des extensions permettant l'ajout de fonctionnalités pour AdminServ.</p>
			</div>
			
			<h2>Comment installer un plugin ?</h2>
			<div class="content">
				<p>Les plugins sont diponibles sur la page de téléchargement du projet AdminServ : <a href="http://code.google.com/p/adminserv/downloads/list">Cliquez-ici</a></p>
				<p>Ensuite dézipper le fichier et placer son contenu dans le dossier "plugins" d'AdminServ. Pour finir, dans la configuration Extension, ajoutez
				une ligne décrivant le plugin :</p>
				<p>
					<code>
						public static $PLUGINS = array(<br />
						&nbsp;&nbsp;&nbsp;&nbsp;'Nom du plugin',<br />
						);
					</code>
				</p>
			</div>
			
			<h2>Comment créer son plugin ?</h2>
			<div class="content">
				<p>Pour créer son plugin, il faut créer un dossier dans plugins avec un fichier php portant le même nom (tout en minuscule).
				C'est ce fichier php qui sera inclu dans la page "plugins" d'AdminServ. A vous de créer une configuration, une classe, et autres ressources possibles
				puis de les inclures dans le fichier principal du plugin.</p>
			</div>
		<?php } ?>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>