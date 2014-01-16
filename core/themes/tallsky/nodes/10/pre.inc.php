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
			if(rmdir('sites/'.$row['site_path'])){
				$result = $this->node->execute("DELETE FROM sites WHERE sid = ?", array($this->sid));
				if($result->rowCount()){
					$this->node->status_messages['status'][] =  "Site profile deleted.";
				}
			}else{
				$this->node->redirect($this->node->paths['base'].'/admin/site/');
				$this->node->status_messages['error'][] =  "Unable to delete site directory.";
			}
		}
	}
}
?>