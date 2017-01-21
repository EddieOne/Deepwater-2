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
		// begin seeking the true path
		// filter out query strings
		for($i = 0; $i < $this->alias_count; $i++){
			if(strpos($this->alias_parts[$i], '?') === 0){
				$this->alias_parts = array_slice($this->alias_parts, 0, $i);
				$this->alias_count = count($this->alias_parts);
			}
		}
		if(!empty($this->alias_parts)){ $this->alias_route = $this->alias_parts[0]; }
		
		if(!empty($this->alias_parts) && $this->alias_parts[$this->alias_count - 1] == 'edit'){
			// give alias parts of the node being edited
			$this->alias_parts = array_splice($this->alias_parts, 0, $this->alias_count - 1);
			// count alias parts without edit
			$this->alias_count = count($this->alias_parts);
			$this->editing = true;
		}
	}
	
	function current_address(){
		return "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

	}
	function get_alias($address){
		// ip support
		$directAddress = 'http://'.$_SERVER['SERVER_ADDR'].'/';
		$alias = str_replace($directAddress,"",$address);
		// http, https support
		$alias = str_replace("http://","", $alias);
		$alias = str_replace("https://","", $alias);
		$alias = str_replace(":443", "", $alias);
		$alias = rtrim($alias, "/");
		$parts = explode("/", $alias);
		$parts = array_slice($parts, 1);
		$alias = implode("/", $parts);
		$alias = ltrim($alias, "/");

		return $alias;
	}
	function get_alias_array($alias){
		$aliasarray = explode("/", $alias);
		if(is_array($aliasarray)){ 
			$aliasarray = array_filter($aliasarray, 'strlen');
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
		if(!empty($this->status_messages)){
			if(is_array($_SESSION['status_messages'])){
				$_SESSION['status_messages'] = array_merge($_SESSION['status_messages'], $this->status_messages);
			}else{
				$_SESSION['status_messages'] = $this->status_messages;
			}
		}
		
		header('Location: '.$to); 
		exit();
	}
}
?>