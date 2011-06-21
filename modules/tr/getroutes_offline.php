<?php
@session_start();
@ob_start();
error_reporting (E_ALL ^ E_NOTICE);

require_once("../../includes/GPSFunction.php");
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
				
		$date_offline = $_GET[date_offline];
		$path1=$GLOBALS[dataPath]."src/data/data_".$_SESSION[clientID]."/".date('d-m-Y',strtotime($date_offline))."/";
		$matches = array();
		$c=0;
		preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);
		for($i=1;$i<count($matches[2]);$i++)
		{
			//echo $matches[2][$i] . '<br>';
			$vehicle_reg_no[$c] = explode('.',$matches[2][$i]);
			$c++;
		}


	$xml = '<routes>';
	
	for($i=0;$i<$c;$i++)
	{
	//echo $vehicle_reg_no[$i][0];
	$xml.='<route sessionID="" phoneNumber="'.$vehicle_reg_no[$i][0].'" select="'.$_GET["phoneNumber"].'" date="'.$_GET["date_offline"].'" />';
	}
	$xml.='<route sessionID="all" phoneNumber="Show All" route="Show All" date="date_offline" select="all" />';
	$xml .= '</routes>';

	header('Content-Type: text/xml');
	echo $xml;

	//$mysqli->close();
?>
