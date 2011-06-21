<style type="text/css" title="currentStyle">
	@import "../media/css/demo_table.css";
	@import "media/css/TableTools.css";
</style>

<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="media/ZeroClipboard/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready( function () {
		/* You might need to set the sSwfPath! Something like:
		 *   TableToolsInit.sSwfPath = "/media/swf/ZeroClipboard.swf";
		 */
		$('#example').dataTable( {
			"sDom": 'T<"clear">lfrtip'
		} );
	} );
</script>
<?php
@set_time_limit(0);

$fr_hr_selected = 'selected';
$fr_min_selected = 'selected';
$to_hr_selected = 'selected';
$to_min_selected = 'selected';

if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{
  $fr_hr_selected = '';
  $fr_min_selected = '';
  $to_hr_selected = '';
  $to_min_selected = '';
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


function getTripReport($sdate,$userId,$devId,$usrSTime,$usrETime,$flag)
{

$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$timediff=array();
$strTime='';
$endTime='';
$k1 = 0;
$totDist = 0;
$file = $GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($sdate))."/".$devId.".txt";   
//echo "<br><br>";
if(chk_folder($file))
{
	$file1 = @fopen($file, "r");
	if($file1)
	{
		while(!feof($file1))
		{
			$data= fgets($file1);
		}
		$data = getSortedData($data);
	}

	if(count($data)>0)
	{
	 $data1=explode("#",$data);

	 for($j1=0;$j1<count($data1);$j1++)
	 {
		$data2=explode("@",$data1[$j1]);
		if(count($data2)>1)
		{
			$data3=explode(",",$data2[1]);
			//echo date("d-m-Y", strtotime($sdate))."==".date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$device=$data3[0];
			$geodate = $data3[8]." ".$data3[9];
			$geoTime = $data3[9];
			$curTime = explode(":",$data3[9]);
			$curTime = (($curTime[0] * 60) + $curTime[1]);
			
			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			
			if($curTime >= $usrSTime && $curTime<=$usrETime)
			{	
			if($pos1>0 && $pos2>0)
			{
			if(!in_array($geoTime,$timeArr))
			{
			//echo $geoTime."	sss ".$data3[3];
			//echo $usrSTime." >= ".$curTime." <= ".$usrETime." ".$totalDistance."<br>";
			//echo "<BR>";
			if($data3[3]>0 && $strTime=='')
			{
				$strTime=$geoTime;
				
				$res2=simpleGeocode($pos1,$pos2);
				$res2=str_replace('"',"",$res2);	
				$strPoint = $res2;
				
				$avgSpeed += $data3[3];
				
				$pits1 =  $pos1;
				$pits2 =  $pos2;
								
				$k1++;
			}
			else if($data3[3]>0 && $endTime=='' && $strTime!='')
			{
				
				$avgSpeed += intval($data3[3]);
				
				$pits3 =  $pos1;
				$pits4 =  $pos2;
				
				$dist = getDistance($pits1, $pits2, $pits3, $pits4);
				$totDist += $dist;
				$pits1 =  $pits3;
				$pits2 =  $pits4;
				$k1++;

			}
			else if($data3[3]==0 && $endTime=='' && $strTime!='')
			{
				$endTime=$geoTime;
				$res2=simpleGeocode($pos1,$pos2);
				$res2=str_replace('"',"",$res2);	
				$endPoint = $res2;
				
				$avgSpeed += $data3[3];
				
				$avgSpeed  = round(($avgSpeed/$k1),2);
				
				$pits3 =  $pos1;
				$pits4 =  $pos2;
				
				$dist = getDistance($pits1, $pits2, $pits3, $pits4);
				$totDist += $dist;
				$pits1 =  $pits3;
				$pits2 =  $pits4;
				
				$finaTime=$strPoint."#".$strTime."#".$endPoint."#".$endTime."#".gmdate("H:i:s",(strtotime($endTime) - strtotime($strTime)))."#".round($totDist)."#".round($avgSpeed)."#".$device."#".date('d-m-Y',strtotime($sdate));
				//echo "<br>";
				//echo "---------------------------------------------------------------------------------------------------------------------------------------------";
				//echo "<BR>";
				
				if($flag == 0)
				{
				$strTime='';
				$endTime='';
				$strPoint = '';
				$endPoint = '';
				$pits1 =  '';
				$pits2 =  '';
				$pits3 =  '';
				$pits4 =  '';
				$totDist = 0;
				$avgSpeed = 0;
				}
				array_push($timediff, $finaTime);
				
			}
				$cnt++; 

				array_push($timeArr,$geoTime);
			}
		}
		}
		}
	}
	fclose($file1);
	}
}

return($timediff);
}

