<?
class pre extends node{
	public $node;
	
	public function pre($node){
		$this->node = $node;
	}
	public function plugins(){
		global $root_path;
		include_once $root_path.'/'.$this->node->paths['validation'];
		$code = '';
		$plugins = [];
		// load installed plugins via database
		$result = $this->node->execute("SELECT * FROM plugins ORDER BY date DESC", array());
		while($plugin = $result->fetch(PDO::FETCH_OBJ)){
			$plugins[$plugin->name_slug] = $plugin;
		}
		// load plugin configs via folders
		$dh  = opendir('plugins');
		while (false !== ($filename = readdir($dh))) {
			if($filename != '.' && $filename != '..'){
				if(is_dir('plugins/'.$filename) && file_exists('plugins/'.$filename.'/config.json')){
					$config = file_get_contents('plugins/'.$filename.'/config.json');
					$config = json_decode($config);
					
					$install_link = '<a href="'.$this->node->paths['base'].'/admin/plugin/do/install/'.$config->name_slug.'/" class="button" style="margin:5px;">Install</a>';
					$uninstall_link = '<a href="'.$this->node->paths['base'].'/admin/plugin/do/uninstall/'.$config->name_slug.'/" class="button" style="margin:5px;">Uninstall</a>';
					$delete_link = '<a href="'.$this->node->paths['base'].'/admin/plugin/do/delete/'.$config->name_slug.'/" class="button" style="margin:5px;">Delete</a>';
					
					if(array_key_exists ($config->name_slug, $plugins)){
						if($plugins[$config->name_slug]->installed == 1){
							$options = $uninstall_link;
							$installed = 'Yes';
						}else{
							$options = $install_link.' '.$delete_link ;
							$installed = 'No';
						}
					}else{
						$options = $install_link.' '.$delete_link ;
						$installed = 'No';
					}
					$created = validation::unix_timestamp_to_human ($config->created, $format = 'M d Y');
					$code .= $this->plugin_code($config->name, $config->description, $config->version, $installed, $config->author, $config->link, $created, $options);
				}
			}
		}
		return $code;
	}
	private function plugin_code($name, $description, $version, $installed, $author, $link, $date, $options){
		return <<<EOD
	<div class="sixteen columns" style="border-bottom:#86939e 1px solid;">
		<div class="two columns">{$name}</div>
		<div class="three columns">{$description}</div>
		<div class="two columns">{$version}</div>
		<div class="two columns">{$installed}</div>
		<div class="two columns"><a href="{$link}">{$author}</a></div>
    	<div class="two columns">{$date}</div>
		<div class="three columns">{$options}</div>
	</div>
EOD;
	}
}
?>