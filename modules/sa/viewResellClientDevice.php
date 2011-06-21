<?php
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
?>
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

<script language="javascript">
function funEditUser(uid,cid,act)
{
	document.frmSubmit.txtDeviceId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>
<div class="pagearea">
<!--<div align="left" class="listofusers">List of  / <a href="#" onclick="funEditUser('','<?php echo $_POST[txtClientId];?>','?ch=addClientDevice')">Add Device</a></div>
--><div align="center">
<div id="container">			
  <div id="demo">
<table cellpadding="0" cellspacing="0" border="0" class="gridform_final" id="example">
  <thead>
    <tr>
        <th>Device ID   </th>
        <th>Client Name</th>
        <th>IMEI</th> 
        <th>Device Name</th>
        <th>Mobile No</th>
        <th>Device Model</th>
        <th>C. Date</th>
        <th>Sub. Exp. Date</th>
   </tr>
 </thead>
 <tbody>
   <?php
   $sql = "SELECT * FROM tb_deviceinfo,tb_clientinfo WHERE ci_id = di_clientId AND ci_clientId =".$_POST[txtClientId]." ORDER BY ci_clientName,di_deviceName,di_deviceId DESC";

   $rows = $db->query($sql);
   if($db->affected_rows > 0)
   {
	   $i = 0;
   	while ($record = $db->fetch_array($rows)) 
	{
		$getSubs = "SELECT * FROM tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = ".$record[di_id];	
		$resSubs = $db->query($getSubs);
		if($db->affected_rows >0)
		{
			$fetSubs = $db->fetch_array($resSubs);
			$renewDate = date("Y-m-d",strtotime("-1 days ".($fetSubs[tcs_noOfMonths]) ."months ".$fetSubs[tcs_renewalDateFrom]));
			$day = dateDiff($renewDate,date("Y-m-d"));
			if(0 < $day)
			$expDays = "Expired ".$day;
			else
			$expDays = "Yet to Expire ".$day;
		}
		else
		{
			$expDays ="Nil";
		}
	?>
    
	<tr class="<?php echo $className;?>" >
        <td><?php echo ucfirst($record[di_deviceId]);?></td>
        <td><?php echo ucfirst($record[ci_clientName]);?></td>
        <td><?php echo $record[di_imeiId];?> </td> 
        <td><?php echo ucfirst($record[di_deviceName]);?></td>
        <td><?php echo $record[di_mobileNo];?> </td>
        <td><?php echo ucfirst($record[di_deviceModel]);?></td>
        <td><?php echo date("d-m-Y",strtotime($record[di_createDate]));?> </td>
        <td><?php echo date("d-m-Y",strtotime($renewDate));?></td>
   </tr>
  
    <?php
	$i++;
	}
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
	<input type="hidden" name="txtDeviceId" id="txtDeviceId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
</form>