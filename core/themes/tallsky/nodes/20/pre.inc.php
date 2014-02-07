<?
global $root_path;
include $root_path.'/core/assets/guzzle/3.8.1/guzzle.phar';

	use Guzzle\Http\Client;
	use Guzzle\Http\Exception;
	
class pre extends node{	
	public $node;
	public $version;
	public $remote_version;
	
	private $agent;
	
	public function pre($node){
		global $root_path;
		$this->node = $node;
		include_once $root_path.'/'.$this->node->paths['validation'];					  
		$this->agent = 'User-Agent: Deepwater-2 '.validation::remove_http(self::$base_url);
		$this->get_versions();
		
		if(!empty($_POST['update_deepwater'])){
			$address =  'https://api.github.com/repos/EddieOne/Deepwater-2/zipball/master';
			if(!is_dir($root_path.'/core/updates')){
				mkdir($root_path.'/core/updates', 0755);
			}
			if(!is_float(floatval($this->remote_version))){
				$this->node->status_messages['admin'][] = 'Error finding new version';
				return false;
			}
			if(!is_dir($root_path.'/core/updates/'.$this->remote_version)){
				mkdir($root_path.'/core/updates/'.$this->remote_version, 0755);
			}
			$file_location = $root_path.'/core/updates/'.$this->remote_version.'/deepwater-'.$this->remote_version.'.zip';
			if(!file_exists($file_location)){
				$this->download_file($address, $file_location);
			}
			$this->unzip($file_location, $root_path.'/core/updates/'.$this->remote_version);
			$dirs = glob($root_path.'/core/updates/'.$this->remote_version.'/EddieOne-Deepwater-2', GLOB_ONLYDIR);
			print_r($dirs);
			// get unzipped directory name
			// delete install.php and sites folder
			// replace production files
			// execute mysql queries
			// delete extra files
			
		}
	}
	public function get_versions(){
		global $root_path;
		$this->version = file_get_contents($root_path.'/VERSION');
		$address = 'https://api.github.com/repos/EddieOne/Deepwater-2/contents/VERSION';
		$response = $this->github_request($address, 'Raw', 'GET');
		$json_response = json_decode($response->getBody(true));
		$this->remote_version = base64_decode($json_response->content);
	}
	private function download_file($address, $file_location){
    	try {
			$client = new Client($address);
			$client->setUserAgent($this->agent);
			
			$options = array('exceptions' => false, );
			
			$response = $client->get('', array(), $options)->setResponseBody($file_location)->send();
        	return true;
		} catch (Guzzle\Http\Exception $e) {
    		foreach ($e as $exception) {
      			$this->status_messages['admin'][] = $exception->getMessage() . PHP_EOL;
				return false;
    		}
		}
	}
	private function unzip($zip_file, $destination){
		$zip = new ZipArchive;
		if ($zip->open($zip_file) === TRUE) {
			$zip->extractTo($destination);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}
	private function github_request($address, $media_type, $type){			  
		try{
			$client = new Client($address);
			$client->setUserAgent($this->agent);
			
			// http://developer.github.com/v3/media/
			if($media_type = 'JSON'){
				$accept = 'application/vnd.github.v3+json';
			}
			if($media_type = 'Raw'){
				$accept = 'application/vnd.github.v3.raw+json';
			}
			if($media_type = 'Text'){
				$accept = 'application/vnd.github.v3.text+json';
			}
			if($media_type = 'HTML'){
				$accept = 'application/vnd.github.v3.html+json';
			}
			if($media_type = 'Full'){
				$accept = 'application/vnd.github.v3.full+json';
			}
			if($media_type = 'diff'){
				$accept = 'application/vnd.github.v3.diff';
			}
			if($media_type = 'patch'){
				$accept = 'application/vnd.github.v3.patch';
			}

			$options = array('Accept-Charset' => 'utf-8',
								'Accept' => 'application/vnd.github.v3+json',
								'timeout' => 5,
								'connect_timeout' => 2,
								'exceptions' => false);
		
			
			
			// http://developer.github.com/v3/#http-verbs
			if($type == 'HEAD'){
				$response = $client->head('', array(), $options)->send();
			}
			if($type == 'GET'){
				$response = $client->get('', array(), $options)->send();
			}
			if($type == 'POST'){
				$response = $client->post('', array(), $options)->send();
			}
			if($type == 'PATCH'){
				$response = $client->patch('', array(), $options)->send();
			}
			if($type == 'PUT'){
				$response = $client->put('', array(), $options)->send();
			}
			if($type == 'DELETE'){
				$response = $client->delete('', array(), $options)->send();
			}
		} catch (Guzzle\Http\Exception $e) {
    		foreach ($e as $exception) {
      			$this->status_messages['admin'][] = $exception->getMessage() . PHP_EOL;
				return false;
    		}
		}
		return $response;
	}
}
?>