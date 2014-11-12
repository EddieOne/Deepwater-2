<?
class pre extends node{
	public $s_name = '';
	public $s_path = '';
	public $s_owner = '';
	private $node;
	private $sid;
	
	public function pre($node){
		$this->node = $node;
		$this->sid = $node->vars['sid'];
		$result = $this->node->execute("SELECT * FROM sites WHERE sid = ?", array($this->sid));
		while($row = $result->fetch()){
			$this->s_name = $row['site_name'];
			$this->s_path = $row['site_path'];
			$this->s_owner = $row['user_id'];
		}
		
		if(!empty($_POST['modify_site'])){
			$this->modify_site($_POST['name'], $_POST['path'], $_POST['owner']);
		}
	}
	private function modify_site($s_name, $s_path, $s_owner){
		// check that all fields are not empty
		if(empty($s_name) || empty($s_path) || empty($s_owner)){
			$this->node->status_messages['error'][] =  "All fields are required.";
			return false;
		}
		// update site profile
		$result = $this->node->execute("UPDATE sites SET user_id = ?, site_name = ?, site_path = ? WHERE sid = ?", array($s_owner, $s_name, $s_path, $this->sid));
		if($result->rowCount()){
			$rename_result = rename('sites/'.$this->s_path, "sites/$s_path");
			if($rename_result === false){
				$this->node->status_messages['error'][] =  "Site directory could not be renamed.";
			}else{
				$this->node->redirect($this->node->paths['base'].'/admin/site/');
				$this->node->status_messages['status'][] =  "Site directory was modified.";
			}
		}
	}
	public function get_files(){	
		$dh  = opendir('sites/'.$this->s_path);
		while (false !== ($filename = readdir($dh))) {
			if($filename != '.' && $filename != '..' && !is_dir('sites/'.$this->s_path.'/'.$filename)){
    			$files[] = $filename;
			}
		}
		sort($files);
		return $files;
	}
}
?>