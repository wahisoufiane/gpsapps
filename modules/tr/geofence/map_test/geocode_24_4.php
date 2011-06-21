<?php 
//print_r($_GET);
//exit;
if(isset($_GET[val1]) && $_GET[val1]!='') 
{
?>
<script language="javascript">
geoData= '<?php echo $_GET[val1];?>';
polyData= '<?php echo $_GET[val2];?>';
gfData='<?php echo $_GET[gfd];?>';
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head><title>Chekhra :: Geocode</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>


<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ" type="text/javascript"></script>
<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&key=ABQIAAAAEcRU5S4wllAASrNAt60gdRTt0x3oJuMbKm0gKGN-LKGVzGrg5BQPHmzzSownKJ1WWRn3YEDh_3AJOQ" type="text/javascript"></script>
<script src="http://www.google.com/uds/solutions/localsearch/gmlocalsearch.js?adsense=pub-1227201690587661" type="text/javascript"></script>
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
var myMap = null;
var localSearch = null;
var myQueryControl = null;
var pts= new Array();
var cpts=new Array();
var gf2= new Array();
var c=0;
var c1=0;
//	http://www.3rdcrust.com/search/searchmap.html
function displayMap(){
	myMap = new GMap2(document.getElementById("map"));
	myMap.addControl(new GSmallMapControl());
	myMap.addControl(new GMenuMapTypeControl());
	myMap.enableScrollWheelZoom();
	myMap.enableContinuousZoom();
	myMap.addControl(new GScaleControl());
	localSearch = new google.maps.LocalSearch();//{externalAds : document.getElementById("ads")});
	myMap.addControl(localSearch);
	myQueryControl = new QueryControl(localSearch);
	myMap.addControl(myQueryControl);
if(gfData!='' && geoData=='')
{
	var y=0;
	var gfArr=gfData.split("@");
	while( y<gfArr.length-1)
	{
		gf1=gfArr[y].split(",");
		gf2.push(new GLatLng(Number(gf1[0]),Number(gf1[1])));
		
		if(y==(gfArr.length)-2)
		{
			myMap.setCenter(new GLatLng(Number(gf1[0]),Number(gf1[1])),14);
		}
		; y++;
	}
	
	gf2.push(gf2[0]);
	var gf3=new GPolyline(gf2,"#0000ff",3,1);
	//var gf3 = new GPolygon(gf2, null, 5, 0.7, "#aaaaff", 0.5 );
	myMap.addOverlay(gf3);

}
else if(gfData!='' && geoData!='')
{
	var y=0;
	var gfArr=gfData.split("@");
	while( y<gfArr.length-1)
	{
		gf1=gfArr[y].split(",");
		gf2.push(new GLatLng(Number(gf1[0]),Number(gf1[1])));
		
		if(y==(gfArr.length)-2)
		{
			myMap.setCenter(new GLatLng(Number(gf1[0]),Number(gf1[1])),14);
		}
		; y++;
	}
	
	gf2.push(gf2[0]);
	var gf3=new GPolyline(gf2,"#0000ff",3,1);
	//var gf3 = new GPolygon(gf2, null, 5, 0.7, "#aaaaff", 0.5 );
	myMap.addOverlay(gf3);

	var ptArr=geoData.split("@");
	for(var f=0;f<(ptArr.length)-1;f++)
	{
		npts1=ptArr[f].split(",");
		pnts=Number(npts1[1])+","+Number(npts1[2]);
		if(c==0)
		{
			pts[c] = pnts;
			c++;
		}
		else
		{
			pts[c] = pnts;
			c++;
		}
		setTimeout("createCircle(new GLatLng("+ Number(npts1[1]) + ", " + Number(npts1[2]) +"), "+ ((Number(npts1[0])*5280)/3.2808399)+");", 300);
		fillDiv(pnts);
		if((ptArr.length-2)==f)
		{
			//alert(npts1);													/// 5280* 3.2808399
			myMap.setCenter(new GLatLng(Number(npts1[1]),Number(npts1[2])), 14);
		}
	}
	//alert(polyData);
}
else
{
	myMap.setCenter(new GLatLng(17.385044,78.486671), 14);

}
  GEvent.addListener(myMap, "click", function(overlay, point) {
    if (point) 
	{
		var polyside=inPoly(gf2,point);
		if (!polyside) 
		{
			 alert('Click Inside Blue Polyline to make geocode');
		}
		else
		{      
			if(c==0)
			{
				pts[c] = point.y.toFixed(8) + ',' + point.x.toFixed(8) ;
				c++;
			}
			else
			{
				pts[c] = point.y.toFixed(8) + ',' + point.x.toFixed(8) ;
				c++;
			}
			  singleClick = !singleClick;
			  fillDiv(pts);
			  setTimeout("if (singleClick) createCircle(new GLatLng("+ point.y + ", " + point.x +"), 250);", 300);
		 }
    }
  });
}
function inPoly(poly,pt){
	 var npoints = poly.length-1; // number of points in polygon
	// this assumes that last point is same as first
	 var xnew,ynew,xold,yold,x1,y1,x2,y2,i;
	 var inside=false;

	 if (npoints < 3) { // points don't describe a polygon
		  return false;
	 }
	 xold=poly[npoints-1].x; yold=poly[npoints-1].y;
	 
	 for (i=0 ; i < npoints ; i++) {
		  xnew=poly[i].x; ynew=poly[i].y;
		  if (xnew > xold) {
			   x1=xold; x2=xnew; y1=yold; y2=ynew;
		  }else{
			   x1=xnew; x2=xold; y1=ynew; y2=yold;
		  }
		  if ((xnew < pt.x) == (pt.x <= xold) && ((pt.y-y1)*(x2-x1) < (y2-y1)*(pt.x-x1))) {
			   inside=!inside;
		  }
		  xold=xnew; yold=ynew;
	 }; // for

	 return inside;
}; // function inPoly
</script>

<style type="text/css">
  @import url("gsearch.css");
  @import url("gmlocalsearch.css");

  div#GQueryControl {
    background-color: white;
    width: 155;
  }
