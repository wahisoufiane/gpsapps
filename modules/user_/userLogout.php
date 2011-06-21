<?php
@session_start();
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

if(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
{
$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_accessFlag  = 1 AND ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_SESSION[clientID];
$resUserInfo = $db->query($getUserInfo);

if($db->affected_rows > 0){
	$recordUserInfo = $db->fetch_array($resUserInfo);
	
	if($recordUserInfo[ci_clientId]!=0)
	{
		$getResellInfo = "SELECT ci_clientName FROM tb_clientinfo WHERE ci_id = ".$recordUserInfo[ci_clientId];
		$resResellInfo = $db->query($getResellInfo);
		if($db->affected_rows > 0){
			$fetResellInfo = $db->fetch_array($resResellInfo);
			$clientName = strtolower(str_replace(" ","",$fetResellInfo[ci_clientName]));
			$redirectPath = "../../in/".$clientName;
		}
		else
		{
			$clientName = strtolower(str_replace(" ","",$recordUserInfo[ci_clientName]));
			$redirectPath = "../../in/".$clientName;
		}
	}
	else
	{
		$clientName = strtolower(str_replace(" ","",$recordUserInfo[ci_clientName]));
		$redirectPath = "../../in/".$clientName;
	}
}
else
{
	$redirectPath = "../../";
}
}
else
{
	$redirectPath = "../../";
}
//echo $redirectPath;
unset($_SESSION[userID]);
unset($_SESSION[clientID]);
header("location:".$redirectPath);
exit;
?>