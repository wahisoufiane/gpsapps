<?php
//print_r($_POST);

//	ADD USER INFORMATION
if(isset($_POST[cmdSubmitAddUser]) && $_POST[cmdSubmitAddUser]!='')
{
$data['ui_firstname'] = $_POST[txtFirstname];
$data['ui_lastname'] = $_POST[txtLastname];
$data['ui_username'] = $_POST[txtUsername];
$data['ui_password'] = $_POST[txtPassword];
$data['ui_mobile'] = $_POST[txtMobile];
$data['ui_email'] = $_POST[txtEmail];
$data['ui_address'] = $_POST[txtAddress];
$data['ui_roleId'] = $_POST[selUserRole];
$data['ui_clientId'] = $_SESSION[clientID];

if($_POST[txtAllowLog])
	$data['ui_accessFlag'] = $_POST[txtAllowLog];
else
	$data['ui_accessFlag'] = 0;	

//print_r($data);
if($db->query_insert("tb_userinfo", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=1&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitUpdateUser]) && $_POST[cmdSubmitUpdateUser]!='')
{
$data['ui_firstname'] = $_POST[txtFirstname];
$data['ui_lastname'] = $_POST[txtLastname];
$data['ui_username'] = $_POST[txtUsername];
$data['ui_password'] = $_POST[txtPassword];
$data['ui_mobile'] = $_POST[txtMobile];
$data['ui_email'] = $_POST[txtEmail];
$data['ui_address'] = $_POST[txtAddress];
$data['ui_roleId'] = $_POST[selUserRole];
$data['ui_updateDate'] = "NOW()";

if($_POST[txtAllowLog])
	$data['ui_accessFlag'] = $_POST[txtAllowLog];
else
	$data['ui_accessFlag'] = 0;	

//print_r($data);

if($db->query_update("tb_userinfo", $data , "ui_id=".$_POST[txtUserId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=2&msg=".$res);
exit;

}


if(isset($_POST[cmdSubmitUpdateUserAdmin]) && $_POST[cmdSubmitUpdateUserAdmin]!='')
{
//print_r($_FILES);
//print_r($recordUserInfo[ci_clientLogo]);
//exit;
if($_FILES["fileUserLogo"]["name"]!='')
{
$type = explode("/",$_FILES["fileUserLogo"]["type"]);
$filename = time().".".$type[1];
if ((($_FILES["fileUserLogo"]["type"] == "image/gif")
|| ($_FILES["fileUserLogo"]["type"] == "image/jpeg")
|| ($_FILES["fileUserLogo"]["type"] == "image/pjpeg"))
&& ($_FILES["fileUserLogo"]["size"] < 200000))
{
	if ($_FILES["fileUserLogo"]["error"] > 0)
	{
		echo "Return Code: " . $_FILES["fileUserLogo"]["error"] . "<br />";
		header("location:?ch=status&au=7&msg=1");
		exit;
	}
	else
	{
		if (file_exists("client_logo/" . $filename))
		{
			echo $_FILES["fileUserLogo"]["name"] . " already exists. ";
			header("location:?ch=status&au=7&msg=0");
			exit;
		}
		else
		{
			move_uploaded_file($_FILES["fileUserLogo"]["tmp_name"],"client_logo/" . $filename);
			$data1['ci_clientLogo'] = $filename;
		}
	}
}
}
else
{
	echo "Invalid fileUserLogo";
	if($recordUserInfo[ci_clientLogo])
		$data1['ci_clientLogo'] = $recordUserInfo[ci_clientLogo];
	else
		$data1['ci_clientLogo'] = "logo.gif";
	//header("location:?ch=status&au=7&msg=0");
	//exit;
}

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
//if($data1['ci_clientLogo'] == '') $data1['ci_clientLogo'] = "logo.png";

if($_POST[txtUserFooter])
$data1['ci_footerText'] = $_POST[txtUserFooter];
else
$data1['ci_footerText'] = "All Rights Reserved to KEYSTONE UNIQUE INFO PRIVATE LIMITED. Copyrights 2010";

if($_POST[txtAboutUS])
$data1['ci_aboutUs'] = $_POST[txtAboutUS];
else
$data1['ci_aboutUs'] = "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.";

if($_POST[txtUserWebURL])
$data1['ci_weburl'] = $_POST[txtUserWebURL];
else
$data1['ci_weburl'] = "Shastrsoftech.com";

//print_r($data1);
//exit;

if($db->query_update("tb_clientinfo", $data1 , "ci_id=".$_POST[txtClientId]))
	$res = 1;
else
	$res = 0;

//exit;
if($db->query_update("tb_userinfo", $data , "ui_id=".$_POST[txtUserId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=7&msg=".$res);
exit;
}

if($_POST[txtUserId]!='' && $_POST[txtAllDeviceId]!='')
{

$deviceId = explode(",",$_POST[txtDeviceId]);
$deviceAllId = explode(",",$_POST[txtAllDeviceId]);

for($i=0;$i<count($deviceAllId)-1;$i++)
{
	if(in_array($deviceAllId[$i],$deviceId))
	{
		$data['di_assignedUserId'] = $_POST[txtUserId];
		if($db->query_update("tb_deviceinfo", $data , "di_id=".$deviceAllId[$i]))	
			$c++;
	}
	else
	{
		$data['di_assignedUserId'] = 0;
		$db->query_update("tb_deviceinfo", $data , "di_id=".$deviceAllId[$i]);
	}
	
}
//echo (count($deviceId)-1) ."==". $c;
if((count($deviceId)-1) == $c)
	$res = 1;
else $res = 0;
//print_r($deviceAllId);
//exit;
header("location:?ch=status&au=2&msg=".$res);
exit;
}

?>