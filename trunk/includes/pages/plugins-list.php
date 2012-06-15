<?php
	// Nombre de plugins
	$nbPlugins = AdminServPlugin::countPlugins();
	
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
			<p>Les plugins sont diponibles sur la page de téléchargement du projet AdminServ : <a href="http://code.google.com/p/adminserv/downloads/list">Cliquez-ici</a></p>
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
			<p>Pour créer son plugin, copiez le dossier &laquo; _newplugin &raquo; et remplacez les valeurs dans le fichier config.ini.<br />
			Le fichier &laquo; index.php &raquo; sera inclu dans la page plugins d'AdminServ. A vous de créer une configuration, une classe, et autres ressources puis de les inclures dans ce fichier.</p>
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>