<?php
	AdminServTemplate::getHeader();
?>
<section>
	<?php
		$data = array(
			'dirname' => array(
				'dirname2' => array(
					'file1.txt'
				),
				'file2.txt'
			),
			'file3.txt'
		);
		Zip::create('test.zip', $data);
	?>
</section>
<?php
	AdminServTemplate::getFooter();
?>