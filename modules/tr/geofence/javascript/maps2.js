// Please leave the link below with the source code, thank you.
// http://www.websmithing.com/portal/Programming/tabid/55/articleType/ArticleView/articleId/6/Google-Map-GPS-Cell-Phone-Tracker-Version-2.aspx
var interval=5;
var data1;
var responseCode1;
function loadRoutes(data, responseCode) {
    if (data.length == 0) {
        showMessage('There are no routes available to view.');
        map.innerHTML = '';
    }
    else {
        // get list of routes
        var xml = GXml.parse(data);

        var routes = xml.getElementsByTagName("route");

        // create the first option of the dropdown box
		var option = document.createElement('option');
		option.setAttribute('value', '0');
		option.innerHTML = 'Select Route...';
		routeSelect.appendChild(option);

        // iterate through the routes and load them into the dropdwon box.
        for (i = 0; i < routes.length; i++) {
			var option = document.createElement('option');
			option.setAttribute('value', '?sessionID=' + routes[i].getAttribute("sessionID")
			                    + '&phoneNumber=' + routes[i].getAttribute("phoneNumber"));
//			option.innerHTML = routes[i].getAttribute("phoneNumber") + "  " + routes[i].getAttribute("times");
			option.innerHTML = routes[i].getAttribute("phoneNumber") ;

			routeSelect.appendChild(option);
        }

        // need to reset this for firefox
        routeSelect.selectedIndex = 0;

        hideWait();
        showMessage('Please select a route below.');
    }

}

// this will get the map and route, the route is selected from the dropdown box
function getRouteForMap() {
    if (hasMap()) {
        showWait('Getting map...');
	    var url = 'getgpslocations2.php' + routeSelect.options[routeSelect.selectedIndex].value;
		//document.write(url);
	    GDownloadUrl(url, loadGPSLocations);
	}
	else {
	    alert("Please select a route before trying to refresh map.");
	}
}

// check to see if we have a map loaded, don't want to autorefresh or delete without it
function hasMap() {
    if (routeSelect.selectedIndex == 0) { // means no map
        return false;
    }
    else {
        return true;
    }
}

function changeInterval(val) {
    if (hasMap()) {
 	showWait('Getting map...');
	interval=parseInt(val);
	//alert(interval);
	   loadGPSLocations(data1, responseCode1)
	   }
	else {
	    alert("Please select a route before selecting zoom level.");
	    //zoomLevelSelect.selectedIndex = zoomLevel - 1;
	}
}

function loadGPSLocations(data, responseCode) {
	data1 = data;
	responseCode1 = responseCode;
	var totalStop=0;
	var t1=0.0;
	var t2=0.0;
	var c1=0;
	var d1;
	var dis=0;
	
	var pts = [];
	
    if (data.length == 0) {
        showMessage('There is no tracking data to view.');
        map.innerHTML = '';
    }
    else {
        if (GBrowserIsCompatible()) {

        	// create list of GPS data locations from our XML
            var xml = GXml.parse(data);

            // markers that we will display on Google map
            var markers = xml.getElementsByTagName("locations");

            // get rid of the wait gif
            hideWait();

            // create new map and add zoom control and type of map control
	        var map = new GMap2(document.getElementById("map"));
	        map.addControl(new GSmallMapControl());
	        map.addControl(new GMapTypeControl());

            var length = markers.length;
			
			document.getElementById("distance").innerHTML=callDistance(markers)+" Km";
			
	        // center map on last marker so we can see progress during refreshes
	        map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),
	                                  parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);
			//map.setMapType(G_HYBRID_MAP); 
     		// interate through all our GPS data, create markers and add them to map
			 callFunction(0,map,markers,length);
			 document.getElementById("vehiId").innerHTML=markers[0].getAttribute("phoneNumber");
				 pts[0] = new GLatLng(parseFloat(markers[0].getAttribute("latitude")),
											   parseFloat(markers[0].getAttribute("longitude")));
						// interate through all our GPS data, create markers and add them to map
				for (var i = 1; i < (length-1); i+=interval) 
				{
					if((t1!=parseFloat(markers[i].getAttribute("latitude"))) && (t2!=parseFloat(markers[i].getAttribute("longitude"))))
					{
							callFunction(i,map,markers,length);
							pts[i] = new GLatLng(parseFloat(markers[i].getAttribute("latitude")),
											   parseFloat(markers[i].getAttribute("longitude")));
					}
						c1++;
				}
							j = length - 1;
							callFunction(j,map,markers,length);
							pts[j] = new GLatLng(parseFloat(markers[j].getAttribute("latitude")),
											   parseFloat(markers[j].getAttribute("longitude")));
									var sts=markers[markers.length-1].getAttribute("speed");
			//alert(isNaN(markers[i].getAttribute("speed")));
			if(Math.round(sts)>0)
			{
				document.getElementById("sts").innerHTML="Running";
			}else document.getElementById("sts").innerHTML="Stopped";
			if(totalStop>0)
			{
				//document.getElementById("sts").innerHTML+="<br>"+totalStop+"times";
			}

        }
        showMessage(routeSelect.options[routeSelect.selectedIndex].innerHTML);
    }
}
function callDistance(markers)
{
	var length = markers.length;
	var dis=0;
	var t3=0.0;
	var t4=0.0;

	for (var p = 0; p < (length-1); p++) 
	{
		if((t3!=parseFloat(markers[p].getAttribute("latitude"))) && (t4!=parseFloat(markers[p].getAttribute("longitude"))))
		{
			lat1=parseCoordinate(markers[p].getAttribute("latitude"));
			lat2=parseCoordinate(markers[p+1].getAttribute("latitude"));
			long1=parseCoordinate(markers[p].getAttribute("longitude"));
			long2=parseCoordinate(markers[p+1].getAttribute("longitude"));
			
			if(p==0)
				dis=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
			else
				dis+=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
		}
	}
	return Math.round(dis/10000);
}

