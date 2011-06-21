<?php
//print_r($_POST);
//	ADD USER INFORMATION
if(isset($_POST[cmdSubmitAddSubs]) && $_POST[cmdSubmitAddSubs]!='')
{
$data['tcs_clientId'] = $_POST[txtClientId];
$data['tcs_deviceId'] = $_POST[txtDeviceId];
$data['tcs_renewalDateFrom'] = date("Y-m-d",strtotime($_POST[txtRenewalDate]));
$data['tcs_noOfMonths'] = $_POST[txtNoofMonth];
$data['tcs_reminderDays'] = $_POST[txtRemindDay];
$data['tcs_amount'] = $_POST[txtSubsAmt];
$data['tcs_payType'] = $_POST[selPayType];
$data['tcs_chequeNo'] = $_POST[txtChequeNo];
$data['tcs_resellerId'] = 0;

//print_r($data);
//exit;

if($db->query_insert("tb_client_subscription", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=14&msg=".$res);
exit;

}


//		UPDATE USER INFORMATION
if(isset($_POST[cmdSubmitUpdateSubs]) && $_POST[cmdSubmitUpdateSubs]!='')
{
$data['tcs_clientId'] = $_POST[txtClientId];
$data['tcs_deviceId'] = $_POST[txtDeviceId];
$data['tcs_renewalDateFrom'] = date("Y-m-d",strtotime($_POST[txtRenewalDate]));
$data['tcs_noOfMonths'] = $_POST[txtNoofMonth];
$data['tcs_reminderDays'] = $_POST[txtRemindDay];
$data['tcs_amount'] = $_POST[txtSubsAmt];
$data['tcs_payType'] = $_POST[selPayType];
$data['tcs_chequeNo'] = $_POST[txtChequeNo];
$data['tcs_resellerId'] = 0;

$data['tcs_updateDate'] = "NOW()";

//print_r($data);
//exit;

if($db->query_update("tb_client_subscription", $data , "tcs_id=".$_POST[txtSubsId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=15&msg=".$res);
exit;

}


if(isset($_POST[cmdSubmitAddResellSubs]) && $_POST[cmdSubmitAddResellSubs]!='')
{
$resell_subs_data['trs_clientId'] = $_POST[txtClientId];
$resell_subs_data['trs_resellerId'] = 0;
$resell_subs_data['trs_renewalDateFrom'] = date("Y-m-d",strtotime($_POST[txtSubStartDate]));;
$resell_subs_data['trs_noOfMonths'] = $_POST[txtNoofMonth];
$resell_subs_data['trs_reminderDays'] = $_POST[txtRemindDay];
$resell_subs_data['trs_amount'] = $_POST[txtSubsAmt];
$resell_subs_data['trs_payType'] = $_POST[selPayType];
$resell_subs_data['trs_chequeNo'] = $_POST[txtChequeNo];

//print_r($resell_subs_data);
//exit;

if($db->query_insert("tb_reseller_subscription", $resell_subs_data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=16&msg=".$res);
exit;

}

if(isset($_POST[cmdSubmitUpdateResellSubs]) && $_POST[cmdSubmitUpdateResellSubs]!='')
{
$resell_subs_data['trs_resellerId'] = 0;
$resell_subs_data['trs_renewalDateFrom'] = date("Y-m-d",strtotime($_POST[txtSubStartDate]));;
$resell_subs_data['trs_noOfMonths'] = $_POST[txtNoofMonth];
$resell_subs_data['trs_reminderDays'] = $_POST[txtRemindDay];
$resell_subs_data['trs_amount'] = $_POST[txtSubsAmt];
$resell_subs_data['trs_payType'] = $_POST[selPayType];
$resell_subs_data['trs_chequeNo'] = $_POST[txtChequeNo];
$resell_subs_data['trs_updateDate'] = "NOW()";

//print_r($resell_subs_data);
//exit;

if($db->query_update("tb_reseller_subscription", $resell_subs_data, "trs_clientId=".$_POST[txtClientId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=17&msg=".$res);
exit;

}

?>