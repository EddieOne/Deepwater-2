<?
class pre extends node{
	private $node;
	public $sort;
	public $ulist;
	
	public function pre($node){
		$this->node = $node;
		$this->sort = $node->vars['sort'];
		
		$result = $this->node->execute("SELECT * FROM user_log LIMIT 100", array());
		while($row = $result->fetch()){
			$this->list = '';
			$views = number_format($row['views']);
			global $root_path;
			include_once $root_path.'/'.$this->node->paths['validation'];
			$start = validation::unix_timestamp_to_human ($row['start'], $format = 'M d Y - H:i');
			$end = validation::unix_timestamp_to_human ($row['end'], $format = 'M d Y - H:i');
			$this->ulist .= <<<EOD
			<div class="three columns">{$row['ip']}</div>
		<div class="one columns">{$row['role']}</div>
		<div class="one columns">{$row['identity']}</div>
		<div class="five columns">{$row['last_page']}</div>
		<div class="one column">$views</div>
		<div class="two columns">$start</div>
		<div class="two columns">$end</div>
		<div class="three columns"> </div>
EOD;
		}
	}
}
?>