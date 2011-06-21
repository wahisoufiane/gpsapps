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

function GetDays($sStartDate, $sEndDate){
  $sStartDate = date("Y-m-d", strtotime($sStartDate));
  $sEndDate = date("Y-m-d", strtotime($sEndDate));

  $aDays[] = $sStartDate;
  $sCurrentDate = $sStartDate;

  while($sCurrentDate < $sEndDate){
    $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
    $aDays[] = $sCurrentDate;
  }
  return $aDays;
}



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
		
		}
		}
		}
		array_push($timeArr,$geoTime);
	}
	//echo $data3[11];
	$res=simpleGeocode($pos1,$pos2);
	//print_r($res);
	$res=str_replace('"',"",$res);
	//echo $res."<br>";
	//echo round($totalDistance,2);
	$finalData = round($totalDistance)."#".$geodate."#".$res;
	fclose($file1);
	return $finalData;
	}
}


$vehicles_query = "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]."  AND ORDER BY vi_id";
$vehicles_resp = mysql_query($vehicles_query);


?>
<script type="text/javascript" language="javascript">
function validateMapReport()
{
		if(document.getElementById('map_vehicle_id').value== 0)
		{ alert("Select Vehicle");  document.getElementById('map_vehicle_id').focus();  return 0;  }
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

</script>


<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();"> 
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
            <td width="81%" height="25" bgcolor="#116999" style="padding-left:10px;"><span class="style1">Monthly Distance Report</span></td>
            <td width="15%" align="right" bgcolor="#116999" class="style2" style="padding-right:10px;"><a style="color:#BFD449;" href="index.php?ch=reportPanel"><strong>Back to Report</strong></a></td>
          </tr>
          <tr>
            <td colspan="3" align="center" bgcolor="#FFFFFF">
			
			<?php 
				$vehicles_query1 = "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]." 
				AND vi_userId=".$_SESSION[userID]." ORDER BY vi_id";
				$vehicles_resp1 = mysql_query($vehicles_query1);
			?>
			<table width="89%" border="0" cellspacing="5" cellpadding="5">

              <tr>
                <td width="19%" align="right"><span class="form_text">Select Vechicle&nbsp;</span></td>
                <td width="35%" align="left">
                	<select name="map_vehicle_id" id="map_vehicle_id" class="hours_select" tabindex="1" style="width:170px;" >
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
                    <?php if($_POST[map_vehicle_id] == $vehicles_fetch[vi_reg_no]) echo "selected"; ?>><?php 
					echo $vehicles_fetch[vi_reg_no]." - ".$ownship; ?></option><?php } ?>	
                    </select>
                    <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    			</td>
                </tr>              
                <tr>
                <td width="19%" align="right"><span class="form_text">Month & Year&nbsp;</span></td>
                <td width="35%" align="left">
				<?php
				$month_list = "<select name=trip_month id=trip_month style='width:125px'>";
				for($i=1;$i<13;$i++)
				{
					if($_POST[trip_month] == $i)
						$selected_month = "selected";
					else if(date('m') == $i && !isset($_POST[trip_month]))
						$selected_month = "selected";
					else
						$selected_month = "";
					$month_list .= "<option value=".date('m',strtotime('2009-'.$i.'-01'))." 
					".$selected_month.">".date('F',strtotime('2009-'.$i.'-01'))."</option>";
				}
				echo $month_list .= "</select> ";
				$year_list = "<select name=trip_year id=trip_year>";
				for($i=2007;$i<=date('Y');$i++)
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
				onclick="location.href='index.php?ch=monthlyReport';" tabindex="9" /> 
				<?php if(isset($_POST[fuel_filter_btn]) && $_POST[fuel_filter_btn]!='')  { ?>
				<input type="button" name="fuel_export_btn" id="fuel_export_btn" value="Export" class="sub_btn" 
				style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
              </tr>
            </table>
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
if(isset($_POST[fuel_filter_btn]) && $_POST[fuel_filter_btn]!='')
{ 
//print_r($_POST);
	
?>
<div style="height:350px; width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table width="882" border="0" cellspacing="0" cellpadding="0" id="grid">
      
      <tr class="heading_tr">
        <td width="10%">Date </td>
        <td width="15%" >Kilometer (kmph)</td>
        <td width="15%">Last Data Rec </td>
        <td width="60%" >Last Location</td>
       </tr>
<?php
	$daysInMonth =  date('t',strtotime($_POST[trip_month]));
	
	$sdate = date("d-m-Y",mktime(0,0,0,$_POST[trip_month],1,$_POST[trip_year]));
	$edate = date("d-m-Y",mktime(0,0,0,$_POST[trip_month],$daysInMonth,$_POST[trip_year]));
	
	$kiloData = "Date,Kilometer,Last Data Rec,Last Location";
	$kiloData .= "@";
 
	$z = GetDays($sdate, $edate);
	$cont = 1;
	for($y=0; $y<count($z)-1; $y++)
	{ 
	
	$file = $dataPath."client_".$_SESSION[clientID]."/".date("d-m-Y", strtotime($z[$y]))."/".$_POST[map_vehicle_id].".txt";

	//if(file_exists("../../reports_test/data/".date("d-m-Y", strtotime($z[$y]))."/TN22AM3207.csv"))
	 if(chk_folder($file))
	 { 
	    //$op = kmsPerDay($file);
	 	//echo $file;
		//echo "<br>";

	    $data = split("#",kmsPerDay($file));

		 if(count($data)>0)
		 {
			$kiloData .= date("d-M-Y", strtotime($z[$y])).','.$data[0].','.$data[1].','.$data[2];
			$kiloData .= '@';
		
	?>	  
		  <tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
			<td width="10%"><?php echo date("d-M-Y", strtotime($z[$y])); ?></td>
			<td width="15%"><?php echo $data[0]; ?></td>
			<td width="15%"><?php echo $data[1]; ?></td>
			<td width="60%"><?php echo $data[2]; ?></td>
		  </tr>
	 <?php
		  
		   $cont++; 
	
		} ///end of if(file exists)
	}
	//exit;
  } ///end of for
  	 if($cont == 1)
	 {
		 echo '<tr><td colspan="8" align="center">&nbsp; No Records Found.</td></tr>';  
		 echo '<script language=javascript>document.getElementById("map_export_btn").style.display="none";</script>'; 
	 }

 ?>	  
    </table>	
	
<form name="frmKiloData" id="frmKiloData" method="post" action="Report/export.php">
<input type="hidden" name="txtMonthKiloMtr" id="txtMonthKiloMtr" value="<?php echo $kiloData;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="<?php echo $_POST[map_vehicle_id]; ?>" />
<input type="hidden" name="txtMonth" id="txtMonth" value="<?php echo date('M',strtotime($_POST[trip_month])); ?>" />
<input type="hidden" name="txtYear" id="txtYear" value="<?php echo $_POST[trip_year]; ?>" />
</form>	
	
	
</div>	
<script type="text/javascript">

hidePreLoader();
</script>
<?php
  
}///end of post

?>
