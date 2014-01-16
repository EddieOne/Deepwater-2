<?
class pre extends node{
	public $node;
	public $edit_alias = '';
	public $enid = ''; // node id to edit
	public $edit_node;
	public $en_pre = '';
	public $en_page = '';
	
	public function pre($node){
		$this->node = $node;
		// remove edit from the alias path
		$this->edit_alias = substr('/'.$node->alias_path, 0, -4);
		// find the edit node id with the alias info of the original node
		$this->enid = $node->nid_from_alias($node->alias_route, $node->alias_parts, $this->edit_alias, $node->alias_count);
		// node being edited is loaded through another node class
		$this->edit_node = new node($this->enid);
		// Site the title for this edit page
		$node->meta['title'] = 'Edit '.$this->edit_node->meta['title'];
		// load pre.php if there is one and page.php
		if(file_exists($this->edit_node->paths['pre'])){
			$this->en_pre = file_get_contents($this->edit_node->paths['pre']);	
		}
		$this->en_page = file_get_contents($this->edit_node->paths['page']);
		
		
		// if modify button pushed
		if(!empty($_POST['edit_page'])){
			if(empty($_POST['skip'])){
				$n_vid = floatval($this->edit_node->meta['vid']) + 0.001;
			}else{
				$n_vid = $this->edit_node->meta['vid'];
			}
			$en_parts = explode('/', substr($_POST['alias'], 1));
			if(is_array($en_parts)){
				$alias_route = $en_parts[0];
			}else{
				$alias_route = str_replace('/', '', $alias_route);
			}
			// update node data
			$node_result = $this->node->execute( "UPDATE nodes SET 
					sid = 1,
					vid = :vid,
					user_id = :owner,
					status = :status,
					created = :time,
					changed = :time,
					type = :type,
					alias_route = :route,
					alias = :alias,
					options = :options,
					title = :title,
					description = :description,
					keywords = :keywords 
					WHERE nid = :enid", array(
					':vid' => $n_vid,
					':status' => $_POST['status'],
					':owner' => $this->node->user_id,
					':time' => $this->node->time,
					':type' => $_POST['type'],
					':route' => $alias_route,
					':alias' => $_POST['alias'],
					':cache' => $_POST['cache'],
					':options' => $_POST['options'],
					':title' => $_POST['title'],
					':description' => $_POST['description'],
					':keywords' => $_POST['keywords'],
					':enid' => $this->enid));
			// create files
			$page_result = file_put_contents($this->edit_node->paths['page'], $_POST['page_code']);
			chmod($this->edit_node->paths['page'], 0644);
			if(empty($_POST['pre_code'])){
				if(file_exists($this->edit_node->paths['pre'])){
					// delete pre.php file
					$pre_result = unlink($this->edit_node->paths['pre']);
				}else{
					$pre_result = true;
				}
			}else{
				$pre_result = file_put_contents($this->edit_node->paths['pre'], $_POST['pre_code']);
				chmod($this->edit_node->paths['pre'], 0644);
			}
			// remove old permissions and add the new
			$role_remove_result = $this->node->execute("DELETE FROM nodes_roles WHERE nid = ?", array($this->enid));
			foreach($_POST['rid'] as $key => $value){
				if(!empty($value)){	
					$role_add_result = $this->node->execute("INSERT INTO nodes_roles SET nid = ?, rid = ?, auth = ?", array($this->enid, $key, $value));
				}
			}
			$this->node->redirect($this->node->paths['base'].$_POST['alias'].'edit');
			// error checking and status updates
			if($node_result === false){
				$this->status_messages['error'][] =  "The node query failed.";
			}if($role_remove_result === false){
				$this->status_messages['error'][] =  "The permission removal query failed";
			}if($role_add_result == false){
				$this->status_messages['error'][] =  "The permission creation query failed";
			}if($pre_result == false){
				$this->status_messages['error'][] =  "The pre.php file failed to be created or deleted.";
			}if($page_result == false){
				$this->status_messages['error'][] =  "The page.php file failed to update.";
			}
			if(empty($this->status_messages['error'])){
				$this->status_messages['status'][] =  "Page has been updated.";
			}
		}
	}
	function role_html(){
		$code = '';
		$defined_roles = $this->node->get_defined_roles();
		foreach($defined_roles as $name => $rid){
			$option1 = ''; $option2 = ''; $option3 = ''; $option4 = ''; 
			$node_role = $this->node->get_node_role($this->enid, $rid);
			if(!$node_role['read'] && !$node_role['write']){
				$option1 = 'selected';
			}else if($node_role['read'] && !$node_role['write']){
				$option2 = 'selected';
			}else if(!$node_role['read'] && $node_role['write']){
				$option3 = 'selected';
			}else if($node_role['read'] && $node_role['write']){
				$option4 = 'selected';
			}
			$code .= <<<EOD
<div class="five columns"><div><strong>$name</strong></div>
	<select name="rid[$rid]" tabindex="5" >
		<option value="" $option1></option>
		<option value="r" $option2>Read</option>
		<option value="w" $option3>Write</option>
		<option value="rw" $option4>Read and Write</option>
	</select>
</div>
EOD;
		}
		return $code;
	}
	function site_html($node, $current_sid){
		$code = '';
		if($node->is_admin($node->user_id)){
			$result = $node->execute("SELECT * FROM sites", array());
		}else{
			$result = $node->execute("SELECT * FROM sites WHERE user_id = ?", array($node->user_id));
		}
		while($row = $result->fetch()){
			$sid = $row['sid'];
			$site_name = $row['site_name'];
			$site_path = $row['site_path'];
			if($sid == $current_sid){
				$selected = 'selected';	
			}else{
				$selected = '';	
			}
			$code .= <<<EOD
		<option value="$sid" $selected>$site_name</option>
EOD;
		}
		return $code;
	}
}
?>