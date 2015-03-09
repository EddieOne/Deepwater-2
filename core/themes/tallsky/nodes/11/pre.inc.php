<?
class pre extends node{
	private $node;
	private $enid;
	public $d_node;
	
	public function pre($node){
		$this->node = $node;
		$this->enid = $node->vars['nid'];
		$this->d_node = new node($this->enid);
		
		if(!empty($_POST['delete_node'])){
			if(!$this->rrmdir($this->d_node->paths['site'].'/nodes/'.$this->enid)){
				$this->node->status_messages['error'][] =  "Unable to delete the page files.";
			}
			$result1 = $this->node->execute("DELETE FROM nodes WHERE nid = ?", array($this->enid));
			$result2 = $this->node->execute("DELETE FROM nodes_roles WHERE nid = ?", array($this->enid));
			if($result1->rowCount() && $result2->rowCount()){
				$this->node->status_messages['status'][] =  "Page has been deleted.";
			}else{
				//$this->node->redirect($node->current_address);
				$this->node->status_messages['error'][] =  "Unable to delete the database metadata for the page.";
			}
		}
	}
	// TODO replace with filesystem::del_dir
	private function rrmdir($dir){ 
  		foreach(glob($dir . '/*') as $file){ 
    		if(is_dir($file)) rrmdir($file); else unlink($file); 
		}
  	}
}
?>