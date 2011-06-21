<style type="text/css" title="currentStyle">
	@import "../media/css/demo_table.css";
	@import "media/css/TableTools.css";
</style>

<script type="text/javascript" charset="utf-8" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf-8" src="media/ZeroClipboard/ZeroClipboard.js"></script>
<script type="text/javascript" charset="utf-8" src="media/js/TableTools.js"></script>
<script type="text/javascript" charset="utf-8">
	$(document).ready( function () {
		/* You might need to set the sSwfPath! Something like:
		 *   TableToolsInit.sSwfPath = "/media/swf/ZeroClipboard.swf";
		 */
		$('#example').dataTable( {
			"sDom": 'T<"clear">lfrtip'
		} );
	} );
</script>
<?php
$sql = "SELECT * FROM tb_clientinfo WHERE ci_clientId != 0 ORDER BY ci_clientName ASC";
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
<!--<div align="left" class="listofusers">List of  / <a href="#" onclick="location.href='?ch=addClient';">Add New</a></div>
--><div align="center">
<div id="container">			
  <div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="gridform_final" id="example">
  <thead>
    <tr>
        <th>Client Name   </th>
        <th>Reseller Name   </th>
        <th>Land Line</th> 
        <th>Mobile </th>
        <th>Email 1</th>
        <th>Email 2</th>
        <th>C. Date</th>
        <th>Tot. Device - View</th>
        <th>Edit</th>
   </tr>
    </thead>
 <tbody>

   <?php
   if($db->affected_rows > 0)
   {
	   $i = 0;
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
        <td><?php echo $record[ci_email1];?> </td>
        <td><?php echo $record[ci_email2];?> </td>
        <td><?php echo date("d-m-Y",strtotime($record[ci_createdDate])) ;?> </td>
        <td><?php echo $avail;?><!--<a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=addClientDevice')">Add </a></span> /--> - <a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=viewClientDevice')">View </a></span></td>
        
        <td><a href="#" onclick="funEditUser('<?php echo $record[ci_id];?>','?ch=addClient')">Edit</a></span></td>
   </tr>
   <?php
   $i++;
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
   </tbody>
</table>
</div>
</div>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtClientId" id="txtClientId" />
</form>
<form name="frmClientType" id="frmClientType" method="post">
	<input type="hidden" name="txtClientType" id="txtClientType" />
</form>