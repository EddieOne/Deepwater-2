<?
class pre extends node{
	private $node;
	public $uid;
	public $u_name;
	
	public function pre($node){
		$this->node = $node;
		$this->uid = $node->vars['uid'];
		$result = $this->node->execute("SELECT user_id,user_name FROM users WHERE user_id = ?", array($this->uid));
		$row = $result->fetch();
		$this->u_name = $row['user_name'];
		
		if(!empty($_POST['delete_user'])){
			$result = $this->node->execute("DELETE FROM users WHERE user_id = ?", array($this->uid));
			if($result->rowCount()){
				$this->node->status_messages['status'][] =  "User account deleted.";
			}
		}
	}
}
?>