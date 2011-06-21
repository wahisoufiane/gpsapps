<?php
if(isset($_POST[txtGroupId]) && $_POST[txtGroupId] !='')
{
	$sql = "SELECT * FROM tb_groupinfo WHERE gp_id =".$_POST[txtGroupId];
	$rows = $db->query($sql);
	$deviceRecord = $db->fetch_array($rows);
}
?>
<script language="javascript">
function funEditGroup(gid,act)
{
	document.frmSubmit.txtGroupId.value = gid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}
</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers">List of Group</div>
<form name="frmEditGroup" id="frmEditGroup" action="?ch=Group" method="post">
<?php
if(isset($_POST[txtGroupId]) && $_POST[txtGroupId] !=''){
?>
	<input type="hidden" name="txtGroupId" id="txtGroupId" value="<?php echo $_POST[txtGroupId];?>"/>  
<?php } ?>
<table class="form1">
    <tr>
        <td width="41%" class="formtext">Group Name </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="text" id="txtGroupName" name="txtGroupName" value="<?php echo $deviceRecord[gp_groupName];?>"  />
     </td>
     
  </tr>
   
  <tr>
        <td width="41%" class="formtext">Description </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><textarea id="txtGPDesc" name="txtGPDesc" cols="40" rows="3"><?php echo $deviceRecord[gp_description];?></textarea>
     </td>
  </tr>
  </tr>
  <tr>
        <td width="41%" class="formtext">Status </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="checkbox" value="1" id="txtGPStatus" <?php if($_POST[txtGroupId]!='') { if($deviceRecord[gp_isActive] == 1) echo 'checked="checked"'; } else {echo 'checked="checked"';}  ?>  name="txtGPStatus" />
      </td>
  </tr>
  <tr><td colspan="3" align="center">
	  <?php
      if(isset($_POST[txtGroupId]) && $_POST[txtGroupId] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitUpdateGroup" id="cmdSubmitUpdateGroup" value="Update" class="click_btn" />  
      <?php } else { ?>
            <input type="submit" name="cmdSubmitAddGroup" id="cmdSubmitAddGroup" value="Submit" class="click_btn" />
                                         <input type="reset" value="Reset" class="click_btn" />
      <?php } ?>
  </td></tr>
                                     

</table>
        
</form>
<div align="center">
<?php
$sql = "SELECT * FROM tb_groupinfo
          WHERE gp_createdUserId =".$_SESSION[userID]."
          ORDER BY gp_groupName DESC";

$rows = $db->query($sql);

?>
<table class="gridform_final">
    <tr>
        <th>Group Name</th>
        <th>Description</th>
        <th>Status</th>
        <th>Members</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
   <?php
   	while ($record = $db->fetch_array($rows)) 
	{
		
	?>
		<tr>
        <td><?php echo ucfirst($record[gp_groupName]);?></td>
        <td><?php echo $record[gp_description];?> </td>
        <td><?php if($record[gp_isActive]) echo "Active"; else echo "NA";?> </td>
        <td><a href="#" onclick="funEditGroup('<?php echo $record[gp_id];?>','?ch=viewUser')">Add/View Members</a></span></td>
        <td><a href="#" onclick="funEditGroup('<?php echo $record[gp_id];?>','')">Edit</a></span></td>
        <td><span>Delete</span></td>
   </tr>
   <?php
	}
   ?>
</table>
</div>
</div>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmEditGroup");
 
  frmvalidator.addValidation("txtGroupName","req","Please enter Group Name");
  frmvalidator.addValidation("txtGroupName","minlen=5",	"Min length for Device ID is 5");
  
  frmvalidator.addValidation("txtGPDesc","req","Please enter Description number");
  frmvalidator.addValidation("txtGPDesc","maxlen=100");

</script>   

</div>
<form name="frmSubmit" id="frmSubmit" method="post">
	<input type="hidden" name="txtGroupId" id="txtGroupId" />
</form>