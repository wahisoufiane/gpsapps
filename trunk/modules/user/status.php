<?php
//echo $_GET[au];
?>
<table class="detailsGrid" style="width:75%;" align="center">
<tr>
    <th colspan="3">Status</th>
</tr>
<tr>
    <tr>
        <td align="center">
        <?php
        switch($_GET[au])
        {
			case 1:
				if($_GET[msg] == 1)
					echo "SUCCESS: Add User";
				else
					echo "FAILED: Add User";
					
				$redirect = "viewUser";
			break;
			case 2:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update User";
				else
					echo "FAILED: Update User";
					
				$redirect = "viewUser";
			break;
			case 3:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Add Device";
				else
					echo "FAILED: Add Device";
					
				$redirect = "viewDevice";
			break;
			case 4:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Device";
				else
					echo "FAILED: Update Device";
					
				$redirect = "viewDevice";
			break;
			
			
			case 5:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Add Group";
				else
					echo "FAILED: Add Group";
					
				$redirect = "viewGroup";
			break;
			case 6:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Group";
				else
					echo "FAILED: Update Group";
					
				$redirect = "viewGroup";
			break;
			case 7:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Profile";
				else
					echo "SUCCESS: Update Profile. But may be Logo Upload Failed due to dupliation";
					
				$redirect = "profileAdmin";
			break;
			
			case 8:
				if($_GET[msg] == 1)
					echo "SUCCESS: Add Client";
				else
					echo "FAILED: Add Client";
					
				$redirect = "viewClient";
			break;
			case 9:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Client";
				else
					echo "FAILED: Update Client";
					
				$redirect = "viewClient";
			break;
			case 10:
				if($_GET[msg] == 1)
					echo "SUCCESS: Add Admin";
				else
					echo "FAILED: Add Admin";
					
				$redirect = "viewAdmin";
			break;
			case 11:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Admin";
				else
					echo "FAILED: Update Admin";
					
				$redirect = "viewAdmin";
			break;
			
			case 12:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Add Device";
				else
					echo "FAILED: Add Device";
					
				$redirect = "viewClient";
			break;
			case 13:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Device";
				else
					echo "FAILED: Update Device";
					
				$redirect = "viewClientDevice";
			break;
			
			case 14:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Add Subscription";
				else
					echo "FAILED: Add Subscription";
					
				$redirect = "viewClient";
			break;
			case 15:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Subscription";
				else
					echo "FAILED: Update Subscription";
					
				$redirect = "viewClient";
			break;
			
			case 16:
				if($_GET[msg] == 1)
					echo "SUCCESS: Add Reseller";
				else
					echo "FAILED: Add Reseller";
					
				$redirect = "viewReseller";
			break;
			case 17:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Reseller";
				else
					echo "FAILED: Update Reseller";
					
				$redirect = "viewReseller";
			break;
			case 18:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Geofence Added";
				elseif($_GET[msg] == 0)
					echo "FAILED: Geofence Adding";
				elseif($_GET[msg] == 2)
					echo "Status: Same data already exist";
					
				$redirect = "viewAssignGeo";
			break;
			case 19:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Geofence Updated";
				else
					echo "FAILED: Geofence not Updated";
					
				$redirect = "viewAssignGeo";
			break;
			case 20:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Insurance Added";
				else
					echo "FAILED: Insurance Adding";
					
				$redirect = "viewDevice";
			break;
			case 21:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Insurance Updated";
				else
					echo "FAILED: Insurance not Updated";
					
				$redirect = "viewDevice";
			break;
			
			case 22:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Alert Added";
				else
					echo "FAILED: Alert not Added";
					
				$redirect = "myalerts";
			break;
			case 23:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Alert Updated Successfully";
				else
					echo "FAILED: Alert not Updated";
					
				$redirect = "myalerts";
			break;
			
			
			
        }
        ?>
        
        </td>
    </tr>
    <tr align="center">
        <td><input type="button" name="cmdRedirect" id="cmdRedirect" onclick="location.href='?ch=<?php echo $redirect;?>';" value="Go" /></td>
    </tr>
</tr>
</table>
