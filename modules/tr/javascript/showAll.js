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
		//option.setAttribute('value', '0');
		//option.innerHTML = 'Select Route...';
		//routeSelect.appendChild(option);

        // iterate through the routes and load them into the dropdwon box.
        for (i = 0; i < routes.length; i++) {
			var option = document.createElement('option');
			option.setAttribute('value', '&sessionID=' + routes[i].getAttribute("sessionID")
			                    + '&phoneNumber=' + routes[i].getAttribute("phoneNumber"));
			if(routes[i].getAttribute("phoneNumber")==routes[i].getAttribute("select"))
			{
				option.setAttribute('selected', 'selected');
				var url = 'getalllocations.php?date_offline='+routes[i].getAttribute("date") + '&sessionID=' + routes[i].getAttribute("sessionID") + '&phoneNumber=' + routes[i].getAttribute("phoneNumber");
			    GDownloadUrl(url, loadGPSLocations);
			}
//			option.innerHTML = routes[i].getAttribute("phoneNumber") + "  " + routes[i].getAttribute("times");
			option.innerHTML = routes[i].getAttribute("phoneNumber") ;
			
			//alert(option);

			routeSelect.appendChild(option);
        }

        // need to reset this for firefox
        //routeSelect.selectedIndex = 0;

        hideWait();
        showMessage('Please select a route below.');
    }

}

// this will get the map and route, the route is selected from the dropdown box - for offline details
function getRouteForMap_offline(date_offline) {
    if (date_offline) {
        showWait('Getting map...');
	    var url = 'getalllocations.php?date_offline='+date_offline + '&sessionID=all';
		//document.write(url);
	    GDownloadUrl(url, loadGPSLocations);
	}
	else {
	    alert("Please select a route before trying to refresh map.");
	}
}


