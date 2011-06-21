<?php
class Util {
       
	   
	   public function getAnyUserInfo($userID,$clientID)
	   {
	   		$getAnyUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_id =".$userID." AND ui_clientId =".$clientID."
			  ORDER BY ui_firstname DESC";
			$resAnyUserInfo = mysql_query($getAnyUserInfo);
			$fetAnyUserInfo = mysql_fetch_assoc($resAnyUserInfo);
			return $fetAnyUserInfo;
	   }
	   
	   public function getRoleNameOfUserByRoleId($roleID)
	   {
			$getRoleName = "SELECT rli_roleName FROM tb_roleinfo WHERE rli_id =".$roleID;
			$resRoleName = mysql_query($getRoleName);
			$fetRoleName = mysql_fetch_array($resRoleName);
			return $fetRoleName[rli_roleName];
	   }
	   
	   public function getGroupNameOfUserByRoleId($groupID)
	   {
			$getGroupName = "SELECT gp_groupName FROM tb_groupinfo WHERE gp_id =".$groupID;
			$resGroupName = mysql_query($getGroupName);
			$fetGroupName = mysql_fetch_array($resGroupName);
			return $fetGroupName[gp_groupName];
	   }
	   public function getAllGroupsByUserId($userID)
	   {
			$getAllGroupsByUserId = "SELECT * FROM tb_groupinfo WHERE gp_createdUserId =".$userID." ORDER BY gp_groupName DESC";
			$resAllGroupsByUserId = mysql_query($getAllGroupsByUserId);
			$fetAllGroupsByUserId = mysql_fetch_array($resAllGroupsByUserId);
			return $fetAllGroupsByUserId;
	   }
	   
	   public function getDeviceInfoByDeviceId($deviceID)
	   {
			$getDeviceId = "SELECT di_deviceId FROM tb_deviceinfo WHERE di_id 	 =".$deviceID;
			$resDeviceId = mysql_query($getDeviceId);
			$fetDeviceId = mysql_fetch_array($resDeviceId);
			return $fetDeviceId[di_deviceId];
	   }
	   
	   public function getDeviceInfoByUserId($userID)
	   {
			$getDeviceId = "SELECT * FROM tb_deviceinfo WHERE di_status = 1 AND di_assignedUserId =".$userID;
			$resDeviceId = mysql_query($getDeviceId);			
			return $resDeviceId;
	   }
	   
	   public function getRoles()
	   {
			$getRoles = "SELECT * FROM FROM tb_roleinfo"; 	
			$resRoles = mysql_query($getRoles);
			$fetRoles = mysql_fetch_array($resRoles);
			return $fetRoles;
	   }
       
}
?>