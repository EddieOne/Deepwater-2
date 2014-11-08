<?
class pre extends node{
	private $node;
	private $sid;
	public $s_name;
	
	public function pre($node){
		$this->node = $node;
		$this->sid = $node->vars['sid'];
		$result = $this->node->execute("SELECT * FROM sites WHERE sid = ?", array($this->sid));
		$row = $result->fetch();
		$this->s_name = $row['site_name'];
		
		if(!empty($_POST['delete_site'])){
			if(!$this->rrmdir('sites/'.$row['site_path'])){
				$this->node->status_messages['error'][] =  "Unable to delete the site files.";
			}
			$result = $this->node->execute("DELETE FROM sites WHERE sid = ?", array($this->sid));
			if($result->rowCount()){
				$this->node->status_messages['status'][] =  "Site database entry deleted.";
			}else{
				$this->node->status_messages['error'][] =  "Unable to delete site database entry.";
			}
		}
	}
	private function rrmdir($dir){ 
  		foreach(glob($dir . '/*') as $file){ 
    		if(is_dir($file)) rrmdir($file); else unlink($file); 
		}
  	}
}
?>