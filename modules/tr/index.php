<?php
@ob_start();
@session_start();
require_once("../../includes/GPSFunction.php");
/*echo "<pre>";
print_r($_SESSION);
echo "</pre>";
exit;
*///require("checkSession.php");
error_reporting (E_ALL ^ E_NOTICE);	
//	USER OR SUPERADMIN SESSION CHECK
/*if(isset($_SESSION[superID]))
	require("../sa/checkSession.php");
else*/
//if(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
	require("../user/checkSession.php");
	
//exit;

require("../../includes/config.inc.php"); 
require("../../includes/Database.class.php"); 

include("../user/Util.php"); 
$util =  new Util();

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
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/mapCss.css" rel="stylesheet" type="text/css" />
<style type="text/css" media="all">@import "css/timePicker.css";</style>
<link rel="stylesheet" href="../../css/themes/base/jquery.ui.all.css">

<style type="text/css">
ul#menu11 li a {
background:#02111F none repeat scroll 0 0;
color:#FFB900;
padding:0.5em;
}
ul#menu11 a {
display:block;
text-decoration:none;
}
</style>

<script language="javascript" src="javascript/ajax.js"></script>

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo GOOGLE_API_KEY;?>" type="text/javascript"></script>
<!--<script language="javascript" src="javascript/progressbarcontrol.js"></script>
-->
<script src="javascript/maps_off.js" type="text/javascript"></script>
<script src="javascript/elabel.js" type="text/javascript"></script>
<script src="javascript/mapiconmaker.js" type="text/javascript"></script>
<script src="javascript/BdccArrowedPolyline.js" type="text/javascript"></script>

<script src="../../js/jquery-1.4.2.js"></script>
<script src="../../js/ui/jquery.ui.core.js"></script>
<script src="../../js/ui/jquery.ui.widget.js"></script>
<script src="../../js/ui/jquery.ui.datepicker.js"></script>

<script type="text/javascript" src="js/jquery.layout.js"></script>
<script type="text/javascript" src="js/timePicker.js"></script>


