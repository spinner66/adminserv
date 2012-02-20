<?php
	$archive = new Archive();
	
	AdminServTemplate::getHeader();
?>
<section>
	<?php
		$data = array();
		$archive->create('./plugins/test.zip', $data);
	?>
</section>
<?php
	AdminServTemplate::getFooter();
?>