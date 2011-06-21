<?php
@ob_start();
require_once("../db/MySQLDB.php");
require_once("../superAdmin/superAdmin.php");
require_once("../superAdmin/superAdminSF.php");
require_once("../Utilities/GPSFunction.php");

$temp="00-00-00 00:00:00am";

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


function gpspathFun($clientId,$date_offline,$phoneNumber)
{
$dataPath= $GLOBALS["dataPath"];
$path1=$dataPath."client_".$clientId."/".date('d-m-Y',strtotime($date_offline))."/".$phoneNumber.".txt"; 
	if(chk_folder($path1))
	{
			$file1 = @fopen($path1, "r");
		if($file1)
		{
		$i=0;
		while(!feof($file1))
		  {
		   $data= fgets($file1);				 
		   //$i++;
		  }
		  storeData1($data);
		fclose($file1);
		}else
		{
			$data=0;
			storeData1($data);
		}
	}
		
} //$path1=$dataPath."client_".$_GET[sessionID]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/".$_GET["phoneNumber"].".txt";

if($_SESSION[superAdminSID]!='')
{
$matches = array();
$c=0;
	$client_array=array();
	$res_clients=SuperAdminSF::getClients();
	while($clientIds=mysql_fetch_assoc($res_clients))
	{
		array_push($client_array,$clientIds);
	}

for($cl=0;$cl<count($client_array);$cl++)
{ 	
	gpspathFun($client_array[$cl][clin_id],$_GET["date_offline"],$_GET["phoneNumber"]);
	//echo $dataPath."client_".$client_array[$cl][clin_id]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/".$_GET["phoneNumber"].".txt";
	
}
}
else
{
gpspathFun($_GET[sessionID],$_GET["date_offline"],$_GET["phoneNumber"]);	
//$path1=$dataPath."client_".$_GET[sessionID]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/".$_GET["phoneNumber"].".txt";
	//echo $latnlong_values;	
}


function calVal1($val)
{
	$g9=round($val,0);
	$h9=round(60*($val-$g9),0);
	$i9=round(3600*(($val-$g9)-($h9/60)),4);
	$e9=($g9+($h9/100))+($i9/10000);
	return round(floatval($e9),4);
}

function calVal2($val)
{

	$v1=round($val,0);
	$v2=(($val-$v1)*100)/60;
	$v3=($v1+$v2);
	return round($v3,5);
}
function getDriverDetails($clientid,$date_offline,$phoneNumber)
{
		$getDriver="SELECT di_id,di_driverId,di_license,di_firstName,di_lastName,di_phone1,di_profile_image from driver_info join 
		project_task_info ON di_id=pti_driver_id JOIN vehicle_info ON pti_vehicle_id = vi_id JOIN schedule_info ON pti_si_id = si_id
		WHERE si_clientId =".$clientid." AND '".$date_offline."' BETWEEN DATE_FORMAT( pti_start_date, '%Y-%m-%d' ) AND DATE_FORMAT( pti_end_date, '%Y-%m-%d' ) AND vi_reg_no = '".$phoneNumber."' LIMIT 0 , 30";
		$resDriver=mysql_query($getDriver); 
		if(mysql_num_rows($resDriver)>0)
			{ $fetchDriver=@mysql_fetch_assoc($resDriver);	
				return  $fetchDriver; }


}

