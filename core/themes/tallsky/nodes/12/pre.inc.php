<?
class pre extends node{
	public $node;
	public $core;
	
	public function pre($node){
		global $root_path;
		$this->node = $node;
		$this->core = array();
		$this->core['pdo'] = file_get_contents($root_path.'/core/includes/pdo.inc.php');
		$this->core['navigator'] = file_get_contents($root_path.'/core/includes/navigator.inc.php');
		$this->core['auth'] = file_get_contents($root_path.'/core/includes/authentication.inc.php');
		$this->core['node'] = file_get_contents($root_path.'/core/includes/node.inc.php');
		$this->core['validation'] = file_get_contents($root_path.'/core/includes/validation.inc.php');
	}
}
?>