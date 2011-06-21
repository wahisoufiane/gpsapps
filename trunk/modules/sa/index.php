<?php
@ob_start();
@session_start();
require("checkSession.php");
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");

//	DB Connections
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

include("Util.php"); 
$util =  new Util();
// Get Superadmin Information
$sql = "SELECT * FROM tb_superadmininfo WHERE sai_id  = ".$_SESSION[superID];
$rows = $db->query($sql);

if($db->affected_rows > 0){
	$record = $db->fetch_array($rows);
	//print_r($record);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />


<script src="../../js/ajax.js" type="text/javascript"></script>
<script src="../../js/gen_validatorv31.js" type="text/javascript"></script>

<link rel="stylesheet" href="../../css/themes/base/jquery.ui.all.css">
<script src="../../js/jquery-1.4.2.js"></script>
<script src="../../js/ui/jquery.ui.core.js"></script>
<script src="../../js/ui/jquery.ui.widget.js"></script>
<script src="../../js/ui/jquery.ui.datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="css/chromestyle2.css" />
<script type="text/javascript" src="js/chrome.js"></script>


<script language="javascript">
function userLogout()
{
	location.href = 'saLogout.php';
}
$(function() {
	$( "#index_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calender.gif",
		buttonImageOnly: true,
		dateFormat : "dd MM yy"
	});
});
</script>
</head>

<body>
<div class="wrapper"><!-- wrapper div start from here -->
	<div class="headerarea"><!-- headerarea div start here -->
    	<div class="logoarea"><img src="images/logo.gif" width="198" height="69" /></div>
        <div class="welcome_user">
        	<p class="chromestyle"><strong>Welcome</strong> : <span class="ui-widget-header"><?php echo ucfirst($record[sai_firstName]." ".$record[sai_lastName]);?></span> | <a href="#" class="ui-state-error-text" onclick="javascript:userLogout();">Logout</a></p><br />
            <p style="display:none;">Your last login is on dd/mm/yyyy, HH:MM</p>
        </div>
        
         <div class="menuarea" id="chromemenu">
        	 <ul>
        	   <li><a href="#" onclick="location.href='?ch=home';">Home</a></li>  
               <!--<li><a href="#" onclick="loadContent(1);">Alerts</a></li>-->
               <li><a rel="dropmenu1" href="#" onclick="loadContent(1);">Account</a></li>
               <li><a href="#" onclick="location.href='../tr/track.php';" target="_blank">Live</a></li>
               <!--<li><a href="#" onclick="loadContent(1);">External Link</a></li>-->
            </ul>
            	<div id="dropmenu1" class="dropmenudiv">
                	<a href="#" class="fly" onclick="location.href='?ch=viewClient';">Client</a>
                    <a href="#" class="fly" onclick="location.href='?ch=viewReseller';">Reseller</a>
                </div>
            <div class="datearea">Date : <input type="text" name="index_date" size="20" value="<?php echo date("d F Y");?>" style="border:0px; background:#1451CD; color:#FFF; font:Arial, Helvetica, sans-serif 12px bold;" id="index_date" class="" readonly="true"/></div>
            
        </div>
        
      <div class="clear"></div>  
    </div><!-- headerarea div END here -->
	<div class="pagearea"><!-- Pagearea div start here -->
    	<div align="center">
    		<?php
			
			  switch($_GET[ch]) {
			  
			  	case 'viewResellerSubscription':
				   include("viewResellerSubscription.php");
				  break;
			  	case 'addResellerSubscription':
				   include("addResellerSubscription.php");
				  break;
			  	case 'Subscription':
				   include("Subscription.php");
				  break;
				case 'Device':
				   include("Device.php");
				  break;
			  	case 'addSubscription':
				   include("addSubscription.php");
				  break;
				case 'viewSubscription':
				   include("viewSubscription.php");
				  break;
				case 'addClientDevice':
				   include("addClientDevice.php");
				  break;
				case 'viewResellClientDevice':
				   include("viewResellClientDevice.php");
				  break;
				case 'viewClientDevice':
				   include("viewClientDevice.php");
				  break;
			  	case 'viewReseller':
				   include("viewReseller.php");
				  break;
			  	case 'addReseller':
				   include("addReseller.php");
				  break;
			  	case 'Reseller':
				   include("Reseller.php");
				  break;
			  	case 'Admin':
				   include("Admin.php");
				  break;
			  	case 'addAdmin':
				   include("addAdmin.php");
				  break;
			  	case 'viewAdmin':
				   include("viewAdmin.php");
				  break;
			  	case 'viewClient':
				   include("viewClient.php");
				  break;
			  	case 'status':
				   include("status.php");
				  break;
			  	case 'Client':
				   include("Client.php");
				  break;
				case 'home':
				  include("home.php");
				  break;
				case 'addClient':
				   include("addClient.php");
				  break;
				default:
				  include("home.php");
				  break;
			  }
			?>
        </div>
        
    </div><!-- PAGEarea END here -->
	
    <div class="footerarea"><!-- Footerarea div start here -->
    <p align="right">All Rights Reserved to KEYSTONE UNIQUE INFO PRIVATE LIMITED. Copyrights 2010</p>
    </div><!-- Footerarea div End here -->
<div class="clear"></div>
</div><!-- wrapper div END here -->
</body>
</html>
<script type="text/javascript">

cssdropdown.startchrome("chromemenu")

</script>