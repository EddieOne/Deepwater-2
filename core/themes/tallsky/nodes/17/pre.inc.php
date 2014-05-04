<?
class pre extends node{
	public $node;
	public $uid;
	public $user;
	
	public function pre($node){
		$this->node = $node;
		$this->uid = $node->vars['uid'];
		if(empty($this->uid)){
			$this->node->status_messages['error'][] =  "The user could not be found.";
			return false;
		}
		// modify roles post data
		if(!empty($_POST['modify_roles'])){
			$result = $this->node->execute("DELETE FROM users_roles WHERE user_id = ?", array($this->uid));
			foreach($_POST['permissions'] as $rid => $value){
				if($value == 'true'){
					$result = $this->node->execute("INSERT INTO users_roles SET user_id = ?, rid = ?", array($this->uid,$rid));	
				}
			}
			$this->node->status_messages['status'][] = 'Roles successfully modified.';
			
		}
		// change user post data
		if(!empty($_POST['modify_user'])){
			if(empty($_POST['user_name']) || empty($_POST['mail']) || empty($_POST['status'])){
				$this->node->status_messages['error'][] =  "Name, pass, mail, and status are required.";
				return false;
			}
			global $root_path;
			include_once $root_path.'/'.$this->node->paths['validation'];					  
			$name_slug = validation::make_friendly($_POST['user_name']);
			$hash_pass = hash('tiger192,3', $this->node->hash_salt.$_POST['user_pass']);
			if(strlen($_POST['user_pass']) == 0){
				$result = $this->node->execute("SELECT user_id,user_pass FROM users WHERE user_id = ?", array($this->uid));
				$row = $result->fetch(PDO::FETCH_OBJ);
				$hash_pass = $row->user_pass;
			}
			
			$result = $this->node->execute("UPDATE users SET 
			user_name = :user, 
			name_slug = :slug, 
			user_pass = :pass,
			mail = :mail, 
			status = :status, 
			profile = :profile, 
			notify = :notify,
			misc = :misc 
			WHERE user_id = :uid", array(
			':user' => $_POST['user_name'], 
			':slug' => $name_slug, 
			':pass' => $hash_pass, 
			':mail' => $_POST['mail'], 
			':status' => $_POST['status'], 
			':profile' => $_POST['profile'], 
			':notify' => $_POST['notify'],
			':misc' => $_POST['misc'],
			':uid' => $this->uid));
			
			if(empty($result)){ return false; }
			if($result->rowCount()){
				$this->node->status_messages['status'][] = "User account modified.";
			}
		}
		$result2 = $this->node->execute("SELECT * FROM users WHERE user_id = ?", array($this->uid));
		$this->user = $result2->fetch(PDO::FETCH_OBJ);
		$this->user->user_pass = '';
		if(empty($this->user)){
			$this->node->status_messages['status'][] = "User not found.";
		}
	}
}
?>