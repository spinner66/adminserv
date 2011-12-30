<?php
	// INCLUDES
	session_start();
	require_once 'adminserv.cfg.php';
	require_once 'servers.cfg.php';
	require_once '../includes/adminserv.inc.php';
	AdminServTemplate::getClass();
	
	define('USER_PAGE', 'config');
?>
<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" />
		<meta name="robots" content="noindex, nofollow" />
		<title><?php echo 'Configuration | '.AdminServTemplate::getTitle(); ?></title>
		<?php echo AdminServTemplate::getCss('../ressources/'); ?>
		<?php echo AdminServTemplate::getJS('../includes/'); ?>
	</head>
	<body>
		
	</body>
</html>