<?php
//print_r($_POST);
if(isset($_SESSION[userID]) && $_SESSION[userID] !='')
{
	$sql = "SELECT * FROM tb_userinfo WHERE ui_id =".$_SESSION[userID];
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
<form name="frmUserRegister" id="frmUserRegister" action="?ch=User" method="post">
	<input type="hidden" name="txtUserId" id="txtUserId" value="<?php echo $userRecord[ui_id];?>"/>  
    <input type="hidden" id="txtUsername" name="txtUsername" value="<?php echo $userRecord[ui_username];?>" />
    <input type="hidden" id="txtPassword" name="txtPassword" value="<?php echo $userRecord[ui_password];?>"  />
    <input type="hidden" id="txtAllowLog" name="txtAllowLog" value="<?php echo $userRecord[ui_accessFlag];?>" />
<table class="form1">
  
   <tr>
        <td width="41%" class="formtext">Firstname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtFirstname" name="txtFirstname" value="<?php echo $userRecord[ui_firstname];?>" />
      </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Lastname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtLastname" name="txtLastname" value="<?php echo $userRecord[ui_lastname];?>" />
     </td>
     
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
        <td width="41%" class="formtext">Address </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><textarea id="txtAddress" name="txtAddress" cols="40" rows="3"><?php echo $userRecord[ui_address];?></textarea>
      
      </td>
  </tr>
  <tr><td colspan="3" align="center">
            <input type="submit" name="cmdSubmitUpdateUser" id="cmdSubmitUpdateUser" value="Update" class="click_btn" />  
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmUserRegister");
 
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
  
  //frmvalidator.addValidation("checkbox","dontselect=0");

  frmvalidator.addValidation("txtAddress","maxlen=150");
  
</script>   
</div><!-- PAGEarea END here -->