</style>
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
<body onLoad="displayMap();" style="background:#fff">
<div class="ui-layout-center" id="map">
</div>

<div class="ui-layout-east">
<form id="f" action="">
<table id="" border="0" cellspacing="0" cellpadding="0">
<tr><td>
Click on the map to set polygon circle. Use the arrow to change the radius of that circle. All circle are draggable and seperately removable.

</td></tr>
<tr><td id="status" style="height:70px">&nbsp;</td></tr>
<tr><td>
<!--<a href="#" class="button" style="text-align:center" onClick="clearMap();return false;">Clear Map</a>
--><a href="#" class="button" style="text-align:center" onClick="sendBackData();return false;">Done</a>

</td></tr><tr><td>
<span class="button" id="route" style="display:none;">Route points:<br></span>

</td></tr>
</table>
</form>
<span class="map_logo" ><img src="images/map_logo.png" width="70" height="21" /><br />
          © 2008 - 2009,Chekhra.com</span> 
</div>
	<!--<div id="map" style="width: 100%; height: 95%;"></div>
	<div style="font-family: arial, sans-serif;">Made by <a href="http://www.3rdcrust.com">3rdCrust.com</a></div>
	div id="QueryControl"></div>
	<div id="ads">
	</div -->
<script>

  var metric = false;
  var singleClick = false;
  var queryCenterOptions = new Object();
  var queryLineOptions = new Object();

queryCenterOptions.icon = new GIcon();
queryCenterOptions.icon.image = "images/centerArrow.png";
queryCenterOptions.icon.iconSize = new GSize(20,20);
queryCenterOptions.icon.shadowSize = new GSize(0, 0);
queryCenterOptions.icon.iconAnchor = new GPoint(10, 10);
queryCenterOptions.draggable = true;
queryCenterOptions.bouncy = false;

queryLineOptions.icon = new GIcon();
queryLineOptions.icon.image = "images/resizeArrow.png";
queryLineOptions.icon.iconSize = new GSize(25,20);
queryLineOptions.icon.shadowSize = new GSize(0, 0);
queryLineOptions.icon.iconAnchor = new GPoint(12, 10);
queryLineOptions.draggable = true;
queryLineOptions.bouncy = false;

function fillDiv(data)
{
	for( u=0;u<data.length;u++)
	{
	if(u==0)
		document.getElementById("route").innerHTML=data[u];	
	else document.getElementById("route").innerHTML+="<br>"+data[u];
	}
}
function createCircle(point, radius) {
  singleClick = false;
  geoQuery = new GeoQuery();
  geoQuery.initializeCircle(radius, point, myMap);
  myQueryControl.addGeoQuery(geoQuery);
  geoQuery.render();
  geoQuery.render2();

}

