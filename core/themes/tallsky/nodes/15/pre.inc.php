<?
class pre extends node{
	private $node;
	private $rid;
	public $r_name;
	
	public function pre($node){
		$this->node = $node;
		$this->rid = $node->vars['rid'];
		$result = $this->node->execute("SELECT * FROM defined_roles WHERE rid = ?", array($this->rid));
		$row = $result->fetch();
		$this->r_name = $row['name'];
		
		if(!empty($_POST['delete_role'])){
			$result = $this->node->execute("DELETE FROM defined_roles WHERE rid = ?", array($this->rid));
			if($result->rowCount()){
				$this->node->status_messages['status'][] =  "User role deleted.";
			}
		}
	}
}
?>