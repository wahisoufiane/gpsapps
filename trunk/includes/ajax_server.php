<?php
@ob_start();
@session_start();
error_reporting (E_ALL ^ E_NOTICE);

require("config.inc.php"); 
require("Database.class.php"); 
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

if($_GET[username] != '' && $_GET[password] != '')
{
	//	GET SUPERADMIN
		
	$sql = "SELECT * FROM tb_superadmininfo WHERE sai_username = '".$_GET[username]."' AND sai_password = '".$_GET[password]."'";
	$rows = $db->query($sql);
	
	if($db->affected_rows > 0){
		$record = $db->fetch_array($rows);
		$_SESSION[superID] = $record[sai_id];
		echo $redirect = "location.href = '../../modules/sa/';";
	}
	else{
	//	GET USERINFO
		$sql = "SELECT * FROM tb_userinfo WHERE ui_username = '".$_GET[username]."' AND ui_password  = '".$_GET[password]."'";
		$rows = $db->query($sql);
		if($db->affected_rows > 0){
			$record = $db->fetch_array($rows);
			if($record[ui_accessFlag] == 1)
			{
				$_SESSION[userID] = $record[ui_id];
				$_SESSION[clientID] = $record[ui_clientId];
				//echo $url = $_SERVER['HTTP_HOST'];
				echo $redirect = "location.href = '../../modules/user/';";
			}
			else
			{
				echo "2";
			}
		}
		else
		{
			echo "0";
		}
	}
	
	//echo $redirect = "location.href = 'modules/gpsTracker/';";

}

$db->close();

?>