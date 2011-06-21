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
  // Firstly, format the provided dates.
  // This function works best with YYYY-MM-DD
  // but other date formats will work thanks
  // to strtotime().
  $sStartDate = gmdate("Y-m-d", strtotime($sStartDate));
  $sEndDate = gmdate("Y-m-d", strtotime($sEndDate));

  // Start the variable off with the start date
  $aDays[] = $sStartDate;

  // Set a 'temp' variable, sCurrentDate, with
  // the start date - before beginning the loop
  $sCurrentDate = $sStartDate;

  // While the current date is less than the end date
  while($sCurrentDate < $sEndDate){
    // Add a day to the current date
    $sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));

    // Add this new day to the aDays array
    $aDays[] = $sCurrentDate;
  }

  // Once the loop has finished, return the
  // array of days.
  return $aDays;
}
function array_push_assoc($array, $key, $value){
 $array[$key] = $value;
 return $array;
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
$ids=$dest;
$ids=explode(",",$ids);
//print_r($ids);
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
			
				for($k=0;$k<count($ids)-1;$k++)
				{
					//echo $ids[$k];
				$sel_pts = "SELECT * FROM gps_geopoints_info WHERE gpi_clientID=".$_SESSION[clientID]." AND gpi_id=".$ids[$k];	
				$rs_sel_pts = mysql_query($sel_pts);
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
							//echo "pos ".in_array($fetch_sel_pts[gpi_id],$ids);
							if(in_array($fetch_sel_pts[gpi_id],$ids))
							{
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
							} 
							//print_r($destArr);
						}
						
					}
					else
					{
						$tmpId=0;
					}
					
				}
				//echo "<br>";
				}
						
				}
			}
			
		}
		fclose($file1);
		}
	}
	$result = $destArr;
	//print_r($destArr);
	
	//echo $timediff;
	return($result);
}


$vehicles_query =  "SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID]." ORDER BY vi_id";
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
  if(document.getElementById('txtDestId').value=='')
  {
   alert("Select atleast one Destination");
   document.getElementById('selDestination').focus();
   return 0;  
  }
  if(document.getElementById('from_date').value=='')
  {
   alert("Select Date");
   document.getElementById('from_date').focus();
   return 0;  
  }
	return 1;
	
}
function seeList(form) { 
    var result = ""; 
    for (var i = 0; i < document.getElementById('selDestination').length; i++) { 
        if (document.getElementById('selDestination').options[i].selected) { 
            result += document.getElementById('selDestination').options[i].value+","; 
			//document.getElementById('txtDestId').value +=document.getElementById('selDestination').options[i].value+",";
        } 
    } 
    document.getElementById('txtDestId').value=result; 
} 
function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
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
            <td width="81%" height="25" bgcolor="#116999" style="padding-left:10px;"><span class="style1">Over All Trip Report</span></td>
           <td width="15%" align="right" bgcolor="#116999" class="style2" style="padding-right:10px;"><a style="color:#BFD449;" href="index.php?ch=reportPanel"><strong>Back to Report</strong></a></td>
          </tr>
          <tr>
            <td colspan="3" align="center" bgcolor="#FFFFFF"><table width="59%" border="0" cellspacing="5" cellpadding="5">                           
              <tr>
                <td width="6%" align="right" valign="top"><span class="form_text">Destination&nbsp;</span></td>
                <td width="41%" align="left">
                <?php 
				$getPts="select * from gps_geopoints_info where gpi_clientID=".$_SESSION[clientID]." order by gpi_stopName ASC";
				$resPts=mysql_query($getPts);
				?>
               <select name="selDestination" id="selDestination" multiple="multiple" tabindex="3" onblur='seeList();' style="width:175px; height:100px;">
                <?php
				$i=0;
				while($fetPts=@mysql_fetch_assoc($resPts))
				{
					if($i==1)
						$select='selected="selected"';
					else
						$select='';
					?>
                    <option <?php //echo $select;?> value="<?php echo $fetPts[gpi_id];?>"><?php echo $fetPts[gpi_stopName];?></option>
                    <?php
					$i++;
				}
				?>
                </select>
                <input type="hidden" name="txtDestId" id="txtDestId" /> <br />
                <span class="form_text">(Use Ctrl+ for multiple)</span>
               </td>
              </tr>
              <tr>
                <td width="6%" align="right"><span class="form_text">Date&nbsp;</span></td>
                <td width="41%" align="left">
                <input type="text" name="from_date" id="from_date" tabindex="2" readonly="true" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/><input type="button" name="button" value=".." class="sub_btn" onclick="return showCalendar('from_date','%Y-%m-%d');" /></td>
              </tr>
              
              <tr>
                <td height="33" colspan="2" align="center">
                <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="8"  onclick="showPreloader();"/>
                <input type="hidden" name="map_filter_btn" value="Filter" />
                <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=overallTripReport_dist';" tabindex="9" /> 
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
<div style="height:450px; width:900px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">
<?php
$timediff1=array();
$totalDistance=0;
$overAllTripData = "VehicleNo,Date";
$overAllTripData .= "@";
$ct=0;
$sdate = $_POST[from_date];		

