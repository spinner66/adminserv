<?php
abstract class TimeDate {
	
	/**
	* Affiche la fonction date() en Français
	*
	* @param string $params -> Les paramètres de la fonction date()
	* @param int    $time   -> Le temps de la fonction date() -> par défaut = time()
	* @return string
	*/
	public static function dateFR($params, $time = null){
		// On récupère la date
		if($time){
			$date = date($params, $time);
		}else{
			$date = date($params);
		}
		
		// On modifie les valeurs
		// Jour
		if( strstr($params, 'D') ){
			$date = str_replace('Mon', 'Lun', $date);
			$date = str_replace('Tue', 'Mar', $date);
			$date = str_replace('Wed', 'Mer', $date);
			$date = str_replace('Thu', 'Jeu', $date);
			$date = str_replace('Fri', 'Ven', $date);
			$date = str_replace('Sat', 'Sam', $date);
			$date = str_replace('Sun', 'Dim', $date);
		}
		else if( strstr($params, 'l') ){
			$date = str_replace('Monday', 'Lundi', $date);
			$date = str_replace('Tuesday', 'Mardi', $date);
			$date = str_replace('Wednesday', 'Mercredi', $date);
			$date = str_replace('Thursday', 'Jeudi', $date);
			$date = str_replace('Friday', 'Vendredi', $date);
			$date = str_replace('Saturday', 'Samedi', $date);
			$date = str_replace('Sunday', 'Dimanche', $date);
		}
		// Mois
		if( strstr($params, 'F') ){
			$date = str_replace('January', 'Janvier', $date);
			$date = str_replace('February', 'Février', $date);
			$date = str_replace('March', 'Mars', $date);
			$date = str_replace('April', 'Avril', $date);
			$date = str_replace('May', 'Mai', $date);
			$date = str_replace('June', 'Juin', $date);
			$date = str_replace('July', 'Juillet', $date);
			$date = str_replace('August', 'Août', $date);
			$date = str_replace('September', 'Septembre', $date);
			$date = str_replace('October', 'Octobre', $date);
			$date = str_replace('November', 'Novembre', $date);
			$date = str_replace('December', 'Décembre', $date);
		}
		else if( strstr($params, 'M') ){
			$date = str_replace('Jan', 'Jan', $date);
			$date = str_replace('Feb', 'Fév', $date);
			$date = str_replace('Mar', 'Mar', $date);
			$date = str_replace('Apr', 'Avr', $date);
			$date = str_replace('May', 'Mai', $date);
			$date = str_replace('Jun', 'Jun', $date);
			$date = str_replace('Jul', 'Jul', $date);
			$date = str_replace('Aug', 'Aoû', $date);
			$date = str_replace('Sep', 'Sep', $date);
			$date = str_replace('Oct', 'Oct', $date);
			$date = str_replace('Nov', 'Nov', $date);
			$date = str_replace('Dec', 'Déc', $date);
		}
		
		return $date;
	}
	
	
	/**
	* Rentourne la date au format INT
	*
	* @param string $date         -> La date sous la forme JJ/MM/AAAA, JJ.MM.AAAA, JJ-MM-AAAA ou JJ MM AAAA sinon mettre $useStrToTime sur true
	* @param bool   $useStrToTime -> Utiliser la méthode php strtotime pour une date d'un autre format
	* @return int
	*/
	public static function dateToTime($date, $useStrToTime = false){
		$out = 0;
		// Si le paramètre n'est pas vide
		if($date){
			$date = trim($date);
			if( strstr($date, '/') && !$useStrToTime){
				$date_ex = explode('/', $date);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, '.')  && !$useStrToTime){
				$date_ex = explode('.', $date_ex);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, '-')  && !$useStrToTime){
				$date_ex = explode('-', $date_ex);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, ' ')  && !$useStrToTime){
				$date_ex = explode(' ', $date_ex);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else{
				$out = strtotime($date);
			}
		}
		// Retour
		return $out;
	}
	
	
	/**
	* Retourne un temps au format INT
	*
	* @param string $time -> Le temps sous la forme HH:MM:SS, HH:MM.SS, HH.MM.SS, HH-MM-SS ou HH MM SS
	* @return int
	*/
	public static function timeToSec($time){
		$out = 0;
		$h = 3600;
		$m = 60;
		$s = 1;
		// Si le paramètre n'est pas vide
		if($time){
			$time = trim($time);
			if( strstr($time, ':') && !strstr($time, '.') && !$useStrToTime){
				$time_ex = explode(':', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, ':') && strstr($time, '.') && !$useStrToTime){
				$time_ex = explode(':', $time);
				$time_ex2 = explode('.', $time_ex);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, '.') && !strstr($time, ':') && !$useStrToTime){
				$time_ex = explode('.', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, '-') && !$useStrToTime){
				$time_ex = explode('-', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, ' ') && !$useStrToTime){
				$time_ex = explode(' ', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
		}
		// Retour
		return $out;
	}
	
	
	/**
	* Formate une date pour une entrée MySQL
	*
	* @param string $type         -> Le type Mysql à retourner
	* @param string $date         -> La date sous la forme JJ/MM/AAAA, JJ.MM.AAAA, JJ-MM-AAAA ou JJ MM AAAA sinon mettre $useStrToTime sur true
	* @param string $time         -> Le temps sous la forme HH:MM:SS, HH:MM.SS, HH.MM.SS, HH-MM-SS ou HH MM SS
	* @param bool   $useStrToTime -> Utiliser la méthode php strtotime pour une date d'un autre format
	* @return string
	*/
	public static function formatDateForMySQL($type, $date, $time = null, $useStrToTime = false){
		// Variables
		$out = '000-00-00 00:00:00';
		$type = strtoupper($type);
		if($date != null){ $date = trim($date); }
		if($time == null){ $time = '00:00:00'; }
		
		// Type MySQL
		if($type == 'DATE'){
			$out = date('Y-m-d', self::dateToTime($date, $useStrToTime));
		}
		else if($type == 'DATETIME'){
			$datetotime = self::dateToTime($date, $useStrToTime);
			$timetosec = self::timeToSec($time);
			$out = date('Y-m-d H:i:s', $datetotime + $timetosec);
		}
		else if($type == 'TIME'){
			$out = date('H:i:s', self::timeToSec($time));
		}
		else if($type == 'YEAR'){
			$out = date('Y', self::dateToTime($date, $useStrToTime));
		}
		else{
			$out = self::formatDateForMySQL('DATETIME', $date, $time, $useStrToTime);
		}
		return $out;
	}
	
	
	
	/**
	* Récupère l'âge à partir de l'année de naissance
	*
	* @param string $birthday -> Sous la forme jj/mm/aaaa ou autre
	*/
	public static function getYearOld($birthday){
		$birthdayDate = getdate( self::dateToTime($birthday) );
		$currentDate = getdate();
		if( ($birthdayDate['mon'] < $currentDate['mon']) || ( ($birthdayDate['mon'] == $currentDate['mon']) && ($birthdayDate['mday'] <= $currentDate['mday']) ) ){
			return $currentDate['year'] - $birthdayDate['year'].' ans';
		}else{
			return $currentDate['year'] - $birthdayDate['year'] - 1 .' ans';
		}
	}
	
	
	/**
	* Retourne une date relative sous la forme il y a x jours/heures/minutes/secondes
	*
	* @param int $time -> Temps à convertir en seconde
	* @return string
	*/
	public static function relativeTime($time){
		$out = null;
		$timeDifference = time() - $time;
		
		// Si le temps est supérieur à 0
		if($timeDifference > 0){
			// Calcul du temps
			$seconds = $timeDifference;
			$minutes = round($timeDifference/60);
			$hours = round($timeDifference/3600);
			$days = round($timeDifference/86400);
			$weeks = round($timeDifference/604800);
			$months = round($timeDifference/2419200);
			$years = round($timeDifference/29030400);
			
			// Création du texte
			if($seconds < 60){
				$out .= 'Il y a moins d\'une minute';
			}
			else if($minutes < 60){
				$out .= 'Il y a '.$minutes.' minute'; if($minutes > 1){ $out .= 's'; }
			}
			else if($hours < 24){
				$out .= 'Il y a '.$hours.' heure'; if($hours > 1){ $out .= 's'; }
			}
			else if($days < 7){
				$out .= 'Il y a '.$days.' jour'; if($days > 1){ $out .= 's'; }
			}
			else if($weeks < 4){
				$out .= 'Il y a '.$weeks.' semaine'; if($weeks > 1){ $out .= 's'; }
			}
			else if($months < 12){
				$out .= 'Il y a '.$months.' mois';
			}
			else{
				$out .= 'Il y a '.$years.' an'; if($years > 1){ $out .= 's'; }
			}
		}
		// Retour
		return $out;
	}
	
	
	/**
	* Transforme un temps en seconde sous forme 0 jour 0 heure 0 minute 0 seconde
	*
	* @param  int  $sec      -> Secondes à transformer
	* @param  bool $fullText -> Retourner le texte en entier
	* @return string
	*/
	public static function secToStringTime($sec, $fullText = true){
		$out = null;
		$timeDifference = intval($sec);
		
		// Si le temps est supérieur à 0
		if($timeDifference > 0){
			// Calcul du temps
			$seconds = $timeDifference;
			$minutes = round($timeDifference/60);
			$hours = round($timeDifference/3600);
			$days = round($timeDifference/86400);
			$weeks = round($timeDifference/604800);
			$months = round($timeDifference/2419200);
			$years = round($timeDifference/29030400);
			
			// Création du texte
			if($seconds < 60){
				$out .= $seconds.'sec'; if($fullText){ $out .= 'onde'; if($seconds > 1){ $out .= 's'; } }
			}
			else if($minutes < 60){
				$out .= $minutes.'min'; if($fullText){ $out .= 'ute'; if($minutes > 1){ $out .= 's'; } }
			}
			else if($hours < 24){
				$out .= $hours.'h'; if($fullText){ $out .= 'eure'; if($hours > 1){ $out .= 's'; } }
			}
			else if($days < 7){
				$out .= $days.'j'; if($fullText){ $out .= 'our'; if($days > 1){ $out .= 's'; } }
			}
			else if($weeks < 4){
				$out .= $weeks.'sem'; if($fullText){ $out .= 'aine'; if($weeks > 1){ $out .= 's'; } }
			}
			else if($months < 12){
				$out .= $months.'mois';
			}
			else{
				$out .= $years.'an'; if($years > 1){ $out .= 's'; }
			}
		}
		// Retour
		return $out;
	}
	public static function secToMillisec($sec){
		$sec = intval( round($sec) );
		$millisec = $sec*1000;
		if($millisec > 0){ return $millisec; }
		else{ return 0; }
	}
	public static function millisecToSec($millisec){
		$millisec = intval( round($millisec) );
		$sec = $millisec/1000;
		if($sec > 0){ return $sec; }
		else{ return 0; }
	}
	public static function secToMin($sec){
		$sec = intval( round($sec) );
		$min = $sec/60;
		if($min > 0){ return $min; }
		else{ return 0; }
	}
	public static function minToSec($min){
		$min = intval( round($min) );
		$sec = $min*60;
		if($sec > 0){ return $sec; }
		else{ return 0; }
	}
	public static function millisecToMin($millisec){
		$millisec = intval( round($millisec) );
		$sec = $millisec/1000;
		$min = $sec/60;
		if($min > 0){ return $min; }
		else{ return 0; }
	}
}
?>