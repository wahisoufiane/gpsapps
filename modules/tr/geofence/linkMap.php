<?php
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//exit;
@ob_start();
require_once("../db/MySQLDB.php");
if(isset($_GET[linkId]) && $_GET[linkId]!='')
{
	$sel_qry = "SELECT * FROM map_outside_link WHERE mol_md5code='".$_GET[linkId]."'";
	$rs_sel_qry = mysql_query($sel_qry);
	$fetch_sel_qry = @mysql_fetch_assoc($rs_sel_qry);
	$_POST[date_offline]=date('Y-m-d',strtotime($fetch_sel_qry[mol_activatedDate]));
	$_POST[sessionid]=$fetch_sel_qry[mol_clientId];
	$_POST[vehicle_no]=$fetch_sel_qry[mol_vehi_regno];
	//print_r($_POST);
	//exit;
}
if($_POST[date_offline]!='' && $_POST[sessionid]!='' && $_POST[vehicle_no]!='')
{

	
?>
<html>
<head>
    <title>Chekhra - Google Map GPRS Tracker</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
	<link href="../GPS/styles/tracker_styles.css" rel="stylesheet" type="text/css" media="all" />
	<link rel="Shortcut Icon" type="image/x-icon" href="../../images/favicon.png" />
    
<style type="text/css">
img {
	behavior: url("../../scripts/pngbehavior.htc");
}
.map_heading{font: bold 15px/40px Arial, Helvetica, sans-serif; color:#ffffff; text-align:left;}

.table_info{

border-right:0px;
font:normal 12px/20px Arial, Helvetica, sans-serif;
color:#FFF;
}
.table_info td{

padding-left:2px;
}
    .style1 {background-color:#FFFFCC; font:bold 11px Arial, Helvetica, sans-serif; color:#000; border:1px #000 solid; text-align:center; width:auto; margin:0px; position:absolute; bottom:20px; left:-35px; padding:2px;}
	 .style2 {background-color:#ffcccc;}
    .css1 {background-color:#FFFFCC; font:bold 11px Arial, Helvetica, sans-serif; color:#000; border:1px #000 solid; text-align:center; width:120px; height:15px; position:absolute; left:920px; bottom:20px; top:120px; padding:2px; z-index:1233 }
    .css2 {background-color:#FFFFCC; font:bold 11px Arial, Helvetica, sans-serif; color:#000; border:1px #000 solid; text-align:center; width:120px; height:75px; overflow:scroll; overflow-X:hidden; position:absolute; left:920px; bottom:20px; top:140px; padding:2px; z-index:1233 }


</style>  

<style type="text/css">
/**
 *	Basic Layout Theme
 * 
 *	This theme uses the default layout class-names for all classes
 *	Add any 'custom class-names', from options: paneClass, resizerClass, togglerClass
 */

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
	background: #AAA; 
} 
.ui-layout-north ul ul {
	/* Drop-Down */
	bottom:		auto;
	margin:		0;
	margin-top:	1em;
}

</style>
<script language="javascript" src="../FMS/js/ajax.js"></script>

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ"
type="text/javascript"></script>

<script src="javascript/calculators.js" type="text/javascript"></script>
<script src="javascript/maps_off.js" type="text/javascript"></script>
<script src="javascript/elabel.js" type="text/javascript"></script>
<script src="javascript/simpletreemenu.js" type="text/javascript"></script>
<script src="javascript/TreeMenu.js" type="text/javascript"></script>
<script language="javascript" src="javascript/anim.js"></script>
<script language="javascript" src="javascript/BdccArrowedPolyline.js"></script>

<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.ui.all.js"></script>
<script type="text/javascript" src="js/jquery.layout.js"></script>
<script type="text/javascript" src="js/complex.js"></script>

<script type="text/javascript">

var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method

$(document).ready(function () {
	myLayout = $('body').layout({
		// enable showOverflow on west-pane so popups will overlap north pane
		west__showOverflowOnHover: true

	//,	west__fxSettings_open: { easing: "easeOutBounce", duration: 750 }
	});
});

//<![CDATA[

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

function load(sessionid,date_offline,vehicle_no) {

	// the code to process the data is in the javascript/maps2.js file
	routeSelect = document.getElementById('selectRoute');
	refreshSelect = document.getElementById('selectRefresh');
	zoomLevelSelect = document.getElementById('selectZoomLevel');
	messages = document.getElementById('messages');
	map =document.getElementById("map");
	geocoder = new GClientGeocoder();

	intervalID = 0;
	newInterval = 0;
	currentInterval = 0;
	zoomLevel = 16;
	//mapType=3;
	mapPoints=3;
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

	//zoomLevelSelect.selectedIndex = 14;
	refreshSelect.selectedIndex = 0;
	showWait('Loading routes...');

	var url = 'getroutes_offline.php?sessionid='+sessionid+'&date_offline='+date_offline+ '&phoneNumber='+vehicle_no;
	//document.write(url);
	GDownloadUrl(url, loadRoutes);
	// when the page first loads, get the routes from the DB and load them into the dropdown box.
	//GDownloadUrl('getroutes_offline.php?sessionid='+sessionid+'&date_offline='+date_offline+ '&phoneNumber='+vehicle_no, loadRoutes);
}

 //]]>

</script>

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

</head>
<body onLoad="load('<?php echo $_POST[sessionid]; ?>','<?php echo $_POST[date_offline]; ?>','<?php echo $_POST[vehicle_no] ?>')" onUnload="GUnload()" style="background:#f3f3f3;">

<div class="ui-layout-north" onMouseOver="myLayout.allowOverflow('north')" onMouseOut="myLayout.resetOverflow(this)">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="popup_heading">
  
  <tr class="popup_heading">
    <td width="179"><img src="images/logo.png" width="200" height="61" border="0" /></td>
    <td width="926" align="left" style="padding-left:25px;">
    	<ul id="H_menu">
    		<li><span id="messages" class="">&nbsp;</span></li>
		  	<li>&nbsp;</li>
		  </ul>
          <div align="center" id="timdDiv" style="color:white;">&nbsp;</div>
    </td>
    <td width="89"><img src="images/tracking.png" width="89" height="64" style="float:right; border:0px;" /></td>
    <td></td>
  </tr>
  </table>
</div>
<div class="ui-layout-west">
<div id="menu_panel">
          <ul id="example3" class="menu">
            <li id="ctSpd">&nbsp;Current Speed </li>
            <li><a href="javascript:void(0)" onClick="TreeMenu.toggle(this)">Vehicle Info.</a>
                <ul>
                  <li style="display:none;">
                    <select name="selectRoute" id="selectRoute" tabindex="1" onChange="getRouteForMap_offline('<?php echo $_POST[date_offline] ?>','<?php echo $_POST[vehicle_no]; ?>'); " class="list_boxes" style="width:120px;">
                    </select>
                  </li>
                  <?php if($_POST[date_offline] == date('Y-m-d')) { ?>
<!--                  <li id="ingli">Ignition&nbsp;<span id="ignit" class="span_data">&nbsp;</span></li>-->
                  <?php } ?>	
                  <li>Traveled&nbsp;:<span id="distance" class="span_data">&nbsp;</span></li>
                  <li>Status&nbsp;:<span id="sts" class="span_data">&nbsp;</span></li>                                  
                </ul>
            </li>
            <li><a href="javascript:void(0)" onClick="TreeMenu.toggle(this)">Map Info.</a>
                <ul>
           		  <?php if($_POST[date_offline] == date('Y-m-d')) { ?>
                  <li>Set Refresh time</li>
                  <li>
                    <select name="selectRefresh" id="selectRefresh" tabindex="3" 
              onchange="autoRefresh('<?php echo $_POST[date_offline]; ?>','<?php echo $_POST[vehicle_no] ?>'); " class="drop_downs">
                      <option value ="0">Refresh - Off</option>
                      <option value ="5">Refresh - 5 secs</option>
                      <option value ="10">Refresh - 10 secs</option>
                      <option value ="20">Refresh - 20 secs</option>
                      <option value ="30">Refresh - 30 secs</option>
                      <option value ="60">Refresh - 60 secs</option>
<!--                      <option value ="300">Refresh - 5 mins</option>
                      <option value ="6000">Refresh - 10 mins</option>
-->                    </select>
                  </li>
                  <li>
                    <input type="button" id="refresh" value="Refresh" 
			onclick="getRouteForMap_offline('<?php echo $_POST[date_offline] ?>','<?php echo $_POST[vehicle_no] ?>'); " class="blue_btn" 
			tabindex="5" />
                  </li>
           		  <?php } else {?>
                  <li>
                   <input type="hidden" id="selectRefresh" onChange="autoRefresh();"  />
                  </li>
                  <?php } ?>
                  <li>Show map points&nbsp;:</li>
                  <li>
                  <select name="selectRefresh" id="selectRefresh" tabindex="3" onChange="showPoints(this.value);"  style="width:155px;" class="drop_downs" autocomplete="off">
                      <option value ="3">Current point</option>
                      <option value ="2">Start & Current point</option>
<!--                      <option value ="1">All points</option>
                      <option value ="4">Other</option>
-->                  
	                  <option value="5">Select Timings</option>
        		</select>
				  </li>
                  <li id="shPt" style="display:none;">Select Timings.<br>
              <label>From
                <select name="from_hrs" id="from_hrs"  tabindex="3" style="width:45px;" class="drop_downs">
                    <option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09" selected>09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
               </select>
                </label>
                <label>
                  <select name="from_mins" id="from_mins"  tabindex="4" style="width:45px;" class="drop_downs">
                    <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30" selected>30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="55">55</option>
                  </select>
                </label><br/>
                <label>To&nbsp;&nbsp;&nbsp;&nbsp;
                  <select name="to_hrs" id="to_hrs"  tabindex="6" style="width:45px;" class="drop_downs">
<option value="00">00</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16" selected>16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                </select>
                </label>
                <label>
                <select name="to_mins" id="to_mins"  tabindex="7" style="width:45px;" class="drop_downs">
                    <option value="00">00</option>
                    <option value="05">05</option>
                    <option value="10">10</option>
                    <option value="15">15</option>
                    <option value="20">20</option>
                    <option value="25">25</option>
                    <option value="30"  selected>30</option>
                    <option value="35">35</option>
                    <option value="40">40</option>
                    <option value="45">45</option>
                    <option value="50">50</option>
                    <option value="55">55</option>
                </select>                  
               </label><br>
                     <input type="button" name="cmdShowBtn" id="cmdShowBtn" value="Show Points" class="blue_btn" onClick="changeInterval_offline('<?php echo $_POST[date_offline] ?>',document.getElementById('from_hrs').value,document.getElementById('from_mins').value,document.getElementById('to_hrs').value,document.getElementById('to_mins').value);">

               </li>
                  <li id="shPt" style="display:none;">Enter point diff.<br>
                  <input type="text" name="txtPtDiff" id="txtPtDiff" style="width:30px;" maxlength="3">
                  <input type="button" name="cmdShowBtn" id="cmdShowBtn" value="Show" class="blue_btn" onClick="changeInterval_offline(document.getElementById('txtPtDiff').value);">
                  </li>
	               <li>
                     <input type="checkbox" id="chkHidStop" name="chkHidStop"onClick="funcHideStop()"tabindex="5" />
                     <span id="stopHead">Hide Stop Points</span>
                  </li>
<!--                  <li>Switch To</li>
                  <li>
                    <input type="button" id="cmdChgMap" name="cmdChgMap" value="Yahoo Map" onClick="funChangeMap() " class="blue_btn" tabindex="5" />
                  </li>--> 
                 <li>Calculate Time</li>
                  <li>From 
                   <input type="text" name="txtFrmPt" id="txtFrmPt" disabled="disabled" size="3" maxlength="3" />
                   To
                   <input type="text" name="txtToPt" id="txtToPt" disabled="disabled" size="3" maxlength="3" />
                  </li>
                  <li>
                    <input type="button" id="cmdFindBtn" name="cmdFindBtn" value="Find Duration" class="blue_btn" tabindex="5" onClick="funFindDura(document.getElementById('txtFrmPt').value,document.getElementById('txtToPt').value);" />
                  </li>
                </ul>
            </li>
          </ul>
          <script type="text/javascript">make_tree_menu('example3',1,0,1);</script>
			</div>
          </div>
          <div class="ui-layout-center" id="map"></div>
</body>
</html>
<?php
}
else
{
	if($_GET[show]==1)
	{
	?>
		<center><font face="Verdana, Arial, Helvetica, sans-serif" size="-1" style="text-align:center">Session Expired. Please try Again</font></center>
	<?php
	}
	else if($_GET[show]==2)
	{
	?>
		<center><font face="Verdana, Arial, Helvetica, sans-serif" size="-1" style="text-align:center">Link doesn't exist.</font></center>
	<?php
	}else
	{
	?>
		<center><font face="Verdana, Arial, Helvetica, sans-serif" size="-1" style="text-align:center">Wrong Data.</font></center>
	<?php
	}
}
?>


