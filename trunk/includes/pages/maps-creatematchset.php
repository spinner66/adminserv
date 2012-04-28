<?php
	// LECTURE
	$gameInfos = AdminServ::getGameInfos();
	$currGamInf = null;
	$nextGamInf = $gameInfos['next'];
	
	
	
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList(ExtensionConfig::$MAPSMENU); ?>
	</section>
	
	<section class="cadre right creatematchset">
		<h1>Créer un MatchSettings</h1>
		
		<h2>Informations de jeu</h2>
		<div class="content">
			<?php
				// Général
				echo AdminServUI::getGameInfosGeneralForm($currGamInf, $nextGamInf);
				// Modes de jeux
				echo AdminServUI::getGameInfosGameModeForm($currGamInf, $nextGamInf);
			?>
		</div>
		
		<h2>Maps</h2>
		<div class="content">
			
		</div>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>