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



$vehicles_query = "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]."  AND vi_userId=".$_SESSION[userID]." 
ORDER BY vi_id";
$vehicles_resp = mysql_query($vehicles_query);	


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
  if(document.getElementById('map_vehicle_id').value== 0 )
  {
   alert("Select Vehicle"); 
   document.getElementById('map_vehicle_id').focus();
   return 0;  
  }
  
  if(document.getElementById('from_date').value=='')
  {
   alert("Select Date");
   document.getElementById('from_date').focus();
   return 0;  
  }
  
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);

	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

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
  document.frmOverSpeed.submit();
}


</script>

 
<form id="frm_map_filter" name="frm_map_filter" method="post" action="">      	
<table id="Table_01" width="770" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
		<td width="16">
			<img src="images/frame_01.png" width="16" height="15" alt=""></td>
  <td width="738" background="images/frame_02.png">
			<img width="1" height="15" alt=""></td>
<td width="16">
			<img src="images/frame_03.png" width="16" height="15" alt=""></td>
  </tr>
	<tr>
		<td background="images/frame_04.png">
			<img src="images/frame_04.png" width="16" height="1" alt=""></td>
	  <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="81%" height="25" bgcolor="#116999" style="padding-left:10px;"><span class="style1">Over Speed Report</span></td>
            <td width="15%" align="right" bgcolor="#116999" class="style2" style="padding-right:10px;"><a style="color:#BFD449;" href="index.php?ch=reportPanel"><strong>Back to Report</strong></a></td>
          </tr>
          <tr>
            <td colspan="3" align="center" bgcolor="#FFFFFF"><table width="89%" border="0" cellspacing="5" cellpadding="5">

              <tr>
                <td width="17%" align="right"><span class="form_text">
                  <label></label>
Select Vechicle&nbsp;</span></td>
                <td width="36%">
                	<select name="map_vehicle_id" id="map_vehicle_id" class="hours_select" tabindex="1" style="width:145px;" >
                    <option value="0">Select Vehicle</option>
                    <?php while($vehicles_fetch = @mysql_fetch_assoc($vehicles_resp)) { 
						$vehicles_fin = "SELECT * FROM vehicle_finance_info WHERE vfi_vi_id=".$vehicles_fetch[vi_id];
						$vehicles_fin_res = mysql_query($vehicles_fin);	
						$vehicles__fin_fetch = @mysql_fetch_assoc($vehicles_fin_res);
							if($vehicles__fin_fetch [vfi_own_hired]==2)
								$ownship="Hired";
							else
								$ownship="Own";
					?>
                    <option value="<?php echo $vehicles_fetch[vi_reg_no]; ?>" 
                    <?php if($_POST[map_vehicle_id] == $vehicles_fetch[vi_reg_no]) echo "selected"; ?>><?php echo $vehicles_fetch[vi_reg_no]." - ".$ownship; ?></option>
                    <?php } ?>	
                    </select>
                    <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    			</td>
                <td width="6%" align="right"><span class="form_text">Date&nbsp;</span></td>
                <td width="41%">
                <input type="text" name="from_date" id="from_date" tabindex="2" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/><input type="button" name="button" value=".." class="sub_btn" onclick="return showCalendar('from_date','%Y-%m-%d');" /></td>
              </tr>              
              <tr>
                <td height="33" colspan="4" align="center">
                <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="8"  onclick="showPreloader();"/>
                <input type="hidden" name="map_filter_btn" value="Filter" />
                <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=overSpeedReport';" tabindex="9" /> 
				<?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
                <input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="sub_btn" style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
              </tr>
            </table></td>
          </tr>
          
        </table></td>
<td background="images/frame_06.png" >
	  <img src="images/frame_06.png" width="16" height="1" alt=""></td>
  </tr>
	<tr>
		<td>
			<img src="images/frame_07.png" width="16" height="15" alt=""></td>
<td background="images/frame_08.png">
			<img src="images/frame_08.png" width="1" height="15" alt=""></td>
