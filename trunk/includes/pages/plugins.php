<?php
	$archive = new Archive();
	
	AdminServTemplate::getHeader();
?>
<section>
	<?php
		$test = $archive->create('test');
		AdminServ::dsm($test);
	?>
</section>
<?php
	AdminServTemplate::getFooter();
?>