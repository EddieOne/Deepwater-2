<?
class emailer {
	public static function send_mail($to, $subject, $message, $from = null, $html = false){
		if(!$from){
			if(property_exists('configuration', 'machine_email')){
				$from = configuration::$machine_email;
			}else{
				$hostname = parse_url(configuration::$base_url, PHP_URL_HOST);
				$from = 'machine@'.$hostname;
			}
		}
		$headers   = array();
		$headers[] = "MIME-Version: 1.0";
		if($html){
			$headers[] = "Content-type: text/html; charset=iso-8859-1";
		}else{
			$headers[] = "Content-type: text/plain; charset=iso-8859-1";
		}
		$headers[] = "From: {$from}";
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		mail($to, $subject, $message, implode("\r\n", $headers));
	}
}
?>