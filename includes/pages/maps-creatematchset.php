<?php
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right creatematchset">
		<h1>Créer un MatchSettings</h1>
		
		<h2>Informations de jeu</h2>
		
		<h2>Maps</h2>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>