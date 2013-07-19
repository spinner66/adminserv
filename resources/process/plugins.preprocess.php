<?php
	// Tente de récupérer les plugins d'une autre config
	AdminServPlugin::setPluginsList();
	
	// Chargement du plugin
	if(USER_PLUGIN){
		if( AdminServPlugin::hasPlugin(USER_PLUGIN) ){
			AdminServPlugin::getPlugin();
			AdminServLogs::add('access', 'Plugin');
		}
		else{
			Utils::redirection(false, '?p=plugins-list');
		}
	}
?>