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



 function TimeCvt($time, $format){
  
  #   $time   - String:  Time in either 24hr format or 12hr AM/PM format
  #   $format - Integer: "0" = 24 to 12 convert    "1" = 12 to 24 convert
  #
  #   RETURNS Time String converted to the proper format
  #
  
      if (ereg ("[0-9]{1,2}:[0-9]{2}:[0-9]{2}<wbr />", $time))   {
        $has_seconds = TRUE;
      }
      else   {
        $has_seconds = FALSE;
      }

      if ($format == 0)   {         //  24 to 12 hr convert
        $time = trim ($time);

        if ($has_seconds == TRUE)   {
          $RetStr = date("g:i:s A", strtotime($time));
        }
        else   {
          $RetStr = date("g:i A", strtotime($time));
        }
      }
      elseif ($format == 1)   {     // 12 to 24 hr convert
        $time = trim ($time);

        if ($has_seconds == TRUE)   {
          $RetStr = date("H:i:s", strtotime($time));
        }
        else   {
          $RetStr = date("H:i", strtotime($time));
        }
      }

      return $RetStr;
    }


function getStopReport($sdate,$devName,$devId,$st_time,$en_time)
{
$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$timediff=array();
$strTime='';
$endTime='';
$totTime = 0;
$k1 = 0;
$totDist = 0;
	$file = $GLOBALS[dataPath]."src/data/".date("d-m-Y", strtotime($sdate))."/".$devId.".txt";   
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
				$pos1=calLat($data3[2]);
				$pos2=calLong($data3[1]);

				$curTime = explode(":",$data3[9]);
				$curTime = (($curTime[0]) * 60);



				if($curTime >= $st_time && $curTime<=$en_time)
				if($pos1>0 && $pos2>0 )
				{
				if(!in_array($geoTime,$timeArr))
				{  
				//echo $geoTime."	sss ".$data3[3];
				//echo "<BR>";
				if($data3[3]==0 && $strTime=='')
				{
					$strTime=$geoTime;
					
					//$res2=simpleGeocode($pos1,$pos2);
					//$res2=str_replace('"',"",$res2);	
					
					$pits1 =  $pos1;
					$pits2 =  $pos2;
									
				}
				else if($data3[3]>0 && $endTime=='' && $strTime!='')
				{
					$endTime=$geoTime;		
					
					if((strtotime($endTime) - strtotime($strTime)) >= 600) 
					{
						//echo strtotime($endTime) - strtotime($strTime)."<br>";
						$res2=simpleGeocode($pos1,$pos2);
						$res2=str_replace('"',"",$res2);	
						$totTime += (strtotime($endTime) - strtotime($strTime));
						$finaTime=$strTime."#".$res2."#".$endTime."#".gmdate("H:i:s",(strtotime($endTime) - strtotime($strTime)))."#".$device."#".$devName."#".$totTime;
						array_push($timediff, $finaTime);
					}					
						
						$strTime='';
						$endTime='';
						$strPoint = '';
						
					//}
				}
					$cnt++; 
					array_push($timeArr,$geoTime);
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


function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{
		//document.getElementById('popup_div').style.display = '';
		//document.getElementById('popup_div').style.visibility = 'visible';
		document.getElementById('popup_div').innerHTML = '<div id="loading_txt" >Loading...</div>';
		document.frm_map_filter.submit();
	}
}

function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';
	//document.getElementById('popup_div').style.visibility = 'hidden';	
	//document.getElementById('popup_div').style.display = 'none';
}



function days_between(date1, date2) {

    // The number of milliseconds in one day
    var ONE_DAY = 1000 * 60 * 60 * 24

    // Convert both dates to milliseconds
    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

    // Calculate the difference in milliseconds
    //var difference_ms = Math.abs(date1_ms - date2_ms)
	var difference_ms = date1_ms - date2_ms
	    
    // Convert back to days and return
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
  {
   alert("Select Date");
   document.getElementById('from_date').focus();
   return 0;  
  }
  
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
function sendCSVData()
{
//alert(c1);
document.frmStopData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
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
</script>

 
<div id="formDiv">
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onSubmit="return validateMapReport();">      	 
<table class="gridform">
<tr><th colspan="4">Stop Report</th></tr>

  <tr>
    <td width="14%" align="right"><span class="form_text">Select Device</span></td>
    <td width="36%" align="left">
    <select name="map_device_id" id="map_device_id" tabindex="1" style="width:100%">
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
    <td width="14%" align="right"><span class="form_text">Date&nbsp;</span></td>
    <td width="36%" align="left">
    <input type="text" name="from_date" id="from_date" tabindex="2" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/></td>
  </tr>              
  <tr>
    <td align="right"><span class="form_text">Start Time &nbsp;</span></td>
    <td align="left"><div><input type="text" name="time3" id="time3" readonly="readonly" tabindex="3" size="7" value="<?php if($_POST[time3]) echo $_POST[time3]; else echo "12:01 AM";?>" /></div></td>
    
    <td  align="right"><span class="form_text">End Time &nbsp;</span></td>
    <td align="left"><div><input type="text" name="time4" id="time4" readonly="readonly" size="7" tabindex="4" value="<?php if($_POST[time4]) echo $_POST[time4]; else echo "11:59 PM";?>" /></div></td>
  </tr>
  
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="click_btn" tabindex="5"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="click_btn" onClick="location.href='index.php?ch=altitute';" tabindex="6" /> 
    
    </td>
  </tr>

 
</table>
</form>
</div>

<div id="popup_div" style=" display:block; border:0px;" >

</div>
<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{
//print_r($_POST);
?>
<div style="overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">      
      <tr>
	    <th width="2%" >#</th>
        <th width="10%">Device Name</th>
        <th width="15%">Start Time</th>
        <th width="15%">End Time</th>
        <th>Location</th>
        <th width="15%">Duration</th>
    </tr>
  <?php
$sdate = $_POST[from_date];
$srcData = explode("#",$_POST[map_device_id]);
//print_r($srcData);
//exit;
$timediff1=array();
$totalDistance=0;
$stopdata = "Date,Device Name,Start Time,End Time,Location,Duration";
$stopdata .= "@";
$ct=0;
$st_time = explode(":",date("H:i",strtotime($_POST[time3])));
$st_time = (($st_time[0] * 60) + $st_time[1]);

$en_time = explode(":",date("H:i",strtotime($_POST[time4])));
$en_time = (($en_time[0] * 60) + $en_time[1]);

$timediff1=getStopReport($sdate,$srcData[0],$srcData[1],$st_time,$en_time);

	if(count($timediff1)>0)
	{
		//print_r($timediff1);
		//exit;
		$finalTime = 0;
		for($r=0;$r<count($timediff1);$r++)
		{
			$tTdiff=explode("#",$timediff1[$r]);
			$stopdata .= $sdate.",".$tTdiff[5].",".date("h:i:s A",strtotime($tTdiff[0])).",".date("h:i:s A",strtotime($tTdiff[2])).",".$tTdiff[1].",".$tTdiff[3];
			$stopdata .= "@";
			//$finalTime += strtotime($tTdiff[3]);		
			//echo $finalTime." ".$tTdiff[6]."<br>";
?> 
        <tr <?php if($r % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
            <td valign="top"><?php echo $r+1; ?></td>
            <td valign="top"><?php echo $tTdiff[5]; ?></td>
            <td valign="top">
			<?php
	        echo date("H:i:s A",strtotime($tTdiff[0])); 
	       // echo TimeCvt($tTdiff[0],'0'); 
            ?>
			</td>
            <td valign="top"><?php echo date("H:i:s A",strtotime($tTdiff[2])); ?></td>
            <td valign="top"><?php echo $tTdiff[1]; ?></td>
            <td valign="top"><?php echo $tTdiff[3]; ?></td>
        </tr>
<?php   
		}	
		//echo date("H:i:s",$finalTime)." sss ".gmdate("H:i:s",$tTdiff[6]);
		$stopdata .= "@";
		$stopdata .= ",,,,Total Time,".gmdate("H:i:s",$tTdiff[6]);
		$stopdata .= "@";
		
		?>
        <tr><td colspan="4">&nbsp;</td><td align="right">Total Time</td><td><?php echo gmdate("H:i:s",$tTdiff[6]);?></td></tr>
        <?php
	}
	else
	{
		echo '<tr><td colspan="6" align="center">No Records Found</td></tr>'; 
		echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 

	}
 ?>	  
 </table>
<form name="frmStopData" id="frmStopData" method="post" action="../report/export.php">
<input type="hidden" name="txtStopData" id="txtStopData" value="<?php echo $stopdata;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $srcData[1]; ?>" />
<input type="hidden" name="txtDate" id="txtDate" value="<?php echo $_POST[from_date]; ?>" />
</form>

</div>	
<script type="text/javascript">

hidePreLoader();
</script>	
	
<?php
  
}///end of post

?> 

