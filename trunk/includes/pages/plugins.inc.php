<?php
	// Logs
	AdminServLogs::add('access', 'Connected - Access to the plugin');
	
	// Tente de récupérer les plugins d'une autre config
	AdminServPlugin::setPluginsList();
	
	// Chargement du plugin
	if(CURRENT_PLUGIN){
		AdminServPlugin::getPlugin();
	}
?>