<?php
//print_r($_POST);
$sql = "SELECT * FROM tb_reseller_subscription,tb_clientinfo WHERE ci_id = trs_clientId  AND trs_clientId = ".$_POST[txtClientId]."  ORDER BY ci_clientName ASC";
$rows = $db->query($sql);

?>
<script language="javascript">
function funEditUser(sid,cid,act)
{
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.txtSubsId.value = sid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of Users / <a href="#" onclick="funEditUser('','<?php echo $_POST[txtClientId];?>','?ch=addResellerSubscription')">Add New</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Reseller Name</th>
        <th>Renewal Date</th> 
        <th>Expiry Date</th>
        <th>Reminder Days</th>
        <th>Amount <br /> Type</th>
        <th>Edit Subs</th>
        <th>Delete</th>
   </tr>
   <?php
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		//echo date("d-m-Y",strtotime("-1 days ".($record[trs_noOfMonths]) ."months ".$record[trs_renewalDateFrom]));
		if($record[trs_payType] == "Cheque")
			$payType = $record[trs_amount]." (".$record[trs_payType]." - ".$record[trs_chequeNo].")";
		else
			$payType = $record[trs_amount]." (".$record[trs_payType].")";
	?>
		<tr>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo date("d-m-Y",strtotime($record[trs_renewalDateFrom]));?> </td> 
        <td><?php echo date("d-m-Y",strtotime("-1 days ".($record[trs_noOfMonths]) ."months ".$record[trs_renewalDateFrom]))."(".$record[trs_noOfMonths]." Months)";?> </td>
        <td><?php echo $record[trs_reminderDays];?> </td>
        <td><?php echo $payType;?> </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[trs_id];?>','<?php echo $record[trs_clientId];?>','?ch=addResellerSubscription')">Edit Subs</a></span></td>
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
</form>