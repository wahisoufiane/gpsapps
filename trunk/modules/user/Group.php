<?php
print_r($_POST);
//	ADD GROUP INFORMATION

if(isset($_POST[cmdSubmitAddGroup]) && $_POST[cmdSubmitAddGroup]!='')
{
	$txtGroupName = str_replace(" ","_",$_POST[txtGroupName]);

	$sql = "SELECT * FROM tb_groupinfo WHERE gp_createdClientId = ".$_SESSION[clientID]." AND gp_groupName = '".$txtGroupName."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=5&msg=0");
		exit;
	}
	else $data['gp_groupName'] = $txtGroupName;

$data['gp_description'] = $_POST[txtGPDesc];
$data['gp_createdUserId'] = $_SESSION[userID];
$data['gp_createdClientId'] = $_SESSION[clientID];

if($_POST[txtGPStatus])
	$data['gp_isActive'] = $_POST[txtGPStatus];
else
	$data['gp_isActive'] = 0;	

//print_r($data);

if($db->query_insert("tb_groupinfo", $data))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=5&msg=".$res);
exit;

}

//		UPDATE GROUP INFORMATION
if(isset($_POST[cmdSubmitUpdateGroup]) && $_POST[cmdSubmitUpdateGroup]!='')
{
	$txtGroupName = str_replace(" ","_",$_POST[txtGroupName]);

	$sql = "SELECT * FROM tb_groupinfo WHERE gp_createdClientId = ".$_SESSION[clientID]." AND gp_groupName = '".$txtGroupName."'";
	$rows = $db->query($sql);
	if($db->affected_rows)
	{
		header("location:?ch=status&au=6&msg=0");
		exit;
	}
	else $data['gp_groupName'] = $txtGroupName;

	
$data['gp_description'] = $_POST[txtGPDesc];
$data['gp_updatedDate'] = "NOW()";

if($_POST[txtGPStatus])
	$data['gp_isActive'] = $_POST[txtGPStatus];
else
	$data['gp_isActive'] = 0;	

//print_r($data);

if($db->query_update("tb_groupinfo", $data , "gp_id=".$_POST[txtGroupId]))
	$res = 1;
else $res = 0;

header("location:?ch=status&au=6&msg=".$res);
exit;

}


?>