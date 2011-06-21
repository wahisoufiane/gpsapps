<?php
if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')
{
	$getDeviceGeofence = "SELECT * FROM tb_assigngeofence WHERE tag_diId = ".$_POST[map_device_id]." AND tag_geofenceId = '".$_POST[selGeofenceId]."'";	
	$resDeviceGeofence = $db->query($getDeviceGeofence);
		
	if($db->affected_rows == 0 )
	{
		$data['tag_geofenceId'] = $_POST[selGeofenceId];
		$data['tag_alertType'] = "Mobile";
		$data['tag_diId'] = $_POST[map_device_id];
		$data['tag_alertSrc'] = $_POST[txtMobiEmail];
		$data['tag_inout'] = $_POST[selInOut];
		$data['tag_noofTimes'] = $_POST[selNoofAlert];
		$data['tag_clientId'] = $_SESSION[clientID];
		
		if($_POST[chkStatusFlag])
		$data['tag_isActive'] = 1;
		else
		$data['tag_isActive'] = 0;
		
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
		
		$fetDeviceGeofence = @mysql_fetch_assoc($resDeviceGeofence);
		//print_r($fetDeviceGeofence);
		$data['tag_geofenceId'] = $_POST[selGeofenceId];
		$data['tag_alertType'] = "Mobile";
		$data['tag_alertSrc'] = $_POST[txtMobiEmail];
		$data['tag_inout'] = $_POST[selInOut];
		$data['tag_noofTimes'] = $_POST[selNoofAlert];
		$data['tag_clientId'] = $_SESSION[clientID];
		$data['tag_updateDate'] = "NOW()";
		
		if($_POST[chkStatusFlag])
		$data['tag_isActive'] = 1;
		else
		$data['tag_isActive'] = 0;
		
		//print_r($data);
		//exit;
		if($db->query_update("tb_assigngeofence", $data , "tag_id=".$fetDeviceGeofence[tag_id]))
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
	if(document.getElementById('selGeofenceId').value == 0)
	{
		alert("Select Geofence"); 
		document.getElementById('selGeofenceId').focus();
		return 0;  
	}
	if(document.getElementById('txtMobiEmail').value == '')
	{
		alert("Enter Mobile No"); 
		document.getElementById('txtMobiEmail').focus();
		return 0;  
	}
	else
	{
		var val = document.getElementById('txtMobiEmail').value;
		if(isNaN(val))
		{
			alert("Enter valid Mobile No"); 
			document.getElementById('txtMobiEmail').focus();
			return 0;
		}
		else
		{
			if(val.length < 10 || val.length > 10)
			{
				alert("Enter valid length of Mobile No"); 
				document.getElementById('txtMobiEmail').focus();
				return 0;
			}
		}
	}
	return 1;
	
}
function validate(address) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   if(reg.test(address) == false) {
      alert('Invalid Email Address');
	  document.getElementById('txtMobiEmail').focus();
      return false;
   }
}
var ajax1=new sack();
function getDeviceGeofence(devid,geoid)
{
//alert(devid);
	if(devid!=0 && geoid != 0)
	{
		qry = "select * from tb_assigngeofence where tag_diId  = "+devid+" AND tag_geofenceId = "+geoid;
		//alert(qry);
		ajax1.requestFile = 'ajax_server.php?getResQry='+qry;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){resultDevice(devid,geoid)};
		ajax1.runAJAX();
	}
}
function resultDevice(devid,geoid)
{
  var data = ajax1.response.split(",");
  if(ajax1.response !=0 )
  {
	document.frmGeofenceId.map_device_id.value = data[2];
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
<div class="pagearea">
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();">      	 
<table class="form1">
<tr><th colspan="4">Assign Geofence</th></tr>
  <tr>
    <td width="41%" class="formtext">Select Device&nbsp;</td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <select name="map_device_id" id="map_device_id" tabindex="1" style="width:50%">
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
    </tr>
    <tr>
    <td width="41%" class="formtext">Geofence Points</td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <select name="selGeofenceId" id="selGeofenceId" tabindex="1" style="width:50%" onchange="getDeviceGeofence(document.frm_map_filter.map_device_id.value,this.value);">
        <option value="0">Select Geofence</option>
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
  </tr>
  <tr>    
    <td class="formtext">Alert when device</td>
     <td width="2%" align="center">  : </td> 
      <td width="57%" > 
                	<input type="radio" name="selInOut" <?php if(isset($_POST[selInOut])) { if($_POST[selInOut] == 'in') echo 'checked="checked"'; } else echo 'checked="checked"';?> value="in" />
                	Enters&nbsp;/&nbsp;
                	<input type="radio" name="selInOut" <?php if(isset($_POST[selInOut])) { if($_POST[selInOut] == 'out') echo 'checked="checked"'; } ?> value="out" />
                	Exit
                the geofence Area</td>
    </tr>
    <tr>
        <td width="41%" class="formtext">Status Active&nbsp;/&nbsp;Deactive</td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="checkbox" name="chkStatusFlag" checked="checked" id="chkStatusFlag" /></td>
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
                    <option <?php if($_POST[selNoofAlert] == $i) echo 'selected="selected"';?> value="<?php echo $i;?>"><?php echo $i;?></option>
                <?php
            }
        ?>
        </select>
      </td>
   </tr>
   <tr>
   <td width="15%" align="right"><span class="form_text">Mobile No</span></td>
    <td width="2%" align="center">  : </td> 
      <td width="57%" >
        <input type="text" name="txtMobiEmail" id="txtMobiEmail" value="<?php echo $_POST[txtMobiEmail];?>" />
    </td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn" value="Submit" class="sub_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="sub_btn" onclick="location.href='index.php?ch=assignGeofence';" tabindex="7" /> 
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
    <input type="hidden" name="txtGeoIds" id="txtGeoIds" />
    <input type="hidden" name="txtMobiEmail" id="txtMobiEmail" />
</form>