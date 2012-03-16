<?php
abstract class TimeDate {
	
	/**
	* Retourne la date suivant la langue
	*
	* @param string $format    -> Le format de la fonction strftime()
	* @param int    $timestamp -> Le temps en sec
	* @param string $lang      -> La langue de la date retournée
	* @return string
	*/
	public static function date($format, $timestamp = time(), $lang = 'fr_FR'){
		setlocale(LC_ALL, $lang);
		return strftime($format, $timestamp);
	}
	
	
	/**
	* Rentourne la date au format INT
	*
	* @param string $date          -> La date sous la forme JJ/MM/AAAA, JJ.MM.AAAA, JJ-MM-AAAA ou JJ MM AAAA sinon mettre $use_strtotime sur true
	* @param bool   $use_strtotime -> Utiliser la méthode php strtotime pour une date d'un autre format
	* @return int
	*/
	public static function dateToTime($date, $use_strtotime = false){
		$out = 0;
		// Si le paramètre n'est pas vide
		if($date){
			$date = trim($date);
			if( strstr($date, '/') && !$use_strtotime){
				$date_ex = explode('/', $date);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, '.')  && !$use_strtotime){
				$date_ex = explode('.', $date_ex);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, '-')  && !$use_strtotime){
				$date_ex = explode('-', $date_ex);
				$out = mktime(0, 0, 0, $date_ex[1], $date_ex[0], $date_ex[2]); 
			}
			else if( strstr($date_ex, ' ')  && !$use_strtotime){
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
	* @param string $time          -> Le temps sous la forme HH:MM:SS, HH:MM.SS, HH.MM.SS, HH-MM-SS ou HH MM SS sinon mettre $use_strtotime sur true
	* @param bool   $use_strtotime -> Utiliser la méthode php strtotime pour un temps d'un autre format
	* @return int
	*/
	public static function timeToSec($time, $use_strtotime){
		$out = 0;
		$h = 3600;
		$m = 60;
		$s = 1;
		// Si le paramètre n'est pas vide
		if($time){
			$time = trim($time);
			if( strstr($time, ':') && !strstr($time, '.') && !$use_strtotime){
				$time_ex = explode(':', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, ':') && strstr($time, '.') && !$use_strtotime){
				$time_ex = explode(':', $time);
				$time_ex2 = explode('.', $time_ex);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, '.') && !strstr($time, ':') && !$use_strtotime){
				$time_ex = explode('.', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, '-') && !$use_strtotime){
				$time_ex = explode('-', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else if( strstr($time, ' ') && !$use_strtotime){
				$time_ex = explode(' ', $time);
				$out = ($time_ex[0] * $h) + ($time_ex[1] * $m) + ($time_ex[2] * $s); 
			}
			else{
				$out = strtotime($time);
			}
		}
		// Retour
		return $out;
	}
	
	
	/**
	* Formate une date pour une entrée MySQL
	*
	* @param string $type          -> Le type Mysql à retourner
	* @param string $date          -> La date sous la forme JJ/MM/AAAA, JJ.MM.AAAA, JJ-MM-AAAA ou JJ MM AAAA sinon mettre $use_strtotime sur true
	* @param string $time          -> Le temps sous la forme HH:MM:SS, HH:MM.SS, HH.MM.SS, HH-MM-SS ou HH MM SS
	* @param bool   $use_strtotime -> Utiliser la méthode php strtotime pour une date d'un autre format
	* @return string
	*/
	public static function formatDateForMySQL($type, $date, $time = '00:00:00', $use_strtotime = false){
		// Variables
		$out = '000-00-00 00:00:00';
		$type = strtoupper($type);
		$date = trim($date);
		
		// Type MySQL
		if($type == 'DATE'){
			$out = date('Y-m-d', self::dateToTime($date, $use_strtotime));
		}
		else if($type == 'DATETIME'){
			$datetotime = self::dateToTime($date, $use_strtotime);
			$timetosec = self::timeToSec($time);
			$out = date('Y-m-d H:i:s', $datetotime + $timetosec);
		}
		else if($type == 'TIME'){
			$out = date('H:i:s', self::timeToSec($time));
		}
		else if($type == 'YEAR'){
			$out = date('Y', self::dateToTime($date, $use_strtotime));
		}
		else{
			$out = self::formatDateForMySQL('DATETIME', $date, $time, $use_strtotime);
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