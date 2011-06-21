<?php
@set_time_limit(0);
@session_start();
@ob_start();
error_reporting (E_ALL ^ E_NOTICE);

require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

require_once("../../includes/GPSFunction.php");
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

function gpspathAll($path1)
{
	$file1 = @fopen($path1, "r");
	if($file1)
	{
		while(!@feof($file1))
		{
			$data= @fgets($file1);				 
		}
		$data = getSortedData($data);
		return $data;
	}
	else
	{
		return 0;
	
	}
	@fclose($file1);
} 
function findDistance($data)
{
	$timeArr= array();
	$totalDistance=0;
	$data1=explode("#",$data);
	for($j1=0;$j1<count($data1);$j1++)
	{
		$data2=explode("$",$data1[$j1]);
		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);

			$vehi=$data3[0];
			
			$geodate=date("d-m-Y h:i A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$geoTime=date("H:i:s A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$pos1=convertLat(calLat($data3[7]));
			$pos2=convertLong(calLong($data3[8]));
			if($pos1>0 && $pos2>0)
			{
			if(!in_array($geoTime,$timeArr))
			{
			if($j1==0)
			{
				$pits1 =  $pos1;
				$pits2 =  $pos2;
			}
			else
			{
				$pits3 =  $pos1;
				$pits4 =  $pos2;
				
				$dist = getDistance($pits1, $pits2, $pits3, $pits4);
				$totalDistance += $dist;
				$pits1 =  $pits3;
				$pits2 =  $pits4;
			}
		}
		}
	}
	array_push($timeArr,$geoTime);
}
	return round($totalDistance);
}
if(isset($_GET[sessionid]) && $_GET[sessionid] !='')
{
		
		$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_SESSION[clientID];
		$resUserInfo = $db->query($getUserInfo);
		
		if($db->affected_rows > 0)
		{
			$recordUserInfo = $db->fetch_array($resUserInfo);
		}
		
		if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
		{
			$getDevName = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_deviceId = di_id AND di_status = 1 AND tcs_isActive = 1 AND di_clientId=ci_id AND ci_id=".$_GET[sessionid]." order by ci_clientName,di_deviceName,di_deviceId ASC";
		}
		else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
		{
			$getDevName = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId = ci_id AND ci_id=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
		}
		else if($recordUserInfo[ci_clientType] == "Reseller")
		{
			$getDevName = "SELECT * FROM tb_deviceinfo,tb_clientinfo,tb_client_subscription WHERE tcs_deviceId = di_id AND di_status = 1 AND tcs_isActive = 1 AND di_clientId = ci_id AND ci_clientId=".$_GET[sessionid]." order by ci_clientName,di_deviceName,di_deviceId ASC";
		}
		//echo $getDevName;
		$rows = $db->query($getDevName);			
		
		if($db->affected_rows > 0)
		{
			$xml = '<gps>';
			while($deviceRecord = $db->fetch_array($rows))
			{
			
				if($deviceRecord[di_deviceName])
				$devName = $deviceRecord[di_deviceName];
				else
				$devName = $deviceRecord[di_deviceId];
				
				$date_offline = date("d-m-Y",strtotime($_GET[date_offline]));
				
				$renewDate = date("d-m-Y",strtotime("-1 days ".($deviceRecord[tcs_noOfMonths]) ."months ".$deviceRecord[tcs_renewalDateFrom]));
				if(strtotime($date_offline) <= strtotime($renewDate))
				{
					
					$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/".$deviceRecord[di_imeiId].".txt";
					
					$mydata=gpspathAll($path1);
					//echo "<br><br>";
					
					//$distance=findDistance($mydata);
					$data1=explode("#",$mydata);
					$data2=explode("@",$data1[count($data1)-2]);
					if(count($data2)>1)
					{
						$data3=explode(",",$data2[1]);			
						//print_r($data3);
						$vehi=$data3[0];
		
						$geodate = $data3[8]." ".$data3[9];
						$geoTime = $data3[9];
						
						$pos1=calLat($data3[2]);
						$pos2=calLong($data3[1]);
						$mph = $data3[3];
						$direction = $data3[4];
						$altitute = $data3[5];
						$deviceIMEI = $data3[0];
						$sessionid = $_GET["sessionid"] ;
						$extraInfo = $data3[11];
						
							
						$xml.='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" altitute="'.$altitute.'" distance="'.$distance.'" deviceName="'.$devName.'" gpsTime="'.date("h:i A",strtotime($geoTime)).'" geodate="'.$geodate.'" deviceIMEI="'.$deviceIMEI.'" sessionid="'.$sessionid.'" accuracy="16" extraInfo="'.$extraInfo.'" route="'.$rtName.'" icon="'.$fetRoute[svi_icon].'" />';
							
					}
				}
			}
			$xml .= '</gps>';
			header('Content-Type: text/xml');
			echo $xml;
		}
}
		
?>