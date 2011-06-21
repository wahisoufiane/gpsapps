var myMap = null;
var localSearch = null;
var myQueryControl = null;
var pts= new Array();
var gf2= new Array();
var stArr= new Array();
var radiusArr = new Array();
var rd = 0;
var c=0;
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
		dispStop1(stData);
		if((ptArr.length-2)==f)
		{
			//showMsg(npts1);													/// 5280* 3.2808399
			myMap.setCenter(new GLatLng(Number(npts1[1]),Number(npts1[2])), 14);
		}
	}
}
else if(gfData=='' && geoData!='')
{

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
		dispStop1(stData);
		if((ptArr.length-2)==f)
		{
			//showMsg(npts1);													/// 5280* 3.2808399
			myMap.setCenter(new GLatLng(Number(npts1[1]),Number(npts1[2])), 14);
		}
	}
}
else
{
	myMap.setCenter(new GLatLng(17.385044,78.486671), 14);

}
GEvent.addListener(myMap, "click", function(overlay, point) {
if (point) 
{
	var polyside=inPoly(gf2,point);
	if (polyside && chk!=1) 
	{
		 showMsg('Click Inside Blue Polyline to make geocode');
	}
	else
	{      
		if(c==0)
		{
			pts[c] = point.y.toFixed(12) + ',' + point.x.toFixed(12) ;
			myHtml="<b>Name It:</b><br><br><input type='text' name='txtPoint"+c+"' id='txtPoint"+c+"' value='Point"+(c+1)+"'/>";
			myHtml+=" <a href='#' onclick='setStopPoint(c);'>Add</a>";				
			myMap.openInfoWindow(point, myHtml);
			c++;
		}
		else
		{
			pts[c] = point.y.toFixed(12) + ',' + point.x.toFixed(12) ;
			myHtml="<b>Name It:</b><br><br><input type='text' name='txtPoint"+c+"' id='txtPoint"+c+"' value='Point"+(c+1)+"' />";
			myHtml+=' <a href="#" onclick="setStopPoint(c);">Add</a>';				
			myMap.openInfoWindow(point, myHtml);
			c++;
		}
		  singleClick = !singleClick;
		  fillDiv(pts);
		  setTimeout("if (singleClick) createCircle(new GLatLng("+ point.y + ", " + point.x +"), 800);", 300);
	 }
}
});
showMsg("Click on map to create geofence Circle");
}
var stopName=/^[a-zA-Z0-9][a-zA-Z0-9 ]*$/;
function validThis(v1)
{
 	if(document.getElementById('txtPoint'+(v1-1)).value=="")
	{
		showMsg('Stop Name required');
		document.getElementById('txtPoint'+(v1-1)).focus();
		return false;
	}
  	else if (document.getElementById('txtPoint'+(v1-1)).value.indexOf(' ') > -1) 
	{
		showMsg('Spaces not allowed in Stop Name');
		document.getElementById('txtPoint'+(v1-1)).focus();
		document.getElementById('txtPoint'+(v1-1)).select();
		return false;
	}
	else if (!stopName.test(document.getElementById('txtPoint'+(v1-1)).value)) 
	{
		showMsg('Only alphanumeric allowed');
		document.getElementById('txtPoint'+(v1-1)).focus();
		document.getElementById('txtPoint'+(v1-1)).select();
		return false;	
	}
	return true;
}
var ajax1=new sack();
function setStopPoint(v1)
{
	var flg1=validThis(v1);
	if(flg1)
	{
		stArr[v1-1]=document.getElementById('txtPoint'+(v1-1)).value;
		ajax1.requestFile = '../../user/ajax_server.php?addGeoPoint=y&param='+pts[v1-1]+'&name='+stArr[v1-1]+'&radius='+radiusArr[v1-1];
		//document.write(ajax1.requestFile);
		ajax1.onCompletion = function(){insertPoint()};
		ajax1.runAJAX();
		
}
function insertPoint()
{
	//showMsg(ajax1.response);
	if(ajax1.response ==1 )
		showMsg("Point added Successfully");
	else if(ajax1.response ==2 )
		showMsg("Point updated Successfully");
	else if(ajax1.response ==0 )
		showMsg("Point no added");
	
	myMap.closeInfoWindow();
	
	for(h=0;h<stArr.length;h++)
	{
		if(stArr[h])
		{
			document.getElementById('spanPTNameID_'+h).innerHTML =stArr[h];
			document.getElementById('spanRadID_'+h).innerHTML =radiusArr[h];	
		}
	}
	
	}
}


