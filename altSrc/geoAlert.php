<?php
error_reporting (E_ALL ^ E_NOTICE);

require("../includes/config.inc.php"); 
require("../includes/Database.class.php"); 
require("../includes/GPSFunction.php"); 
require("../includes/smsSF.php"); 
require("../includes/mailSMTP.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 
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
if(isset($_GET[gpsdata]) && $_GET[gpsdata])
{
	$gpsdata = str_replace("$","",$_GET[gpsdata]);
	$gpsdata = str_replace("@","",$gpsdata);
	$gpsdata = explode(",",$gpsdata);
	
	if($gpsdata[8] == date("d-m-Y"))
	{
	
		$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($gpsdata[8]))."/".$gpsdata[0].".txt";
		$mydata=gpspathAll($path1);
		$data1=explode("#",$mydata);
		$data2=explode("@",$data1[count($data1)-2]);
		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);			
			//print_r($data3);
			$lat=calLat($data3[2]);
			$lng=calLong($data3[1]);
			
			$devDateTime = $data3[8]." ".$data3[9];
			$devImei = $data3[0];
			
			$sessionid = $_GET["sessionid"] ;
		}
		//exit;
		
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
					$gpspts = $fetGeofence[tag_geofenceId]."#".$fetGeofence[tag_id]."#".$fetGeofence[tag_alertSrc]."#".$fetGeofence[tag_noofTimes]."#".$fetGeofence[tag_inout];
					$resSrv1= explode("#",$gpspts);	
					//print_r($resSrv1);
					//statusGeoPoint($lat,$lng,$resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei);
					$getDevice = "SELECT * FROM tb_geofence_info WHERE tgi_id = ".$fetGeofence[tag_geofenceId]." AND tgi_isActive = 1";
					$resDevice = $db->query($getDevice);
					if($db->affected_rows > 0)
					{
						$fetDevice = $db->fetch_array($resDevice);
						$lat_lng = explode(",",$fetDevice[tgi_latLong]);		
						$radius = $fetDevice[tgi_radius];
						//echo $lat_lng[0]." , ".$lat_lng[1]." , ".$radius." , ".$lat." , ".$lng."<br>";
						$latlngArr = drawCircle($lat_lng[0],$lat_lng[1],$radius);
						
						//print_r($latlngArr);
						//exit;
						$res = getGeofenceStatus($latlngArr,$lat,$lng);
						//echo "<br>";
						//exit;

	$getAlertImeiInfo_repeat = "SELECT COUNT(*) FROM tb_geoalertinfo WHERE tgai_assignDevId = '".$devImei."' AND  tgai_reachdevTime2='0000-00-00 00:00:00' AND tgai_geoAssignId=".$fetGeofence[tag_geofenceId]." ORDER BY tgai_id DESC LIMIT 0,1";

				$already_in_alert_sent = $db->query($getAlertImeiInfo_repeat);


						if($res)
						{
							if($already_in_alert_sent=='0'){
							smsmAlertFunc($resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei,"in");
							}
						}
						else
						{
							smsmAlertFunc($resSrv1[0],$resSrv1[1],$resSrv1[2],$resSrv1[3],$resSrv1[4],$devDateTime,$devImei,"out");
						}
						
					}
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
	//echo $aid."  ".$nooftime." cs ". $inorout." ds ".$inout." ".$devDateTime."<br><br>";
	//echo "<br><br>";
	
	$getAlertImeiInfo = "SELECT * FROM tb_geoalertinfo WHERE tgai_assignDevId = '".$devImei."' AND tgai_createDate like '".date("Y-m-d",strtotime($devDateTime))."%' ORDER BY tgai_id DESC LIMIT 0,1";
	$resAlertImeiInfo = $db->query($getAlertImeiInfo);
	if($db->affected_rows == 0)
	{
		if($inout == 'in' && $inorout == $inout)
		{
			$smsStatus = sendAlert($aid,$devDateTime);
			//echo "<br>";
			if($smsStatus)
			{
				$data['tgai_assignDevId'] = $devImei;
				$data['tgai_geoAssignId'] = $aid;
				$data['tgai_inoutStatus'] = 0;
				$data['tgai_alertCount'] = 1;
				$data['tgai_alertType'] = $inout;
				$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
				//print_r($data);
				//exit;
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
				//exit;
				if($db->query_insert("tb_geoalertinfo", $data))
					$res = 1;
				else $res = 0;
		}

	}
	else													//if($nooftime == 0 || ($fetAlertImeiInfo[tgai_alertCount] < $nooftime))
	{
		$fetAlertImeiInfo = $db->fetch_array($resAlertImeiInfo);
		
		$getAlertInfo = "SELECT count(*) as pastTotal FROM tb_geoalertinfo WHERE tgai_geoAssignId = ".$aid." AND tgai_inoutStatus = 1 AND tgai_createDate like '".date("Y-m-d",strtotime($devDateTime))."%'";
		$resAlertInfo = $db->query($getAlertInfo);
		$fetAlertInfo = $db->fetch_array($resAlertInfo);
		
		
		//echo "<br>".$fetAlertImeiInfo[tgai_geoAssignId]." ".$aid." ".$fetAlertImeiInfo[tgai_inoutStatus]." ".$nooftime." ".$fetAlertImeiInfo[tgai_alertCount]." ". $inorout." ".$inout."<br>";
		//print_r($fetAlertInfo);
		/*echo "<pre>";
		print_r($fetAlertImeiInfo);
		//
		echo "</pre>";*/
		//echo $fetAlertImeiInfo[tgai_id];
		//echo "<br> 1 ".$fetAlertInfo[pastTotal]." ".$fetAlertImeiInfo[tgai_alertCount]."<".$nooftime."<br>";
		if($fetAlertImeiInfo[tgai_geoAssignId] == $aid && $fetAlertInfo[pastTotal] < $nooftime)
		{
			//echo "<br>".$fetAlertInfo[pastTotal]." ".$fetAlertImeiInfo[tgai_alertCount]."<".$nooftime."<br>";
			if($fetAlertImeiInfo[tgai_inoutStatus] == 0)
			{
				//echo $fetAlertImeiInfo[tgai_alertCount]."<".$nooftime."<br>";
				if($inout == 'in' && $inorout != $inout)
				{
					$data['tgai_inoutStatus'] = 1;
					$data['tgai_reachdevTime2'] = date("Y-m-d H:i:s",strtotime($devDateTime));
					//print_r($data);
					//exit;
					if($db->query_update("tb_geoalertinfo", $data , "tgai_id=".$fetAlertImeiInfo[tgai_id]))
						$res = 1;
					else $res = 0;
					
				}
				elseif($inout == 'out' && $inorout == $inout)
				{
					$smsStatus = sendAlert($aid,$devDateTime);
					if($smsStatus)
					{
						$data['tgai_inoutStatus'] = 1;
						$data['tgai_reachdevTime2'] = date("Y-m-d H:i:s",strtotime($devDateTime));
						//print_r($data);
						//exit;
						if($db->query_update("tb_geoalertinfo", $data , "tgai_id=".$fetAlertImeiInfo[tgai_id]))
							$res = 1;
						else $res = 0;
						
						echo "SMS";
					}
				}
			}
			else
			{
				//echo $fetAlertImeiInfo[tgai_alertCount]."<".$nooftime."<br>";
				//echo "<br>".$inout." ".$inorout;
				if($inout == 'in' && $inorout == $inout)
				{
					$smsStatus = sendAlert($aid,$devDateTime);
					if($smsStatus)
					{
						$data['tgai_assignDevId'] = $devImei;
						$data['tgai_geoAssignId'] = $aid;
						$data['tgai_inoutStatus'] = 0;
						$data['tgai_alertCount'] =  $fetAlertInfo[tgai_alertCount] + 1;
						$data['tgai_alertType'] = $inout;
						$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
						//print_r($data);
						//exit;
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
						//exit;
						
						if($db->query_insert("tb_geoalertinfo", $data))
							$res = 1;
						else $res = 0;
				}
			}
		}
		elseif($fetAlertImeiInfo[tgai_geoAssignId] != $aid && $fetAlertImeiInfo[tgai_inoutStatus] == 1 && $fetAlertInfo[tgai_alertCount] < $nooftime)
		{
			//print_r($fetAlertImeiInfo);
			if($inout == 'in' && $inorout == $inout)
			{
				$smsStatus = sendAlert($aid,$devDateTime);
				if($smsStatus)
				{
					$data['tgai_assignDevId'] = $devImei;
					$data['tgai_geoAssignId'] = $aid;
					$data['tgai_inoutStatus'] = 0;
					$data['tgai_alertCount'] =  $fetAlertInfo[tgai_alertCount] + 1;
					$data['tgai_alertType'] = $inout;
					$data['tgai_reachdevTime1'] = date("Y-m-d H:i:s",strtotime($devDateTime));
					//print_r($data);
					//exit;
					
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
					//exit;
					
					if($db->query_insert("tb_geoalertinfo", $data))
						$res = 1;
					else $res = 0;
			}
		}
	}

}
function sendAlert($geoAssId,$devDateTime)
{
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

	$getData = "SELECT * FROM tb_assigngeofence,tb_deviceinfo,tb_geofence_info,tb_clientinfo WHERE ci_id = tag_clientId AND tgi_id = tag_geofenceId AND di_id = tag_diId AND tag_id = ".$geoAssId;
	$resData = mysql_query($getData);
	if(@mysql_affected_rows() > 0)
	{
		$fetData = mysql_fetch_assoc($resData);
		//print_r($fetData);
		
		$getReseller = "select * from tb_clientinfo where ci_id = ".$fetData[ci_clientId];
		$resReseller = mysql_query($getReseller);
		$fetReseller = @mysql_fetch_assoc($resReseller);
		//print_r($fetReseller);
		
		if($fetData[di_deviceName])
			$devName = $fetData[di_deviceName];
		else
			$devName = $fetData[di_deviceId];
			
		if($fetData[tag_inout] == "in")
			$status = "entered zone";
		else
			$status = "left zone";
		
		$info =$devName." has ".$status." ".$fetData[tgi_name]." at ".date("d M Y H:i:s",strtotime($devDateTime))." ".$fetData[tag_alertType]." ".$fetData[tag_alertSrc];
		
		if($fetData[tag_alertType] == "Email")		
		{
				$to = $fetData[tag_alertSrc];
				$sub = "Geofence Alert - ".$fetReseller[ci_clientName];
				$msg = "<b>Dear ".ucfirst($fetData[ci_clientName])."! </b><br><br>Vehicle ".$devName." has ".$status." ".$fetData[tgi_name]." at ".date("d M Y H:i",strtotime($devDateTime))."<br><br> - ".$fetReseller[ci_weburl];
				$fr = $fetReseller[ci_clientName];			
				
				//echo $msg;
				//exit;
				
				$mailres = sendSMTPMail($to,$sub,$msg,$fetReseller[ci_clientName],$fetReseller[ci_smtpHostname],$fetReseller[ci_smtpUsername],$fetReseller[ci_smtpPassword],$fetReseller[ci_smtpUsername]);
				
				$mailString = $to.",".$fetReseller[ci_clientName].",".$fetReseller[ci_smtpHostname].",".$fetReseller[ci_smtpUsername].",".$fetReseller[ci_smtpPassword].",".$fetReseller[ci_smtpUsername].",".$mailres;
				writeGeoData(date("d-m-Y",strtotime($devDateTime)),date("H:i:s",strtotime($devDateTime)),strip_tags($info),$mailString);
				
				$maildata['tmi_email'] = $to;
				$maildata['tmi_tgai_id'] = $geoAssId;
				$maildata['tmi_mailResult'] = $mailres;
				$maildata['tmi_message'] = urlencode($msg);
				$maildata['tmi_mailType'] = "GEOALERT";		
				//print_r($maildata);		
				//exit;
				if($db->query_insert("tb_mail_info", $maildata))
					return 1;
				else return 0;
		}
		else if($fetData[tag_alertType] == "Mobile")		
		{
			$from = "";
			$to_nos = explode(',',$fetData[tag_alertSrc]);
            foreach($to_nos as $to){
              if($to !=""){
				$msg = "Dear ".ucfirst($fetData[ci_clientName])."! ".$devName." has ".$status." ".$fetData[tgi_name]." at ".date("H:i:s",strtotime($devDateTime))." - ".$fetData[ci_weburl];

				$smsres= sendGatewaySMS($from,$to,$msg,$fetReseller[ci_smsGatewayUri],$fetReseller[ci_smsGatewayUsername],$fetReseller[ci_smsGatewayPassword],$fetReseller[ci_smsGatewaySenderId]);
				
				
			  }
			}

$smsString = $fetData[tag_alertSrc].",".$fetReseller[ci_smsGatewayUri].",".$fetReseller[ci_smsGatewayUsername].",".$fetReseller[ci_smsGatewayPassword].",".$fetReseller[ci_smsGatewaySenderId].",".$smsres;
	writeGeoData(date("d-m-Y",strtotime($devDateTime)),date("H:i:s",strtotime($devDateTime)),strip_tags($info),$smsString);

			$smsdata['tsi_mobileno'] = $fetData[tag_alertSrc];
			$smsdata['tsi_tgai_id'] = $geoAssId;
			$smsdata['tsi_smsResult'] = $smsres;
			$smsdata['tsi_message'] = urlencode($msg);
			$smsdata['tsi_smsType'] = "GEOALERT";		
			//print_r($smsdata);		
			//exit;
			if($db->query_insert("tb_smsinfo", $smsdata))
				return 1;
			else return 0;			
		}
		
		//exit;
		//return 1;
	}
}
function writeGeoData($gdate,$gtime,$mesg,$mailString)
{
	$file_name = "gdata/".$gdate.".txt";
	//open file for writng and place pointer at the end
	$handle = fopen($file_name, 'a+');

	if(!$handle)
	{
		die("couldn't open file <i>$file_name</i>");
	}
	
	fwrite($handle, $gtime." - ".$mesg." - ".$mailString."\n");
	return 1;

}
?>