function changeInterval_offline(val) {
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
var map;
// check to see if we have a map loaded, don't want to autorefresh or delete without it
function hasMap() {
//    if (routeSelect.selectedIndex == 0) { // means no map
//        return false;
//    }
//    else {
	 if (true) {
	        map = new GMap2(document.getElementById("map"));
	        map.addControl(new GSmallMapControl());
	        map.addControl(new GMenuMapTypeControl());
			map.enableScrollWheelZoom();
		    map.enableContinuousZoom();
			map.addControl(new GScaleControl());
			//map.addControl(new GOverviewMapControl());
        return true;
	 }
	 else
	 {
		 return false;
	 }
    //}
}
function changeType(id)
{
		//alert(id);
		left_pan('side_pan', 'control_pan');
		mapType=id;
		if(mapType==1)
		map.setMapType(G_NORMAL_MAP);
		else if(mapType==2)
		map.setMapType(G_SATELLITE_MAP); 
		else if(mapType==3)
		map.setMapType(G_HYBRID_MAP);
}
function showPoints(mp)
{
mapPoints=mp;
left_pan('side_pan', 'control_pan');
loadGPSLocations(data1, responseCode1);
//alert(data1);
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
	
	
    if (data.length == 0) {
        showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = '';
    }
    else {
        if (hasMap()) {


		var xmlRoot=GXml.parse(data);
		
		var root = xmlRoot.getElementsByTagName("gps");
		
        	// create list of GPS data locations from our XML
            var xml = GXml.parse(data);
			

            // markers that we will display on Google map
            var markers = xml.getElementsByTagName("locations");

            // get rid of the wait gif
            hideWait();
			
            // create new map and add zoom control and type of map control
//	        var map = new GMap2(document.getElementById("map"));
//	        map.addControl(new GSmallMapControl());
//	        map.addControl(new GMapTypeControl());
            var length = markers.length;
			if(length!=0)
			{
	        // center map on last marker so we can see progress during refreshes
	        map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);
			if(mapType==1)
			map.setMapType(G_NORMAL_MAP);
			else if(mapType==2)
			map.setMapType(G_SATELLITE_MAP); 
			else if(mapType==3)
			map.setMapType(G_HYBRID_MAP); 

     		// interate through all our GPS data, create markers and add them to map
			
			//callFunction(0,map,markers,length);
			
		// pts[0] = new GLatLng(parseFloat(markers[0].getAttribute("latitude")),
									   //parseFloat(markers[0].getAttribute("longitude")));	
		for (var i = 0; i < length; i++) 
		{
			callFunction(i,map,markers,length);
		}
		
		//j = length - 1;
		
		//callFunction(j,map,markers,length);
		

		if(for_Poly==1)
		{
			var poly = new BDCCArrowedPolyline(pts,"#FF0000",4,0.3,null,1,7,"#0000FF",2,0.5);
			map.addOverlay(poly);
			//var poly = new GPolyline(pts,"#A2D84C",5,1,"#A2D84C",3.5,{clickable:false});
//			map.addOverlay(poly);
		}
		}

        }
        //showMessage(routeSelect);
    }
}
function callDistance(map,markers)
{
	var length1 = markers.length;
	var dis=0;
	var t3=0.0;
	var t4=0.0;
	var tmp=0;
	var t=0;

	for (var p1 = 0; p1 < (length1-1); p1++) 
	{

			if(p1==0)
			{
				lat1=parseCoordinate(markers[0].getAttribute("latitude"));
				lat2=parseCoordinate(markers[p1+1].getAttribute("latitude"));
				long1=parseCoordinate(markers[0].getAttribute("longitude"));
				long2=parseCoordinate(markers[p1+1].getAttribute("longitude"));
				dis=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
				pts[t] = new GLatLng(parseFloat(markers[0].getAttribute("latitude")),parseFloat(markers[0].getAttribute("longitude")));
				t++;

			}
			else if(p1 > 0 && p1 < (length1-2))
			{
				lat1=parseCoordinate(markers[p1+1].getAttribute("latitude"));
				lat2=parseCoordinate(markers[p1+2].getAttribute("latitude"));
				long1=parseCoordinate(markers[p1+1].getAttribute("longitude"));
				long2=parseCoordinate(markers[p1+2].getAttribute("longitude"));
				dis+=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
			}
			if(tmp!=(Math.round(dis/1000)))
			{
				if(tmp>=1)
				{
					callFunction(p1,map,markers,length1);
					pts[t] = new GLatLng(parseFloat(markers[p1].getAttribute("latitude")),parseFloat(markers[p1].getAttribute("longitude")));
					t++;
				}
				tmp=Math.round(dis/1000);
				
			}
			
	}
	pts[t] = new GLatLng(parseFloat(markers[p1].getAttribute("latitude")),parseFloat(markers[p1].getAttribute("longitude")));

	//alert(c2+","+length);
	return Math.round(dis/1000);
}
function callFunction(p,map,markers,length)
{
	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),parseFloat(markers[p].getAttribute("longitude")));
	
	d1=(markers[p].getAttribute("gpsTime"));	
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
	dat=d1[0].split("-");
	d2=d1[1].split(":");
	d4=parseInt(d2[1])+30;
	var d3;
	var tem;
	if(d4>=60)
	{
		d6=d4-60;
		tem=parseFloat(d2[0])+6;
	}
	else 
	{
		d6=d4;
		tem=parseFloat(d2[0])+5;
	}
	
		if(tem>12 && tem<24)
		{
			d5=parseFloat(tem)-12;
			//alert(d5);
			ct=parseFloat(dat[2]);
			mer="PM";
		}
		else if(tem==12)
		{
			d5=parseFloat(tem);
			ct=parseFloat(dat[2]);
			mer="PM";
		}
		else if(tem>24)
		{
				//alert(tem);
			d5=parseFloat(tem)-24;
			ct=parseFloat(dat[2])+1;
			mer="AM";
		}
		else if(tem==24)
		{
			d5=parseFloat(tem)-12;
			ct=parseFloat(dat[2])+1;
			mer="AM";
		}
		else
		{
			d5=parseFloat(tem);
			ct=parseFloat(dat[2]);
			mer="AM";
		}
		if((Math.floor(d6/10))==0)
		{
			
			d3=d5+":0"+d6;
			
		}
		else 
		{
			d3=d5+":"+d6;
		}
	//}
	
	return (ct+"-"+dat[1]+"-"+dat[0]+" "+d3+" "+mer);
}


