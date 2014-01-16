<?php
include_once('authentication.inc.php');
class node extends authentication {
	
	public $nid; // node id
	// meta is an array holding the db data for the node
	public $meta; // [array] column match the array keys
	public $paths; // [array] pre, style, and page paths
	public $site; // [string] the folder in sites that the node resides in
	public $vars; // [array] could contain alias variables and keys
	
	public function __construct($nid = false){
		 parent::__construct();
		// call node(false) to use class functions
		// default method loads node from current url
		$this->nid = $nid;
		if($nid === false){
			if($this->alias_count == 0){
				$nid = $this->nid_from_route('frontpage');
			}else if($this->alias_count == 1){
				$nid = $this->nid_from_route($this->alias_route);
			}else if($this->alias_count > 1){
				$nid = $this->nid_from_alias($this->alias_route, $this->alias_parts, $this->alias_path, $this->alias_count);
			}
			if(empty($nid)){
				$nid = $this->nid_from_route('404');
			}
			// if user does not have read access to node
			if($this->check_read_permission($nid, $this->user_id) == false){
				$nid = $this->access_redirect($nid);
			}
			if($this->editing){
				if($this->check_write_permission($nid, $this->user_id) == false){	
					$nid = $this->access_redirect($nid);
				}else{
					$nid = 3; // admin edit page
				}
			}
			$this->log_user();
		}
		
		$this->nid = $nid;
		$this->meta = $this->get_node_meta($nid);
		$this->site = $this->get_site_path($this->meta['sid']);
		$this->paths = $this->get_node_paths($nid, $this->site);
	}
	// handles access denided. Return nid of alternate page.
	function access_redirect($nid){
		if($nid < 1000){
			$this->status_messages['error'][] =  "Access Denied. Please login to see if you have access.";
			return 2; // admin login page
		}else{
			return $this->nid_from_route('access-denied');
		}
	}

	function get_node_meta($nid){
		$result = $this->execute("SELECT * FROM nodes WHERE nid = ?", array($nid));
		if(!$result){ echo 'no meta found'; return false; }
		return $result->fetch();
	}
	function get_node_paths($nid, $site){
		// main files
		// useful shortcuts
		if($this->nid > 999){
			$paths['site'] = 'sites/'.$this->site;
			$paths['node'] = 'sites/'.$this->site.'/nodes/'.$nid;
			$paths['pre'] = "sites/$site/nodes/$nid/pre.inc.php";
			$paths['style'] = "sites/$site/nodes/$nid/style.css";
			$paths['page'] = "sites/$site/nodes/$nid/page.inc.php";
		}else{
			$paths['site'] = 'core/themes/'.self::$admin_theme;
			$paths['node'] = 'core/themes/'.self::$admin_theme.'/nodes/'.$nid;
			$paths['pre'] = 'core/themes/'.self::$admin_theme.'/nodes/'.$nid.'/pre.inc.php';
			$paths['style'] = 'core/themes/'.self::$admin_theme.'/nodes/'.$nid.'/style.css';
			$paths['page'] = 'core/themes/'.self::$admin_theme.'/nodes/'.$nid.'/page.inc.php';
		}
		$paths['validation'] = 'core/includes/validation.inc.php';
		$paths['base'] = self::$base_url.self::$install_path;
		return $paths;
	}
	function get_site_path($sid){
		$result = $this->execute("SELECT * FROM sites WHERE sid = ? LIMIT 1", array($sid));
		if(!$result){ return $default_site; }
		$row = $result->fetch();
		return $row['site_path'];
	}
	public function nid_from_route($route){
		$result = $this->execute("SELECT nid,alias_route,alias FROM nodes WHERE alias_route = ? AND alias = ? LIMIT 1", array($route, '/'.$route.'/'));
		if(!$result){
			return false;	
		}
		while($row = $result->fetch()){
			return $row['nid'];
		}
	}
	function nid_from_alias($route, $parts, $alias, $count){
		$result = $this->execute("SELECT nid,alias_route,alias FROM nodes WHERE alias_route = ?", array($route));
		while($row = $result->fetch()){
			$r_alias = $row['alias'];
			if(strpos($r_alias, '/') === 0){ $r_alias = substr($r_alias, 1); }
			$r_parts = $this->get_alias_array($r_alias);
			$r_count = count($r_parts);
			if($r_count != $count){
				continue;
			}
			for($i = 0; $i < $count; $i++){
				if($parts[$i] == $r_parts[$i] || strpos($r_parts[$i], '}') !== false){
					if($i + 1 == $count){
						if(strpos($r_parts[$i], '}') !== false){
							$this->get_alias_vars($parts, $r_parts, $count);
						}
						return $row['nid'];
						break;
					}
				}else{
					break;
				}
			}
		}
	}
	function get_alias_vars($parts, $r_parts, $count){
		$vars = array();
		for($i = 0; $i < $count; $i++){
			if(strpos($r_parts[$i], '{') !== false){
				$var_key = str_replace('{', '', $r_parts[$i]);
				$var_key = str_replace('}', '', $var_key);
				$var_value = $parts[$i];
				$vars[$var_key] = $var_value;
			}
		}
		$this->vars = $vars;
	}
	function alias_from_nid($nid){
		$result = $this->execute("SELECT alias FROM nodes WHERE nid = ?", array($nid));
		if(!$result){
			return false;	
		}
		while($row = $result->fetch()){
			return $row['alias'];
		}
	}
	function log_user(){		
		$user_roles = $this->get_user_roles($this->user_id);
		if(count($user_roles) == 1){
			$user_roles = $this->get_role_name($user_roles[0]);
		}else{
			$user_roles = implode(',', $user_roles);
		}
		if($this->user_id === false){
			$spider = $this->spider_check();
			if(!empty($spider)){
				$identity = $spider.' bot';
			}else{
				$identity = 'unknown';
			}
		}else{
			$identity = $this->user_name;
		}
		
		$result = $this->execute("UPDATE user_log SET 
		role = :role, 
		identity = :identity, 
		last_page = :page, 
		views = views + 1, 
		end = :time 
		WHERE ip = :ip AND identity = :identity", array(
		':role' => $user_roles, 
		':identity' => $identity, 
		':page' => $this->alias_path, 
		':time' => $this->time,
		':ip' => $this->user_ip));
		if($result->rowCount() == 0){
			$result = $this->execute("INSERT INTO user_log SET ip = :ip, role = :role, identity = :identity, last_page = :page, views = 1, start = :time, end = :time", 
			array(':ip' => $this->user_ip, ':role' => $user_roles, ':identity' => $identity, ':page' => $this->alias_path, ':time' => $this->time));
		}
		
	}
	public function __set($name, $value){
        $this->$name = $value;
   }
}
?>