function callFunction(p,map,markers,length)
{
    var pts = [];

	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),
							parseFloat(markers[p].getAttribute("longitude")));
	
	d1=dateTimeConvert(markers[p].getAttribute("gpsTime"));	
	//d1=eval(markers[i].getAttribute("gpsTime")+"0000-00-00 05:30:00");
	var marker = createMarker(p, length, point,
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("locationMethod"),
				 d1,
				 markers[p].getAttribute("phoneNumber"),
				 markers[p].getAttribute("sessionID"),
				 markers[p].getAttribute("accuracy"),
				 markers[p].getAttribute("isLocationValid"),
				 markers[p].getAttribute("extraInfo"),
				 parseFloat(markers[p].getAttribute("latitude")),
				 parseFloat(markers[p].getAttribute("longitude")));

	// add markers to map
	map.addOverlay(marker);
}
function dateTimeConvert(str)
{
	mer="";
	d1=str.split(" ");
	d2=d1[1].split(":");
	d4=(parseFloat(d2[1])+30)%60;
	if((d4%60)>0)
	{
		if(parseFloat(d2[0])+(Math.floor((parseFloat(d2[1])+30)/60)+5)>11)
		{
			d5=(parseFloat(d2[0])+(Math.floor((parseFloat(d2[1])+30)/60)+5))-12;
			mer="PM";
		}
		else
		{
			d5=(parseFloat(d2[0])+(Math.floor((parseFloat(d2[1])+30)/60)+5));
			mer="AM";
		}
		if(Math.floor(d4/10)==0)
		{
			d3=d5+":0"+d4;
		}
		else 
		{
			d3=d5+":"+d4;
		}
	}
	
	return (d1[0]+" "+d3+":"+mer);
}
function createMarker(i, length, point, speed, direction, locationMethod, gpsTime,
                      phoneNumber, sessionID, accuracy, isLocationValid, extraInfo,latitude,longitude) {
    var icon = new GIcon();
    // make the most current marker red
    if (i == length - 1) {
       icon.image = "images/red_small.png";
    }
    else {
        if(i == 0)
			icon.image = "images/green_small.png"; 
		else
			icon.image = "images/blue_small.png";
    }

    icon.shadow = "images/coolshadow_small.png";
    icon.iconSize = new GSize(12, 20);
    icon.shadowSize = new GSize(22, 20);
    icon.iconAnchor = new GPoint(6, 20);
    icon.infoWindowAnchor = new GPoint(5, 1);

    var marker = new GMarker(point,icon);

	// this describes how we got our location data, either by satellite or by cell phone tower
    var lm = "";
    if (locationMethod == "8") {
        lm = "Cell Tower";
    } else if (locationMethod == "327681") {
        lm = "Satellite";
    } else {
        lm = locationMethod;
    }

    var str = "</td></tr>";

    // when a user clicks on last marker, let them know it's final one
    if (i == length - 1) {
        str = "</td></tr><tr><td align=left>&nbsp;</td><td><b>Final location</b></td></tr>";
    }

	// this creates the pop up bubble that displays info when a user clicks on a marker
    GEvent.addListener(marker, "click", function() {
		
        marker.openInfoWindowHtml(
        "<table border=0 style=\"font-size:95%;font-family:arial,helvetica,sans-serif;\">"
        + "<tr><td align=right>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=right>Speed:</td><td>" + speed +  " Kmph</td></tr>"
//        + "<tr><td align=right>Distance:</td><td>" +Math.round(distance/1000) +  " Km</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right>Date & Time:</td><td colspan=2>" + gpsTime +  "</td></tr>"
        + "<tr><td align=right>Latitude:</td><td>" + latitude + "</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right>Longitude:</td><td>" + longitude + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Distance:</td><td>" + dis + " km</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Accuracy:</td><td>" + accuracy + " ft</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Location Valid:</td><td>" + isLocationValid + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Extra Info:</td><td>" + extraInfo + "</td><td>&nbsp;</td></tr>"

        + "</table>"
        );
    });

    return marker;
}
  /*
 * Use Haversine formula to Calculate distance (in km) between two points specified by 
 * latitude/longitude (in numeric degrees)
 *
 * example usage from form:
 *   result.value = LatLon.distHaversine(lat1.value.parseDeg(), long1.value.parseDeg(), 
 *                                       lat2.value.parseDeg(), long2.value.parseDeg());
 * where lat1, long1, lat2, long2, and result are form fields
 */
