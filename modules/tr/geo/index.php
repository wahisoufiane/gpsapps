<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
@ob_start();
@session_start();
require_once("../../../includes/GPSFunction.php");

//require("checkSession.php");
error_reporting (E_ALL ^ E_NOTICE);	
//	USER OR SUPERADMIN SESSION CHECK
if(isset($_SESSION[superID]))
	require("../../sa/checkSession.php");
elseif(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
	require("../../user/checkSession.php");
//exit;

require("../../../includes/config.inc.php"); 
require("../../../includes/Database.class.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

$getUserInfo = "SELECT * FROM tb_userinfo,tb_clientinfo WHERE ui_id  = ".$_SESSION[userID]." AND ci_id = ".$_SESSION[clientID];
$resUserInfo = $db->query($getUserInfo);

if($db->affected_rows > 0){
	$recordUserInfo = $db->fetch_array($resUserInfo);
	//print_r($recordUserInfo);	
	if($recordUserInfo[ci_clientId]!=0)
	{
		$getResellInfo = "SELECT ci_clientLogo,ci_footerText FROM tb_clientinfo WHERE ci_id = ".$recordUserInfo[ci_clientId];
		$resResellInfo = $db->query($getResellInfo);
		if($db->affected_rows > 0){
			$fetResellInfo = $db->fetch_array($resResellInfo);
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

if(isset($_POST[date_offline]) && $_POST[date_offline])
	$date_offline = $_POST[date_offline];
else
	$date_offline = date('d-m-Y');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/mapCss.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../css/themes/base/jquery.ui.all.css">
<script language="javascript" src="../javascript/ajax.js"></script>

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;sensor=falsekey=<?php echo GOOGLE_API_KEY;?>" type="text/javascript"></script>

<script src="../../../js/jquery-1.4.2.js"></script>
<script src="../../../js/ui/jquery.ui.core.js"></script>
<script src="../../../js/ui/jquery.ui.widget.js"></script>

<script type="text/javascript" src="../js/jquery.layout.js"></script>

<script src="src/geometrycontrols.js" type="text/javascript"></script>
<script src="src/polygoncontrol.js" type="text/javascript"></script>
<script src="scripts/map_main.js" type="text/javascript"></script>
<style type="text/css">
	/* to be added to a central style sheet */
  .emmc-tooltip {
		border: 1px solid #666666;
		background-color: #ffffff;
		color: #444444;
		display:none;
	font-size:13px;
	padding:1px;
	}
  
  /* Doesn't work in ie :(
   #msim-icons * img:hover {
	border-color:#3D69B1;
  }*/
</style>
<script type="text/javascript">
var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

$(document).ready(function () {
	myLayout = $('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true

	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
});
initMenu();
function initMenu() {
	
  $('#menu ul').hide();
  $('#menu ul:first').show();
  $('#menu li a').click(
    function() {
      var checkElement = $(this).next();
      if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        return false;
        }
      if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        $('#menu ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
        return false;
        }
      }
    );
  }
$('#submenu li a.active_link').click( function() { alert('ss'); } );

function initMenu1() {
	
  $('#menu1 ul').hide();
  $('#menu1 ul:first').show();
  $('#menu1 li a').click(
    function() {
      var checkElement = $(this).next();
      if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        return false;
        }
      if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        $('#menu1 ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
        return false;
        }
      }
    );
  }

//]]>
</script>

</head>

<body >

<div class="ui-layout-north">
<div class="headerarea">
    <div class="logoarea"><img src="../../user/client_logo/<?php echo $clientLogo;?>" width="128" height="39" /></div>
    
    <div class="statusBlock" style="display:none">
        <span id="messages">Loading...</span>
    </div>
  
  <div class="clear"></div>  
</div>
</div>
<div class="ui-layout-west">
<div id="side_bar" style="height:100%; overflow-Y:scroll">Loading...</div>
</div>

<div class="ui-layout-center" id="map_canvas" style="width: 1200px; height: 500px">Loading...</div>



</div>

</body>
</html>
