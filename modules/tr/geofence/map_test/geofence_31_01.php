<?php 
if(isset($_GET[val]) && $_GET[val]!='') 
{
?>
<script language="javascript">
geoData= '<?php echo $_GET[val];?>';
</script>
<?php
}
else
if(isset($_GET[val]) && $_GET[val]!='') 
{
?>
<script language="javascript">
geoData= '';
</script>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">


<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>
<title>Chekhra :: dfdf</title>
<link href="css/tracker_styles.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="css/mapStyle.css" />

<script type="text/javascript" src="key.js"></script>

<script type="text/javascript">
	var scriptTag = '<' + 'script src="http://maps.google.com/maps?file=api&v=2.81&key=' + myKey + '" type="text/javascript">'+'<'+'/script>';
	document.write(scriptTag);
	//http://maps.forum.nu/gm_plot.html
</script>


<script type="text/javascript">
//<![CDATA[

var routePoints = new Array();
var routeMarkers = new Array();
var routeOverlays = new Array();
var map;
var totalDistance = 0.0;
var lineIx = 0;
var lineColor = "#0000af";
var fillColor = "#335599";
var lineWeight = 3;
var lineOpacity = .8;
var fillOpacity = .2;
var lt=0;
var baseIcon = new GIcon();
baseIcon.iconSize=new GSize(16,16);
baseIcon.iconAnchor=new GPoint(8,8);
baseIcon.infoWindowAnchor=new GPoint(10,0);

var yellowIcon = (new GIcon(baseIcon, "images/yellowSquare.png", null, ""));
var greenIcon = (new GIcon(baseIcon, "images/greenCircle.png", null, ""));
var redIcon = (new GIcon(baseIcon, "images/redCircle.png", null, ""));
var orangeIcon = (new GIcon(baseIcon, "images/orangeCircle.png", null, ""));
var blueIcon = (new GIcon(baseIcon, "images/blueCircle.png", null, ""));
var violetIcon = (new GIcon(baseIcon, "images/violetCircle.png", null, ""));












function load() {
  //if (GBrowserIsCompatible()) {
	var centerPoint = new GLatLng(17.385044,78.486671);

	map = new GMap2(document.getElementById("map"),{draggableCursor:"crosshair"});
	map.setCenter(centerPoint, 13);
	getMapcenter();

	map.addControl(new GMapTypeControl());
	map.addControl(new GScaleControl());
	map.addControl(new GSmallMapControl());
	map.enableScrollWheelZoom();
	map.enableContinuousZoom();
	
	GEvent.addListener(map, "moveend", getMapcenter);
	GEvent.addListener(map, "click", mapClick);
  
//  ======== Add a map overview ==========
	var ovcontrol = new GOverviewMapControl(new GSize(120,120));
	map.addControl(ovcontrol);


if(geoData!='')
{
	//alert(geoVal);
	map.removeOverlay(routeOverlays[lineIx])
	var ptArr=[];
	ptArr=geoData.split("@");
	alert(ptArr);
	var poly =new google.maps.Polygon(ptArr,"#f33f00", 5, 1,"#ff0000", 0.2); 
	map.addOverlay(poly); 
	
}
else
{

return;
var pl = new GPolyline.fromEncoded({
  color: "#0000ff",
  weight: 4,
  opacity: 0.8,
  points: "_gkxEr}|vNcBwBoAoA{Jg@cBcBoA{@S?uBoA}@wBS_DbBwhATg@c[sDiT}\\g@i@{@S{@{@ScBRSnAg@SoAS{Eg@kCkClH?nAoF`LQRU{E~CgJ?g@jCqNrNcGz@?zJyBf@y@~CmC{@zJ?f@mAnA?f@x@z@bBR~HsIRe@jClKRz@`Dh@`Bd@vBRnF_DRSg@S?wBpFcGd@SScBwBvB{@T_D~Cg@?oA`B{@R{E~CqD{EUgJwB{@nAoA?kChJrIdJaBz@i@f@_DdBf@dEgEnFcGoP_N{@SeTvBqKoA{J_SsDoF?_Df@{@vB_XoAoPbLoZrDcBy@{EmC_D{ToAy@g@?_DlAgEgEcLg@g@qDnAqAkR_DyJy@i@mC?g@{@}CqNi@i@_ISoK_g@g@{@{EiCiCd@g@z@yGfOwBnAoARyJ_DuDRoAg@_D_IoKyEcGk\\gOx@cGy@{@}@sI_DcGgEwLbBuBRmCe@{JgOS}Jy@{@kHcBy[_]gEmPnA}Ef@g@vGwBgEa[rDqP|h@w[hMoFzJ{J~HwL{@_DoAkCcGwBuBgJyBoA_SfJiCz@{Jz@yBg@oU{OkCkCf@cLbBsIy@iCyGeB}H{@mCnFoAvB}ReEyGaIpFsSx@iHoAmCkHkCQ{JmM{TS_DvBuG?qFR{@vBoA~Hg@bBoAlCuL}@cG_IcGyEi@?sNUy@{@}@wLvBuB?aIcGgTgOSwLS_DoFoFwQTsDUg^kW}Hwe@U_DrI_l@|@_Dx@g@fTbBjC?j\\sD|OgEdJcGnAwBaB{EuDoAaLcB}J{JqDjCee@wGsD{JoAwB_X?cB{@g@g@kCqSkf@ye@gJkWoi@_]{JgESkHcBkHco@u[{Od@{@e@sIa]{@gJk\\kMkHoF_NgJcG?oAaBoAyGcBoAgESgYvG{@?wB_NcBgO~HoFrI_I~a@_]rNcVzJoAnU_q@nA{@rIsDrDsIwG_Ng@}CjMa]bBcGz@g@nF|@z@i@sDa[vGiYvBoAfJ_Dz@_DgEoKoA_NdBuL`VkWvLmH_Ie@kH?wB|Ce^|TyBx@cL?f@{T{@yEcQyBoFfEoKtDeJPmCoFg@{@gO}Huj@oA}OgOaBeBwQf@}@?mFsIyB_DyE_DaXrDcGrDbBkCvBgE{JsSoKsIqDSuDkHR{@xB?lAbBcBR",
  levels: "P?CFDEADGDHBGIBDCEADCFDECFADFBFGCFCEEBDBCDGBEBFHDEABDFBCCDEBFCEFEDCFFECFGEFFCGEHEDCJFGDEDFGHDFBEGECFCIBEGCEDFFGHEFCGEKFECGEHDEFGCEIFDEFGHGHIFDEFGDFGEFJDFEDGEFDGHFEGEFIDEFBDGDFEFGHBDFDGLEFEGDHGDIDCFGHFGDEDFGDHEFGDBFJFHEFEIFCGKGEHEDFDEGCIEFGHFGICFEGDHJCECFGDHDFEGIFEFDGDHFDGEFGICFHFDGCJDEGEDEMFDGBDEP",
  zoomFactor: 2,
  numLevels: 18
});


map.addOverlay(pl);

}


 //}
}



function mapClick(marker, point) {
	if (!marker) {
		addRoutePoint(point);
	}
}





function addRoutePoint(point) {
	var dist = 0;

	if (!routePoints[lineIx]) {
		routePoints[lineIx] = Array();
		routeMarkers[lineIx] = Array();
	}

	routePoints[lineIx].push(point);

	if (routePoints[lineIx].length > 1)	{
		plotRoute();
		routeMarkers[lineIx][routePoints[lineIx].length-1] = new GMarker(point,{icon:blueIcon,title:'Internal'});
		map.addOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);
		dist = routePoints[lineIx][routePoints[lineIx].length-2].distanceFrom(point) / 1000;
		totalDistance += dist;
		document.getElementById("dist").innerHTML = 'Total Distance: '+ totalDistance.toFixed(3) + ' km';
	}
	else {
		routeMarkers[lineIx][routePoints[lineIx].length-1] = new GMarker(point,{icon:greenIcon,title:'Start'});
		map.addOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);

	}
	document.getElementById("route").innerHTML += point.y.toFixed(6) + ' ' + point.x.toFixed(6) + ' : ' + dist.toFixed(3) +"<br>";
}



