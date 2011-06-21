<?php
//print_r($_POST);
if(isset($_POST[cmdAddAssign]) && $_POST[cmdAddAssign]!='')
{
	$getDeviceGeofence = "SELECT * FROM tb_assigngeofence WHERE tag_diId = ".$_POST[map_device_id]." AND tag_geofenceId = ".$_POST[selGeofenceId];	
	$resDeviceGeofence = $db->query($getDeviceGeofence);
	if($db->affected_rows == 0 )
	{
		$data['tag_geofenceId'] = $_POST[selGeofenceId];
		$data['tag_alertType'] = $_POST[rdAlertType];		
		$data['tag_diId'] = $_POST[map_device_id];
		$data['tag_alertSrc'] = $_POST[txtMobiEmail];
		$data['tag_inout'] = $_POST[selInOut];
		$data['tag_noofTimes'] = $_POST[selNoofAlert];
		$data['tag_donotTime'] = $_POST[time3]."#".$_POST[time4];
		$data['tag_clientId'] = $_SESSION[clientID];
		$data['tag_isActive'] = 1;
		
		//print_r($data);
		//exit;
		if($db->query_insert("tb_assigngeofence", $data))
		{
			header("location:?ch=status&au=18&msg=1");
			exit;
		}
		else
		{
			header("location:?ch=status&au=18&msg=0");
			exit;
		}
	}
	else
	{
		header("location:?ch=status&au=18&msg=2");
		exit;
	}
}

if(isset($_POST[cmdUpdateAssign]) && $_POST[cmdUpdateAssign]!='')
{
	$data['tag_geofenceId'] = $_POST[selGeofenceId];
	$data['tag_alertType'] = $_POST[rdAlertType];		
	$data['tag_diId'] = $_POST[map_device_id];
	$data['tag_alertSrc'] = $_POST[txtMobiEmail];
	$data['tag_inout'] = $_POST[selInOut];
	$data['tag_noofTimes'] = $_POST[selNoofAlert];
	$data['tag_donotTime'] = $_POST[time3]."#".$_POST[time4];
	$data['tag_clientId'] = $_SESSION[clientID];
	
	//print_r($data);
	//exit;
	if($db->query_update("tb_assigngeofence", $data , "tag_id=".$_POST[txtAssGeoId]))
	{
		header("location:?ch=status&au=19&msg=1");
		exit;
	}
	else
	{
		header("location:?ch=status&au=19&msg=0");
		exit;
	}
}
if(isset($_POST[cmdUpdateAlert]) && $_POST[cmdUpdateAlert]!='')
{
	$data['tdai_deviceId'] = $_POST[map_device_id];
	$data['tdai_clientId'] = $_SESSION[clientID];
	$data['tdai_purpose'] = $_POST[txtPurpose];
	$data['tdai_alertBy'] = $_POST[rdAlertBy];
	if($_POST[rdAlertBy] == "Date")
		$data['tdai_alertSrc'] = $_POST[txtAlertDate];
	else if($_POST[rdAlertBy] == "Meter")
		$data['tdai_alertSrc'] = $_POST[txtOdoMRead];
	else if($_POST[rdAlertBy] == "OverSpeed")
		$data['tdai_alertSrc'] = $_POST[sdMinSpeed]."#".$_POST[sdMaxSpeed];
	else if($_POST[rdAlertBy] == "OverStay")
		$data['tdai_alertSrc'] = $_POST[txtOverStay];
	else if($_POST[rdAlertBy] == "LowBattery")
		$data['tdai_alertSrc'] = $_POST[txtLowBattery];
	
	$data['tdai_status'] = 0;
	$data['tdai_alertType'] = $_POST[rdAlertType];
	$data['tdai_source'] = $_POST[txtContIds];
	$data['tdai_description'] = $_POST[txtAreaAlertDesc];
	$data['tdai_updateDate'] = date("Y-m-d H:i:s");
	
	//print_r($data);
	//exit;
	if($db->query_update("tb_device_alert_info", $data, "tdai_id=".$_POST[txtAlertId]))
		$res = 1;
	else $res = 0;
	
	header("location:?ch=status&au=23&msg=".$res);
	exit;
}
if(isset($_POST[cmdAddAlert]) && $_POST[cmdAddAlert]!='')
{
	$data['tdai_deviceId'] = $_POST[map_device_id];
	$data['tdai_clientId'] = $_SESSION[clientID];
	$data['tdai_purpose'] = $_POST[txtPurpose];
	$data['tdai_alertBy'] = $_POST[rdAlertBy];
	if($_POST[rdAlertBy] == "Date")
		$data['tdai_alertSrc'] = $_POST[txtAlertDate];
	else if($_POST[rdAlertBy] == "Meter")
		$data['tdai_alertSrc'] = $_POST[txtOdoMRead];
	else if($_POST[rdAlertBy] == "OverSpeed")
		$data['tdai_alertSrc'] = $_POST[sdMinSpeed]."#".$_POST[sdMaxSpeed];
	else if($_POST[rdAlertBy] == "OverStay")
		$data['tdai_alertSrc'] = $_POST[txtOverStay];
	else if($_POST[rdAlertBy] == "LowBattery")
		$data['tdai_alertSrc'] = $_POST[txtLowBattery];
		
	$data['tdai_status'] = 0;
	$data['tdai_alertType'] = $_POST[rdAlertType];
	$data['tdai_source'] = $_POST[txtContIds];
	$data['tdai_description'] = $_POST[txtAreaAlertDesc];
	
	//print_r($data);
	//exit;
	if($db->query_insert("tb_device_alert_info", $data))
		$res = 1;
	else $res = 0;
	
	header("location:?ch=status&au=22&msg=".$res);
	exit;
}
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
	$tanksizes = $_POST['tanksize'];
	$voltages = $_POST['voltage'];
	 $data_fuel['imei'] = $_POST[txtfImeiNo];

$query = "select * from tb_fuel where imei=".$data_fuel['imei']."";   
 $rows = $db->query($query);
  $resultRecord = $db->fetch_array($rows);
		 if($resultRecord[imei]!=""){
         $query1 = "DELETE from tb_fuel where imei=".$data_fuel['imei']."";   
        $rows1 = $db->query($query1);
		 }

	for($q='0';$q<count($tanksizes);$q++){
		
		$data_fuel['id'] = $q+1;
		$data_fuel['tanksize'] = $tanksizes[$q];
		$data_fuel['voltage'] = $voltages[$q];
        
		//echo $resultRecord[id_pk]; echo '<br>';
			 if($data_fuel['voltage']!="" &&$data_fuel['tanksize']!=""){
		   $db->query_insert("tb_fuel", $data_fuel);
			 }
		 

	}
	
/*echo '<pre>'; print_r($tanksizes);echo '</pre><br/>';
echo '<pre>'; print_r($voltages);echo '</pre><br/>';
exit;*/
	$data['di_deviceName'] = $_POST[txtDeviceName];
	$data['di_odoMeter'] = $_POST[txtOdometer];
	$data['di_deviceModel'] = $_POST[txtDeviceModel];

	if($_POST[txtNeedfuel]=='on'){
	 $data['di_fuel'] = '1';
	} else {
      $data['di_fuel'] = '0';
	}
	if($_POST['txtMobileNo'])
	$data['di_mobileNo'] = $_POST[txtMobileNo];
	
	$data['di_userId'] = $_POST[txtClientUserId];
	$data['di_clientId'] = $_POST[txtClientId];
	$data['di_updateDate'] = "NOW()";
	
	

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