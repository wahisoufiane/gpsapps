<?php
//For Adding a branch
//print_r($_POST);
$msg = '';

$getAssignForm="SELECT * FROM user_info WHERE ui_id=".$_POST[user_id];    
$rsAssignForm = mysql_query($getAssignForm);
$fetAssignForm = @mysql_fetch_assoc($rsAssignForm);

if($_POST['act']=="update" && $_POST['rid']!="")
{


	if($_POST[branch_name]!='')
	{
		$updattCodes="update userbranch_info set ub_branchName='".$_POST[branch_name]."' where ub_id=".$_POST['rid'];
		$chk=mysql_query($updattCodes);
		if($chk==1)
			header("location:index.php?ch=succMsg&edit_branchCode_succmsg=1&img_succ=2");
		else
			header("location:index.php?ch=succMsg&edit_branchCode_succmsg=0&img_succ=0");
	}
}

if($_POST['act']=="delete" && $_POST['rid']!="")
{
	$delattCodes="DELETE FROM userbranch_info WHERE  ub_id='".$_POST['rid']."' ";
	$resattCodes=mysql_query($delattCodes);
	if($resattCodes==1)
			header("location:index.php?ch=succMsg&del_branchCode_succmsg=1&img_succ=1");
		else
			header("location:index.php?ch=succMsg&del_branchCode_succmsg=0&img_succ=0");
}

if(isset($_POST[all_form_ids]) && $_POST[all_form_ids]!='')
{
 //for maintaining the auto increment as a sequence
	//print_r($_POST);	
	$ct1 = 0;
	$ct2 = 0;
	if(isset($_POST[all_form_ids]) && $_POST[all_form_ids]!='')
	{
		$form_ids_array = explode('#',$_POST[all_form_ids]);
		$ct1 = count($form_ids_array)-1;
		for($s=0;$s<count($form_ids_array);$s++)
		{
			if(isset($_POST["form_id_".$form_ids_array[$s]]))
			{
				
				$assignForm="UPDATE form_info SET fi_emp_id = ".$_POST[user_id]." WHERE fi_id = ".$form_ids_array[$s]; 
				$chk=mysql_query($assignForm);
				
				if($chk)
					$ct2++;
			}
			else
			{
				$assignForm="UPDATE form_info SET fi_emp_id = 0 WHERE fi_id = ".$form_ids_array[$s]; 
				$chk=mysql_query($assignForm);
				
				if($chk)
					$ct2++;
			}
		}
	}
	if($ct1 == $ct2)
	{
		$_POST[all_form_ids]='';
		$msg="Form Assigned Successfully";
		//header("location:index.php?ch=succMsg&assignForm=1&img_succ=1");
	}
	else
	{
		$_POST[all_form_ids]='';
		$msg="Assigning Form Failed";
		//header("location:index.php?ch=succMsg&assignForm=0&img_succ=0");
	}
}
//echo $msg;