function dispStop(v2)
{
	var side_bar = '<ul id="menu3"><li><a href="#">Geopoint Name(s)</a><ul>';
	
	for(h=0;h<v2.length;h++)
	{
		side_bar += "<li><a href='#' class=green_link>"+v2[h]+"</a></li>";
	}
	document.getElementById('side_bar').innerHTML= side_bar;
}
function dispStop1(v3)
{
	v4=v3.toString();
	v4=v4.split("$");
	v4.splice((v4.length-1),1);
	stArr=v4;
	dispStop(stArr);
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
	GEvent.addListener (this._centerHandle, "click", function() {checkThis(myObject._control.getIndex(myObject),point);});
    GEvent.addListener (this._dragHandle, "dragend", function() {myObject.updateCircle(1);});
    //GEvent.addListener (this._dragHandle, "drag", function() {myObject.updateCircle(1);});
	GEvent.addListener (this._dragHandle, "dragend", function() {checkThis(myObject._control.getIndex(myObject),point);});
    GEvent.addListener(this._centerHandle, "dragend", function() {myObject.updateCircle(2);});
   // GEvent.addListener(this._centerHandle, "drag", function() {myObject.updateCircle(2);});
}
function checkThis(type,pt) 
{
	var ptsnew = pt.y + ',' + pt.x;
	pts[type] = ptsnew;
	if((stArr[type]))
	{
		myHtml="<b>Name It:</b><br><br><input type='text' name='txtPoint"+(type)+"' id='txtPoint"+(type)+"' value='"+(stArr[type])+"'/>";
	}
	else
	{
		myHtml="<b>Name It:</b><br><br><input type='text' name='txtPoint"+(type)+"' id='txtPoint"+(type)+"' value='Point"+(type+1)+"'/>";
	}
	myHtml+=" <a href='#' class='ui-ui-button' onclick='setStopPoint("+(type+1)+");'> Update</a>";				
	myMap.openInfoWindow(pt, myHtml);
}
function showMsg(msg)
{
	document.getElementById('messages').innerHTML = "Status :: "+msg;
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

GeoQuery.prototype.render = function() {
  if (this._type == this.CIRCLE) {
    this._points = [];
    var distance = this._radius/1000;
    for (i = 0; i < 72; i++) {
      this._points.push(destination(this._centerHandlePosition, i * 360/72, distance) );
	  //document.write(this._points[i]+" "+distance);
    }
    this._points.push(destination(this._centerHandlePosition, 0, distance) );
		  //document.write(this._points[i]);
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
  return this.getDistHtml();
}

GeoQuery.prototype.getDistHtml = function() {
  result = "<img src='images/close-icon.gif' width=15 height=15  title='delete' onclick='myQueryControl.remove(" + this._control.getIndex(this) + ")' /> ";
  rd = this._control.getIndex(this);
  if (metric) {
    if (this._radius < 1000) {
      result += " <span id='spanRadID_"+this._control.getIndex(this)+"' onclick='checkThis(" + this._control.getIndex(this) + ",new GLatLng(" + pts[this._control.getIndex(this)] + "))'>"+this._radius.toFixed(1)+"<\/span>";
	  radiusArr[rd] = (this._radius.toFixed(1));
    } else {
      result +=" <span id='spanRadID_"+this._control.getIndex(this)+"' onclick='checkThis(" + this._control.getIndex(this) + ",new GLatLng(" + pts[this._control.getIndex(this)] + "))'>"+(this._radius / 1000).toFixed(1)+"<\/span>";
	  radiusArr[rd] = ((this._radius / 1000).toFixed(1));
    }
  } else {
    var radius = this._radius * 3.2808399;
      result +=" <font color='"+ this._color + "'><span id='spanRadID_"+this._control.getIndex(this)+"' onclick='checkThis(" + this._control.getIndex(this) + ",new GLatLng(" + pts[this._control.getIndex(this)] + "))'>"+(radius / 5280).toFixed(2)+"<\/span>"; 
	  radiusArr[rd] = ((radius / 5280).toFixed(2));
	  //if(result>=0.35);
  }
	if(stArr[this._control.getIndex(this)])
	ptNam2=stArr[this._control.getIndex(this)];
	else ptNam2="Point"+(this._control.getIndex(this)+1);

	result+=" <span onclick='checkThis(" + this._control.getIndex(this) + ",new GLatLng(" + pts[this._control.getIndex(this)] + "))' id='spanPTNameID_"+this._control.getIndex(this)+"'>"+ptNam2+"</span>";
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
  this._mainDiv = document.getElementById("menu3");
  //this._mainDiv.id = "GQueryControl";
  var titleLi = document.createElement("li");
  var titleLiAnchor = document.createElement("a");  
  //titleDiv.id = "GQueryControlTitle";
  //titleDiv.className="button";
  titleLiAnchor.appendChild(document.createTextNode("Geopoints"));
  titleLi.appendChild(titleLiAnchor);
  this._mainDiv.appendChild(titleLi);
  
  var secondLi = document.createElement("li");
  
  this._queriesDiv = document.createElement("ul");
  //this._queriesDiv.id = "queriesDiv";
  secondLi.appendChild(this._queriesDiv);
  //this._queriesDiv.className="button";
  this._mainDiv.appendChild(secondLi);
  //map.getContainer().appendChild(this._mainDiv);
 // document.getElementById('status').innerHTML = this._mainDiv.innerHTML;
  this._geoQueries = new Array();
  return this._mainDiv;
}

QueryControl.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(50, 10));
}