if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
{
	$devices_query =  "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_clientId =".$_SESSION[clientID]." AND di_status = 1 ORDER BY di_deviceName,di_deviceId ASC";
}
else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
{
	$devices_query = "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
}
//echo $devices_query;
//$devices_query =  "SELECT * FROM tb_deviceinfo WHERE di_clientId =".$_SESSION[clientID]." AND di_status = 1 ORDER BY di_deviceName,di_deviceId ASC";
$devices_resp = mysql_query($devices_query);	
?>
<script type="text/javascript" language="javascript">
function showFormDiv()
{
	if(document.getElementById('formDiv').style.display=='none')
	{
		document.getElementById('formDiv').style.display = 'block';
		document.getElementById('shLink').innerHTML = "Hide Form";
	}
	else
	{
		document.getElementById('formDiv').style.display = 'none';
		if(document.getElementById('shLink'))
		document.getElementById('shLink').innerHTML = "Show Form";
	}
}
function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{
		document.getElementById('popup_div').innerHTML = '<div id="loading_txt" >Loading...</div>';
		document.frm_map_filter.submit();
	}
}

function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';
}

function days_between(date1, date2) {

    var ONE_DAY = 1000 * 60 * 60 * 24

    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

	var difference_ms = date1_ms - date2_ms
	    
    return Math.round(difference_ms/ONE_DAY)

}



function validateMapReport()
{
  if(document.getElementById('map_device_id').value== 0 )
  {
   alert("Select Device"); 
   document.getElementById('map_device_id').focus();
   return 0;  
  }
  
  if(document.getElementById('from_date').value=='')
  { alert("Select From Date"); document.getElementById('from_date').focus();  return 0;  }
  
  if(document.getElementById('to_date').value=='')
  { alert("Select To Date");  document.getElementById('to_date').focus(); return 0; }
  
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	//var todt_array = document.getElementById('to_date').value.split("-");
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
	//var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

	//var fr_to_diff = days_between(frdate, todate);
	//var days_diff = days_between(todate, curdate);
	var days_diff = days_between(frdate, curdate);

	if(days_diff > 0)
	{ 
	  alert("Date should not be future.");
	  document.getElementById('to_date').select();
	  return 0;
	}
	
	return 1;
	
}
jQuery(function() {

//$("#time3, #time4").timePicker();
 $("#time3, #time4").timePicker({
  startTime: "12:0 AM", // Using string. Can take string or Date object.
  endTime: "11:59 PM", // Using Date object here.
  show24Hours: false,
  separator: ':',
  step: 1});    
	
// Store time used by duration.
var oldTime = $.timePicker("#time3").getTime();

// Keep the duration between the two inputs.
$("#time3").change(function() {
  if ($("#time4").val()) { // Only update when second input has a value.
	// Calculate duration.
	var duration = ($.timePicker("#time4").getTime() - oldTime);
	var time = $.timePicker("#time3").getTime();
	// Calculate and update the time in the second input.
	$.timePicker("#time4").setTime(new Date(new Date(time.getTime() + duration)));
	oldTime = time;
  }
});
// Validate.
$("#time4").change(function() {
  if($.timePicker("#time3").getTime() > $.timePicker(this).getTime()) {
	$(this).addClass("error");
  }
  else {
	$(this).removeClass("error");
  }
});

});
function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}




