<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

if(isset($_POST[txtClientId]) && $_POST[txtClientId] !='')
{
	$sql = "SELECT * FROM tb_clientinfo,tb_userinfo WHERE ui_clientId = ci_id  AND ci_id =".$_POST[txtClientId];
	$rows = $db->query($sql);
	$clientRecord = $db->fetch_array($rows);
	//print_r($clientRecord);
	if($clientRecord[ci_clientType] == "Reseller")
	{
		$styl = 'style="display:;"';
		$getSubs = "SELECT * FROM tb_reseller_subscription WHERE trs_clientId = ".$_POST[txtClientId];
		$resSubs = $db->query($getSubs);
		$subsRecord = $db->fetch_array($resSubs);
	}
	else
		$styl = 'style="display:none;"';
}
?>
<script language="javascript">
$(function() {
	$( "#txtSubStartDate" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd/mm/yy"
	});
});

var ajax1=new sack();

//CHECKING UNIQUE DRIVER ID FOR ADMIN

function checkUser(tableName,condStr,val,id)
{
	qry = "select * from "+tableName+" where "+condStr+" = '"+val+"'";
		
	ajax1.requestFile = 'ajax_server.php?ajaxQry='+qry;
	//document.write(ajax1.requestFile);
	ajax1.onCompletion = function(){resultDevice(id)};
	ajax1.runAJAX();
}

function resultDevice(id)
{
  if(ajax1.response ==1 )
  {
  	alert("Date already Exist");
  	document.getElementById(id).value = '';
	document.getElementById(id).focus();
  }
	
}


</script>
<style type="text/css">

</style>
<div class="pagearea"><!-- Pagearea div start here -->
<div align="left" class="listofusers"><a href="#" onclick="location.href='?ch=viewClient';">List of Users</a> / Add New</div>
<form name="frmUserRegister" id="frmUserRegister" action="?ch=Client" method="post" autocomplete="off">
<?php
if(isset($_POST[txtClientId]) && $_POST[txtClientId] !=''){
?>
	<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>"/>  
<?php } ?>
<table class="form1">
    <tr>
        <td width="28%" class="formtext">Client Name </td>
        <td align="center" width="2%">  : </td> 
        <td width="40%" >
        <?php if(isset($_POST[txtClientId]) && $_POST[txtClientId] !=''){ ?>
        <input type="text" id="txtClientName" name="txtClientName" disabled="disabled" value="<?php echo $clientRecord[ci_clientName];?>"  /></td>
        <?php } else { ?>
        <input type="text" id="txtClientName" name="txtClientName" value="<?php echo $clientRecord[ci_clientName];?>" onblur="checkUser('tb_clientinfo','ci_clientName',this.value,this.id);" />
     <?php } ?>
  </tr>
   
   <tr>
        <td width="41%" class="formtext">Username </td>
        <td align="center">  : </td> 
      <td>
      <?php
	if(isset($_POST[txtClientId]) && $_POST[txtClientId] !=''){
	?>
      <input type="text" id="txtUsername" name="txtUsername" disabled="disabled" value="<?php echo $clientRecord[ui_username];?>"/>
      <?php } else { ?>
      <input type="text" id="txtUsername" name="txtUsername" value="<?php echo $clientRecord[ui_username];?>" onblur="checkUser('tb_userinfo','ui_username',this.value,this.id);" />
     <?php } ?>
     </td>
     
  </tr>
   
  <tr>
        <td width="41%" class="formtext">Password </td>
        <td align="center">  : </td> 
      <td>
       <?php
		if(isset($_POST[txtClientId]) && $_POST[txtClientId] !=''){
		?>
      <input type="password" id="txtPassword" name="txtPassword" disabled="disabled" value="<?php echo $clientRecord[ui_password];?>"/>
      <?php } else { ?>
      <input type="password" id="txtPassword" name="txtPassword" value="<?php echo $clientRecord[ui_password];?>" />
       <?php } ?>
     </td>
  </tr>
   
   <tr>
        <td width="41%" class="formtext">Land Line </td>
        <td align="center">  : </td> 
      <td><input type="text" id="txtCLandline" name="txtCLandline" value="<?php echo $clientRecord[ci_phoneNumber];?>" />
      </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Mobile </td>
        <td align="center">  : </td> 
      <td><input type="text" id="txtCMobile" name="txtCMobile" value="<?php echo $clientRecord[ci_mobileNumer];?>" />
     </td>
     
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Email 1 </td>
        <td align="center">  : </td> 
      <td><input type="text" id="txtCEmail1" name="txtCEmail1" value="<?php echo $clientRecord[ci_email1];?>" />
      
      </td>
  </tr>
  
  <tr>


        <td width="41%" class="formtext">Email 2</td>
        <td align="center">  : </td> 
      <td><input type="text" id="txtCEmail2" name="txtCEmail2" value="<?php echo $clientRecord[ci_email2];?>" />
      
      </td>
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Website </td>
        <td align="center">  : </td> 
      <td><input type="text" id="txtCWebsite" name="txtCWebsite" value="<?php echo $clientRecord[ci_website];?>" />
      
      </td>
  </tr>

 	<tr>
        <td width="41%" class="formtext">Address </td>
        <td align="center">  : </td> 
      <td><textarea id="txtCAddress" name="txtCAddress" cols="40" rows="3"><?php echo $clientRecord[ci_address];?></textarea>
      
      </td>
  </tr>
  <tr><td align="center" colspan="4">
	  <?php
      if(isset($_POST[txtClientId]) && $_POST[txtClientId] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitUpdateClient" id="cmdSubmitUpdateClient" value="Update" class="click_btn" />  
      <?php } else { ?>
            <input type="submit" name="cmdSubmitAddClient" id="cmdSubmitAddClient" value="Submit" class="click_btn" />
            <input type="reset" value="Reset" class="click_btn" />
      <?php } ?>
  </td></tr>
  </table>
        
</form>
 <script language="javaScript" type="text/javascript">

//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmUserRegister");
  
  frmvalidator.addValidation("txtClientName","req","Please enter Client Name");
  frmvalidator.addValidation("txtClientName","minlen=5",	"Min length for Client Name is 5");
  frmvalidator.addValidation("txtClientName","alpha_s","Name can contain alphabetic chars only");
  
  frmvalidator.addValidation("txtUsername","req","Please enter Username");
  frmvalidator.addValidation("txtUsername","minlen=4",	"Min length for Username is 5");
  frmvalidator.addValidation("txtUsername","alpha_s","Name can contain alphabetic chars only");
  
  frmvalidator.addValidation("txtPassword","req","Please enter Password");
  frmvalidator.addValidation("txtPassword","minlen=8",	"Min length for Password is 8");
  
  frmvalidator.addValidation("txtCLandline","maxlen=50");
  frmvalidator.addValidation("txtCLandline","numeric","Please enter valid Landline number");
  
  frmvalidator.addValidation("txtCMobile","maxlen=50");
  frmvalidator.addValidation("txtCMobile","req","Please enter Mobile number");
  frmvalidator.addValidation("txtCMobile","numeric","Please enter valid Mobile number");

  frmvalidator.addValidation("txtCEmail1","maxlen=50");
  frmvalidator.addValidation("txtCEmail1","req","Please enter Email 1");
  frmvalidator.addValidation("txtCEmail1","email","Please enter valid Email 1");
  
  frmvalidator.addValidation("txtCEmail2","maxlen=50");
  frmvalidator.addValidation("txtCEmail2","email","Please enter valid Email 1");
  
  frmvalidator.addValidation("txtCAddress","maxlen=150");
 
</script>   
</div><!-- PAGEarea END here -->
