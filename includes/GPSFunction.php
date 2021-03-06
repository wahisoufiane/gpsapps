<?php

$dataPath="http://122.183.93.232/";
//define("GOOGLE_API_KEY","ABQIAAAAEcRU5S4wllAASrNAt60gdRSavmhanSNe5Ln__CcAfNSvKrVNQBSuHvAXNB1-T9M9kZ4MwDUPMS_dPQ"); gpsapp.shastrasoftech.com
//define("GOOGLE_API_KEY","ABQIAAAAEcRU5S4wllAASrNAt60gdRTXPxY9MtDFaNH_Y0GvYqNVOWWIUhSz4Rqrd7PknS26b2_7WD4vB1VrZQ"); //india.abstracking.com

if($_SERVER['HTTP_HOST'] == "gpsapp.shastrasoftech.com"||$_SERVER['HTTP_HOST'] == "india.abstracking.com" || $_SERVER['HTTP_HOST'] == "localhost"){
define("GOOGLE_API_KEY","ABQIAAAAEcRU5S4wllAASrNAt60gdRSavmhanSNe5Ln__CcAfNSvKrVNQBSuHvAXNB1-T9M9kZ4MwDUPMS_dPQ");
}else{
define("GOOGLE_API_KEY","ABQIAAAAEcRU5S4wllAASrNAt60gdRTXPxY9MtDFaNH_Y0GvYqNVOWWIUhSz4Rqrd7PknS26b2_7WD4vB1VrZQ");
}
function GetDays($sStartDate, $sEndDate){
  $sStartDate = date("Y-m-d", strtotime($sStartDate));
  $sEndDate = date("Y-m-d", strtotime($sEndDate));

  $aDays[] = $sStartDate;

  $sCurrentDate = $sStartDate;

  while($sCurrentDate < $sEndDate){
    // Add a day to the current date
    $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
    $aDays[] = $sCurrentDate;
  }
  return $aDays;
}

function getGeofenceStatus($latnlong_values,$lngval,$latval)
{
	//echo $latval." ".$lngval."<br>";
	
	$npoints = count($latnlong_values); // number of points in polygon
	// this assumes that last point is same as first
	 $xnew;$ynew;$xold;$yold;$x1;$y1;$x2;$y2;$i;
	 $in=false;

	 if ($npoints < 3) { // points don't describe a polygon
		  return false;
	 }
	 $spt1=explode(",",$latnlong_values[$npoints-1]);
      $xold=$spt1[0];$yold=$spt1[1];
	
	 for ($i=0 ; $i < $npoints ; $i++) {
		 $spt=explode(",",$latnlong_values[$i]);
		  $xnew=$spt[0]; $ynew=$spt[1];
		 
		  if ($xnew > $xold) {
			   $x1=$xold; $x2=$xnew; $y1=$yold; $y2=$ynew;
		  }else{
			   $x1=$xnew; $x2=$xold; $y1=$ynew; $y2=$yold;
		  }
		  // echo $latval.' '.$lngval."<br><br>";
		   
		  if (($xnew < $latval) == ($latval <= $xold) && (($lngval-$y1)*($x2-$x1) < ($y2-$y1)*($latval-$x1))) {
		 
			  $in=!$in;
		  } 
		  
		  $xold=$xnew; $yold=$ynew;
	 }; // for
	
	 //return $in;
	 if($in)
	{
		return 1;
	}
	else
	{
		return 0;	
	}
}
function getDistance($lat1, $lon1, $lat2, $lon2)
{
$rad = doubleval(pi()/180.0);

$lon1 = doubleval($lon1) * $rad;
$lat1 = doubleval($lat1) * $rad;
$lon2 = doubleval($lon2) * $rad;
$lat2 = doubleval($lat2) * $rad;

$theta = $lon2 - $lon1;
$distance = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));

if ($distance < 0)
{
$distance += pi();
}

$distance = $distance * 6371.2;
$miles = doubleval($distance * 0.621);
$distance = sprintf("%.2f", $distance);
$miles = sprintf("%.4f", $miles);

return $miles*1.61;
}


