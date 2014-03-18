<?php
// validates, sanetizes, and manipulates objects and strings
class validation {
	// check if user input is in the proper format, MUST DO FOR ALL USER INPUT
	static public function classify($string, $expected){
		if($expected == 'web address' || $expected == 'any' ){
			if(preg_match("@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@", $string)){
				return 'web address';
			}
		}else if($expected == 'numeric' || $expected == 'any' ){
			if(preg_match("/^(0|[1-9][0-9]*)$/", $string)){
				return 'numeric';
			}
		}else if($expected == 'alphanumeric' || $expected == 'any' ){
			if(preg_match("/^[a-zA-Z0-9]+$/", $string)){
				return 'alphanumeric';
			}
		}else if($expected == 'ipv4' || $expected == 'any' ){
			if(preg_match("/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/", $string)){
				return 'ipv4';
			}
		}else if($expected == 'hostname' || $expected == 'any' ){
			if(preg_match("/^(([a-zA-Z0-9]+([\-])?[a-zA-Z0-9]+)+(\.)?)+[a-zA-Z]{2,6}$/", $string)){
				return 'hostname';
			}
		}else if($expected == 'numeric' || $expected == 'any' ){
			if(preg_match("/^(0|[1-9][0-9]*)$/", $string)){
				return 'numeric';
			}
		}
	}
	static public function remove_http($url = ''){
		return(str_replace(array('http://','https://'), '', $url));
	}
	static public function remove_www($url = ''){
		return(str_replace('www.', '', $url));
	}
	static public function make_friendly($string){
		$string = strtolower(trim($string));
		// make all spaces single spaces
		$string = preg_replace('!\s+!', ' ', $string);
		// replace spaces with -
		$string = str_replace(" ", "-", $string);
		// remove all characters except spaces, a-z and 0-9 and -
		$string = preg_replace("/[^a-z0-9-]+/i", "", $string);

		return $string;
	}
	// number base converter
	static public function conv_base($numberInput, $fromBaseInput, $toBaseInput){
		if ($fromBaseInput==$toBaseInput) return $numberInput;
		$fromBase = str_split($fromBaseInput,1);
		$toBase = str_split($toBaseInput,1);
		$number = str_split($numberInput,1);
		$fromLen=strlen($fromBaseInput);
		$toLen=strlen($toBaseInput);
		$numberLen=strlen($numberInput);
		$retval='';
		if ($toBaseInput == '0123456789'){
			$retval=0;
			for ($i = 1;$i <= $numberLen; $i++)
				$retval = bcadd($retval, bcmul(array_search($number[$i-1], $fromBase),bcpow($fromLen,$numberLen-$i)));
				return $retval;
			}
		if ($fromBaseInput != '0123456789')
			$base10 = $this->conv_base($numberInput, $fromBaseInput, '0123456789');
		else
			$base10 = $numberInput;
		if ($base10<strlen($toBaseInput))
			return $toBase[$base10];
		while($base10 != '0'){
			$retval = $toBase[bcmod($base10,$toLen)].$retval;
			$base10 = bcdiv($base10,$toLen,0);
		}
		return $retval;
	}
	//determin the time deference from $time and now, and displays in human readable format
	static public function time_difference($time){
		$now = time();
		$diff = $now-$time; 
		$days = intval($diff/24/60/60); 
		if($days<1){
			$hours=intval($diff/3600);
			if($hours<1){
				$mins=intval($diff/60);
				if($mins<1){$secs=$diff;}
			}
		}
		if($days>=1){if($days>1){return $days." days";}else{return $days." day";}}
		if($hours>=1){if($hours>1){return $hours." hours";}else{return $hours." hour";}}
		if($mins>1){return $mins." minutes";}elseif($mins<=1){return $secs." seconds";}
	}
	static public function unix_timestamp_to_human ($timestamp = "", $format = 'D M d Y - H:i:s'){
		if (empty($timestamp) || ! is_numeric($timestamp)) $timestamp = time();
		return ($timestamp) ? date($format, $timestamp) : date($format, $timestamp);
	}
	// convert bytes to human readable format
	static public function bytes_to_human($size){
		$unit=array('b','kb','mb','gb','tb','pb');
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
	// for use on strings to matching regex (comments, etc..)
	static public function strip_string($q){
		$q = filter_var($q, FILTER_SANITIZE_STRING);
		$q = strip_tags ($q);
		return $q;
	}
	static public function neutralize_string($q){
		$q = htmlspecialchars($q);
		return filter_var($q, FILTER_SANITIZE_STRING);
	}
}
?>