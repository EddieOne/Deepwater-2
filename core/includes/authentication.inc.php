<?php
include 'navigator.inc.php';
class authentication extends navigator {
	
	public $time;
	
	protected $user_name = 'Guest'; // username
	protected $name_slug = 'guest'; // lower case, spaces to underscores
	protected $user_id = false;
	protected $token = false;
	protected $user_ip;
	protected $user_lang;
	protected $refferer;
	protected $user_agent;
	protected $user_hostname; // in Apache you'll need HostnameLookups On inside httpd.conf
	protected $banned;
	
	
	public function __construct(){
		parent::__construct();
		$this->time = time();
		$this->user_ip = $_SERVER['REMOTE_ADDR'];
		if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])){ $this->user_lang = $_SERVER['HTTP_ACCEPT_LANGUAGE']; }
		if(isset($_SERVER['HTTP_REFERER'])){ $this->refferer = $_SERVER['HTTP_REFERER']; }
		if(isset($_SERVER['HTTP_USER_AGENT'])){ $this->user_agent = $_SERVER['HTTP_USER_AGENT']; }
		if(isset($_SERVER['REMOTE_HOST'])){ $this->user_hostname = $_SERVER['REMOTE_HOST']; }
		// if user is logged in, save their data to this class for later use
		if(!empty($_SESSION['token'])){
			$this->user_name = $_SESSION['user_name'];
			$this->name_slug = $_SESSION['name_slug'];
			$this->user_id = $_SESSION['user_id'];
			$this->token = $_SESSION['token'];
		}
		
		// display offline message if website is not in online mode
		if(self::$site_status != 'online' && !$this->is_admin($this->user_id)){
			echo self::offline_message;
			exit();
		}
		// adjust base_url if user is using ssl
		if($_SERVER['SERVER_PORT'] == '443'){
			$ssl_url = str_replace("http://","https://",$base_url);
			$ssl_url = $ssl_url.':443/';
			$this->base_url = $ssl_url;
		}
		// Check to see if the user ip is banned
		if(!isset($_SESSION['banned'])){
			$this->banned = $this->ban_check($this->user_ip);	
		}else{
			$this->banned = $_SESSION['banned'];
		}
		
		if ($this->banned === true){
			echo self::$ban_message;
    		exit();
 	 	}
		// User Login from cookie
		if(!$this->logged_in() && isset($_COOKIE["token"])){
			//$loginReturn = $this->cookie_login($_COOKIE["token"]);
		}
		// User Login from POST
		if (!$this->logged_in() && !empty($_POST['login'])){
			$email = $_POST["email"];
			$password = $_POST["pass"];
			$this->login_user($email,$password,$this->current_address());
		}
	}
	public function logged_in(){
		if($this->user_id === false){
			return false;
		}else{
			return true;
		}
	}
	public function login_user($email,$password,$frompage){
		if(empty($email)){
			$this->status_messages['error'][] =  "A valid username must be supplied.";
			return false;
		}
		if(empty($password)){
			$this->status_messages['error'][] = "A valid password must be supplied.";
			return false;
		}
		if(!$this->check_flood(3,"login")){
			$this->status_messages['error'][] = "Too many login attempts, please wait an hour.";
			return false;
		}
		$hashpass = hash('tiger192,3', $this->hash_salt.$password);
		$result = $this->execute("SELECT * FROM users WHERE mail = ? AND user_pass = ?", array($email, $hashpass));
		$user = $result->fetch(PDO::FETCH_OBJ);
		if(!$user){
			$this->add_flood("login",3600);
			$this->status_messages['error'][] = "Invalid Login";
			return false;
		}
		
		$this->user_name = $user->user_name;
		$this->name_slug = $user->name_slug;
		$this->user_id = $user->user_id;
		$this->token = $this->create_token();
		$_SESSION['user_name'] = $user->user_name;;
		$_SESSION['name_slug'] = $user->name_slug;
		$_SESSION['user_id'] = $user->user_id;
		$_SESSION['token'] = $this->token;
		$this->set_login_cookie();
		$result = $this->execute("UPDATE users SET accessed = ?, token = ? WHERE user_id = ?", array($this->time, $this->token, $this->user_id));
		$this->status_messages['status'][] = "Successful login";
		return true;
	}
	public function cookie_login($token){
		include_once 'validation.inc.php';
		if(!$this->check_flood(3,"login")){
			$this->status_messages['error'][] = "Too many login attempts, please wait an hour.";
			$this->redirect($frompage);
		}
		$cookieBits = explode('+', $token);
		$hash = $cookieBits[0];
		$tuid = $cookieBits[1];
		$tuid = validation::conv_base($tuid, 'aeiou948372', '0123456789');
		$name = $this->get_user_from_id($tuid);
		$curHash = hash('tiger192,3', $this->hash_salt.$name.$this->user_ip.$this->hash_salt);
		if($curHash == $hash){
			$result = $this->execute("SELECT user_id,user_name,user_pass,token FROM users WHERE user_id = ? AND token = ?", array($tuid, $token));
			if(!$result){ $this->redirect(); }
			while($row = $result->fetch()){
				$user_name = $row['user_name'];
				$user_pass = $row['user_name'];
			}
			$this->login_user($user_name,$user_pass,$base_url);
		}else{
			$this->add_flood("login",3600);
			$this->unset_login_cookie();
		}
	}
	public function create_token(){
		include_once 'validation.inc.php';
		$userSalt = hash('tiger192,3', $this->hash_salt.$this->user_name.$this->user_ip.$this->hash_salt);
		$uidbase = validation::conv_base($this->user_id, '0123456789', 'aeiou948372');
		return $userSalt.'+'.$uidbase.'+'.time();
	}
	public function set_login_cookie(){
		setrawcookie("token", $this->token, time()+7776000, '/', '', false, true);
	}
	public function unset_login_cookie(){
		setrawcookie("token", '', time() - 42000, '/', '', false, true);
	}
	public function logout_user(){
		$this->user_name = 'Guest'; // username
		$this->name_slug = 'guest'; // lower case, spaces to underscores
		$this->user_id = false;
		$this->token = false;
		session_unset();
		session_destroy();
		$this->redirect($this->paths['base']);
		$this->unsetLoginCookie();
	}
	// check flood attempts by time and count based on event, false = flood detected
	function check_flood($count,$event){
		$result = $this->execute("SELECT * FROM flood_watch WHERE ip = ? AND expire > ?  AND event = ?", array(
		$this->user_ip, $this->time, $event));
		$findings = $result->fetch();
		if(empty($findings)){
			return true;
		}
		$fcount = $result->rowCount();
		$bots = $this->spider_check();
		if($bots != '' || $this->is_admin($this->user_id)){
			return true;
		}
		if($fcount > $count){return false;}else{return true;}
	}
	function add_flood($event,$expiration){
		$this->execute( "INSERT INTO flood_watch SET 
					ip = :ip,
					time = :time,
					expire = :time + :expire,
					event = :event", array(
					':ip' => $this->user_ip,
					':time' => $this->time,
					':expire' => $expiration,
					':event' => $event));
	}
	// This fuction is called to see if a specific IP address is banned
	function ban_check($ipaddress){
		$result = $this->execute("SELECT ip FROM banned WHERE ip = ? LIMIT 1", array($ipaddress));
		$findings = $result->fetch();
		if(empty($findings)){
			return false;
		}else{
			return true;
		}
	}
	// determine if this user is an admin
	function is_admin($uid){
		if($uid === false){
			return false;
		}
		$role = $this->get_user_roles($uid);
		for($i = 0; $i < count($role); $i++){
			// if permission equals visitor's role
			if($role[$i] == 1){
				$isadmin = true;
				break;
			}else{
				$isadmin = false;
			}
		}
		return $isadmin;
	}
	// return all roles that have been created
	function get_defined_roles(){
		$result = $this->execute("SELECT * FROM defined_roles ORDER BY weight ASC", array());
		while($row = $result->fetch()){
			$role[$row['name']] =  $row['rid'];
		}
		return $role;
	}
	// return the visitors role, 1 = administrator, 2 = authenticated, 3 = anonymous
	function get_user_roles($uid){
		if($uid === false){
			$role[] = 3;
		}else{
			$result = $this->execute("SELECT user_id,rid FROM users_roles WHERE user_id = ?", array($uid));
			while($row = $result->fetch()){
				$role[] = $row['rid'];
			}
		}
		return $role;
	}
	function get_role_name($rid){
		$roles = $this->get_defined_roles();
		foreach($roles as $name => $role_id){
			if($role_id == $rid){
				return $name;
				break;
			}
		}
	}
	// get the roles of a node
	function get_node_role($nid, $rid){
		$result = $this->execute("SELECT nid,rid,auth FROM nodes_roles WHERE nid = ? AND rid = ?", array($nid, $rid));
		$permissions = array('read' => false, 'write' => false);
		if(empty($result)){ return $permissions; }
		$row = $result->fetch();
		if($row['auth'] == 'rw'){
			$permissions['read'] = true;
			$permissions['write'] = true;
		}
		if($row['auth'] == 'r'){
			$permissions['read'] = true;
		}
		if($row['auth'] == 'w'){
			$permissions['write'] = true;
		}
		return $permissions;
	}
	//Check visitors role against node read permissions
	function check_read_permission($nid, $uid){
		$user_roles = $this->get_user_roles($uid);
		$accessable = false;
		foreach($user_roles as $rid){
			$finding = $this->get_node_role($nid, $rid);
			if($finding['read'] == true){
				$accessable = true;
				break;
			}
		}
		return $accessable;
	}
	//Check visitors role against node write permissions
	function check_write_permission($nid, $uid){
		$user_roles = $this->get_user_roles($uid);
		$accessable = false;
		foreach($user_roles as $rid){
			$finding = $this->get_node_role($nid, $rid);
			if($finding['write'] == true){
				$accessable = true;
				break;
			}
		}
		return $accessable;
	}
	function get_role_id($role_name){
		$result = $this->execute("SELECT * FROM defined_roles WHERE name = ? LIMIT 1", array($role_name));
		while($row = $result->fetch()){
			$rid = $row['rid'];
		}
	}
	// see if a user has a role
	function user_has_role($uid,$role_name){
		$rid = $this->get_role_id($role_name);
		$result = $this->execute("SELECT * FROM users_roles WHERE user_id = ? AND rid = ? LIMIT 1", array($uid, $rid));
		if(!$result){
			return false;	
		}else{
			return true;
		}
	}
	function get_user_from_id($uid){
		$result = $this->execute("SELECT user_id,user_name FROM users WHERE user_id = ? LIMIT 1", array($uid));
		if(!$result){
			return false;	
		}
		while($row = $result->fetch()){
			$name= $row['user_name'];
			return $name;
		}
	}
	function get_uid_from_name($name){
		$result = $this->execute("SELECT user_id,user_name,name_slug FROM users WHERE name_slug = ? LIMIT 1", array($name));
		if(!$result){
			return false;	
		}
		while($row = $result->fetch()){
			$uid = $row['user_id'];
			return $uid;
		}
	}
	function get_user($uid){
		$result = $this->execute("SELECT * FROM users WHERE user_id = ? LIMIT 1", array($uid));
		if(!$result){
			return false;	
		}
		while($row = $result->fetch()){
			$userContent['user_name'] = $row['user_name'];
			$userContent['name_slug'] = $row['name_slug'];
			$userContent['mail'] = $row['mail'];
			$userContent['created'] = $row['created'];
		}
		return $userContent;
	}
	function spider_check(){
		$content = '';
		$useragent = $_SERVER['HTTP_USER_AGENT'];;
		if(stripos($useragent,"google")==true)
		$content = "google";
		elseif(stripos($useragent,"yahoo")==true)
		$content = "yahoo";
		elseif(stripos($useragent,"msn")==true)
		$content = "msn";

		return $content;

	}
}
?>