<script type="text/javascript">
//<![CDATA[
var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method
$(document).ready(function () {
	myLayout = $('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		//west__showOverflowOnHover: true

	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
});
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

var routeSelect;
var allRoute;
var refreshSelect;
var messages;
var map;
var intervalID;
var newInterval = 10;
var currentInterval;
var zoomLevelSelect;
var zoomLevel;
var autoRefresh = 1;
var mapPoints;
var hsp;
var interval;
var date_offline;

function load(date_offline,sessionid) 
{
	//date_offline = "<?php echo date("d-m-Y");?>";
	routeSelect = document.getElementById('selectRoute');
	refreshSelect = document.getElementById('selectRefresh');
	zoomLevelSelect = document.getElementById('selectZoomLevel');
	messages = document.getElementById('messages');
	map = document.getElementById("map");
	geocoder = new GClientGeocoder();

	intervalID = 0;
	newInterval = 30;
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
$(function() {
	$( "#from_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
$(function() {
	$( "#to_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});


jQuery(function() 
{
    // Default.
    
    // Use default settings
    //$("#time3, #time4").timePicker();
	 $("#time3, #time4").timePicker({
	  startTime: "00:01", // Using string. Can take string or Date object.
	  endTime: "23:59", // Using Date object here.
	  show24Hours: true,
	  separator: ':',
	  step: 1});    
        
    // Store time used by duration.
    var oldTime = $.timePicker("#time3").getTime();
    
    // Keep the duration between the two inputs.
    $("#time3").change(function() {
      if ($("#time4").val()) { // Only update when second input has a value.
        // Calculate duration.
        var duration = ($.timePicker("#time4").getTime() - oldTime);
        var time = $.timePicker("#time3").getTime();
        // Calculate and update the time in the second input.
        $.timePicker("#time4").setTime(new Date(new Date(time.getTime() + duration)));
        oldTime = time;
      }
    });
    // Validate.
    $("#time4").change(function() {
      if($.timePicker("#time3").getTime() > $.timePicker(this).getTime()) {
        $(this).addClass("error");
      }
      else {
        $(this).removeClass("error");
      }
    });
    
  });

var ajax1=new sack();
refreshMapTable("","<?php echo $_SESSION[clientID];?>","<?php echo $date_offline;?>");
function showMapForDate(val)
{	
	document.getElementById('mapTable').innerHTML = "Loading...";
	document.getElementById('showAll').className = 'showlive_off';
	hasMap();
	autoRefresh = 0;
	clearInterval(intervalID);
	pickThisDevice(0);
	document.frmMapData.cmdPlay.value = "Play";
	document.frmMapData.cmdPlay.disabled = true;
	if(route) clearTimeout(route);
	 stopClick = false;
	 count = 0;
	 myMarker = "";
	//document.getElementById('map').innerHTML = "Loading...";
	getAllVehicle(val,'<?php echo $_SESSION[clientID];?>');
	refreshMapTable("","<?php echo $_SESSION[clientID];?>",val);	
}

//var refreshId = setInterval("refreshMapTable("+1+","+<?php echo $date_offline;?>+")", 1000);

function refreshMapTable(divid,sessionid,date_offline)
{
	//document.getElementById('mapTable').innerHTML = "Refreshing...";
	/*if(autoRefresh == 1)
	{
		var listDeviceIntervalId = setInterval(function() { 
			ajax1.requestFile = 'ajax_server.php?date_offline='+date_offline+'&sessionid='+sessionid;
			
			ajax1.onCompletion = function(){exeRefreshTable()};
			ajax1.runAJAX();
			}, newInterval * 1000);
	}
	else
	{*/
		ajax1.requestFile = 'ajax_server.php?date_offline='+date_offline+'&sessionid='+sessionid;
		//alert(ajax1.requestFile);
		if(divid!='')
		document.getElementById(divid).innerHTML = "Refreshing...";
		ajax1.onCompletion = function(){exeRefreshTable()};
		ajax1.runAJAX();
	//}
}
function exeRefreshTable()
{
	document.getElementById('mapTable').innerHTML = ajax1.response;
	initMenu();
	initMenu1();
}

function exeReportTable(rtData)
{
	
	var result = rtData.split("@");
	//document.write(result);
	mapTable = '<table class="gridform_final" width="100%" border="0" cellpadding="3" cellspacing="2">';
    mapTable +='<tr><th width="7%">Date</th><th width="7%">Time</th><th width="20%">Dev. Name</th><th width="20%">IMEI</th><th width="7%">Latitude</th><th width="7%">Longitude</th><th width="7%">Altitude</th><th width="7%">Speed</th>';
	
	no_of_rows= result.length-1;
	if(no_of_rows > 0)
	{
		for(i=0;i<no_of_rows;i++)
		{
			repData = result[i].split(',');
			//alert(data[2].split(" "));
			date1 = repData[3].split(" ");

			mapTable +='<tr>';
			mapTable +='<td valign="top">'+date1[0]+'</td>';
			mapTable +='<td valign="top">'+date1[1]+'</td>';
			mapTable +='<td valign="top">'+repData[4]+'</td>';
			mapTable +='<td valign="top">'+repData[5]+'</td>';
			mapTable +='<td valign="top">'+repData[11]+'</td>';
			mapTable +='<td valign="top">'+repData[12]+'</td>';
			mapTable +='<td valign="top">'+repData[10]+'</td>';
			mapTable +='<td valign="top">'+repData[1]+'</td>';
			mapTable +='</td></tr>';
			
		}//end of for loop
	}//end of if loop
	else
	{
		mapTable +='<tr><td colspan="3" style="border-top:1px solid #c5d4da; border-right:0px; background-color:#e8e9ea;">No Records found</td></tr>';
	}
	mapTable +='</table>';
	document.getElementById('reportView').innerHTML = mapTable;
}

function pickThisDevice(devId)
{
	//alert(devId);
	//map.clearOverlays();
	if(devId!=0)
	{
		document.frmMapData.hidTxtDevId.value = devId;
		document.frmMapData.cmdFindData.disabled = false;
		document.frmMapData.selectRefresh.disabled = false;
		document.frmMapData.chkShowBubble.disabled = true;
		
	}
	else
	{
		//document.getElementById('hidTxtDevId').value = '';
		document.frmMapData.cmdFindData.disabled = true;
		//document.frmMapData.chkShowBubble.disabled = true;
	}
}
function onlineGTracker(sessionid,d1,d2,d3,devId)
{
	//alert(sessionid+','+d1+','+d2+','+d3);
	date_offline = d1+'-'+d2+'-'+d3;
	pickThisDevice(devId);
	var currentTime = new Date()
	var month = currentTime.getMonth() + 1
	var day = currentTime.getDate()
	var year = currentTime.getFullYear()
	to_day = year + "-" + month + "-" + day;
	
	var check_date1 = to_day.split('-');
	var now_date1 = new Date(check_date1[0],check_date1[1]-1,check_date1[2]);

	//var check_date2 = date_offline.split('-');
	var now_date2 = new Date(d3,d2-1,d1);
	//alert(to_day+" "+now_date1+" "+now_date2)	
	var diff_date = now_date2 - now_date1;
	

	if(diff_date == 0)
	{
		//alert(diff_date);
		newInterval = 30;
		if (currentInterval > 0) { // currently running at an interval
	
			if (newInterval > 0) { // moving to another interval (3)
			
				clearInterval(intervalID);
				intervalID = setInterval(function() { 
				var url = 'locations.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devId;
				getOneVehicle(url); 
				}, newInterval * 1000);
				currentInterval = newInterval;
			}
			else { // we are turning off (2)
				clearInterval(intervalID);
				newInterval = 0;
				currentInterval = 0;
			}
		}
		else { // off and going to an interval (1)
		
			intervalID = setInterval(function() { 
			var url = 'locations.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devId;
			getOneVehicle(url); 
			}, newInterval * 1000);
			currentInterval = newInterval;
		}
	
		//showMessage("Getting Map...");
		clearInterval(intervalID);
		newInterval = 0;
		currentInterval = 0;
		var url = 'locations.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devId;
		getOneVehicle(url);
	}
	else
	{
		//alert('ss');
		var url = 'locations.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devId;
		getOneVehicle(url);
	}
}
function manRefresh()
{
	//$('#liveFrame').load('ViewTracker.php').fadeIn("slow");
	//document.getElementById('load_view').innerHTML='<span id="loading_txt">Loading...</span>';
	document.getElementById('liveFrame').src="ViewTracker.php";
}
//]]>
</script>

</head>

<body onLoad="load('<?php echo date("d-m-Y");?>','<?php echo $_SESSION[clientID];?>')" onUnload="GUnload()" >
<form name="frmMapData" id="frmMapData">
<div class="ui-layout-north" onMouseOver="myLayout.allowOverflow('north')" onMouseOut="myLayout.resetOverflow(this)">
<div class="headerarea">
    <div class="logoarea"><img src="../user/client_logo/<?php echo $clientLogo;?>" width="128" height="39" />
    <span>V 1.0</span>
    </div>
    
    <div class="statusBlock">
        <span id="messages">Loading...</span>
    </div>
    
    <div class="status_message">
    	<input type="button" name="showAll" class="showlive_off" id="showAll" value="Show Live" onclick="showMapForDate('<?php echo $date_offline;?>')" />
   </div>
   <div class="status_message">
      <select name="selectRefresh" id="selectRefresh" disabled="disabled" tabindex="3" onchange="autoRefresh1();">
          <option value ="0">Refresh - Off</option>
          <option value ="10">Refresh - 10 secs</option>
          <option value ="20">Refresh - 20 secs</option>
          <option value ="30">Refresh - 30 secs</option>
          <option value ="60">Refresh - 60 secs</option>
       </select>
   </div> 
  
  <div class="clear"></div>  
</div>
</div>
<div class="ui-layout-west">

    <table width="100%" class="detailsGrid" cols="1">
    	<tr>
        <td><span style="background:red">&nbsp;</span>Stopped</td>
        <td><span style="background:green">&nbsp;</span>Running</td>
        <td><span style="background:blue">&nbsp;</span>Not in Live</td>
        <!--<td align="right"><a href="#" class="ui-state-error-text" onclick="refreshMapTable('mapTable','<?php echo $_SESSION[clientID];?>','<?php echo $date_offline;?>');">Refresh</a>
        </td>-->
        </tr>
        <tr>
        <td colspan="3">
        <div id="mapTable" style="height:100%;">Loading...</div>
        <div id="side_bar"></div>
        </td>
        </tr>
    </table>
    
</div>
<div class="ui-layout-east">

<ul id="menu11">
    <li>
        <a href="#">Track</a>
        <ul><li>
       
        <table class="detailsGrid">
          <tr>
          	<td width="50%"><strong>Start Date </strong></td>
            <td><strong> Time</strong></td>
          </tr>
          <tr>
            <td><input type="text" name="from_date" id="from_date" size="10%" value="<?php echo $date_offline;?>" /></td>
            <td align="left"><span><input type="text" readonly="readonly" id="time3" size="5" value="00:01" /></span></td>
          </tr>
          <tr><td><strong>End Date</strong></td><td><strong> Time</strong></td></tr>
          <tr><td><input type="text" name="to_date" id="to_date" size="10%" value="<?php echo $date_offline;?>" /></td>
              <td align="left"><span><input type="text" id="time4" size="5" readonly="readonly" value="23:59" /></span></td>
          </tr>
          <tr><td align="center">
            <input type="hidden" name="hidTxtDevId" id="hidTxtDevId" />
            <input type="button" name="cmdFindData" id="cmdFindData" value="Find" class="click_btn" disabled="disabled" onClick="showMapOnDate(document.getElementById('from_date').value,document.getElementById('time3').value,document.getElementById('to_date').value,document.getElementById('time4').value,document.getElementById('hidTxtDevId').value);" /></td>
            <td><input type="button" name="cmdPlay" id="cmdPlay" onclick="playAnimation(this)" disabled="disabled" value="Play" /></td>            
          </tr>          
          <tr>
          <td><strong>Distance - <span id="totDist">0</span> km(s)</strong></td>
          <td><input type="checkbox" name="chkShowBubble" id="chkShowBubble" checked="checked" disabled="disabled" onchange="showPoints(this.id)" /><span id="shBub" class="green_link">Hide Bubble</span></td>
          </tr>
        </table>
        </li>
        </ul>
        </li>
        <li>
    <a href="#">Parameters</a>
    <ul><li>
    <table class="detailsGrid">

    </tr>
    <tr><td id="ctSpd">Speed&nbsp;: Null</td></tr>
    <tr><td id="dateTime">Date & Time&nbsp;&nbsp;: Null </td></tr>
    <tr><td id="posLatPt">Latitude &nbsp;: Null</td></tr>
    <tr><td id="posLongPt">Logntitude&nbsp;: Null</td></tr>
    <tr><td id="posAltPt">Logntitude&nbsp;: Null</td></tr>
    <tr><td id="noSate">Satelite(s)&nbsp;: Null</td></tr>
    <tr><td id="Engine">Engine&nbsp;: Null</td></tr>
    <tr><td id="AC">A/C&nbsp;: Null</td></tr>
    <tr><td id="Ignition">Ignition&nbsp;: Null</td></tr>
    <tr><td id="Sos">Sos&nbsp;: Null</td></tr>
    </table>
    </li>
    </ul>
    </li>
    <li>
    <a href="#">Other Parameters</a>
    <ul><li>

    <table class="detailsGrid">
    <tr><td id="other">Null</td></tr>
    </table>
    </li>
    </ul>
    </li>
    </ul>


</div>

<div class="ui-layout-center" id="map">Loading...</div>

<canvas id="arrowcanvas" width="32" height="32"><\/canvas>

</div>
</form>
</body>
</html>
