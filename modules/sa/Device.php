<?php
/*print_r($_POST);
exit;*/
//	ADD USER INFORMATION
function checkExist()
{
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
/*Fuel parameters*/
	$data['di_tankSize'] = $_POST[txtTanksize];
	$data['di_fullTanksignal'] = $_POST[txtfullTanksignal];
	$data['di_fullTanksignalmeasure'] = $_POST[txtfullTanksignalmeasure];
	$data['di_emptyTanksignal'] = $_POST[txtemptyTanksignal];
	$data['di_emptyTanksignalmeasure'] = $_POST[txtemptyTanksignalmeasure];

    /*Port numbers*/
	$data['di_Fuelport'] = $_POST[txtFuelport];
	$data['di_Acport'] = $_POST[txtAcport];
	$data['di_Ignitionport'] = $_POST[txtIgport];
	$data['di_Sosport'] = $_POST[txtSosport];
	$data['di_Engineport'] = $_POST[txtEngineport];

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	

/*print_r($data);
exit;*/

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
	$txtDeviceName = str_replace(" ","_",$_POST[txtDeviceName]);
	$data['di_deviceId'] = $txtDeviceName;
	$data['di_imeiId'] = $_POST[txtImeiNo];
	$data['di_mobileNo'] = $_POST[txtMobileNo];

	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];
	$data['di_userId'] = $_POST[txtClientUserId];
	$data['di_clientId'] = $_POST[txtClientId];
	/*Fuel parameters*/
	$data['di_tankSize'] = $_POST[txtTanksize];
	$data['di_fullTanksignal'] = $_POST[txtfullTanksignal];
	$data['di_fullTanksignalmeasure'] = $_POST[txtfullTanksignalmeasure];
	$data['di_emptyTanksignal'] = $_POST[txtemptyTanksignal];
	$data['di_emptyTanksignalmeasure'] = $_POST[txtemptyTanksignalmeasure];

    /*Port numbers*/
	$data['di_Fuelport'] = $_POST[txtFuelport];
	$data['di_Acport'] = $_POST[txtAcport];
	$data['di_Ignitionport'] = $_POST[txtIgport];
	$data['di_Sosport'] = $_POST[txtSosport];
	$data['di_Engineport'] = $_POST[txtEngineport];

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
	$data['di_mobileNo'] = $_POST[txtMobileNo];	
	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];
	
	$data['di_userId'] = $_POST[txtClientUserId];
	$data['di_clientId'] = $_POST[txtClientId];
	$data['di_updateDate'] = "NOW()";
	
	/*Fuel parameters*/
	$data['di_tankSize'] = $_POST[txtTanksize];
	$data['di_fullTanksignal'] = $_POST[txtfullTanksignal];
	$data['di_fullTanksignalmeasure'] = $_POST[txtfullTanksignalmeasure];
	$data['di_emptyTanksignal'] = $_POST[txtemptyTanksignal];
	$data['di_emptyTanksignalmeasure'] = $_POST[txtemptyTanksignalmeasure];

    /*Port numbers*/
	$data['di_Fuelport'] = $_POST[txtFuelport];
	$data['di_Acport'] = $_POST[txtAcport];
	$data['di_Ignitionport'] = $_POST[txtIgport];
	$data['di_Sosport'] = $_POST[txtSosport];
	$data['di_Engineport'] = $_POST[txtEngineport];

if($_POST[txtStatus])
	$data['di_status'] = $_POST[txtStatus];
else
	$data['di_status'] = 0;	

/*print_r($data);
exit;*/

if($db->query_update("tb_deviceinfo", $data , "di_id=".$_POST[txtDeviceId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=13&msg=".$res);
exit;

}


?>