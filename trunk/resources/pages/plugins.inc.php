<?php
	// Tente de récupérer les plugins d'une autre config
	AdminServPlugin::setPluginsList();
	
	// Chargement du plugin
	if(CURRENT_PLUGIN){
		if( AdminServPlugin::hasPlugin(CURRENT_PLUGIN) ){
			AdminServPlugin::getPlugin();
			AdminServLogs::add('access', 'Plugin');
		}
		else{
			Utils::redirection(false, '?p=plugins-list');
		}
	}
?>