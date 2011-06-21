<?php
@set_time_limit(0);
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


function geofenceChk($date_offline,$deviceIMEI)
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
function geoAlrtPerDay($lData,$sTime1,$eTime1)
{ 
	$timeArr = array();
	//$ptArr = array();
	$cnt = 0;
	$totalDistance = 0;
	$data1=explode("#",$lData);
	$timeArr= array();
	$timediff=array();
	$srcArr= array();
	$destArr=array();
	$strTime='';
	$endTime='';
	$endPoint = '';
	$tmpId = -1;
	$lat_lngArr = array();
	
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
			//echo "<br>";
			//echo $curTime." >= ".$sTime1." && ".$curTime."<=".$eTime1;
			//echo "<br>";
			
			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			
			if($pos1>0 && $pos2>0)
			{
				
				if(!in_array($geoTime,$timeArr))
				{
					$getDevice = "SELECT * FROM tb_geofence_info WHERE tgi_clientId = ".$_SESSION[clientID]." AND tgi_isActive = 1 ORDER BY tgi_id ASC";					
					//$getDevice = "SELECT * FROM `tb_geofence_info` WHERE `tgi_name` = 'Colva Beach'";					
					$resDevice = mysql_query($getDevice);					
					if(@mysql_affected_rows() > 0)
					{
						while($fetDevice = @mysql_fetch_assoc($resDevice))
						{
							
							$lat_lngArr = explode("#",$fetDevice[tgi_coordinates]);		
							for($i=0;$i<count($lat_lngArr)-1;$i++)
							{
								$lat_lng1 = explode(",",$lat_lngArr[$i]);
								$lat_lng [$i] = $lat_lng1[0].",".$lat_lng1[1];
								
							}
							//print_r($lat_lng);
							//exit;
							$res = getGeofenceStatus($lat_lng,$pos1,$pos2);
							if($res)
							{
								
								if($tmpId == -1)
								{							
									$tmpId=$fetDevice[tgi_id];				
									$finaTime[] = $tmpId."#".$geodate." ".$geoTime."#Entered#".$fetDevice[tgi_name];																
								}
								elseif($tmpId == $fetDevice[tgi_id])
								{
									$endTime = $geodate." ".$geoTime;
									//echo " ";
									$endId = $fetDevice[tgi_id];
									$endPoint = $fetDevice[tgi_name];
									//echo "<br>";
									//$tmpId = $fetDevice[tgi_id];
								}
								elseif($tmpId != $fetDevice[tgi_id] && $tmpid !=-1 && $endPoint!='')
								{	
									 //$finaTime .=$endTime."#LEFT#".$endPoint."@";
									 //echo $tmpId."#".$geodate." ".$geoTime."#".$endTime."#Left#".$endPoint;
									 //echo "<br>";
									 $finaTime[] = $tmpId."#".$endTime."#Left#".$endPoint;
									 $tmpId = $fetDevice[tgi_id];
									 $endPoint = "";
									 $finaTime[] = $fetDevice[tgi_id]."#".$geodate." ".$geoTime."#Entered#".$fetDevice[tgi_name];
									//echo "<br>";
								}
								//echo $finaTime;
								//echo "<br>";
								
							}
							$result_pt="";
							$lat_lngArr = "";
							$lat_lng = array();
							/*if($res == 1 && $data3[3]>0 && $strTime=='')
							{
								 $finaTime .=$geoTime."#Entered#".$fetDevice[tgi_name]."@";
								 $strTime = $geoTime;
								 
							}
							else if($res == 1 && $data3[3]==0 && $endTime=='' && $strTime!='')
							{													
								$finaTime .=$geoTime."#LEFT#".$fetDevice[tgi_name]."@";
								
							}*/
							/*if($res)
							{
								echo $finaTime = $geodate."#".$geoTime."#".$fetDevice[tgi_name]."@";
								echo "<br>";
								$strTime='';
								$endTime='';
								$lat_lngArr = '';
							}*/
						}
						
					}
				}
			}
				$cnt++;
			}
		}
		array_push($timeArr,$geoTime);
	}
	//print_r($finaTime);
	//exit;
	if(count($finaTime)>0)
	{
		$finaTime = implode("@",$finaTime);
		return $finaTime;
	}
	else return false;
}

