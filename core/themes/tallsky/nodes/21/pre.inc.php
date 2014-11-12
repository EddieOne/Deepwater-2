<?
class pre extends node{
	public $node;
	public $file;
	public $filename;
	public $ext;
	public $mode;
	
	public function pre($node){
		$this->node = $node;
		$this->site_path = $node->vars['site'];
		$this->filename = $node->vars['file'];
		$node->meta['title'] = 'Modify '.$this->filename;
		
		// update file
		if(!empty($_POST['update_file'])){
			$page_result = file_put_contents('sites/'.$this->site_path.'/'.$this->filename, $_POST['file']);
			chmod('sites/'.$this->site_path.'/'.$this->filename, 0644);
			$this->node->status_messages['status'][] = 'File updated.';
			$this->node->redirect($this->node->current_address);
		}
		
		$this->file = file_get_contents('sites/'.$this->site_path.'/'.$this->filename);
		$path_parts = pathinfo($this->filename);
		$this->ext = $path_parts['extension'];
		if($this->ext == 'php'){
			$this->mode = 'application/x-httpd-php';
		}else if($this->ext == 'css'){
			$this->mode = 'text/css';
		}
	}
}
?>