function calLat($lat)
{

	$latArr=array();
	$lat=str_split($lat);
	$l2=0;
	for($l1=count($lat);$l1>=0;$l1--)
	{
		if($l1==1)
		{
			$latArr[$l2]=".".$lat[$l1];
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
		if($l1==1)
		{
			$longArr[$l2]=".".$long[$l1];
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


function simpleGeocode($tlat,$tlong) {

$host = "maps.google.com";

	$addr=$tlat.",".$tlong;
	$datas = explode(",",geocode($addr, $host, GOOGLE_API_KEY));
	//print_r($datas);
	//if (! ($datas && $datas['Status']['code'])) {
//		return null;
//	}
//	$statusCode = $datas['Status']['code'];
//	if ($statusCode == "602" || ! $datas['Placemark']) {
//		return array();
//	} else if ($statusCode != "200") {
//		return null;
//	}
//	$result = array();
//	for ($t=0;$t<count(datas);$t++) {
//	   $result[] = $datas['Placemark'][$t]['address'];
//	   //$placemark['address'];
//	   //echo $datas['Placemark'][$t]['address'];
//	}
	return $datas[2]."-".$datas[3]."-".$datas[4]."-".$datas[5];
}

function geocode($addr, $host, $key) {
	if (! $key) { throw new Exception("Add your Google Maps API key to the source to use this function without passing it as a parameter."); }
	$url = "http://" . $host . "/maps/geo?output=csv&oe=utf-8&q=" . urlencode($addr) . "&key=" . $key;
	$json = @file_get_contents($url);
	if (! $json) { return null; }
	////$datas = json_decode($json, true);
	if (! $json) { return false; }
	return $json;
}

// Takes a member of the Google Maps API 'Placemark' array and converts it to something flatter and more manageable.
// Return value is assoc array with keys 'address', 'longitude' and 'latitude'
function parsePlacemark($placemark) {
	$result = array();
	$result['address'] = $placemark['address'];
	$coordinates = (($placemark['Point']['coordinates']) ? $placemark['Point']['coordinates'] : array());
	$result['longitude'] = $coordinates[0];
	$result['latitude'] = $coordinates[1];
	return $result;
}

function convert($v)
{
$D = split(" ", $v);
$M = split("\.",$D[1]);	
$s =((floatval($M[1])/10000)*60);
$res=$D[0]." ".$M[0]." ".$s." ".$D[2];
return ($res);
}

function convertLat($lat)
{

	$lat1=split(" ",convert($lat));
    // Retrieve Lat and Lon information
    $LatDeg = $lat1[0];
    $LatMin = $lat1[1];
    $LatSec = $lat1[2];

    // Assume the value to be zero if the user does not enter value
    if ($LatDeg=='')
      $LatDeg=0;
    if ($LatMin=='') {
      $LatMin=0;
    }
    if ($LatSec=='') {
      $LatSec=0;
    }

    // Check if any error occurred
   if ($LatDeg != round($LatDeg) || $LatMin != round($LatMin) ) {
      echo "ERROR";
    } else if ($LatDeg < -90 || $LatDeg > 90 || $LatMin < -60 || $LatMin > 60 || $LatSec < -60 || $LatSec > 60 ) {
      echo "ERROR";
    } else {
    // If no error, then go on

    // Retrieve the latitude direction for Degrees Decimal
        $LatDMSDirect = $lat1[3];


    // Change to absolute value
    $LatDeg = abs($LatDeg);
    $LatMin = abs($LatMin);
    $LatSec = abs($LatSec);
    //setAllEnabled(Location);

    // Convert to Decimal Degrees Representation
    $lat = $LatDeg + ($LatMin/60) + ($LatSec / 60 / 60);
    if ( $lat <= 90 && $lat >=0 )
    {

      // Rounding off
     //$lat = (round($lat*1000000)/1000000);

	  return ($lat);
      } else
        echo "ERROR!!";
    }
}

function convertLong($lng)
{

$lng1=split(" ",convert($lng));
    // Retrieve Lat and Lon information
    $LonDeg = $lng1[0];
    $LonMin = $lng1[1];
    $LonSec = $lng1[2];

    // Assume the value to be zero if the user does not enter value
    if ($LonDeg=='')
      $LonDeg=0;
    if ($LonMin=='') {
      $LonMin=0;
    }
    if ($LonSec==''){
      $LonSec=0;
    }

    // Check if any error occurred
   if ($LonDeg != round($LonDeg) || $LonMin != round($LonMin)) {
      echo "ERROR";
    } else if ($LonDeg < -180 || $LonDeg > 180 || $LonMin < -60 || $LonMin > 60 || $LonSec < -60 || $LonSec > 60) {
      echo "ERROR";
    } else {
    // If no error, then go on

    // Retrieve the longitude direction for Deg/Min/Sec
        $LonDMSDirect = $lng1[3];

    // Change to absolute value
    $LonDeg = abs($LonDeg);
    $LonMin = abs($LonMin);
    $LonSec = abs($LonSec);
    //setAllEnabled(Location);

    // Convert to Decimal Degrees Representation
    $lon = $LonDeg + ($LonMin/60) + ($LonSec / 60 / 60);
    if ( $lon <= 180 && $lon >= 0 )
    {

      // Rounding off
      //$lon = (round($lon*1000000)/1000000);

	  return ($lon);
      } else
        echo "ERROR!!";
    }
}
function drawCircle($lt1,$ln1,$radius) {
 
 $Cpoints = array();
 $Cy=0.0; $Cx=0.0;
 
      $d2r = pi()/180;
      $r2d = 180/pi();
      $Clat = $radius * 0.014483;  // Convert statute miles into degrees latitude radius * 0.014483;
     
      $Clng = $Clat/cos($lt1*$d2r); 
      for ($i=0; $i < 33; $i++) { 
       
        $d1 = (($i*10000)/16);
    	$d1=$d1/10000;
        $theta = pi() * ($d1); 
        $Cy = $lt1 + ($Clat * sin($theta)); 
		$Cx = $ln1 + ($Clng * cos($theta)); 	  
        $P = $Cx.",".$Cy; 
		array_push($Cpoints,$P);
		
        //$Cpoints.push()[i]=$P; 
      
      }
	  //print_r($Cpoints);
	return $Cpoints;
     
     }
function render ($lt1,$ln1,$radius){
 	$points = array();
    $distance = (($radius*5280)/3.2808399)/1000;
    for ($i = 0; $i < 72; $i++) {
      array_push($points,(destination($lt1,$ln1, $i * 360/72, $distance) ));
    }
    array_push($points,(destination($lt1,$ln1, 0, $distance) ));
	return $points;
    //this._points.push(destination(this._centerHandlePosition, 0, distance) );
    //this._polyline = new GPolyline(this._points, this._color, 6);
}
function destination($lt2,$ln2, $hdng, $dist) {
  $R = 6371; // earth's mean radius in km
  $oX; $oY;
  $x; $y;
  $d = $dist/$R;  // d = angular distance covered on earth's surface
  $hdng = $hdng * pi() / 180; // degrees to radians
  $oX = $lt2 * pi() / 180;
  $oY = $ln2 * pi() / 180;

  $y = asin( sin($oY)*cos($d) + cos($oY)*sin($d)*cos($hdng) );
  $x = $oX + atan2(sin($hdng)*sin($d)*cos($oY), cos($d)-sin($oY)*sin($y));

  $y = $y * 180 / pi();
  $x = $x * 180 / pi();
  return $x.",".$y;
}

function getSortedData($data1)
{
	//echo $data1;exit;	
	$dataArr = explode('#',$data1);
	for($i=0;$i<count($dataArr)-1;$i++)
	{
		$dtArray = explode(',',$dataArr[$i]);
		//print_r($dtArray);
		$newDate=$dtArray[9];
		$newdtArray1[$newDate] = $dataArr[$i];
	}
	ksort($newdtArray1);
	foreach ($newdtArray1 as $key => $val) 
	{
		$data.=$val."#";
	}
	return $data;
}



//		New Traxis
function calLatNew($lat)
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
			$latArr[$l2]="".$lat[$l1];
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
function calLongNew($long)
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
			$longArr[$l2]="".$long[$l1];
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
function getUDAddress($lt,$ln)
{
	//echo $lt;
	$sel_pts = "SELECT * FROM gps_geopoints_info WHERE gpi_clientID=".$_SESSION[clientID];						
	$rs_sel_pts = mysql_query($sel_pts);
	while($fetch_sel_pts = @mysql_fetch_assoc($rs_sel_pts))
	{
		//print_r($fetch_sel_pts);
		$gcdLatLng=drawCircle($fetch_sel_pts[gpi_latVal],$fetch_sel_pts[gpi_longVal],$fetch_sel_pts[gpi_miles]);
							//print_r($gcdLatLng);
		$geocheck=getGeofenceStatus($gcdLatLng,$lt,$ln);
		if($geocheck == 1)
		{
			return $fetch_sel_pts[gpi_stopName];
		}
	}
}
function funKMPerDayofDevice($path1)
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

?>
