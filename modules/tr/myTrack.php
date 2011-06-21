<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";

@session_start();
@ob_start();
//require("checkSession.php");

//	USER OR SUPERADMIN SESSION CHECK
if(isset($_SESSION[superID]))
	require("../sa/checkSession.php");
elseif(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
	require("../user/checkSession.php");
//exit;

error_reporting (E_ALL ^ E_NOTICE);	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<link href="css/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">

.map_heading{font: bold 15px/40px Arial, Helvetica, sans-serif; color:#ffffff; text-align:left;}

.table_info{

border-right:0px;
font:normal 12px/20px Arial, Helvetica, sans-serif;
color:#FFF;
}
.table_info td{

padding-left:2px;
}
.style1 {background-color:#333; font:bold 11px Arial, Helvetica, sans-serif; color:#fff; border:1px #000 solid; text-align:center; width:auto; margin:0px; position:absolute; bottom:20px; left:-35px; padding:2px;}
.style2 {background-color:#ffcccc;  font:bold 11px Arial, Helvetica, sans-serif; color:#333; border:1px #000 solid; text-align:center; width:100px; margin:0px; position:absolute; bottom:20px; left:-35px; padding:2px;}
.css1 {background-color:#FFFFCC; font:bold 11px Arial, Helvetica, sans-serif; color:#000; border:1px #000 solid; text-align:center; width:120px; height:15px; position:absolute; left:920px; bottom:20px; top:120px; padding:2px; z-index:1233 }
.css2 {background-color:#FFFFCC; font:bold 11px Arial, Helvetica, sans-serif; color:#000; border:1px #000 solid; text-align:center; width:120px; height:75px; overflow:scroll; overflow-X:hidden; position:absolute; left:920px; bottom:20px; top:140px; padding:2px; z-index:1233 }

.ui-layout-pane { /* all 'panes' */ 
	background: #FFF; 
	border: 1px solid #BBB; 
	padding: 5px; 
	overflow: auto;
} 

.ui-layout-resizer { /* all 'resizer-bars' */ 
	background: #DDD; 
} 

.ui-layout-toggler { /* all 'toggler-buttons' */ 
	background: red; 
} 
ul {
list-style-type: none;
margin: 0px;
}
.ui-layout-north ul ul {
	/* Drop-Down */
	bottom:		auto;
	margin:		0;
	margin-top:	1em;
}

</style>
<script language="javascript" src="javascript/ajax.js"></script>

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRR6gMao_Dcd5SMHBEzNxu-t4q-KNhQkTcrH6GvqMrKepHPXB9izcv_36w"
type="text/javascript"></script>


<script src="javascript/maps_off.js" type="text/javascript"></script>
<script src="javascript/elabel.js" type="text/javascript"></script>
<script language="javascript" src="javascript/anim.js"></script>
<script language="javascript" src="javascript/BdccArrowedPolyline.js"></script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.ui.all.js"></script>
<script type="text/javascript" src="js/jquery.layout.js"></script>

<script type="text/javascript">
//<![CDATA[
var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

$(document).ready(function () {
	myLayout = $('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true

	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
});

var routeSelect;
var allRoute;
var refreshSelect;
var messages;
var map;
var intervalID;
var newInterval;
var currentInterval;
var zoomLevelSelect;
var zoomLevel;

var mapPoints;
var hsp;
var interval;

function load(date_offline) {
	sessionid = '';
	//date_offline = "<?php echo date("d-m-Y");?>";
	zoomLevelSelect = document.getElementById('selectZoomLevel');
	messages = document.getElementById('messages');
	map = document.getElementById("map");
	geocoder = new GClientGeocoder();

	intervalID = 0;
	newInterval = 0;
	currentInterval = 0;
	zoomLevel = 16;
	//mapType=3;
	mapPoints=5;
	hsp=0;
	interval=1;
	
	
	if(date_offline!='')
	{
		var currentTime = new Date()
		var month = currentTime.getMonth() + 1
		var day = currentTime.getDate()
		var year = currentTime.getFullYear()
		to_day = year + "-" + month + "-" + day;
		
		var check_date1 = to_day.split('-');
		var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);
		
		var check_date2 = date_offline.split('-');
		var now_date2 = new Date(check_date2[0],check_date2[1]-1,check_date2[2]);
		
		var diff_date = now_date2 - now_date1;
		if(diff_date == 0)
		{
			for_Poly = 0;
		}
		else
		{
			for_Poly = 1;
		}
	}
	if(date_offline != '' || sessionid != '')
	{
		hasMap();
		//manRefresh();
		getAllVehicle(date_offline,sessionid);
	}
	else
	{
		showWait('No Data');
		map.innerHTML = '<img src="images/ajax-loader.GIF"' + 'style="width:25%; height:75%;">';
	}
	//zoomLevelSelect.selectedIndex = 14;
	//showWait('Loading Routes...');
}
function userLogout()
{
	location.href = 'userLogout.php';
}
 //]]>
/*var refreshId = setInterval(function()
{
     $('#liveFrame').fadeOut("slow").load('response.php').fadeIn("slow");
}, 10000);*/

function manRefresh()
{
	//$('#liveFrame').load('ViewTracker.php').fadeIn("slow");
	//document.getElementById('load_view').innerHTML='<span id="loading_txt">Loading...</span>';
	document.getElementById('liveFrame').src="ViewTracker.php";
}
 </script>

</head>

<body onLoad="load('<?php echo date("d-m-Y");?>')" onUnload="GUnload()" style="background:#666666;">

<div class="ui-layout-north" onMouseOver="myLayout.allowOverflow('north')" onMouseOut="myLayout.resetOverflow(this)">
<div class="headerarea">
    <div class="logoarea"><img src="images/logo.gif" width="198" height="69" /></div>
    
    <div class="statusBlock">
        <span id="messages">Loading...</span>
    </div>
    
    <div style="float:left; padding:10px 5px; margin-top:10px">
    	<input type="button" value="Close" class="login" onclick="window.close();" />
    </div>
  
  <div class="clear"></div>  
</div>
</div>

<div class="ui-layout-east">
    <table width="100%" class="detailsGrid">
        <tr>
            <th colspan="3">Parameters</th>
        </tr>
        <tr><td id="ctSpd">Speed </td></tr>
        <tr><td id="distance">Traveled&nbsp;&nbsp;: 0 </td></tr>
        <tr><td id="sts">Status&nbsp;&nbsp;: Null</td></tr>
        <tr><td id="posLatPt">Latitude &nbsp;:Null</td></tr>
        <tr><td id="posLongPt">Logntitude&nbsp;:Null</td></tr>
        <tr><td id="noSate">Satelite(s)&nbsp;: </td></tr>
    </table>
</div>

<div class="ui-layout-center" id="map"></div>

<div class="ui-layout-south" id="load_view">
	<iframe id="liveFrame" src="ViewTracker.php" style="border:#ebebeb solid 0px; margin:0px;" frameborder="0"  width="100%"></iframe>
</div>

</div>

</body>
</html>