function destination(orig, hdng, dist) {
  var R = 6371; // earth's mean radius in km
  var oX, oY;
  var x, y;
  var d = dist/R;  // d = angular distance covered on earth's surface
  hdng = hdng * Math.PI / 180; // degrees to radians
  oX = orig.x * Math.PI / 180;
  oY = orig.y * Math.PI / 180;

  y = Math.asin( Math.sin(oY)*Math.cos(d) + Math.cos(oY)*Math.sin(d)*Math.cos(hdng) );
  x = oX + Math.atan2(Math.sin(hdng)*Math.sin(d)*Math.cos(oY), Math.cos(d)-Math.sin(oY)*Math.sin(y));

  y = y * 180 / Math.PI;
  x = x * 180 / Math.PI;
  return new GLatLng(y, x);
}

function distance(point1, point2) {
  var R = 6371; // earth's mean radius in km
  var lon1 = point1.lng()* Math.PI / 180;
  var lat1 = point1.lat() * Math.PI / 180;
  var lon2 = point2.lng() * Math.PI / 180;
  var lat2 = point2.lat() * Math.PI / 180;

  var deltaLat = lat1 - lat2
  var deltaLon = lon1 - lon2

  var step1 = Math.pow(Math.sin(deltaLat/2), 2) + Math.cos(lat2) * Math.cos(lat1) * Math.pow(Math.sin(deltaLon/2), 2);
  var step2 = 2 * Math.atan2(Math.sqrt(step1), Math.sqrt(1 - step1));
  return step2 * R;
}

function GeoQuery() {

}

GeoQuery.prototype.CIRCLE='circle';
GeoQuery.prototype.COLORS=["#0000ff", "#00ff00", "#ff0000"];
var COLORI=0;

GeoQuery.prototype = new GeoQuery();
GeoQuery.prototype._map;
GeoQuery.prototype._type;
GeoQuery.prototype._radius;
GeoQuery.prototype._dragHandle;
GeoQuery.prototype._centerHandle;
GeoQuery.prototype._polyline;
GeoQuery.prototype._color ;
GeoQuery.prototype._control;
GeoQuery.prototype._points;
GeoQuery.prototype._dragHandlePosition;
GeoQuery.prototype._centerHandlePosition;


GeoQuery.prototype.initializeCircle = function(radius, point, map) {
    this._type = this.CIRCLE;
    this._radius = radius;
    this._map = map;
    this._dragHandlePosition = destination(point, 90, this._radius/1000);
    this._dragHandle = new GMarker(this._dragHandlePosition, queryLineOptions);
    this._centerHandlePosition = point;
    this._centerHandle = new GMarker(this._centerHandlePosition, queryCenterOptions);
    this._color = this.COLORS[COLORI++ % 3];
    map.addOverlay(this._dragHandle);
    map.addOverlay(this._centerHandle);
    var myObject = this;
    GEvent.addListener (this._dragHandle, "dragend", function() { myObject.updateCircle1(1);});
    GEvent.addListener (this._dragHandle, "drag", function() {myObject.updateCircle(1);});
    GEvent.addListener(this._centerHandle, "dragend", function() {myObject.updateCircle(2);});
    GEvent.addListener(this._centerHandle, "drag", function() {myObject.updateCircle(2);});
}
GeoQuery.prototype.updateCircle1 = function (type) {
    this._map.removeOverlay(this._polyline);
    if (type==1) {
      this._dragHandlePosition = this._dragHandle.getPoint();
      this._radius = distance(this._centerHandlePosition, this._dragHandlePosition) * 1000;
 //     alert(this.render1());
	  //alert(this._control.getIndex(this)+"	"+c1);
	  cpts[this._control.getIndex(this)] = this.render1()+"#";
	  //c1++;

    } else {
      this._centerHandlePosition = this._centerHandle.getPoint();
      this.render();
      this._dragHandle.setPoint(this.getEast());
    }
}

GeoQuery.prototype.updateCircle = function (type) {
    this._map.removeOverlay(this._polyline);
    if (type==1) {
      this._dragHandlePosition = this._dragHandle.getPoint();
      this._radius = distance(this._centerHandlePosition, this._dragHandlePosition) * 1000;
      this.render();
    } else {
      this._centerHandlePosition = this._centerHandle.getPoint();
      this.render();
      this._dragHandle.setPoint(this.getEast());
    }
}
GeoQuery.prototype.render2 = function() {
  if (this._type == this.CIRCLE) {
    this._points = [];
    var distance = this._radius/1000;
    for (i = 0; i < 6; i++) {
      this._points.push(destination(this._centerHandlePosition, i * 360/6, distance) );
    }
    this._points.push(destination(this._centerHandlePosition, 0, distance) );
	cpts[c1] = this._points+"#";
	c1++;
  }
  return this._points;
}