function createMarker(i, length, point, speed, direction, locationMethod, gpsTime,
                      phoneNumber, sessionID, accuracy, isLocationValid, extraInfo,latitude,longitude) {
    var icon = new GIcon();
	icon.image = "images/RedCar.png";
	//icon.shadow = "images/coolshadow_small.png";

    //icon.shadow = "images/coolshadow_small.png";
    icon.iconSize = new GSize(35, 20);
    icon.shadowSize = new GSize(22, 20);
    icon.iconAnchor = new GPoint(6, 20);
    icon.infoWindowAnchor = new GPoint(5, 1);

    var marker = new GMarker(point,icon);
	var latlng;
	// this describes how we got our location data, either by satellite or by cell phone tower
    var lm = "";
    if (locationMethod == "8") {
        lm = "Cell Tower";
    } else if (locationMethod == "327681") {
        lm = "Satellite";
    } else {
        lm = locationMethod;
    }
		var addrs;

    var str = "</td></tr>";

        str = "</td></tr><tr><td align=left>&nbsp;</td><td><b>Vehicle "+(i+1)+" : "+phoneNumber+"</b></td></tr>";
		
    GEvent.addListener(marker, "click", function() {
	
	geocoder.getLocations((latitude+","+longitude), function(response)																																						   {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addrs="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
			//addr=addr.split(",");
			showMessage(addr);
			//alert(place.address);
			//addr=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
	  }
																																						 	showWait(addr);
	var ing=null;	
	if(extraInfo!='')
	{
		if(extraInfo==0)
		ing="off";
		else ing="On";
	}else ing="-";
												 
        marker.openInfoWindowHtml(
        "<table border=0 style=\"font-size:75%;font-family:arial,helvetica,sans-serif;\">"
        + "<tr><td align=right>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=right><b>Speed:</b></td><td>" + speed +  " Kmph&nbsp;&nbsp;<b>Ignition:&nbsp;</b>"+ing+"</td></tr>"
//        + "<tr><td align=right>Distance:</td><td>" +Math.round(distance/1000) +  " Km</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right><b>Date & Time:</b></td><td colspan=2>" + gpsTime +  "</td></tr>"
        + "<tr><td align=right><b>Latitude:</b></td><td>" + latitude + "&nbsp;&nbsp;<b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Longitude:</td><td>" + longitude + "</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right><b>Address:</b></td><td>" + addr + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Accuracy:</td><td>" + accuracy + " ft</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Location Valid:</td><td>" + isLocationValid + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Extra Info:</td><td>" + extraInfo + "</td><td>&nbsp;</td></tr>"

        + "</table>"
        );
		});
    });

    return marker;
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
function autoRefresh(date,vehicle_no) {
    /*
        1) going from off to any interval
        2) going from any interval to off
        3) going from one interval to another
    */

    if (true) {
        newInterval = refreshSelect.options[refreshSelect.selectedIndex].value;
        if (currentInterval > 0) { // currently running at an interval

            if (newInterval > 0) { // moving to another interval (3)
			
                clearInterval(intervalID);
                intervalID = setInterval("getRouteForMap_offline('"+date+"','"+vehicle_no+"');", newInterval * 1000);
                currentInterval = newInterval;
            }
            else { // we are turning off (2)
                clearInterval(intervalID);
                newInterval = 0;
                currentInterval = 0;
            }
        }
        else { // off and going to an interval (1)
		
            intervalID = setInterval("getRouteForMap_offline('"+date+"','"+vehicle_no+"');", newInterval * 1000);
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


function changeZoomLevel_offline(date_offline,vehicle_no) {
    if (hasMap()) {
        zoomLevel = zoomLevelSelect.selectedIndex + 1;
        getRouteForMap_offline(date_offline,vehicle_no);

        // show what zoom level action was taken and after 5 seconds, display the route name again
        showMessage(zoomLevelSelect.options[zoomLevelSelect.selectedIndex].innerHTML);
        setTimeout('showRouteName();', 5000);
  	}
	else {
	    alert("Please select a route before selecting zoom level.");
	    zoomLevelSelect.selectedIndex = zoomLevel - 1;
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
     messages.innerHTML = 'Status: <b>' + message + '</b>';
}

function showRouteName() {
    //showMessage(routeSelect);
}

function showWait(theMessage) {
    map.innerHTML = '<img src="images/ajax-loader.gif"' +
                    'style="position:absolute;top:225px;left:325px;">';
    showMessage(theMessage);
}

function hideWait() {
    map.innerHTML = '';
    messages.innerHTML = 'Status:';
}


