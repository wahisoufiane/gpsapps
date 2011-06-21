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
					echo "SUCCESS: Add Client";
				else
					echo "FAILED: Add Client";
					
				$redirect = "viewClient";
			break;
			case 2:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Client";
				else
					echo "FAILED: Update Client";
					
				$redirect = "viewClient";
			break;
			case 3:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Add Admin";
				else
					echo "FAILED: Add Admin";
					
				$redirect = "viewAdmin";
			break;
			case 4:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Admin";
				else
					echo "FAILED: Update Admin";
					
				$redirect = "viewAdmin";
			break;
			case 5:
				if($_GET[msg] == 1)
					echo "SUCCESS: Add Reseller";
				else
					echo "FAILED: Add Reseller";
					
				$redirect = "viewReseller";
			break;
			case 6:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Reseller";
				else
					echo "FAILED: Update Reseller";
					
				$redirect = "viewReseller";
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
					
				$redirect = "viewClient";
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
					echo "SUCCESS: Add Reseller Subscription";
				else
					echo "FAILED: Add Reseller Subscription";
					
				$redirect = "viewReseller";
			break;
			case 17:
				 if($_GET[msg] == 1)
					echo "SUCCESS: Update Reseller Subscription";
				else
					echo "FAILED: Update Reseller Subscription";
					
				$redirect = "viewReseller";
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
