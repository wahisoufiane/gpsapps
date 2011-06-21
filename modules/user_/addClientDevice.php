<?php
//print_r($_POST);
if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !='')
{
	$sql = "SELECT * FROM tb_deviceinfo WHERE di_id =".$_POST[txtDeviceId];
	$rows = $db->query($sql);
	$deviceRecord = $db->fetch_array($rows);
	$readonly = 'readonly="readonly"';
}
$sql = "SELECT ci_id,ci_clientName,ui_id FROM tb_clientinfo,tb_userinfo WHERE ui_isAdmin = 1 AND ui_clientId = ci_id AND ci_id=".$_POST[txtClientId];
$rows = $db->query($sql);
$clientRecord = $db->fetch_array($rows);
	

?>
<script language="javascript">
var ajax1=new sack();

//CHECKING UNIQUE DRIVER ID FOR ADMIN

function checkDevice(tableName,condStr,val,id)
{
	val = val.replace(" ","_");
	qry = "select * from "+tableName+" where di_status = 1 AND "+condStr+" = '"+val+"'";
		
	ajax1.requestFile = 'ajax_server.php?ajaxQry='+qry;
	//document.write(ajax1.requestFile);
	ajax1.onCompletion = function(){resultDevice(id)};
	ajax1.runAJAX();
}

function resultDevice(id)
{
  if(ajax1.response ==1 )
  {
  	alert("Date already Exist");
  	document.getElementById(id).value = '';
	document.getElementById(id).focus();
  }
	
}

</script>

