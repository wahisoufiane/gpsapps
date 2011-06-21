<?php 
//print_r($_GET);
//exit;
if(isset($_GET[val1]) && $_GET[val1]!='') 
{
?>
<script language="javascript">
geoData= '<?php echo $_GET[val1];?>';
</script>
<?php
}
else
{
?>
<script language="javascript">
geoData= '';
</script>
<?php
}
?>
<html>
<head>
<title>GPS :: Geofence</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<!-- See also the official example at:
  http://gmaps-samples.googlecode.com/svn/trunk/poly/area_length.html -->


<style type="text/css">
.map_logo{position:absolute; bottom:2px; left:5px; font:normal 12px/18px Arial, Helvetica, sans-serif; color:#333;}

body { height: 600px; }


#descr { position:absolute;
	top:40px;
	left: 580px;
	width: 250px;
}


.button { display: block;
	width: 180px;
	border: 1px Solid #565;
	background-color:#F5F5F5; 
	padding: 3px;
	text-decoration: none;
	font-size:smaller;
}

.button:hover { background-color: white; }

.tooltip { text-align: center;
	opacity: .70;
	-moz-opacity:.70;
	filter:Alpha(opacity=70);
	white-space: nowrap;
	margin: 0;
	padding: 2px 0.5ex;
	border: 1px solid #000;
	font-weight: bold;
	font-size: 9pt;
	font-family: Verdana;
	background-color: #fff;
}
/**
 *	Basic Layout Theme
 * 
 *	This theme uses the default layout class-names for all classes
 *	Add any 'custom class-names', from options: paneClass, resizerClass, togglerClass
 */

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

<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ"
type="text/javascript"></script>

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
function findLocation(ltln)
{

	geocoder.getLocations(ltln, function(response)																																						   {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		if(response.Status.code==620)
		{
			findLocation(ltln);
		}
		//addr5="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr5=place.address; 
			addr6=addr5.replace(",","-");
			//alert(place.address);
			addr7=addr6[0]+"-"+addr6[1]+"-"+addr6[2]+"-"+place.AddressDetails.Country.CountryNameCode;
			locat.push(addr6);
			//alert(addr6);
	  }
	});

}

function sendBackData()
{
	if(points.length == 0 || points.length <4)
	{
		alert('Min. 3 points needed to complete Geofence. Please complete properly.');
	}
	else
	{
		//alert(geoData.length+","+routePoints.length+","+locat.length);
		opener.document.getElementById('<?php echo $_GET[id1]; ?>').value=points;		//latlng;
		opener.document.getElementById('<?php echo $_GET[len]; ?>').value=points.length-1;		//latlng;
		opener.showPoints(points);
		opener.document.getElementById('<?php echo $_GET[load_symbol]; ?>').innerHTML="<img src='../../../images/ok_symbol.gif'>";
		window.close();
	}
}
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

<body onLoad="buildMap()" onUnload="GUnload()">

<h3>Switch between Polylines and Polygons</h3>

<div class="ui-layout-center" id="map">
</div>

<div class="ui-layout-east">
<form id="f" action="">
<table id="" border="0" cellspacing="0" cellpadding="0">
<tr><td>
Click on the map to set markers and polygons. At least three markers are needed to draw a line. All markers are draggable and seperately removable.

</td></tr>
<tr><td id="status" style="height:70px">&nbsp;</td></tr>
<tr><td>
<div class="button">
<input type="text" name="address" id="address" value="Hyderabad" /><br />
<input name="submit" type="button" value="Show Address"  onClick="locateAddress();" /></div>
</td></tr><tr><td>

<a href="#" class="button" style="text-align:center" onClick="clearMap();return false;">Clear Map</a>
<a href="#" class="button" style="text-align:center" onClick="sendBackData();return false;">Done</a>

</td></tr><tr><td style="padding-right:19px; height:30px;">
<span class="button" id="route" style="display:none;">Route points:<br></span>

</td></tr>
</table>
</form>
<span class="map_logo" >Copy rights</span> 
</div>


<script type="text/javascript">
//<![CDATA[

// Global variables
var mapdiv = document.getElementById("map");
var map;
var poly;
var count = 0;
var points = new Array();
var markers = new Array();
var icon_url ="images/";
var tooltip;
var lineColor = "#0000af";
var fillColor = "#335599";
var lineWeight = 3;
var lineOpacity = .8;
var fillOpacity = .2;
var report= document.getElementById("status");
var routePoints = new Array();
var locat = new Array();
var geocoder = new GClientGeocoder();