LatLon.distHaversine = function(lat1, lon1, lat2, lon2) {
  var R = 6371; // earth's mean radius in km
  var dLat = (lat2-lat1).toRad();
  var dLon = (lon2-lon1).toRad();
  lat1 = lat1.toRad(), lat2 = lat2.toRad();

  var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
          Math.cos(lat1) * Math.cos(lat2) * 
          Math.sin(dLon/2) * Math.sin(dLon/2);
  var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  var d = R * c;
  return Math.ceil(d);
}


/*
 * ditto using Law of Cosines
 */
LatLon.distCosineLaw = function(lat1, lon1, lat2, lon2) {
  var R = 6371; // earth's mean radius in km
  var d = Math.acos(Math.sin(lat1.toRad())*Math.sin(lat2.toRad()) +
                    Math.cos(lat1.toRad())*Math.cos(lat2.toRad())*Math.cos((lon2-lon1).toRad())) * R;
  return d;
}


/*
 * calculate (initial) bearing between two points
 *
 * from: Ed Williams' Aviation Formulary, http://williams.best.vwh.net/avform.htm#Crs
 */
LatLon.bearing = function(lat1, lon1, lat2, lon2) {
  lat1 = lat1.toRad(); lat2 = lat2.toRad();
  var dLon = (lon2-lon1).toRad();

  var y = Math.sin(dLon) * Math.cos(lat2);
  var x = Math.cos(lat1)*Math.sin(lat2) -
          Math.sin(lat1)*Math.cos(lat2)*Math.cos(dLon);
  return Math.atan2(y, x).toBrng();
}

 
/* *111#001#
 * calculate destination point given start point, initial bearing (deg) and distance (km)
 *   see http://williams.best.vwh.net/avform.htm#LL
 */
LatLon.prototype.destPoint = function(brng, d) {
  var R = 6371; // earth's mean radius in km
  var lat1 = this.lat.toRad(), lon1 = this.lon.toRad();
  brng = brng.toRad();

  var lat2 = Math.asin( Math.sin(lat1)*Math.cos(d/R) + 
                        Math.cos(lat1)*Math.sin(d/R)*Math.cos(brng) );
  var lon2 = lon1 + Math.atan2(Math.sin(brng)*Math.sin(d/R)*Math.cos(lat1), 
                               Math.cos(d/R)-Math.sin(lat1)*Math.sin(lat2));
  lon2 = (lon2+Math.PI)%(2*Math.PI) - Math.PI;  // normalise to -180...+180

  if (isNaN(lat2) || isNaN(lon2)) return null;
  return new LatLon(lat2.toDeg(), lon2.toDeg());
}


