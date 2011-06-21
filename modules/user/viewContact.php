<?php
/*echo $_POST[del];
exit;*/
if(isset($_POST[del]) && $_POST[del]!=""){
 $sql = "DELETE FROM tb_client_contact_info WHERE tcci_id =".$_POST[txtContId];
 $rows = $db->query($sql);
}
if(isset($_POST[txtContId]) && $_POST[txtContId] !='')
{
	$sql = "SELECT * FROM tb_client_contact_info WHERE tcci_id =".$_POST[txtContId];
	$rows = $db->query($sql);
	if($db->affected_rows > 0)
	{
		$deviceRecord = $db->fetch_array($rows);
	}
}
?>
<script type="text/javascript" language="javascript">

function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{
		document.getElementById('popup_div').innerHTML = '<div id="loading_txt" >Loading...</div>';
		document.frm_map_filter.submit();
	}
}

function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';
}

function days_between(date1, date2) {

    var ONE_DAY = 1000 * 60 * 60 * 24

    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

	var difference_ms = date1_ms - date2_ms
	    
    return Math.round(difference_ms/ONE_DAY)

}

function validateEmail(id){
   var objMobileNo = document.getElementById(id);
   var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
   if(!emailPattern.test(objMobileNo.value))
   {
	   //alert('Please enter valid email address');
	   //objMobileNo.focus();
	   //objMobileNo.select();
	   return false;
   }
   else return true;
 }

function mobileNoValid(id)
{
	objMobileNo = document.getElementById(id);
	if(document.getElementById(id).value.length < 10 || document.getElementById(id).value.search(/[^0-9\-()+]/g) != -1 )
	{
		//alert('Please enter valid mobile number');
		//objMobileNo.focus();
		//objMobileNo.value="";
		return false;
	}
	else
	return true; 
	
}
var ajax1=new sack();

function validContForm(type)
{
var f = 0;	
	if(document.getElementById('txtContName').value == "" )
	{
		alert("Enter Name"); 
		document.getElementById('txtContName').focus();
		f = 1;
		return false;
	}
	else f =0;
    if(document.getElementById('rdMobile').checked)
	{
		alrtType = 'Mobile';
		if(document.getElementById('txtMobileEmail').value == "")
		{
			alert("Enter Mobile Number"); 
			document.getElementById('txtMobileEmail').focus();
			f = 1;
			return false;
		}
		else
		{
			if(!mobileNoValid('txtMobileEmail'))
			{
				alert('Please enter valid mobile number');
				document.getElementById('txtMobileEmail').focus();
				f = 1;
				return false;
			}
		}
	}else f =0;
	if(document.getElementById('rdEmail').checked)
	{
		alrtType = 'Email';
		if(document.getElementById('txtMobileEmail').value == "")
		{
			alert("Enter Email ID"); 
			document.getElementById('txtMobileEmail').focus();
			f = 1;
			return false;
		}
		else
		{
			if(!validateEmail('txtMobileEmail'))			
			{
				alert('Please enter valid email address');
				document.getElementById('txtMobileEmail').focus();
				f = 1;
				return false;
			}
		}
	}else f =0;
	//alert(f);
	if(f==0)
	{
		//qry = "select * from tb_userinfo where ui_username = '"+val+"'";
		if(type == 'add')
			ajax1.requestFile = 'ajax_server.php?type='+type+'&name='+document.getElementById('txtContName').value+'&alrttype='+alrtType+'&src='+document.getElementById('txtMobileEmail').value;
		else
			ajax1.requestFile = 'ajax_server.php?type='+type+'&name='+document.getElementById('txtContName').value+'&alrttype='+alrtType+'&src='+document.getElementById('txtMobileEmail').value+'&cid='+document.getElementById('txtContId').value;
				
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){addContact()};
		ajax1.runAJAX();
	}
	
}
function addContact()
{
	if(ajax1.response ==1 )
	{
		alert("Date Added Successfully");
		window.location.href = '?ch=viewContact'; 
	}
	else  if(ajax1.response ==0 )
	{
		alert("Date not Added");
	}
	else  if(ajax1.response == 2 )
	{
		alert("Date already Exist");
	}
	else  if(ajax1.response == 3 )
	{
		alert("Date Updated Successfully");
		window.location.href = '?ch=viewContact'; 
	}
}

function funEditUser(did,cid,act)
{
	//alert(uid)
	document.frmSubmit.txtContId.value = did;
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
}

