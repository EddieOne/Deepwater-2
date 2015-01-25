<?
class pre extends node{
	public $node;

	public function pre($node){
		$this->node = $node;
		
		if(!empty($_POST['update_crontab'])){
			try{
				$cron = $_POST["crons"];
				passthru('echo "'.$cron.'" | crontab -', $response);
				if($response == ""){
					$node->status_messages['status'][] = "Assuming successful write, no response received.";
				}else{
					$node->status_messages['error'][] = "Unexpected response: ".$response;
				}
			}catch(Exception $e){
				$node->status_messages['error'][] = "Could not write the crontab file. Probably due to security settings of your hosting providor.";
				$node->status_messages['error'][] = "PHP returned: ".$e->getMessage();
			}
		}
	}
	public function get_crons(){
		try{
			passthru('crontab -l', $crons);
		}catch(Exception $e){
			$node->status_messages['error'][] = "Could not read the crontab file. Probably due to security settings of your hosting providor.";
			$node->status_messages['error'][] = "PHP returned: ".$e->getMessage();
		}
		return $crons;
	}
}
?>