?>
<script language="javascript">
function callCustomer(cid,action)
{
	document.client_details.client_id.value = cid;
	document.client_details.action = action;
	document.client_details.submit();
	
}
function getForms(cate_id,user_id)
{
	document.frmCategory.cate_id.value = cate_id;
	document.frmCategory.user_id.value = user_id;
	document.frmCategory.submit();
}
function fnEditBranch(frm,id)
{
	frm.act.value="edit";
	frm.rid.value=id;
	frm.submit();
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
<form name="frmCategory" id="frmCategory" method="post" action="">
	<input type="hidden" name="cate_id" id="cate_id" value="" />
   	<input type="hidden" name="user_id" id="user_id" value="" />
</form>

<div style="width:100%; width:100%;">
<form name="add_client" id="add_client" method="post" autocomplete="off" action="">
<input type="hidden" name="client_id" id="client_id" value="<?php echo $_POST[client_id];?>" />
<table width="100%" border="0" cellpadding="5" cellspacing="1">
<tr>
    <td align="center" height="30" width="90%" class="actived_link">Assigning Forms for Employee - <?php echo ucfirst($fetAssignForm[ui_name]) ;?> of Vasista</td>
    <td align="center" height="30" class="forms_bold_text"><a href="#" onclick="location.href='?ch=viewEmp';">Back</a> </td>
  </tr>
  <tr>
    <td style="padding:0px;" colspan="2">
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="1" id="addTable_grid">
      <tr>
        <td colspan="4" align="center">
        <div id="add_client_error" class="error_css">
		<?php 
		if(isset($msg) && $msg!='')
		echo $msg;
		else echo "&nbsp;";
		?>
        </div>
        </td>
      </tr>
      <tr>
        <td width="20%" align="right">Choose Category</td>
        <td width="30%" align="left">
		<?php
        $getCategory="SELECT * FROM category_info ORDER BY cat_id ASC";
        $rsCategory= mysql_query($getCategory);
        while($fetCategory = @mysql_fetch_assoc($rsCategory))
        {
            if($_POST[cate_id]==$fetCategory[cat_id])
                $selected = 'selected="selected"';
            else
                $selected = '';
                
                
            $option .= '<option '.$selected .' value='.$fetCategory[cat_id].'>'.$fetCategory[cat_category_name].'</option>';
        }
        ?>
        <select name="selCategory" id="selCategory" style="width:45%" onchange="getForms(this.value,'<?php echo $_POST[user_id];?>');">
        <option value="0">Select Category</option>
        <?php echo $option;?>
        </select>
        </td>
     </tr>
     </table></td>
      </tr>
    </table>
</form>
</div>
<?php if(isset($_POST[cate_id]) && $_POST[cate_id]!='') { ?>
<form name="frmFormList" id="frmFormList" method="post" action="">
<input type="hidden" name="user_id" id="user_id" value="<?php echo $_POST[user_id];?>">
<input type="hidden" name="cate_id" id="cate_id" value="<?php echo $_POST[cate_id];?>" />
<table width="100%" border="0" cellpadding="5" cellspacing="1">
   <tr><td colspan="5"><input type="button" name="cmdAssignForm" id="cmdAssignForm" class="go_button" value="Assign" onclick="assignForm(document.getElementById('all_form_ids').value);" /></td></tr>
   <tr class="grid_heading" style="background:#e5e3e3">
   <td width="5%"><input type="checkbox" name="form_del_all" id="form_del_all" onclick="checkAllForm();" /></td>
    <td width="30%">Form Name</td>
    <td width="10%">Count</td>
    <td width="50%">Description</td>
    <td width="5%">Status</td>
  </tr>
  <?php

	$getForm="SELECT * FROM form_info WHERE fi_form_category=".$_POST[cate_id]." AND (fi_emp_id=0 OR fi_emp_id=".$_POST[user_id].") ORDER BY fi_id ASC";
	$rsForm = mysql_query($getForm);
	if(@mysql_affected_rows() > 0)
	{
	while($fetForm = @mysql_fetch_assoc($rsForm))
	{
			//echo $fetForm[fi_id];
			$getCompAssForm="SELECT * FROM form_assign_company WHERE fac_form_ids LIKE '%".$fetForm[fi_id]."%' ORDER BY fac_id ASC";
			$rsCompAssForm = mysql_query($getCompAssForm);
			$no_of_rows=@mysql_num_rows($rsCompAssForm);
			
			
			if ($fetForm[fi_emp_id] ==  $_POST[user_id]) 
					$check = 'checked=checked';
				else
					$check = '';
			
			
    ?>
  <tr class=<?php if(($i%2)==0) echo "even"; else echo "odd";?>>
    <td><?php //echo $fetForm[fi_id];?><input type="checkbox" <?php echo $check;?> name="form_id_<?php echo $fetForm[fi_id]; ?>" id="form_id_<?php echo $fetForm[fi_id]; ?>" /></td>
    <td><?php echo wordwrap($fetForm[fi_form_name],50,"<br>",true); ?></td>	
    <td><?php echo $no_of_rows; ?></td>
    <td><?php echo wordwrap($fetForm[fi_desc],25,"<br>",true); ?></td>
    <td><?php echo $fetForm[fi_isActive]; ?></td>
  </tr>
  <?php 
	$assignIds.=$fetForm[fi_id]."#";
	$i++;
	}
	}
	else
	{
		?>
        <tr><td colspan="5" align="center">No Records found</td></tr>
        <?php
	}
		//$cateCountArry[$fetFoprm[fi_form_category]] = $cateCountArry[$fetFoprm[fi_form_category]]+1;
		//echo "<br>";
    ?>
    <input type="hidden" name="all_form_ids" id="all_form_ids" value="<?php echo $assignIds; ?>" />
    
  <tr>
    <td colspan="7" style="border:0px;"><?php //paginate($tot,$n,$pg); ?></td>
  </tr>
</table>
</form>	
<?php } ?>		

        
<form name="client_details" id="client_details" method="post" action="">
	<input type="hidden" name="client_id" id="client_id" value="" />
</form>
