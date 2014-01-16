<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
		
		if(!empty($_POST['new_role'])){
				$result = $this->node->execute("INSERT INTO defined_roles SET name = ?, weight = ?", 
											   array($_POST['n_role_name'], $_POST['n_role_weight']));
			if($result->rowCount()){
				$this->node->status_messages['status'][] =  "New Role Created.";
			}
		}
	}
	public function roles_html(){
		$code = '';
		$result = $this->node->execute("SELECT * FROM defined_roles ORDER BY weight ASC", array());
		while($role = $result->fetch(PDO::FETCH_OBJ)){
			$edit_link = $this->node->paths['base'].'/admin/user/roles/modify/'.$role->rid.'/';
			$delete_link = $this->node->paths['base'].'/admin/user/roles/delete/'.$role->rid.'/';
			
			$code .= <<<EOD
	<div class="six columns" style="font-size:17px; margin:6px 0 0 0;">{$role->name}</div>
	<div class="four columns" style="font-size:17px; margin:6px 0 0 0;">{$role->weight}</div>
	<div class="six columns">
		<a href="$edit_link" class="button">Edit</a>
		<a href="$delete_link" class="button">Delete</a>
	</div>
EOD;
		}
		return $code;
	}
}
?>