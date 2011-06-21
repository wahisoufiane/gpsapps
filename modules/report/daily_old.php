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

$devices_query = "SELECT * FROM tb_deviceinfo WHERE di_clientId =".$_SESSION[clientID]." ORDER BY di_createDate ASC";
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
function sendCSVData()
{
//alert(c1);
document.frmCSVData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
function getAddress(lat,long,div)
{
	var addr="";
	address=(lat+","+long);
	var geocoder2 = new GClientGeocoder();
geocoder2.getLocations(address, function(response) {
  if (!response || response.Status.code != 200) {
	//alert("Status Code:" + response.Status.code);
	//document.getElementById(div).innerHTML= "Status Code:"+response.Status.code;
	if(response.Status.code==620)
	  {
		 getAddress(lat,long,div);
	  }
  } else {
	place = response.Placemark[0];
	addr=place.address;
	addr=addr.split(",");
	//alert(place.address);
	document.getElementById(div).innerHTML=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
}
});
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
   alert("Select Vehicle"); 
   document.getElementById('map_device_id').focus();
   return 0;  
  }
  
  if(document.getElementById('from_date').value=='')
  {
   alert("Select Date");
   document.getElementById('from_date').focus();
   return 0;  
  }
  
/*  if(document.getElementById('to_date').value=='')
  {
   alert("Select To Date"); 
   document.getElementById('to_date').focus();
    return 0; 
  
  }*/
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	//var todt_array = document.getElementById('to_date').value.split("-");
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
	//var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

	//var fr_to_diff = days_between(frdate, todate);
	//var days_diff = days_between(todate, curdate);
	var days_diff = days_between(frdate, curdate);

	//var diff = todate-curdate;
	//if(diff >=0) { alert(""); }
	
	//alert('input diff -'+fr_to_diff);
	
/*	if(fr_to_diff < -6)
	{
	 alert("Date range should not be more than 7 days."); 
	 document.getElementById('to_date').focus();
	 return 0;
	}
	
	if(fr_to_diff > 0)
	{ 
	 alert("From Date should be prior to To Date.");
	 document.getElementById('to_date').focus();
	 return 0;
	 }
	
	//alert(days_diff); 
*/	
	if(days_diff > 0)
	{ 
	 alert("Date should not be future.");
	 document.getElementById('to_date').select();
	  return 0;
	}
	
	var time_diff = document.getElementById('to_hrs').value - document.getElementById('from_hrs').value ; 
	if(time_diff < 0)
	{
	  alert("From Time Should be Prior to To Time(Hrs).");
	  return 0;
	 } 
	 
	if(time_diff == 0)
	{
	  var t_diff = document.getElementById('to_mins').value - document.getElementById('from_mins').value;
	  if(t_diff < 0)
	  {
	    alert("From Time Should be Prior to To Time(Mins).");
		return 0;
	  }	
		
	} 
	
	return 1;
	
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
    <td width="17%" align="right">Select Device&nbsp;</td>
    <td width="36%">
    <select name="map_device_id" id="map_device_id" class="hours_select" tabindex="1" style="width:145px;" >
        <option value="0">Select Device</option>
        <?php while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{
        
         ?>
        <option value="<?php echo $devices_fetch[di_imeiId]; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_imeiId]) echo "selected"; ?>><?php echo $devices_fetch[di_deviceId]." - ".$devices_fetch[di_imeiId]; ?></option>
        <?php } ?>	
        </select>
     <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    <td width="6%" align="right"><span class="form_text">Date&nbsp;</span></td>
    <td width="41%">
    <input type="text" name="from_date" id="from_date" tabindex="2" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/></td>
  </tr>
  <tr>
    <td height="19" align="right"><label class="form_text">From &nbsp;</label></td>
    <td height="19"><table class="gridform">
      <tr>
        <td width="30" class="form_text">Hrs</td>
        <td width="47"><label>
          <select name="from_hrs" id="from_hrs"  tabindex="3">
            <option value="00" <?php if($_POST[from_hrs] == '00') echo 'Selected'; ?>>00</option>
            <option value="01" <?php if($_POST[from_hrs] == '01') echo 'Selected'; ?>>01</option>
            <option value="02" <?php if($_POST[from_hrs] == '02') echo 'Selected'; ?>>02</option>
            <option value="03" <?php if($_POST[from_hrs] == '03') echo 'Selected'; ?>>03</option>
            <option value="04" <?php if($_POST[from_hrs] == '04') echo 'Selected'; ?>>04</option>
            <option value="05" <?php if($_POST[from_hrs] == '05') echo 'Selected'; ?>>05</option>
            <option value="06" <?php if($_POST[from_hrs] == '06') echo 'Selected'; ?>>06</option>
            <option value="07" <?php if($_POST[from_hrs] == '07') echo 'Selected'; ?>>07</option>
            <option value="08" <?php if($_POST[from_hrs] == '08') echo 'Selected'; ?>>08</option>
            <option value="09" <?php echo $fr_hr_selected; ?> <?php if($_POST[from_hrs] == '09') echo 'Selected'; ?>>09</option>
            <option value="10" <?php if($_POST[from_hrs] == '10') echo 'Selected'; ?>>10</option>
            <option value="11" <?php if($_POST[from_hrs] == '11') echo 'Selected'; ?>>11</option>
            <option value="12" <?php if($_POST[from_hrs] == '12') echo 'Selected'; ?>>12</option>
            <option value="13" <?php if($_POST[from_hrs] == '13') echo 'Selected'; ?>>13</option>
            <option value="14" <?php if($_POST[from_hrs] == '14') echo 'Selected'; ?>>14</option>
            <option value="15" <?php if($_POST[from_hrs] == '15') echo 'Selected'; ?>>15</option>
            <option value="16" <?php if($_POST[from_hrs] == '16') echo 'Selected'; ?>>16</option>
            <option value="17" <?php if($_POST[from_hrs] == '17') echo 'Selected'; ?>>17</option>
            <option value="18" <?php if($_POST[from_hrs] == '18') echo 'Selected'; ?>>18</option>
            <option value="19" <?php if($_POST[from_hrs] == '19') echo 'Selected'; ?>>19</option>
            <option value="20" <?php if($_POST[from_hrs] == '20') echo 'Selected'; ?>>20</option>
            <option value="21" <?php if($_POST[from_hrs] == '21') echo 'Selected'; ?>>21</option>
            <option value="22" <?php if($_POST[from_hrs] == '22') echo 'Selected'; ?>>22</option>
            <option value="23" <?php if($_POST[from_hrs] == '23') echo 'Selected'; ?>>23</option>
          </select>
        </label></td>
        <td width="29" class="form_text">Mins</td>
        <td width="105"><label>
          <select name="from_mins" id="from_mins"  tabindex="4">
            <option value="00" <?php echo $fr_min_selected; ?> <?php if($_POST[from_mins] == '00') echo 'Selected'; ?>>00</option>
            <option value="05"<?php if($_POST[from_mins] == '05') echo 'Selected'; ?>>05</option>
            <option value="10"<?php if($_POST[from_mins] == '10') echo 'Selected'; ?>>10</option>
            <option value="15"<?php if($_POST[from_mins] == '15') echo 'Selected'; ?>>15</option>
            <option value="20"<?php if($_POST[from_mins] == '20') echo 'Selected'; ?>>20</option>
            <option value="25"<?php if($_POST[from_mins] == '25') echo 'Selected'; ?>>25</option>
            <option value="30"<?php if($_POST[from_mins] == '30') echo 'Selected'; ?>>30</option>
            <option value="35"<?php if($_POST[from_mins] == '35') echo 'Selected'; ?>>35</option>
            <option value="40"<?php if($_POST[from_mins] == '40') echo 'Selected'; ?>>40</option>
            <option value="45"<?php if($_POST[from_mins] == '45') echo 'Selected'; ?>>45</option>
            <option value="50"<?php if($_POST[from_mins] == '50') echo 'Selected'; ?>>50</option>
            <option value="55"<?php if($_POST[from_mins] == '55') echo 'Selected'; ?>>55</option>
          </select>
        </label></td>
      </tr>
    </table></td>
    <td align="right"><span class="form_text">To&nbsp;</span></td>
    <td><table class="gridform">
      <tr>
        <td width="30" class="form_text">Hrs</td>
        <td width="47"><label>
          <select name="to_hrs" id="to_hrs"  tabindex="6">
            <option value="01" <?php if($_POST[to_hrs] == '01') echo 'Selected'; ?>>01</option>
            <option value="02" <?php if($_POST[to_hrs] == '02') echo 'Selected'; ?>>02</option>
            <option value="03" <?php if($_POST[to_hrs] == '03') echo 'Selected'; ?>>03</option>
            <option value="04" <?php if($_POST[to_hrs] == '04') echo 'Selected'; ?>>04</option>
            <option value="05" <?php if($_POST[to_hrs] == '05') echo 'Selected'; ?>>05</option>
            <option value="06" <?php if($_POST[to_hrs] == '06') echo 'Selected'; ?>>06</option>
            <option value="07" <?php if($_POST[to_hrs] == '07') echo 'Selected'; ?>>07</option>
            <option value="08" <?php if($_POST[to_hrs] == '08') echo 'Selected'; ?>>08</option>
            <option value="09" <?php if($_POST[to_hrs] == '09') echo 'Selected'; ?>>09</option>
            <option value="10" <?php if($_POST[to_hrs] == '10') echo 'Selected'; ?>>10</option>
            <option value="11" <?php if($_POST[to_hrs] == '11') echo 'Selected'; ?>>11</option>
            <option value="12" <?php if($_POST[to_hrs] == '12') echo 'Selected'; ?>>12</option>
            <option value="13" <?php if($_POST[to_hrs] == '13') echo 'Selected'; ?>>13</option>
            <option value="14" <?php if($_POST[to_hrs] == '14') echo 'Selected'; ?>>14</option>
            <option value="15" <?php if($_POST[to_hrs] == '15') echo 'Selected'; ?>>15</option>
            <option value="16" <?php if($_POST[to_hrs] == '16') echo 'Selected'; ?>>16</option>
            <option value="17" <?php if($_POST[to_hrs] == '17') echo 'Selected'; ?>>17</option>
            <option value="18" <?php echo $to_hr_selected; ?> <?php if($_POST[to_hrs] == '18') echo 'Selected'; ?>>18</option>
            <option value="19" <?php if($_POST[to_hrs] == '19') echo 'Selected'; ?>>19</option>
            <option value="20" <?php if($_POST[to_hrs] == '20') echo 'Selected'; ?>>20</option>
            <option value="21" <?php if($_POST[to_hrs] == '21') echo 'Selected'; ?>>21</option>
            <option value="22" <?php if($_POST[to_hrs] == '22') echo 'Selected'; ?>>22</option>
            <option value="23" <?php if($_POST[to_hrs] == '23') echo 'Selected'; ?>>23</option>
            <option value="24" <?php if($_POST[to_hrs] == '24') echo 'Selected'; ?>>24</option>
          </select>
        </label></td>
        <td width="29" class="form_text">Mins</td>
        <td width="105"><label>
          <select name="to_mins" id="to_mins"  tabindex="7">
              <option value="00" <?php echo $to_min_selected; ?> <?php if($_POST[to_mins] == '00') echo 'Selected'; ?>>00</option>
              <option value="05" <?php if($_POST[to_mins] == '05') echo 'Selected'; ?>>05</option>
              <option value="10" <?php if($_POST[to_mins] == '10') echo 'Selected'; ?>>10</option>
              <option value="15" <?php if($_POST[to_mins] == '15') echo 'Selected'; ?>>15</option>
              <option value="20" <?php if($_POST[to_mins] == '20') echo 'Selected'; ?>>20</option>
              <option value="25" <?php if($_POST[to_mins] == '25') echo 'Selected'; ?>>25</option>
              <option value="30" <?php if($_POST[to_mins] == '30') echo 'Selected'; ?>>30</option>
              <option value="35" <?php if($_POST[to_mins] == '35') echo 'Selected'; ?>>35</option>
              <option value="40" <?php if($_POST[to_mins] == '40') echo 'Selected'; ?>>40</option>
              <option value="45" <?php if($_POST[to_mins] == '45') echo 'Selected'; ?>>45</option>
              <option value="50" <?php if($_POST[to_mins] == '50') echo 'Selected'; ?>>50</option>
              <option value="55" <?php if($_POST[to_mins] == '55') echo 'Selected'; ?>>55</option>
            </select>
        </label></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="click_btn" tabindex="8"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="click_btn" onclick="location.href='index.php?ch=daily';" tabindex="9" /> 
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
?>
<div style="height:450px; width:900px; overflow:scroll; overflow-X:hidden; border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">
    <tr>
      <th width="5%" >#</th>
      <th width="25%">Date & Time </th>
      <th width="54%" >Location</th>
      <th width="10%">Distance(km)</th>
      <th width="12%">Speed(kmph)</th>
    </tr>
<?php
//print_r($_POST);

$sdate = $_POST[from_date];
//$edate = $_POST[to_date];

//$z = GetDays($sdate, $edate);
$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$totalDistance=0;
$odata = "VehicleNo,Date-Time,Location,Distance,Direction,Speed(km)";
$odata .= "@";

$file = $dataPath."src/data/".date("m-d-Y", strtotime($sdate))."/".$_POST[map_device_id].".txt";   
//exit;
	if(chk_folder($file))
	{
		$file1 = @fopen($file, "r");
		if($file1)
		{
		$i=0;
		while(!feof($file1))
	    {
			$data= fgets($file1);
		   //$i++;
		}
		$data = getSortedData($data);
		 $data1=explode("#",$data);
		 for($j1=0;$j1<count($data1);$j1++)
		 {
			$data2=explode("@",$data1[$j1]);
			if(count($data2)>1)
			{
			$data3=explode(",",$data2[1]);
			
			$geodate = $data3[8]." ".$data3[9];
			$geoTime = $data3[9];
			$curTime = explode(":",$data3[9]);
			$curTime = (($curTime[0] * 60) + $curTime[1]);
			
			$srtTime = ($_POST[from_hrs]*60)+$_POST[from_mins];
			$endTime = ($_POST[to_hrs]*60)+$_POST[to_mins];
			
			if($curTime >= $srtTime && $curTime<=$endTime)
			{
			//echo $show;
			$vehi=$data3[0];
			$pos1=calLat($data3[2]);
			$pos2=calLong($data3[1]);
			if($pos1>0 && $pos2>0)
			{
			if(!in_array($geoTime,$timeArr) )
			{
			//echo round($data3[9]);
			if($cnt==1)
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
			if($tmp != round($totalDistance))
			{
			$res=simpleGeocode($pos1,$pos2);
			//print_r($res);
			$res=str_replace('"',"",$res);

			$odata .= $data3[0].','.$geodate.','.$res.','.round($totalDistance).','.$data3[4].','.round($data3[3]);
			$odata .= "@";		
?>	  
      <tr <?php if($cnt % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td width="11%"><?php echo $cnt; ?></td>
		<td width="13%"><?php echo date("d-m-Y h:i A",strtotime($geodate)); ?></td>
		<td width="54%"><?php echo $res; ?></td>
   		<td width="10%"><?php echo round($totalDistance); ?></td>
		<td width="12%"><?php echo round($data3[3]); ?></td>
    </tr>
 <?php   
				$cnt++; 
				$tmp=round($totalDistance);
				}
					array_push($timeArr,$geoTime);
					//$t1=$pos1;
					//$t2=$pos2;
				}
				}
			}
		}
	}
	
		fclose($file1);
?>	  
      <tr <?php if($cnt % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td width="11%">&nbsp;</td>
		<td width="13%">&nbsp;</td>
		<td width="54%">Total Distance</td>
   		<td width="10%"><?php echo round($totalDistance);?></td>
		<td width="12%">&nbsp;</td>
    </tr>
 <?php   
		}
		else
		{
			echo '<tr><td colspan="8" align="center">&nbsp; No Records Found.</td></tr>';  
			echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 
		}
	}else
		{
			echo '<tr><td colspan="8" align="center">&nbsp; No Records Found.</td></tr>';  
			echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 
		}
 ?>	  
</table>
<form name="frmCSVData" id="frmCSVData" method="post" action="../report/export.php">
<input type="hidden" name="txtCSVData" id="txtCSVData" value="<?php echo $odata;?>" />
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
