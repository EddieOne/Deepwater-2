<?
include ("config.php");
include ("functions.php");
$username = clean_string($_POST['name']);
if(!empty($username)){
	if(checkFlood(10,"namecheck")){
		// 24 hours
		addFlood("namecheck",86400);
		$sql_query = "SELECT name FROM users WHERE name = '$username'";
		$result = mysql_query($sql_query);
		if(mysql_num_rows($result)){
			// username already exist
			echo "false";
		}else{
			echo "true";
			//username validated
		}
	}else{
		echo "error";
	}
}else{
	echo "error";
}
?>