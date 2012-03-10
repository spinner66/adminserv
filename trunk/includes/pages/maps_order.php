<?php
	// HTML
	$client->Terminate();
	AdminServUI::getHeader();
?>
<section class="maps hasMenu">
	<section class="cadre left menu">
		<?php echo AdminServUI::getMenuList($menuList); ?>
	</section>
	
	<section class="cadre right order">
		<h1>Ordonner</h1>
	</section>
</section>
<?php
	AdminServUI::getFooter();
?>