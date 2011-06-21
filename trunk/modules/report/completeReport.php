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
function getOverAllTripReport($sdate,$vehId,$dest)
{
$cnt = 1;
$tmp=-1;
$t1=0.0;
$t2=0.0;
$timeArr= array();
$timediff=array();
$strTime='';
$endTime='';
$tmpId=0;
$srcArr= array();
$destArr=array();

$stCunt=0;
$stChk=0;
$geocheck=0;
//print_r($dest);
$file = $GLOBALS[dataPath]."client_".$_SESSION[clientID]."/".date("d-m-Y", strtotime($sdate))."/".$vehId.".txt";   
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
		$data2=explode("$",$data1[$j1]);
		if(count($data2)>1)
		{
		$data3=explode(",",$data2[1]);
		//echo date("d-m-Y", strtotime($sdate))."==".date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
		$vehi=$data3[0];
		$geodate=date("d-m-Y",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
		$geoTime=date("H:i:s A",@mktime(($data3[4]+5),($data3[5]+30),$data3[6],$data3[2],$data3[1],$data3[3]));
		$pos1=convertLat(calLat($data3[7]));
		$pos2=convertLong(calLong($data3[8]));	
		$vehi=$data3[0];
		/*$getLL=drawCircle("17.98264333","79.601965","0.16");
		print_r($getLL);
		echo $resLL=getGeofenceStatus($getLL,"17.9809383333","79.6004683333");
		exit;*/
		if($pos1>0 && $pos2>0)
		{
		
			$sel_pts = "SELECT * FROM gps_geopoints_info WHERE gpi_clientID=".$_SESSION[clientID]." AND gpi_id=".$dest;	
			$rs_sel_pts = mysql_query($sel_pts);
			if(@mysql_affected_rows()>0)
			{
			while($fetch_sel_pts = @mysql_fetch_assoc($rs_sel_pts))
			{
				
				$gcdLatLng=drawCircle($fetch_sel_pts[gpi_latVal],$fetch_sel_pts[gpi_longVal],$fetch_sel_pts[gpi_miles]);
				//print_r($fetch_sel_pts);
				$geocheck=getGeofenceStatus($gcdLatLng,$pos1,$pos2);
				if($geocheck == 1)
				{
					//print_r($gcdLatLng);
					/*$getLL=drawCircle("17.97905965","79.59972382","0.10");
					print_r($getLL);
					$resLL=getGeofenceStatus($getLL,$pos1,$pos2);*/
					//print_r($getLL);
					$result_pt = $fetch_sel_pts[gpi_stopName]."#".$geoTime;
					//echo $k." ".$j1." ".$result_pt." ".$pos1."&&".$pos2."";
					if($tmpId!=$fetch_sel_pts[gpi_id])
					{
						
						$tmpId=$fetch_sel_pts[gpi_id];
						//echo $fetch_sel_pts[gpi_id]." ".$dest;
						
						//echo "pos ".in_array($fetch_sel_pts[gpi_id],$dest);
						//if(in_array($fetch_sel_pts[gpi_id],$dest))
						//{
							if($destArr[$tmpId]=="")
							{
								//echo "ss".count($destArr[$tmpId])." ".$tmpId." ".$result_pt."<br>";
								//$destArr = array_push_assoc($destArr, $tmpId, $result_pt);
								$destArr[$tmpId]=$result_pt;
								//print_r($destArr);
							}
							else
							{
								//echo $result_pt."<br>";
								foreach ($destArr as $key => $value) 
								{
								  // echo "Key: $key; Value: $value<br />\n";
								   if($key == $tmpId)
								   {
										//echo $key ."==". $tmpId;
										$tmpVal=$value;
										//unset($destArr[$key]);
										$destArr[$key]=$value.','.$result_pt;
										//print_r($destArr);
										//echo "<br>";
										//echo "<br>";
								   }
								   else
								   {
									   //	$destArr[$tmpId]=$result_pt;
										//$destArr = array_push_assoc($destArr, $tmpId, $result_pt);
										//print_r($destArr);
										//echo "<br>";
								   }
								}	// foreach end
							}	// else end
						//} 
						//print_r($destArr);
					}
					
				}
				else
				{
					$tmpId=0;
				}
				
			}
		}
			//echo "<br>";
					
			}
		}
		
	}
	fclose($file1);
	}
}
$result = $destArr;
//print_r($destArr);
//exit;

