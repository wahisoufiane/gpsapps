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

/*function GetDays($sStartDate, $sEndDate){
  $sStartDate = date("Y-m-d", strtotime($sStartDate));
  $sEndDate = date("Y-m-d", strtotime($sEndDate));

  $aDays[] = $sStartDate;

  $sCurrentDate = $sStartDate;

  while($sCurrentDate < $sEndDate){
    $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

    $aDays[] = $sCurrentDate;
  }

  return $aDays;
}*/



function kmsPerDay($path)
{ 
  $timeArr = array();
  $cnt = 1;
  $totalDistance = 0;

  $file1 = @fopen($path, "r");
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

			$vehi=$data3[0];
			
			$geodate=date("d-m-Y h:i A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$geoTime=date("H:i:s A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
			$pos1=convertLat(calLat($data3[7]));
			$pos2=convertLong(calLong($data3[8]));
			if($pos1>0 && $pos2>0)
			{
			if(!in_array($geoTime,$timeArr))
			{
			if($j1==0)
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

			
			
			$mph = $data3[9];
			$direction = $data3[10];
			$distance = '';
			$date = $date;
			$locationMethod = '327681';
			//echo getDateFromJavaDate($date);
			$phoneNumber = $data3[0];
			$sessionID = $_GET["sessionID"] ;
			$accuracy = 11;
			$locationIsValid = yes;
			$extraInfo = $data3[11];
		
	
		$xml.='<locations latitude="'.$pos1.'" longitude="'.$pos2. '" speed="'.$mph. '" direction="'.$direction.'" distance="'.round($totalDistance,2).'" locationMethod="327681" gpsTime="'.$geodate.'" phoneNumber="'.$phoneNumber.
	  '" sessionID="'.$sessionID.'" accuracy="11" isLocationValid="yes" extraInfo="'.$extraInfo.'" />';
		}
		}
		}
		array_push($timeArr,$geoTime);
	}
	//echo $data3[11];
	//$res=simpleGeocode($pos1,$pos2);
	//print_r($res);
	//$res=str_replace(",","-",$res[0]);
	//echo $res."<br>";
	//echo round($totalDistance,2);
	$finalData = round($totalDistance)."#".$geodate."#".$res;
	fclose($file1);
	return $finalData;
	}
}
?>
<script type="text/javascript" language="javascript">
function fuelRadios()
{
	if(document.getElementById('fuelRadio1').checked == true)
		window.location.href="index.php?ch=fuelConsumeReport";
	else if(document.getElementById('fuelRadio2').checked == true)
		document.frm_map_filter.submit();
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
	if(document.getElementById('fuelRadio1').checked)
	{
		if(document.getElementById('map_vehicle_id').value== 0 )
		{ alert("Select Vehicle");  document.getElementById('map_vehicle_id').focus();  return 0;  }
		
		if(document.getElementById('from_date').value=='')
		{ alert("Select From Date"); document.getElementById('from_date').focus();  return 0;  }
		
		if(document.getElementById('to_date').value=='')
		{ alert("Select To Date");  document.getElementById('to_date').focus(); return 0; }
		
		var curdt_array = document.getElementById('curdate').value.split("-");   
		var todt_array = document.getElementById('to_date').value.split("-");
		var frdt_array = document.getElementById('from_date').value.split("-");	
		
		var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
		var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
		var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);
		
		var fr_to_diff = days_between(frdate, todate);
		var days_diff = days_between(todate, curdate);
		
		if(fr_to_diff > 0)
		{ alert("From Date should be prior to To Date."); document.getElementById('to_date').select(); return 0;}
		
		//alert(days_diff); 
		
		if(days_diff > 0)
		{ alert("To Date should not be future."); document.getElementById('to_date').select(); return 0;}
		
		return 1;
		
	}
	else if(document.getElementById('fuelRadio2').checked)
	{
		if(document.getElementById('fuel_vehicle_id').value== 0)
		{ alert("Select Vehicle");  document.getElementById('fuel_vehicle_id').focus();  return 0;  }
		return 1;
	}
}


function sendCSVData()
{
  document.frmKiloData.submit();
}

