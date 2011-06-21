<?php
function chk_folder($filename)
{ //echo $filename;
	$fp_load = @fopen($filename, "rb");
	if ( $fp_load )
	{ 
		return true;
	}
	else
	{ 
		return false;
	}
}


function gpspathFunAll($date_offline,$deviceIMEI)
{
	$path1=$GLOBALS[dataPath]."src/data/".date('d-m-Y',strtotime($date_offline))."/".$deviceIMEI.".txt";
	
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
			fclose($file1);
		}
		return $data1;
	}
		
}
function liveKmsPerDay($lData,$sTime1,$eTime1)
{ 
	$timeArr = array();
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
			
			if($curTime >= $sTime1 && $curTime<=$eTime1)
			{
			
			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			
			if($pos1>0 && $pos2>0)
			{
				
				if(!in_array($geoTime,$timeArr))
				{
					if($cnt==0)
					{
						$pits1 =  $pos1;
						$pits2 =  $pos2;
					}
					else
					{
						$pits3 =  $pos1;
						$pits4 =  $pos2;
						//echo $sTime1." >= ".$curTime." <= ".$eTime1." ".$totalDistance."<br>";
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
	//$res=simpleGeocode($pos1,$pos2);
	//$res=str_replace('"',"",$res);
	$finalData = round($totalDistance);
	return $finalData;
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
  { alert("Select Vehicle");  document.getElementById('map_device_id').focus();  return 0;  }
  
  if(document.getElementById('from_date').value=='')
  { alert("Select From Date"); document.getElementById('from_date').focus();  return 0;  }
  
  if(document.getElementById('to_date').value=='')
  { alert("Select To Date");  document.getElementById('to_date').focus(); return 0; }
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	var todt_array = document.getElementById('to_date').value.split("-");
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
	//alert(curdate)
	var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

	var fr_to_diff = days_between(frdate, todate);
	var days_diff = days_between(todate, frdate);

	
	if(fr_to_diff > 0)
	{ alert("From Date should be prior to To Date."); document.getElementById('to_date').select(); return 0;}
	
	//alert(days_diff); 
	
	if(days_diff > 7)
	{alert("From and To date should not be greater than 7 days."); document.getElementById('to_date').select(); return 0;}
	
	return 1;
	
}
function sendCSVData()
{
  document.frmKiloData.submit();
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
jQuery(function() {

    //$("#time3, #time4").timePicker();
	 $("#time3, #time4").timePicker({
	  startTime: "12:01 AM", // Using string. Can take string or Date object.
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

$(function() {
	$( "#from_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
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
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();"> 
<table class="gridform">
<tr><th colspan="4">Distance Report</th></tr>
  <tr>
    <td width="15%" align="right"><span class="form_text">Select Device</span></td>
    <td width="35%" align="left" colspan="3">
        <select name="map_device_id" id="map_device_id" class="hours_select" tabindex="1" style="width:50%;" >
        <option value="0">Select Device</option>
        <?php 
        while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
        {
			if($devices_fetch[di_deviceName])
				$devName = $devices_fetch[di_deviceName];
			else
				$devName = $devices_fetch[di_deviceId];
        ?>
        <option value="<?php echo $devices_fetch[di_imeiId]; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_imeiId]) echo "selected"; ?>><?php echo $devName; ?></option>
        <?php } ?>	
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    </tr>              
    <tr>
    <td width="15%" align="right"><span class="form_text">From Date & Time</span></td>
    <td width="35%" align="left">
    <input type="text" name="from_date" id="from_date" readonly="true" tabindex="2" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/>&nbsp;
    <span><input type="text" name="time3" readonly="true" id="time3" size="7" tabindex="3" value="<?php if($_POST[time3]) echo $_POST[time3]; else echo "12:01 AM";?>" /></span>
    </td>
    <td width="15%" align="right"><span class="form_text">To Date & Time</span></td>
    <td width="35%">
    <input type="text" name="to_date" id="to_date" size="12" style="width:140px;" tabindex="4" readonly="true" value="<?php echo $_POST[to_date]; ?>" />&nbsp;
    <span><input type="text" name="time4" readonly="true" id="time4" size="7" tabindex="5" value="<?php if($_POST[time4]) echo $_POST[time4]; else echo "11:59 PM";?>" /></span>
    </td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="click_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="click_btn" onclick="location.href='index.php?ch=distance';" tabindex="7" /> 
    <?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
    <input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="click_btn" style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
  </tr>
</table>
</form>
<div id="popup_div" style=" display:block; border:0px;" >

</div>	
	
<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{ 
//print_r($_POST);
?>
<div style="width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">
      <tr>
        <th width="80%">Date </th>
        <th width="20%" >Kilometer (km)</th>
       </tr>
<?php
	$sdate = $_POST[from_date];
	$edate = $_POST[to_date];
	
	$strtTime = explode(":",date("H:i",strtotime($_POST[time3])));
	$strtTime = (($strtTime[0] * 60) + $strtTime[1]);
	
	$endTime = explode(":",date("H:i",strtotime($_POST[time4])));
	$endTime = (($endTime[0] * 60) + $endTime[1]);
	
	//exit;
	if($_POST[from_date] == $_POST[to_date])
	{
		$data =  gpspathFunAll($_POST[from_date],$_POST[map_device_id]);
		$finData[] = $_POST[from_date]."#".liveKmsPerDay($data,$strtTime,$endTime);
	}
	else
	{
		$z = GetDays($sdate, $edate);
		for($y=0; $y<count($z); $y++)
		{ 
			if($y == 0) 
			{
				$strtTime1 = $strtTime;
				$endTime1 = 1439;
				$data =  gpspathFunAll($z[$y],$_POST[map_device_id]);			
				$finData[] = $z[$y]."#".liveKmsPerDay($data,$strtTime1,$endTime1);
			}
			elseif($y == count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = $endTime;
				$data =  gpspathFunAll($z[$y],$_POST[map_device_id]);			
				$finData[] = $z[$y]."#".liveKmsPerDay($data,$strtTime1,$endTime1);
			}
			elseif($y < count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = 1439;
				$data =  gpspathFunAll($z[$y],$_POST[map_device_id]);			
				$finData[] = $z[$y]."#".liveKmsPerDay($data,$strtTime1,$endTime1);
			}
			
			
		}
	}
	//print_r($finData);
	
	$kiloData = "Date,Kilometer";
	$kiloData .= "@";
 
	$z = GetDays($sdate, $edate);
	$cont = 0;
	if(count($finData)>0)
	{
	for($x=0; $x<count($finData); $x++)
	{
	 	$splitData = explode("#",$finData[$x]);
		$kiloData .= date("d-m-Y",strtotime($splitData[0])).','.$splitData[1];
		$kiloData .= '@';
		$totKm += $splitData[1];
    
?>	  
      <tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td><?php echo date("d-m-Y",strtotime($splitData[0])); ?></td>
		<td><?php echo $splitData[1]; ?></td>
      </tr>
<?php      
	   $cont++; 
   }
} ///end of if(file exists)
		$kiloData .= '@';
		$kiloData .= "Total KM Travelled,".$totKm." in ".$cont;
		$kiloData .= '@';
?>	  
	 <tr>
		<td align="right">Total Km Travelled in <?php echo $cont; ?> days</td>
		<td><?php echo $totKm; ?></td>
      </tr>
</table>	
	
<form name="frmKiloData" id="frmKiloData" method="post" action="../report/export.php">
<input type="hidden" name="txtKiloData" id="txtKiloData" value="<?php echo $kiloData;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $_POST[map_device_id]; ?>" />
<input type="hidden" name="txtDate1" id="txtDate1" value="<?php echo $_POST[from_date]; ?>" />
<input type="hidden" name="txtDate2" id="txtDate2" value="<?php echo $_POST[to_date]; ?>" />
</form>	
	
	
</div>	
<script type="text/javascript">

hidePreLoader();
</script>
<?php
  
}///end of post

?>
