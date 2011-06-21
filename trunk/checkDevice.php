<?php
@set_time_limit(0);
@session_start();
@ob_start();
error_reporting (E_ALL ^ E_NOTICE);

require("includes/config.inc.php"); 
require("includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

require_once("includes/GPSFunction.php");
$temp="00-00-00 00:00:00am";

function get_text($filename)
{
	$fp_load = @fopen("$filename", "rb");
	if ( $fp_load )
	{
		while ( !feof($fp_load) )
		{
			$content .= fgets($fp_load, 102400);
		}
		return $content;
	}
	else
	{
		//header("Location:dataServerConn.php");
	}
	@fclose($fp_load);
}
function chk_folder($filename)
{
	$fp_load = @fopen("$filename", "rb");
	if ( $fp_load )
	{
		return true;
	}
	else
	{
		return false;
	}
}

$date_offline = date("d-m-Y");
$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/";

$chkFolder=chk_folder($path1);
if($chkFolder)
{
	preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);  
	for($i=1;$i<count($matches[2]);$i++)
	{
		//echo $matches[2][$i] . '<br>';
		$tmpVehi = explode('.',$matches[2][$i]);
		//print_r($tmpVehi);
		$getUserInfo = "SELECT * FROM tb_deviceinfo WHERE di_imeiId  = ".$tmpVehi[0];
		$resUserInfo = $db->query($getUserInfo);
		
		if($db->affected_rows == 0)
		{
			echo "yes ".$tmpVehi[0]."<br>";
		}
		
	}
}
		
?>