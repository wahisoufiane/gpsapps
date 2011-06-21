<?php
@ob_start();
@session_start();
include("checkSession.php");
error_reporting (E_ALL ^ E_NOTICE);
require_once("../../includes/GPSFunction.php");

//require_once("User.php");

//	DB Connection
require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 
//echo $_SERVER['HTTP_HOST'];
include("Util.php"); 
$util =  new Util();


$getfuelInfo = "SELECT count(*) as cnt FROM tb_deviceinfo WHERE di_userId = ".$_SESSION[userID]." and di_fuel=1";
$resfuelInfo = $db->query($getfuelInfo);
$recordfuelInfo = $db->fetch_array($resfuelInfo);

// Get User Information
$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_accessFlag  = 1 AND ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_SESSION[clientID];
$resUserInfo = $db->query($getUserInfo);

if($db->affected_rows > 0){
	$recordUserInfo = $db->fetch_array($resUserInfo);
	//print_r($recordUserInfo);	
	if($recordUserInfo[ci_clientId]!=0)
	{
		$getResellInfo = "SELECT ci_clientLogo,ci_footerText,ci_googleApiKey FROM tb_clientinfo WHERE ci_id = ".$recordUserInfo[ci_clientId];
		$resResellInfo = $db->query($getResellInfo);
		if($db->affected_rows > 0){
			$fetResellInfo = $db->fetch_array($resResellInfo);
			//print_r($fetResellInfo);
			$_SESSION[GoogleMapApi] = $fetResellInfo[ci_googleApiKey];
			$clientLogo = $fetResellInfo[ci_clientLogo];
			$clientFooter = $fetResellInfo[ci_footerText];
		}
		else
		{
			$clientLogo = $recordUserInfo[ci_clientLogo];
			$clientFooter = $recordUserInfo[ci_footerText];
		}
	}
	else
	{
		$clientLogo = $recordUserInfo[ci_clientLogo];
		$clientFooter = $recordUserInfo[ci_footerText];
	}
	$welcomeTxt = '';
	if($recordUserInfo[ui_isAdmin] == 1)
	{
		$welcomeTxt = 'Admin';
	}
	elseif($recordUserInfo[ui_roleId])
	{
		
		$welcomeTxt = $util->getRoleNameOfUserByRoleId($recordUserInfo[ui_roleId]);
	}
	
}
else
{
	header("location:userLogout.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="all">@import "../../css/timePicker.css";</style>
<link rel="stylesheet" href="../../css/themes/base/jquery.ui.all.css">

<script src="../../js/ajax.js" type="text/javascript"></script>
<script src="../../js/gen_validatorv31.js" type="text/javascript"></script>

<script src="js/jquery-1.4.4.min.js" type="text/javascript"></script>
<script src="../../js/ui/jquery.ui.core.js"></script>
<script src="../../js/ui/jquery.ui.widget.js"></script>
<script src="../../js/ui/jquery.ui.datepicker.js"></script>

<script type="text/javascript" src="../../js/jquery.layout.js"></script>
<script type="text/javascript" src="../../js/timePicker.js"></script>

<script type="text/javascript" src="../../js/highcharts.js"></script>
<script type="text/javascript" src="../../js/modules/exporting.js"></script>

<link rel="stylesheet" href="css/validationEngine.jquery.css" type="text/css"/>
<link rel="stylesheet" href="css/template.css" type="text/css"/>
<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
<script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>


<link rel="stylesheet" type="text/css" href="css/chromestyle2.css" />
<script type="text/javascript" src="js/chrome.js"></script>

<script language="javascript">
function userLogout()
{
	location.href = 'userLogout.php';
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
<!-- CSS -->
<style>
</style>

</head>

<body>
<div class="wrapper"><!-- wrapper div start from here -->
	<div class="headerarea"><!-- headerarea div start here -->

    	<div class="logoarea"><img src="client_logo/<?php echo $clientLogo;?>" width="198" height="69" />
        <span>V 1.0</span>
        </div>
        <div class="welcome_user">
        	<p><strong>Welcome</strong> : <span class="ui-widget-header"> <?php echo ucfirst($recordUserInfo[ci_clientName]." (".$recordUserInfo[ci_clientType].")-".$welcomeTxt." - ".$recordUserInfo[ui_firstname]." ".$recordUserInfo[ui_lastname]);?></span>  | 
            <?php if($recordUserInfo[ui_isAdmin] == 1) { ?>
            <a class="ui-state-error-text" href="#" onclick="location.href='?ch=profileAdmin';">Profile</a> 
            <?php } else { ?>
            <a class="ui-state-error-text" href="#" onclick="location.href='?ch=profile';">Profile</a> 
            <?php } ?>
            | <a class="ui-state-error-text" href="#" onclick="javascript:userLogout();">Logout</a></p><br />
            <p style="display:none;">Your last login is on dd/mm/yyyy, HH:MM</p>
        </div>
        <div class="menuarea" id="chromemenu">
		<div style="float: left; width: 81%;">
        <ul>
        <li><a href="#" onclick="location.href='?ch=home';">Home</a></li>  
        
        <?php if($recordUserInfo[ci_clientType] == "Reseller" && $recordUserInfo[ui_isAdmin] == "1") { ?>
        <li><a href="#" rel="dropmenu1">Account</a></li>
        <li><a href="#" rel="dropmenu9">Reports</a></li>
        <?php } ?>
        
        <?php if(($recordUserInfo[ci_clientType] == "Client") && $recordUserInfo[ui_isAdmin] == "1") { ?>
        <li><a href="#" rel="dropmenu2">Management</a></li>        
        <li><a href="#" rel="dropmenu3">Reports</a></li>
        <li><a href="#" rel="dropmenu6">Graphs</a></li>
        <li><a href="#" rel="dropmenu4">GeoFence</a></li>	
        <?php } else if( ($recordUserInfo[ci_clientType] == "Client") && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1") {?>
        <li><a href="#" rel="dropmenu3">Reports & Graphs</a></li>
        <?php } ?>
        
        <li><a href="../tr" class="top_link" target="_blank">Map</a></li>
        </ul>
		</div>

        <div class="datearea">Date : <input type="text" name="index_date" size="12" value="<?php echo date("d F Y");?>" style="border:0px; background:#1451CD; color:#FFF; font:Arial, Helvetica, sans-serif 12px bold;" id="index_date" class="" readonly="true"/></div>
        </div>
        <!--1st drop down menu -->                                                   
        <div id="dropmenu1" class="dropmenudiv">
        	<a href="#" onclick="location.href='?ch=viewClient';">Client</a>
            <a href="#" onclick="location.href='?ch=viewReseller';">Reseller</a>
            <a href="#" onclick="location.href='?ch=viewSimcard';">Sim Card</a>
        </div>
         <div id="dropmenu9" class="dropmenudiv">
        	<a href="#" onclick="location.href='?ch=subscription_report';">Subscription Reports</a>
        	<a href="#" onclick="location.href='?ch=simcard_report';">Simcard Reports</a>
        </div>
        
        <!--2nd drop down menu -->                                                
        <div id="dropmenu2" class="dropmenudiv" style="width: 150px;">
        	<a href="#" onclick="location.href='?ch=viewUser';">My User</a>
            <a href="#" onclick="location.href='?ch=viewDevice';">My Device</a>
			<a href="#" onclick="location.href='?ch=viewContact';">My Contacts</a>
            <a href="#" onclick="location.href='?ch=myalerts';">My Alerts</a>
            <a href="#" onclick="location.href='?ch=deviceParams';">My Decive Parameters</a>
        </div>
        
        <!--3rd drop down menu -->                                                   
        <div id="dropmenu3" class="dropmenudiv" style="width: 150px;">
        	<a href="#" onclick="location.href='?ch=daily';">Trip Report</a>
            <a href="#" onclick="location.href='?ch=geofence';">Geofence Report</a>
            <a href="#" onclick="location.href='?ch=stop';">Stop Report</a>
            <a href="#" onclick="location.href='?ch=distance';">Distance Report</a>
            <a href="#" onclick="location.href='?ch=ac'">AC Report</a>
            <a href="#" onclick="location.href='?ch=ignition'">Ignition Report</a>
			<?php if($recordfuelInfo[cnt]>='1'){?>
            <a href="#" onclick="location.href='?ch=fuel'">Fuel Report</a>
			<?php }?>
            <a href="#" onclick="location.href='?ch=idlereport'">Idle Report</a>
             </div>
<div id="dropmenu6" class="dropmenudiv" style="width: 150px;">

            <a href="#" onclick="location.href='?ch=speed'">Speed Graph</a>
            <a href="#" onclick="location.href='?ch=altitute'">Altitute Graph</a>
            <!-- <a href="#" onclick="location.href='?ch=fuel_graph'">Fuel Graph</a>
 -->             </div>
       
        <div id="dropmenu4" class="dropmenudiv" style="width: 150px;">
        	<a href="../tr/geofence/geocode.php" target="_blank">Create</a>
            <a href="#" onclick="location.href='?ch=viewAssignGeo';">Assign</a>
        </div>
        
         <div id="dropmenu5" class="dropmenudiv">
        	<a href="#" class="fly" onclick="location.href='?ch=viewUser';">Employee</a>
        </div>
        
      <div class="clear"></div>  
    </div><!-- headerarea div END here -->
	<div class="pagearea"><!-- Pagearea div start here -->
    	<div align="center" id="mainAreaInternal">
            <?php			
			  switch($_GET[ch]) 
			  {
				case 'myalerts':
				   include("myalerts.php");
				   break;
				 case 'deviceParams':
					 include("mydeviceparams.php");
				 break;
				case 'fuel':
				   include("../report/fuelConsumeReport.php");
				   break;
				   case 'fuel_graph':
				   include("../report/fuelConsumeGraph.php");
				   break;
				case 'viewAssignGeo':
				   include("viewAssignGeo.php");
				   break;
				case 'viewContact':
				   include("viewContact.php");
				   break;
			  	case 'insurance':
				   include("insurance.php");
				   break;
			  	case 'manageDevice':
				   include("manageDevice.php");
				   break;
				case 'assignGeofence':
				   include("assignGeofence.php");
				   break;
				case 'geofence':
				   include("../report/geofence.php");
				   break;
				case 'altitute':
				   include("../report/altitute.php");
				   break;
				 
				case 'speed':
				   include("../report/speed.php");
				   break;
                case 'idlereport':
				  include("../report/idlereport.php");
				break;
				
				case 'ac':
				  include("../report/acreport.php");
				break;
				case 'ignition':
					include('../report/igreport.php');
				break;
				case 'distance':
				   include("../report/distance.php");
				   break;
				case 'stop':
				   include("../report/stop.php");
				   break;
				case 'daily':
				   include("../report/daily.php");
				   break;
				case 'viewResellerSubscription':
				   include("viewResellerSubscription.php");
				   break;
			  	case 'addResellerSubscription':
				   include("addResellerSubscription.php");
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
				case 'mySubscription':
				   include("mySubscription.php");
				   break;
				case 'Subscription':
				   include("Subscription.php");
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
				case 'viewClientDevice':
				   include("viewClientDevice.php");
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
			  	case 'Client':
				   include("Client.php");
				   break;
				case 'addClient':
				   include("addClient.php");
				   break;
			  	case 'profileAdmin':
				   include("profileAdmin.php");
				   break;
			  	case 'profile':
				   include("profile.php");
				   break;
			  	case 'groupMembers':
				   include("groupMembers.php");
				   break;
			  	case 'Group':
				  include("Group.php");
				  break;
			    case 'viewGroup':
				  include("viewGroup.php");
				  break;
			  	case 'Device':
				  include("Device.php");
				  break;
			  	case 'addDevice':
				  include("addDevice.php");
				  break;
			  	case 'viewDevice':
				  include("viewDevice.php");
				  break;
			  	case 'viewUser':
				  include("viewUser.php");
				  break;
			  	case 'status':
				  include("status.php");
				  break;
			  	case 'User':
				  include("User.php");
				  break;
				case 'home':
				  include("home.php");
				  break;
				case 'addUser':
				  include("addUser.php");
				  break;

				case 'subscription_report':
                  include("../report/subscription.php");
				break;
               
				case 'viewSimcard':
                  include("viewSimcard.php");
				break;
				case 'addSimcard':
                  include("addSimcard.php");
				break;
                 case 'simcard_report':
				  include("../report/simcard_report.php");
                 break;
				default:
				  include("home.php");
				  break;
			  }
			?>
        </div>
        
    </div><!-- PAGEarea END here -->
	<div class="footerarea"><!-- Footerarea div start here -->
    <p align="right"><?php echo $clientFooter;?></p>
    </div><!-- Footerarea div End here -->
<div class="clear"></div>
</div><!-- wrapper div END here -->
</body>
</html>
<script type="text/javascript">

cssdropdown.startchrome("chromemenu")

</script>
<?php

$db->close();

?>