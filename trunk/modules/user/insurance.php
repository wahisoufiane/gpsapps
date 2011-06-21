<?php
//print_r($_POST);
if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !='')
{
	$sql = "SELECT * FROM tb_device_insurance_info WHERE tdii_deviceId =".$_POST[txtDeviceId];
	$rows = $db->query($sql);
	$deviceRecord = $db->fetch_array($rows);
	$expDate = $deviceRecord[tdii_policyExpDate];
	$remDate = $deviceRecord[tdii_alertDate];
	$rmd = dateDiff($expDate, $remDate);
	//print_r($deviceRecord);
	$readonly = 'readonly="readonly"';
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

function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';
}
function funEditUser(did,cid,act)
{
	//alert(uid)
	document.frmSubmit.txtDeviceId.value = did;
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



function validatePolicy1()
{
	alert('sss');
  /*if(document.getElementById('map_device_id').value== 0 )
  {
   alert("Select Device"); 
   document.getElementById('map_device_id').focus();
   return 0;  
  }
  
  if(document.getElementById('from_date').value=='')
  { alert("Select From Date"); document.getElementById('from_date').focus();  return 0;  }
  
  if(document.getElementById('to_date').value=='')
  { alert("Select To Date");  document.getElementById('to_date').focus(); return 0; }
  
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	//var todt_array = document.getElementById('to_date').value.split("-");
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
	//var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

	//var fr_to_diff = days_between(frdate, todate);
	//var days_diff = days_between(todate, curdate);
	var days_diff = days_between(frdate, curdate);

	if(days_diff > 0)
	{ 
	 alert("Date should not be future.");
	 document.getElementById('to_date').select();
	  return 0;
	}
	
	return 1;*/
	
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
$(function() {
	$( "#txtPolicyExp" ).datepicker({
		changeMonth: true,
		changeYear: true,
		minDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
</script>
<table border="0" width="95%">
<tr>
<td>
<form id="frmAddPolicy" name="frmAddPolicy" method="post" action="?ch=Device"> 
<input type="hidden" name="txtDeviceId" id="txtDeviceId" value="<?php echo $_POST[txtDeviceId];?>" />
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>" />
<table class="gridform_final">
<tr><th colspan="4">Add Insurance</th></tr>
  <tr>
    <td width="15%" align="right">Select Device&nbsp;</td>
    <td width="35%" align="left" colspan="3">
        <select name="map_device_id" id="map_device_id" tabindex="1" style="width:80%">
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
        <?php if($_POST[txtDeviceId] == $devices_fetch[di_id]) echo "selected"; ?>><?php echo $devName; ?></option>
        <?php } ?>		
        </select>
        <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
    </td>
    </tr>
  <tr>
    <td width="15%" align="right">Policy Number</td>
    <td width="35%" align="left">
            <input type="text" name="txtPolicyNo" id="txtPolicyNo" tabindex="2" style="width:80%" value="<?php echo $deviceRecord[tdii_policyNum];?>" />
    </td>
  </tr>              
  <tr>
    <td width="15%" align="right">Company</td>
    <td width="35%" align="left">
        <input type="text" name="txtPolicyComp" id="txtPolicyComp" tabindex="3" style="width:80%" value="<?php echo $deviceRecord[tdii_policyCompany];?>" />
    </td>
    </tr>
  <tr>
    <td width="15%" align="right">Amount</td>
    <td width="35%" align="left">
        <input type="text" name="txtPolicyAmt" id="txtPolicyAmt" tabindex="4" style="width:80%" value="<?php echo $deviceRecord[tdii_policyAmount];?>"/>
  </tr>
  <tr>
    <td width="15%" align="right">Expiry Date</td>
    <td width="35%" align="left">
        <input type="text" name="txtPolicyExp" id="txtPolicyExp" readonly="true" tabindex="5" style="width:80%" value="<?php echo $deviceRecord[tdii_policyExpDate];?>"/>
  </tr>
   <tr>
        <td width="15%" align="right">Remind me Before  </td>
      <td width="35%"  align="left">
      <select name="txtNoofDay" id="txtNoofDay" tabindex="6" style="width:80%">
      <option value="0">Select No of Days</option>
		<?php
        for($k=1;$k<=30;$k++)
        {
			
            if($rmd == $k)
                $select= 'selected="selected"';
            else if($k == 7)
                $select= 'selected="selected"';
			 else
                $select = '';
				
            echo '<option '.$select.' value='.$k.'>'.$k.'</option>';
        }
        ?>
        </select>
      &nbsp;Days
      
      </td>
  </tr>
  <tr>
    <td width="15%" align="right">Contact Person</td>
    <td width="35%" align="left">
        <input type="text" name="txtPolicyPerson" id="txtPolicyPerson" tabindex="7" style="width:80%" value="<?php echo $deviceRecord[tdii_policyNum];?>"/>
    </td>
   </tr>
   <tr>
    <td width="15%" align="right">Mobile</td>
    <td width="35%" align="left">
        <input type="text" name="txtPolicyMobile" id="txtPolicyMobile" tabindex="8" style="width:80%" value="<?php echo $deviceRecord[tdii_policyContMobile];?>" />
    </td>
  </tr>
  <tr>
    <td align="center" colspan="2">    
    <?php if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId]!='')  { ?>
    <input type="submit" name="cmdSubmitUpdateInusrance" id="cmdSubmitUpdateInusrance" value="Update" class="click_btn" tabindex="8"  /> 
    <input type="hidden" name="txtInsurId" id="txtInsurId" value="<?php echo $deviceRecord[tdii_id];?>" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=insurance';" tabindex="9" /> 
	<?php } else { ?>
    <input type="submit" name="cmdSubmitAddInusrance" id="cmdSubmitAddInusrance" value="Add" class="click_btn" tabindex="8" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=viewDevice';" tabindex="9" /> 
    <?php } ?>
    </td>
  </tr>  
</table>
</form>
</td>
<td style="vertical-align:top;">
<?php
$selInsur = "SELECT * FROM tb_device_insurance_info,tb_deviceinfo WHERE di_id = tdii_deviceId";
$resInsur = $db->query($selInsur);
?>
<table class="gridform_final" style="width:100%">
    <tr>
        <th>Device Name</th>
        <th>Policy No.</th>
        <th>Ploicy Amt.</th>
        <th>Contact Person</th>
        <th>Contact Number</th>
        <th>Exp. Date</th>
        <th>Alert Date</th>
        <th>Edit</th>
   </tr>
	 <?php 
    while($fetInsur = @mysql_fetch_assoc($resInsur)) 
    { 
        if($fetInsur[di_deviceName])
            $devName = $fetInsur[di_deviceName];
        else
            $devName = $fetInsur[di_deviceId];
    ?>
   <tr>
    <td><?php echo $devName;?></td>
    <td><?php echo $fetInsur[tdii_policyNum];?></td>
    <td><?php echo $fetInsur[tdii_policyAmount];?></td>
    <td><?php echo ucfirst($fetInsur[tdii_policyContPerson]);?></td>
    <td><?php echo $fetInsur[tdii_policyContMobile];?></td>
    <td><?php echo $fetInsur[tdii_policyExpDate];?></td>
    <td><?php echo $fetInsur[tdii_alertDate];?></td>
    <td><a class="error_strings" href="#" onclick="funEditUser('<?php echo $fetInsur[di_id];?>','<?php echo $_SESSION[clientID];?>','?ch=insurance')">Edit</a></span></td>
  </tr>
  <?php
	}
  ?>
</table>
</td>
</tr>
</table>
<script language="javaScript" type="text/javascript">

	var frmvalidator  = new Validator("frmAddPolicy");
	frmvalidator.addValidation("map_device_id","dontselect=0","Please select No of Device");
	
	frmvalidator.addValidation("txtPolicyNo","req","Please enter Policy Number");
	frmvalidator.addValidation("txtPolicyNo","minlen=5","Min length for Policy Number is 5");
	
	frmvalidator.addValidation("txtPolicyComp","req","Please enter Policy Company Name");
	frmvalidator.addValidation("txtPolicyComp","alpha_s","Policy Company Name can contain alphabetic chars only");
	
	frmvalidator.addValidation("txtPolicyAmt","req","Please enter Policy Amount");
	frmvalidator.addValidation("txtPolicyAmt","numeric","Please enter valid Policy Amount");
	
	frmvalidator.addValidation("txtPolicyExp","req","Please enter Policy Expiry Date");
	
	frmvalidator.addValidation("txtNoofDay","dontselect=0","Please select No of Days");
	
	frmvalidator.addValidation("txtPolicyPerson","req","Please enter Contact Person Name");
	frmvalidator.addValidation("txtPolicyPerson","alpha_s","Contact Person Name can contain alphabetic chars only");
	
	frmvalidator.addValidation("txtPolicyMobile","req","Please enter Mobile number");
	frmvalidator.addValidation("txtPolicyMobile","numeric","Please enter valid Mobile number");
	frmvalidator.addValidation("txtPolicyMobile","maxlen=10", "Invalid mobile number");
	
	//var queryString = "Add#"+document.frmAddPolicy.txtPolicyNo.value+"#"+document.frmAddPolicy.txtPolicyComp.value+"#"+document.frmAddPolicy.txtPolicyAmt.value+"#"+document.frmAddPolicy.txtPolicyExp.value+"#"+document.frmAddPolicy.txtPolicyPerson.value+"#"+document.frmAddPolicy.txtPolicyMobile.value;
 
</script>   
<form name="frmSubmit" id="frmSubmit" method="post">
    <input type="hidden" name="txtDeviceId" id="txtDeviceId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
</form>