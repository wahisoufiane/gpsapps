<?php
@ob_start();
require("gpsapp/includes/config.inc.php"); 
require("gpsapp/includes/Database.class.php"); 
require("gpsapp/includes/GPSFunction.php"); 
require("gpsapp/includes/smsSF.php"); 

//print_r($_GET);
//http://72.232.217.94/lc.php?receivedon=54999&amp;from=919999477288&amp;message=aj
//http://www.chekhra/?mobilenumber=9949090472&message=CHD MH06AQ1949&receivedon=XXXX
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

if($_GET[from]!='' && $_GET[message]!='')
{
$_GET[message]=urlencode($_GET[message]);
$src=explode("+",$_GET[message]);
//echo $src[1];	http://gpsapp.shastrasoftech.com/twoway.php?from=#from#&message=#message# 
//$src=str_replace("%14","",$src[1]);
//$src=str_replace("%02","",$src);
$src=$src[1];

$cltId='';


	$getVehi="SELECT ci_clientName,di_deviceId,di_deviceName,di_imeiId,ci_clientId FROM tb_deviceinfo,tb_clientinfo WHERE ci_id = di_clientId AND di_mobileNo='".$src."' AND di_status=1";
	$resVehi=$db->query($getVehi);
	if($db->affected_rows > 0 )
	{
		$fetVehi=$db->fetch_array($resVehi);
		//print_r($fetVehi);
		$cltId=$fetVehi[di_imeiId];
		$path=$GLOBALS[dataPath]."src/data/".date('d-m-Y')."/".$fetVehi[di_imeiId].".txt"; ;
		//$path=$dataPath."client_".$fetVehi[vi_clientId]."/10-05-2009/".$src.".txt";
		if($fetVehi[di_deviceName] != "")
			$devName = $fetVehi[di_deviceName];
		else
			$devName = $fetVehi[di_deviceId];
			
		PrintKMLFolder($path,$devName,$fetVehi[ci_clientId]);
		insertData($src);
	}
	else
	{
		print("Invalid data.");
		$from = "Shastra";
		$to = $_GET[from];
		//sendLocation($from,$to,$message,$fetVehi[ci_clientId]);
	}
	
}
else
{
	print("Parameter missing. Please check the data once.");
	$from = "";
	$to = $_GET[from];
	//sendLocation($from,$to,$message);
	//echo "Invalid";
}

function insertData($src)
{
	$folder="gpsapp/altSrc/SMSData/";
	if(!is_dir($folder))
	{
		@mkdir($folder, 0777);
	}
	$subFolder=$folder.date("d-m-Y")."/";
	if(!is_dir($subFolder))
	{
		@mkdir($subFolder, 0777);
	}
	
	$strFilename=$subFolder.$src;
	$myFile = $strFilename.".txt";
	$fh = @fopen($myFile, 'a') or die("can't open file");
	$stringData = $_GET[from]." ".$_GET[message]." ".date("h:i:s A")." ".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."#";
	@fwrite($fh, $stringData);
	@fclose($fh);
	//echo "Thank you";
	
}

function PrintKMLFolder($path1,$vehiName,$resellID)
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
				$mph = $data3[3];
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
		//$finalData = round($totalDistance).",".$locat.",".$data3[3].",".$geodate.",".$geoTime;
		@fclose($file1);
		
		print("Dear Client! ".$vehiName." is located at ".$locat." on ".$geodate.", Sd = ".$mph." k/h TD = ".round($totalDistance)." km - VTS Alert.");
		$from = "";
		$to = $_GET[from];
		//sendLocation($from,$to,$msg,$resellID);
		//sendGatewaySMS($from,$to,$msg,$smsUri,$smsUname,$smsPwd,$smsSender);
		
		//return $finalData;
	}
	else
	{
		//return false;
		print_r("Dear Client! ".$vehiName." is not avilable today - VTS Alert.");
		$from = "";
		$to = $_GET[from];
		//sendLocation($from,$to,$msg,$resellID);
		//sendGatewaySMS($from,$to,$msg,$smsUri,$smsUname,$smsPwd,$smsSender);
	}


}

function sendLocation($from,$to,$message,$resellID)
{
	//echo "From- ".$from."	TO-	".$to."	Msg-	".$message;
	
	$getReseller = "select ci_smsGatewayUri,ci_smsGatewayUsername,ci_smsGatewayPassword,ci_smsGatewaySenderId from tb_clientinfo where ci_id = ".$resellID;
	$resReseller = mysql_query($getReseller);
	$fetReseller = @mysql_fetch_assoc($resReseller);
	
	
	//$res=smsSF::sendGatewaySMS($from,$to,$msg,$fetVehi[ci_smsGatewayUri],$fetVehi[ci_smsGatewayUsername],$fetVehi[ci_smsGatewayPassword],$fetVehi[ci_smsGatewaySenderId]);
	if($res)
	{
		//mysql_query("INSERT INTO sms_gate_way(sgw_from,sgw_mobile_no,sgw_message,sgw_status,sgw_sent_time,sgw_ip_address) VALUES('".$from."','".$to."','".addslashes($message)."',1,'".date('Y-m-d H:i:s')."','".$_SERVER['SERVER_ADDR']."')");
		echo "yes";
	}
	else
	{
		echo "no";
	}
}


?>