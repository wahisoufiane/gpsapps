<?php //echo $_GET[val];?>
<?php 
if(isset($_GET[val]) && $_GET[val]!='') 
{
?>
<script language="javascript">
geoData= '<?php echo $_GET[val];?>';
</script>
<?php
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
<link rel="stylesheet" type="text/css" href="include.css" />
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta name="author" content="Wolfgang Pichler" />
<meta name="URL" content="http://wolfpil.googlepages.com" />

<title>Resizable Polygons</title>

<style type="text/css">

body { font-family: Verdana; }

h3 { margin-left: 8px; }

#map { height: 400px;
	width: 550px;
	border: 1px solid gray;
	margin-top: 8px;
	margin-left: 8px;
}
.button { display: block;
	width: 180px;
	border: 1px Solid #565;
	background-color:#F5F5F5; 
	padding: 3px;
	text-decoration: none;
	text-align:center;
	font-size:smaller;
}
.button:hover {
	background-color: white;
}


#descr { position:absolute;
	top:44px;
	left: 580px;
	width: 250px;
}

</style>

<script src="http://maps.google.com/maps?file=api&v=2" type="text/javascript">
</script>

</head>

<body onLoad="loadMap()" onUnload="GUnload()">

<h3>Draw Resizable Polygons</h3>

<div id="map"> </div>		

<table id="descr" border="0" cellspacing="10" cellpadding="1">
<tr><td>
Click at least at three different places on the map to draw a polygon.
The corners of the polygon are draggable and removable. You can add further vertices simply by click.
</td></tr>
<tr><td>
Area of polygon:
</td></tr>

<tr><td id="status">&nbsp; </td></tr>
<tr><td height="20">&nbsp;


</td></tr><tr><td>
<a href="#" class="button" onClick="zoomToPoly();return false;">Zoom To Polygon</a>
</td></tr><tr><td>
<a href="#" class="button" onClick="clearPoly();return false;">Remove Polygon</a>
</td></tr><tr><td style="padding-right:19px; height:30px;">
<span class="include"><a href="index.html">Back</a></span>

</td></tr>
</table>


<script type="text/javascript">
//	http://wolfpil.googlepages.com/polygon.html
//<![CDATA[		

 // Force SVG on also on Linux and BSD machines
 if(navigator.platform.match(/linux|bsd/i)) {
  _mSvgEnabled = _mSvgForced = true;
 }

// Global variables
var map;
var polyShape;
var polyLineColor = "#3355ff";
var polyFillColor = "#335599";
var polyPoints = new Array();
var markers = new Array();
var report = document.getElementById("status");


function loadMap() {

 map = new GMap2(document.getElementById("map"), {draggableCursor:'auto', draggingCursor:'move'});
 map.setCenter(new GLatLng(19.129, 73.054), 10);
 map.addMapType(G_PHYSICAL_MAP);
 var hierarchy = new GHierarchicalMapTypeControl();
 hierarchy.addRelationship(G_SATELLITE_MAP, G_HYBRID_MAP, "Labels", true);
 map.addControl(hierarchy);
 map.addControl(new GSmallMapControl());
 //map.disableDoubleClickZoom();
 GEvent.addListener(map, "click", leftClick);
 drawPoly();

}


function addIcon(icon) { // Add icon attributes

 icon.iconSize = new GSize(11, 11);
 icon.dragCrossSize = new GSize(0, 0);
 icon.shadowSize = new GSize(11, 11);
 icon.iconAnchor = new GPoint(5, 5);
// icon.infoWindowAnchor = new GPoint(5, 1);
}


function leftClick(overlay, point) {

 if(point) {

  // Square marker icons
  var square = new GIcon();
  square.image = "square.png";
  addIcon(square);

  // Make markers draggable
  var marker =new GMarker(point, {icon:square, draggable:true, bouncy:false, dragCrossMove:true});
  markers.push(marker);
  map.addOverlay(marker);

  GEvent.addListener(marker, "drag", function() {
   drawPoly();
  });

  GEvent.addListener(marker, "mouseover", function() {
    marker.setImage("m-over-square.png");
  });

  GEvent.addListener(marker, "mouseout", function() {
   marker.setImage("square.png");
  });

  // Second click listener to remove the square
  GEvent.addListener(marker, "click", function() {

  // Find out which square to remove
  for(var n = 0; n < markers.length; n++) {
   if(markers[n] == marker) {
    map.removeOverlay(markers[n]);
    break;
   }
  }
  markers.splice(n, 1);
  drawPoly();
  });

  drawPoly();
 }
}

function drawPoly() {


 if(polyShape) map.removeOverlay(polyShape);
 polyPoints.length = 0;	
//alert(geoData);
  var square = new GIcon();
  square.image = "square.png";
  addIcon(square);

	
	var ptArr=new Array(geoData);
	ptArr=geoData.split("),");
for(f=0;f<ptArr.length;f++)
{
	npts1=ptArr[f].split("(");
	npts2=npts1[1].split(",");
	//pts=(npts2[0]+","+npts2[1]);
	pixelX=(npts2[0]);
	if((ptArr.length-1)==f)
	{
		//alert(npts2[1]);
		var tmp=(npts2[1]).split(")");
		pixelY=tmp[0];
	}
	else
	{
		pixelY=(npts2[1]);
	}
	pts=new GLatLng(Number(pixelX),Number(pixelY));
    var marker1 = createMarker(pts,'Some stuff to display in the<br>Second Info Window')
	//var marker1 =new GMarker(pts);
 	//markers.push(marker);
  	map.addOverlay(marker1);
	//alert(pts);
	//var polyPixel = new GPoint(pixelX,pixelY);
	polyPoints.push(new GLatLng(Number(pixelX),Number(pixelY)));
	//var polyPixel = new GPoint(pixelX,pixelY);
	//alert(polyPixel);
	//polyPoints.push(polyPixel);
	
}//alert(polyPoints);
  polyShape = new GPolygon(polyPoints, polyLineColor, 3, .8, polyFillColor,.3);
  map.addOverlay(polyShape);

}
function createMarker(point,html) {
        var marker = new GMarker(point);
        GEvent.addListener(marker, "click", function() {
          marker.openInfoWindowHtml(html+"<br>"+point);
        });
        return marker;
      }

function zoomToPoly() {

 if(polyShape && polyPoints.length > 0) {
  var bounds = polyShape.getBounds();
  map.setCenter(bounds.getCenter());
  map.setZoom(map.getBoundsZoomLevel(bounds));
 }
}

function clearPoly() {

 // Remove polygon and reset arrays
 map.clearOverlays();
 polyPoints.length = 0;
 markers.length = 0;
 report.innerHTML = "&nbsp;";
}

//]]>
</script>   

</body>
</html>