GeoQuery.prototype.render1 = function() {
  if (this._type == this.CIRCLE) {
    this._points = [];
    var distance = this._radius/1000;
    for (i = 0; i < 6; i++) {
      this._points.push(destination(this._centerHandlePosition, i * 360/6, distance) );
    }
    this._points.push(destination(this._centerHandlePosition, 0, distance) );
    //this._polyline = new GPolyline(this._points, this._color, 6);
    this._polyline = new GPolygon(this._points, this._color, 1, 1, this._color, 0.2);
    this._map.addOverlay(this._polyline)
    this._control.render();
  }
  return this._points;
}

GeoQuery.prototype.render = function() {
  if (this._type == this.CIRCLE) {
    this._points = [];
    var distance = this._radius/1000;
    for (i = 0; i < 6; i++) {
      this._points.push(destination(this._centerHandlePosition, i * 360/6, distance) );
    }
    this._points.push(destination(this._centerHandlePosition, 0, distance) );
	//cpts[c1] = this._points+"@";
	//c1++;
    //this._polyline = new GPolyline(this._points, this._color, 6);
    this._polyline = new GPolygon(this._points, this._color, 1, 1, this._color, 0.2);
    this._map.addOverlay(this._polyline)
    this._control.render();
  }
}

GeoQuery.prototype.remove = function() {
  this._map.removeOverlay(this._polyline);
  this._map.removeOverlay(this._dragHandle);
  this._map.removeOverlay(this._centerHandle);
}

GeoQuery.prototype.getRadius = function() {
    return this._radius;
}

GeoQuery.prototype.getHTML = function() {
  return "<SPAN><FONT color='"+ this._color + "'>" + this.getDistHtml() + "</FONT></SPAN>";
}

GeoQuery.prototype.getDistHtml = function() {
  result = "<IMG SRC='images/close.gif' onclick='myQueryControl.remove(" + this._control.getIndex(this) + ")'/>";
  if (metric) {
    if (this._radius < 1000) {
      result +=  this._radius.toFixed(1);
    } else {
      result +=  (this._radius / 1000).toFixed(1);
    }
  } else {
    var radius = this._radius * 3.2808399;
      result +=  (radius / 5280).toFixed(2); 
  }
  return result;   
}

GeoQuery.prototype.getNorth = function() {
  return this._points[0];
}

GeoQuery.prototype.getSouth = function() {
  return this._points[(72/2)];
}

GeoQuery.prototype.getEast = function() {
  return this._points[(72/4)];
}

GeoQuery.prototype.getWest = function() {
  return this._points[(72/4*3)];
}

function QueryControl (localSearch) {
  this._localSearch = localSearch;
}

QueryControl.prototype = new GControl();
QueryControl.prototype._geoQueries ;
QueryControl.prototype._mainDiv;
QueryControl.prototype._queriesDiv;
QueryControl.prototype._minStar;
QueryControl.prototype._minPrice;
QueryControl.prototype._maxPrice;
QueryControl.prototype._timeout;
QueryControl.prototype._localSearch;

QueryControl.prototype.initialize = function(map) {
  this._mainDiv = document.createElement("div");
  this._mainDiv.id = "GQueryControl";
  titleDiv = document.createElement("div");
  titleDiv.id = "GQueryControlTitle";
  titleDiv.className="button";
  titleDiv.appendChild(document.createTextNode("Filter (in Miles)"));
  this._mainDiv.appendChild(titleDiv);
  this._queriesDiv = document.createElement("div");
  this._queriesDiv.id = "queriesDiv";
  this._queriesDiv .className="button";
  this._mainDiv.appendChild(this._queriesDiv);

  map.getContainer().appendChild(this._mainDiv);
  this._geoQueries = new Array();
  return this._mainDiv;
}

QueryControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(50, 10));
}

QueryControl.prototype.addGeoQuery = function(geoQuery) {
  this._geoQueries.push(geoQuery);
  geoQuery._control = this;
  newDiv = document.createElement("div");
  newDiv.innerHTML = geoQuery.getHTML();
  this._queriesDiv.appendChild(newDiv);
 
}

QueryControl.prototype.render = function() {
  for (i = 0; i < this._geoQueries.length; i++) {
    geoQuery = this._geoQueries[i];
    this._queriesDiv.childNodes[i].innerHTML = geoQuery.getHTML();
  }
  if (this._timeout == null) {
    this._timeout = setTimeout(myQueryControl.query, 1000);
  } else {
    clearTimeout(this._timeout);
    this._timeout = setTimeout(myQueryControl.query, 1000);
  }
}

