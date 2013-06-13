<?php

/**
* Classe pour le traitement des tris AdminServ
*/
class AdminServSort {
	
	
	public static function sortByNickName($a, $b){
		$a['NickName'] = TmNick::toText($a['NickName']);
		$b['NickName'] = TmNick::toText($b['NickName']);
		
		if($a['NickName'] == $b['NickName']){
			return 0;
		}
		if($a['NickName'] < $b['NickName']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLadderRanking($a, $b){
		if($a['LadderRanking'] == $b['LadderRanking']){
			return 0;
		}
		if($a['LadderRanking'] < $b['LadderRanking']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByLogin($a, $b){
		if($a['Login'] == $b['Login']){
			return 0;
		}
		if($a['Login'] < $b['Login']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByStatus($a, $b){
		if($a['SpectatorStatus'] == $b['SpectatorStatus']){
			return 0;
		}
		if($a['SpectatorStatus'] < $b['SpectatorStatus']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByTeam($a, $b){
		if($a['TeamId'] == 0){
			$a['TeamId'] = 'blue';
		}else if($a['TeamId'] == 1){
			$a['TeamId'] = 'red';
		}else{
			$a['TeamId'] = 'spectator';
		}
		if($b['TeamId'] == 0){
			$b['TeamId'] = 'blue';
		}else if($b['TeamId'] == 1){
			$b['TeamId'] = 'red';
		}else{
			$b['TeamId'] = 'spectator';
		}
		
		if($a['TeamId'] == $b['TeamId']){
			return 0;
		}
		if($a['TeamId'] < $b['TeamId']){
			return -1;
		}else{
			return 1;
		}
	}
	
	/* Maps-list */
	public static function sortByFileName($a, $b){
		if($a['FileName'] == $b['FileName']){
			return 0;
		}
		if($a['FileName'] < $b['FileName']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByName($a, $b){
		$a['Name'] = TmNick::toText($a['Name']);
		$b['Name'] = TmNick::toText($b['Name']);
		
		if($a['Name'] == $b['Name']){
			return 0;
		}
		if($a['Name'] < $b['Name']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByEnviro($a, $b){
		if($a['Environnement'] == 'Speed'){
			$a['Environnement'] = 'Desert';
		}
		if($b['Environnement'] == 'Speed'){
			$b['Environnement'] = 'Desert';
		}
		if($a['Environnement'] == 'Alpine'){
			$a['Environnement'] = 'Snow';
		}
		if($b['Environnement'] == 'Alpine'){
			$b['Environnement'] = 'Snow';
		}
		
		if($a['Environnement'] == $b['Environnement']){
			return 0;
		}
		if($a['Environnement'] < $b['Environnement']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByType($a, $b){
		if($a['Type']['Name'] == $b['Type']['Name']){
			return 0;
		}
		if($a['Type']['Name'] < $b['Type']['Name']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByAuthor($a, $b){
		if($a['Author'] == $b['Author']){
			return 0;
		}
		if($a['Author'] < $b['Author']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByGoldTime($a, $b){
		if($a['GoldTime'] == $b['GoldTime']){
			return 0;
		}
		if($a['GoldTime'] < $b['GoldTime']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByPrice($a, $b){
		if($a['CopperPrice'] == $b['CopperPrice']){
			return 0;
		}
		if($a['CopperPrice'] < $b['CopperPrice']){
			return -1;
		}else{
			return 1;
		}
	}
	public static function sortByRank($a, $b){
		if($a['Rank'] == $b['Rank']){
			return 0;
		}
		if($a['Rank'] < $b['Rank']){
			return -1;
		}else{
			return 1;
		}
	}
}
?>