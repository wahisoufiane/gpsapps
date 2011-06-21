<?php
//print_r($_POST);
if(isset($_POST[txtAlertId]) && $_POST[txtAlertId] !='')
{
	$sql = "SELECT * FROM tb_device_alert_info WHERE tdai_id =".$_POST[txtAlertId];
	$rows = $db->query($sql);
	if($db->affected_rows > 0)
	{
		$deviceRecord = $db->fetch_array($rows);
		if($deviceRecord[tdai_alertBy] == "OverSpeed")
		{
			$speedLimit = explode("#",$deviceRecord[tdai_alertSrc]);		
			//print_r($speedLimit);
		}
	}
}

function dateDiff($endDate, $beginDate)
{
	 //explode the date by "-" and storing to array
   $date_parts1=explode("-", $beginDate);
   $date_parts2=explode("-", $endDate);
   //gregoriantojd() Converts a Gregorian date to Julian Day Count
   $start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
   $end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);
   return $end_date - $start_date;

}
if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
{
	$devices_query =  "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_clientId =".$_SESSION[clientID]." AND di_status = 1 ORDER BY di_deviceName,di_deviceId ASC";
}
else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
{
	$devices_query = "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
}
$devices_resp = mysql_query($devices_query);	
?>
<script type="text/javascript" language="javascript">
function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{
		document.getElementById('popup_div').innerHTML = '<div id="loading_txt" >Loading...</div>';
		document.frm_map_filter.submit();
	}
}
function changeValue()
{
	var tmpId = '';
	for (i=0; i<document.getElementById('selContId').length;i++)
	{
		if (document.getElementById('selContId').options[i].selected)
		{
			if(i==0)
				tmpId = document.getElementById('selContId').options[i].value+",";
			else
				tmpId +=document.getElementById('selContId').options[i].value+",";
		}
	}
	document.getElementById('txtContIds').value = tmpId;
}
function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';
}
function funEditUser(did,cid,act)
{
	//alert(uid)
	document.frmSubmit.txtAlertId.value = did;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
function days_between(date1, date2) {

    var ONE_DAY = 1000 * 60 * 60 * 24

    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

	var difference_ms = date1_ms - date2_ms
	    
    return Math.round(difference_ms/ONE_DAY)

}


function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
$(function() {
	$("#nav a").click(function()
	{
		//$("#current").removeAttr("id");
		//$(this).attr("id", "current");
		$("#content").load($(this).attr("id") + '.php');
		// Prevent click from jumping to the top of the page
		return false;
	});
});
var ajax1=new sack();
function showAlertBy(val)
{
	if(val != "Date" && document.frmAddAlert.map_device_id.value !="")
	{
		qry = "select count(tdai_id) from tb_device_alert_info where tdai_clientId = <?php echo $_SESSION[clientID];?> and tdai_status = 0 and tdai_deviceId = "+document.frmAddAlert.map_device_id.value+" and tdai_alertBy = '"+val+"'";
		ajax1.requestFile = 'ajax_server.php?getResQry='+qry;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function()
		{
			if(ajax1.response != 0)
			{
				alert("Choose different Alert Method");
				document.frmAddAlert.rdAlertBy.selectedIndex = 0;
			}
			else
			{
				 showHideMethod(val);
			}
		};
		ajax1.runAJAX();
	}
	else
	{
		 showHideMethod(val);
	}
	
}
function showHideMethod(val)
{
			if(val == "Date")
		{
			document.getElementById('trAlertLabel').innerHTML='Choose Date';
			document.getElementById('spanAlertDate').style.display='block';
			document.getElementById('spanOdoMRead').style.display='none';
			document.getElementById('spanOverSpeed').style.display='none';
			document.getElementById('spanOverStay').style.display='none';
			document.getElementById('spanLowBattery').style.display='none';
			document.getElementById('txtAlertDate').value = "";
		}
		else if(val == "Meter")
		{
			document.getElementById('trAlertLabel').innerHTML='Enter Odometer';
			document.getElementById('spanAlertDate').style.display='none';
			document.getElementById('spanOdoMRead').style.display='block';
			document.getElementById('spanOverSpeed').style.display='none';
			document.getElementById('spanOverStay').style.display='none';
			document.getElementById('spanLowBattery').style.display='none';
			document.getElementById('txtOdoMRead').value = "";
		}
		else if(val == "OverSpeed")
		{
			document.getElementById('trAlertLabel').innerHTML='Speed Limit';
			document.getElementById('spanAlertDate').style.display='none';
			document.getElementById('spanOdoMRead').style.display='none';
			document.getElementById('spanOverSpeed').style.display='block';
			document.getElementById('spanOverStay').style.display='none';
			document.getElementById('spanLowBattery').style.display='none';
			//document.getElementById('spanOverSpeed').value = "";
		}
		else if(val == "OverStay")
		{
			document.getElementById('trAlertLabel').innerHTML='Duration Limit';
			document.getElementById('spanAlertDate').style.display='none';
			document.getElementById('spanOdoMRead').style.display='none';
			document.getElementById('spanOverSpeed').style.display='none';
			document.getElementById('spanOverStay').style.display='block';
			document.getElementById('spanLowBattery').style.display='none';
			document.getElementById('txtOverStay').value = "";
		}
		else if(val == "LowBattery")
		{
			document.getElementById('trAlertLabel').innerHTML='Low Battery Limit';
			document.getElementById('spanAlertDate').style.display='none';
			document.getElementById('spanOdoMRead').style.display='none';
			document.getElementById('spanOverSpeed').style.display='none';
			document.getElementById('spanOverStay').style.display='none';
			document.getElementById('spanLowBattery').style.display='block';
			//document.getElementById('txtLowBattery').value = "";
		}
}

var minVal;
var m1 = '<?php echo $speedLimit[0];?>';
for(i=10; i <= 300; i=i+10)
{
	if(m1!='' && i==m1)
	{
		minVal +='<option value='+i+' selected="selected">'+i+'</option>';
	}
	else
	{
		if(i==40)
			maxVal +='<option value='+i+' selected="selected">'+i+'</option>';
		else
			minVal +='<option value='+i+'>'+i+'</option>';
	}
}

var maxVal;
var m2 = '<?php echo $speedLimit[1];?>';
for(j=10; j <= 300; j=j+10)
{
	if(m2!='' && j == m2)
	{
		maxVal +='<option value='+j+' selected="selected">'+j+'</option>';
	}
	else
	{
		if(j==80)
			maxVal +='<option value='+j+' selected="selected">'+j+'</option>';
		else
			maxVal +='<option value='+j+'>'+j+'</option>';
	}
}

function changeSts(uid)
{
	t = confirm("Are your sure to delete this Alert?");
	if(t)
	{
		qry = "UPDATE tb_device_alert_info SET tdai_active = 0 WHERE tdai_id = "+uid;
		ajax1.requestFile = 'ajax_server.php?ajaxQry='+qry;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){resultUserStatus()};
		ajax1.runAJAX();
	}
}
function resultUserStatus()
{
	//alert(ajax1.response)
  if(ajax1.response)
  {
	 alert("Status Updated Successfully.");
	 window.location.href = "?ch=myalerts";
  }
  else
  {
	  alert("Status Not Updated.");
  }
	
}
function submitAlertType(val)
{
	ajax1.requestFile = 'ajax_server.php?contType='+val;			
	//alert(ajax1.requestFile);
	ajax1.onCompletion = function(){getSource()};
	document.getElementById('selContId').innerHTML = '</option value=0>Loading...</option>';
	ajax1.runAJAX();
}
function getSource()
{
	document.getElementById('txtContIds').value="";
	document.getElementById('selContId').innerHTML = ajax1.response;
}
 jQuery(document).ready( function() {
	// binds form submission and fields to the validation engine
	jQuery("#frmAddAlert").validationEngine();
	$("#txtAlertDate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showOn: "button",
		minDate: 1,
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
function changeAlerts(alts)
{
	document.frmTypeSubmit.txtAlertType.value = alts;
	document.frmTypeSubmit.submit();
}
</script>
<table border="0" width="95%">
<tr>
	<td>&nbsp;</td>
	<td align="left">Show Alerts&nbsp;
    	<select id="selAlert" name="selAlert" onchange="changeAlerts(this.value)">
        	<option <?php if($_POST[txtAlertType] == "up") echo 'selected="selected""';?> value="up">Upcoming</option>
            <option <?php if($_POST[txtAlertType] == "gone") echo 'selected="selected""';?> value="gone">Delivered</option>
            <option <?php if($_POST[txtAlertType] == "all") echo 'selected="selected""';?> value="all">All</option>            
        </select>
    
    </td>
</tr>
<tr>
<td width="40%" style="vertical-align:top;">
<form id="frmAddAlert" name="frmAddAlert" method="post" action="?ch=Device"> 
<input type="hidden" name="txtAlertId" id="txtAlertId" value="<?php echo $_POST[txtAlertId];?>" />
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>" />
<table class="gridform_final">
<tr><th colspan="2">Add Alert</th></tr>
  <tr>
    <td width="20%" align="right">Select Device&nbsp;</td>
    <td width="30%" align="left">
        <select name="map_device_id" id="map_device_id" tabindex="1" style="width:80%" class="validate[required]" onchange="document.frmAddAlert.rdAlertBy.selectedIndex = 0">
        <option value="">Select Device</option>
         <?php 
		while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{ 
			if($devices_fetch[di_deviceName])
				$devName = $devices_fetch[di_deviceName];
			else
				$devName = $devices_fetch[di_deviceId];
        ?>
        <option value="<?php echo $devices_fetch[di_id]; ?>" 
        <?php if($deviceRecord[tdai_deviceId] == $devices_fetch[di_id]) echo "selected"; ?>><?php echo $devName; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    </tr>
  <tr>
    <td align="right">Purpose</td>
    <td align="left">
            <input type="text" name="txtPurpose" id="txtPurpose" class="validate[required] text-input" tabindex="2" style="width:80%" value="<?php echo $deviceRecord[tdai_purpose];?>" />
    </td>
  </tr>              
  <tr>
    <td align="right">Alert Method </td>
    <td align="left">
    
    	<select name="rdAlertBy" id="rdAlertBy" tabindex="3" style="width:80%" class="validate[required]" onchange="showAlertBy(this.value);">
        	<option value="">Select Alert By</option>
        	<option value="Date" <?php if($deviceRecord[tdai_alertBy] == 'Date') echo 'selected="selected"';?>>Date</option>
            <option value="Meter" <?php if($deviceRecord[tdai_alertBy] == 'Meter') echo 'selected="selected"';?>>Odo Meter</option>
            <option value="OverSpeed" <?php if($deviceRecord[tdai_alertBy] == 'OverSpeed') echo 'selected="selected"';?>>Over Speed</option>
            <option value="OverStay" <?php if($deviceRecord[tdai_alertBy] == 'OverStay') echo 'selected="selected"';?>>Over Stay</option>
            <option value="LowBattery" <?php if($deviceRecord[tdai_alertBy] == 'LowBattery') echo 'selected="selected"';?>>Low Battery</option>
        </select>
        <!--<input type="radio" class="validate[required] radio" name="rdAlertBy" <?php if(isset($deviceRecord[tdai_alertBy])) { if($deviceRecord[tdai_alertBy] == 'Date') echo 'checked="checked"'; } else echo 'checked="checked"';?> id="rdDateAlert" value="Date" onclick="showAlertBy(this.value);" />Date
        <input type="radio" class="validate[required] radio" <?php if(isset($deviceRecord[tdai_alertBy])) { if($deviceRecord[tdai_alertBy] == 'Meter') echo 'checked="checked"'; } ?> name="rdAlertBy" id="rdKmAlert" value="Meter" onclick="showAlertBy(this.value);" />Odo Meter-->
    </td>
    </tr>   
  <tr>
        <td align="right" id="trAlertLabel"><?php 
			if(isset($deviceRecord[tdai_alertBy])) { 
				if($deviceRecord[tdai_alertBy] == 'Date') 
					echo 'Choose Date'; 
				elseif($deviceRecord[tdai_alertBy] == 'Meter') 
					echo 'Enter Odometer';
				elseif($deviceRecord[tdai_alertBy] == 'OverSpeed') 
					echo 'Speed Limit';
				elseif($deviceRecord[tdai_alertBy] == 'OverStay') 
					echo 'Duration Limit';
				elseif($deviceRecord[tdai_alertBy] == 'LowBattery') 
					echo 'Low Battery Limit'; } 
				else echo 'Choose Date';?></td>
        <td align="left">
        <?php
		if(isset($deviceRecord[tdai_alertBy])) 
		{ 
			if($deviceRecord[tdai_alertBy] == 'Date') 
			{
				?>
                 <span id="spanAlertDate" style="display:block">
                <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOdoMRead" style="display:none">
                <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOverSpeed" style="display:none">
                Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60" selected="selected">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120" selected="selected">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                       </select>
                
                </span>
                <span id="spanOverStay" style="display:none">
                <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
                </span>
                <span id="spanLowBattery" style="display:none">
                <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
                </span>
                <?php
			}
			elseif($deviceRecord[tdai_alertBy] == 'Meter') 
			{
				?>
                <span id="spanAlertDate" style="display:none">
                <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOdoMRead" style="display:block">
                <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOverSpeed" style="display:none">
               Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60" selected="selected">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120" selected="selected">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                       </select>
                </span>
                <span id="spanOverStay" style="display:none">
                <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
                </span>
                <span id="spanLowBattery" style="display:none">
                <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
                </span>
            <?php
			}
			elseif($deviceRecord[tdai_alertBy] == 'OverSpeed') 
			{
				?>
                <span id="spanAlertDate" style="display:none">
                <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOdoMRead" style="display:none">
                <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOverSpeed" style="display:block">
                Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<script language="javascript">
									document.write(minVal);
								</script>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<script language="javascript">
									document.write(maxVal);
								</script>
                       </select>
                </span>
                <span id="spanOverStay" style="display:none">
                <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
                </span>
                <span id="spanLowBattery" style="display:none">
                    <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
                    </span>
                <?php
			}
			elseif($deviceRecord[tdai_alertBy] == 'OverStay') 
			{
				?>
                 <span id="spanAlertDate" style="display:none">
                <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOdoMRead" style="display:none">
                <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOverSpeed" style="display:none">
                Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60" selected="selected">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120" selected="selected">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                       </select>
                </span>
                <span id="spanOverStay" style="display:block">
                <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
                </span>
                <span id="spanLowBattery" style="display:none">
                <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
                </span>
                <?php
			}
			
			elseif($deviceRecord[tdai_alertBy] == 'LowBattery') 
			{
				?>
                 <span id="spanAlertDate" style="display:none">
                <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOdoMRead" style="display:none">
                <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
                </span>
                <span id="spanOverSpeed" style="display:none">
                Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60" selected="selected">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<option value="20">20</option>
                                <option value="40">40</option>
                                <option value="60">60</option>
                                <option value="80">80</option>
                                <option value="100">100</option>
                                <option value="120" selected="selected">120</option>
                                <option value="140">140</option>
                                <option value="160">160</option>
                                <option value="180">180</option>
                                <option value="200">200</option>
                                <option value="220">220</option>
                                <option value="240">240</option>
                                <option value="260">260</option>
                                <option value="280">280</option>
                                <option value="300">300</option>
                       </select>
                </span>
                <span id="spanOverStay" style="display:none">
                <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
                </span>
                <span id="spanLowBattery" style="display:block">
                <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
                </span>
                <?php
			}
		}
		else
		{
			
		?>
        <span id="spanAlertDate" style="display:block">
        <input type="text" name="txtAlertDate" id="txtAlertDate" class="validate[required] text-input" readonly="true" tabindex="5" style="width:60%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
        </span>
        <span id="spanOdoMRead" style="display:none">
        <input type="text" name="txtOdoMRead" id="txtOdoMRead" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>
        </span>
        <span id="spanOverSpeed" style="display:none">
        	Min &nbsp;<select name="sdMinSpeed" id="sdMinSpeed" class="validate[required] text-input">
                				<script language="javascript">
									document.write(minVal);
								</script>
                            </select>
                 Max &nbsp;<select name="sdMaxSpeed" id="sdMaxSpeed" class="validate[required] text-input">
                				<script language="javascript">
									document.write(maxVal);
								</script>
                       </select>
        
        </span>
        <span id="spanOverStay" style="display:none">
        <input type="text" name="txtOverStay" id="txtOverStay" class="validate[required] text-input" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdai_alertSrc];?>"/>&nbsp; in Mins
        </span>
        <span id="spanLowBattery" style="display:none">
        <input type="text" name="txtLowBattery" id="txtLowBattery" class="validate[required] text-input" readonly="readonly" tabindex="5" style="width:80%" value="10"/>&nbsp; in mV
        </span>
        
        <?php 
		}
		?>
        </td>
        
   </tr>
  <tr>
    <td align="right">Alert Type </td>
    <td align="left">
        <input type="radio" class="validate[required] radio" name="rdAlertType" id="rdSMSAlert" <?php if(isset($deviceRecord[tdai_alertType])) { if($deviceRecord[tdai_alertType] == 'Mobile') echo 'checked="checked"'; } else echo 'checked="checked"';?>  value="Mobile" onchange="submitAlertType(this.value);" />SMS
        <input type="radio" class="validate[required] radio" name="rdAlertType" id="rdMailAlert" <?php if(isset($deviceRecord[tdai_alertType])) { if($deviceRecord[tdai_alertType] == 'Email') echo 'checked="checked"'; } ?> value="Email" onchange="submitAlertType(this.value);" />Mail
    </td>
    </tr>
  <tr>
    <td align="right">Mobile / Email ID</td>
    <td align="left">
    <?php
		if($deviceRecord[tdai_alertType]!='')
			$getContInfo =  "SELECT * FROM tb_client_contact_info WHERE tcci_srcType = '".$deviceRecord[tdai_alertType]."' AND tcci_clientId  =".$_SESSION[clientID]." ORDER BY tcci_source ASC";
		else
			$getContInfo =  "SELECT * FROM tb_client_contact_info WHERE tcci_srcType = 'Mobile' AND tcci_clientId  =".$_SESSION[clientID]." ORDER BY tcci_source ASC";
			
		//echo $getContInfo;	
		$resContInfo = mysql_query($getContInfo);
	?>
         <select name="selContId" id="selContId" tabindex="1" style="width:80%" size="3" multiple="multiple" onchange="changeValue()" class="validate[required]" >
         <?php 
		
		
		while($fetContInfo = @mysql_fetch_assoc($resContInfo)) 
		{ 
			if(isset($_POST[txtAlertId]))
			{
				$geoArr = explode(",",$deviceRecord[tdai_source]);
				if(in_array($fetContInfo[tcci_source],$geoArr))
				{
					$select = 'selected="selected"';
				}
				else $select ='';
			}
        ?>
        <option value="<?php echo $fetContInfo[tcci_source];?>" <?php echo $select ?>><?php echo $fetContInfo[tcci_source]; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="txtContIds" id="txtContIds" value="<?php echo $deviceRecord[tdai_source];?>" />
    </td>
   </tr>
   <tr>
    <td align="right">Description</td>
    <td align="left">
	    <textarea name="txtAreaAlertDesc" id="txtAreaAlertDesc" class="validate[required] text-input" cols="30" rows="2"><?php echo $deviceRecord[tdai_description];?></textarea>    
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">    
    <?php if(isset($_POST[txtAlertId]) && $_POST[txtAlertId]!='')  { ?>
    <input type="submit" name="cmdUpdateAlert" id="cmdUpdateAlert" value="Update" class="click_btn" tabindex="8"  /> 
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=myalerts';" tabindex="9" /> 
	<?php } else { ?>
    <input type="submit" name="cmdAddAlert" id="cmdAddAlert" value="Add" class="click_btn" tabindex="8"/>
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=myalerts';" tabindex="9" /> 
    <?php } ?>
    </td>
  </tr>  
</table>
</form>
</td>
<td style="vertical-align:top;">
<?php
	$selInsur = "SELECT * FROM tb_device_alert_info,tb_deviceinfo WHERE tdai_active = 1 AND di_id = tdai_deviceId AND tdai_clientId = ".$_SESSION[clientID];
	
	if($_POST[txtAlertType] == "up" || !isset($_POST[txtAlertType]))
		$selInsur .= " AND tdai_status = 0";
	else if($_POST[txtAlertType] == "gone")
		$selInsur .= " AND tdai_status = 1";
	else if($_POST[txtAlertType] == "all")
		$selInsur = $selInsur;

	$resInsur = $db->query($selInsur);
?>
<table class="gridform_final" style="width:100%">
    <tr>
        <th>Device Name</th>
        <th>Purpose</th>
        <th>Alert Thru.</th>
        <th>Date/Reading</th>
        <th>Mobile / Email</th>
        <?php if($_POST[txtAlertType] == "gone") { ?>
        <th>Status</th>
        <th>Time</th>
        <?php } else { ?>
        <th>Delete</th>
        <th>Edit</th>
        <?php } ?>
   </tr>
	 <?php 
	 if($db->affected_rows)
	 {
    while($fetInsur = @mysql_fetch_assoc($resInsur)) 
    { 
        if($fetInsur[di_deviceName])
            $devName = $fetInsur[di_deviceName];
        else
            $devName = $fetInsur[di_deviceId];
    ?>
   <tr>
    <td><?php echo $devName;?></td>
    <td><?php echo $fetInsur[tdai_purpose];?></td>
    <td><?php echo $fetInsur[tdai_alertBy];?></td>
    <td><?php echo ucfirst($fetInsur[tdai_alertSrc]);?></td>
    <td><?php echo $fetInsur[tdai_source];?></td>
    <?php if($_POST[txtAlertType] == "gone" || $fetInsur[tdai_status] == 1) { ?>
    <td>Delivered</td>
    <td><?php echo date("h:i A",strtotime($fetInsur[tdai_deliveryTime]));?></td>
    <?php } else { ?>
    <td><a class="error_strings" href="#" onclick="changeSts('<?php echo $fetInsur[tdai_id];?>')">Delete</a></td>
    <td><a class="error_strings" href="#" onclick="funEditUser('<?php echo $fetInsur[tdai_id];?>','<?php echo $_SESSION[clientID];?>','?ch=myalerts')">Edit</a></span></td>
    <?php } ?>
  </tr>
  <?php
	}
	 }
	 else
	 {
		 echo '<tr><td colspan="8">No Records Found</td></tr>';
	 }
  ?>
</table>
</td>
</tr>
</table>

<form name="frmSubmit" id="frmSubmit" method="post">
    <input type="hidden" name="txtAlertId" id="txtAlertId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
</form>

<form name="frmTypeSubmit" id="frmTypeSubmit" method="post">
    <input type="hidden" name="txtAlertType" id="txtAlertType" />
</form>