<?php
abstract class Example {
	
	public static function myFunction(){
		$gameinfos = AdminServ::getGameInfos();
		
		// Use "AdminServ::dsm($gameinfos);" for view all array
		return 'Current game mode : '.AdminServ::getGameModeName($gameinfos['curr']['GameMode']);
	}
}