<?php
//print_r($_POST);
$sql = "SELECT * FROM tb_client_subscription,tb_clientinfo WHERE ci_id = tcs_clientId  AND tcs_clientId = ".$_POST[txtClientId]." AND tcs_deviceId = ".$_POST[txtDeviceId]." ORDER BY ci_clientName DESC";
$rows = $db->query($sql);

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
<div align="left" class="listofusers">List of Users / <a href="#" onclick="funEditUser('','<?php echo $_POST[txtClientId];?>','<?php echo $_POST[txtDeviceId];?>')">Add New</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Client Name</th>
        <th>Device Id   </th>
        <th>Renewal Date</th> 
        <th>Expiry Date</th>
        <th>Reminder Days</th>
        <th>Amount <br /> Type</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
   <?php
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		//echo date("d-m-Y",strtotime("-1 days ".($record[tcs_noOfMonths]) ."months ".$record[tcs_renewalDateFrom]));
		if($record[tcs_payType] == "Cheque")
			$payType = $record[tcs_amount]." (".$record[tcs_payType]." - ".$record[tcs_chequeNo].")";
		else
			$payType = $record[tcs_amount]." (".$record[tcs_payType].")";
	?>
		<tr>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo ucfirst($util->getDeviceInfoByDeviceId($record[tcs_deviceId]));?></td>
        <td><?php echo date("d-m-Y",strtotime($record[tcs_renewalDateFrom]));?> </td> 
        <td><?php echo date("d-m-Y",strtotime("-1 days ".($record[tcs_noOfMonths]) ."months ".$record[tcs_renewalDateFrom]))."(".$record[tcs_noOfMonths]." Months)";?> </td>
        <td><?php echo $record[tcs_reminderDays];?> </td>
        <td><?php echo $payType;?> </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[tcs_id];?>','<?php echo $record[tcs_clientId];?>','<?php echo $record[tcs_deviceId];?>')">Edit</a></span></td>
        <td><span>Delete</span></td>
   </tr>
    <?php
	}
	}
	else
	{
   ?>
		<tr>
        <td colspan="8"><span>Not data found</span></td>
   </tr>
   <?php
	}
   ?>
</table>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtSubsId" id="txtSubsId" />
   	<input type="hidden" name="txtClientId" id="txtClientId" />
    <input type="hidden" name="txtDeviceId" id="txtDeviceId" />
</form>