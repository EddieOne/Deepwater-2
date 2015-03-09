<?
class install extends pre{
	public $node;
	public $pre;
	
	
	public function install($node, $pre){
		$this->node = $node;
		$this->pre = $pre;
	}
	public function start_install(){
		$this->create_page('Testimonial Submit', 'testimonial', '/testimonial/submit/', 'testimonials');
		// get the node id of the new page
		$nid = $this->node->nid_from_simple_alias('/testimonial/submit/');
		if(!$nid){ return false; }
		// get the defined role id for registered users
		$registered_rid = $this->node->get_role_id('registered');
		$admin_rid = $this->node->get_role_id('admin');
		if(!$registered_rid || !$admin_rid ){ return false; }
		// add read permission for registered users and admins to new page
		$result = $this->node->execute("INSERT INTO nodes_roles SET nid = ?, rid = ?, auth = ?", array($nid, $registered_rid, 'r'));
		$result2 = $this->node->execute("INSERT INTO nodes_roles SET nid = ?, rid = ?, auth = ?", array($nid, $admin_rid, 'rw'));
		if(!$result || !$result2){ return false; }
		// copy page templates from plugin folder to new page location
		$site = $this->node->get_site_path(1); // normally default site
		if(!is_dir("sites/$site/nodes/$nid")){ mkdir("sites/$site/nodes/$nid", 0755); }
		copy("plugins/{$this->pre->config->name_slug}/testimonial_page.inc.php", "sites/$site/nodes/$nid/page.inc.php");
		chmod("sites/$site/nodes/$nid/page.inc.php", 0644);
		copy("plugins/{$this->pre->config->name_slug}/testimonial_pre.inc.php", "sites/$site/nodes/$nid/pre.inc.php");
		chmod("sites/$site/nodes/$nid/pre.inc.php", 0644);
		return true;
	}
	public function create_page($title, $route, $alias, $type){
		$this->node->execute( "INSERT INTO nodes SET 
			sid = 1,
			vid = 0.001,
			user_id = 1,
			status = 1,
			created = :time,
			changed = :time,
			type = :type,
			alias_route = :route,
			alias = :alias,
			title = :title", array(
			':time' => $this->node->time,
			':type' => $type,
			':route' => $route,
			':alias' => $alias,
			':title' => $title));
	}
}
?>