function getMapcenter() {
	var center = map.getCenter();
	var z = map.getZoom();
	document.getElementById("coords").innerHTML = 'Map center:<br>' + center.y.toFixed(6) + ' ' + center.x.toFixed(6) + '<br>Zoom: ' + z;
}



function DEC2DMS(dec) {

	var deg = Math.floor(Math.abs(dec));
	var min = Math.floor((Math.abs(dec)-deg)*60);
	var sec = (Math.round((((Math.abs(dec) - deg) - (min/60)) * 60 * 60) * 100) / 100 ) ;

	deg = dec < 0 ? deg * -1 : deg;

	var dms  = deg + '&deg ' + min + '\' ' + sec + '"';
	return dms;
}

function editPlotRoute(geoVal) {
	//alert(geoVal);
	var ptArr=[];
	ptArr=geoVal.split("@");
	//alert(ptArr.length);
	var poly = new GPolyline(geoVal, lineColor, lineWeight, lineOpacity, fillColor, fillOpacity);
	map.addOverlay(poly); 
//	map.removeOverlay(routeOverlays[lineIx]);
//	for(f=0;f<ptArr.length;f++)
//	{
//		
//	}
//	routeOverlays[lineIx] = new GPolygon(routePoints[lineIx], lineColor, lineWeight, lineOpacity, fillColor, fillOpacity); 
//	// GPolyline(routePoints[lineIx],'#C602C8',3,1); 
//	map.addOverlay(routeOverlays[lineIx]);

}


