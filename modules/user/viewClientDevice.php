<?php
//print_r($_POST);
if(isset($_POST[del]) && $_POST[del]!=""){
	//echo 'fsfsfs';exit;
	$q1= mysql_query("DELETE FROM tb_deviceinfo where di_id='".$_POST[frmDeldevice]."'");
	$q2= mysql_query("DELETE FROM tb_client_subscription where tcs_deviceId='".$_POST[frmDeldevice]."'");
	 header("location:?ch=viewClientDecive");
    exit;
}
?>
<script language="javascript">
function funEditUser(uid,cid,act)
{
	document.frmSubmit.txtDeviceId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
function deleteDevice(cid,did,act)
{
  if (confirm("Are you sure you want to delete")) {
	document.frmSubmit.txtClientId.value = cid;
	  document.frmSubmit.frmDeldevice.value = did;
	document.frmSubmit.action = act;
		document.frmSubmit.del.value = 'delete';

	document.frmSubmit.submit();
  }
}
</script>
<div class="pagearea">
<div align="left" class="listofusers">List of  / <a href="#" onclick="funEditUser('','<?php echo $_POST[txtClientId];?>','?ch=addClientDevice')">Add Device</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Device ID   </th>
        <th>Client Name</th>
        <th>IMEI</th> 
        <th>Mobile No</th>
        <th>Device Model</th>
        <th>Expiry Date</th>
        <th>Edit</th>
        <th>Add/View Subs.</th>
        <th>Delete</th>
   </tr>
   <?php
   //$sql = "SELECT * FROM tb_deviceinfo,tb_clientinfo WHERE ci_id = di_clientId AND di_clientId =".$_POST[txtClientId]." ORDER BY di_deviceId DESC";
   $sql = "SELECT * FROM tb_deviceinfo, tb_client_subscription, tb_clientinfo WHERE tcs_isActive =1 AND tcs_deviceId = di_id AND di_status =1 AND di_clientId = ci_id AND di_clientId =".$_POST[txtClientId]." ORDER BY di_deviceId ASC";

   $rows = $db->query($sql);
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		if($record[di_status])
		{
		
	?>
		<tr>
        <td><?php echo ucfirst($record[di_deviceId]);?></td>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo $record[di_imeiId];?> </td> 
        <td><?php echo $record[di_mobileNo];?> </td>
        <td><?php echo ucfirst($record[di_deviceModel]);?></td>
        <td><?php echo date('d-m-Y',strtotime($record[tcs_renewalDateFrom]));?></td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[di_id];?>','<?php echo $_POST[txtClientId];?>','?ch=addClientDevice')">Edit Device</a></span></td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[di_id];?>','<?php echo $_POST[txtClientId];?>','?ch=viewSubscription')">View Subs.</a></span></td>
        <td>
		 <?php if(strtotime(date("d-m-Y")) > strtotime($record[tcs_renewalDateFrom])){?>
		<a class="error_strings" href="#" onclick="deleteDevice('<?php echo $record[ci_id];?>','<?php echo $record[di_id];?>','?ch=viewClientDevice','del')">Delete</a>
		<?php }?>
		</td>
   </tr>
    <?php
		}
	}
	}
	else
	{
   ?>
		<tr>
        <td colspan="9"><span>Not data found</span></td>
   </tr>
   <?php
	}
   ?>
</table>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtDeviceId" id="txtDeviceId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />

	<input type="hidden" name="frmDeldevice" id="frmDeldevice" />
	<input type="hidden" name="del" id="del"/>

</form>