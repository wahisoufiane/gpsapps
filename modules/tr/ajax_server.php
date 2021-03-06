<?php
@set_time_limit(0);
@ob_start();
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
require("../../includes/GPSFunction.php");
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

function chk_folder($filename)
{
	if (@fopen($filename, "r"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

function gpspathFun($source)
{
	$file1 = @fopen($source, "r");
	if($file1)
	{
		while(!@feof($file1))
		{
		   $data= @fgets($file1);				 
		   //$i++;
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

if(isset($_GET[add_stop_name]) && $_GET[add_stop_name] !='')
{
	//echo $_GET[add_stop_name];
	$getCont =  "select * from tb_geofence_info where tgi_name = '".$_GET[add_stop_name]."' OR tgi_latLong = '".$_GET[mapPt]."' AND tgi_clientId =".$_SESSION[clientID];
	$resCont = $db->query($getCont);
	if($db->affected_rows == 0)
	{
		$cdata["tgi_clientId"] = $_SESSION[clientID];
		$cdata["tgi_name"] = $_GET[add_stop_name];
		$cdata["tgi_isActive"] = 1;
		$cdata["tgi_radius"] = "0.5";
		$cdata["tgi_latLong"] = $_GET[mapPt];
		//print_r($cdata);
		//exit;
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
		echo 0;
}

if($_GET[date_offline] !='' && $_GET[sessionid] !='')
{
	if(isset($_GET[date_offline]) && $_GET[date_offline])
		$date_offline = $_GET[date_offline];
	else
		$date_offline = date('d-m-Y');
	
	$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_SESSION[clientID];
	$resUserInfo = $db->query($getUserInfo);

	if($db->affected_rows > 0){
	$recordUserInfo = $db->fetch_array($resUserInfo);
	}
	
	if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_deviceId = di_id AND di_status = 1 AND tcs_isActive = 1 AND di_clientId=ci_id AND ci_id=".$_GET[sessionid]." order by ci_clientName,di_deviceName,di_deviceId ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId = ci_id AND ci_id=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Reseller")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_clientinfo,tb_client_subscription WHERE tcs_deviceId = di_id AND di_status = 1 AND tcs_isActive = 1 AND di_clientId = ci_id AND ci_clientId=".$_GET[sessionid]." order by ci_clientName,di_deviceName,di_deviceId ASC";
	}
	//echo $getDevice;
	$resDevice= $db->query($getDevice);	
	//exit;
	$tmpId='';
	$destArr=array();
	if($db->affected_rows > 0 )
	{
		while ($fetDevice = $db->fetch_array($resDevice)) 
		{
			$renewDate = date("d-m-Y",strtotime("-1 days ".($fetDevice[tcs_noOfMonths]) ."months ".$fetDevice[tcs_renewalDateFrom]));
			if(strtotime($date_offline) <= strtotime($renewDate))
			{ 
			 $vehicle_reg_no=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/".$fetDevice[di_imeiId].".txt";
				if($mydata=gpspathFun($vehicle_reg_no))
				{
					$data1=explode("#",$mydata);		
					$data2=explode("@",$data1[count($data1)-2]);
					if(count($data2)>1)
					{
						$data3=explode(",",$data2[1]);	
						//print_r($data3);
						$vehi=$data3[0];
						$pos1=calLat($data3[2]);
						$pos2=calLong($data3[1]);
						$recTime = date("h:i A",strtotime($data3[9]));
					
						$d = explode("-",$_GET[date_offline]);
						$result_pt = $fetDevice[di_deviceId]."#".$fetDevice[di_imeiId]."#"."00ff00"."#1#".$_GET[sessionid]."#".$d[0]."#".$d[1]."#".$d[2]."#".$fetDevice[di_imeiId]."#".$recTime."#".$pos1."#".$pos2."#".$fetDevice[di_deviceName]."#".$data3[3];
					}
					if($tmpId!=$fetDevice[ci_clientName])
					{
						$tmpId=$fetDevice[ci_clientName];
						if($destArr[$tmpId]=="")
						{
							//$destArr = array_push_assoc($destArr, $tmpId, $result_pt);
							$destArr[$tmpId]=$result_pt;
						}
							// else end
					}
					else
					{
						foreach ($destArr as $key => $value) 
						{
						   //echo "Key: $key; Value: $value<br />\n";
						   if($key == $tmpId)
						   {
								$tmpVal=$value;
								$destArr[$key]=$value.','.$result_pt;
						   }
						}	// foreach end
					}
					
				}
				else
				{
					//echo $fetDevice[di_createDate]."<br>";
					$sdate = date("d-m-Y",strtotime($fetDevice[di_createDate]));
					$edate = date("d-m-Y",strtotime($_GET[date_offline]));
					
					$z = GetDays($sdate, $edate);
					
					for($y=count($z); $y>0; $y--)
					{
						$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($z[$y]))."/".$fetDevice[di_imeiId].".txt";
						$fp_load = @fopen($path, "rb");
						if ($fp_load)
						{
							/*$path1 = $path;
							$datDate1 = $z[$y];*/
							break;
						}
						
						@fclose($path);
					}
					//echo $path;
					//exit;
					if($mydata=gpspathFun($path))
					{
						$data1=explode("#",$mydata);		
						$data2=explode("@",$data1[count($data1)-2]);
						if(count($data2)>1)
						{
							$data3=explode(",",$data2[1]);	
							//print_r($data3);
							$vehi=$data3[0];
							$pos1=calLat($data3[2]);
							$pos2=calLong($data3[1]);
							$recTime = date("h:i A",strtotime($data3[9]));
						
							$d = explode("-",$data3[8]);
							$result_pt = $fetDevice[di_deviceId]."#".$fetDevice[di_imeiId]."#"."00ff00"."#0#".$_GET[sessionid]."#".$d[0]."#".$d[1]."#".$d[2]."#".$fetDevice[di_imeiId]."#".$recTime."#".$pos1."#".$pos2."#".$fetDevice[di_deviceName]."#".$data3[3];
						}
					
						//$d = explode("-",$_GET[date_offline]);
						//$result_pt = $fetDevice[di_deviceId]."#".$fetDevice[di_imeiId]."#"."00ff00"."#0#".$_GET[sessionid]."#".$d[0]."#".$d[1]."#".$d[2]."#".$fetDevice[di_imeiId]."####".$fetDevice[di_deviceName]."#-1";
						//$result_pt = $fetDevice[di_deviceId]."#".$fetDevice[di_imeiId]."#"."ff0000"."#"."n"."#".$fetDevice[di_deviceName];
		
						//exit;
						if($tmpId!=$fetDevice[ci_clientName])
						{
							$tmpId=$fetDevice[ci_clientName];
							$destArr[$tmpId]=$result_pt;
						}
						else
						{
							foreach ($destArr as $key => $value) 
							{
							  // echo "Key: $key; Value: $value<br />\n";
							   if($key == $tmpId)
							   {
									$tmpVal=$value;
									$destArr[$key]=$value.','.$result_pt;
							   }
							}	// foreach end
						}
					}
				}	
			}
			else
			{

					$result_pt = $fetDevice[di_deviceId]."#".$fetDevice[di_imeiId]."#"."DFA16F"."#2#".$_GET[sessionid]."####".$fetDevice[di_imeiId]."####".$fetDevice[di_deviceName]."#";
					if($tmpId!=$fetDevice[ci_clientName])
					{
						$tmpId=$fetDevice[ci_clientName];
						$destArr[$tmpId]=$result_pt;
					}
					else
					{
						foreach ($destArr as $key => $value) 
						{
						  // echo "Key: $key; Value: $value<br />\n";
						   if($key == $tmpId)
						   {
								$tmpVal=$value;
								$destArr[$key]=$value.','.$result_pt;
						   }
						}	// foreach end
					}
				//}
			}
		}	// While
		//print_r($destArr);
		//exit;
		echo '<ul id="menu">';
		foreach ($destArr as $key => $value) 
		{
			//echo 'Key: $key; Value: $value<br />\n';
			$value = explode(',',$value);
			echo '<li><a href="#">'.ucfirst($key).'</a>';
			echo '<ul>';
		
			for ($col = 0; $col < count($value); $col++)
			{
				$link = explode('#',$value[$col]);
				//print_r($link);
				//if($link[3] == 'y')
				//{
					if($link[12])
					$devName = $link[12];
					else
					$devName = $link[0];
					
					if($link[3]==2)
					{
						echo '<li><a style="color:#B519CF;" class='.$cssName.' id="'.$link[1].'" >'.$devName.'<br>Sub. Exp.</a></li>';
					}
					else
					{
					if($link[13]>0 && $link[3] == 1)
					{
						$colorName  = 'green';
						$cssName  = 'green_link';
						$devName.=' - '.$link[9];
						echo '<li><a style="color:'.$colorName.';" class='.$cssName.' id="'.$link[1].'" onclick="myclick('.$_GET[sessionid].','.$link[5].','.$link[6].','.$link[7].','.$link[1].','.$link[3].');">'.$devName.'</a></li>';
					}
					else if($link[13] == 0 && $link[3] == 1)
					{
						$colorName = 'red';
						$cssName  = 'green_link';
						$devName.=' - '.$link[9];
						echo '<li><a style="color:'.$colorName.';" class='.$cssName.' id="'.$link[1].'" onclick="myclick('.$_GET[sessionid].','.$link[5].','.$link[6].','.$link[7].','.$link[1].','.$link[3].');">'.$devName.'</a></li>';
					}
					else
					{
						$colorName = 'blue';
						$cssName  = 'green_link';
						$offDate = $link[5]."/".$link[6]."/".$link[7];
						$devName.= "<br>".$offDate.' - '.$link[9];
						echo '<li><a style="color:'.$colorName.';" class='.$cssName.' id="'.$link[1].'">'.$devName.'</a></li>';
						
					}
					//echo '<li><a style="cursor:pointer;background:#'.$link[2].'" onclick="onlineGTracker('.$link[4].','.$link[5].','.$link[6].','.$link[7].','.$link[8].','.$link[10].','.$link[11].');">'.$link[0].' - '.$link[9].'</a></li>';
					
					}
					
				//echo '<li><a style="background:#'.$link[2].'" href="'.$link[1].'">'.$link[0].'</a></li>';
			}
		
			echo '</ul>';
			echo '</li>';
		}
		echo '</li></ul>';
		}// If
	
}
if(isset($_GET[type]) && $_GET[type])
{

	parse_str($_SERVER['QUERY_STRING'],$param);
	//print_r($param);
	//echo "<br><br>";
	$paramArr = array();
	
	if($param['geoid']==0)
		$getGeofenceInfo = "SELECT * FROM tb_geofence_info WHERE tgi_clientId = ".$_SESSION[clientID]." AND tgi_name = '".$param['title']."'";
	else
		$getGeofenceInfo = "SELECT * FROM tb_geofence_info WHERE tgi_clientId = ".$_SESSION[clientID]." AND tgi_id = ".$param['geoid'];

	//echo $getGeofenceInfo;
	$resGeofenceInfo = $db->query($getGeofenceInfo);
	if($db->affected_rows == 0 )
	{
	
			$param1 = explode("&",$_SERVER['QUERY_STRING']);
			//print_r($param1);
			$param2 = explode("=",$param1[count($param1)-3]);
			$geoId = $param2[1];
			
			$param2 = explode("=",$param1[count($param1)-4]);
			$data['tgi_name'] = urldecode($param2[1]);
			
			$param2 = explode("=",$param1[count($param1)-2]);
			$data['tgi_description'] = urldecode($param2[1]);
			$k=1;
			while($k < count($param1)-4)
			{
				$param3 = explode("=",$param1[$k]);
				$k++;
				$param4 = explode("=",$param1[$k]);
				$pts .= $param4[1].",".$param3[1]."#";
				$k++;
			}
			$data['tgi_coordinates'] = $pts;
			$data['tgi_parameter'] = urldecode($_SERVER['QUERY_STRING']);
			$data['tgi_clientId'] = $_SESSION[clientID];
			$data['tgi_isActive'] = 1;	
			
			//print_r($data);
			//exit;
			if($db->query_insert("tb_geofence_info", $data))
				$res = 1;
			else $res = 0;
			
			echo $res;

	}
	else
	{
		if($param['geoid']==0)
		{
			echo 2;
		}
		else
		{
			$fetchGeofenceInfo = @mysql_fetch_assoc($resGeofenceInfo);
	
			$param1 = explode("&",$_SERVER['QUERY_STRING']);
			//print_r($param1);
			//echo "<br><br>";
			$param2 = explode("=",$param1[count($param1)-3]);
			$geoId = $param2[1];
			
			$param2 = explode("=",$param1[count($param1)-4]);
			$data['tgi_name'] = urldecode($param2[1]);
			
			$param2 = explode("=",$param1[count($param1)-2]);
			$data['tgi_description'] = urldecode($param2[1]);
			$k=1;
			while($k < count($param1)-4)
			{
				$param3 = explode("=",$param1[$k]);
				$k++;
				$param4 = explode("=",$param1[$k]);
				$pts .= $param4[1].",".$param3[1]."#";
				$k++;
			}
			$data['tgi_coordinates'] = $pts;
			$data['tgi_parameter'] = urldecode($_SERVER['QUERY_STRING']);
			$data['tgi_clientId'] = $_SESSION[clientID];
			$data['tgi_isActive'] = 1;	
			
			//print_r($data);
			//exit;
			if($db->query_update("tb_geofence_info", $data , "tgi_id =".$geoId))
				$res = 3;
			else $res = 0;
			
			echo $res;
		}
	}
}
if(isset($_GET[deleteGeoId]) && $_GET[deleteGeoId]!='')
{
	$sql = "UPDATE tb_geofence_info SET tgi_isActive = 0 WHERE tgi_id=".$_GET[deleteGeoId];
	$row = $db->query($sql); 
	if($row)
		echo 4;
	else
		echo 5;
}
?>