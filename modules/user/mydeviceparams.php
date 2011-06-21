<?php

if((isset($_POST[map_device_id]) && $_POST[map_device_id]!="") || $_POST[txtTime]!=""){
if(isset($_REQUEST[id]) && $_REQUEST[id]!=""){
   $query1 = "SELECT * FROM tb_device_param_info WHERE id = ".$_REQUEST[id]."";
  $result1 = $db->query($query1);
  $value1 = mysql_fetch_row($result1);
}

	
	$data['time'] = $_POST[txtTime];
if($value1[2]==""){

	$data[client_id] = $_SESSION[clientID];
	$data[device_id] = $_POST[map_device_id];
	$data[type] = $_POST[over];
	
	if($db->query_insert("tb_device_param_info", $data))
		{
			header("location:?ch=deviceParams");
			exit;
		}
  } else {
      if($db->query_update("tb_device_param_info", $data, "id=".$value1[0]))
		{
			header("location:?ch=deviceParams");
			exit;
		}
  }
}
if(isset($_REQUEST['id']) && $_REQUEST['id']!=""){
 
 $query = "SELECT * FROM tb_device_param_info WHERE id = ".$_REQUEST['id'];
 $result = $db->query($query);
  $value = mysql_fetch_row($result);

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


			
           
			if($value[3]=='overspeed'){
             $u = 'checked';
			}
			if($value[3]=='overstay'){
             $w = 'checked';
			}
?>

<table border="0" width="100%">
<tr>
<td width="50%">
<form id="frmAddDeviceParam" name="frmAddDeviceParam" method="post" action="?ch=deviceParams&id=<?php echo $_REQUEST['id'];?>" onsubmit="return validateForm();"> 
<input type="hidden" name="txtContId" id="txtContId" value="<?php echo $_POST[txtContId];?>" />
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_SESSION[clientID];?>" />
<table class="gridform_final" width="100%">
<tr><th colspan="2">Add Device Parameters</th></tr>
  <tr>
    <td width="15%" align="right">Select Device</td>
    <td width="35%" align="left">
    <select name="map_device_id" id="map_device_id" tabindex="1" style="width:100%" <?php if($value[2]!=""){echo 'disabled';}?>>
        <option value="">Select Device</option>
        <?php 
		while($devices_fetch = @mysql_fetch_assoc($devices_resp)) 
		{
			if($devices_fetch[di_deviceName])
				$devName = $devices_fetch[di_deviceName];
			else
				$devName = $devices_fetch[di_deviceId];

			if($value[2]==$devices_fetch[di_imeiId]){
             $v = 'selected';
			}

        ?>
        <option value="<?php echo $devices_fetch[di_imeiId]; ?>" <?php echo $v;?>><?php echo $devName; ?></option>
        <?php } ?>	
        </select>
	</td>
  </tr>              
  <tr>
    <td width="15%" align="right">Type</td>
    <td width="35%" align="left">
        <input type="radio" name="over" id="over" tabindex="2" value="overspeed" 
		<?php echo $u;?> <?php if($u!="" || $w!=""){echo 'DISABLED';}?>/>Over speed
        <input type="radio" name="over" id="over" tabindex="3" value="overstay" 
		<?php echo $w;?> <?php if($w!="" || $u!=""){echo 'DISABLED';}?>/>Over stay
    </td>
    </tr>
  <tr>
    <td width="15%" align="right">Enter Time</td>
    <td width="35%" align="left">
        <input type="text" name="txtTime" id="txtTime" tabindex="4" value="<?php echo $value[4];?>"/>
  </tr>
  <tr>
    <td align="center" colspan="2">
  

      <input type="submit" name="map_filter_btn" value="<?php if($_REQUEST[id]==""){echo 'Add';}else{echo 'Update';}?>" class="click_btn" tabindex="5" />    
      <input type="submit" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" tabindex="6" /> 
    </td>
  </tr>  
</table>
</form>
</td>
<td style="vertical-align:top;">
<?php
$selInsur = "SELECT * FROM tb_device_param_info WHERE client_id = ".$_SESSION[clientID];
$resInsur = $db->query($selInsur);



?>
<table class="gridform_final" style="width:100%">
    <tr>
        <th>Device Name</th>
        <th>Type</th>
        <th>Time</th>
   </tr>
	 <?php 
	 if($db->affected_rows)
	 {
    while($fetInsur = @mysql_fetch_assoc($resInsur)) 
    { 
		$selDev = "SELECT * FROM tb_deviceinfo WHERE di_imeiId = ".$fetInsur[device_id];
        $resDev = $db->query($selDev);
	    
		while($fetDev = @mysql_fetch_assoc($resDev)){
            if($fetDev[di_deviceName]==""){
			  $dn= $fetDev[di_deviceId];
		    }else {$dn= $fetDev[di_deviceName];}
	     }
		?>
   
   <tr>
       <td><?php echo $dn;?></td>
 
	 
    <td><?php echo $fetInsur[type];?></td>
    <td><?php echo $fetInsur['time'];?></td>
    <td><a class="error_strings" href="#"
	onclick="location.href='?ch=deviceParams&id=<?php echo $fetInsur[id];?>';">Edit</a></span></td>
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

<script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmAddDeviceParam");
 
  frmvalidator.addValidation("map_device_id","req","Please select Device");
 // frmvalidator.addValidation("over","req","Please select overSpeed");

  frmvalidator.addValidation("txtTime","req","Please enter valid numbers");
  frmvalidator.addValidation("txtTime","numeric","Please enter valid time");
 
 
</script>   