function deleteUser(did,cid,act)
{
  if (confirm("Are you sure you want to delete")) {
	  document.frmSubmit.txtContId.value = did;
	document.frmSubmit.del.value = 'delete';
	document.frmSubmit.txtClientId.value = cid;
	document.frmSubmit.action = act;
	document.frmSubmit.submit();
  }
}

function sendCSVData()
{
//alert(c1);
document.frmTripData.submit();
//	window.location.href='export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5;
	//document.write('export.php?csvData='+c1+'&frdate='+c2+'&frtime='+c3+'&totime='+c4+'&vehino='+c5);
}
$(function() {
	$( "#txtPolicyExp" ).datepicker({
		changeMonth: true,
		changeYear: true,
		minDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
</script>
<table border="0" width="100%">
<tr>
<td width="50%" style="vertical-align: top;">
<form id="frmAddPolicy" name="frmAddPolicy" method="post" action="?ch=Device"> 
<input type="hidden" name="txtContId" id="txtContId" value="<?php echo $_POST[txtContId];?>" />
<input type="hidden" name="txtClientId" id="txtClientId" value="<?php echo $_POST[txtClientId];?>" />
<table class="gridform_final" width="100%">
<tr><th colspan="2">Add Contact</th></tr>
  <tr>
    <td width="15%" align="right">Name</td>
    <td width="35%" align="left">
        <input type="text" name="txtContName" id="txtContName" tabindex="1" value="<?php echo $deviceRecord[tcci_name];?>" />
    </td>
  </tr>              
  <tr>
    <td width="15%" align="right">Type</td>
    <td width="35%" align="left">
        <input type="radio" name="rdContType" id="rdMobile" tabindex="2" checked="checked" />Mobile No
        <input type="radio" name="rdContType" id="rdEmail" tabindex="3" />Email ID
    </td>
    </tr>
  <tr>
    <td width="15%" align="right">Mobile No / Email ID</td>
    <td width="35%" align="left">
        <input type="text" name="txtMobileEmail" id="txtMobileEmail" tabindex="4" value="<?php echo $deviceRecord[tcci_source];?>"/>
  </tr>
  <tr>
    <td align="center" colspan="2">
  
    <?php if(isset($_POST[txtContId]) && $_POST[txtContId]!='')  { ?>
	  <input type="button" name="map_export_btn" id="map_export_btn" value="Update" class="click_btn" style="font-weight:bold;" onclick="validContForm('update');"/> 
	<?php } else { ?>
      <input type="button" name="map_filter_btn" value="Add" class="click_btn" tabindex="5" onclick="validContForm('add');" />    
    <?php } ?>
      <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Cancel" class="click_btn" onclick="location.href='index.php?ch=viewContact';" tabindex="6" /> 
    </td>
  </tr>  
</table>
</form>
</td>
<td style="vertical-align:top;">
<?php
$selInsur = "SELECT * FROM tb_client_contact_info WHERE tcci_clientId = ".$_SESSION[clientID];
$resInsur = $db->query($selInsur);
?>
<table class="gridform_final" style="width:100%">
    <tr>
        <th>Name</th>
        <th>Mobile / Email ID.</th>
        <th>Edit</th>
        <th>Delete</th>
   </tr>
	 <?php 
	 if($db->affected_rows)
	 {
    while($fetInsur = @mysql_fetch_assoc($resInsur)) 
    { 
    ?>
   <tr>
    <td><?php echo ucfirst($fetInsur[tcci_name]);?></td>
    <td><?php echo $fetInsur[tcci_source];?></td>
    <td><a class="error_strings" href="#" onclick="funEditUser('<?php echo $fetInsur[tcci_id];?>','<?php echo $_SESSION[clientID];?>','?ch=viewContact')">Edit</a></td>
	<td><a class="error_strings" href="#" onclick="deleteUser('<?php echo $fetInsur[tcci_id];?>','<?php echo $_SESSION[clientID];?>','?ch=viewContact','del')">Delete</a></td>
  </tr>
  <?php
	}
	 }
	 else
	 {
		 echo '<tr><td colspan="8">No Records Found</td></tr>';
	 }
  ?>
</table>
</td>
</tr>
</table>

<form name="frmSubmit" id="frmSubmit" method="post">
    <input type="hidden" name="txtContId" id="txtContId" />
    <input type="hidden" name="txtClientId" id="txtClientId" />
	<input type="hidden" name="del" id="del"/>

</form>