toggleMode();
function addIcon(icon) { // Add icon attributes

 icon.iconSize = new GSize(11, 11);
 icon.dragCrossSize = new GSize(0, 0);
 icon.shadowSize = new GSize(11, 11);
 icon.iconAnchor = new GPoint(5, 5);
}

function locateAddress() {
  var address = document.getElementById("address").value;      
  if (geocoder) {
	geocoder.getLatLng(
	  address,
	  function(point) {
		if (!point) {
		  alert(address + " not found");
		} else {
		  map.setCenter(point, 15);
		  var marker = new GMarker(point);
		  map.addOverlay(marker);
		  marker.openInfoWindowHtml(address);
		  start = point;
		}
	  }
	);
  }
}

function buildMap() {

 map = new GMap2(mapdiv, {draggableCursor:"auto", draggingCursor:"move"});

 // Add a div element for toolips
 tooltip = document.createElement("div");
 tooltip.className="tooltip";
 map.getPane(G_MAP_MARKER_PANE).appendChild(tooltip);

	if(geoData!='')
	{
		//alert(geoData);
		//var ptArr=new Array(geoData);
		var ptArr=geoData.split("),");
		for(var f=0;f<ptArr.length;f++)
		{
			npts1=ptArr[f].split("(");
			npts2=npts1[1].split(",");
			//pts=(npts2[0]+","+npts2[1]);
			
			if((ptArr.length-1)==f)
			{
				var pixelX1=(npts2[0]);
				var tmp=(npts2[1]).split(")");
				var pixelY1=tmp[0];
				pts=new GLatLng(Number(pixelX1),Number(pixelY1));
				map.setCenter(pts, 15);
			}
		}
	}
	else
	{
		map.setCenter(new GLatLng(17.385652586418747, 78.49096298217773), 15);
	}
 // Load initial map and a bunch of controls
	map.addControl(new GMapTypeControl());
	map.addControl(new GScaleControl());
	map.addControl(new GSmallMapControl());
	map.enableScrollWheelZoom();
	map.enableContinuousZoom();

 // Add click listener
 GEvent.addListener(map, "click", leftClick);
	if(geoData!='')
	{
		createOldMarker(geoData);
		//alert(geoData);
	}
}


function createOldMarker(dts)
{
if(dts!='') 
{
	var ptArr=new Array(dts);
	ptArr=geoData.split("),");
while(count<ptArr.length)
{
	npts1=ptArr[count].split("(");
	npts2=npts1[1].split(",");
	//pts=(npts2[0]+","+npts2[1]);
	pixelX=(npts2[0]);
	if((ptArr.length-1)==count)
	{
		//alert(npts2[1]);
		var tmp=(npts2[1]).split(")");
		pixelY=tmp[0];
	}
	else
	{
		pixelY=(npts2[1]);
	}
	point=new GLatLng(Number(pixelX),Number(pixelY));
//	findLocation(point);
	document.getElementById("route").innerHTML += Number(pixelX) + ' ' + Number(pixelY) +"<br>";

	 if(count%2 != 0) 
	 {
	  // Light blue marker icons
	  var icon = new GIcon();
	  icon.image =  icon_url +"m-over-square.png";
	  addIcon(icon);
	 }
	  else {
	  // Purple marker icons
	  var icon = new GIcon();
	  icon.image = icon_url +"m-over-square.png";
	  addIcon(icon);
	 }

	  // Make markers draggable
	  var marker = createMarker(point,icon);
	  map.addOverlay(marker);
	  marker.content = count;
	  markers.push(marker);
	  marker.tooltip = "Point "+ count;


 drawOverlay();
 count++;
 }
}
}
function createMarker(point,icon)
{
	var marker =  new GMarker(point, {icon:icon, draggable:true, bouncy:false, dragCrossMove:true});

	  GEvent.addListener(marker, "mouseover", function() {
	   showTooltip(marker);
	  });
	
	  GEvent.addListener(marker, "mouseout", function() {
	   tooltip.style.display = "none";
	 });
	
	  // Drag listener
	  GEvent.addListener(marker, "drag", function() {
	   tooltip.style.display= "none";
	   drawOverlay();
	  });
	  
	  
	    GEvent.addListener(marker, "click", function() {
   tooltip.style.display = "none";

  // Find out which marker to remove
  for(var n = 0; n < markers.length; n++) {
   if(markers[n] == marker) {
    map.removeOverlay(markers[n]);
    break;
   }
  }

  // Shorten array of markers and adjust counter
  markers.splice(n, 1);
  if(markers.length == 0) {
    count = 0;
  }
   else {
    count = markers[markers.length-1].content;
    drawOverlay();
  }
  });
	return marker;


}	

