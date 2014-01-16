<?php
include 'pdo.inc.php';
class navigator extends pdo_db {
	
	public $current_address; // http://example.com/route/extra/stuff/
	public $alias_route; // route
	public $alias_path; // /route/extra/stuff/
	public $alias_parts; // array of path
	public $alias_count; // array count
	protected $editing = false;
	
	public function __construct(){
		parent::__construct();
		$this->current_address = $this->current_address();
		$this->alias_path = $this->get_alias($this->current_address);
		$this->alias_parts = $this->get_alias_array($this->alias_path);
		$this->alias_count = count($this->alias_parts);
		if(!empty($this->alias_parts)){ $this->alias_route = $this->alias_parts[0]; }
		// begin seeking the true path
		if(!empty($this->alias_parts) && $this->alias_parts[$this->alias_count - 1] == 'edit'){
			// give alias parts of the node being edited
			$this->alias_parts = array_splice($this->alias_parts, 0, $this->alias_count - 1);
			// count alias parts without edit
			$this->alias_count = count($this->alias_parts);
			$this->editing = true;
		}
	}
	
	function current_address(){
		$pageURL = 'http';
		if(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	function get_alias($address){
		$alias = $address;
		// ip support
		$directAddress = 'http://'.$_SERVER['SERVER_ADDR'].'/';
		$alias = str_replace($directAddress,"",$alias);
		// ssl support
		$ssl_url = str_replace("http://","https://",self::$base_url);
		$ssl_url = substr_replace($ssl_url ,"",-1);
		$ssl_url = $ssl_url.':443/';
		$alias = str_replace($ssl_url,"",$alias);
		// http support
		$alias = str_replace(self::$install_path, '', $alias);
		$alias = str_replace(self::$base_url.'/','',$alias);
		return $alias;
	}
	function get_alias_array($alias){
		$aliasarray = explode("/", $alias);
		if(is_array($aliasarray)){ 
			$aliasarray = array_filter($aliasarray);
		}else{
			$aliasarray = array();
		}
		return $aliasarray;
	}
	// return a portion of any alias, useful for searching down aliases from right to left
	function get_sub_alias($alias,$num){
		//fix front  page errors
		if(empty($alias)){return;}
		$alias = substr($alias, 0, -1);
		$aliasArray = explode("/", $alias);
		$aliasArrayCount = count($aliasArray);
		for($i=0;$i<$num;$i++){
			if(!empty($aliasArray[$i])){
				$subAliasArray[$i] = $aliasArray[$i];
			}
		}
		$subAlias = implode("/", $subAliasArray);
		return $subAlias;
	}
	// are the last 4 latters of url alias "edit"
	function is_edit(){
		$aliasarray = $this->get_alias_array();
		$lastarg = count($aliasarray);
		if($aliasarray[$lastarg-1] == "edit"){
			return true;
		}else{
			return false;
		}
	}
	function redirect($to){
		if(empty($to)){ $to = self::$base_url; }
		header('Location: '.$to); 
		exit();
	}
}
?>