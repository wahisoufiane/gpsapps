<?php
//print_r($_POST);	
if(isset($_POST[all_form_ids]) && $_POST[all_form_ids]!='')
{
	if(isset($_POST[all_form_ids]) && $_POST[all_form_ids]!='')
	{
		$form_ids_array = explode('#',$_POST[all_form_ids]);
		$ct1 = count($form_ids_array)-1;
		for($s=0;$s<count($form_ids_array);$s++)
		{
			if(isset($_POST["form_id_".$form_ids_array[$s]]))
			{
				
				$assignForm="UPDATE tb_userinfo SET ui_groupId = ".$_POST[selGroup]." WHERE ui_id = ".$form_ids_array[$s]; 
				$chk=mysql_query($assignForm);
				
				if($chk)
					header("location:?ch=status&au=2&msg=".$chk);
				else
					header("location:?ch=status&au=2&msg=".$chk);
			}
			
		}
	}
}
?>
<script language="javascript">
function funEditUser(uid,did,act)
{
	//alert(did)
	document.frmSubmit.txtUserId.value = uid;
	if(did)
	document.frmSubmit.txtDeviceId.value = did;
	/*else
	document.frmSubmit.txtDeviceId.value = document.getElementById('selDeviceId').value;*/
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
function checkAllForm()
{
	var form_ids_array = new Array();
	form_ids_array = document.frmFormList.all_form_ids.value.split('#');
	var tr;

	if(document.frmFormList.form_del_all.checked == true)
		tr = true;
	else if(document.frmFormList.form_del_all.checked == false)
		tr = false;
	for(var m=0;m<(form_ids_array.length)-1;m++)
	{
		/*if(document.getElementById('form_id_'+form_ids_array[m]).disabled == true)
			document.getElementById('form_id_'+form_ids_array[m]).disabled = tr;*/
		
		document.getElementById('form_id_'+form_ids_array[m]).checked=tr;
	}
}
//DELETE VEHICLE DETAILS
function assignForm(all_form_ids)
{
	
	var del_form_ids = new Array();
	del_form_ids = all_form_ids.split('#');
	var flag = 0;
	for(var m=0;m<(del_form_ids.length)-1;m++)
	{
		if(document.getElementById('form_id_'+del_form_ids[m]).checked == true)
		flag = 1;
	}
	if(flag == 0)
	{
		alert("Select atleast one Record");
		return false;
	}
	else
	{
		t=confirm("Are you sure to Assign?");
		if(t)
		{
			document.frmFormList.all_form_ids.value=all_form_ids;
			document.frmFormList.submit();
			return true;
		}
		else
		{
			return false;
		}
	}
	
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of Users <?php if($recordUserInfo[ui_roleId] != "1") { ?>/ <a href="#" onclick="location.href='?ch=addUser';">Add New</a><?php } ?></div>
<div align="center">
<form name="frmFormList" id="frmFormList" method="post" action="">
<table class="gridform_final">
<?php if($recordUserInfo[ui_roleId] != "1") { ?>
<tr>
<td colspan="2" align="left">
<select name="selGroup" id="selGroup">
<option value="0">Select Group</option>
<?php
$sql = "SELECT * FROM tb_groupinfo
          WHERE gp_createdUserId =".$_SESSION[userID]."
          ORDER BY gp_groupName DESC";

$rows = $db->query($sql);
while ($record = $db->fetch_array($rows)) 
{
		if($_POST[txtGroupId] == $record[gp_id])
			$sel = 'selected="seleted"';
		else
			$sel = '';
	?>
    	<option <?php echo $sel;?> value="<?php echo $record[gp_id];?>"><?php echo ucfirst($record[gp_groupName]);?></option>
    <?php
}
?>
</select>
</td>
<td><input type="button" name="cmdAssignForm" id="cmdAssignForm" class="go_button" value="Assign" onclick="assignForm(document.getElementById('all_form_ids').value);" />
</td>
<td colspan="9">&nbsp;</td>
</tr>
<?php } ?>
    <tr>
		<?php if($recordUserInfo[ui_roleId] != "1") { ?>
    	<th width="5%"><input type="checkbox" name="form_del_all" id="form_del_all" onclick="checkAllForm();" /></th>
		<?php } ?>
        <th>Name   </th>
        <th>Username</th>
        <th>Role</th> 
        <th>Group Name</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Device</th>
        <th>Access</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
   <?php
   if(($recordUserInfo[ci_clientType] == "Client") && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1") {
   		 $sql = "SELECT * FROM tb_userinfo
			  WHERE ui_id !=".$_SESSION[userID]." AND ui_isAdmin !=1 AND ui_groupId = ".$recordUserInfo[ui_groupId]."
			  AND ui_clientId =".$_SESSION[clientID]."
			  ORDER BY ui_firstname DESC";
   }
   else
   {
		$sql = "SELECT * FROM tb_userinfo
			  WHERE ui_id !=".$_SESSION[userID]."
			  AND ui_clientId =".$_SESSION[clientID]."
			  ORDER BY ui_firstname DESC";
   }
  
	$rows = $db->query($sql);
    if($db->affected_rows > 0)
    {
   	while ($record = $db->fetch_array($rows)) 
	{
		if($record[ui_roleId])
			$roleName = $util->getRoleNameOfUserByRoleId($record[ui_roleId]);
		else
			$roleName = "NIL";
			
		if($record[ui_groupId])
			$groupName = $util->getGroupNameOfUserByRoleId($record[ui_groupId]);
		else	
			$groupName = "NIL";
		
		
		$dev= $util->getDeviceInfoByUserId($record[ui_id]);
		if($dev[di_deviceName])
			$device = $dev[di_deviceName];
		else
			$device = $dev[di_deviceId];
		
		if($_POST[txtUserId] == $record[ui_id]) 
		{
		$getUserDevice = "SELECT * FROM tb_deviceinfo WHERE (di_assignedUserId = 0 or di_assignedUserId = $record[ui_id])AND di_clientId = ".$_SESSION[clientID]." ORDER BY di_deviceName,di_deviceId ASC";
		$rowUserDevice = $db->query($getUserDevice);
		if($db->affected_rows > 0)
		{
			$device = '<select name="selUserDevice" id="selUserDevice" onchange="document.frmSubmit.txtDeviceId.value= this.value">';
			$device .= '<option value="0" style="width:50%">Select Device</option>';
			while ($fetUserDevice = $db->fetch_array($rowUserDevice)) 
			{
				if($fetUserDevice[di_deviceName])
					$devName = $fetUserDevice[di_deviceName];
				else
					$devName = $fetUserDevice[di_deviceId];
					
				if($dev[di_id] == $fetUserDevice[di_id])
					$sel = 'selected="selected"';
				else
					$sel ='';
				$device .= '<option '.$sel.' value="'.$fetUserDevice[di_id].'">'.$devName.'</option>';
			}
			$device .= '</select><input type="hidden" name="selDeviceId" id="selDeviceId" value="'.$dev[di_id].'" />';	
		}
		}
		if(!$device)
		{
			$device = 'Not Assigned';
		}
			
	?>
		<tr>
        <?php if($recordUserInfo[ui_roleId] != "1") { ?>
        <td><input type="checkbox" <?php echo $check;?> name="form_id_<?php echo $record[ui_id]; ?>" id="form_id_<?php echo $record[ui_id]; ?>" /></td>
        <?php } ?>        
        <td><?php echo ucfirst($record[ui_firstname]." ".$record[ui_lastname]);?></td>
        <td><?php echo ucfirst($record[ui_username]);?> </td> 
        <td><?php echo ucfirst($roleName);?> </td> 
        <td><?php echo ucfirst($groupName);?> </td> 
        <td><?php echo $record[ui_mobile];?> </td>
        <td><?php echo $record[ui_email];?> </td>
        <td>
        <?php if($_POST[txtUserId] == $record[ui_id])   { ?>
		<?php echo $device;?>&nbsp;<a href="#" onclick="funEditUser('<?php echo $_POST[txtUserId];?>','','?ch=User')">Assign</a> | <a href="#" onclick="location.href='?ch=viewUser';">Cancel</a></span>
        <?php } else { ?>
        <a href="#" onclick="funEditUser('<?php echo $record[ui_id];?>','<?php echo $dev[di_id];?>','')"><?php echo $device;?></a> 
        <?php } ?>
        </td>
        <td><?php if($record[ui_accessFlag]) echo "Allowed"; else echo "NA";?> </td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[ui_id];?>','','?ch=addUser')">Edit</a></span></td>
        <td><span>Delete</span></td>
   </tr>
    <?php
	$assignIds.=$record[ui_id]."#";
	$i++;
	}
	}
	else
	{
   ?>
		<tr>
        <td colspan="11"><span>Not data found</span></td>
   </tr>
   <?php
	}
   ?><input type="hidden" name="all_form_ids" id="all_form_ids" value="<?php echo $assignIds; ?>" />
</table>
</form>
</div>
</div>
</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtUserId" id="txtUserId" />
    <input type="hidden" name="txtDeviceId" id="txtDeviceId" />
</form>