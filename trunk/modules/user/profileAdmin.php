<?php
//print_r($_POST);
/*if(isset($_SESSION[userID]) && $_SESSION[userID] !='')
{
	$sql = "SELECT * FROM tb_userinfo WHERE ui_id =".$_SESSION[userID];
	$rows = $db->query($sql);
	$recordUserInfo = $db->fetch_array($rows);
	
	$readonly = 'readonly="readonly"';
}*/
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
<form name="frmUserRegister" id="frmUserRegister" action="?ch=User" method="post" enctype="multipart/form-data">
	<input type="hidden" name="txtUserId" id="txtUserId" value="<?php echo $_SESSION[userID];?>"/>  
   	<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_SESSION[clientID];?>"/>  
    <input type="hidden" id="txtUsername" name="txtUsername" value="<?php echo $recordUserInfo[ui_username];?>" />
    <input type="hidden" id="txtnPassword" name="txtPassword" value="<?php echo $recordUserInfo[ui_password];?>"  />
    <input type="hidden" id="txtAllowLog" name="txtAllowLog" value="<?php echo $recordUserInfo[ui_accessFlag];?>" />
<table class="form1">
  
   <tr>
        <td width="41%" class="formtext">Firstname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtFirstname" name="txtFirstname" value="<?php echo $recordUserInfo[ui_firstname];?>" />
      </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Lastname </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtLastname" name="txtLastname" value="<?php echo $recordUserInfo[ui_lastname];?>" />
     </td>
     
  </tr>
  <tr>
        <td width="41%" class="formtext">Password </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="password" id="txtPassword" name="txtPassword"/>
     </td>
     
  </tr>
  <tr>
        <td width="41%" class="formtext">Confirm Password </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="password" id="txtcPassword" name="txtcPassword"/>
     </td>
     
  </tr>
   <tr>
        <td width="41%" class="formtext">Mobile </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtMobile" name="txtMobile" value="<?php echo $recordUserInfo[ui_mobile];?>" />
      
      </td>
  </tr>
  
  <tr>
        <td width="41%" class="formtext">Email </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="text" id="txtEmail" name="txtEmail" value="<?php echo $recordUserInfo[ui_email];?>" />
      
      </td>
  </tr>
  <?php if($recordUserInfo[ci_clientType] != "Client") { ?>
 	<tr>
        <td width="41%" class="formtext">Logo </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><input type="file" name="fileUserLogo" id="fileUserLogo" />
      </td>
  </tr>
   <tr>
        <td width="41%" class="formtext">Website URL </td>
        <td width="2%" align="center">  : </td> 
      	<td width="57%" ><input type="text" id="txtUserWebURL" name="txtUserWebURL" value="<?php echo $recordUserInfo[ci_weburl];?>" />
      </td>
     
  </tr>
  <tr>
        <td width="41%" class="formtext">Footer Text </td>
        <td width="2%" align="center">  : </td> 
      	<td width="57%" ><input type="text" id="txtUserFooter" name="txtUserFooter" value="<?php echo $recordUserInfo[ci_footerText];?>" />
      </td>
     
  </tr>
  <tr>
        <td width="41%" class="formtext">About Us </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><textarea id="txtAboutUS" name="txtAboutUS" cols="40" rows="3"><?php echo $recordUserInfo[ci_aboutUs];?></textarea>
      
      </td>
  </tr>
  <?php } ?>
  <tr>
        <td width="41%" class="formtext">Address </td>
        <td width="2%" align="center">  : </td> 
      <td width="57%" ><textarea id="txtAddress" name="txtAddress" cols="40" rows="3"><?php echo $recordUserInfo[ui_address];?></textarea>
      
      </td>
  </tr>
  <tr><td colspan="3" align="center">
            <input type="submit" name="cmdSubmitUpdateUserAdmin" id="cmdSubmitUpdateUserAdmin" value="Update" class="click_btn" />  
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">

 

 function DoCustomValidation()
{
  var frm = document.forms["frmUserRegister"];
  var pw1 = document.getElementById('txtPassword').value;
  var pw2 = document.getElementById('txtcPassword').value;
if(pw1!="" || pw2!=""){
   if(pw1.length<'8' || pw2.length<'8'){
  sfm_show_error_msg('Min length for Password is 8',frm.pwd1);
    return false;
   } else {

   if(pw1 != pw2)
  {  

    sfm_show_error_msg('The Password and verified password does not match!',frm.pwd1);
    return false;
  }
  else
  {
    return true;
  }
   }
   } else {
    return true;
   }
}
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmUserRegister");

  
  frmvalidator.addValidation("txtFirstname","req","Please enter First Name");
  frmvalidator.addValidation("txtFirstname","maxlen=20","Max length for FirstName is 20");
  frmvalidator.addValidation("txtFirstname","alpha_s","Name can contain alphabetic chars only");
  
  //if(pw1!="" || pw2!=""){
  // frmvalidator.addValidation("txtPassword","minlen=6","Min length for Password is 6");
  // frmvalidator.addValidation("txtcPassword","minlen=6","Min length for Confirm Password is 6");

  frmvalidator.setAddnlValidationFunction("DoCustomValidation");
  //}
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
