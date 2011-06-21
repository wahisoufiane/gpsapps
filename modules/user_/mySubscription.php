<?php
//print_r($_POST);
$sql = "SELECT * FROM tb_client_subscription,tb_clientinfo,tb_deviceinfo WHERE di_id =tcs_deviceId AND tcs_isActive =1 AND tcs_deviceId= ".$_POST[txtDeviceId]." AND ci_id = tcs_clientId  AND tcs_clientId = ".$_POST[txtClientId]."  ORDER BY ci_clientName DESC";
$rows = $db->query($sql);
$record = $db->fetch_array($rows);

if($record[tcs_payType] == "Cheque")
	$payType = $record[tcs_amount]." (".$record[tcs_payType]." - ".$record[tcs_chequeNo].")";
else
	$payType = $record[tcs_amount]." (".$record[tcs_payType].")";
?>
<script language="javascript">
function funEditUser(uid,cid,did)
{
	document.frmSubmit.txtSubsId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.txtDeviceId.value = did;
	document.frmSubmit.action = '?ch=addSubscription';
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="center">
<table class="gridform_final" border="1">
    <tr>
        <td>Device Id   </td><td><?php echo ucfirst($util->getDeviceInfoByDeviceId($_POST[txtDeviceId]));?></td>
    </tr>
    <tr>
        <td>Renewal Date</td> <td><?php echo date("d-m-Y",strtotime($record[tcs_renewalDateFrom]));?> </td> 
    </tr>
    <tr>
        <td>Expiry Date</td> <td><?php echo date("d-m-Y",strtotime("-1 days ".($record[tcs_noOfMonths]) ."months ".$record[tcs_renewalDateFrom]))."(".$record[tcs_noOfMonths]." Months)";?> </td>
    </tr>
    <tr>
        <td>Reminder Days</td><td><?php echo $record[tcs_reminderDays];?> </td>
    </tr>
    <tr>
        <td>Amount & Type</td><td><?php echo $payType ;?> </td>
   </tr>
</table>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtSubsId" id="txtSubsId" />
   	<input type="hidden" name="txtClientId" id="txtClientId" />
    <input type="hidden" name="txtDeviceId" id="txtDeviceId" />
</form>