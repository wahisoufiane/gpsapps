<?php
@ob_start();
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");

//	DB Connection
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 


if($_GET[ajaxQry] !='' && $_GET[ajaxQry] !='')
{
	$rows = $db->query($_GET[ajaxQry]);
	echo $db->affected_rows;
}
?>