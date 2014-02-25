<?
global $root_path;
include $root_path.'/core/assets/guzzle/3.8.1/guzzle.phar';

	use Guzzle\Http\Client;
	use Guzzle\Http\Exception;
	
class pre extends node{	
	public $node;
	public $version;
	public $remote_version;
	public $update_msg;
	
	private $agent;
	
	public function pre($node){
		global $root_path;
		$this->node = $node;
		include_once $root_path.'/'.$this->node->paths['validation'];					  
		$this->agent = 'User-Agent: Deepwater-2 '.validation::remove_http(self::$base_url);
		$this->get_versions();
		
		if(!empty($_POST['update_deepwater'])){
			$this->update_msg = '';
			$address =  'https://api.github.com/repos/EddieOne/Deepwater-2/zipball/master';
			// check for update dir
			if(!is_dir($root_path.'/core/updates')){
				mkdir($root_path.'/core/updates', 0755);
			}
			// lazy check for version download errors
			if(!is_float(floatval($this->remote_version))){
				$this->node->status_messages['admin'][] = 'Error finding new version';
				return false;
			}
			// create a version dir
			if(!is_dir($root_path.'/core/updates/'.$this->remote_version)){
				mkdir($root_path.'/core/updates/'.$this->remote_version, 0755);
			}
			$file_location = $root_path.'/core/updates/'.$this->remote_version.'/deepwater-'.$this->remote_version.'.zip';
			// try downloading the newer version from git
			if(!file_exists($file_location)){
				$this->download_file($address, $file_location);
			}
			// unzip the downloaded file
			$unzip_result = $this->unzip($file_location, $root_path.'/core/updates/'.$this->remote_version);
			// catch zip errors
			if($unzip_result === false){
				$this->node->status_messages['admin'][] = 'Error unzipping update';
				return false;
			}
			$git_dir = 'EddieOne-Deepwater-2';
			if(!is_dir($root_path.'/core/updates/'.$this->remote_version.'/EddieOne-Deepwater-2')){
				$git_dir = '/EddieOne-Deepwater-2-'.substr($unzip_result, 0, 7);
			}
			
			if(!is_dir($root_path.'/core/updates/'.$this->remote_version.'/files')){ 
				mkdir($root_path.'/core/updates/'.$this->remote_version.'/files', 0755);
				// move github archive dir to files dir
				rename($root_path.'/core/updates/'.$this->remote_version.$git_dir, $root_path.'/core/updates/'.$this->remote_version.'/files');
			}
			
			
			// delete sites dir and install.php from update files
			include $root_path.'/core/includes/filesystem.inc.php';
			if(file_exists($root_path.'/core/updates/'.$this->remote_version.'/files/sites')){
				filesystem::del_dir($root_path.'/core/updates/'.$this->remote_version.'/files/sites');
				unlink($root_path.'/core/updates/'.$this->remote_version.'/files/install.php');
			}
			
			// replace production files
			filesystem::copy_dir($root_path.'/core/updates/'.$this->remote_version.'/files', $root_path);
			
			// search for sql files that run database updates
			$updates = array_diff(scandir($root_path.'/core/updates'), array('..', '.'));
			foreach($updates as $update){
				$this->update_msg .= 'Checking '.$update.' folder for sql file.<br />';
				// everything here should be a directory but lets check
				if(is_dir($root_path.'/core/updates/'.$update)){
					// see if the update has an sql update file
					if(file_exists($root_path.'/core/updates/'.$update.'/update.sql')){
						$this->update_msg .= 'Sql file found.<br />';
						// make sure we need to execute the sql
						if(version_compare($update, $this->version, '>')){
							$this->update_msg .= 'version needs installing.<br />';
							$sql = $this->read_sql_file($root_path.'/core/updates/'.$update.'/update.sql');
							$update_queries = $this->split_sql($sql);
							foreach($update_queries as $query){
								$this->update_msg .= 'Executing "'.$query.'" query.<br />';
								$result = $this->node->execute($query);
							}
						}
						
					}
				}
			}
			
			// clean up, delete downloaded zip
			if(file_exists($file_location)){
				unlink($file_location);
			}
			
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
			$comment = $zip->getArchiveComment();
			$zip->extractTo($destination);
			$zip->close();
			return $comment;
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
	// read sql line by line and strip comments. Returns a string 
	public function read_sql_file($sql_file){
		// read sql line by line to save memory
		$handle = @fopen($sql_file, "r");
		$output = '';
		if ($handle) {
			$in_comment = false;
			while (($buffer = fgets($handle, 4096)) !== false) {
				// if line not empty, not comment, and not a remark
				if(strpos($buffer, '\n') === false && strpos($buffer, '/*!') === false && strpos($buffer, '-- ') === false){
					$output .= trim($buffer);	
					}
				if (feof($handle) === false){
					$output .= "\n";
				}
			}
   	 	fclose($handle);
		}
		return $output;
	}

	// split sql string into single statements
	function split_sql($sql){
		$delimiter = ';';
		$tokens = explode($delimiter, $sql);
		unset($sql);
		$output = array();
		// matches are not important
		$matches = array();
		$token_count = count($tokens);
		
		for ($i = 0; $i < $token_count; $i++){
			// Avoid adding empty string at the end of return array
			if (($i != ($token_count - 1)) || (strlen($tokens[$i] > 0))){
				// Ttotal number of single quotes in the token.
				$total_quotes = preg_match_all("/'/", $tokens[$i], $matches);
				// Count escaped quotes
				$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$i], $matches);
				$unescaped_quotes = $total_quotes - $escaped_quotes;
				// If the number of unescaped quotes is even, then the delimiter did NOT occur inside a string literal.
				if (($unescaped_quotes % 2) == 0){
					// It's a complete sql statement.
					$output[] = $tokens[$i];
					// save memory.
					$tokens[$i] = "";
				}else{
					// incomplete sql statement. keep adding tokens until we have a complete one.
					// $temp will hold what we have so far.
					$temp = $tokens[$i] . $delimiter;
					// save memory..
					$tokens[$i] = "";

					// Do we have a complete statement yet?
					$complete_stmt = false;

					for ($j = $i + 1; (!$complete_stmt && ($j < $token_count)); $j++){
						// This is the total number of single quotes in the token.
						$total_quotes = preg_match_all("/'/", $tokens[$j], $matches);
						// Counts single quotes that are preceded by an odd number of backslashes,
						// which means they're escaped quotes.
						$escaped_quotes = preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tokens[$j], $matches);

						$unescaped_quotes = $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1){
							// odd number of unescaped quotes. In combination with the previous incomplete
							// statement(s), we now have a complete statement. (2 odds always make an even)
							$output[] = $temp . $tokens[$j];

							// save memory.
							$tokens[$j] = "";
							$temp = "";
	
							// exit the loop.
							$complete_stmt = true;
							// make sure the outer loop continues at the right point.
							$i = $j;
						}else{
							// even number of unescaped quotes. We still don't have a complete statement.
							// (1 odd and 1 even always make an odd)
							$temp .= $tokens[$j] . $delimiter;
							// save memory.
							$tokens[$j] = "";
						}
					}
				}
			}
		}
		return $output;
	}
}
?>