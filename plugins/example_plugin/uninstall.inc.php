<?
class uninstall extends pre{
	public $node;
	public $pre;
	
	
	public function uninstall($node, $pre){
		$this->node = $node;
		$this->pre = $pre;
	}
	public function start_uninstall(){
		// get the node id of the plugin page
		$nid = $this->node->nid_from_simple_alias('/testimonial/submit/');
		// delete permissions to the page
		$result = $this->node->execute("DELETE FROM nodes_roles WHERE nid = ?", array($nid));
		// delete page record
		$result = $this->node->execute("DELETE FROM nodes WHERE nid = ?", array($nid));
		// delete the files for the testimonial page
		$site = $this->node->get_site_path(1); // normally default site
		global $root_path;
		include_once $root_path.'/'.$this->node->paths['filesystem'];
		if(!filesystem::del_dir("sites/$site/nodes/$nid")){
			return false;
		}else{
			return true;
		}
	}
}
?>