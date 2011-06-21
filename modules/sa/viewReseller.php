<?php
/*if(!isset($_POST[txtClientType]))
	$_POST[txtClientType] = 'Reseller';*/
//print_r($_POST);

$sql = "SELECT * FROM tb_clientinfo where ci_clientId =0 ORDER BY ci_clientName DESC";
$rows = $db->query($sql);

?>
<script language="javascript">
function callDiffClient(clientType)
{
	document.frmClientType.txtClientType.value = clientType;
	document.frmClientType.submit();
}
function funEditUser(uid,act)
{
	document.frmSubmit.txtClientId.value = uid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of / <a href="#" onclick="location.href='?ch=addReseller';">Add New</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>Reseller Name   </th>
        <th>Land Line</th> 
        <th>Mobile </th>
        <th>Email 1</th>
        <th>Email 2</th>
        <th>C. Date</th>
        <th>View Device & Client</th>
        <th>Edit</th>
   </tr>
   <?php
   if($db->affected_rows > 0)
   {
   	while ($record = $db->fetch_array($rows)) 
	{
	?>
		<tr>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo $record[ci_phoneNumber];?> </td>
        <td><?php echo $record[ci_mobileNumer];?> </td>
        <td><a href="mailto:<?php echo $record[ci_email1];?>"><?php echo $record[ci_email1];?></a></td>
        <td><a href="mailto:<?php echo $record[ci_email2];?>"><?php echo $record[ci_email2];?></a></td>
        <td><?php echo date("d-m-Y",strtotime($record[ci_createdDate])) ;?> </td>
        <td><!--<a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=addResellerSubscription')">Add</a></span> / --><a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=viewResellClientDevice')">view </a></span></td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=addReseller')">Edit Reseller</a></span></td>
   </tr>
   <?php
   	//$avail = 0;
   //}
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
	<input type="hidden" name="txtClientId" id="txtClientId" />
</form>
<form name="frmClientType" id="frmClientType" method="post">
	<input type="hidden" name="txtClientType" id="txtClientType" />
</form>