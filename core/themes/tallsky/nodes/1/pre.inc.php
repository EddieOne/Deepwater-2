<?
class pre extends node{
	public $n_title = '';
	public $n_alias = '';
	public $n_type = '';
	private $node;
	
	public function pre($node){
		$this->node = $node;
		if(!empty($_POST['new_page'])){
			$this->n_title = $_POST['title'];
			$this->n_alias = $_POST['alias'];
			$this->n_type = $_POST['type'];
			$this->create_node($this->n_title, $this->n_alias, $this->n_type);
		}
	}
	function create_node($n_title, $n_alias, $n_type){
		if(empty($n_title) || empty($n_alias) || empty($n_type)){
			$this->node->status_messages['error'][] =  "All fields are required.";
			return false;
		}
		$result = $this->node->execute("SELECT nid,alias FROM nodes WHERE alias = ?", array($n_alias));
		$findings = $result->fetch();
		if(!empty($findings)){
			$this->node->status_messages['error'][] =  "Duplicate alias found.";
			return false;
		}
		$parts = explode('/', $n_alias);
		if(is_array($parts)){
			$route = $parts[1];
		}else{
			$route = str_replace('/', '', $n_alias);
		}

		// find what nid to give the ndoe
		if($n_type == 'core'){
			$result = $this->node->execute("SELECT nid FROM nodes WHERE nid < 1000 ORDER BY nid DESC LIMIT 1");
		}else{
			$result = $this->node->execute("SELECT nid FROM nodes ORDER BY nid DESC LIMIT 1");
		}
		$lastId = $result->fetch(PDO::FETCH_NUM);
		$nid = intval($lastId[0]) + 1;
		// create node database entry
		$result = $this->node->execute( "INSERT INTO nodes SET 
					nid = :nid,
					sid = 1,
					vid = 0.001,
					user_id = :owner,
					status = 0,
					created = :time,
					changed = :time,
					type = :type,
					alias_route = :route,
					alias = :alias,
					title = :title", array(
					':nid' => $nid,
					':owner' => $this->node->user_id,
					':time' => $this->node->time,
					':type' => $this->n_type,
					':route' => $route,
					':alias' => $n_alias,
					':title' => $n_title));
		if($result->rowCount()){
			// give admin read and write permission
			$result2 = $this->node->execute( "INSERT INTO nodes_roles SET nid = ?, rid = ?, auth = ?", array($nid, 1, 'rw'));
			if($result2->rowCount()){
				// create node folder and page.php file
				if($n_type == 'core'){
					mkdir('core/themes/'.self::$admin_theme.'/nodes/'.$nid, 0755);
					touch('core/themes/'.self::$admin_theme.'/nodes/'.$nid.'/page.inc.php');
					chmod('core/themes/'.self::$admin_theme.'/nodes/'.$nid.'/page.inc.php', 0644);
				}else{
					mkdir("sites/$site/nodes/$nid", 0755);
					touch("sites/$site/nodes/$nid/page.inc.php");
					chmod("sites/$site/nodes/$nid/page.inc.php", 0644);
				}
				// redirect to edit node
				$this->node->redirect($this->node->paths['base'].$n_alias.'edit');
			}
		}
	}
}
?>