function storeData1($data)
{
	//echo $data;
	$data1=explode("#",$data);
	$latnlong_values="";
	if($_GET[sessionID]!='')
	{
	$getDriver="SELECT di_id,di_driverId,di_license,di_firstName,di_lastName,di_phone1,di_profile_image from driver_info,gps_task_info, vehicle_info  WHERE gti_clientId=".$_GET[sessionID]." AND di_id=gti_driver_id AND  '".$_GET[date_offline]."' BETWEEN DATE_FORMAT( gti_start_date, '%Y-%m-%d' ) AND DATE_FORMAT( gti_end_date, '%Y-%m-%d' ) AND vi_reg_no = '".$_GET[phoneNumber]."' LIMIT 0 , 30";
	
		$resDriver=mysql_query($getDriver);
	$fetchDriver=@mysql_fetch_assoc($resDriver);	

	$qr="SELECT * FROM gps_geopoints_info WHERE gpi_clientID=".$_SESSION["clientID"];
	$rs_geofence_details = mysql_query($qr);
	if(@mysql_num_rows($rs_geofence_details) == 0)
	{
		$latnlong_values = "";
	}
	else
	{
		$latnlong_values = "";
		$ct=0;
		while($fetch_geofence_details = @mysql_fetch_assoc($rs_geofence_details))
		{
			//print_r($fetch_geofence_details);
			if($ct==0)
			{
				$latnlong_values = $fetch_geofence_details[gpi_miles].",".$fetch_geofence_details[gpi_latVal].",".$fetch_geofence_details[gpi_longVal]."@";
				$ct++;
			}
			else
			{
				$latnlong_values .= $fetch_geofence_details[gpi_miles].",".$fetch_geofence_details[gpi_latVal].",".$fetch_geofence_details[gpi_longVal]."@";
				$ct++;
			}
			if($ct!=@mysql_num_rows($rs_geofence_details))
			$point_names.=$fetch_geofence_details[gpi_stopName].",";
			else $point_names.=$fetch_geofence_details[gpi_stopName];
		}
		//echo $latnlong_values;
	}

	}
	else
	{ 
		$client_array=array();
		$res_clients=SuperAdminSF::getClients();
		while($clientIds=mysql_fetch_assoc($res_clients))
		{ array_push($client_array,$clientIds); }
			
		for($cl=0;$cl<count($client_array);$cl++)
		{ 
		$fetchDriver=getDriverDetails($client_array[$cl][clin_id],$_GET[date_offline],$_GET[phoneNumber]);
		}
	}

	$xml = '<gps geoData="'.$latnlong_values.'" geoPointName="'.$point_names.'">';
	$timeArr= array();
	$totalDistance=0;
if( $_GET[from_hrs]!='' && $fm = $_GET[from_mins]!='' && $th = $_GET[to_hrs]!='' && $tm = $_GET[to_mins]!='')
{
	for($j1=0;$j1<count($data1);$j1++)
	{
		$data2=explode("$",$data1[$j1]);
		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);

			if(date("d-m-Y", strtotime($_GET[date_offline]))==date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3])))
			{
			$fh = $_GET[from_hrs];  
			$fm = $_GET[from_mins];
			$fs = '00';
			
			$th = $_GET[to_hrs];
			$tm = $_GET[to_mins];
			$ts = '00';
			
			$h=date("H",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));				
			$m=date("i",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$s=date("s",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			
			if($fh == $th && $fh==$h)
			{
				if($m >= $fm && $m < $tm){	$show=1;	}
				else	{ $show = 0; }
			}
			else if($h >= $fh && $h <= $th)
			{
				if($h < $th)
				{
					if($m >= $fm || $m < $tm){	$show=1;	}
					else	{ $show = 0; }
				}
				else if($h == $th)
				{
					if($m >= $fm && $m < $tm){	$show=1;	}
					else	{ $show = 0; }
				}
			}
			else
			{ $show = 0; }
			
			if($show == 1)
			{	
			$vehi=$data3[0];
			$geodate=date("d-m-Y h:i A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$geoTime=date("h:i:s A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$pos1=convertLat(calLat($data3[7]));
			$pos2=convertLong(calLong($data3[8]));
			if($pos1>0 && $pos2>0)
			{
			if(!in_array($geoTime,$timeArr))
			{
				//echo $sPt=$pos1."#".$pos2;
			if($cnt==1)
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
			$mph = $data3[9];
			$direction = $data3[10];
			$distance = '';
			$date = $date;
			$locationMethod = '327681';
			//echo getDateFromJavaDate($date);
			$phoneNumber = $data3[0];
			$sessionID = $_GET["sessionID"] ;
			$accuracy = 11;
			$locationIsValid = yes;
			$extraInfo = $data3[11];
		
	
	$xml.='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" distance="'.round($totalDistance,2).'" locationMethod="327681" gpsTime="'.$geodate.'" phoneNumber="'.$phoneNumber.
  '" sessionID="'.$sessionID.'" accuracy="11" isLocationValid="yes" extraInfo="'.$extraInfo.'"  route="'.$rtName.'"/>';
	}
	}
	}
	array_push($timeArr,$geoTime);
	}
	}
}
}
else
{
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

			
			
			$mph = $data3[9];
			$direction = $data3[10];
			$distance = '';
			$date = $date;
			$locationMethod = '327681';
			//echo getDateFromJavaDate($date);
			$phoneNumber = $data3[0];
			$sessionID = $_GET["sessionID"] ;
			$accuracy = 11;
			$locationIsValid = yes;
			$extraInfo = $data3[11];
		
	
	$xml.='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" distance="'.round($totalDistance,2).'" locationMethod="327681" gpsTime="'.$geodate.'" phoneNumber="'.$phoneNumber.
  '" sessionID="'.$sessionID.'" accuracy="11" isLocationValid="yes" extraInfo="'.$extraInfo.'"  route="'.$rtName.'"/>';
	}
	}
	}
	array_push($timeArr,$geoTime);
}



}
	//echo $totalDistance;
	$xml .= '</gps>';

	header('Content-Type: text/xml');
	echo $xml;

}
?>
