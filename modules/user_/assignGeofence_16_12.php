<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{
	print_r($_POST);
	$getDeviceGeofence = "SELECT * FROM tb_assigngeofence WHERE tag_diId = ".$_POST[map_device_id];	
	$resDeviceGeofence = $db->query($getDeviceGeofence);
		
	if($db->affected_rows == 0 )
	{
		$data['tag_geofenceId'] = $_POST[txtGeoIds];
		$data['tag_alertType'] = $_POST[alrtType];
		$data['tag_diId'] = $_POST[map_device_id];
		$data['tag_alertSrc'] = $_POST[txtMobiEmail];
		$data['tag_inout'] = $_POST[selInOut];
		$data['tag_noofTimes'] = $_POST[selNoofAlert];
		$data['tag_clientId'] = $_SESSION[clientID];
		$data['tag_isActive'] = 1;
		
		//print_r($data);
		//exit;
		if($db->query_insert("tb_assigngeofence", $data))
		{
			header("location:?ch=status&au=18&msg=1");
			exit;
		}
		else
		{
			header("location:?ch=status&au=18&msg=0");
			exit;
		}
	}
	else
	{
		$data['tag_geofenceId'] = $_POST[txtGeoIds];
		$data['tag_alertType'] = $_POST[alrtType];
		$data['tag_alertSrc'] = $_POST[txtMobiEmail];
		$data['tag_inout'] = $_POST[selInOut];
		$data['tag_noofTimes'] = $_POST[selNoofAlert];
		$data['tag_clientId'] = $_SESSION[clientID];
		$data['tag_isActive'] = 1;
		
		//print_r($data);
		//exit;
		if($db->query_update("tb_assigngeofence", $data , "tag_diId=".$_POST[map_device_id]))
		{
			header("location:?ch=status&au=19&msg=1");
			exit;
		}
		else
		{
			header("location:?ch=status&au=19&msg=0");
			exit;
		}
	}

}
?> 

<script type="text/javascript" language="javascript">

function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{
		document.frm_map_filter.submit();
	}
}


function validateMapReport()
{
	if(document.getElementById('map_device_id').value== 0 )
	{
		alert("Select Device"); 
		document.getElementById('map_device_id').focus();
		return 0;  
	}
	if(document.getElementById('txtGeoIds').value == '')
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
	return 1;
	
}
var ajax1=new sack();
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
	document.frmGeofenceId.map_device_id.value = data[0];
	document.frmGeofenceId.alrtType.value = data[4];
	document.frmGeofenceId.selInOut.value = data[6];
	document.frmGeofenceId.selNoofAlert.value = data[7];
	document.frmGeofenceId.txtGeoIds.value = data[1];
	document.frmGeofenceId.txtMobiEmail.value = data[5];
	document.frmGeofenceId.submit();
  }
	
}
function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
function changeValue()
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
</script>
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();">      	 
<table class="gridform">
<tr><th colspan="4">Assign Geofence</th></tr>
  <tr>
    <td width="15%" align="right">Select Device&nbsp;</td>
    <td width="35%" align="left">
        <select name="map_device_id" id="map_device_id" tabindex="1" style="width:100%" onchange="getDeviceGeofence(this.value);">
        <option value="0">Select Device</option>
         <?php
		$devices_query =  "SELECT * FROM tb_deviceinfo WHERE di_clientId =".$_SESSION[clientID]." AND di_status = 1 ORDER BY di_createDate ASC";
		$devices_resp = mysql_query($devices_query); 
		while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{ 
			if($devices_fetch[di_deviceName])
				$devName = $devices_fetch[di_deviceName];
			else
				$devName = $devices_fetch[di_deviceId];
        ?>
        <option value="<?php echo $devices_fetch[di_id]; ?>" 
        <?php if($_POST[map_device_id] == $devices_fetch[di_id]) echo 'selected="selected"'; ?>><?php echo $devName; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    <td width="25%" align="right">Alert Type</td>
    <td width="25%" align="left">
    	<input type="radio" name="alrtType" <?php if(isset($_POST[alrtType])) { if($_POST[alrtType] == 'Mobile') echo 'checked="checked"'; } else echo 'checked="checked"';?> value="Mobile"  id="mobileAlt" />Mobile
        <input type="radio" name="alrtType" id="emailAlt" <?php if(isset($_POST[alrtType])) { if($_POST[alrtType] == 'Email') echo 'checked="checked"'; } ?> value="Email" />Email
    </td>
    </tr> 
    <tr>
    <td colspan="2" align="left">Alert me when device 
    			<select name="selInOut" id="selInOut" style="width:50%">
                	<option <?php if($_POST[selInOut] == "in") echo 'selected=selected';?> value="in">crossing Inside</option>
                    <option <?php if($_POST[selInOut] == "out") echo 'selected=selected';?> value="out">crossing Outside</option>
                </select> the geofence</td>
     <td align="right">No. of Times to Alert</td>
     <td align="left">
    			<select name="selNoofAlert" id="selNoofAlert" style="width:35%">
                <?php 
					for($i = 1; $i <= 10; $i++)
					{
						
						?>
                        	<option <?php if($_POST[selNoofAlert] == $i) echo 'selected="selected"';?> value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php
					}
				?>
                </select>
      </td>
    </tr>             
    <tr>
    <td width="15%" align="right"><span class="form_text">Geofence Points</span></td>
    <td width="35%" align="left">
        <select name="selGeofenceId" id="selGeofenceId" tabindex="1" style="width:100%" multiple="multiple" onchange="changeValue()" >
         <?php 
		$getGeofence =  "SELECT * FROM tb_geofence_info WHERE tgi_clientId =".$_SESSION[clientID]." ORDER BY tgi_name ASC";
		$resGeofence = mysql_query($getGeofence);
		while($fetGeofence = @mysql_fetch_assoc($resGeofence)) 
		{ 
			if(isset($_POST[txtGeoIds]))
			{
				$geoArr = explode("#",$_POST[txtGeoIds]);
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
        <input type="hidden" name="txtGeoIds" id="txtGeoIds" value="<?php echo $_POST[txtGeoIds];?>" />
    </td>
   <td width="15%" align="right"><span class="form_text">Mobile No's / Email</span></td>
    <td width="35%" align="left">
        <textarea name="txtMobiEmail" id="txtMobiEmail"><?php echo $_POST[txtMobiEmail];?></textarea>
        <span>Only Comma(,)separated values</span>
    </td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="sub_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=assignGeofence';" tabindex="7" /> 
	</td>
  </tr>
</table>
</form>	
<form name="frmGeofenceId" id="frmGeofenceId" method="post">
	<input type="hidden" name="map_device_id" id="map_device_id" />
    <input type="hidden" name="alrtType" id="alrtType" />
    <input type="hidden" name="selInOut" id="selInOut" />
    <input type="hidden" name="selNoofAlert" id="selNoofAlert" />
    <input type="hidden" name="txtGeoIds" id="txtGeoIds" />
    <input type="hidden" name="txtMobiEmail" id="txtMobiEmail" />
</form>