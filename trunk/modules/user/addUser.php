<?php
//print_r($_POST);
if(isset($_POST[txtUserId]) && $_POST[txtUserId] !='')
{
	$sql = "SELECT * FROM tb_userinfo WHERE ui_id =".$_POST[txtUserId];
	$rows = $db->query($sql);
	$userRecord = $db->fetch_array($rows);
	
	$readonly = 'readonly="readonly"';
}
?>
<script language="javascript">
var ajax1=new sack();

//CHECKING UNIQUE DRIVER ID FOR ADMIN

function checkUsername(val,id)
{
	
	qry = "select * from tb_userinfo where ui_username = '"+val+"'";
	ajax1.requestFile = 'ajax_server.php?ajaxQry='+qry;
	//document.write(ajax1.requestFile);
	ajax1.onCompletion = function(){resultUsername(id)};
	ajax1.runAJAX();
}

function resultUsername(id)
{
  if(ajax1.response ==1 )
  {
  	alert("Date already Exist");
  	document.getElementById(id).value = '';
	document.getElementById(id).focus();
  }
	
}

</script>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers"><a href="#" class="error_strings" onclick="location.href='?ch=viewUser';">List of Users</a> / Add New</div>
<form name="frmUserRegister" id="frmUserRegister" action="?ch=User" method="post">
<input type="hidden" name="selUserRole" id="selUserRole" value="1" />
<?php
if(isset($_POST[txtUserId]) && $_POST[txtUserId] !=''){
?>
	<input type="hidden" name="txtUserId" id="txtUserId" value="<?php echo $_POST[txtUserId];?>"/>  
<?php } ?>
<table class="form1">
    <tr>
        <td width="41%" class="formtext">Username </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" >
      <?php
		if(isset($_POST[txtUserId]) && $_POST[txtUserId] !=''){
		?>
      <input type="text" id="txtUsername" name="txtUsername" <?php echo $readonly;?> value="<?php echo $userRecord[ui_username];?>"  />
      <?php } else { ?>
       <input type="text" id="txtUsername" name="txtUsername" <?php echo $readonly;?> value="<?php echo $userRecord[ui_username];?>" onblur="checkUsername(this.value,this.id);" />
       <?php }?>
     </td>
     
  </tr>
   
  <tr>
        <td width="41%" class="formtext">Password </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="password" id="txtPassword" name="txtPassword"  />
     </td>
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Firstname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtFirstname" name="txtFirstname" value="<?php echo $userRecord[ui_firstname];?>" />
      </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Lastname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtLastname" name="txtLastname" value="<?php echo $userRecord[ui_lastname];?>" /></td>
     
  </tr>
      
   <tr>
        <td width="41%" class="formtext">Mobile </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtMobile" name="txtMobile" value="<?php echo $userRecord[ui_mobile];?>" />
      
      </td>
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Email </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtEmail" name="txtEmail" value="<?php echo $userRecord[ui_email];?>" />
      
      </td>
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Allow Login </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="checkbox" value="1" id="txtAllowLog" <?php if($_POST[txtUserId]!='') { if($userRecord[ui_accessFlag] == 1) echo 'checked="checked"'; } else { echo 'checked="checked"'; } ?> name="txtAllowLog" />
      
      </td>
  </tr>
 	<tr>
        <td width="41%" class="formtext">Address </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><textarea id="txtAddress" name="txtAddress" cols="40" rows="3"><?php echo $userRecord[ui_address];?></textarea>
      
      </td>
  </tr>
  <tr><td colspan="3" align="center">
	  <?php
      if(isset($_POST[txtUserId]) && $_POST[txtUserId] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitUpdateUser" id="cmdSubmitUpdateUser" value="Update" class="click_btn" />  
      <?php } else { ?>
            <input type="submit" name="cmdSubmitAddUser" id="cmdSubmitAddUser" value="Submit" class="click_btn" />
                                         <input type="reset" value="Reset" class="click_btn" />
      <?php } ?>
          <input type="button" name="cmdCancel" id="cmdCancel" value="Cancel" class="click_btn" onclick="location.href='?ch=viewUser';" />
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmUserRegister");
 
  frmvalidator.addValidation("txtUsername","req","Please enter Username");
  frmvalidator.addValidation("txtUsername","minlen=4",	"Min length for Username is 5");
  frmvalidator.addValidation("txtUsername","alpha_s","Name can contain alphabetic chars only");
  
  frmvalidator.addValidation("txtPassword","req","Please enter Password");
  frmvalidator.addValidation("txtPassword","minlen=8",	"Min length for Password is 8");
  
  frmvalidator.addValidation("txtFirstname","req","Please enter First Name");
  frmvalidator.addValidation("txtFirstname","maxlen=20",	"Max length for FirstName is 20");
  frmvalidator.addValidation("txtFirstname","alpha_s","Name can contain alphabetic chars only");
  
  frmvalidator.addValidation("txtLastname","req","Please enter Last Name");
  frmvalidator.addValidation("txtLastname","maxlen=20","For LastName, Max length is 20");
  frmvalidator.addValidation("txtLastname","alpha_s","Name can contain alphabetic chars only");
  
  
  frmvalidator.addValidation("txtMobile","maxlen=50");
  frmvalidator.addValidation("txtMobile","req","Please enter Mobile number");
  frmvalidator.addValidation("txtMobile","numeric","Please enter valid numbers");

  frmvalidator.addValidation("txtEmail","maxlen=50");
  frmvalidator.addValidation("txtEmail","req","Please enter Email");
  frmvalidator.addValidation("txtEmail","email","Please enter valid email id");
   

  frmvalidator.addValidation("txtAddress","maxlen=150");
  
</script>   
</div><!-- PAGEarea END here -->
