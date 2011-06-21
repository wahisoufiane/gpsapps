<?php
@session_start();
@ob_start();
require_once("../db/MySQLDB.php");
require_once("../superAdmin/superAdmin.php");
require_once("../superAdmin/superAdminSF.php");

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
		fclose($fp_load);
		return $content;
	}
	else
	{
		//header("Location:index.php?ch=dataServerConn");
	}
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
function calLat($lat)
{
	$latArr=array();
	$lat=str_split($lat);
	$l2=0;
	for($l1=count($lat);$l1>=0;$l1--)
	{
		if($l1==7)
		{
			$latArr[$l2]=" ".$lat[$l1];
			$l2++;
		}
		else if($l1==3)
		{
			$latArr[$l2]=".".$lat[$l1];
			$l2++;
		}
		else if($l1==1)
		{
			$latArr[$l2]=" ".$lat[$l1];
			$l2++;
		}
		else
		{
			$latArr[$l2]=$lat[$l1];
			$l2++;
		}
	}
	$latArr=implode($latArr);
	return strrev($latArr);;
}
function calLong($long)
{
	$longArr=array();
	$long=str_split($long);
	$l2=0;
	for($l1=count($long);$l1>=0;$l1--)
	{
		if($l1==8)
		{
			$longArr[$l2]=" ".$long[$l1];
			$l2++;
		}
		else if($l1==4)
		{
			$longArr[$l2]=".".$long[$l1];
			$l2++;
		}
		else if($l1==2)
		{
			$longArr[$l2]=" ".$long[$l1];
			$l2++;
		}
		else
		{
			$longArr[$l2]=$long[$l1];
			$l2++;
		}
	}
	$longArr=implode($longArr);
	return strrev($longArr);;
}
function gpspathAll($source,$vehicle)
{
$path1=$source.$vehicle; 
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
	  return $data;
	fclose($file1);
	}else
	{
		$data=0;
		return $data;
	}
}
} 
if(isset($_GET[sessionID]) && $_GET[sessionID]=='all')
{
		$path1=$dataPath."client_".$_SESSION[clientID]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/";
		$matches = array();
		$c=0;
		preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);
		//print_r($matches[2]);
		$xml = '<gps>';
		for($i=1;$i<count($matches[2]);$i++)
		{
			//echo $mydata=gpspathFun($path1,$matches[2][$i]);
			$mydata=gpspathAll($path1,$matches[2][$i]);
			$data1=explode("#",$mydata);
			$data2=explode("$",$data1[count($data1)-2]);
				if(count($data2)>1)
				{
					$data3=explode(",",$data2[1]);			
					$vehi=$data3[0];
					$date=date("Y-m-d H:i:s",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
					$info.="Direction : ".$data3[10]."&lt;br&gt;";
					$pos1=calLat(($data3[7]));
					$pos2=calLong(($data3[8]));				
						
					$lat = $pos1;
					$lng = $pos2;
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
		
	
	$xml.='<locations latitude="'.$lat.'" longitude="'.$lng. '" speed="'.$mph. '" direction="'.$direction.'" distance="" locationMethod="327681" gpsTime="'.$date.'" phoneNumber="'.$phoneNumber.
  '" sessionID="'.$sessionID.'" accuracy="11" isLocationValid="yes" extraInfo="'.$extraInfo.'" />';
					
				$vehicle_reg_no[$c] = explode('.',$matches[2][$i]);
				$c++;
		}
		}
		$xml .= '</gps>';
}
	header('Content-Type: text/xml');
	echo $xml;
?>
