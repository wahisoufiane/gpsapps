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
/*  echo 'fromtime - '.$_POST[from_time];
  echo '<br/>totime - '.$_POST[to_time];*/
  
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

function GetDays($sStartDate, $sEndDate){
  $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
  $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));

  $aDays[] = $sStartDate;
  $sCurrentDate = $sStartDate;

  while($sCurrentDate < $sEndDate){
    // Add a day to the current date
    $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
    $aDays[] = $sCurrentDate;
  }
  return $aDays;
}

function getTripReport($sdate,$vehId)
{
$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$timediff=array();
$strTime='';
$endTime='';

	$file = $GLOBALS[dataPath]."src/data/".date("m-d-Y", strtotime($sdate))."/".$vehId.".txt";   
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
				$vehi=$data3[0];
				$geodate = $data3[8]." ".$data3[9];
				$geoTime = $data3[9];
				$pos1=calLat($data3[2]);
				$pos2=calLong($data3[1]);	
				if($pos1>0 && $pos2>0)
				{
				if(!in_array($geoTime,$timeArr))
				{
				//echo $geoTime."	sss ".$data3[3];
				//echo "<BR>";
				if($data3[3]>0 && $strTime=='')
				{
					$strTime=$geoTime;
				}
				else if($data3[3]==0 && $endTime=='' && $strTime!='')
				{
					$endTime=$geoTime;
					$res2=simpleGeocode($pos1,$pos2);
					$res2=str_replace('"',"",$res2);	
					$finaTime=$strTime."#".$endTime."#".gmdate("H:i:s",(strtotime($endTime) - strtotime($strTime)))."#".$res2."#".$pos1."#".$pos2."#".$vehi;
					$strTime='';
					$endTime='';
					array_push($timediff, $finaTime);
					
				}
					$cnt++; 
	
					array_push($timeArr,$geoTime);
					//$t1=$pos1;
					//$t2=$pos2;
				}
			}
			}
		}
		fclose($file1);
		}
	}
	return($timediff);
}


$devices_query =  "SELECT * FROM tb_deviceinfo WHERE di_clientId =".$_SESSION[clientID]." ORDER BY di_createDate ASC";
$devices_resp = mysql_query($devices_query);	


?>
<script type="text/javascript" language="javascript">

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
document.frmTripData.submit();
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
		dateFormat : "dd/mm/yy"
	});
});
</script>

 
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();">      	 
<table class="gridform">
  <tr>
    <td width="17%" align="right"><span class="form_text">
      <label></label>
Select Device&nbsp;</span></td>
    <td width="36%" align="left">
    <select name="map_device_id" id="map_device_id" class="hours_select" tabindex="1" style="width:175px;" >
        <option value="0">Select Device</option>
        <?php 
		while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{
        ?>
        <option value="<?php echo $devices_fetch[di_imeiId]; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_imeiId]) echo "selected"; ?>><?php echo $devices_fetch[di_deviceId]." - ".$devices_fetch[di_imeiId]; ?></option>
        <?php } ?>	
        </select>
     <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
  </tr>              
  <tr>
    <td width="6%" align="right"><span class="form_text">Date&nbsp;</span></td>
    <td width="41%" align="left">
    <input type="text" name="from_date" id="from_date" tabindex="2" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/></td>
  </tr>
  
  <tr>
    <td height="33" colspan="2" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="8"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=trip';" tabindex="9" /> 
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
?>
<div style="height:450px; width:900px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">      
      <tr>
	    <th width="4%" >#</th>
        <th width="12%">Device No.</th>
        <th width="13%">Start Time</th>
        <th width="12%">End Time</th>
        <th width="50%" >Destination</th>
        <th width="9%">Duration</th>
    </tr>
  <?php
$sdate = $_POST[from_date];
//$edate = $_POST[to_date];

//$z = GetDays($sdate, $edate);

$timediff1=array();
$totalDistance=0;
$tripdata = "VehicleNo,Date,Start Time,End Time,Destination,Duration";
$tripdata .= "@";
$ct=0;
$timediff1=getTripReport($sdate,$_POST[map_device_id]);
	if(count($timediff1)>0)
	{
	for($r=0;$r<count($timediff1);$r++)
	{
		$tTdiff=explode("#",$timediff1[$r]);
		$tripdata .= $tTdiff[6].','.$sdate.','.date("h:i:s A",strtotime($tTdiff[0])).','.date("h:i:s A",strtotime($tTdiff[1])).','.$tTdiff[3].','.$tTdiff[2];
		$tripdata .= "@";		
?> 
	<tr <?php if($r % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
	<td valign="top"><?php echo $r+1; ?></td>
	<td valign="top"><?php echo $tTdiff[6]; ?></td>
	<td valign="top"><?php echo date("h:i:s A",strtotime($tTdiff[0])); ?></td>
	<td valign="top"><?php echo date("h:i:s A",strtotime($tTdiff[1])); ?></td>
	<td valign="top"><?php echo $tTdiff[3]; ?></td>
	<td valign="top"><?php echo $tTdiff[2]; ?></td>
	</tr>
<?php   
}	
	}
	else
	{
		echo '<tr><td colspan="7" align="center">No Records Found</td></tr>'; 
		echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 

	}
 ?>	  
 </table>
<form name="frmTripData" id="frmTripData" method="post" action="../report/export.php">
<input type="hidden" name="txtTripData" id="txtTripData" value="<?php echo $tripdata;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $_POST[map_device_id]; ?>" />
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