function plotRoute() {
	map.removeOverlay(routeOverlays[lineIx]);
	routeOverlays[lineIx] = new GPolygon(routePoints[lineIx], lineColor, lineWeight, lineOpacity, fillColor, fillOpacity); 
	// GPolyline(routePoints[lineIx],'#C602C8',3,1); 
	map.addOverlay(routeOverlays[lineIx]);

}
function clearAll() {

	if(lt==0)
	{
		addClosing();
		lt=0;
	}

	while (lineIx > 0) {
		resetRoute();
	}
    map.clearOverlays();
	totalDistance = 0;
	document.getElementById("dist").innerHTML = '';
	document.getElementById("route").innerHTML = 'Route points:<br>';
}


function resetRoute() {

	if (!routePoints[lineIx] || routePoints[lineIx].length == 0) {
		lineIx--;
	}

	routePoints[lineIx] = null;
	map.removeOverlay(routeOverlays[lineIx]);

	for (var n = 0 ; n < routeMarkers[lineIx].length ; n++ ) {
		var marker = routeMarkers[lineIx][n];
		map.removeOverlay(marker);
	}
	routeMarkers[lineIx] = null;

	var html = document.getElementById("route").innerHTML;
	html = html.replace(/<br>[^<]+<br>$/,'<br>');
	document.getElementById("route").innerHTML = html;
	
}

function undoPoint() {
	if (!routePoints[lineIx] || routePoints[lineIx].length == 0) {
		lineIx--;
	}

	if (routePoints[lineIx].length > 1)	{

		var dist = routePoints[lineIx][routePoints[lineIx].length-2].distanceFrom(routePoints[lineIx][routePoints[lineIx].length-1]) / 1000;
		totalDistance -= dist;
		document.getElementById("dist").innerHTML = 'Total Distance: '+ totalDistance.toFixed(3) + ' km';

		var html = document.getElementById("route").innerHTML;
		html = html.replace(/<br>[^<]+<br>(<br>)*$/,'<br>');
		document.getElementById("route").innerHTML = html;

		if (routeMarkers[lineIx][routePoints[lineIx].length-1]) {
			var marker = routeMarkers[lineIx].pop();
			map.removeOverlay(marker);
		}
		routePoints[lineIx].pop();
		plotRoute();
	}
	else {
		resetRoute();	
	}
}


function showPoints(xml) {
	var html = '';
	if (xml) {
		html = '<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n';

		html += '<routes>\n';
		for (var i = 0 ; i < lineIx ; i++ ) {
			html += '  <route>\n';
			for (var n = 0 ; n < routePoints[i].length ; n++ ) {
				html += '    <p lat="' + routePoints[i][n].y.toFixed(8) + '" lon="' + routePoints[i][n].x.toFixed(8) + '"';
				if (routeMarkers[i][n]) {
					html += ' markerIcon="'+ routeMarkers[i][n].getIcon().image +'"';
				}
				html += ' />\n';
			}

			html += '  </route>\n';
		}
		html += '</routes>\n';
	}
	else {
		for (var i = 0 ; i < lineIx ; i++ ) {
			for (var n = 0 ; n < routePoints[i].length ; n++ ) {
				html += routePoints[i][n].y.toFixed(8) + ', ' + routePoints[i][n].x.toFixed(8) + '\n';
			}
			html += '----- new line ------ \n';
		}
	}

	if (html == '') {
		html += 'You must add a closing point to each line\n\n';
	}

	html += '\n\n';
//	html += encodePolyline();

	var nWin = window.open('','nWin','width=780,height=500,left=50,top=50,resizable=1,scrollbars=yes,menubar=no,status=no');
	nWin.focus();
	nWin.document.open ('text/xml\n\n');
	nWin.document.write(html);
	nWin.document.close();
}

function addIntermediate() {
	if (routePoints[lineIx].length > 1)	{
		map.removeOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);
		routeMarkers[lineIx][routePoints[lineIx].length-1] = new GMarker(routePoints[lineIx][routePoints[lineIx].length-1],{icon:yellowIcon,title:'Point '+ routePoints[lineIx].length-1});
		map.addOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);
	}
}


function addClosing() {
	if (routePoints[lineIx].length > 1)	{
		map.removeOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);
		routeMarkers[lineIx][routePoints[lineIx].length-1] = new GMarker(routePoints[lineIx][routePoints[lineIx].length-1],{icon:redIcon,title:'End'});
		lt=1;
		map.addOverlay(routeMarkers[lineIx][routePoints[lineIx].length-1]);
		lineIx++;
		document.getElementById("route").innerHTML += '<br>';

	}
}





