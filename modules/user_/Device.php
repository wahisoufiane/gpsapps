<?php
//print_r($_POST);
//	ADD USER INFORMATION
if(isset($_POST[cmdSubmitUpdateInusrance]) && $_POST[cmdSubmitUpdateInusrance]!='')
{
	//print_r($_POST);
	$data['tdii_clientId'] = $_POST[txtClientId];
	$data['tdii_deviceId'] = $_POST[map_device_id];
	$data['tdii_policyNum'] = $_POST[txtPolicyNo];
	$data['tdii_policyCompany'] = $_POST[txtPolicyComp];
	$data['tdii_policyAmount'] = $_POST[txtPolicyAmt];
	$data['tdii_policyExpDate'] = date("Y-m-d",strtotime($_POST[txtPolicyExp]));
	$data['tdii_alertDate'] = date("Y-m-d",strtotime("-".$_POST[txtNoofDay]."days ".$_POST[txtPolicyExp]));
	$data['tdii_policyContPerson'] = $_POST[txtPolicyPerson];
	$data['tdii_policyContMobile'] = $_POST[txtPolicyMobile];
	
	
	//print_r($data);
	//exit;
	if($db->query_update("tb_device_insurance_info", $data , "tdii_id=".$_POST[txtDeviceId]))
		$res = 1;
	else $res = 0;
	
	header("location:?ch=status&au=21&msg=".$res);
	exit;
}
if(isset($_POST[cmdSubmitAddInusrance]) && $_POST[cmdSubmitAddInusrance]!='')
{
	//print_r($_POST);
	$data['tdii_clientId'] = $_POST[txtClientId];
	$data['tdii_deviceId'] = $_POST[map_device_id];
	$data['tdii_policyNum'] = $_POST[txtPolicyNo];
	$data['tdii_policyCompany'] = $_POST[txtPolicyComp];
	$data['tdii_policyAmount'] = $_POST[txtPolicyAmt];
	$data['tdii_policyExpDate'] = date("Y-m-d",strtotime($_POST[txtPolicyExp]));
	$data['tdii_alertDate'] = date("Y-m-d",strtotime("-".$_POST[txtNoofDay]."days ".$_POST[txtPolicyExp]));
	$data['tdii_policyContPerson'] = $_POST[txtPolicyPerson];
	$data['tdii_policyContMobile'] = $_POST[txtPolicyMobile];
	
	
	//print_r($data);
	//exit;
	
	if($db->query_insert("tb_device_insurance_info", $data))
		$res = 1;
	else $res = 0;
	
	header("location:?ch=status&au=20&msg=".$res);
	exit;
}

if(isset($_POST[cmdSubmitAddDevice]) && $_POST[cmdSubmitAddDevice]!='')
{
	$txtDeviceName = str_replace(" ","_",$_POST[txtDeviceName]);

	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_deviceId = '".$txtDeviceName."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_deviceId'] = $txtDeviceName;


	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_imeiId = '".$_POST[txtImeiNo]."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_imeiId'] = $_POST[txtImeiNo];
	
	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_mobileNo = '".$_POST[txtMobileNo]."'";
	$rows = $db->query($sql);
	exit;
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_mobileNo'] = $_POST[txtMobileNo];

$data['di_odoMeter'] = $_POST[txtOdometer];
$data['di_deviceModel'] = $_POST[txtDeviceModel];
$data['di_userId'] = $_SESSION[userID];
$data['di_clientId'] = $_SESSION[clientID];

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	

print_r($data);
exit;

if($db->query_insert("tb_deviceinfo", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=3&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitUpdateDevice]) && $_POST[cmdSubmitUpdateDevice]!='')
{
	$txtDeviceName = str_replace(" ","_",$_POST[txtDeviceName]);

	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_deviceId = '".$txtDeviceName."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_deviceId'] = $txtDeviceName;


	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_imeiId = '".$_POST[txtImeiNo]."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_imeiId'] = $_POST[txtImeiNo];
	
	/*$sql = "SELECT * FROM tb_deviceinfo WHERE di_clientId = ".$_SESSION[clientID]." AND di_mobileNo = '".$_POST[txtMobileNo]."'";
	$rows = $db->query($sql);
	exit;
	if($db->affected_rows)
	{
		header("location:?ch=status&au=3&msg=0");
		exit;
	}
	else*/ $data['di_mobileNo'] = $_POST[txtMobileNo];
	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];
	$data['di_updateDate'] = "NOW()";

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	

//print_r($data);
//exit;

if($db->query_update("tb_deviceinfo", $data , "di_id=".$_POST[txtDeviceId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=4&msg=".$res);
exit;

}



if(isset($_POST[cmdSubmitClientAddDevice]) && $_POST[cmdSubmitClientAddDevice]!='')
{
	$txtDeviceUnId = str_replace(" ","_",$_POST[txtDeviceUnId]);
	$data['di_deviceId'] = $txtDeviceUnId;
	$data['di_deviceName'] = $_POST[txtDeviceName];
	$data['di_imeiId'] = $_POST[txtImeiNo];
	$data['di_mobileNo'] = $_POST[txtMobileNo];

	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];
	$data['di_userId'] = $_POST[txtClientUserId];
	$data['di_clientId'] = $_POST[txtClientId];

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	

//print_r($data);
//exit;

if($db->query_insert("tb_deviceinfo", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=12&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitClientUpdateDevice]) && $_POST[cmdSubmitClientUpdateDevice]!='')
{
	$data['di_deviceName'] = $_POST[txtDeviceName];
	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];
	
	if($_POST['txtMobileNo'])
	$data['di_mobileNo'] = $_POST[txtMobileNo];
	
	$data['di_userId'] = $_POST[txtClientUserId];
	$data['di_clientId'] = $_POST[txtClientId];
	$data['di_updateDate'] = "NOW()";
	

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	
	
if($_FILES["fileDeviceLogo"]["name"]!='')
{
	$type = explode("/",$_FILES["fileDeviceLogo"]["type"]);
	$filename = time().".".$type[1];
	if ((($_FILES["fileDeviceLogo"]["type"] == "image/gif")
	|| ($_FILES["fileDeviceLogo"]["type"] == "image/jpeg")
	|| ($_FILES["fileDeviceLogo"]["type"] == "image/pjpeg"))
	&& ($_FILES["fileDeviceLogo"]["size"] < 200000))
	{
		if ($_FILES["fileDeviceLogo"]["error"] > 0)
		{
			echo "Return Code: " . $_FILES["fileDeviceLogo"]["error"] . "<br />";
			//header("location:?ch=status&au=7&msg=1");
			//exit;
		}
		else
		{
			if (file_exists("unit_img/" . $filename))
			{
				echo $_FILES["fileDeviceLogo"]["name"] . " already exists. ";
				//header("location:?ch=status&au=7&msg=0");
				//exit;
			}
			else
			{
				move_uploaded_file($_FILES["fileDeviceLogo"]["tmp_name"],"unit_img/" . $filename);
				$data['di_deviceImg'] = $filename;
			}
		}
	}
}
	
else
	$data['di_deviceImg'] = $_POST[rdDevImg];
	
//echo "<br><br>";
//print_r($data);
//exit;

if($db->query_update("tb_deviceinfo", $data , "di_id=".$_POST[txtDeviceId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=13&msg=".$res);
exit;

}


?>