function leftClick(overlay, point) {

 if(point) {
  findLocation(point);
  document.getElementById("route").innerHTML += point.y.toFixed(6) + ' ' + point.x.toFixed(6) +"<br>";
  routePoints.push(point);

 if(count%2 != 0) {

  // Light blue marker icons
  var icon = new GIcon();
  icon.image =  icon_url +"m-over-square.png";
  addIcon(icon);
 }
  else {
  // Purple marker icons
  var icon = new GIcon();
  icon.image = icon_url +"m-over-square.png";
  addIcon(icon);
 }

  // Make markers draggable
  var marker = new GMarker(point, {icon:icon, draggable:true, bouncy:false, dragCrossMove:true});
  map.addOverlay(marker);
  marker.content = count;
  markers.push(marker);
  marker.tooltip = "Point "+ count;

  GEvent.addListener(marker, "mouseover", function() {
   showTooltip(marker);
  });

  GEvent.addListener(marker, "mouseout", function() {
   tooltip.style.display = "none";
 });

  // Drag listener
  GEvent.addListener(marker, "drag", function() {
   tooltip.style.display= "none";
   drawOverlay();
  });

  // Second click listener
  GEvent.addListener(marker, "click", function() {
	  
   tooltip.style.display = "none";

  // Find out which marker to remove
  for(var n = 0; n < markers.length; n++) {
   if(markers[n] == marker) {
    map.removeOverlay(markers[n]);
    break;
   }
  }

  // Shorten array of markers and adjust counter
  markers.splice(n, 1);
  if(markers.length == 0) {
    count = 0;
  }
   else {
    count = markers[markers.length-1].content;
    drawOverlay();
  }
  });
 drawOverlay();
 count++
 }
}


function toggleMode() {

 if(markers.length > 1) drawOverlay();
}

function drawOverlay(){

 // Check radio button
 var lineMode = false;

 if(poly) { map.removeOverlay(poly); }
 points.length = 0;

 for(i = 0; i < markers.length; i++) {
  points.push(markers[i].getLatLng());
 }
 if(lineMode) {
   // Polyline mode
   poly = new GPolyline(points, lineColor, lineWeight, lineOpacity);
   var length = poly.getLength()/1000;
   var unit = " km";
   report.innerHTML = "Total line length:<br> " + length.toFixed(3) + unit;
  }
  else {
   // Polygon mode
   points.push(markers[0].getLatLng());
   poly = new GPolygon(points, lineColor, lineWeight, lineOpacity, fillColor, fillOpacity);
   var area = poly.getArea()/(1000*1000);
   var unit = " km&sup2;";
   report.innerHTML = "Area of polygon:<br> " + area.toFixed(3) + unit;
  }
  map.addOverlay(poly);
}

function showTooltip(marker) { // Display tooltips

 tooltip.innerHTML = marker.tooltip;
 tooltip.style.display = "block";

 // Tooltip transparency specially for IE
 if(typeof(tooltip.style.filter) == "string") {
 tooltip.style.filter = "alpha(opacity:70)";
 }

 var currtype = map.getCurrentMapType().getProjection();
 var point= currtype.fromLatLngToPixel(map.fromDivPixelToLatLng(new GPoint(0,0),true),map.getZoom());
 var offset= currtype.fromLatLngToPixel(marker.getLatLng(),map.getZoom());
 var anchor = marker.getIcon().iconAnchor;
 var width = marker.getIcon().iconSize.width + 6;
// var height = tooltip.clientHeight +18;
 var height = 10;
 var pos = new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(offset.x - point.x - anchor.x + width, offset.y - point.y -anchor.y - height)); 
 pos.apply(tooltip);
}


function clearMap() {

 // Clear current map and reset arrays
 map.clearOverlays();
 points.length = 0;
 markers.length = 0;
 count = 0;
 routePoints.length=0;
 document.getElementById("route").innerHTML="Route points:";
 report.innerHTML = "&nbsp;";
}

//]]>
</script>

</body>
</html>
