<?
class pre extends node{
	private $node;
	private $rid;
	public $r_name = '';
	public $r_weight = '';
	
	public function pre($node){
		$this->node = $node;
		// get and check table column id
		$this->rid = $node->vars['rid'];
		if(empty($this->rid)){
			$this->node->status_messages['error'][] =  "The role could not be found.";
			return false;
		}
		// update the table if post data was found
		if(!empty($_POST['modify_role'])){
			if(empty($_POST['name'])){
				$this->node->status_messages['error'][] =  "The name field is required.";
				return false;
			}
			$result = $this->node->execute("UPDATE defined_roles SET name = ?, weight = ? WHERE rid = ?", array($_POST['name'], $_POST['weight'], $this->rid));
			if($result->rowCount()){
				$this->r_name = $_POST['name'];
				$this->r_weight = $_POST['weight'];
				$this->node->status_messages['status'][] =  "Role has been updated.";
				return true;
			}
		}
		// fill in the text field vars 
		$result = $this->node->execute("SELECT * FROM defined_roles WHERE rid = ? LIMIT 1", array($this->rid));
		$row = $result->fetch();
		$this->r_name = $row['name'];
		$this->r_weight = $row['weight'];
	}
}
?>