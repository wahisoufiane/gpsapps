<?php
@session_start();
@ob_start();
require_once("../db/MySQLDB.php");
require_once("../superAdmin/superAdmin.php");
require_once("../superAdmin/superAdminSF.php");
require_once("../Utilities/GPSFunction.php");

	//$query = 'CALL prcGetRoutes_offline("'.$_GET[sessionid].'","'.$_GET[date_offline].'")';
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

	
	////////START READING DATA FROM FOLDER
				
		if($_SESSION[superAdminSID]!='')
		{ 
		$matches = array();
		$c=0;
			$client_array=array();
			$res_clients=SuperAdminSF::getClients();
			while($client_array=mysql_fetch_assoc($res_clients))
			{
				//array_push($client_array,$clientIds);
			$path1=$dataPath."client_".$client_array[clin_id]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/";
			$chkFolder=chk_folder($path1);
			if($chkFolder)
			{
			$getDriver="SELECT * from driver_info,gps_task_info,vehicle_info where gti_vehicle_id = vi_id AND gti_driver_id = di_id AND gti_clientId =".$client_array[clin_id]." AND '".$_GET[date_offline]."' BETWEEN DATE_FORMAT( gti_start_date, '%Y-%m-%d' ) AND DATE_FORMAT( gti_end_date, '%Y-%m-%d' ) AND vi_reg_no = '".$_GET[phoneNumber]."' LIMIT 0 , 30";
			preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);  
			}
			
			}
		}
		else
		{
			$path1=$dataPath."client_".$_GET[sessionid]."/".date('d-m-Y',strtotime($_GET["date_offline"]))."/";
			//exit;
			$matches = array();
			$c=0;
			preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);
			
			
			$getDriver="SELECT * from driver_info,gps_task_info,vehicle_info where gti_vehicle_id = vi_id AND gti_driver_id = di_id AND gti_clientId =".$_SESSION[clientID]." AND '".$_GET[date_offline]."' BETWEEN DATE_FORMAT( gti_start_date, '%Y-%m-%d' ) AND DATE_FORMAT( gti_end_date, '%Y-%m-%d' ) AND vi_reg_no = '".$_GET[phoneNumber]."' LIMIT 0 , 30";

		}
	//
	
	$resDriver=mysql_query($getDriver);
	$fetchDriver=@mysql_fetch_assoc($resDriver);

	
	//print_r($matches[2]);
	for($i=1;$i<count($matches[2]);$i++)
	{
		//echo $matches[2][$i] . '<br>';
		$vehicle_reg_no[$c] = explode('.',$matches[2][$i]);
		$c++;
	}
	$xml = '<routes dImg="'.$fetchDriver[di_profile_image].'" dFName="'.$fetchDriver[di_firstName].'" dLName="'.$fetchDriver[di_lastName].'" dPhone="'.$fetchDriver[di_phone1].'">';
	
	for($i=0;$i<$c;$i++)
	{
	//echo $vehicle_reg_no[$i][0];
	$xml.='<route sessionID="'.$_GET[sessionid].'" phoneNumber="'.$vehicle_reg_no[$i][0].'" select="'.$_GET["phoneNumber"].'" date="'.$_GET["date_offline"].'" />';
	}
	$xml.='<route sessionID="all" phoneNumber="Show All" route="" date="'.$_GET["date_offline"].'" select="all" />';
	$xml .= '</routes>';

	header('Content-Type: text/xml');
	echo $xml;

	//$mysqli->close();
?>
