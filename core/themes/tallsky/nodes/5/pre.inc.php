<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
	}
	public function nodes_html(){
		global $root_path;
		include_once $root_path.'/'.$this->node->paths['validation'];
		$code = '';
		$result = $this->node->execute("SELECT nid,sid,vid,user_id,created,changed,type,alias,title FROM nodes ORDER BY nid DESC", array());
		while($page_node = $result->fetch(PDO::FETCH_OBJ)){
			$created = validation::unix_timestamp_to_human ($page_node->created, $format = 'M d Y');
			$changed = validation::unix_timestamp_to_human ($page_node->changed, $format = 'M d Y');
			$edit_link = $this->node->paths['base'].$page_node->alias.'edit/';
			$delete_link = $this->node->paths['base'].'/admin/page/delete/'.$page_node->nid;
			$node_link = $this->node->paths['base'].$page_node->alias;
			$code .= <<<EOD
	<div class="sixteen columns" style="border-bottom:#86939e 1px solid; line-height:40px;">
		<div class="one column">{$page_node->nid}</div>
		<div class="four columns"><a href="$node_link">{$page_node->title}</a></div>
		<div class="three columns">{$page_node->alias}</div>
    	<div class="two columns">{$page_node->type}</div>
		<div class="two columns">$created</div>
		<div class="two columns">$changed</div>
		<div class="two columns"><a href="$edit_link" class="button" style="margin:5px 0;">Edit</a>   
		<a href="$delete_link" class="button" style="margin:5px 0;">Delete</a></div>
	</div>
EOD;
		}
		return $code;
	}
}
?>