QueryControl.prototype.query = function() {
  listMarkers = myQueryControl._localSearch.markers.slice();
  for (i = 0; i < listMarkers.length; i++) {
    marker = listMarkers[i].marker;
    result = listMarkers[i].resultsListItem;
    listImage = marker.getIcon().image;
    inCircle = true;
    for (j = 0; j < myQueryControl._geoQueries.length; j++) {
      geoQuery = myQueryControl._geoQueries[j];
      dist = distance(marker.getLatLng(), geoQuery._centerHandlePosition); 
      if (dist > geoQuery._radius / 1000) {
        inCircle = false;
        break;
      }
    }
    if (inCircle) {
      marker.setImage(listImage);
      result.childNodes[1].style.color = '#0000cc';
    } else {
      var re = new RegExp(".*(marker.\.png)");
      marker.setImage(listImage.replace(re, "img/$1"));
      result.childNodes[1].style.color = '#b0b0cc';
    }
  }
}
function removeByElement(arrayName,arrayElement)
{
for(var i=0; i<arrayName.length;i++ )
 { 
	if(i==arrayElement)
		arrayName.splice(i,1); 
  } 
  return arrayName;
}
QueryControl.prototype.remove = function(index) {
  this._geoQueries[index].remove();
  this._queriesDiv.removeChild(this._queriesDiv.childNodes[index]);
  delete this._geoQueries[index];
  pts.splice(index,1);
  c--;
  cpts.splice(index,1);
  c1--;
  this._geoQueries.splice(index,1); 
  //fillDiv(removeByElement(pts,index));
  this.render();
}

QueryControl.prototype.getIndex = function(geoQuery) {
  for (i = 0; i < this._geoQueries.length; i++) {
    if (geoQuery == this._geoQueries[i]) {
      return i;
    }
  }
  return -1;
}
function sendBackData()
{
	if(pts.length ==0 || c==0)
	{
		alert('Make proper Geocode');
	}
	else
	{
	var browser=navigator.appName;
	var b_version=navigator.appVersion;
	var version=parseFloat(b_version);
	
	//document.write("Browser name: "+ browser);
	//document.write("<br />");
	//document.write("Browser version: "+ version);
	
	if(browser=='Microsoft Internet Explorer')
	{
		final1=document.getElementById('queriesDiv').innerHTML.split("</FONT>");
			for(l=0;l<(final1.length-1);l++)
			{
				final2=final1[l].split('">');
				//alert(final2[1]);
				//final3=final2[1].split('</font></span>');
				//alert(final2[2]+" "+pts[l]);
				if(l==0)
					fin=final2[1]+","+pts[l];
				else
					fin+=final2[1]+","+pts[l];
	
				fin+="@";
			}
			//alert(cpts.length+"	"+cpts[0]);
			opener.document.getElementById('<?php echo $_GET[id1]; ?>').value=fin;		//latlng;
			opener.document.getElementById('<?php echo $_GET[id2]; ?>').value=cpts;		//latlng;
			opener.showPoints(fin);
			window.close();
	}else if(browser=='Netscape')
	{
			final1=document.getElementById('queriesDiv').innerHTML.split("</font>");
		for(l=0;l<(final1.length-1);l++)
		{
			final2=final1[l].split('">');
				//alert(pts);
	//		final3=final2[1].split('</font></span>');
			//alert(final2[2]+" "+pts[l]);
			if(l==0)
				fin=final2[2]+","+pts[l];
			else
				fin+=final2[2]+","+pts[l];

			fin+="@";
		}
			//alert(cpts.length+"	"+cpts[0]);
			opener.document.getElementById('<?php echo $_GET[id1]; ?>').value=fin;		//latlng;
			opener.document.getElementById('<?php echo $_GET[id2]; ?>').value=cpts;		//latlng;
			opener.showPoints(fin);
			window.close();
	
	}
	}
}
function clearMap() {

 // Clear current map and reset arrays
 myMap.clearOverlays();
 //alert(pts.length);
 for(b=0;b<pts.length;b++)
 {
 	myQueryControl.remove(b);
 }
 pts.length = 0;
// markers.length = 0;
 c = 0;
 //routePoints.length=0;
 //document.getElementById('queriesDiv').innerHTML="";
 //report.innerHTML = "&nbsp;";
}


</script>
</body>
</html>