/*
 * construct a LatLon object: arguments in numeric degrees
 *
 * note all LatLong methods expect & return numeric degrees (for lat/long & for bearings)
 */
function LatLon(lat, lon) {
  this.lat = lat;
  this.lon = lon;
}


/*
 * represent point {lat, lon} in standard representation
 */
LatLon.prototype.toString = function() {
  return this.lat.toLat() + ', ' + this.lon.toLon();
}

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */

// extend String object with method for parsing degrees or lat/long values to numeric degrees
//
// this is very flexible on formats, allowing signed decimal degrees, or deg-min-sec suffixed by 
// compass direction (NSEW). A variety of separators are accepted (eg 3� 37' 09"W) or fixed-width 
// format without separators (eg 0033709W). Seconds and minutes may be omitted. (Minimal validation 
// is done).

String.prototype.parseDeg = function() {
  if (!isNaN(this)) return Number(this);                 // signed decimal degrees without NSEW

  var degLL = this.replace(/^-/,'').replace(/[NSEW]/i,'');  // strip off any sign or compass dir'n
  var dms = degLL.split(/[^0-9.]+/);                     // split out separate d/m/s
  for (var i in dms) if (dms[i]=='') dms.splice(i,1);    // remove empty elements (see note below)
  switch (dms.length) {                                  // convert to decimal degrees...
    case 3:                                              // interpret 3-part result as d/m/s
      var deg = dms[0]/1 + dms[1]/60 + dms[2]/3600; break;
    case 2:                                              // interpret 2-part result as d/m
      var deg = dms[0]/1 + dms[1]/60; break;
    case 1:                                              // decimal or non-separated dddmmss
      if (/[NS]/i.test(this)) degLL = '0' + degLL;       // - normalise N/S to 3-digit degrees
      var deg = dms[0].slice(0,3)/1 + dms[0].slice(3,5)/60 + dms[0].slice(5)/3600; break;
    default: return NaN;
  }
  if (/^-/.test(this) || /[WS]/i.test(this)) deg = -deg; // take '-', west and south as -ve
  return deg;
}
// note: whitespace at start/end will split() into empty elements (except in IE)


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */

// extend Number object with methods for converting degrees/radians

Number.prototype.toRad = function() {  // convert degrees to radians
  return this * Math.PI / 180;
}

Number.prototype.toDeg = function() {  // convert radians to degrees (signed)
  return this * 180 / Math.PI;
}

Number.prototype.toBrng = function() {  // convert radians to degrees (as bearing: 0...360)
  return (this.toDeg()+360) % 360;
}


/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */

// extend Number object with methods for presenting bearings & lat/longs

Number.prototype.toDMS = function() {  // convert numeric degrees to deg/min/sec
  var d = Math.abs(this);  // (unsigned result ready for appending compass dir'n)
  d += 1/7200;  // add � second for rounding
  var deg = Math.floor(d);
  var min = Math.floor((d-deg)*60);
  var sec = Math.floor((d-deg-min/60)*3600);
  // add leading zeros if required
  if (deg<100) deg = '0' + deg; if (deg<10) deg = '0' + deg;
  if (min<10) min = '0' + min;
  if (sec<10) sec = '0' + sec;
  return deg + '\u00B0' + min + '\u2032' + sec + '\u2033';
}

Number.prototype.toLat = function() {  // convert numeric degrees to deg/min/sec latitude
  return this.toDMS().slice(1) + (this<0 ? 'S' : 'N');  // knock off initial '0' for lat!
}

Number.prototype.toLon = function() {  // convert numeric degrees to deg/min/sec longitude
  return this.toDMS() + (this>0 ? 'E' : 'W');
}

Number.prototype.toPrecision = function(fig) {  // override toPrecision method with one which displays 
  if (this == 0) return 0;                      // trailing zeros in place of exponential notation
  var scale = Math.ceil(Math.log(this)*Math.LOG10E);
  var mult = Math.pow(10, fig-scale);
  return Math.round(this*mult)/mult;
}

/* - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -  */
// this chooses the proper image for our litte compass in the popup window
function getCompassImage(azimuth) {
    if ((azimuth >= 337 && azimuth <= 360) || (azimuth >= 0 && azimuth < 23))
            return "compassN";
    if (azimuth >= 23 && azimuth < 68)
            return "compassNE";
    if (azimuth >= 68 && azimuth < 113)
            return "compassE";
    if (azimuth >= 113 && azimuth < 158)
            return "compassSE";
    if (azimuth >= 158 && azimuth < 203)
            return "compassS";
    if (azimuth >= 203 && azimuth < 248)
            return "compassSW";
    if (azimuth >= 248 && azimuth < 293)
            return "compassW";
    if (azimuth >= 293 && azimuth < 337)
            return "compassNW";

    return "";
}

function deleteRoute() {
    if (hasMap()) {
	    var answer = confirm("This will permanently delete this route\n from the database. Do you want to delete?")
	    if (answer){
            showWait('Deleting route...');
            var url = 'deleteroute.php' + routeSelect.options[routeSelect.selectedIndex].value;
            GDownloadUrl(url, deleteRouteResponse);
	    }
	    else {
		    return false;
	    }
	}
	else {
	    alert("Please select a route before trying to delete.");
	}
}

function deleteRouteResponse(data, responseCode) {
    map.innerHTML = '';
    routeSelect.length = 0;
    GDownloadUrl('getroutes.php', loadRoutes);
}

// auto refresh the map. there are 3 transitions (shown below). transitions happen when a user
// selects an option in the auto refresh dropdown box. an interval is an amount of time in between
// refreshes of the map. for instance, auto refresh once a minute. in the method below, the 3 numbers
// in the code show where the 3 transitions are handled. setInterval turns on a timer that calls
// the getRouteForMap() method every so many seconds based on the value of newInterval.
// clearInterval turns off the timer. if newInterval is 5, then the value passed to setInterval is
// 5000 milliseconds or 5 seconds.
function autoRefresh() {
    /*
        1) going from off to any interval
        2) going from any interval to off
        3) going from one interval to another
    */

    if (hasMap()) {
        newInterval = refreshSelect.options[refreshSelect.selectedIndex].value;
        if (currentInterval > 0) { // currently running at an interval

            if (newInterval > 0) { // moving to another interval (3)
                clearInterval(intervalID);
                intervalID = setInterval("getRouteForMap();", newInterval * 1000);
                currentInterval = newInterval;
            }
            else { // we are turning off (2)
                clearInterval(intervalID);
                newInterval = 0;
                currentInterval = 0;
            }
        }
        else { // off and going to an interval (1)
            intervalID = setInterval("getRouteForMap();", newInterval * 1000);
            currentInterval = newInterval;
        }

        // show what auto refresh action was taken and after 5 seconds, display the route name again
        showMessage(refreshSelect.options[refreshSelect.selectedIndex].innerHTML);
        setTimeout('showRouteName();', 5000);
  	}
	else {
	    alert("Please select a route before trying to refresh map.");
	    refreshSelect.selectedIndex = 0;
	}
}

function changeZoomLevel() {
    if (hasMap()) {
        zoomLevel = zoomLevelSelect.selectedIndex + 1;

        getRouteForMap();

        // show what zoom level action was taken and after 5 seconds, display the route name again
        showMessage(zoomLevelSelect.options[zoomLevelSelect.selectedIndex].innerHTML);
        setTimeout('showRouteName();', 5000);
  	}
	else {
	    alert("Please select a route before selecting zoom level.");
	    zoomLevelSelect.selectedIndex = zoomLevel - 1;
	}
}

function showMessage(message) {
     messages.innerHTML = 'GPS Tracker: <b>' + message + '</b>';
}

function showRouteName() {
    showMessage(routeSelect.options[routeSelect.selectedIndex].innerHTML);
}

function showWait(theMessage) {
    map.innerHTML = '<img src="images/ajax-loader.gif"' +
                    'style="position:absolute;top:225px;left:325px;">';
    showMessage(theMessage);
}

function hideWait() {
    map.innerHTML = '';
    messages.innerHTML = 'Online - GPS Tracker';
}

