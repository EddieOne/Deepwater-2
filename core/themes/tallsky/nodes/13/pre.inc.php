<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
		if(!empty($_POST['create_user'])){
			if(empty($_POST['user_name']) || empty($_POST['user_pass']) || empty($_POST['mail']) || empty($_POST['status'])){
				$this->node->status_messages['error'][] =  "Name, pass, mail, and status are required.";
				return false;
			}
			$result = $this->node->execute("SELECT user_id,mail FROM users WHERE mail = ?", array($_POST['mail']));
			$findings = $result->fetch();
			if(!empty($findings)){
				$this->node->status_messages['error'][] =  "Found duplicate email address.";
				return false;
			}
			global $root_path;
			include_once $root_path.'/'.$this->node->paths['validation'];					  
			$name_slug = validation::make_friendly($_POST['user_name']);
			$hash_pass = hash('tiger192,3', $this->node->hash_salt.$_POST['user_pass']);
			
			$result = $this->node->execute("INSERT INTO users SET 
			user_name = :user, 
			name_slug = :slug, 
			user_pass = :pass, 
			mail = :mail, 
			created = :time, 
			status = :status, 
			notify = :notify", array(
			':user' => $_POST['user_name'], 
			':slug' => $name_slug, 
			':pass' => $hash_pass, 
			':mail' => $_POST['mail'], 
			':time' => $this->node->time, 
			':status' => $_POST['status'], 
			':notify' => $_POST['notify']));
			
			if($result->rowCount()){
				$this->node->status_messages['status'][] = "New user created.";
			}
		}
	}
}
?>