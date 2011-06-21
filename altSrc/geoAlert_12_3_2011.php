<?php
error_reporting (E_ALL ^ E_NOTICE);
require("../includes/config.inc.php"); 
require("../includes/Database.class.php"); 
require("../includes/GPSFunction.php"); 
require("../includes/smsSF.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

if(isset($_GET[gpsdata]) && $_GET[gpsdata])
{
	$gpsdata = str_replace("$","",$_GET[gpsdata]);
	$gpsdata = str_replace("@","",$gpsdata);
	$gpsdata = explode(",",$gpsdata);
	$lat=calLat($gpsdata[2]);
	$lng=calLong($gpsdata[1]);
	
	$devDateTime = $gpsdata[8]." ".$gpsdata[9];
	$devImei = $gpsdata[0];
	if($gpsdata[8] == date("d-m-Y"))
	{
	
		$getDevice = "SELECT * FROM tb_deviceinfo WHERE di_imeiId = ".$gpsdata[0]." AND di_status = 1";
		$resDevice = $db->query($getDevice);
		if($db->affected_rows > 0)
		{
			$fetDevice = $db->fetch_array($resDevice);
			//print_r($fetDevice);
			$getGeofenceInfo = "SELECT * FROM tb_assigngeofence WHERE tag_diId = ".$fetDevice[di_id]." AND tag_isActive = 1 ORDER BY tag_id ASC";
			$resGeofenceInfo = $db->query($getGeofenceInfo);
			if($db->affected_rows > 0)
			{
				while($fetGeofence = $db->fetch_array($resGeofenceInfo))
				{
					$gpspts = $fetGeofence[tag_geofenceId]."@".$fetGeofence[tag_id]."@".$fetGeofence[tag_alertSrc]."@".$fetGeofence[tag_noofTimes]."@".$fetGeofence[tag_inout];
					$resSrv1= explode("@",$gpspts);	
					//print_r($resSrv1);
					//statusGeoPoint($lat,$lng,$resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei);
					$getDevice = "SELECT * FROM tb_geofence_info WHERE tgi_id = ".$fetGeofence[tag_geofenceId]." AND tgi_isActive = 1";
					$resDevice = $db->query($getDevice);
					if($db->affected_rows > 0)
					{
						$fetDevice = $db->fetch_array($resDevice);
						$lat_lngArr = explode("#",$fetDevice[tgi_coordinates]);		
						for($i=0;$i<count($lat_lngArr)-1;$i++)
						{
							$lat_lng1 = explode(",",$lat_lngArr[$i]);
							$lat_lng [$i] = $lat_lng1[0].",".$lat_lng1[1];
							
						}
						//print_r($lat_lng);
						$res = getGeofenceStatus($lat_lng,$lat,$lng);
						if($res)
						{
							smsmAlertFunc($resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei,"in");
						}
						else
						{
							smsmAlertFunc($resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei,"out");
						}
						
					}
					
					$lat_lng = "";
					//echo "<br><br>";
				}
				
			}
		}
	}
}
function smsmAlertFunc($gid,$aid,$src,$nooftime,$inout,$devDateTime,$devImei,$inorout)
{
	$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
	$db->connect(); 
	//print_r($_GET);
	//echo "<br><br>";
	$getAlertImeiInfo = "SELECT * FROM tb_geoalertinfo WHERE tgai_assignDevId = '".$devImei."' AND tgai_createDate like '".date("Y-m-d",strtotime($devDateTime))."%' ORDER BY tgai_id DESC LIMIT 0,1";
	$resAlertImeiInfo = $db->query($getAlertImeiInfo);
	if($db->affected_rows == 0)
	{
		if($inout == 'in' && $inorout == $inout)
		{
			$smsStatus = sendSMSAlert($aid,$devDateTime);
			if($smsStatus)
			{
				$data['tgai_assignDevId'] = $devImei;
				$data['tgai_geoAssignId'] = $aid;
				$data['tgai_inoutStatus'] = 0;
				$data['tgai_alertCount'] = 1;
				$data['tgai_alertType'] = $inout;
				$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
				//print_r($data);
				
				if($db->query_insert("tb_geoalertinfo", $data))
					$res = 1;
				else $res = 0;
				echo "SMS";
			}
		}
		elseif($inout == 'out' && $inorout != $inout)
		{
				$data['tgai_assignDevId'] = $devImei;
				$data['tgai_geoAssignId'] = $aid;
				$data['tgai_inoutStatus'] = 0;
				$data['tgai_alertCount'] = 1;
				$data['tgai_alertType'] = $inout;
				$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
				//print_r($data);
				
				if($db->query_insert("tb_geoalertinfo", $data))
					$res = 1;
				else $res = 0;
		}

	}
	else													//if($nooftime == 0 || ($fetAlertImeiInfo[tgai_alertCount] < $nooftime))
	{
		$fetAlertImeiInfo = $db->fetch_array($resAlertImeiInfo);
		
		$getAlertInfo = "SELECT * FROM tb_geoalertinfo WHERE tgai_geoAssignId = ".$aid." AND tgai_createDate like '".date("Y-m-d",strtotime($devDateTime))."%'";
		$resAlertInfo = $db->query($getAlertInfo);
		$fetAlertInfo = $db->fetch_array($resAlertInfo);
		
		/*echo $fetAlertImeiInfo[tgai_geoAssignId]." ".$aid." ".$fetAlertImeiInfo[tgai_inoutStatus]." ".$nooftime." ".$fetAlertImeiInfo[tgai_alertCount]." ". $inorout." ".$inout;
		
		echo "<pre>";
		print_r($fetAlertImeiInfo);
		//print_r($fetAlertInfo);
		echo "</pre>";*/
		
		if($fetAlertImeiInfo[tgai_geoAssignId] == $aid && $fetAlertImeiInfo[tgai_inoutStatus] == 0)
		{
			//echo $fetAlertImeiInfo[tgai_alertCount]."<".$nooftime."<br>";
			if($inout == 'in' && $inorout != $inout)
			{
				$data['tgai_inoutStatus'] = 1;
				$data['tgai_reachdevTime2'] = date("Y-m-d H:i:s",strtotime($devDateTime));
				//print_r($data);
				//exit;
				if($db->query_update("tb_geoalertinfo", $data , "tgai_id=".$fetAlertInfo[tgai_id]))
					$res = 1;
				else $res = 0;
				
			}
			elseif($inout == 'out' && $inorout == $inout)
			{
				$smsStatus = sendSMSAlert($aid,$devDateTime);
				if($smsStatus)
				{
					$data['tgai_inoutStatus'] = 1;
					$data['tgai_reachdevTime2'] = date("Y-m-d H:i:s",strtotime($devDateTime));
					//print_r($data);
					//exit;
					if($db->query_update("tb_geoalertinfo", $data , "tgai_id=".$fetAlertInfo[tgai_id]))
						$res = 1;
					else $res = 0;
					
					echo "SMS";
				}
			}
		}
		elseif($fetAlertImeiInfo[tgai_geoAssignId] != $aid && $fetAlertImeiInfo[tgai_inoutStatus] == 1 && $fetAlertInfo[tgai_alertCount] < $nooftime)
		{
			//print_r($fetAlertImeiInfo);
			if($inout == 'in' && $inorout == $inout)
			{
				$smsStatus = sendSMSAlert($aid,$devDateTime);
				if($smsStatus)
				{
					$data['tgai_assignDevId'] = $devImei;
					$data['tgai_geoAssignId'] = $aid;
					$data['tgai_inoutStatus'] = 0;
					$data['tgai_alertCount'] =  $fetAlertInfo[tgai_alertCount] + 1;
					$data['tgai_alertType'] = $inout;
					$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
					//print_r($data);
					
					if($db->query_insert("tb_geoalertinfo", $data))
						$res = 1;
					else $res = 0;
					echo "SMS";
				}
			}
			elseif($inout == 'out' && $inorout != $inout)
			{
					$data['tgai_assignDevId'] = $devImei;
					$data['tgai_geoAssignId'] = $aid;
					$data['tgai_inoutStatus'] = 0;
					$data['tgai_alertCount'] =  $fetAlertInfo[tgai_alertCount] + 1;
					$data['tgai_alertType'] = $inout;
					$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
					//print_r($data);
					
					if($db->query_insert("tb_geoalertinfo", $data))
						$res = 1;
					else $res = 0;
			}
		}
	}

}
?>
