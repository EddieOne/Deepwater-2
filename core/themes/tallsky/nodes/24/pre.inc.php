<?
class pre extends node{
	public $node;
	public $config;
	
	public function pre($node){
		$this->node = $node;
		$this->action = $node->vars['action'];
		$this->name_slug = $node->vars['name_slug'];
		global $root_path;
		// verify pluygin has all needed files
		if(!is_dir('plugins/'.$this->name_slug)){
			$this->status_messages['error'][] =  "Missing plugin directory";
			return;
		}
		if(!file_exists('plugins/'.$this->name_slug.'/config.json')){
			$this->status_messages['error'][] =  "Missing config.json file";
			return;
		}
		if(!file_exists('plugins/'.$this->name_slug.'/install.sql')){
			$this->status_messages['error'][] =  "Missing install.sql file. It can be blank if no sql is needed.";
			return;
		}
		if(!file_exists('plugins/'.$this->name_slug.'/install.inc.php')){
			$this->status_messages['error'][] =  "Missing install.inc.php file";
			return;
		}
		if(!file_exists('plugins/'.$this->name_slug.'/uninstall.sql')){
			$this->status_messages['error'][] =  "Missing uninstall.sql file. It can be blank if no sql is needed.";
			return;
		}
		if(!file_exists('plugins/'.$this->name_slug.'/uninstall.inc.php')){
			$this->status_messages['error'][] =  "Missing uninstall.inc.php file";
			return;
		}
		// load config
		$config = file_get_contents('plugins/'.$this->name_slug.'/config.json');
		$config = json_decode($config);
		$this->config = $config;
		
		//get plugin record if it exist
		$result = $this->node->execute("SELECT * FROM plugins WHERE name_slug = ?", array($this->name_slug));
		$plugin = $result->fetch(PDO::FETCH_OBJ);
		
		// delete plugin files
		if($this->action == 'delete'){
			$node->meta['title'] = 'Delete '.$config->name;
			// verify not installed
			if($plugin && $plugin->installed == 1){
				$this->status_messages['error'][] =  "Please uninstall plugin before trying to delete.";
				$node->redirect($this->node->paths['base'].'/admin/plugin/');
				return;
			}else{
				global $root_path;
				include_once $root_path.'/'.$this->node->paths['filesystem'];
				if(!filesystem::del_dir('plugins/'.$this->name_slug)){
					$this->node->status_messages['error'][] =  "Unable to delete the plugins files.";
				}else{
					$this->node->status_messages['status'][] =  "Plugin files deleted.";
				}
				$node->redirect($this->node->paths['base'].'/admin/plugin/');
			}
			
		}
		
		// uninstall plugin
		if($this->action == 'uninstall'){
			$node->meta['title'] = 'Uninstall '.$config->name;
			if(!empty($_POST['discard_records'])){
				$sql = $this->node->read_sql_file('plugins/'.$this->name_slug.'/uninstall.sql');
				$update_queries = $this->node->split_sql($sql);
				foreach($update_queries as $query){
					$result = $this->node->execute($query);
				}
				
			}
			if(!empty($_POST['keep_records']) || !empty($_POST['discard_records'])){
				include $root_path.'/plugins/'.$this->name_slug.'/uninstall.inc.php';
				if (class_exists('uninstall')) {
    				$uninstall = new uninstall($this->node, $this);
					if(method_exists($uninstall, 'start_uninstall')){
						$uninstall_result = $uninstall->start_uninstall();
						if($uninstall_result){
							$result = $this->node->execute("UPDATE plugins SET installed = 0 WHERE name_slug = ?", array($this->name_slug));
							$this->node->status_messages['status'][] =  "Plugin uninstalled.";
							$node->redirect($this->node->paths['base'].'/admin/plugin/');
						}else{
							$this->node->status_messages['error'][] =  "Plugin uninstalled failed.";
							$node->redirect($this->node->paths['base'].'/admin/plugin/');
						}
					}else{
						$this->node->status_messages['error'][] =  "Missing start_uninstall method in uninstall.inc.php";
						$node->redirect($this->node->paths['base'].'/admin/plugin/');
					}
				}else{
					$this->node->status_messages['error'][] =  "Missing uninstall class in uninstall.inc.php";
					$node->redirect($this->node->paths['base'].'/admin/plugin/');
				}
			}
		}
		
		// install plugin
		if($this->action == 'install'){
			// add database queries
			$node->meta['title'] = 'Install '.$config->name;
			$sql = $this->node->read_sql_file('plugins/'.$this->name_slug.'/install.sql');
			$update_queries = $this->node->split_sql($sql);
			foreach($update_queries as $query){
				$result = $this->node->execute($query);
			}
			
			include $root_path.'/plugins/'.$this->name_slug.'/install.inc.php';
			if (class_exists('install')) {
    			$install = new install($this->node, $this);
				if(method_exists($install, 'start_install')){
					$install_result = $install->start_install();
					if($install_result){
						if($plugin){
							$result = $this->node->execute("UPDATE plugins SET name = ?, version = ?, installed = 1, date = ? WHERE name_slug = ?",
													 array($config->name, $config->version, $this->node->time, $this->name_slug));
						}else{
							$result = $this->node->execute("INSERT INTO plugins SET name = ?, name_slug = ?, version = ?, installed = 1, date = ?",
													 array($config->name, $config->name_slug, $config->version, $this->node->time));
						}
						$this->node->status_messages['status'][] =  "Plugin installed.";
						$node->redirect($this->node->paths['base'].'/admin/plugin/');
					}else{
						$this->node->status_messages['error'][] =  "Plugin installed failed.";
						$node->redirect($this->node->paths['base'].'/admin/plugin/');
					}
				}else{
					$this->node->status_messages['error'][] =  "Missing start_install method in install.inc.php";
					$node->redirect($this->node->paths['base'].'/admin/plugin/');
				}
			}else{
				$this->node->status_messages['error'][] =  "Missing install class in install.inc.php";
				$node->redirect($this->node->paths['base'].'/admin/plugin/');
			}
		}
		
	}
}
?>