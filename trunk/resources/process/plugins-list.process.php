<?php
	// Nombre de plugins
	$nbPlugins = AdminServPlugin::countPlugins();
	if($nbPlugins['count'] === 0){
		Utils::redirection();
	}
?>