$selVehicle="SELECT * FROM vehicle_info where vi_clientId=".$_SESSION[clientID];
$rs_getVehicleDetails = mysql_query($selVehicle);
while($fetch_getDetails = @mysql_fetch_assoc($rs_getVehicleDetails))
{
	//echo $fetch_getDetails[vi_reg_no]."<br>";	
	$timediff1=getOverAllTripReport($sdate,$fetch_getDetails[vi_reg_no],$_POST[txtDestId]);		//$fetch_getDetails[vi_reg_no]
	if(count($timediff1)!=0)
	{
	$overAllTripData .= $fetch_getDetails[vi_reg_no].','.date("d-m-Y",strtotime($_POST[from_date]));
	$overAllTripData .= "@";

	

		

?>
<div id="" style="font:normal 12px/30px Arial, Helvetica, sans-serif;">
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="grid">
   <tr>
        <td width="12%" class="sub_btn">Vehicle No: <?php echo $fetch_getDetails[vi_reg_no];?></td>
        <td width="12%" class="sub_btn">Date: <?php echo date("d-m-Y",strtotime($_POST[from_date]));?></td>
     </tr>
</table>
</div>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="grid">
    <tr class="heading_tr">
        <td width="12%">Destination</td>
        <td width="13%">Last Reached Time</td>
        <td width="12%">No. of Trip</td>
    </tr>
    <?php
	
	$overAllTripData .= "#,Destination,Reached Timings,No. of Trips";
	$overAllTripData .= "@";
	$r=0;
	foreach ($timediff1 as $key => $value) {
		//echo $key."	".$_POST[txtDestId];
	   //echo "Key: $key; Value: $value<br />\n";
		$ct++;
		$destPt = explode(",",$timediff1[$key]);
		//print_r($destPt);
		for($u=0;$u<count($destPt);$u++)
		{
			$destVal = explode("#",$destPt[$u]);
		//print_r($destVal);
		
		//for($n=0;$n<count($destVal);$n++)
		//{
			//echo $destVal[0]." ".$destVal[1];
			$endTime .= $destVal[1].'; ';
		//}
		//if($r % 2 == 0) { echo $cls="class=odd_row";} else { echo $cls="class=even_row"; }
		}
		$strtHtml ="<tr class='odd_row'>";
		$strtHtml .='<td valign=top>'.$destVal[0].'</td><td>'.$endTime.'</td><td>'.count($destPt).'</td>';
		$strtHtml .='</tr>';
		echo $strtHtml;
		$overAllTripData .= $ct.','.$destVal[0].','.$endTime.','.count($destPt);
		$overAllTripData .= "@";
		$endTime="";
	   $r++;
	 
	}
	echo '<tr><td colspan="3">&nbsp;</td></tr>';
	$overAllTripData .= "@";
?>	
</table>
<?php
}
	/*else
	{
		$overAllTripData .= "No Records Found";
		$overAllTripData .= "@";
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="grid">
		<tr><td colspan="3" align="center">No Records Found</td></tr>
        </table>
    <?php 
	}*/
//exit;
}
?>
<form name="frmTripData" id="frmTripData" method="post" action="Report/export.php">
<input type="hidden" name="overAllTripData" id="overAllTripData" value="<?php echo $overAllTripData;?>" />
<input type="hidden" name="txtVehino" id="txtVehino" value="OverAll" />
<input type="hidden" name="txtDate" id="txtDate" value="<?php echo $_POST[from_date]; ?>" />
</form>

</div>	
<script type="text/javascript">

hidePreLoader();
</script>	
	
<?php
  
}///end of post

?> 