//echo $timediff;
return($result);
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

			
			
			$mph = $data3[9];
			$direction = $data3[10];
			$date = $date;
			$locationMethod = '327681';
			//echo getDateFromJavaDate($date);
			$phoneNumber = $data3[0];
			$sessionID = $_GET["sessionID"] ;
			$extraInfo = $data3[11];
		}
		}
		}
		array_push($timeArr,$geoTime);
	}
	//echo $data3[11];
	//$res=simpleGeocode($pos1,$pos2);
	//print_r($res);
	//$res=str_replace('"',"",$res);
	//echo $res."<br>";
	//echo round($totalDistance,2);
	$finalData = round($totalDistance)."#".$geodate;
	fclose($file1);
	return $finalData;
	}
}

?>
<script type="text/javascript" language="javascript">

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
  if(document.getElementById('from_date').value=='')
  { alert("Select Date"); document.getElementById('from_date').focus();  return 0;  }
  
  if(document.getElementById('selPoint').value==0)
  { alert("Select Point"); document.getElementById('selPoint').focus();  return 0;  }
  
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
            <td width="81%" height="25" bgcolor="#116999" style="padding-left:10px;"><span class="style1">Complete Trip Report</span></td>
            <td width="15%" align="right" bgcolor="#116999" class="style2" style="padding-right:10px;"><a style="color:#BFD449;" href="index.php?ch=reportPanel"><strong>Back to Report</strong></a></td>
          </tr>
          <tr>
            <td colspan="3" align="center" bgcolor="#FFFFFF"><table width="89%" border="0" cellspacing="5" cellpadding="5">

              <tr>
                <td width="19%" align="right"><span class="form_text">Point Name&nbsp;</span></td>
                <td width="35%" align="left">
                <?php 
				$getPts="select * from gps_geopoints_info where gpi_clientID=".$_SESSION[clientID]." order by gpi_stopName ASC";
				$resPts=mysql_query($getPts);
				?>
               <select name="selPoint" id="selPoint" tabindex="3" style="width:170px;" >
                <option value="0">Select Point</option>
                <?php
				$i=0;
				while($fetPts=@mysql_fetch_assoc($resPts))
				{
					if($fetPts[gpi_id]==$_POST[selPoint])
						$select='selected="selected"';
					else
						$select='';
					?>
                    <option <?php echo $select;?> value="<?php echo $fetPts[gpi_id];?>"><?php echo $fetPts[gpi_stopName];?></option>
                    <?php
					$i++;
				}
				?>
                </select>
               </td>
                 <td align="right"><span class="form_text">Date&nbsp;</span></td>
                <td align="left">
                <input type="text" name="from_date" id="from_date" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/><input type="button" name="button" tabindex="2" value=".." class="sub_btn" onclick="return showCalendar('from_date','%Y-%m-%d');" /></td>
                </tr>              
                <tr>
                
              </tr>
              <tr>
                <td height="33" colspan="4" align="center">
                <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="4"  onclick="showPreloader();"/>
                <input type="hidden" name="map_filter_btn" value="Filter" />
                <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=completeReport';" tabindex="9" /> 
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
	//print_r($_POST);
?>
<div style="height:350px; width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<table width="882" border="0" cellspacing="0" cellpadding="0" id="grid">
      
      <tr class="heading_tr">
        <td width="2%">#</td>
        <td width="7%">Vehicle Reg.No </td>
        <td width="9%">Vehicle Type</td>
        <td width="10%">Owner Name </td>
        <td width="7%">Contact No</td>
        <td width="7%">Dist (kmph)</td>
        <td width="7%">Fuel</td>
        <td width="20%">Trip Details</td>        
       </tr>
