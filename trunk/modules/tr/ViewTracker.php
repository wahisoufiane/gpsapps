<?php
@ob_start();
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");
//	USER OR SUPERADMIN SESSION CHECK

if(isset($_SESSION[superID]))
	require("../sa/checkSession.php");
elseif(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
	require("../user/checkSession.php");
	


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
}

if(isset($_POST[date_offline]) && $_POST[date_offline])
	$date_offline = $_POST[date_offline];
else
	$date_offline = date('d-m-Y');
/*	
$path1=$GLOBALS[dataPath]."src/data/data_".$_SESSION[clientID]."/".date('d-m-Y',strtotime($date_offline))."/";		//".date('d-m-Y')."
$chkFolder=chk_folder($path1);
$c = 0;
if($chkFolder)
{
	preg_match_all("/(a href\=\")([^\?\"]*)(\")/i", get_text($path1), $matches);  
	for($i=0;$i<count($matches[2]);$i++)
	{
		$tmpVehi = explode('.',$matches[2][$i]);
		if(isset($_POST[txtVehino]) && $_POST[txtVehino]!='')
		{
			if($tmpVehi[0]==$_POST[txtVehino])
			{
				$vehicle_reg_no[$c]=$tmpVehi;
				$pathSA[$c]=$path1;
				$c++;
			}
		}
		else
		{
			$vehicle_reg_no[$c]=$tmpVehi;
			$pathSA[$c]=$path1;
			$c++;
		}
		
	}
	//echo "<br>";
}

function gpspathFun($source,$vehicle)
{
	$path1=$source.$vehicle.".txt"; 
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
		}else
		{
			$data=0;
			return $data;
		}
	}
} */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/userAjax.js"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRR6gMao_Dcd5SMHBEzNxu-t4q-KNhQkTcrH6GvqMrKepHPXB9izcv_36w" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
var ajax1=new sack();
refreshMapTable("<?php echo $_SESSION[clientID];?>","<?php echo $date_offline;?>");
var interval = setInterval( function() { 

refreshMapTable("<?php echo $_SESSION[clientID];?>","<?php echo $date_offline;?>"); 
}, 20000 );


//var refreshId = setInterval("refreshMapTable("+1+","+<?php echo $date_offline;?>+")", 1000);
function refreshFunc(newInterval)
{
	alert(newInterval);
	if (newInterval > 0) { // moving to another interval (3)
		
		intervalID = setInterval("refreshMapTable("+1+","+<?php echo $date_offline;?>+");", newInterval * 1000);
		currentInterval = newInterval;
	}
	else { // we are turning off (2)
		clearInterval(intervalID);
		newInterval = 0;
		currentInterval = 0;
	}
}
function refreshPage()
{
	//loader();
	document.frmTrackDateMap.submit();
}
//refreshFunc(30);
//refreshMapTable("1","<?php echo $date_offline;?>");

function refreshMapTable(sessionid,date_offline)
{
	ajax1.requestFile = 'ajax_server.php?date_offline='+date_offline+'&sessionid='+sessionid;
	//alert(ajax1.requestFile);
	ajax1.onCompletion = function(){exeRefreshTable()};
	ajax1.runAJAX();
}
function exeRefreshTable()
{
	var result = ajax1.response.split("#");
	
	mapTable = '<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">';
    mapTable +='<tr><th width="10%">Time</th><th width="10%">Device&nbsp;ID</th><th width="50%">Location</th><th width="15%">Status</th><th width="15%">Map</th></tr>';
	
	no_of_rows= result.length-1;
	if(no_of_rows > 0)
	{
		for(i=0;i<no_of_rows;i++)
		{
			data = result[i].split(',');
				if(data[4]==0)
					status = 'Stopped';
				else status= 'Running';
				date = data[5].split("-");

			mapTable +='<tr>';
			mapTable +='<td valign="top">'+data[0]+'</td>';
			mapTable +='<td valign="top">'+data[1]+'</td>';
			mapTable +='<td valign="top"><span id="addr'+i+'">'+getAddress(data[2],data[3],"addr"+i)+'</span></td>';
			mapTable +='<td valign="top" style="padding-left:5px; text-align:center">'+status+'</td>';
			mapTable +='<td style="border-right:0px; text-align:center"><a href="#" class="href" onclick="onlineGTracker('+data[6]+','+date[0]+','+date[1]+','+date[2]+','+data[1]+');">Map</a>';
			mapTable +='</td></tr>';
			
		}//end of for loop
	}//end of if loop
	else
	{
		mapTable +='<tr><td colspan="5" style="border-top:1px solid #c5d4da; border-right:0px; background-color:#e8e9ea;">No Records found</td></tr>';
	}
	mapTable +='</table>';
	document.getElementById('mapTable').innerHTML = mapTable;
}

function getAddress(lat,long,div)
{
	var addr="";
	address=((lat)+","+(long));
	var geocoder2 = new GClientGeocoder();
geocoder2.getLocations(address, function(response) {
  if (!response || response.Status.code != 200) {
	//alert("Status Code:" + response.Status.code);
	document.getElementById(div).innerHTML= "Status Code: not identified";
  } else {
	place = response.Placemark[0];

	addr=place.address;
	addr=addr.split(",");
	//alert(place.address);
	document.getElementById(div).innerHTML=place.address;
}
});
}

function onlineGTracker(sessionid,d1,d2,d3,vehicle_no)
{
	date_offline = d1+'-'+d2+'-'+d3;
	//alert(date_offline);
	//alert(sessionid+','+date_offline+','+vehicle_no);
	//document.getElementById('load_view').innerHTML='<span id="loading_txt">Loading...</span>';
	var url = 'getgpslocations_offline.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&phoneNumber=' + vehicle_no;
	//document.write(url);
	parent.getOneVehicle(url);
	var interval = setInterval( function() { 
	parent.getOneVehicle(url);; 
	}, 20000 );
    
	
}

function showMapForDate(val)
{	
	parent.getAllVehicle(val,'');
	document.frmTrackDateMap.date_offline.value = val;
	document.frmTrackDateMap.submit();
}

</script>
</head>

<body style="background:#fff;">
<table width="100%" border="0" cellpadding="3" cellspacing="2" align="center">
<tr>
    <td align="center"><span>Choose Date</span>
    	<select name="selMapDate" id="selMapDate" onchange="showMapForDate(this.value)">
        <?php
		for($k=0;$k<7;$k++)
		{
			$offDate = date("d-m-Y",strtotime("-".$k." day"));
			if($_POST[date_offline] == $offDate)
				$select= 'selected="selected"';
			else
				$select = '';
			echo '<option '.$select.' value='.date("d-m-Y",strtotime("-".$k." day")).'>'.date("d-m-Y",strtotime("-".$k." day")).'</option>';
		}
        ?>
        </select>
    </td>
</tr>
<tr>
    <td align="center">
        <span id="mapTable">Loading...</span>
    </td>
</tr>
</table>
        
<form name="frmTrackDateMap" id="frmTrackDateMap" method="post">
	<input type="hidden" name="date_offline" id="date_offline" value="<?php echo $date_offline;?>" />
</form>
<script language="javascript">
function loader()
{
	//alert('ssss');
	document.getElementById('mapTable').innerHTML = 'Loading...';
}
</script>
</body>
</html>
