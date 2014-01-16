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
}
?>