<?php
	$sdate = $_POST[from_date];
	$comeData = "Date,".date("d-m-Y", strtotime($sdate));
	$comeData .= "@";
	
	$comeData .= '#,Registration No,Type,Owner Name,Contact No,Total KM,Total Fuel,Trips';
	$comeData .= "@";
	
	$cont = 1;

	$selVehicle="SELECT vi_id,vi_reg_no,vi_mileage,vi_ownerName FROM vehicle_info where vi_clientId=".$_SESSION[clientID];
	$rs_getVehicleDetails = mysql_query($selVehicle);
	
	while($fetch_getDetails = @mysql_fetch_assoc($rs_getVehicleDetails))
	{
		//print_r($fetch_getDetails);
		$rs_fin_info = UserSF::getVehicle_financeDetails($fetch_getDetails[vi_id]);
		$fetch_fin_info = @mysql_fetch_assoc($rs_fin_info);		
		//print_r($fetch_fin_info);
		if($fetch_fin_info[vfi_own_hired]==1 && $fetch_fin_info[vfi_finance]==1)
		{ 
			$vehiStatus = "Finance";
			$vehiOwner = $fetch_getDetails[vi_ownerName];
			$vehiContact = "Not Set";
		}
		else if($fetch_fin_info[vfi_own_hired]==1 && $fetch_fin_info[vfi_finance]==0) 
		{
			$vehiStatus = "Own";
			$vehiOwner = $fetch_getDetails[vi_ownerName];
			$vehiContact = "Not Set"; 
		}
		else if($fetch_fin_info[vfi_own_hired]==2) 
		{
			$vehiStatus = "Hired";
			$vehiOwner = $fetch_getDetails[vi_ownerName];
			$vehiContact = $fetch_fin_info[vfi_hired_contact_no];;
			 
		}
		else 
		{
			$vehiStatus = "New";
			$vehiOwner = $fetch_getDetails[vi_ownerName];
			$vehiContact = "Not Set";
		}
		
 
	
	$file = $dataPath."client_".$_SESSION[clientID]."/".date("d-m-Y", strtotime($sdate))."/".$fetch_getDetails[vi_reg_no].".txt";

	 //if(file_exists("../../reports_test/data/".date("d-m-Y", strtotime($z[$y]))."/TN22AM3207.csv"))
	 if(chk_folder($file))
	 { 
	    //$op = kmsPerDay($file);
	    $data = split("#",kmsPerDay($file));
		$timediff1=getOverAllTripReport($sdate,$fetch_getDetails[vi_reg_no],$_POST[selPoint]);
	 }
	 if(count($timediff1)>0)
	 {
	 foreach ($timediff1 as $key => $value) {
		$destPt = explode(",",$timediff1[$key]);
		for($u=0;$u<count($destPt);$u++)
		{
			$destVal = explode("#",$destPt[$u]);
			$endTime .= $destVal[1].',';
		}
		$strtHtml =$destVal[0].' - '.count($destPt).' ('.$endTime.')';
		$endTime="";
	   $r++;
	 }
	 }else $strtHtml="";
	 //print_r($data);
	 //echo "<br>";
     //print_r($timediff1);
	 //echo "<br>";
	 //echo count($data)." ".count($timediff1)."<br>";
	 if(count($data)>0)
	 {
	 	if(round($fetch_getDetails[vi_mileage])>0 )
		{
		 	$feulMtr=$data[0]/round($fetch_getDetails[vi_mileage]);
		}
		else 
		{
			 $feulMtr = "Not Set";
		}
		
		$comeData .= $cont.','.$fetch_getDetails[vi_reg_no].','.$vehiStatus.','.$vehiOwner.','.$vehiContact.','.$data[0].','.$feulMtr.','.$strtHtml;
		$comeData .= '@';
       
?>	  
      <tr <?php if($cont % 2 == 0) { echo 'class="odd_row"';} else { echo 'class="even_row"'; } ?> >
	    <td valign="top"><?php echo $cont;?></td>
		<td valign="top"><?php echo $fetch_getDetails[vi_reg_no]; ?></td>
		<td valign="top"><?php echo $vehiStatus; ?></td>
		<td valign="top"><?php echo $vehiOwner; ?></td>
        <td valign="top"><?php echo $vehiContact; ?></td>
		<td valign="top"><?php echo $data[0]; ?></td>
        <td valign="top"><?php echo round($feulMtr); ?></td>
		<td><?php echo $strtHtml; ?></td>
      </tr>
 <?php
      
	   $cont++; 
	   // exit;

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
<input type="hidden" name="txtComplData" id="txtComplData" value="<?php echo $comeData;?>" />
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

?>
