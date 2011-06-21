<?php
//print_r($_POST);
//	ADD USER INFORMATION
if(isset($_POST[cmdSubmitAddAdmin]) && $_POST[cmdSubmitAddAdmin]!='')
{
$data['ui_firstname'] = $_POST[txtFirstname];
$data['ui_lastname'] = $_POST[txtLastname];
$data['ui_username'] = $_POST[txtUsername];
$data['ui_password'] = $_POST[txtPassword];
$data['ui_mobile'] = $_POST[txtMobile];
$data['ui_email'] = $_POST[txtEmail];
$data['ui_address'] = $_POST[txtAddress];
$data['ui_clientId'] = $_POST[txtClientId];
$data['ui_isAdmin'] = 1;

if($_POST[txtAllowLog])
	$data['ui_accessFlag'] = $_POST[txtAllowLog];
else
	$data['ui_accessFlag'] = 0;	

//print_r($data);
//exit;
if($db->query_insert("tb_userinfo", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=3&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitUpdateAdmin]) && $_POST[cmdSubmitUpdateAdmin]!='')
{
$data['ui_firstname'] = $_POST[txtFirstname];
$data['ui_lastname'] = $_POST[txtLastname];
$data['ui_username'] = $_POST[txtUsername];
$data['ui_password'] = $_POST[txtPassword];
$data['ui_mobile'] = $_POST[txtMobile];
$data['ui_email'] = $_POST[txtEmail];
$data['ui_address'] = $_POST[txtAddress];
$data['ui_updateDate'] = "NOW()";

if($_POST[txtAllowLog])
	$data['ui_accessFlag'] = $_POST[txtAllowLog];
else
	$data['ui_accessFlag'] = 0;	

//print_r($data);
//exit;

if($db->query_update("tb_userinfo", $data , "ui_id=".$_POST[txtUserId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=4&msg=".$res);
exit;

}


?>