<div class="pagearea"><!-- Pagearea div start here -->
<!--<div align="left" class="listofusers"><a href="#" onclick="location.href='?ch=viewClientDevice';">List of Device</a> / Add Device</div>
-->
<form name="frmDeviceRegister" id="frmDeviceRegister" action="?ch=Device" method="post" enctype="multipart/form-data">
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>"/>  
<input type="hidden" name="txtClientUserId" id="txtClientUserId" value="<?php echo $clientRecord[ui_id];?>"/>
<?php
if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !=''){
?>
	<input type="hidden" name="txtDeviceId" id="txtDeviceId" value="<?php echo $_POST[txtDeviceId];?>"/>  
<?php } ?>
<table class="form1">
<tr>
        <td width="41%" class="formtext">Client </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><?php echo ucfirst($clientRecord[ci_clientName]);?>
     </td>
     
  </tr>
    <tr>
        <td width="41%" class="formtext">Device Id </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
        <?php if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !=''){ ?>
        <input type="text" id="txtDeviceUnId" name="txtDeviceUnId" disabled="disabled" value="<?php echo $deviceRecord[di_deviceId];?>" />
        <?php } else { ?>
        <input type="text" id="txtDeviceUnId" name="txtDeviceUnId" value="<?php echo $deviceRecord[di_deviceId];?>" onblur="checkDevice('tb_deviceinfo','di_deviceId',this.value,this.id);"  />
        <?php } ?>
     </td>
     
  </tr>
   
   	<?php if($recordUserInfo[ci_clientType] == "Client") { ?>
    		
    <tr>
        <td width="41%" class="formtext">Device Name</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
        <input type="text" id="txtDeviceName" name="txtDeviceName" value="<?php echo $deviceRecord[di_deviceName];?>" onblur="checkDevice('tb_deviceinfo','di_deviceName',this.value,this.id);"  />
     </td>
  </tr>
            
    <?php } ?>

   
  <tr>
        <td width="41%" class="formtext">IMEI </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
         <?php if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !=''){ ?>
         <input type="text" id="txtImeiNo" name="txtImeiNo" disabled="disabled" value="<?php echo $deviceRecord[di_imeiId];?>" />
         <?php } else { ?>
         <input type="text" id="txtImeiNo" name="txtImeiNo" value="<?php echo $deviceRecord[di_imeiId];?>" onblur="checkDevice('tb_deviceinfo','di_imeiId',this.value,this.id);"/>
        <?php } ?>
     </td>
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Mobile No </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
        	<?php if($recordUserInfo[ci_clientType] == "Client") { ?>
         <input type="text" id="txtMobileNo" name="txtMobileNo" disabled="disabled" value="<?php echo $deviceRecord[di_mobileNo];?>"/>
         <?php } else { ?>
         <input type="text" id="txtMobileNo" name="txtMobileNo" value="<?php echo $deviceRecord[di_mobileNo];?>"/>
         <?php } ?>
   </td>     
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Odometer Reading</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="text" id="txtOdometer" name="txtOdometer" value="<?php echo $deviceRecord[di_odoMeter];?>"/>
   </td>     
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Device Model</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="text" id="txtDeviceModel" name="txtDeviceModel" value="<?php echo $deviceRecord[di_deviceModel];?>"/>
   </td>     
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Status </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="checkbox" value="1" id="txtStatus" <?php if($_POST[txtDeviceId]!='') { if($deviceRecord[di_status] == 1) echo 'checked="checked"'; } else {echo 'checked="checked"';}  ?>  name="txtStatus" />
      </td>
  </tr>
  <tr valign="top">
        <td width="41%" class="formtext" valign="top">Image </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <input type="file" name="fileDeviceLogo" id="fileDeviceLogo" />
      &nbsp;Or&nbsp;
      <br /><input type="radio" checked="checked" name="rdDevImg" id="rdDevImg_1" value="tank1.png" /><img src="unit_img/tank1.png" height="75" width="75" />&nbsp;<input type="radio" name="rdDevImg" id="rdDevImg_2" value="Car1.png" /><img src="unit_img/Car1.png" height="75" width="75" />&nbsp;<input type="radio" name="rdDevImg" id="rdDevImg_3" value="lorry1.png" /><img src="unit_img/lorry1.png" height="75" width="75" />&nbsp;<input type="radio" name="rdDevImg" id="rdDevImg_4" value="boat2.png" /><img src="unit_img/boat2.png" height="75" width="75" />&nbsp;<input type="radio" name="rdDevImg" id="rdDevImg_4" value="bus1.png" /><img src="unit_img/bus1.png" height="75" width="75" /></td>
  </tr>

  <tr><td colspan="3" align="center">
	  <?php
      if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitClientUpdateDevice" id="cmdSubmitClientUpdateDevice" value="Update" class="click_btn" />
            <input type="button" class="click_btn" name="cmdCancel" id="cmdCancel" onclick="location.href='?ch=viewDevice';" value="Cancel" />
      <?php } else { ?>
            <input type="submit" name="cmdSubmitClientAddDevice" id="cmdSubmitClientAddDevice" value="Submit" class="click_btn" />
                                         <input type="reset" value="Reset" class="click_btn" />
      <?php } ?>
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmDeviceRegister");
  
  frmvalidator.addValidation("txtDeviceUnId","req","Please enter Device Name");
  frmvalidator.addValidation("txtDeviceUnId","minlen=5","Min length for Device ID is 5");
  
  if(document.frmDeviceRegister.txtDeviceName)
  frmvalidator.addValidation("txtDeviceName","req","Please enter Device Name");
  
  frmvalidator.addValidation("txtImeiNo","maxlen=50");
  frmvalidator.addValidation("txtImeiNo","req","Please enter imei number");
  frmvalidator.addValidation("txtImeiNo","numeric","Please enter valid numbers");
  
  frmvalidator.addValidation("txtMobileNo","maxlen=50");
  frmvalidator.addValidation("txtMobileNo","req","Please enter Mobile number");
  frmvalidator.addValidation("txtMobileNo","numeric","Please enter valid Mobile numbers");

  frmvalidator.addValidation("txtOdometer","maxlen=50");
  frmvalidator.addValidation("txtOdometer","req","Please enter Odometer Reading");
  frmvalidator.addValidation("txtOdometer","numeric","Please enter valid Reading");
  
  frmvalidator.addValidation("txtDeviceModel","req","Please enter Device Model");
  
</script>   
</div><!-- PAGEarea END here -->
