<?php
require("../includes/config.inc.php"); 
require("../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

if(isset($_GET[geoAssId]) && $_GET[geoAssId] && isset($_GET[srcdata]) && $_GET[srcdata])
{
	//print_r($_GET);
	//echo "<br><br>";
	$getAlertImeiInfo = "SELECT * FROM tb_geoalertinfo WHERE tgai_assignDevId = '".$_GET[devImei]."' AND tgai_createDate like '".date("Y-m-d",strtotime($_GET[devDateTime]))."%' ORDER BY tgai_id DESC LIMIT 0,1";
	$resAlertImeiInfo = $db->query($getAlertImeiInfo);
	if($db->affected_rows == 0)
	{
		$getAlertInfo = "SELECT * FROM tb_geoalertinfo WHERE tgai_geoAssignId = ".$_GET[geoAssId];
		$resAlertInfo = $db->query($getAlertInfo);
		if($db->affected_rows == 0)
		{
			if($_GET[inoutFlag] == $_GET[inoutPoint])
			{
				$data['tgai_assignDevId'] = $_GET[devImei];
				$data['tgai_geoAssignId'] = $_GET[geoAssId];
				$data['tgai_inoutStatus'] = 0;
				$data['tgai_alertCount'] = 1;
				$data['tgai_alertType'] = $_GET[inoutPoint];
				$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($_GET[devDateTime]));
				
				//print_r($data);
				//exit;
				if($db->query_insert("tb_geoalertinfo", $data))
					$res = 1;
				else $res = 0;
				echo "SMS";
			}
		}

	}
	else
	{
		$fetAlertImeiInfo = $db->fetch_array($resAlertImeiInfo);
		//print_r($fetAlertImeiInfo);
		if($fetAlertImeiInfo[tgai_geoAssignId] == $_GET[geoAssId])
		{
			//echo $fetAlertImeiInfo[tgai_alertCount]."<".$_GET[notime];
			if($fetAlertImeiInfo[tgai_inoutStatus] == 0)
			{
				if($_GET[inoutFlag] != $_GET[inoutPoint])
				{
					$data['tgai_inoutStatus'] = 1;
					$data['tgai_reachdevTime2'] = date("Y-m-d H:i:s",strtotime($_GET[devDateTime]));
					
					//print_r($data);
					//exit;
					if($db->query_update("tb_geoalertinfo", $data , "tgai_id=".$fetAlertImeiInfo[tgai_id]))
						$res = 1;
					else $res = 0;
				}	
			}
			else
			{
				if($_GET[inoutFlag] == $_GET[inoutPoint] && ($_GET[notime] == 0 || $fetAlertImeiInfo[tgai_alertCount] < $_GET[notime] ))
				{
					$data['tgai_assignDevId'] = $_GET[devImei];
					$data['tgai_geoAssignId'] = $_GET[geoAssId];
					$data['tgai_inoutStatus'] = 0;
					$data['tgai_alertCount'] =  $fetAlertImeiInfo[tgai_alertCount] + 1;
					$data['tgai_alertType'] = $_GET[inoutPoint];
					$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($_GET[devDateTime]));
					
					//print_r($data);
					//exit;
					if($db->query_insert("tb_geoalertinfo", $data))
						$res = 1;
					else $res = 0;
					echo "SMS";
				}
			}
		}
		else
		{
			//print_r($fetAlertImeiInfo);
			if($_GET[inoutFlag] == $_GET[inoutPoint] && $fetAlertImeiInfo[tgai_inoutStatus] != 0)
			{
				$data['tgai_assignDevId'] = $_GET[devImei];
				$data['tgai_geoAssignId'] = $_GET[geoAssId];
				$data['tgai_inoutStatus'] = 0;
				$data['tgai_alertCount'] = 1;
				$data['tgai_alertType'] = $_GET[inoutPoint];
				$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($_GET[devDateTime]));
				
				//print_r($data);
				//exit;
				if($db->query_insert("tb_geoalertinfo", $data))
					$res = 1;
				else $res = 0;
				echo "SMS";
			}
		}
	}
}


if(isset($_GET[getpointId]) && $_GET[getpointId])
{
	//echo $_GET[getpointId];
	$getDevice = "SELECT * FROM tb_geofence_info WHERE tgi_id = ".$_GET[getpointId]." AND tgi_isActive = 1";
	$resDevice = $db->query($getDevice);
	if($db->affected_rows > 0)
	{
		$fetDevice = $db->fetch_array($resDevice);
		echo $fetDevice[tgi_coordinates];		
	}
	else
	{
		echo 0;
	}
}

if(isset($_GET[gpsdata]) && $_GET[gpsdata])
{
	$gpsdata = str_replace("$","",$_GET[gpsdata]);
	$gpsdata = str_replace("@","",$gpsdata);
	$gpsdata = explode(",",$gpsdata);
	//print_r($gpsdata);
	
	$getDevice = "SELECT * FROM tb_deviceinfo WHERE di_imeiId = ".$gpsdata[0]." AND di_status = 1";
	$resDevice = $db->query($getDevice);
	if($db->affected_rows > 0)
	{
		$fetDevice = $db->fetch_array($resDevice);
		//print_r($fetDevice);
		$getGeofenceInfo = "SELECT * FROM tb_assigngeofence WHERE tag_diId = ".$fetDevice[di_id]." ORDER BY tag_id ASC";
		$resGeofenceInfo = $db->query($getGeofenceInfo);
		if($db->affected_rows > 0)
		{
			while($fetGeofence = $db->fetch_array($resGeofenceInfo))
			{
				$gpspts[] =  $fetGeofence[tag_geofenceId]."@".$fetGeofence[tag_id]."@".$fetGeofence[tag_alertSrc]."@".$fetGeofence[tag_noofTimes]."@".$fetGeofence[tag_inout];
			}
			$gpspts = implode("#",$gpspts);
			echo $gpspts;
			/*$geoIds = explode("#",$fetGeofence[tag_geofenceId]);
			for($k = 0; $k < count($geoIds); $k++)
			{
				
			}*/
		}
		else
		{
			
		}
	}
	else
	{
	}
}
?>