QueryControl.prototype.addGeoQuery = function(geoQuery) {
  this._geoQueries.push(geoQuery);
  geoQuery._control = this;
  newDiv = document.createElement("li");
  newDiv.className = "green_link";
  newDiv.innerHTML = geoQuery.getHTML();
  this._queriesDiv.appendChild(newDiv);
  //document.getElementById('new_slide').innerHTML = "<font color='"+ this._color + "'>"+this._queriesDiv.innerHTML+"</font>";
 
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
function pointRemove(arrayName,arrayElement)
{
	for(var i=0; i<arrayName.length;i++ )
	{ 
		if(i == arrayElement)
		{
			ajax1.requestFile = '../../user/ajax_server.php?deletePoint=y&param='+arrayName[i];
			//document.write(ajax1.requestFile);
			//alert(ajax1.requestFile);
			ajax1.onCompletion = function(){deletPt()};
			ajax1.runAJAX();
			arrayName.splice(i,1); 
		}
	} 

}

function deletPt()
{
  if(ajax1.response == 4 )
  	showMsg("<font color='green'>Point deleted Successfully</font>");
  else
	showMsg("<font color='red'>Point delet failed</font>");	
}
QueryControl.prototype.remove = function(index) 
{
	//alert(stArr+" "+index+" "+this._geoQueries);
	if(stArr[index])
	ptNam1=stArr[index];
	else ptNam1="Point"+(index+1);
	
	var t=confirm("Are you sure to delete this point: "+ptNam1);
	if(t)
	{
		pointRemove(pts,index);
		this._geoQueries[index].remove();
		this._queriesDiv.removeChild(this._queriesDiv.childNodes[index]);		
		delete this._geoQueries[index];
		pts.splice(index,1);
		stArr.splice(index,1);
		dispStop(stArr);
		this._geoQueries.splice(index,1); 	
		c--;		
		this.render();
	}
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
		showMsg('Make proper Geocode');
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
				//showMsg(final2[1]);
				fin3=final2[1].split(' ');
				//showMsg(fin3[0]);
				//showMsg(final2[2]+" "+pts[l]);
				if(l==0)
					fin=fin3[0]+","+pts[l];
				else
					fin+=fin3[0]+","+pts[l];
	
				fin+="@";
				}
			//showMsg(fin);
			opener.document.getElementById('txtGeoParam').value=fin;		//latlng;
			opener.showPoints(fin,stArr);
			window.close();
	}else if(browser=='Netscape')
	{
			final1=document.getElementById('queriesDiv').innerHTML.split("</font>");
		for(l=0;l<(final1.length-1);l++)
		{
			final2=final1[l].split('">');
				//showMsg(pts);
			fin3=final2[2].split(' ');
			//showMsg(final2[2]+" "+pts[l]);
			//showMsg(fin3[0]);
			if(l==0)
				fin=fin3[0]+","+pts[l];
			else
				fin+=fin3[0]+","+pts[l];

			fin+="@";
		}
			//showMsg(fin);
			opener.document.getElementById('txtGeoParam').value=fin;		//latlng;
			opener.showPoints(fin,stArr);
			//window.close();
	
	}
	}
}
function clearMap() {

 // Clear current map and reset arrays
 myMap.clearOverlays();
 //showMsg(pts.length);
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

