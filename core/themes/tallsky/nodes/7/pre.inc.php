<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
	}
	public function users_html(){
		global $root_path;
		include_once $root_path.'/'.$this->node->paths['validation'];
		$code = '';
		$result = $this->node->execute("SELECT * FROM users ORDER BY user_id DESC LIMIT 100", array());
		while($user = $result->fetch(PDO::FETCH_OBJ)){
			$edit_link = $this->node->paths['base'].'/admin/user/modify/'.$user->user_id.'/';
			$delete_link = $this->node->paths['base'].'/admin/user/delete/'.$user->user_id.'/';
			$created = validation::unix_timestamp_to_human ($user->created, 'M d Y');
			$accessed = validation::unix_timestamp_to_human ($user->accessed, 'M d Y');
			
			$code .= <<<EOD
	<div class="one column">{$user->user_id}</div>
	<div class="two columns">{$user->user_name}</div>
    <div class="three columns">{$user->mail}</div>
	<div class="two columns">$created</div>
	<div class="two columns">$accessed</div>
	<div class="two columns"><a href="$edit_link" class="button">Edit</a>   <a href="$delete_link" class="button">Delete</a></div>
	<div class="two columns offset-by-four"></div>
EOD;
		}
		return $code;
	}
}
?>