<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
	}
	public function sites_html(){
		$code = '';
		$result = $this->node->execute("SELECT * FROM sites ORDER BY sid DESC", array());
		while($site = $result->fetch(PDO::FETCH_OBJ)){
			$edit_link = $this->node->paths['base'].'/admin/site/modify/'.$site->sid.'/';
			$delete_link = $this->node->paths['base'].'/admin/site/delete/'.$site->sid.'/';
			$owner = $this->node->get_user($site->user_id);
			
			$code .= <<<EOD
	<div class="two columns">{$site->site_name}</div>
	<div class="two columns">{$site->site_path}</div>
    <div class="two columns">{$owner['user_name']}</div>
	<div class="ten columns">
		<a href="$edit_link" class="button">Edit</a>   
		<a href="$delete_link" class="button">Delete</a>
	</div>
EOD;
		}
		return $code;
	}
}
?>