<?
class pre extends node{
	public $s_name = '';
	public $s_path = '';
	public $s_owner = '';
	private $node;
	
	public function pre($node){
		$this->node = $node;
		if(!empty($_POST['new_site'])){
			$this->s_name = $_POST['name'];
			$this->s_path = $_POST['path'];
			$this->s_owner = $_POST['owner'];
			$this->create_site($this->s_name, $this->s_path, $this->s_owner);
		}
	}
	function create_site($s_name, $s_path, $s_owner){
		// check that all fields are not empty
		if(empty($s_name) || empty($s_path) || empty($s_owner)){
			$this->node->status_messages['error'][] =  "All fields are required.";
			return false;
		}
		// check that there is not a path duplicate
		$result = $this->node->execute("SELECT site_name,site_path FROM sites WHERE site_path = ?", array($s_path));
		$findings = $result->fetch();
		if(!empty($findings)){
			$this->node->status_messages['error'][] =  "Duplicate site path found.";
			return false;
		}
		// insert new site profile
		$result = $this->node->execute("INSERT INTO sites SET user_id = ?, site_name = ?, site_path = ?", array($s_owner, $s_name, $s_path));
		if($result->rowCount()){
			if(mkdir("sites/$s_path", 0755)){
				mkdir("sites/$s_path/nodes", 0755);
				//@TODO create template files and allow editing of those files through deepwater
				$this->node->status_messages['status'][] =  "New site profile created.";
				$this->node->redirect($this->node->paths['base'].'/admin/site/');
			}else{
				$this->node->status_messages['error'][] =  "Site directory could not be created.";
			}
		}
	}
}
?>