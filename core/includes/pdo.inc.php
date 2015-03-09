<?
include 'configuration.inc.php';
class pdo_db extends configuration {
	private $dbh;
	public $status_messages = array();
	
	public function __construct(){
		$this->db_connect();
	}
	private function db_connect(){
		try {
			$config = new configuration();
			$host = $config->db_host;
			$db_user = $config->db_user;
			$db_pass = $config->db_pass;
			$db_name = $config->db_name;
			$db_port = $config->db_port;
    		$this->dbh = new PDO("mysql:host=$host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
			$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			$this->status_messages['admin'][] = "Failed to connect to database: " . $e->getMessage();
			echo "Error: Failed to connect to database.";
    		//die();
		}
	}
	public function execute($query, $varibles = null){
		try {
			$statement = $this->dbh->prepare($query);
			$statement->execute($varibles);
			return $statement;
		} catch (Exception $e) {
			$error = str_replace(array("'", '
			'), '', $e->getMessage());
			$this->status_messages['admin'][] = "Database Error: " . $error;
			return false;
		}
	}
	public function last_insert(){
		return $this->dbh->lastInsertId(); 
	}
	public function db_close(){
		$this->dbh = null;
	}
	public function read_sql_file($sql_file){
		$handle = @fopen($sql_file, "r");
		$output = '';
		if ($handle) {
			$in_comment = false;
			while (($buffer = fgets($handle, 4096)) !== false) {
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
	public function split_sql($sql){
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