function sendCSVData_mil()
{
	document.frmFuelMilData.submit();
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

</script>

<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return showPreloader();">
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
            <td width="81%" height="25" bgcolor="#116999" style="padding-left:10px;"><span class="style1">Fuel Consumption Report</span></td>
            <td width="15%" align="right" bgcolor="#116999" class="style2" style="padding-right:10px;"><a style="color:#BFD449;" href="index.php?ch=reportPanel"><strong>Back to Report</strong></a></td>
          </tr>
		  <tr><td colspan="3" align="center" bgcolor="#FFFFFF"><span class="form_text">
		  	<input type="radio" name="fuelRadio" id="fuelRadio1" value="1" tabindex="1" 
			onclick="fuelRadios();" <?php if($_POST[fuelRadio]=="" || $_POST[fuelRadio]==1) echo "checked=checked"; 
			else echo ""; ?> />Normal&nbsp;&nbsp;
			<input type="radio" name="fuelRadio" id="fuelRadio2" value="2" tabindex="2" onclick="fuelRadios();" 
			<?php if($_POST[fuelRadio]==2) echo "checked=checked"; else echo ""; ?> />
			Trip</span></td></tr>
          <tr>
            <td colspan="3" align="center" bgcolor="#FFFFFF">
			<span id="normalFuelDiv" style="display:<?php if($_POST[fuelRadio]=="" || $_POST[fuelRadio]==1) echo "block"; 
			else echo "none"; ?>;">
			<?php 
				$vehicles_query = "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]." 
				AND vi_userId=".$_SESSION[userID]." ORDER BY vi_id";
				$vehicles_resp = mysql_query($vehicles_query);
			?>
			<table width="89%" border="0" cellspacing="5" cellpadding="5">

              <tr>
                <td width="19%" align="right"><span class="form_text">
				Select Vechicle&nbsp;</span></td>
                <td width="35%" align="left">
                	<select name="map_vehicle_id" id="map_vehicle_id" class="hours_select" tabindex="1" style="width:170px;" >
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
                </tr>              
                <tr>
                <td width="19%" align="right"><span class="form_text">From Date&nbsp;</span></td>
                <td width="35%" align="left">
                <input type="text" name="from_date" id="from_date" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/><input type="button" name="button" tabindex="2" value=".." class="sub_btn" onclick="return showCalendar('from_date','%Y-%m-%d');" /></td>
              </tr>
			  <tr>
			   <td width="19%" align="right"><span class="form_text">To Date&nbsp;</span></td>
                <td width="35%">
   				<input type="text" name="to_date" id="to_date" size="12" style="width:140px;" readonly="true" value="<?php echo $_POST[to_date]; ?>" /><input type="button" name="button2" tabindex="3" value=".." class="sub_btn" onclick="return showCalendar('to_date','%Y-%m-%d');" /></td>
				</tr>
              <tr>
                <td height="33" colspan="4" align="center">
                <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="4"  onclick="showPreloader();"/>
                <input type="hidden" name="map_filter_btn" value="Filter" />
                <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=fuelConsumeReport';" tabindex="9" /> 
				<?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
                <input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="sub_btn" style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
              </tr>
            </table>
			</span>
			<span id="tripFuelDiv" style="display:<?php if($_POST[fuelRadio]==2) echo "block"; 
			else echo "none"; ?>;">
			<?php 
				$vehicles_query1 = "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]." 
				AND vi_userId=".$_SESSION[userID]." ORDER BY vi_id";
				$vehicles_resp1 = mysql_query($vehicles_query1);
			?>
			<table width="89%" border="0" cellspacing="5" cellpadding="5">

              <tr>
                <td width="19%" align="right"><span class="form_text">Select Vechicle&nbsp;</span></td>
                <td width="35%" align="left">
                	<select name="fuel_vehicle_id" id="fuel_vehicle_id" class="hours_select" tabindex="1" style="width:170px;" >
                    <option value="0">Select Vehicle</option>
                    <?php while($vehicles_fetch = @mysql_fetch_assoc($vehicles_resp1)) { 
				$vehicles_fin = "SELECT * FROM vehicle_finance_info WHERE vfi_vi_id=".$vehicles_fetch[vi_id];
				$vehicles_fin_res = mysql_query($vehicles_fin);	
				$vehicles__fin_fetch = @mysql_fetch_assoc($vehicles_fin_res);
				if($vehicles__fin_fetch [vfi_own_hired]==2)
					$ownship="Hired";
				else
					$ownship="Own";
			?>
                    <option value="<?php echo $vehicles_fetch[vi_reg_no]; ?>" 
                    <?php if($_POST[fuel_vehicle_id] == $vehicles_fetch[vi_reg_no]) echo "selected"; ?>><?php 
					echo $vehicles_fetch[vi_reg_no]." - ".$ownship; ?></option><?php } ?>	
                    </select>
                    <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    			</td>
                </tr>              
                <tr>
                <td width="19%" align="right"><span class="form_text">Month & Year&nbsp;</span></td>
                <td width="35%" align="left">
				<?php
				$month_list = "<select name=trip_month id=trip_month>";
				for($i=1;$i<13;$i++)
				{
					if($_POST[trip_month] == $i)
						$selected_month = "selected";
					else if(date('m') == $i && !isset($_POST[trip_month]))
						$selected_month = "selected";
					else
						$selected_month = "";
					$month_list .= "<option value=".date('m',strtotime('2009-'.$i.'-01'))." 
					".$selected_month.">".date('M',strtotime('2009-'.$i.'-01'))."</option>";
				}
				echo $month_list .= "</select> ";
				$year_list = "<select name=trip_year id=trip_year>";
				for($i=1980;$i<=date('Y');$i++)
				{
					if(isset($_POST[trip_year]) && $_POST[trip_year] == $i)
						$selected = "selected";
					else if(date('Y') == $i && !isset($_POST[trip_year]))
						$selected = "selected";
					else
						$selected = "";
					$year_list .= "<option value=".$i." ".$selected.">".$i."</option>";
				}
				echo $year_list .= "</select>";
				?>
                </td>
              </tr>
              <tr>
                <td height="33" colspan="4" align="center">
                <input type="button" name="fuel_filter_btn" value="Filter" class="sub_btn" tabindex="4" 
				onclick="showPreloader();"/>
                <input type="hidden" name="fuel_filter_btn" value="Filter" />
                <input type="button" name="fuel_cancel_btn" id="fuel_cancel_btn" value="Reset" class="sub_btn" 
				onclick="location.href='index.php?ch=fuelConsumeReport';" tabindex="9" /> 
				<?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
				<input type="button" name="fuel_export_btn" id="fuel_export_btn" value="Export" class="sub_btn" 
				style="font-weight:bold;" onclick="sendCSVData_mil();" /> <?php } ?></td>
              </tr>
            </table>
			</span>
			</td>
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
if($_POST[map_vehicle_id]!='0' && $_POST[fuel_vehicle_id]=='0')
{
?>
<div style="height:350px; width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table width="882" border="0" cellspacing="0" cellpadding="0" id="grid">
      
      <tr class="heading_tr">
        <td width="10%">Date </td>
        <td width="32%" >Distance travelled (km)</td>
        <td width="26%">Mileage (km / ltr)</td>
        <td width="32%" >Fuel Consumed (ltr)</td>
    </tr>
<?php
	$sdate = $_POST[from_date];
	$edate = $_POST[to_date];
	
	$kiloData = "Date,Distance travelled (km),Mileage (km / ltr),Fuel Consumed (ltr)";
	$kiloData .= "@";
 
	$z = GetDays($sdate, $edate);
	$cont = 1;
	for($y=0; $y<count($z); $y++)
	{ 
	
	$file = $dataPath."client_".$_SESSION[clientID]."/".date("d-m-Y", strtotime($z[$y]))."/".$_POST[map_vehicle_id].".txt";
	 //if(file_exists("../../reports_test/data/".date("d-m-Y", strtotime($z[$y]))."/TN22AM3207.csv"))
	 if(chk_folder($file))
	 { 
	    //$op = kmsPerDay($file);
	    $data = split("#",kmsPerDay($file));
		
		$sel_qry = "SELECT vi_mileage FROM vehicle_info WHERE vi_clientId=".$_SESSION[clientID]." AND 
		vi_reg_no='".$_POST[map_vehicle_id]."' ";
		$rs_sel_qry = mysql_query($sel_qry);
		$fetch_sel_qry = @mysql_fetch_assoc($rs_sel_qry);
		
		if($fetch_sel_qry[vi_mileage] == 0 || $fetch_sel_qry[vi_mileage] == "")
		{
			$fuel_cons = "Unable to calculate";
			$mileage = "Not specified";
		}
		else
		{
			$fuel_cons = sprintf("%01.2f",$data[0]/$fetch_sel_qry[vi_mileage]);
			$mileage = $fetch_sel_qry[vi_mileage];
		}
		
		$kiloData .= date("d-m-Y", strtotime($z[$y])).','.$data[0].','.$mileage.','.$fuel_cons;
		$kiloData .= '@';
    
?>	  
      <tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td width="10%"><?php echo date("d-m-Y", strtotime($z[$y])); ?></td>
		<td width="32%"><?php echo $data[0]; ?></td>
		<td width="26%"><?php echo $mileage; ?></td>
		<td width="32%"><?php echo $fuel_cons; ?></td>
      </tr>
 <?php
      
	   $cont++; 

    } ///end of if(file exists)
  } ///end of for
    	 if($cont == 1)
	 {
		 echo '<tr><td colspan="8" align="center">&nbsp; No Records Found.</td></tr>';  
		 echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 
	 }
 ?>	  
    </table>	
<form name="frmKiloData" id="frmKiloData" method="post" action="Report/export.php">
<input type="hidden" name="txtKiloData" id="txtKiloData" value="<?php echo $kiloData;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $_POST[map_vehicle_id]; ?>" />
<input type="hidden" name="txtDate1" id="txtDate1" value="<?php echo $_POST[from_date]; ?>" />
<input type="hidden" name="txtDate2" id="txtDate2" value="<?php echo $_POST[to_date]; ?>" />
</form>	
	
	
</div>	
<script type="text/javascript">

hidePreLoader();
</script>
<?php
  
}///end of post

