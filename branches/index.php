<?php
	require_once 'config/displayserv.cfg.php';
	require_once DisplayServConfig::PATH_INCLUDES .'displayserv.inc.php';
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<title>DisplayServ</title>
		<?php echo DisplayServ::getHeadFiles(); ?>
		<script>
			$(document).ready(function(){
				$("#displayserv").displayServ();
			});
		</script>
	</head>
	<body>
		<div id="displayserv"></div>
	</body>
</html>