$(function() {
var myDate = new Date($("#from_date").attr('rel'));

	$( "#from_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		defaultDate: myDate,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
$(function() {
	$( "#to_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
</script>

<div id="formDiv">
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();">      	 
<table class="gridform">
<tr><th colspan="4">Trip Report</th></tr>
  <tr>
    <td width="15%" align="right"><span class="form_text">Select Device&nbsp;</span></td>
    <td width="35%" align="left" colspan="3">
        <select name="map_device_id" id="map_device_id" tabindex="1" style="width:50%">
        <option value="0">Select Device</option>
         <?php 
		while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{ 
			if($devices_fetch[di_deviceName])
				$devName = $devices_fetch[di_deviceName];
			else
				$devName = $devices_fetch[di_deviceId];
        ?>
        <option value="<?php echo $devices_fetch[di_deviceId]."#".$devices_fetch[di_imeiId]; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_deviceId]."#".$devices_fetch[di_imeiId]) echo "selected"; ?>><?php echo $devName; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    </tr>              
    <tr>
    <td width="15%" align="right"><span class="form_text">From Date & Time</span></td>
    <td width="35%" align="left">
    <input type="text" name="from_date" id="from_date" readonly="true" size="12" tabindex="2" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/>&nbsp;
    <span><input type="text" name="time3" id="time3" readonly="true" size="7" tabindex="3" value="<?php if($_POST[time3]) echo $_POST[time3]; else echo "12:01 AM";?>" /></span>
    </td>
    <td width="15%" align="right"><span class="form_text">To Date & Time</span></td>
    <td width="35%">
    <input type="text" name="to_date" id="to_date" size="12" style="width:140px;" tabindex="4" readonly="true" value="<?php echo $_POST[to_date]; ?>" />&nbsp;
    <span><input type="text" name="time4" id="time4" readonly="true" size="7" tabindex="5" value="<?php if($_POST[time4]) echo $_POST[time4]; else echo "11:59 PM";?>" /></span>
    </td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="click_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="click_btn" onclick="location.href='index.php?ch=daily';" tabindex="7" /> 
    <?php //if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
    <!--<input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="click_btn" style="font-weight:bold;" onclick="sendCSVData();" /> --><?php //} ?></td>
  </tr>
</table>
</form>
</div>	
<div id="popup_div" style=" display:block; border:0px;" >

</div>
<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{
	/*echo '<pre>';
print_r($_POST);
echo '</pre>';
exit;*/

?>
<script type="text/javascript">
showFormDiv();
hidePreLoader();
</script>
<div style="overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<div class="listofusers" align="right" style="padding-right:10%"><a href="#" class="error_strings" id="shLink" onClick="showFormDiv();">Show Form</a></div>
<div id="container">			
  <div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="gridform_final" id="example">      
  <thead>
  <tr>
    <th width="2%" >#</th>
    <th width="10%">Date</th>
    <th width="35%">Start  Point & Time</th>
    <th width="35%">End  Point & Time</th>
    <th width="15%">Duration</th>
    <th width="5%" >Avg. Speed(kmph)</th>
    <th width="5%" >Dist. (km)</th>        
  </tr>
 </thead>
 <tbody>
<?php
$sdate = $_POST[from_date];
$edate = $_POST[to_date];
$srcData = explode("#",$_POST[map_device_id]);

$strtTime = explode(":",date("H:i",strtotime($_POST[time3])));
$strtTime = (($strtTime[0] * 60) + $strtTime[1]);

$endTime = explode(":",date("H:i",strtotime($_POST[time4])));
$endTime = (($endTime[0] * 60) + $endTime[1]);

$timediff1=array();
$timediff2=array();
$timediff3=array();
$timediff4=array();
$totalDistance=0;
$tripdata = "Date,Start Point,Start Time,End Point,End Time,Duration,Avg. Speed,Dist. Covered";
$tripdata .= "@";
$ct=0;
	if($_POST[from_date] == $_POST[to_date])
	{
		$timediff4=getTripReport($sdate,$srcData[0],$srcData[1],$strtTime,$endTime,0);
	}
	else
	{
		$z = GetDays($sdate, $edate);
		for($y=0; $y<count($z); $y++)
		{ 
			if($y == 0) 
			{
				$timediff1=getTripReport($z[$y],$srcData[0],$srcData[1],$strtTime,1439,1);
				
			}
			elseif($y == count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = $endTime;
				$timediff3=getTripReport($z[$y],$srcData[0],$srcData[1],0,$endTime,0);
			}
			elseif($y < count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = 1439;
				$timediff2=getTripReport($z[$y],$srcData[0],$srcData[1],0,1439,1);
			}
		}
		$timediff4 = array_merge($timediff1,$timediff2,$timediff3);
	}
	if(count($timediff4)>0)
	{
	for($r=0;$r<count($timediff4);$r++)
	{
		$tTdiff=explode("#",$timediff4[$r]);
		$tripdata .= $tTdiff[8].",".$tTdiff[0].",".date("h:i:s A",strtotime($tTdiff[1])).",".$tTdiff[2].",".date("h:i:s A",strtotime($tTdiff[3])).",".$tTdiff[4].",".$tTdiff[6].",".$tTdiff[5];
		$tripdata .= "@";		
?> 
	<tr <?php if($r % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
	<td valign="top"><?php echo $r+1; ?></td>
	<td valign="top"><?php echo $tTdiff[8]; ?></td>
	<td valign="top"><?php echo $tTdiff[0]." (".date("h:i:s A",strtotime($tTdiff[1])).")"; ?></td>
	<td valign="top"><?php echo $tTdiff[2]." (".date("h:i:s A",strtotime($tTdiff[3])).")"; ?></td>
	<td valign="top"><?php echo date("H:i:s",strtotime($tTdiff[4])); ?></td>
	<td valign="top"><?php echo $tTdiff[6]; ?></td>
    <td valign="top"><?php echo $tTdiff[5]; ?></td>
	</tr>
<?php   
}	
	}
	else
	{
		
		echo '<tr><td colspan="9" align="center">No Records Found</td></tr>'; 
		echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 

	}
 ?>	  
 </tbody>
 </table>
 </div>
 </div>
<form name="frmTripData" id="frmTripData" method="post" action="../report/export.php">
<input type="hidden" name="txtTripData" id="txtTripData" value="<?php echo $tripdata;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $srcData[1]; ?>" />
<input type="hidden" name="txtDate" id="txtDate" value="<?php echo $_POST[from_date]; ?>" />
<input type="hidden" name="txtFromTime" id="txtFromTime" value="<?php echo $_POST[from_hrs].':'.$_POST[from_mins].':00'; ?>" />
<input type="hidden" name="txtToTime" id="txtToTime" value="<?php echo $_POST[to_hrs].':'.$_POST[to_mins].':00'; ?>" />
</form>

</div>	
<script type="text/javascript">

hidePreLoader();
</script>	
	
<?php
  
}///end of post

?> 