else if(isset($_POST[fuel_filter_btn]) && $_POST[fuel_filter_btn]!='' && $_POST[fuel_vehicle_id]!='0')
{
aaa
	?>
	 <div style="height:350px; width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
	<?php
	$getTripNames="SELECT DISTINCT(gtn_id),gtn_trip_name FROM gps_trip_info,gps_trip_names WHERE 
	gtri_clientID=".$_SESSION[clientID]." AND gtn_id=gtri_trip_id AND gtri_vehicle_regNo='".$_POST[fuel_vehicle_id]."' AND 
	DATE_FORMAT(gtri_from_date,'%Y-%m')='".$_POST[trip_year]."-".$_POST[trip_month]."' ";
	$rs_trip_details_names = mysql_query($getTripNames);
	?>
	<table width="882" border="0" cellspacing="0" cellpadding="0" id="grid">
	
	<tr class="heading_tr">
	<td width="177">Start Point</td>
	<td width="184">Destination</td>
	<td width="95">Distance&nbsp;(Kms)</td>
	<td width="149">Load</td>
	<td width="137">Mileage(Kmpl)</td>
	<td width="140">Fuel Consumed(lts)</td>
	</tr>
	<?php
	$fuelMileage="Start Point,Destination,Distance (Kms),Load,Mileage(Kmpl),Fuel Consumed(lts)@";
	$cont = 1;
	$total_distance = 0;
	$fuel = 0;
	$total_fuel = 0;
	while($fetch_trip_details_names = @mysql_fetch_assoc($rs_trip_details_names))
	{
	$cont++;
	?>
	<tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
	<td colspan="6"><strong><?php echo $fetch_trip_details_names[gtn_trip_name]; ?></strong></td>
	</tr>
	<?php 
		$fuelMileage.=$fetch_trip_details_names[gtn_trip_name]."@";
		$getTrip="SELECT * FROM gps_trip_info,gps_trip_names WHERE gtri_clientID=".$_SESSION[clientID]." AND 
		gtn_id=gtri_trip_id AND DATE_FORMAT(gtri_from_date,'%Y-%m')='".$_POST[trip_year]."-".$_POST[trip_month]."' 
		AND gtri_vehicle_regNo='".$_POST[fuel_vehicle_id]."' AND gtri_trip_id=".$fetch_trip_details_names[gtn_id]." ";
		$rs_trip_details = mysql_query($getTrip);
		while($fetch_trip_details = @mysql_fetch_assoc($rs_trip_details))
		{
		$cont++;
		?>
		<tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
		<td><?php echo wordwrap($fetch_trip_details[gtri_start_point],20,"<br>",true); ?></td>
		<td><?php echo wordwrap($fetch_trip_details[gtri_end_point],20,"<br>",true); ?></td>
		<td><?php echo $fetch_trip_details[gtri_distance]; $total_distance+=$fetch_trip_details[gtri_distance]; ?></td>
		<td><?php if($fetch_trip_details[gtri_withload] == 0) echo $load = "No Load"; 
			else { echo substr($fetch_trip_details[gtri_desc],0,20); $load=$fetch_trip_details[gtri_desc]; }
			if(strlen($fetch_trip_details[gtri_desc]) > 20) echo "..."; ?>
		</td>
		<td><?php 
			if($fetch_trip_details[gtri_withload] == 0) echo $mil = $fetch_trip_details[gtn_mileage_wl]; 
			else echo $mil = $fetch_trip_details[gtn_mileage_wol]; ?>
		<td><?php if($fetch_trip_details[gtri_withload] == 0) 
			{
				if($fetch_trip_details[gtn_mileage_wl]==0)
					echo $fuel = "Unable to Calculate";
				else
					echo $fuel = sprintf("%01.2f",$fetch_trip_details[gtri_distance]/$fetch_trip_details[gtn_mileage_wl]);
			}
			else 
			{
				if($fetch_trip_details[gtn_mileage_wol]==0)
					echo $fuel = "Unable to Calculate";
				else
					echo $fuel = sprintf("%01.2f",$fetch_trip_details[gtri_distance]/$fetch_trip_details[gtn_mileage_wol]);
			}
			$total_fuel+=$fuel ;?></td>
		</tr>
		<?php
		$fuelMileage.=$fetch_trip_details[gtri_start_point].','.$fetch_trip_details[gtri_end_point].','.$fetch_trip_details[gtri_distance].','.$load.','.$mil.','.$fuel.','.'@';
		}
	}
	$cont++;
	if($cont != 2)
	{
	?>
	<tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
	<td colspan="2"><strong>Total</strong></td>
	<td><?php echo $total_distance; ?></td>
	<td colspan="2">&nbsp;</td>
	<td><?php echo $total_fuel; ?></td>
	</tr>
	<?php
	$fuelMileage.='Total, ,'.$total_distance.', , ,'.$total_fuel.'@';
	}
	if($cont == 2)
	{
	echo '<tr><td colspan="8" align="center">&nbsp; No Records Found.</td></tr>';  
	echo '<script language=javascript>document.getElementById("fuel_export_btn").style.display="none";</script>'; 
	}
	?>
	</table>	
	<form name="frmFuelMilData" id="frmFuelMilData" method="post" action="Report/export.php">
	<input type="hidden" name="txtFuelMileage" id="txtFuelMileage" value="<?php echo $fuelMileage;?>" />
	<input type="hidden" name="fuel_vehicle_id" id="fuel_vehicle_id" value="<?php echo $_POST[fuel_vehicle_id]; ?>" />
	<input type="hidden" name="trip_month" id="trip_month" value="<?php echo $_POST[trip_month]; ?>" />
	<input type="hidden" name="trip_year" id="trip_year" value="<?php echo $_POST[trip_year]; ?>" />
	</form>
	</div>	
	<script type="text/javascript">
		hidePreLoader();
	</script>
	<?php
}
?>