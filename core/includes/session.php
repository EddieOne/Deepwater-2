<?
include 'pdo.inc.php';
class session extends pdo_db{
	
	function __construct() {
		// set custom session functions.
		session_set_save_handler(array($this, 'open'), array($this, 'close'), array($this, 'read'), array($this, 'write'), array($this, 'destroy'), array($this, 'gc'));
 
		// Prevents unexpected effects when using objects as save handlers.
		register_shutdown_function('session_write_close');
	}
	
	function start_session($session_name, $secure) {
		// Don't allow javascipt to access sessions.
		$httponly = true;
		$session_hash = 'sha512';
 
		// bits per character, '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
		ini_set('session.hash_bits_per_character', 5);
		// Prevent sessions in address
		ini_set('session.use_only_cookies', 1);
 
		// Get session cookie parameters 
		$cookieParams = session_get_cookie_params(); 
		// Set the parameters
		session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
		// Change the session name 
		session_name($session_name);
		session_start();
		// This line regenerates the session and delete the old one. 
		// It also generates a new encryption key in the database. 
		session_regenerate_id(true);	 
	}

	function open() {
		return true;
	}

	function close() {
		return true;
	}

	function read($id) {
		$result = $this->execute("SELECT data FROM sessions WHERE id = ? LIMIT 1", array($id));
		$findings = $result->fetch(PDO::FETCH_OBJ);
		$data = $findings->data;
		$key = $this->getkey($id);
		$data = $this->decrypt($data, $key);
		return $data;
	}

	function write($id, $data) {
		$key = $this->getkey($id);
		$data = $this->encrypt($data, $key);
 
		$time = time();
		$this->execute("REPLACE INTO sessions (id, set_time, data, session_key) VALUES (?, ?, ?, ?)", array($id, $time, $data, $key));
		return true;
	}
	function destroy($id) {
		$result = $this->execute("DELETE FROM sessions WHERE id = ?", array($id));
		return true;
	}
	function gc($max) {
		$old = time() - $max;
		$result = $this->execute("DELETE FROM sessions WHERE set_time < ?", array($old));
		return true;
	}
	private function getkey($id) {
		$this->key_stmt = $this->db->prepare("SELECT session_key FROM sessions WHERE id = ? LIMIT 1");
		$result = $this->execute("SELECT session_key FROM sessions WHERE id = ? LIMIT 1", array($old));
		if($result->rowCount()){
			$findings = $result->fetch(PDO::FETCH_OBJ);
			$key = $findings->session_key;
			return $key;
		}else{
			$random_key = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
			return $random_key;
		}
	}
	private function encrypt($data, $key) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $data, MCRYPT_MODE_ECB, $iv));
		return $encrypted;
	}
	private function decrypt($data, $key) {
		$salt = 'cH!swe!retReGu7W6bEDRup7usuDUh9THeD2CHeGE*ewr4n39=E@rAsp7c-Ph@pH';
		$key = substr(hash('sha256', $salt.$key.$salt), 0, 32);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, base64_decode($data), MCRYPT_MODE_ECB, $iv);
		return $decrypted;
	}
}