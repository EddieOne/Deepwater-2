<?
class pre extends node{
	public $node;
	public $user_count;
	public $weekly_users;
	
	public function pre($node){
		$this->node = $node;
		
		global $root_path;
		include_once $root_path.'/'.$this->node->paths['validation'];
		$this->mem = validation::bytes_to_human(memory_get_usage());
		$this->mem_peak = validation::bytes_to_human(memory_get_peak_usage());
		$this->mem_limit = str_replace('M', '', ini_get('memory_limit'));
		
		$this->avr_load();
		
		$result = $node->execute("SELECT count(user_id) FROM users", array());
		$row = $result->fetch();
		$this->user_count = $row[0];
		
		$past_week = $node->time - 604800;
		$result = $node->execute("SELECT count(user_id),created FROM users WHERE created > ?", array($past_week));
		$row = $result->fetch();
		$this->weekly_users = $row[0];
	}
	function proc_stats(){   
		$fp=fopen("/proc/stat","r");
		if(false===$fp)
				return false;
		$a=explode(' ',fgets($fp));
		array_shift($a); //get rid of 'cpu'
		while(!$a[0])
			array_shift($a); //get rid of ' '
		fclose($fp);
		return $a;
	}
	function avr_load(){
		$this->a = $this->proc_stats();
		sleep(1);
		$this->b = $this->proc_stats();
		$this->total=array_sum($this->b)-array_sum($this->a);
		$this->loadavg = round(100* (($this->b[0]+$this->b[1]+$this->b[2]) - ($this->a[0]+$this->a[1]+$this->a[2])) / $this->total, 2);
   		$this->iowait = round(100* ($this->b[4] - $this->a[4])/$this->total,2);
	}
}
?>