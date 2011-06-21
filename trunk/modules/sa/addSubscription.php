<?php
//print_r($_POST);
if(isset($_POST[txtSubsId]) && $_POST[txtSubsId] !='')
{
	$sql = "SELECT * FROM tb_client_subscription WHERE tcs_id =".$_POST[txtSubsId]." AND tcs_clientId=".$_POST[txtClientId];
	$rows = $db->query($sql);
	$subsRecord = $db->fetch_array($rows);
    $endDate = date("d-m-Y",strtotime("-1 days ".($subsRecord[tcs_noOfMonths]) ."months ".$subsRecord[tcs_renewalDateFrom]));
}
$sql = "SELECT ci_id,ci_clientName,ui_id FROM tb_clientinfo,tb_userinfo WHERE ui_isAdmin = 1 AND ui_clientId = ci_id AND ci_id=".$_POST[txtClientId];
$rows = $db->query($sql);
$clientRecord = $db->fetch_array($rows);

?>
<script>
$(function() {
	$( "#txtRenewalDate" ).datepicker({
		changeMonth: true,
		changeYear: true
	});
});
function showHideSubsTable(val)
{
	if(val == "Cheque")
		document.getElementById('txtChequeNo').disabled = false;
	else
		document.getElementById('txtChequeNo').disabled = true;
}
Date.prototype.defaultView=function(){
var dd=this.getDate();
if(dd<10)dd='0'+dd;
var mm=this.getMonth()+1;
if(mm<10)mm='0'+mm;
var yyyy=this.getFullYear();
return String(dd+"-"+mm+"-"+yyyy)
}
function addMonth(d,month)
{
t = new Date (d);
var ed = new Date(new Date(t).setMonth(t.getMonth()+parseInt(month)));

return ed.defaultView();
} 
function showEndDate(sDate,durMonth)
{
	document.getElementById('txtEndDate').innerHTML = addMonth(sDate,durMonth);
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<form name="frmClientSubs" id="frmClientSubs" action="?ch=Subscription" method="post">
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>"/>  
<input type="hidden" name="txtClientUserId" id="txtClientUserId" value="<?php echo $clientRecord[ui_id];?>"/>
<?php
if(isset($_POST[txtSubsId]) && $_POST[txtSubsId] !=''){
?>
	<input type="hidden" name="txtSubsId" id="txtSubsId" value="<?php echo $_POST[txtSubsId];?>"/>  
<?php } ?>
<table class="form1">
  <tr>
        <td width="41%" class="formtext">Client </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><?php echo ucfirst($clientRecord[ci_clientName]);?>
     </td>
     
  </tr>
  
    <tr>
        <td width="41%" class="formtext">Device </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <select name="txtDeviceId" id="txtDeviceId">
      	<option value="0">Select Device</option>
      <?php
	  	$sql = "SELECT di_id,di_deviceId FROM tb_deviceinfo WHERE di_clientId=".$_POST[txtClientId];
		$rows = $db->query($sql);
		while ($clientRecord = $db->fetch_array($rows)) 
		{
			//echo $clientRecord[di_id];
			if($_POST[txtDeviceId] == $clientRecord[di_id])
				$select = 'selected="selected"';
			else
				$select  = '';
				
			echo '<option '.$select.' value='.$clientRecord[di_id].'>'.ucfirst($clientRecord[di_deviceId]).'</option>';
		}
	  ?>
      </select>
     </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Renewal Date From </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtRenewalDate" name="txtRenewalDate" value="<?php echo $subsRecord[tcs_renewalDateFrom];?>" />
      </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">No of Months </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <select name="txtNoofMonth" id="txtNoofMonth" autocomplete="off" onchange="showEndDate(document.frmClientSubs.txtRenewalDate.value,this.value)">
      <option value="0">Select No of Month</option>
		<?php
        for($k=1;$k<=30;$k++)
        {
            if($subsRecord[tcs_noOfMonths] == $k)
                $select= 'selected="selected"';
            else
                $select = '';
				
            echo '<option '.$select.' value='.$k.'>'.$k.'</option>';
        }
        ?>
        </select>End Date: <span id="txtEndDate"><?php echo $endDate;?></span>
     </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Remind me Before  </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtRemindDay" name="txtRemindDay" value="<?php echo $subsRecord[tcs_reminderDays];?>" />&nbsp;Days
      
      </td>
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Amount </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtSubsAmt" name="txtSubsAmt" value="<?php echo $subsRecord[tcs_amount];?>" />
      
      </td>
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Payment Type</td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <select name="selPayType" id="selPayType" onchange="showHideSubsTable(this.value)">
      <option value="0">Select Payment Type</option>
      <option <?php if($_POST[txtSubsId]!='') { if($subsRecord[tcs_payType] == "Cash") echo 'selected="selected"'; }?> value="Cash">Cash</option>
      <option <?php if($_POST[txtSubsId]!='') { if($subsRecord[tcs_payType] == "Cheque") echo 'selected="selected"'; }?> value="Cheque">Cheque</option>
      </select>
      
      </td>
  </tr>
 	<tr>
        <td width="41%" class="formtext">Cheque No </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <?php  if($subsRecord[tcs_payType] == "Cheque") { ?>
      <input type="text" id="txtChequeNo" name="txtChequeNo" value="<?php echo $subsRecord[tcs_chequeNo];?>" />
      <?php } else { ?>
      <input type="text" id="txtChequeNo" name="txtChequeNo" disabled="disabled" value="<?php echo $subsRecord[tcs_chequeNo];?>" />
      <?php } ?>
      </td>
  </tr>
  <tr><td colspan="3" align="center">
	  <?php
      if(isset($_POST[txtSubsId]) && $_POST[txtSubsId] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitUpdateSubs" id="cmdSubmitUpdateSubs" value="Update" class="click_btn" />  
      <?php } else { ?>
            <input type="submit" name="cmdSubmitAddSubs" id="cmdSubmitAddSubs" value="Submit" class="click_btn" onclick="return getValid()" />
                                         <input type="reset" value="Reset" class="click_btn" />
      <?php } ?>
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmClientSubs");
 
  frmvalidator.addValidation("txtDeviceId","dontselect=0","Please Select Device");
 
  frmvalidator.addValidation("txtRenewalDate","req","Please Select Renewal Date");

  frmvalidator.addValidation("txtNoofMonth","dontselect=0","Please select No of Months");
  
  frmvalidator.addValidation("txtRemindDay","maxlen=50");
  frmvalidator.addValidation("txtRemindDay","req","Please enter No. of Days to Remain");
  frmvalidator.addValidation("txtRemindDay","numeric","Please enter Days");
  
  frmvalidator.addValidation("txtSubsAmt","maxlen=50");
  frmvalidator.addValidation("txtSubsAmt","req","Please enter Amount");
  frmvalidator.addValidation("txtSubsAmt","numeric","Please enter Numbers");
  
  frmvalidator.addValidation("selPayType","dontselect=0","Please Select payment Type")
  
  function getValid()
  {
  	  if(document.frmClientSubs.selPayType.value == "Cheque")
	  {
		frmvalidator.addValidation("txtChequeNo","req","Please enter cheque no");
		frmvalidator.addValidation("txtChequeNo","numeric","Please enter Numbers");
		
	  }
	  return true;
  }
  
</script>   
</div><!-- PAGEarea END here -->
