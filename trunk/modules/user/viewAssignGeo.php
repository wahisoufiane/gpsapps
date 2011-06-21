<?php
//print_r($_POST);

?>
<script language="javascript">
function funEditUser(uid,cid,act)
{
	document.frmSubmit.txtAssGeoId.value = uid;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
var ajax1=new sack();
function changeSts(id,span,uid)
{
	if(document.getElementById(id).checked)
		qry = "UPDATE tb_assigngeofence SET tag_isActive = 1 WHERE tag_id = "+uid;
	else
		qry = "UPDATE tb_assigngeofence SET tag_isActive = 0 WHERE tag_id = "+uid;
	ajax1.requestFile = 'ajax_server.php?ajaxQry='+qry;
	//alert(ajax1.requestFile);
	ajax1.onCompletion = function(){resultUserStatus(span,document.getElementById(id).checked)};
	ajax1.runAJAX();
}
function resultUserStatus(span,flag)
{
	//alert(ajax1.response)
  if(ajax1.response)
  {
	 alert("Status Updated Successfully.");
	 if(flag) 
	  	document.getElementById(span).innerHTML = 'Deactive';
	 else 
		document.getElementById(span).innerHTML = 'Active';
  }
  else
  {
	  alert("Status Not Updated.");
  }
	
}
</script>
<div class="pagearea">
<div align="left" class="listofusers">List of  / <a href="#" onclick="location.href='?ch=assignGeofence'">Assign Geofence</a></div>
<div align="center">
<table class="gridform_final">
    <tr>
        <th>#   </th>
        <th>Geofence Name(s)</th>
        <th>Alert Type</th> 
        <th>Mobile/Email</th>
        <th>No of Times</th>
        <th>Do Not Dist. Time</th>
        <th>Active</th>
        <th>Edit</th>
   </tr>
   <?php
   $sql = "SELECT * FROM tb_assigngeofence WHERE tag_clientId =".$_SESSION[clientID];

   $rows = $db->query($sql);
   if($db->affected_rows > 0)
   {
	$i = 0;
   	while ($record = $db->fetch_array($rows)) 
	{
		
		/*$geoArr = explode("#",$record[tag_geofenceId]);
		
		for($v = 0; $v < count($geoArr)-1; $v++)
		{
			*/$sql1 = "SELECT * FROM tb_geofence_info WHERE tgi_id = ".$record[tag_geofenceId]." ORDER BY tgi_name DESC";
			$res1 = $db->query($sql1);
			$fet1 = $db->fetch_array($res1);
			//print_r($record);
			$geoNames = $fet1[tgi_name]; 
		//}
		
	?>
		<tr>
        <td><?php echo ++$i;?></td>
        <td><?php echo ucfirst($geoNames);?></td>
        <td><?php echo $record[tag_inout];?> </td> 
        <td><?php echo $record[tag_alertSrc];?> </td>
        <td><?php echo $record[tag_noofTimes];?> </td>
        <td><?php echo ucfirst($record[tag_donotTime]);?></td>
        <td>
        <input type="checkbox" name="chkUsrStatus<?php echo $l;?>" id="chkUsrStatus<?php echo $l;?>" <?php if($record[tag_isActive] == 1) {?> checked="checked" <?php } ?> onclick="changeSts(this.id,'stsId<?php echo $i;?>','<?php echo $record[tag_id];?>')"/>
        <span id="stsId<?php echo $i;?>"><?php if($record[tag_isActive]) echo "Deactive"; else echo "Active"; ?></span>
        </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[tag_id];?>','<?php echo $_POST[clientID];?>','?ch=assignGeofence')">Edit</a></span></td>
   </tr>
    <?php
	$i++;
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
	<input type="hidden" name="txtAssGeoId" id="txtAssGeoId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
</form>