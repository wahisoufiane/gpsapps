<?php
$sql = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_isAdmin = 1 AND ci_id = ui_clientId ORDER BY ui_firstname DESC";

$rows = $db->query($sql);

?>
<script language="javascript">
function funEditUser(uid,cid)
{
	document.frmSubmit.txtUserId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = '?ch=addAdmin';
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of Users / <a href="#" onclick="location.href='?ch=addAdmin';">Add New</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Client Name</th>
        <th>Username</th> 
        <th>Mobile</th>
        <th>Email</th>
        <th>Access</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
   <?php
   $i = 0;
   	while ($record = $db->fetch_array($rows)) 
	{
		
	?>
		<tr>
        <td><?php echo $i+1;?></td>
        <td><?php echo ucfirst($record[ui_firstname]." ".$record[ui_lastname]);?></td>
        <td><?php echo ucfirst($record[ci_clientName]." (".$record[ci_clientType].")");?></td>
        <td><?php echo ucfirst($record[ui_username]);?> </td> 
        <td><?php echo $record[ui_mobile];?> </td>
        <td><?php echo $record[ui_email];?> </td>
        <td><?php if($record[ui_accessFlag]) echo "Allowed"; else echo "NA";?> </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[ui_id];?>','<?php echo $record[ui_clientId];?>')">Edit</a></span></td>
        <td><span>Delete</span></td>
   </tr>
   <?php
   $i++;
	}
   ?>
</table>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtUserId" id="txtUserId" />
   	<input type="hidden" name="txtClientId" id="txtClientId" />
</form>