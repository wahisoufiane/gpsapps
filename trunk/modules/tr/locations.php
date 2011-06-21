<?php
@session_start();
@ob_start();
error_reporting (E_ALL ^ E_NOTICE);

require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 


require_once("../../includes/GPSFunction.php");

$temp="0";
//print_r($_GET);
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
function liveKmsAllPerDay($lData)
{ 
	$timeArr = array();
	$ptArr = array();
	$tmpDist = -1;
	//$ptArr = array();
	$cnt = 1;
	$totalDistance = 0;
	$data1=explode("#",$lData);
	for($j1=0;$j1<count($data1);$j1++)
	{
		$data2=explode("@",$data1[$j1]);

		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);
			$geodate = $data3[8];
			$geoTime = $data3[9];
			$curTime = explode(":",$data3[9]);
			$curTime = (($curTime[0] * 60) + $curTime[1]);
			//echo "<br>";

			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			
			if(($pos1>0 && $pos2>0))
			{
				
				if(!in_array($geoTime,$timeArr))
				{
					$ptArr[]= $pos1.",".$pos2;
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
		//exit;
	}
	//echo $j1;
	//echo round($totalDistance,2);
	$ptArr = implode("@",$ptArr); 
	//echo $ptArr;
	//exit;
	$finalData = round($totalDistance)."#".$ptArr;
	return $finalData;
}
function liveKmsPerDay($lData,$sTime1,$eTime1)
{ 
	$timeArr = array();
	$ptArr = array();
	//$ptArr = array();
	$cnt = 0;
	$totalDistance = 0;
	$data1=explode("#",$lData);
	for($j1=0;$j1<count($data1);$j1++)
	{
		$data2=explode("@",$data1[$j1]);

		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);
			$geodate = $data3[8];
			$geoTime = $data3[9];
			$curTime = explode(":",$data3[9]);
			$curTime = (($curTime[0] * 60) + $curTime[1]);
			//echo "<br>";
			//echo $sTime1.">=".$data3[9]." ".$curTime."<=".$eTime1."<br>";
			if($curTime >= $sTime1 && $curTime<=$eTime1)
			{
			//echo $geoTime." ".$geodate." ".$sTime1.">=".$curTime."<=".$eTime1."<br>";
			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			
			if($pos1>0 && $pos2>0)
			{
				
				if(!in_array($geoTime,$timeArr))
				{
					$ptArr[] = $pos1.",".$pos2;
					//echo $cnt;
					if($cnt==0)
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
				$cnt++;
			}
		}
		array_push($timeArr,$geoTime);
	}
	//echo $j1;
	$ptArr = implode("@",$ptArr);
	//echo round($totalDistance,2);
	//echo $ptArr;
	//exit;
	$finalData = round($totalDistance)."#".$ptArr;
	return $finalData;
}

function gpspathFun($clientId,$date_offline,$deviceIMEI,$sTime,$eTime)
{
	//$date_offline = $_GET[date_offline];
	
	$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/".$deviceIMEI.".txt"; ;
	
	if(chk_folder($path1))
	{
		$file1 = @fopen($path1, "r");
		if($file1)
		{
			$i=0;
			while(!feof($file1))
			{
			  $data1= fgets($file1);				 
			}
			$data = getSortedData($data1);
			return $data;
			fclose($file1);
		}
		else
		{
			$data=0;
			//storeData($data);
		}
	}
		
} //$path1=$dataPath."client_".$_SESSION[clientID]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/".$_GET["deviceIMEI"].".txt";
if(isset($_GET[sDate]) && isset($_GET[sTime]) && isset($_GET[eDate]) && isset($_GET[eTime]) && isset($_GET[deviceIMEI]))
{

	if($_GET[sDate] == $_GET[eDate])
	{
		$data = gpspathFun($_SESSION[clientID],$_GET[sDate],$_GET["deviceIMEI"],$_GET[sTime],$_GET[eTime]);	
		$km = liveKmsPerDay($data,$_GET[sTime],$_GET[eTime]);
		$devName = getDeviceName($_GET["deviceIMEI"]);  

		$data1=explode("#",$data);
		$latnlong_values="";
		
		$km = explode("#",$km);
		$totKm += $km[0];
		$totPath .= $km[1];
		//print_r($km);
		$xml = '<gps>';
		$timeArr= array();
		$totalDistance=0;
		
		$ct = 0;
		$j1=0;
		while($j1<count($data1))		
		{
			$data2=explode("@",$data1[$j1]);
			if(count($data2)>1)
			{
			$data3=explode(",",$data2[1]);
			//print_r($data3);
			$geodate = $data3[8]." ".$data3[9];
			$geoTime = $data3[9];
			$curTime = explode(":",$data3[9]);
			$curTime = (($curTime[0] * 60) + $curTime[1]);
			
				if($curTime >= $_GET[sTime] && $curTime<=$_GET[eTime])
				{
					$pos1=calLat($data3[2]);
					$pos2=calLong($data3[1]);
					if($pos1>0 && $pos2>0)
					{
						
						if(!in_array($geoTime,$timeArr))
						{
							//$geoTime." ".$ct++."<br>";
							$mph = $data3[3];
							$direction = $data3[4];
							$altitute = $data3[5];
							$deviceIMEI = $data3[0];
							$sessionID = $_GET["sessionID"] ;
							$accuracy = $data3[6];
							$extraInfo = $data3[6];
					
					$xml .='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" altitute="'.$altitute.'" curTime = "'.$curTime.'" distance="'.$km[0].'" gpsTime="'.date("h:i A",strtotime($geoTime)).'" geodate="'.$geodate.'" deviceIMEI="'.$deviceIMEI. '" deviceName="'.$devName.
					'" sessionID="'.$sessionID.'"  extraInfo="'.$extraInfo.'"  route="'.$rtName.'"/>';
					
						}
					}
				}
			}
		array_push($timeArr,$geoTime);
		if($j1>3)				
		{
			$dif = count($data1)-$j1;
			if($dif > 10)
				$j1 = $j1+10;
			else
				$j1 = $j1+1;
			//echo $dif." ".$j1." ".count($data1)."<br>";
		}
		else
		{
			$j1 = $j1+1;
			//echo $dif." ".$j1." ".count($data1)."<br>";
		}
		//exit;
		}// while
		$xml .= '<OtherData totPt="'.$totPath.'" geoData="" geoPointName="" totalDist="'.$totKm.'" />';	
		$xml .= '</gps>';	
		header('Content-Type: text/xml');	
		echo $xml;
	}
	else
	{
		$z = GetDays($_GET[sDate],$_GET[eDate]);
		//print_r($z);
		$cont = 1;
		
		$xml = '<gps>';
		//echo count($z);
		for($y=0; $y<count($z); $y++)
		{ 
			if($y == 0) 
			{
				$strtTime = $_GET[sTime];
				$endTime = 1439;
			}
			elseif($y == count($z)-1) 
			{
				$strtTime = 0;
				$endTime = $_GET[eTime];
			}
			elseif($y < count($z)-1) 
			{
				$strtTime = 0;
				$endTime = 1439;
			}
			//echo $strtTime ."-".$endTime;
			/*else
			{
				$strtTime = $_GET[sTime];
				$endTime = $_GET[eTime];
			}	*/
			//$xml .=gpspathFun($_SESSION[clientID],$z[$y],$_GET["deviceIMEI"],$_GET[sTime],$_GET[eTime]);	
			$data = gpspathFun($_SESSION[clientID],$z[$y],$_GET["deviceIMEI"],$strtTime,$endTime);	
			$km = liveKmsPerDay($data,$strtTime,$endTime);
			//$km = 
			//print_r($km);
			//exit;
			$devName = getDeviceName($_GET["deviceIMEI"]);  
	
			$data1=explode("#",$data);
			$latnlong_values="";
			
			$km = explode("#",$km);
			$totKm += $km[0];
			$totPath .= $km[1];
			//echo $totKm." ".$totPath."<br><br>";
			//print_r($km);
			//$xml .= 'totPt="'.$totPath.'" geoData="" geoPointName="" totalDist="'.$totKm.'">';
			$timeArr= array();
			$totalDistance=0;
			
			$ct = 0;
			$j1=0;
			while($j1<count($data1))		
			{
				$data2=explode("@",$data1[$j1]);
				if(count($data2)>1)
				{
				$data3=explode(",",$data2[1]);
				$geodate = $data3[8]." ".$data3[9];
				$geoTime = $data3[9];
				$curTime = explode(":",$data3[9]);
				$curTime = (($curTime[0] * 60) + $curTime[1]);
				
				//echo $geoTime." ".$geodate." ".$strtTime.">=".$curTime."<=".$endTime."<br>";
					if($curTime >= $strtTime && $curTime<=$endTime)
					{
					//echo $geoTime." ".$geodate." ".$strtTime." ".$curTime."<=".$endTime."<br>";
						$pos1=calLat($data3[2]);
						$pos2=calLong($data3[1]);
						if($pos1>0 && $pos2>0)
						{
							
							if(!in_array($geoTime,$timeArr))
							{
								
								$geoTime." ".$ct++."<br>";
								$mph = $data3[3];
								$direction = $data3[4];
								$altitute = $data3[5];
								$deviceIMEI = $data3[0];
								$sessionID = $_GET["sessionID"] ;
								$accuracy = $data3[6];
								$extraInfo = $data3[6];
							
							$xml .='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" altitute="'.$altitute.'" curTime = "'.$curTime.'" distance="'.$km[0].'" gpsTime="'.date("h:i A",strtotime($geoTime)).'" geodate="'.$geodate.'" deviceIMEI="'.$deviceIMEI. '" deviceName="'.$devName.
							'" sessionID="'.$sessionID.'" extraInfo="'.$extraInfo.'"  route="'.$rtName.'"/>';
						
							}
						}
					}
				}
			array_push($timeArr,$geoTime);
			if($j1>0)				
			{
				$dif = count($data1)-$j1;
				if($dif > 10)
					$j1 = $j1+10;
				else
					$j1 = $j1+1;
				//echo $dif." ".$j1." ".count($data1)."<br>";
			}
			else
			{
				$j1 = $j1+1;
				//echo $dif." ".$j1." ".count($data1)."<br>";
			}
			//exit;
			}// while
			
			
		}
		$xml .= '<OtherData totPt="'.$totPath.'" geoData="" geoPointName="" totalDist="'.$totKm.'" />';	
		$xml .= '</gps>';
		
		header('Content-Type: text/xml');	
		echo $xml;
	}


}
else
{
	gpspathFunAll($_SESSION[clientID],$_GET["date_offline"],$_GET["deviceIMEI"]);	
}

function gpspathFunAll($clientId,$date_offline,$deviceIMEI)
{
	$date_offline = $_GET[date_offline];
	$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/".$deviceIMEI.".txt"; ;
	
	if(chk_folder($path1))
	{
		$file1 = @fopen($path1, "r");
		if($file1)
		{
			$i=0;
			while(!feof($file1))
			{
			  $data1= fgets($file1);				 
			}
			$data1 = getSortedData($data1);
			
			$km = liveKmsAllPerDay($data1);
			//exit;
			  //$data = $data1;
			$devName = getDeviceName($_GET["deviceIMEI"]);  
			storeData($data1,$km,$devName,'','');
			fclose($file1);
		}
		else
		{
			$data=0;
			storeData($data);
		}
	}
		
}
function getDeviceName($imeiNo)
{
		$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
		$db->connect(); 
		$getDevName = "SELECT di_deviceId,di_deviceName  FROM tb_deviceinfo WHERE di_imeiId =".$imeiNo." AND di_status = 1 AND di_clientId =".$_SESSION[clientID];
		$resultRow = $db->query($getDevName);
		$deviceRecord = $db->fetch_array($resultRow);
		if($deviceRecord[di_deviceName] == '')
			return $deviceRecord[di_deviceId];
		else
			return $deviceRecord[di_deviceName];
		
}

function storeData($data,$km,$devName,$sTime2,$eTime2)
{
	//echo $data;
	$data1=explode("#",$data);
	$latnlong_values="";
	
	//$xml = '<gps geoData="'.$latnlong_values.'" geoPointName="'.$point_names.'">';
	$km = explode("#",$km);
	$totKm += $km[0];
	$totPath .= $km[1];
	//print_r($km);
	//exit;

	$xml = '<gps>';
	$timeArr= array();
	$totalDistance=0;

	$ct = 0;
	$j1=0;
	while($j1<count($data1))
	{
		$data2=explode("@",$data1[$j1]);
		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);
			//print_r($data3);
			$date = ($data3[8]/1000);
			$date = $data3[8]." ".$data3[9];
			$geodate = $data3[8]." ".$data3[9];
			$geoTime = $data3[9];

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
					$mph = $data3[3];
					$direction = $data3[4];
					$altitute = $data3[5];
					$deviceIMEI = $data3[0];
					$sessionID = $_GET["sessionID"] ;
					$accuracy = $data3[6];
					$extraInfo = $data3[6];
		
                  $fule_param = $data3[7]; 
					  $d =preg_split("/[\s,]+/",$fule_param);
					  foreach($d as $e){
                          if(substr($e,0,3)=='[9='){
                            $fuel= str_replace(array('[9=',']'),'',$e); 
						  }
						  if(substr($e,0,3)=='[1='){
                            $engine= str_replace(array('[1=',']'),'',$e); 
							if($engine=='1'){
                                 $engine_st = 'On';
							} else {
								$engine_st = 'Off';
							}
						  }
					  }

		$xml .='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" altitute="'.$altitute.'" curTime = "'.$curTime.'" distance="'.round($totalDistance).'" gpsTime="'.date("h:i A",strtotime($geoTime)).'" geodate="'.$geodate.'" deviceIMEI="'.$deviceIMEI. '" deviceName="'.$devName.
	  '" sessionID="'.$sessionID.'"  extraInfo="'.$extraInfo.'"  route="'.$rtName.'"  other="'.$data3[7].'"/>';

						
					}

			}
		}
	array_push($timeArr,$geoTime);
	if($j1>0)				// && $j1 < count($data1))
	{
		$dif = count($data1)-$j1;
		if($dif > 10)
			$j1 = $j1+10;
		else
			$j1 = $j1+1;
		//echo $dif." ".$j1." ".count($data1)."<br>";
	}
	else
	{
		$j1 = $j1+1;
		//echo $dif." ".$j1." ".count($data1)."<br>";
	}
	
	}
	$xml .= '<OtherData totPt="'.$totPath.'" geoData="" geoPointName="" totalDist="'.$totKm.'" />';	
	$xml .= '</gps>';
	
	header('Content-Type: text/xml');
	echo $xml;
}

?>
