<?php
//print_r($_POST);
if(isset($_POST[txtAssGeoId]) && $_POST[txtAssGeoId] !='')
{
	$sql = "SELECT * FROM tb_assigngeofence where tag_id =".$_POST[txtAssGeoId];
	$rows = $db->query($sql);
	$userRecord = $db->fetch_array($rows);
//	print_r($userRecord);
	
	$spltTime = explode("#",$userRecord[tag_donotTime]);
	//$readonly = 'readonly="readonly"';
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

function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
		return true;
	else return false;
}

jQuery(function() 
{

 $("#time3, #time4").timePicker({
  startTime: "00:00 AM", // Using string. Can take string or Date object.
  endTime: "11:55 PM", // Using Date object here.
  show24Hours: false,
  separator: ':',
  step: 5});    
	
var oldTime = $.timePicker("#time3").getTime();

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

function validateMapReport()
{
	if(document.getElementById('map_device_id').value== 0 )
	{
		alert("Select Device"); 
		document.getElementById('map_device_id').focus();
		return 0;  
	}
	if(document.getElementById('selGeofenceId').value == 0)
	{
		alert("Select Geofence"); 
		document.getElementById('selGeofenceId').focus();
		return 0;  
	}
	if(document.getElementById('txtMobiEmail').value == '')
	{
		alert("Enter Mobile No/Email ID"); 
		document.getElementById('txtMobiEmail').focus();
		return 0;  
	}
	if(document.getElementById('txtMobiEmail').value)
	{
		var val =document.getElementById('txtMobiEmail').value;
		val = val.split(",");
		
		for(i=0; i<val.length-1; i++)
		{
			
			if(document.getElementById('rdSMSAlert').checked)
			{
				if(!mobileNoValid(val[i]))
					document.getElementById('txtMobiEmail').focus();
			}
			else if(document.getElementById('rdMailAlert').checked)
			{
				//alert(document.getElementById('rdMailAlert').checked);
				if(!validateEmail(val[i]))
					document.getElementById('txtMobiEmail').focus();
			}
		}
	}
	return 1;
	
}
function validateEmail(elementValue){
   var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
   if(!emailPattern.test(elementValue))
   {
	   alert('Please enter valid email address');
	   return false;
   }
   else return true;
 }

function mobileNoValid(incomingString)
{
	if(incomingString.length < 10 || incomingString.search(/[^0-9\-()+]/g) != -1 )
	{
		alert('Please enter valid mobile number');
		objMobileNo.focus();
		objMobileNo.value="";
		return false;
	}
	else
	return true; 
	
}
var ajax1=new sack();
function submitAlertType(val)
{
	ajax1.requestFile = 'ajax_server.php?contType='+val;			
	//alert(ajax1.requestFile);
	ajax1.onCompletion = function(){getSource()};
	ajax1.runAJAX();
}
function getSource()
{
	document.getElementById('txtMobiEmail').value="";
	document.getElementById('selContId').innerHTML = ajax1.response;
}
function getDeviceGeofence(geoid)
{

	if(geoid != 0)
	{
		qry = "select * from tb_assigngeofence where tag_diId  = "+geoid;
		ajax1.requestFile = 'ajax_server.php?getResQry='+qry;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){resultDevice(geoid)};
		ajax1.runAJAX();
	}
}
function resultDevice(geoid)
{
  var data = ajax1.response.split(",");
  if(ajax1.response !=0 )
  {
	document.frmGeofenceId.map_device_id.value = data[2];
	document.frmGeofenceId.alrtType.value = data[4];
	document.frmGeofenceId.selInOut.value = data[6];
	document.frmGeofenceId.selNoofAlert.value = data[7];
	var timeSlt = data[8].split("#");
	document.frmGeofenceId.time3.value = timeSlt[0];
	document.frmGeofenceId.time4.value = timeSlt[1];
	document.frmGeofenceId.txtGeoIds.value = data[1];
	document.frmGeofenceId.txtMobiEmail.value = data[5];
	document.frmGeofenceId.submit();
  }
	
}
function changeSts(id,span,uid)
{
	if(document.getElementById(id).checked)
		status = 1;
	else
		status = 0;
		
	ajax1.requestFile = 'ajax_server.php?upGeoStatus=y&geoid='+uid+'&status='+status;
	//alert(ajax1.requestFile);
	ajax1.onCompletion = function(){resultGeoStatus(span,id)};
	ajax1.runAJAX();
}
function resultGeoStatus(span,id)
{
  //alert(ajax1.response)	
  if(ajax1.response == 4)
  {
	 alert("Status Updated Successfully.");
	 if(document.getElementById(id).checked) 
	  	document.getElementById(span).innerHTML = 'Deactive';
	 else 
		document.getElementById(span).innerHTML = 'Active';
	
	document.getElementById('selGeofenceId').innerHTML = "<option value=0>Loading...</option>";
	window.location.href = "?ch=assignGeofence";
  }
  else
  {
	  alert("Status Not Updated. May be geofence assigned to any device");
	  window.location.href = "?ch=assignGeofence";
  }
	
}
function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
function changeGeoValue()
{
	var tmpId = '';
	for (i=0; i<document.getElementById('selGeofenceId').length;i++)
	{
		if (document.getElementById('selGeofenceId').options[i].selected)
		{
			if(i==0)
				tmpId = document.getElementById('selGeofenceId').options[i].value+"#";
			else
				tmpId +=document.getElementById('selGeofenceId').options[i].value+"#";
		}
	}
	document.getElementById('txtGeoIds').value = tmpId;
}
function changeAlertValue()
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
	document.getElementById('txtMobiEmail').value = tmpId;
}
var ajax1=new sack();
function showPoints(f,s)
{
	//ajax1.requestFile = 'ajax_server.php?addGeoPoint=y&param='+f+'&name='+s;
	//document.write(ajax1.requestFile);
	/*var tableData = "";
	var f1 = f.split("@");
	for(i = 0; i < f1.length-1; i++)
	{
		//tableData = '<tr><td>'+i+'</td><td><input type="checkbox" name="param'+i+'" id="param'+i+'"></td><td><input type="text" name="param'+i+'" id="param'+i+'" /></td></tr>';
		tableData = '<tr><td>'+i+'</td><td><input type="checkbox" name="param'+i+'" id="param'+i+'"></td><td><input type="text" name="param'+i+'" id="param'+i+'" value="'+s[i]+'" /></td></tr>';
	}
	document.getElementById('paramTable').innerHTML= tableData;*/
}
</script>
<div class="pagearea">
<form id="frm_map_filter" name="frm_map_filter" method="post" action="?ch=Device" onsubmit="return validateMapReport();">   
<input type="text" name="txtAssGeoId" id="txtAssGeoId" value="<?php echo $_POST[txtAssGeoId];?>" /> 
<table>
<tr>
<td width="50%">
<table class="form1">
<tr height="35"><th colspan="4">Assign Geofence</th></tr>
  <tr>
    <td width="41%" class="formtext">Select Device&nbsp;</td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
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
        <option value="<?php echo $devices_fetch[di_id]; ?>" 
        <?php if($userRecord[tag_diId] == $devices_fetch[di_id]) echo 'selected="selected"'; ?>><?php echo 	$devName; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
  </tr>
   <tr>
    <td width="41%" class="formtext">Do not Disturb me between</td>
   <td width="2%" align="center">  : </td> 
      <td width="57%">      
      	<table class="grid" style="width:100%;">
          <tr>
            <td width="15%" style="border:0px;">From</td>
            <td width="35%" style="border:0px;"><span><input type="text" name="time3" id="time3" style="width:60%" readonly="true" tabindex="3" value="<?php if($spltTime[0]) echo $spltTime[0]; else echo "10:00 PM";?>" /></span></td>
            <td width="15%" style="border:0px;">To</td>
            <td width="35%" style="border:0px;"><span><input type="text" name="time4" id="time4" style="width:60%" readonly="true" tabindex="5" value="<?php if($spltTime[1]) echo $spltTime[1]; else echo "06:00 AM";?>" /></span></td>
          </tr>
        </table>
    </td>
    </tr> 
    <tr>
    <td width="41%" class="formtext">Alert me when device </td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <select name="selInOut" id="selInOut" style="width:50%">
            <option <?php if($userRecord[tag_inout] == "in") echo 'selected=selected';?> value="in">Entered Geofence</option>
            <option <?php if($userRecord[tag_inout] == "out") echo 'selected=selected';?> value="out">Left Geofence</option>
        </select></td>
    </tr>
  <tr>
     <td width="41%" class="formtext">No. of Times to Alert</td>
     <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <select name="selNoofAlert" id="selNoofAlert" style="width:50%">
        <?php 
            for($i = 1; $i <= 10; $i++)
            {
                
                ?>
                    <option <?php if($userRecord[tag_noofTimes] == $i) echo 'selected="selected"';?> value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php
            }
        ?>
        </select>
      </td>
    </tr>             
    <tr>
    <td width="41%" class="formtext">Geofence Points</td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <select name="selGeofenceId" id="selGeofenceId" tabindex="1" style="width:50%" >
        <option value="0">Select Geofence</option>
         <?php 
		$getGeofence =  "SELECT * FROM tb_geofence_info WHERE tgi_isActive = 1 AND tgi_clientId =".$_SESSION[clientID]." ORDER BY tgi_name ASC";
		$resGeofence = mysql_query($getGeofence);
		while($fetGeofence = @mysql_fetch_assoc($resGeofence)) 
		{ 
			if(isset($_POST[txtAssGeoId]))
			{
				$geoArr = explode("#",$userRecord[tag_geofenceId]);
				if(in_array($fetGeofence[tgi_id],$geoArr))
				{
					$select = 'selected="selected"';
				}
				else $select ='';
			}
        ?>
        <option value="<?php echo $fetGeofence[tgi_id];?>" <?php echo $select ?>><?php echo $fetGeofence[tgi_name]; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="txtGeoIds" id="txtGeoIds" value="<?php echo $userRecord[tag_geofenceId];?>" />
    </td>
   </tr>
   
   <tr>
    <td width="41%" class="formtext">Alert Type </td>
   <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <input type="radio" class="validate[required] radio" name="rdAlertType" id="rdSMSAlert" <?php if(isset($userRecord[tag_alertType])) { if($userRecord[tag_alertType] == 'Mobile') echo 'checked="checked"'; } else echo 'checked="checked"';?>  value="Mobile" onchange="submitAlertType(this.value);" />SMS
        <input type="radio" class="validate[required] radio" name="rdAlertType" id="rdMailAlert" <?php if(isset($userRecord[tag_alertType])) { if($userRecord[tag_alertType] == 'Email') echo 'checked="checked"'; } ?> value="Email" onchange="submitAlertType(this.value);" />Mail
    </td>
    </tr>
  <tr>
    <td width="41%" class="formtext">Mobile / Email ID</td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
    <?php
		if($userRecord[tag_alertType]!='')
			$getContInfo =  "SELECT * FROM tb_client_contact_info WHERE tcci_srcType = '".$userRecord[tag_alertType]."' AND tcci_clientId  =".$_SESSION[clientID]." ORDER BY tcci_source ASC";
		else
			$getContInfo =  "SELECT * FROM tb_client_contact_info WHERE tcci_srcType = 'Mobile' AND tcci_clientId  =".$_SESSION[clientID]." ORDER BY tcci_source ASC";
			
		//echo $getContInfo;	
		$resContInfo = mysql_query($getContInfo);
	?>
         <select name="selContId" id="selContId" tabindex="1" style="width:50%" size="3" multiple="multiple" onchange="changeAlertValue()" class="validate[required]" >
         <?php 
		
		
		while($fetContInfo = @mysql_fetch_assoc($resContInfo)) 
		{ 
			if(isset($_POST[txtAssGeoId]))
			{
				$geoArr = explode(",",$userRecord[tag_alertSrc]);
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
        <input type="hidden" name="txtMobiEmail" id="txtMobiEmail" value="<?php echo $userRecord[tag_alertSrc];?>" />
    </td>
   </tr>
   
  <tr>
    <td height="33" colspan="4" align="center">
    <?php if(isset($_POST[txtAssGeoId]) && $_POST[txtAssGeoId]!='') { ?>
    <input type="submit" name="cmdUpdateAssign" id="cmdUpdateAssign" value="Update" class="click_btn" tabindex="6" onclick="return showPreloader();" />
    <?php } else { ?>
    <input type="submit" name="cmdAddAssign" id="cmdAddAssign" value="Add" class="click_btn" tabindex="6" onclick="return showPreloader();" />
    <?php } ?>
    <input type="button" name="cmdCancel" id="cmdCancel" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=viewAssignGeo';" tabindex="7" /> 
	</td>
  </tr>
</table>
</td>
<td width="50%" valign="top" style="vertical-align:top">
<table class="form1" align="left">
<tr align="left">
<th width="5%">#</th><th width="70%">Name</th><th>Status</th>
</tr>
<?php
	$getGeofence =  "SELECT * FROM tb_geofence_info WHERE tgi_clientId =".$_SESSION[clientID]." ORDER BY tgi_name ASC";
	$resGeofence = mysql_query($getGeofence);
	if($db->affected_rows > 0)
	{
		$i = 0;
	while($fetGeofence = @mysql_fetch_assoc($resGeofence)) 
	{ 
?>
	<tr>
        <td><?php echo $i+1;?></td>
        <td><?php echo ucfirst($fetGeofence[tgi_name]);?></td>
        <td><input type="checkbox" name="chkUsrStatus<?php echo $i;?>" id="chkUsrStatus<?php echo $i;?>" <?php if($fetGeofence[tgi_isActive]) {?> checked="checked" <?php } ?> onclick="changeSts(this.id,'stsId<?php echo $i;?>','<?php echo $fetGeofence[tgi_id];?>')"/>
            <span id="stsId<?php echo $i;?>"><?php if($fetGeofence[tgi_isActive]) echo "Deactive"; else echo "Active"; ?></span>
        </td>
    </tr>
<?php
	$i++;
	}	
	}
	else
	{
?>
	<tr>
	<td colspan="3">No records found</td>
    </tr>
<?php
	}
?>
</table>
</td>
</tr>
</table>
</form>
</div>	
<form name="frmGeofenceId" id="frmGeofenceId" method="post">
	<input type="hidden" name="map_device_id" id="map_device_id" />
    <input type="hidden" name="alrtType" id="alrtType" />
    <input type="hidden" name="selInOut" id="selInOut" />
    <input type="hidden" name="selNoofAlert" id="selNoofAlert" />
    <input type="hidden" name="time3" id="time3" />
    <input type="hidden" name="time4" id="time4" />
    <input type="hidden" name="txtGeoIds" id="txtGeoIds" />
    <input type="hidden" name="txtMobiEmail" id="txtMobiEmail" />
</form>