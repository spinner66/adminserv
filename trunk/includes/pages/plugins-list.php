<?php
	// Nombre de plugins
	$nbPlugins = AdminServPlugin::countPlugins();
	if($nbPlugins['count'] === 0){
		Utils::redirection();
	}
	
	// HTML
	AdminServUI::getHeader();
?>
<section class="plugins hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServPlugin::getMenuList(); ?>
	</section>
	
	<section class="cadre right">
		<h1><?php echo Utils::t('Plugins'); ?></h1>
		<div class="title-detail">
			<ul>
				<li class="last">
					<?php echo $nbPlugins['count'].' '.$nbPlugins['title']; ?>
				</li>
			</ul>
		</div>
		<div class="content">
			<p>Les plugins sont des extensions permettant d'ajouter des fonctionnalités pour AdminServ.</p>
		</div>
		
		<h2>Comment installer un plugin ?</h2>
		<div class="content">
			<p>Les plugins sont disponibles sur la page de téléchargement du projet AdminServ : <a href="http://code.google.com/p/adminserv/downloads/list">Cliquez-ici</a></p>
			<p>- Dézippez le plugin et placez son contenu dans le dossier &laquo; plugins &raquo; d'AdminServ.<br />
			- Dans la configuration Extension, ajoutez le nom du dossier du plugin précédement ajouté.</p>
			<p>
				<code>
					public static $PLUGINS = array(<br />
					&nbsp;&nbsp;&nbsp;&nbsp;'PluginName',<br />
					);
				</code>
			</p>
		</div>
		
		<h2>Comment créer son plugin ?</h2>
		<div class="content">
			<p>Pour créer son plugin, allez dans le dossier &laquo; Plugins  &raquo; et dupliquez le dossier &laquo; _newplugin &raquo. Remplacez les valeurs dans le fichier config.ini ainsi que le nom du dossier.<br />
			Le fichier &laquo; index.php &raquo; est le fichier principal du plugin. A vous de créer les ressources (classes, js, css) puis de les inclures dans ce fichier.</p>
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>