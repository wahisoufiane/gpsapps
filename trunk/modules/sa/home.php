<?php
$sql = "SELECT * FROM tb_clientinfo where ci_clientId =0 ORDER BY ci_clientName DESC";
$rows = $db->query($sql);
?>
<script language="javascript">
function funEditUser(uid)
{
	document.frmSubmit.txtClientId.value = uid;
	document.frmSubmit.action = '?ch=addClient';
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div class="listofusers"><strong>Total - Resellers : <?php echo $db->affected_rows;?></strong></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Reseller Name   </th>
        <th>Phone 1</th>
        <th>Phone 2</th>
        <th>Email 1</th>
        <th>Email 2</th>
        <th>Tot. Clients</th> 
        <th>Tot. Devices</th>
        <th>Edit</th>
   </tr>
   <?php
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		   $getResellDev = "select * from tb_deviceinfo,tb_clientinfo where di_clientId = ci_id AND ci_clientId = ".$record[ci_id];
		   $resResellDev = $db->query($getResellDev);
		   $totDev = $db->affected_rows;
		   
		   $getResellClient = "select * from tb_clientinfo where ci_clientId = ".$record[ci_id];
		   $resResellClient = $db->query($getResellClient);
		   $totClient = $db->affected_rows;
	?>
		<tr>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo $record[ci_phoneNumber];?> </td>
        <td><?php echo $record[ci_mobileNumer];?> </td>
        <td><a href="mailto:<?php echo $record[ci_email1];?>"><?php echo $record[ci_email1];?></a></td>
        <td><a href="mailto:<?php echo $record[ci_email2];?>"><?php echo $record[ci_email2];?></a></td>
        <td><?php echo $totClient;?> </td>
        <td><?php echo $totDev;?> </td>        
        <td><a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>')">Edit</a></span></td>
   </tr>
   <?php
   	$avail = 0;
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
<br />
<br />
<?php
	$sql = "SELECT * FROM tb_clientinfo  where ci_clientId !=0 ORDER BY ci_clientName DESC";
	$rows = $db->query($sql);
?>
<div class="listofusers"><strong>Total - Client : <?php echo $db->affected_rows;?></strong></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Client Name   </th>
        <th>Reseller Name   </th>
        <th>Phone 1</th> 
        <th>Phone 2</th>
        <th>Email 1</th>
        <th>Email 2</th>
        <th>Limit</th>
        <th>Edit</th>
   </tr>
   <?php
  
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
		$getDeviceCount = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$record[ci_id];
		$rowCount = $db->query($getDeviceCount);
		$avail = $db->affected_rows;
		
		if($record[ci_clientId] != 0)
		{
		$getClient = "SELECT ci_clientName FROM tb_clientinfo WHERE ci_id = ".$record[ci_clientId];
	    $rowClient = $db->query($getClient);
		$recordClient = $db->fetch_array($rowClient);
		$cltName = $recordClient[ci_clientName];
		}
		else $cltName = "Nil";
		
	?>
		<tr>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo ucfirst($cltName);?></td>
        <td><?php echo $record[ci_phoneNumber];?> </td>
        <td><?php echo $record[ci_mobileNumer];?> </td>
        <td><a href="mailto:<?php echo $record[ci_email1];?>"><?php echo $record[ci_email1];?></a></td>
        <td><a href="mailto:<?php echo $record[ci_email2];?>"><?php echo $record[ci_email2];?></a></td>
        <td><?php echo $avail;?> </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>')">Edit</a></span></td>
   </tr>
   <?php
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
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtClientId" id="txtClientId" />
</form>