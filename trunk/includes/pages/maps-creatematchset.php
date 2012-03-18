<?php
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right creatematchset">
		<h1>Créer un MatchSetting</h1>
		
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>