//-----------------------------------------
function encodePolyline() {
	var encodedPoints = '';
	var	encodedLevels = '';

	var plat = 0;
	var plng = 0;

	for (var n = 0 ; n < routePoints[lineIx].length ; n++ ) {
		var lat = routePoints[lineIx][n].y.toFixed(8);
		var lng = routePoints[lineIx][n].x.toFixed(8);

		var level = (n == 0 || n == routePoints[lineIx].length-1) ? 3 : 1;
		var level = 0;

		var late5 = Math.floor(lat * 1e5);
		var lnge5 = Math.floor(lng * 1e5);

		dlat = late5 - plat;
		dlng = lnge5 - plng;

		plat = late5;
		plng = lnge5;

		encodedPoints += encodeSignedNumber(dlat) + encodeSignedNumber(dlng);
		encodedLevels += encodeNumber(level);
	}


	var html = '';
	html += 'new GPolyline.fromEncoded({\n';
	html += '  color: "#0000ff",\n';
	html += '  weight: 4,\n';
	html += '  opacity: 0.8,\n';
	html += '  points: "'+encodedPoints+'",\n';
	html += '  levels: "'+encodedLevels+'",\n';
	html += '  zoomFactor: 16,\n';
	html += '  numLevels: 4\n';
	html += '});\n';

	return html;
}

function encodeSignedNumber(num) {
	var sgn_num = num << 1;

	if (num < 0) {
		sgn_num = ~(sgn_num);
	}

	return(encodeNumber(sgn_num));
}

// Encode an unsigned number in the encode format.
function encodeNumber(num) {
	var encodeString = "";

	while (num >= 0x20) {
		encodeString += (String.fromCharCode((0x20 | (num & 0x1f)) + 63));
		num >>= 5;
	}

	encodeString += (String.fromCharCode(num + 63));
	return encodeString;
}

 function locateAddress() {
      var address = document.getElementById("address").value;
      var geocoder = new GClientGeocoder();
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert(address + " not found");
            } else {
              map.setCenter(point, 13);
              var marker = new GMarker(point);
              map.addOverlay(marker);
              marker.openInfoWindowHtml(address);
              start = point;
            }
          }
        );
      }
}
function sendBackData()
{
	if(routePoints.length ==0 || lt==0)
	{
		alert('Make proper Geofence');
	}
	else
	{
		//alert(routePoints);
		opener.document.getElementById('<?php echo $_GET[id]; ?>').value=routePoints;		//latlng;
		window.close();
	}
}


//]]>
</script>
</head>



<body onLoad="load()" onUnload="GUnload()" style="background:#fff">
	
	<div style="width: 680px; font: normal 12px verdana;padding:3px;margin:0px auto;">
	Click the map two or more times to create polylines. Distance for each segment is shown on the right (in kilometers)
	In order to view the point list, you must add a closing point to each line.
	</div>

    <table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="corner_mid">
    <td width="22"><img src="images/corner_LT.gif" width="22" height="20" /></td>
    <td width="900">&nbsp;</td>
    <td width="22"><img src="images/corner_RT.gif" width="22" height="20" /></td>
  </tr>
  
  <tr>
    <td class="corner_mid_left">&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="popup_map">
      
      <tr>
        <td><div id="map_div"><div id="map"></div></div><div id="map_panel">
           <ul>
       	  <div class="buttons">
            <div class="buttonB" onClick="clearAll()">Clear all</div>
            <div class="buttonB" onClick="undoPoint()">Undo last</div>
<!--            <div class="button_box" onclick="addIntermediate()" >Medium Pt.</div>
-->            <div class="button_cur" onClick="addClosing()">Closing Pt.</div>
            <div class="buttonB" onClick="sendBackData()">Done</div>
          </div>
          <div  id="coords">&nbsp;</div>
            <div  id="dist">&nbsp;</div>
          <li><input type="text" name="address" id="address" value="Hyderabad" /></li>
          <li><input name="submit" type="submit" value="Show Address" class="blue_btn" onClick="locateAddress();" /></li>
			<div id="route">Route points:<br></div>
          </ul>
          <span class="map_logo"><img src="images/map_logo.png" width="70" height="21" /><br />
          © 2008 - 2009,Chekhra.com</span>
        </div></td>
      </tr>
    </table></td>
    <td class="corner_mid_right">&nbsp;</td>
  </tr>
  <tr class="corner_mid_bot">
    <td><img src="images/corner_LB.gif" width="22" height="20" /></td>
    <td>&nbsp;</td>
    <td><img src="images/corner_RB.gif" width="22" height="20" /></td>
  </tr>
</table>

</body>
</html>

