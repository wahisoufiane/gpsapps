<?php
if(isset($_POST) && $_POST[cmdSubmitClientAddDevice]!=""){

   date_default_timezone_set('Asia/Kolkata');

  $query = mysql_query("select * from tb_simcard where number='".$_POST[txtSimNo]."'");
  $result = mysql_fetch_row($query);

 
 $data['name'] = $_POST[txtClientName];
 $data['number'] = $_POST[txtSimNo];


 if($_POST[txtGsm]=='1'){
   $data['gsm_number'] = 'Yes';
  } else {
   $data['gsm_number'] = 'No';
  }

if($_POST[txtSubscription]=='1'){
   $data['payment_status'] = 'Paid';
  } else {
   $data['payment_status'] = 'Not Paid';
  }
 $data['date'] = date('Y-m-d');
 $data['clientid'] = $_POST[txtClientUserId];
 $data['amount'] = $_POST[txtAmount];

if($result!="" && $result['0']!=""){
	
	 if($db->query_update("tb_simcard", $data)){
    header("location:?ch=viewSimcard");
    exit;
 }
 }


 if($db->query_insert("tb_simcard", $data)){
  header("location:?ch=viewSimcard");
    exit;
 }

}
if($_REQUEST['id']!=""){

  $query1 = mysql_query("select * from tb_simcard where id='".$_REQUEST['id']."'");
  $result1 = mysql_fetch_row($query1);
  
  /*echo '<pre>';print_r($result1); echo '</pre>';

  exit;*/
}
if($_REQUEST[del]=='1'){
  $query2 = mysql_query("DELETE FROM tb_simcard where id='".$_REQUEST['id']."'");
  header("location:?ch=viewSimcard");
    exit;
}
?>
<script language="javascript">
var ajax1=new sack();

//CHECKING UNIQUE DRIVER ID FOR ADMIN

function checkDevice(tableName,condStr,val,id)
{
	val = val.replace(" ","_");
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

<div class="pagearea">
<!-- Pagearea div start here -->
<!--<div align="left" class="listofusers"><a href="#" onclick="location.href='?ch=viewClientDevice';">List of Device</a> / Add Device</div>
-->
<form name="frmSimcard" id="frmSimcard" action="?ch=addSimcard" method="post">
<input type="hidden" name="txtClientUserId" id="txtClientUserId" value="<?php echo $_SESSION[userID];?>"/>

<table class="form1">


    <tr>
        <td width="41%" class="formtext">Name </td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
      
        <input type="text" id="txtClientName" name="txtClientName" value="<?php echo $result1[1];?>"/>
        
     </td>
     
  </tr>
   
  <tr>
        <td width="41%" class="formtext">Sim card number</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
         <?php if(isset($_POST[txtDeviceId]) && $_POST[txtDeviceId] !=''){ ?>
         <input type="text" id="txtSimNo" name="txtSimNo" disabled="disabled" value="<?php echo $result1[2];?>" />
         <?php } else { ?>
         <input type="text" id="txtSimNo" name="txtSimNo" value="<?php echo $result1[2];?>"/>
        <?php } ?>
     </td>
  </tr>
  
   <tr>
        <td width="41%" class="formtext">Subscription Paid (Yes/No)</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
         <input type="checkbox" value="1" id="txtSubscription"  name="txtSubscription" 
		 <?php if($result1[3]=='Paid'){echo 'checked';}?>/>

		
   </td>     
  </tr>

  <tr>
        <td width="41%" class="formtext">Amount</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" >
      <input type="text" value="<?php if($result1[7]!=''){echo $result1[7];}?>" id="txtAmount"  name="txtAmount"/>

		
   </td>     
  </tr>
  <tr>
        <td width="41%" class="formtext">Gsm Number (Yes/No)</td>
        <td width="2%" align="center">  : </td> 
        <td width="57%" ><input type="checkbox" value="1" id="txtGsm"  name="txtGsm" 
		<?php if($result1[4]=='Yes'){echo 'checked';}?>/>
      </td>
  </tr>
  <tr><td colspan="3" align="center">
	  <?php
      if(isset($_REQUEST[id]) && $_REQUEST[id] !='')
        {
        ?>
            <input type="submit" name="cmdSubmitClientAddDevice" id="cmdSubmitClientAddDevice" value="Update" class="click_btn" />  
      <?php } else { ?>
            <input type="submit" name="cmdSubmitClientAddDevice" id="cmdSubmitClientAddDevice" value="Submit" class="click_btn" />
            <input type="button" value="Cancel" class="click_btn" onclick="window.location.href='?ch=viewSimcard'" />
      <?php } ?>
  </td></tr>
                                     

</table>
        
</form>
 <script language="JavaScript" type="text/javascript">
//You should create the validator only after the definition of the HTML form
  var frmvalidator  = new Validator("frmSimcard");
   
  frmvalidator.addValidation("txtClientName","req","Please enter Client name");
  frmvalidator.addValidation("txtSimNo","req","Please enter Mobile number");
  frmvalidator.addValidation("txtSimNo","maxlen=10");
  frmvalidator.addValidation("txtSimNo","numeric","Please enter valid Mobile numbers");

  
</script>   
</div>
