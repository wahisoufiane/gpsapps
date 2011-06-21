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


$getCont =  "select * from tb_geofence_info where tgi_isActive = 1 AND tgi_clientId =".$_SESSION[clientID];
$resCont = $db->query($getCont);
if($db->affected_rows > 0)
{
	while($fetCont = $db->fetch_array($resCont))
	{
		//print_r($fetCont);
		$param .= $fetCont[tgi_radius].",".$fetCont[tgi_latLong]."@";
		$ptName .= $fetCont[tgi_name ]."$";
	}
?>
<script language="javascript">
geoData= '<?php echo $param;?>';
gfData='<?php echo $_GET[gfd];?>';
stData= '<?php echo $ptName;?>';
</script>
<?php
}
else
{
?>
<script language="javascript">
geoData= '';
gfData='<?php echo $_GET[gfd];?>';
</script>
<?php
}
//exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head><title>GPS :: Geofence</title>
<link rel="Shortcut Icon" type="image/x-icon" href="../images/favicon.png" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>

<link href="../css/style.css" rel="stylesheet" type="text/css" />
<link href="../css/mapCss.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../../../css/themes/base/jquery.ui.all.css">

<script language="javascript" src="js/ajax.js"></script>


<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo GOOGLE_API_KEY;?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&key=<?php echo GOOGLE_API_KEY;?>" type="text/javascript"></script>
<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js?adsense=pub-1227201690587661" type="text/javascript"></script>

<script language="javascript" src="js/geoscript.js"></script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.ui.all.js"></script>
<script type="text/javascript" src="../js/jquery.layout.js"></script>

<script type="text/javascript" src="js/complex.js"></script>


<style type="text/css">
  @import url("gsearch.css");
  @import url("gmlocalsearch.css");


.ui-layout-pane { /* all 'panes' */ 
	background: #FFF; 
	border: 1px solid #BBB; 
	padding: 10px; 
	overflow: auto;
} 

.ui-layout-resizer { /* all 'resizer-bars' */ 
	background: #DDD; 
} 

.ui-layout-toggler { /* all 'toggler-buttons' */ 
	background: #AAA; 
} 
</style>
<!--[if IE]> 

<style type="text/css" media="all" >
img { behavior: url("../../scripts/pngbehavior.htc"); }
 
 body {
 behavior: url(../../scripts/csshover.htc); }
 
</style>
<![endif]-->

<!--[if IE]> 
 <style type="text/css" media="screen">
 body {
 overflow:visible;
 }

</style>
<![endif]-->
<script type="text/javascript">

var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

$(document).ready(function () {
	myLayout = $('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true

	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
});
function initMenu() {
	
  $('#menu3 ul').hide();
  $('#menu3 ul:first').show();
  $('#menu3 li a').click(
    function() {
      var checkElement = $(this).next();
      if((checkElement.is('ul')) && (checkElement.is(':visible'))) {
        return false;
        }
      if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
        $('#menu3 ul:visible').slideUp('normal');
        checkElement.slideDown('normal');
        return false;
        }
      }
    );
  }
$('#submenu li a.active_link').click( function() { alert('ss'); } );
</script>

</head>
<body onLoad="displayMap();" style="background:#fff">
<div class="ui-layout-north">
<div class="headerarea">
    <div class="logoarea"><img src="../../user/client_logo/<?php echo $clientLogo;?>" width="128" height="39" /></div>
    
    <div class="statusMsg"> 
        <span id="messages">Loading...</span>
    </div>
  
  <div class="clear"></div>  
</div>
</div>
<div class="ui-layout-west">
<div id="side_bar" style="display:none;">Loading...</div>
<span id="route" style="display:none;">Route points:</span>
<ul id="menu3">
</ul>    

</div>

<div class="ui-layout-center" id="map">Loading...</div>



</div>
</body>
</html>
