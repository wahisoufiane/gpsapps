<?php
@set_time_limit(0);
@ob_start();
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");

//	DB Connection
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

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

function get_text($filename)
{
	echo $filename;
	$fp_load = @fopen("$filename", "rb");
	if ( $fp_load )
	{
		while ( !feof($fp_load) )
		{
			$content .= fgets($fp_load, 102400);
		}
		fclose($fp_load);
		return $content;
	}
	else
	{
		//header("Location:dataServerConn.php");
	}
}

function gpspathFun($source)
{
	$path1=$source; 
	if(chk_folder($path1))
	{
			$file1 = @fopen($path1, "r");
		if($file1)
		{
		while(!feof($file1))
		{
		   $data= fgets($file1);				 
		   //$i++;
		}
			$data = getSortedData($data);
			return $data;
		fclose($file1);
		}
		else
		{
			$data=0;
			return $data;
		}
	}
} 
function kmsPerDay($path1)
{ 
	//echo $path;
	$timeArr = array();
	$cnt = 1;
	$totalDistance = 0;

  	$file1 = @fopen($path1, "r");
	if($file1)
	{
		//echo $path1;
		while(!feof($file1))
		{
			$data= @fgets($file1);				 
		}
		$data = getSortedData($data);
		$data1=explode("#",$data);
		//print_r($data1);
		for($j1=0;$j1<count($data1);$j1++)
		{
			$data2=explode("@",$data1[$j1]);
			if(count($data2)>1)
			{
				$data3=explode(",",$data2[1]);	
				$vehi=$data3[0];
				
				$geodate = $data3[8];
				$geoTime = date("h:i A",strtotime($data3[9]));
				
				$pos1=calLat($data3[2]);
				$pos2=calLong($data3[1]);
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
		$locat=simpleGeocode($pits1,$pits2);
		$locat=str_replace('"',"",$locat);
		$finalData = round($totalDistance).",".$locat.",".$data3[3].",".$geodate.",".$geoTime;
		@fclose($file1);
		return $finalData;
	}
	else
	{
		return false;
	}
}
if(isset($_GET[upGeoStatus]) && $_GET[upGeoStatus]!='')
{
	$getDeviceGeofence = "SELECT * FROM tb_assigngeofence WHERE tag_geofenceId  = ".$_GET[geoid];	
	$resDeviceGeofence = $db->query($getDeviceGeofence);
		
	if($db->affected_rows == 0 )
	{
		$sql = "UPDATE tb_geofence_info SET tgi_isActive = ".$_GET[status]." WHERE tgi_id = ".$_GET[geoid]." AND tgi_clientId = ".$_SESSION[clientID];
		$row = $db->query($sql); 
		if($row)
			echo 4;
		else
			echo 5;
	}
	else
		echo 5;
}
if(isset($_GET[deletePoint]) && $_GET[deletePoint]!='')
{
	$sql = "UPDATE tb_geofence_info SET tgi_isActive = 0 WHERE tgi_latLong = '".$_GET[param]."' AND tgi_clientId = ".$_SESSION[clientID];
	$row = $db->query($sql); 
	if($row)
		echo 4;
	else
		echo 5;
}
if(isset($_GET[addGeoPoint]) && $_GET[addGeoPoint]!='')
{
	$getCont =  "select * from tb_geofence_info where tgi_name = '".$_GET[name]."' OR tgi_latLong = '".$_GET[param]."' AND tgi_clientId =".$_SESSION[clientID];
	$resCont = $db->query($getCont);
	if($db->affected_rows == 0)
	{
		$cdata["tgi_clientId"] = $_SESSION[clientID];
		$cdata["tgi_name"] = $_GET[name];
		$cdata["tgi_isActive"] = 1;
		$cdata["tgi_radius"] = $_GET[radius];
		$cdata["tgi_latLong"] = $_GET[param];
		//print_r($cdata);
		if($db->query_insert("tb_geofence_info", $cdata))
		{
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	else
	{
		$fetCont = $db->fetch_array($resCont);
		$cdata["tgi_clientId"] = $_SESSION[clientID];
		$cdata["tgi_name"] = $_GET[name];
		$cdata["tgi_isActive"] = 1;
		$cdata["tgi_radius"] = $_GET[radius];
		$cdata["tgi_latLong"] = $_GET[param];
		//print_r($cdata);
		if($db->query_update("tb_geofence_info", $cdata, "tgi_id = ".$fetCont[tgi_id]))
		{
			echo 2;
		}
		else
		{
			echo 0;
		}
	}
}
if(isset($_GET[contType]) && $_GET[contType]!='')
{
	$getCont =  "select * from tb_client_contact_info where tcci_srcType = '".$_GET[contType]."' AND tcci_clientId =".$_SESSION[clientID];
	$resCont = $db->query($getCont);
	$str = '';
	if($db->affected_rows > 0)
	{
		while($fetCont = $db->fetch_array($resCont))
		{
			//print_r($fetCont);
			$str .='<option value='.$fetCont[tcci_source].'>'.$fetCont[tcci_source].'</option>';
		}
	}
	echo $str;
}
if(isset($_GET[type]) && $_GET[type]!='')
{
	if($_GET[type] == 'add')
	{
		$chkCont = "select * from tb_client_contact_info where tcci_source = ".$_GET[src]." AND tcci_clientId =".$_SESSION[clientID];
		$resCont = $db->query($chkCont);
		if($db->affected_rows == 0)
		{
			$cdata["tcci_clientId"] = $_SESSION[clientID];
			$cdata["tcci_name"] = $_GET[name];
			$cdata["tcci_srcType"] = $_GET[alrttype];
			$cdata["tcci_source"] = $_GET[src];
			if($db->query_insert("tb_client_contact_info", $cdata))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else echo 2;
	}
	else
	{
		$cdata["tcci_name"] = $_GET[name];
		$cdata["tcci_srcType"] = $_GET[alrttype];
		$cdata["tcci_source"] = $_GET[src];
		
		//print_r($_GET);
		
		if($db->query_update("tb_client_contact_info", $cdata, "tcci_id = ".$_GET[cid]))
			echo 3;
		else echo 4;
	}
}

if($_GET[getResQry] !='' && $_GET[getResQry] !='')
{
	$rows = $db->query($_GET[getResQry]);
	if($db->affected_rows > 0)
	{
		$fetch = @mysql_fetch_assoc($rows); 
		//print_r($fetch);
		echo implode(",",$fetch);
	}
	else echo 0;
}
if($_GET[ajaxQry] !='' && $_GET[ajaxQry] !='')
{
	$rows = $db->query($_GET[ajaxQry]);
	echo $db->affected_rows;
}

if($_GET[date_offline] !='' && $_GET[sessionid] !='')
{
	if(isset($_GET[date_offline]) && $_GET[date_offline])
		$date_offline = $_GET[date_offline];
	else
		$date_offline = date('d-m-Y');
	
	$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_GET[sessionid];
	$resUserInfo = $db->query($getUserInfo);

	if($db->affected_rows > 0){
	$recordUserInfo = $db->fetch_array($resUserInfo);
	}
	
	if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=ci_id AND ci_id=".$_GET[sessionid]." order by di_deviceName,di_deviceId,ci_clientName ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Reseller")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_clientinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId = ci_id AND ci_clientId=".$_GET[sessionid]." order by di_deviceName,di_deviceId,ci_clientName ASC";
	}
	
	$resDevice= $db->query($getDevice);	
	//exit;
	$tmpId='';
	$destArr=array();
	
	if($db->affected_rows > 0 )
	{
		while ($fetDevice = $db->fetch_array($resDevice)) 
		{
			//print_r($fetDevice);
			if($fetDevice[di_deviceName])
				$devName = $fetDevice[di_deviceName];
			else
				$devName = $fetDevice[di_deviceId];
			$devImage = $fetDevice[di_deviceImg];
				
			$renewDate = date("d-m-Y",strtotime("-1 days ".($fetDevice[tcs_noOfMonths]) ."months ".$fetDevice[tcs_renewalDateFrom]));
			//echo $date_offline." <= ".date("d-m-Y",strtotime($renewDate))."<br>";
			if(strtotime($date_offline) <= strtotime($renewDate))
			{
				 
			$sdate = date("d-m-Y",strtotime($fetDevice[di_createDate]));
			$edate = date("d-m-Y");
			
			$z = GetDays($sdate, $edate);
			for($y=0; $y<count($z); $y++)
			{
				$getDateReading = "SELECT * FROM tb_speed_meter_info WHERE tmsi_clientId = ".$fetDevice[di_clientId]." AND tmsi_imei = '".$fetDevice[di_imeiId]."' AND tmsi_readDate = '".date('Y-m-d',strtotime($z[$y]))."'";
				$resDateReading = $db->query($getDateReading);

				if($db->affected_rows == 0)
				{
					$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($z[$y]))."/".$fetDevice[di_imeiId].".txt";
					$km = explode(",",kmsPerDay($path));
					$kmdata['tmsi_clientId'] = $fetDevice[di_clientId];
					$kmdata['tmsi_imei'] = $fetDevice[di_imeiId];
					$kmdata['tmsi_readDate'] = date('Y-m-d',strtotime($z[$y]));
					$kmdata['tmsi_kmpd'] = $km[0];
					
					//print_r($kmdata);
					//exit;
					$db->query_insert("tb_speed_meter_info", $kmdata);
				}
				else
				{
					$sToTime = strtotime($z[$y]);
					$eToTime = strtotime(date("d-m-Y"));
					if($sToTime == $eToTime )
					{
						$fetDateReading = $db->fetch_array($resDateReading);
						//print_r($fetDateReading);
						$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($z[$y]))."/".$fetDevice[di_imeiId].".txt";
						$km = explode(",",kmsPerDay($path));
						//print_r($km);
						$kmdata['tmsi_clientId'] = $fetDevice[di_clientId];
						$kmdata['tmsi_imei'] = $fetDevice[di_imeiId];
						$kmdata['tmsi_readDate'] = date('Y-m-d',strtotime($z[$y]));
						$kmdata['tmsi_kmpd'] = $km[0];
						
						//print_r($kmdata);
						//exit;
						$db->query_update("tb_speed_meter_info", $kmdata, "tsmi_id = ".$fetDateReading[tsmi_id]);
					}
				}
				
				//echo "<br><br>";
			}
			//print_r($fetDevice);
			
			//exit;
			//$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y')."/".$fetDevice[di_imeiId].".txt";
			//$totKm = kmsPerDay($path);
			//echo "<br><br>";
			$getReading = "SELECT SUM(tmsi_kmpd) as dist FROM tb_speed_meter_info WHERE tmsi_clientId = ".$fetDevice[di_clientId]." AND tmsi_imei = '".$fetDevice[di_imeiId]."' group by tmsi_imei";
			$resReading = $db->query($getReading);
			//exit;
			//print_r($km);
			if($db->affected_rows > 0 )
			{
			
				$fetReading = mysql_fetch_assoc($resReading);
				$odoMeter = $fetDevice[di_odoMeter] + $fetReading[dist];
			}
			else
			{
				$odoMeter = $fetDevice[di_odoMeter];
			}
			//echo ((strtotime(date("H:i"))-strtotime(date("H:i",strtotime($km[4]))))/60)." ".date("H:i")." ".date("H:i",strtotime($km[4]))."<br>";
			if(((strtotime(date("H:i"))-strtotime(date("H:i",strtotime($km[4]))))/60) < 5)
				$meterArr[] = ucfirst($fetDevice[ci_clientName]).",".$devName.",".$odoMeter.",".$renewDate.",".$km[1].",".$km[2].",".$km[3].",".$km[4].",1".",".$devImage;
			else
				$meterArr[] = ucfirst($fetDevice[ci_clientName]).",".$devName.",".$odoMeter.",".$renewDate.",".$km[1].",".$km[2].",".$km[3].",".$km[4].",2".",".$devImage;
			}
			else
			{
				$getReading = "SELECT SUM(tmsi_kmpd) as dist FROM tb_speed_meter_info WHERE tmsi_clientId = ".$fetDevice[di_clientId]." AND tmsi_imei = '".$fetDevice[di_imeiId]."' group by tmsi_imei";
				$resReading = $db->query($getReading);
				//exit;
				//print_r($km);
				if($db->affected_rows > 0 )
				{
				
					$fetReading = mysql_fetch_assoc($resReading);
					$odoMeter = $fetDevice[di_odoMeter] + $fetReading[dist];
				}
				else
				{
					$odoMeter = $fetDevice[di_odoMeter];
				}
				$meterArr[] = ucfirst($fetDevice[ci_clientName]).",".$devName.",".$odoMeter.",".$renewDate.",Subscription Date Expired.Please Renew the Unit,,,,0,".$devImage;
			}
			//echo "<br>";
		}	// While
		
		if(count($meterArr)>0)
		{
			$meterArr1 = implode("@",$meterArr);
		}
		else
		{
			$meterArr1 = "";	
		}
		
		echo $meterArr1;

	}// If
}
?>