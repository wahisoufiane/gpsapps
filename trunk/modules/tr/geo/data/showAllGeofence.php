<?php
@session_start();
@ob_start();
error_reporting (E_ALL ^ E_NOTICE);

require("../../../../includes/config.inc.php"); 
require("../../../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

require_once("../../../../includes/GPSFunction.php");

	$getGeofenceInfo = "SELECT * FROM tb_geofence_info WHERE tgi_isActive = 1 AND tgi_clientId = ".$_SESSION[clientID];
	$resGeofenceInfo = $db->query($getGeofenceInfo);
	
	$finalXml = array();
	
	$mainXml = '<?xml version="1.0" encoding="UTF-8"?>
	<kml xmlns="http://earth.google.com/kml/2.0">
	<Document>
	  <name>KML Example file</name>
	  <description>Simple markers</description>
	  <Style id="examplePolyStyle">
		<PolyStyle>
		  <color>aaff00cc</color>
		  <colorMode>normal</colorMode>
		  <fill>1</fill>                     
		  <outline>1</outline>  
		</PolyStyle>
	  </Style>';
	if($db->affected_rows > 0)
	{
	while($fetchGeofenceInfo = $db->fetch_array($resGeofenceInfo))
	{
		/*echo "<pre>";
		print_r($fetchGeofenceInfo);
		echo "</pre>";*/
		$param1 = explode("&",$fetchGeofenceInfo[tgi_parameter]);
		//print_r($param1);
		$xml.='<Placemark>';
		$xml.='<geoid>'.$fetchGeofenceInfo[tgi_id ].'</geoid>';
		$param2 = explode("=",$param1[count($param1)-1]);
		$xml.='<styleUrl>#'.$fetchGeofenceInfo[tgi_style].'</styleUrl>';
		
		$param2 = explode("=",$param1[count($param1)-3]);
		$xml.='<name>'.$fetchGeofenceInfo[tgi_name].'</name>';
		
		$param2 = explode("=",$param1[count($param1)-2]);
		$xml.='<description>'.$fetchGeofenceInfo[tgi_description].'</description>';
		$xml.='<Polygon>
					  <coordinates>';
		$k=1;
		//print_r($param1);
		while($k < count($param1)-4)
		{
			$param3 = explode("=",$param1[$k]);
			$k++;
			$param4 = explode("=",$param1[$k]);
			$pts .= $param4[1].",".$param3[1].",30 ";
			$k++;
		}
			$xml .=$pts;
			$xml.='</coordinates>
					</Polygon>
				  </Placemark>';
		
		$finalXml[] = $xml;
		$k = 1;
		$pts = '';
		$xml ='';
		////echo $xml;
		//echo "<br><br>";
	
	}
	$mainXml .= implode("\n",$finalXml);
	$mainXml .= '</Document></kml>';
	//print_r($finalXml);
	header('Content-Type: text/xml');
	echo $mainXml;
	
	}
	else
	{
		$mainXml  .= '</Document></kml>';
		header('Content-Type: text/xml');
		echo $mainXml ;
	}

?>
<?php

$db->close();

?>