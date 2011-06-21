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
				
				/*$assignForm="UPDATE tb_userinfo SET ui_groupId = ".$_POST[selGroup]." WHERE ui_id = ".$form_ids_array[$s]; 
				$chk=mysql_query($assignForm);
				
				if($chk)
					header("location:?ch=status&au=2&msg=".$chk);
				else
					header("location:?ch=status&au=2&msg=".$chk);*/
			}
			
		}
	}
}
?>
<script language="javascript">
function funEditUser(uid,did,aid,act)
{
	//alert(uid)
	//alert(document.getElementById('selDeviceId').value)
	document.frmSubmit.txtUserId.value = uid;
	//if(did)
	document.frmSubmit.txtDeviceId.value = did;
	document.frmSubmit.txtAllDeviceId.value = aid;
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
function dispDeviceID()
{
	var result = ""; 
	for (var i = 0; i < document.getElementById('selUserDevice').length; i++) { 
			if ( document.getElementById('selUserDevice').options[i].selected) { 
				result += document.getElementById('selUserDevice').options[i].value+","; 
			} 
		} 
		document.getElementById('selDeviceId').value = result;
	
}
var ajax1=new sack();
function changeSts(id,span,uid)
{
	if(document.getElementById(id).checked)
		qry = "UPDATE tb_userinfo SET ui_accessFlag = 1 WHERE ui_id = "+uid;
	else
		qry = "UPDATE tb_userinfo SET ui_accessFlag = 0 WHERE ui_id = "+uid;
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
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of Users <?php if($recordUserInfo[ui_roleId] != "1") { ?>/ <a href="#" class="error_strings" onclick="location.href='?ch=addUser';">Add New</a><?php } ?></div>
<div align="center">
<form name="frmFormList" id="frmFormList" method="post" action="">
<table class="gridform_final">
    <tr>
        <th>Name   </th>
        <th>Username</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Device</th>
        <th>Access</th>
        <th>Edit</th>
   </tr>
   <?php
  $sql = "SELECT * FROM tb_userinfo  WHERE ui_id !=".$_SESSION[userID]."  AND ui_clientId =".$_SESSION[clientID]." ORDER BY ui_firstname DESC";
	$rows = $db->query($sql);
    if($db->affected_rows > 0)
    {
   	while ($record = $db->fetch_array($rows)) 
	{
		
		$dev= $util->getDeviceInfoByUserId($record[ui_id]);
		$device = "<table>";
		while($fetDeviceId = mysql_fetch_array($dev))
		{
			if($fetDeviceId[di_deviceName])
				$device .= "<tr><th>".$fetDeviceId[di_deviceName]."</th></tr>";
			else
				$device .= "<tr><th>".$fetDeviceId[di_deviceId]."</th></tr>";
		}
		$device .= "</table>";
		
		if($_POST[txtUserId] == $record[ui_id]) 
		{
		$getUserDevice = "SELECT * FROM tb_deviceinfo WHERE (di_assignedUserId = 0 or di_assignedUserId = $record[ui_id])AND di_clientId = ".$_SESSION[clientID]." ORDER BY di_deviceName,di_deviceId ASC";
		$rowUserDevice = $db->query($getUserDevice);
		if($db->affected_rows > 0)
		{
			$device = '<select name="selUserDevice" style="position:absolute;" id="selUserDevice" multiple="multiple" onchange="dispDeviceID();">';
			while ($fetUserDevice = $db->fetch_array($rowUserDevice)) 
			{
				if($fetUserDevice[di_deviceName])
					$devName = $fetUserDevice[di_deviceName];
				else
					$devName = $fetUserDevice[di_deviceId];
				
				$allIds .=$fetUserDevice[di_id].",";	
				if($record[ui_id] == $fetUserDevice[di_assignedUserId])
				{
					$sel = 'selected="selected"';
					$ids .= $fetUserDevice[di_id].",";
					
				}
				else
				{
					$sel ='';
				}
				$device .= '<option '.$sel.' value="'.$fetUserDevice[di_id].'">'.$devName.'</option>';
			}
			$device .= '</select><input type="hidden" name="selDeviceId" id="selDeviceId" value="'.$ids.'" /><input type="hidden" name="allDeviceId" id="allDeviceId" value="'.$allIds.'" />';	
		}
		}
		if(!$device)
		{
			$device = 'Not Assigned';
		}
		
		if($record[ui_accessFlag])
		{
		}
		
			
	?>
		<tr>
        <td><?php echo ucfirst($record[ui_firstname]." ".$record[ui_lastname]);?></td>
        <td><?php echo ucfirst($record[ui_username]);?> </td> 
        <td><?php echo $record[ui_mobile];?> </td>
        <td><?php echo $record[ui_email];?> </td>
        <td>
        <?php if($_POST[txtUserId] == $record[ui_id])   { ?>
		<a href="#" onclick="funEditUser('<?php echo $_POST[txtUserId];?>',document.getElementById('selDeviceId').value,document.getElementById('allDeviceId').value,'?ch=User')">Assign</a> | <a href="#" onclick="location.href='?ch=viewUser';">Cancel</a><br /><?php echo $device;?>
        <?php } else { ?>
        <a href="#" onclick="funEditUser('<?php echo $record[ui_id];?>','','','?ch=viewUser')" onmouseover="document.getElementById('dev<?php echo $i;?>').style.display='block';" onmouseout="document.getElementById('dev<?php echo $i;?>').style.display='none';">Devices<span style="display:none; position:absolute" id="dev<?php echo $i;?>"><?php echo $device;?></span></a> 
        <?php } ?>
        </td>
        <td>
            <input type="checkbox" name="chkUsrStatus" id="chkUsrStatus" <?php if($record[ui_accessFlag]) {?> checked="checked" <?php } ?> onclick="changeSts(this.id,'stsId<?php echo $l;?>','<?php echo $record[ui_id];?>')"/>
            <span id="stsId<?php echo $l;?>"><?php if($record[ui_accessFlag]) echo "Deactive"; else echo "Active"; ?></span></td>
        <td><a href="#" onclick="funEditUser('<?php echo $record[ui_id];?>','','','?ch=addUser')">Edit</a></span></td>
   </tr>
    <?php
	$device = "";
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
    <input type="hidden" name="txtAllDeviceId" id="txtAllDeviceId" />
</form>