if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
{
	$devices_query =  "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_clientId =".$_SESSION[clientID]." AND di_status = 1 ORDER BY di_deviceName,di_deviceId ASC";
}
else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
{
	$devices_query = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_userinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_assignedUserId = ui_id AND di_clientId=ui_clientId AND ui_clientId = ".$_SESSION[clientID]." AND ui_groupId =".$recordUserInfo[ui_groupId]." order by di_deviceName,di_deviceId ASC";
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
  
	var frdt_array = document.getElementById('from_date').value.split("-");	
	var frdate = new Date(frdt_array[2],(frdt_array[1]-1), frdt_array[0]);
	
	var todt_array = document.getElementById('to_date').value.split("-");
	var todate = new Date(todt_array[2],(todt_array[1]-1), todt_array[0]);
	

	var fr_to_diff = days_between(todate, frdate);
	if(fr_to_diff < 0)
	{ 
		alert("To Date should not be greater then From Date.");
		document.getElementById('to_date').select();
		return 0;
	}
	if(fr_to_diff > 6)
	{ 
		alert("Date diff. should be less then or equal to 7");
		document.getElementById('to_date').select();
		return 0;
	}
	
	return 1;
	
}
function sendCSVData()
{
  document.frmTripData.submit();
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
jQuery(function()
{
    // Default.
    
    // Use default settings
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
<tr>
  <th colspan="4">Geofence Report</th></tr>
  <tr>
    <td width="15%" align="right">Select Device&nbsp;</td>
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
        <option value="<?php echo $devices_fetch[di_deviceId]."#".$devices_fetch[di_imeiId]."#".$devName; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_deviceId]."#".$devices_fetch[di_imeiId]."#".$devName) echo "selected"; ?>><?php echo $devName; ?></option>
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
    <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=geofence';" tabindex="7" /> 
    <?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
    <input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="sub_btn" style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
  </tr>
</table>
</form>
<div id="popup_div" style=" display:block; border:0px;" >

</div>	
	
<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{ 
//print_r($_POST);
//exit;
?>
<div style="width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">
      <tr>
        <th width="5%" >#</th>
        <th width="40%">Zone Name</th>
        <th width="20%">Crossing </th>
        <th width="35%">Date & Time</th>
      </tr>
<?php
	
$sdate = $_POST[from_date];
$edate = $_POST[to_date];
$srcData = explode("#",$_POST[map_device_id]);

$strtTime1 = date("H:i",strtotime($_POST[time3]));
$endTime1 = date("H:i",strtotime($_POST[time4]));

$strtTime = explode(":",date("H:i",strtotime($_POST[time3])));
$strtTime = (($strtTime[0] * 60) + $strtTime[1]);

$endTime = explode(":",date("H:i",strtotime($_POST[time4])));
$endTime = (($endTime[0] * 60) + $endTime[1]);

	
$geodata = "Device Name,Date From,Date To";
$geodata .= "@";

$qstrDate = date("d-m-Y H:i",strtotime($sdate." ".$strtTime1));
$qendDate = date("d-m-Y H:i",strtotime($edate." ".$endTime1));

$geodata .= $srcData[2].",".$qstrDate.",".$qendDate;
$geodata .= "@@";

$geodata .= "#,Zone Name,Crossing,Date & Time";
$geodata .= "@@";
	

	if($_POST[from_date] == $_POST[to_date])
	{
		$data =  geofenceChk($_POST[from_date],$srcData[1]);
		$finData[] = geoAlrtPerDay($data,$strtTime,$endTime);
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
				$data =  geofenceChk($z[$y],$srcData[1]);			
				$finData[] = geoAlrtPerDay($data,$strtTime1,$endTime1);
			}
			elseif($y == count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = $endTime;
				$data =  geofenceChk($z[$y],$srcData[1]);			
				$finData[] = geoAlrtPerDay($data,$strtTime1,$endTime1);
			}
			elseif($y < count($z)-1) 
			{
				$strtTime1 = 0;
				$endTime1 = 1439;
				$data =  geofenceChk($z[$y],$srcData[1]);			
				$finData[] = geoAlrtPerDay($data,$strtTime1,$endTime1);
			}
			
			
		}
	}
	//print_r($finData);
	//exit;
 	//echo 'ss'.count($finData);
	//$z = GetDays($sdate, $edate);
	$i = 0;
	if(count($finData)>0 && $finData[0]!='')
	{
			
		for($x=0; $x<count($finData); $x++)
		{
			$finData1 = explode("@",$finData[$x]);
			//print_r($finData1);
		for($y=0; $y<count($finData1); $y++)
		{
			$splitData = explode("#",$finData1[$y]);
			
			//print_r($splitData);
			if($splitData[2] == "Left")
				$align = 'align= right';
			else
				$align = 'align= left';
				
			$geodata .= ($i+1).",".ucfirst($splitData[3]).",".$splitData[2].",".$splitData[1];
			$geodata .= "@";
		
	?>	  
			<tr <?php if($i % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
				<td valign="top"><?php echo $i+1; ?></td>
				<td valign="top"><?php echo ucfirst($splitData[3]); ?></td>
				<td <?php echo $align;?> valign="top"><?php echo $splitData[2]; ?></td>
				<td valign="top"><?php echo $splitData[1]; ?></td>
			</tr>
	<?php      
		   $i++; 
		   //exit;
		}
	   }
	} ///end of if(file exists)
	else
	{
		?> 
            <tr <?php if($i % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
            <td valign="top" colspan="4">Data Not Found<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script></td>
            </tr>
        <?php 
	}
?>	  
</table>	
	
<form name="frmTripData" id="frmTripData" method="post" action="../report/export.php">
<input type="hidden" name="txtGeofenceData" id="txtGeofenceData" value="<?php echo $geodata;?>" />
<input type="hidden" name="txtDeviceName" id="txtDeviceName" value="<?php echo $srcData[2]; ?>" />
<input type="hidden" name="txtStartDateTime" id="txtStartDateTime" value="<?php echo $_POST[from_date]." ".$_POST[time3]; ?>" />
<input type="hidden" name="txtEndDateTime" id="txtEndDateTime" value="<?php echo $_POST[to_date].' '.$_POST[time4]; ?>" />
</form>
	
	
</div>	
<script type="text/javascript">

hidePreLoader();
</script>
<?php
  
}///end of post

?>