<td>
			<img src="images/frame_09.png" width="16" height="15" alt=""></td>
	</tr>
</table>
</form>
<div id="popup_div" style=" display:block; border:0px;" >

</div>
<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{  
?>
<div style="height:450px; width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table width="882" border="0" cellspacing="0" cellpadding="0" id="grid">
      
      <tr class="heading_tr">
        <td width="4%" >#</td>
        <td width="9%">Time</td>
        <td width="11%">Vehicle no</td>
        <td width="53%" >Location</td>
        <td width="12%">Speed(kmph)</td>
        <td width="11%">Status</td>
    </tr>
<?php
$sdate = $_POST[from_date];

  $cnt = 1;
     
$query = "SELECT gti_min_speed, gti_max_speed FROM gps_task_info,vehicle_info WHERE vi_reg_no='".$_POST[map_vehicle_id]."' 
 AND vi_id=gti_vehicle_id AND gti_client_ST='T' AND '".$sdate."' BETWEEN gti_start_date AND gti_end_date";	 
 $resp = mysql_query($query);
if(mysql_num_rows($resp) > 0)
{
$row = mysql_fetch_assoc($resp);	 
$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$totalDistance=0;
	 
$outData = "Date,Vehicle,Time,Location,Speed(kmph),Max.Speed";
$outData .= "@";
	 
$file = $dataPath."client_".$_SESSION[clientID]."/".date("d-m-Y", strtotime($sdate))."/".$_POST[map_vehicle_id].".txt";   
	
	 
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
		$data2=explode("$",$data1[$j1]);
		if(count($data2)>1)
		{
		$data3=explode(",",$data2[1]);
		$geodate=date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
		$geoTime=date("h:i:s A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));		
		//echo date("d-m-Y", strtotime($sdate))."==".date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
		if(date("d-m-Y", strtotime($sdate))==date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3])))
		{
			$pos1=convertLat(calLat($data3[7]));
			$pos2=convertLong(calLong($data3[8]));
		if($pos1>0 && $pos2>0)
		{
		if(!in_array($geoTime,$timeArr))
		{
		if(($data3[9]) > $row[gti_max_speed])
		{	
		//echo $data3[11];
		$res=simpleGeocode($pos1,$pos2);
		//print_r($res);
		$res=str_replace('"',"",$res);
		//echo $res."<br>";
		
		$outData .= $geodate.','.$data3[0].','.$geoTime.','.$res.','.round($data3[9]).','.$row[gti_max_speed];
		$outData .= "@";	   
	   
?>	  
      <tr <?php if($cnt % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td width="4%"><?php echo $cnt; ?></td>
		<td width="9%"><?php echo $geoTime; ?></td>
   		<td width="11%"><?php echo $data3[0]; ?></td>
		<td width="53%"><?php echo $res; ?></td>
		<td width="12%"><?php echo round($data3[9]); ?></td>
		<td width="11%"><?php echo '(Max .'.$row[gti_max_speed].' kmph)'; ?></td>
    </tr>
 <?php   $cnt++; 
 		 //if($cnt > 200) break;
       }
			array_push($timeArr,$geoTime);
	   }
	   }
	   }
	   }
	   }
	   
	 } ///end of while
	 
     @fclose($handle);
    } ///end of if(file exists)
  /*}*/ ///end of for
  } ///end of mysqlnumrows(if max speed found)
	 if($cnt == 1)
	 {
		 echo '<tr><td colspan="8" align="center">&nbsp; No Recddords Found.</td></tr>';  
		 echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 
	 }
  
 ?>	  
    </table>
	
<form name="frmOverSpeed" id="frmOverSpeed" method="post" action="Report/export.php">
<input type="hidden" name="txtOverSpeed" id="txtOverSpeed" value="<?php echo $outData;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $_POST[map_vehicle_id]; ?>" />
<input type="hidden" name="txtDate" id="txtDate" value="<?php echo $_POST[from_date]; ?>" />
</form>	
	
		
</div>	
<script type="text/javascript">

hidePreLoader();
</script>	
	
<?php
  
}///end of post

?>
