var data1;
var responseCode1;
var ptno=/^[0-9]*$/;
var myMarker;
var t1=null;
var vehiIcon;
var cht;
var zoomLevel1;
var mapType;
var rtc=0;

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
				var url = 'getgpslocations_offline.php?date_offline='+routes[i].getAttribute("date") + '&sessionID=' + routes[i].getAttribute("sessionID") + '&phoneNumber=' + routes[i].getAttribute("phoneNumber");
				//document.write(url);
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
        //showMessage('Please select a route below.');
    }

}
function loadAllGPSLocations(data, responseCode) {
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
    else 
	{
        if (hasMap()) {
		var xmlRoot=GXml.parse(data);
		var root = xmlRoot.getElementsByTagName("gps");
        var xml = GXml.parse(data);
        var markers = xml.getElementsByTagName("locations");
        hideWait();
		
		var length = markers.length;
		if(length!=0)
		{
		// center map on last marker so we can see progress during refreshes
			map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);	//zoomLevel
			if(mapType==1)
			map.setMapType(G_NORMAL_MAP);
			else if(mapType==2)
			map.setMapType(G_SATELLITE_MAP); 
			else if(mapType==3)
			map.setMapType(G_HYBRID_MAP); 
				for (var i = 0; i < length; i++) 
				{
					callAllFunction(i,map,markers,length);
				}
			}
		}
    }
}
function callAllFunction(p,map,markers,length)
{
	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),parseFloat(markers[p].getAttribute("longitude")));
	
	var marker = createAllMarker(p, length, point,
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("locationMethod"),
				 markers[p].getAttribute("gpsTime"),
				 markers[p].getAttribute("phoneNumber"),
				 markers[p].getAttribute("distance"),
				 markers[p].getAttribute("sessionID"),
				 markers[p].getAttribute("accuracy"),
				 markers[p].getAttribute("isLocationValid"),
				 markers[p].getAttribute("extraInfo"),
				 parseFloat(markers[p].getAttribute("latitude")),
				 parseFloat(markers[p].getAttribute("longitude")));

	// add markers to map
	map.addOverlay(marker);
}
function createAllMarker(i, length, point, speed, direction, locationMethod, gpsTime,
                      phoneNumber, distance,sessionID, accuracy, isLocationValid, extraInfo,latitude,longitude) {
    var icon = new GIcon();
	icon.image = "images/RedCar.png";

	var label = new ELabel(point, phoneNumber, "style1");
	map.addOverlay(label);

	
	//icon.shadow = "images/coolshadow_small.png";

    //icon.shadow = "images/coolshadow_small.png";
    icon.iconSize = new GSize(27, 27);
    icon.shadowSize = new GSize(27, 27);
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
        + "<tr><td align=right><b>Distance:</b></td><td>" +distance +  " Km</td><td>&nbsp;</td></tr>"
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
function chgRoute(date_offline,sessionid,vehicle_no)
{
	//alert(vehicle_no);
	if (date_offline) {
        showWait('Getting map...');
	    var url = 'getgpslocations_offline.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&phoneNumber=' + vehicle_no;
		//document.write(url);
	    GDownloadUrl(url, loadGPSLocations);
	}
	else {
	    alert("Please select a route before trying to refresh map.");
	}
}
// this will get the map and route, the route is selected from the dropdown box - for offline details
function getRouteForMap_offline(date_offline,vehicle_no) {
    if (date_offline) {
        showWait('Getting map...');
		if(routeSelect.options[routeSelect.selectedIndex].value=="&sessionID=all&phoneNumber=Show All")
		{
			zoomLevel=10;
			var url = 'getalllocations.php?date_offline='+date_offline + '&sessionID=all';
			//document.write(url);
			GDownloadUrl(url, loadAllGPSLocations);
		}
		else
		{
			zoomLevel=17;
			var url = 'getgpslocations_offline.php?date_offline='+date_offline + routeSelect.options[routeSelect.selectedIndex].value;
			//document.write(url);
			GDownloadUrl(url, loadGPSLocations);
		}
	}
	else {
	    alert("Please select a route before trying to refresh map.");
	}
}
function getAllRouteForMap_offline(date_offline,vehicle_no) {
    if (date_offline) {
        showWait('Getting map...');
		if(routeSelect.options[routeSelect.selectedIndex].value=="&sessionID=all&phoneNumber=Show All")
		{
			zoomLevel=10;
			var url = 'getalllocations.php?date_offline='+date_offline + '&sessionID=all';
			//document.write(url);
			GDownloadUrl(url, loadAllGPSLocations);
		}
		else
		{
			zoomLevel=17;
			var url = 'getgpslocations_offline.php?date_offline='+date_offline + routeSelect.options[routeSelect.selectedIndex].value;
			//document.write(url);
			GDownloadUrl(url, loadGPSLocations);
		}
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
function hasMap()
{
	if (true) //GBrowserIsCompatible()
	{
	        map = new GMap2(document.getElementById("map"));
	        map.addControl(new GSmallMapControl());
	        map.addControl(new GMenuMapTypeControl());
			map.enableScrollWheelZoom();
		    //map.enableContinuousZoom();
			map.addControl(new GScaleControl());

			
			if(mapType=="Map" || mapType=="")
			{
				//mapType=map.getCurrentMapType().getName();
				map.setMapType(G_NORMAL_MAP);
			}
			else if(mapType=="Satellite")
			{
				mapType=map.getCurrentMapType().getName();
				map.setMapType(G_SATELLITE_MAP); 
			}
			else if(mapType=="Hybrid")
			{
				mapType=map.getCurrentMapType().getName();
				map.setMapType(G_HYBRID_MAP); 
			}
			/*map.addControl(new GNavLabelControl(),
			   new GControlPosition(G_ANCHOR_TOP_LEFT, new GSize(100,7))
			); */
			//map.setMapType(map.getCurrentMapType()); 
	        //map.addControl(new GNavLabelControl());
			//map.addControl(new GOverviewMapControl());
			
        return true;
	 }
	 else
	 {
		 return false;
	 }
}
function changeType(id)
{
		//alert(id);
		//left_pan('side_pan', 'control_pan');
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
//left_pan('side_pan', 'control_pan');
loadGPSLocations(data1, responseCode1);
//alert(data1);
}
function loadGPSLocations(data, responseCode) {
	data1 = data;
	responseCode1 = responseCode;
	var totalStop=0;
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
		var geoData="";
		var geoPointName="";
		var root = xmlRoot.getElementsByTagName("gps");
		
		if(root[0].getAttribute('geoData')!='' && root[0].getAttribute('geoPointName')!='' && root[0].getAttribute('geoCodeData')!='')
		{
			 geoData=root[0].getAttribute('geoData');
 			 geoPointName=root[0].getAttribute('geoPointName');
		}
		// create list of GPS data locations from our XML
            var xml = GXml.parse(data);

            // markers that we will display on Google map
            var markers = xml.getElementsByTagName("locations");
				myMarker = xml.getElementsByTagName("locations");
            // get rid of the wait gif
//            hideWait();
			
										
		var sts=markers[markers.length-1].getAttribute("speed");
		var ignit=markers[markers.length-1].getAttribute("extraInfo");
		//document.getElementById("vehiId").innerHTML=markers[0].getAttribute("phoneNumber");
		

            var length = markers.length;
			t1=markers.length;
			if(length!=0)
			{
			if(zoomLevel1)
			{
	        map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel1);
			}
			else 
			{
			 map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);
			}
	
				
			if(mapType==1)
			map.setMapType(G_NORMAL_MAP);
			else if(mapType==2)
			map.setMapType(G_SATELLITE_MAP); 
			else if(mapType==3)
			map.setMapType(G_HYBRID_MAP); 
			
			
			var baseIcon = new GIcon();
			baseIcon.iconSize=new GSize(10,10);
			baseIcon.iconAnchor=new GPoint(8,8);
			baseIcon.infoWindowAnchor=new GPoint(10,0);
			
			var yellowIcon = (new GIcon(baseIcon, "../Maps/images/yellowSquare.png", null, ""));
			var greenIcon = (new GIcon(baseIcon, "../Maps/images/greenCircle.png", null, ""));
			var redIcon = (new GIcon(baseIcon, "../Maps/images/redCircle.png", null, ""));
			var orangeIcon = (new GIcon(baseIcon, "../Maps/images/orangeCircle.png", null, ""));
			var blueIcon = (new GIcon(baseIcon, "../Maps/images/blueCircle.png", null, ""));
			var violetIcon = (new GIcon(baseIcon, "../Maps/images/m-over-square.png", null, ""));
			var lineColor = "#0000af";
			var fillColor = "#335599";
			var lineWeight = 3;
			var lineOpacity = .8;
			var fillOpacity = .2;
		var gf2= new Array();
		var COLORS=["#0000ff", "#00ff00", "#ff0000"];
		var COLORI=0;


     		// interate through all our GPS data, create markers and add them to map
		if(geoData!='' && geoPointName!='')
		{
			//alert(geoData);
			var ptName=geoPointName.split(",");
			var ptArr=new Array(geoData);
			ptArr=geoData.split("),");
			var polyPoints=[];
			  
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
			pts3=new GLatLng(Number(pixelX),Number(pixelY));
			//alert(pts3);
			var marker4 = createGeo(pts3,violetIcon,'<b>Geofence&nbsp;-&nbsp;'+ptName[f]+'</b>');
			map.addOverlay(marker4);
			polyPoints.push(new GLatLng(Number(pixelX),Number(pixelY)));
			
		}
		polyPoints.push(polyPoints[0]);
		//alert(polyPoints);
		  polyShape = new GPolyline(polyPoints,'#0000af',2,1);  	//new BDCCArrowedPolyline(polyPoints,"#FF0000",4,0.3,null,1,7,"#0000FF",2,0.5);
		  map.addOverlay(polyShape);
		
		}
			callFunction(0,map,markers,length);
			//callDistance(map,markers);
//		document.getElementById("distance").innerHTML="&nbsp;&nbsp;<strong>"+callDistance(map,markers)+" Km</strong>";
		var t=0;
		var pts = [];
		var pits1;
		var pits2;
		var t7=0.0;
		var t8=0.0;
		var totalDistance=0;
		var stopIn=0;
		var strTime='';
		var endTime='';
		var stoptime='';
	
		if(mapPoints!=3)
		{
		for (var h = 0; h < (length-1); h++) 
		{
			pts[t] = new GLatLng(parseFloat(markers[h].getAttribute("latitude")),parseFloat(markers[h].getAttribute("longitude")));
			t++;
		}
			pts[t] = new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude")));
			var poly = new BDCCArrowedPolyline(pts,"#FF0000",2,0.60,null,1,7,"#0000FF",1,1);
			map.addOverlay(poly);
	//		var poly = new GPolyline(pts,"#A2D84C",5,1,"#A2D84C",3.5,{clickable:false});
	//					map.addOverlay(poly);
		}


		for (var i = 0; i < (length-1); i++) 
		{
			//callFunction(i,map,markers,length);
			if((t7!=parseFloat(markers[i].getAttribute("latitude"))) && (t8!=parseFloat(markers[i].getAttribute("longitude"))))
			{
			if(i>0)
			{
			pits1 =  new GLatLng(parseFloat(markers[(i-1)].getAttribute("latitude")),parseFloat(markers[(i-1)].getAttribute("longitude")));
			pits2 =  new GLatLng(parseFloat(markers[i].getAttribute("latitude")),parseFloat(markers[i].getAttribute("longitude")));
			dist = pits1.distanceFrom(pits2) / 1000;
			totalDistance += dist;
				if(c1!=Math.floor(totalDistance))
				{
					//alert(c1+"!="+Math.floor(totalDistance));
					if(c1!=Math.floor(totalDistance))
					{
						callFunction(i,map,markers,length);			
	
					}
					c1=Math.floor(totalDistance);
				}
				//pts[t] = new GLatLng(parseFloat(markers[i].getAttribute("latitude")),parseFloat(markers[i].getAttribute("longitude")));
				//t++;
			}
			
			if(markers[i].getAttribute("speed")==0 && strTime=='')
			{
				strTime=markers[i].getAttribute("gpsTime");
				//echo "<br>";
			}
			else if(markers[i].getAttribute("speed")>0 && endTime=='' && strTime!='')
			{
				endTime=markers[i].getAttribute("gpsTime");
				
				
				var hr1='';
				var mn1='';
				var icon4 = new GIcon();
				icon4.image = "../images/stop.png";
				//icon4.shadow = "images/coolshadow_small.png";
				icon4.shadowSize = new GSize(12, 20);
				icon4.iconAnchor = new GPoint(6, 10);
				icon4.infoWindowAnchor = new GPoint(5, 1);
			
				var thisPt=new GLatLng(parseFloat(markers[i].getAttribute("latitude")),parseFloat(markers[i].getAttribute("longitude")));
				dur1=Math.abs(diffTime(strTime,endTime));
				//alert(dur1);
				if(dur1 >=  300)
				{
				if(dur1 <  3600)
				{
					stoptime=Math.round(dur1/60)+" Mins";
				}
				else
				{
					hr1=Math.floor(dur1/3600);
					mn1=dur1-(3600*hr1);
					mn1=Math.floor(mn1/60);
					//alert(hr1);
					stoptime =hr1+" Hour and "+mn1+" Mssins";
					//alert(stopIn+"	fff	"+stoptime);
				}
				stopIn++;
				//var stoptime=strTime+"	"+endTime+"	"+Math.abs(diffTime(strTime,endTime));
				
				//var stoptime=strTime+"	"+endTime;
				if(hsp==0)
				{
				var marker6 =createGeo(thisPt,icon4,"<b>Parking no : </b>"+stopIn+"&nbsp;<b>Duration : </b>"+stoptime);
				map.addOverlay(marker6);
				}
				//finaTime=strTime+"#"+endTime+"#"+"diff"+"#"+$res."#".$pos1."#".$pos2;
				strTime='';
				endTime='';
				}
				//array_push($timediff, $finaTime);
				
			}
			
			}
			t3=parseFloat(markers[i].getAttribute("latitude"));
			t4=parseFloat(markers[i].getAttribute("longitude"));	
		}
		document.getElementById("distance").innerHTML="&nbsp;&nbsp;<strong>"+Math.round(totalDistance)+" Km</strong>";
		
		j = length - 1;
		
		callFunction(j,map,markers,length);
		
		/*if(ignit==0 && for_Poly==0)
		{
			document.getElementById("ignit").innerHTML="&nbsp;&nbsp;<strong>Off</strong>";
		}
		if(ignit==1 && for_Poly==0)
		{
			document.getElementById("ignit").innerHTML="&nbsp;&nbsp;<strong>On</strong>";
		}
		if(ignit=='' && for_Poly==0)
		{
			document.getElementById("ignit").style.display='none';
			document.getElementById("ingli").style.display='none';
		}*/
		document.getElementById("ctSpd").innerHTML='&nbsp;Current Speed&nbsp;:&nbsp;'+Math.round(parseFloat(markers[j].getAttribute("speed")))+' kmph';

		
		if(Math.round(sts)>0)
		{
			document.getElementById("sts").innerHTML="&nbsp;&nbsp;<strong>Running</strong>";
		}
		else document.getElementById("sts").innerHTML="&nbsp;&nbsp;<strong>Stopped</strong>";
		
		}

        }
        //showMessage(routeSelect);
    }
}
function funcHideStop()
{
	//alert(document.getElementById('chkHidStop').checked);
	if(document.getElementById('chkHidStop').checked)
	{
		hsp=1;
		document.getElementById('stopHead').innerHTML='Show Stop Points';
		loadGPSLocations(data1, responseCode1);

	}
	else
	{
		hsp=0;
		document.getElementById('stopHead').innerHTML='Hide Stop Points';
		loadGPSLocations(data1, responseCode1);
	}
}

/*function createGeo(point4,icon4,html4) {
	//alert(point4+","+icon4+","+html4);
	var marker5 = new GMarker(point4,{icon:icon4});
	GEvent.addListener(marker5, "click", function() {
												  
  geocoder.getLocations(point4, function(response)																																						   {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
			//alert(place.address);
			//addr=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
		//alert(place.address);
	  }
	  marker5.openInfoWindowHtml(html4+"<br>"+addr);
	});
  });
	return marker5;
}*/
function createGeo(point4,icon4,html4) {
	//alert(point4+","+icon4+","+html4);
	var marker5 = new GMarker(point4,{icon:icon4});
	GEvent.addListener(marker5, "click", function() {
  geocoder.getLocations(point4, function(response)																																						   {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
			//alert(place.address);
			//addr=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
		//alert(place.address);
	  }
	});
  	html5="<table border=0 style=\"font-size:75%;font-family:arial,helvetica,sans-serif;\" id=\"grid\">"
	+ "<tr><td align=left>" + html4 +  "</td><td valign=top align=left><img src=../../images/favicon.png /></td></tr>"
	+ "<tr><td align=left colspan=2><b>Location:</b>&nbsp;" + addr + "</td></tr>"
	+ "</table>"

  	  marker5.openInfoWindowHtml(html5);

  });
	return marker5;
}	


function funFindDura(spt,ept)
{
	var ptf=0;
	if(spt == '' && ept == '')
	{
		alert('Please select the values');
		ptf=0;
		document.getElementById('txtFrmPt').select();
	}
	else if(spt == '' )
	{
		alert('Please select start point');
		ptf=0;
		document.getElementById('txtFrmPt').select();
		document.getElementById('txtFrmPt').value='';
	}
	else if(!ptno.test(spt))
	{
		alert('From should be integer');
		ptf=0;
		document.getElementById('txtFrmPt').focus();
		document.getElementById('txtFrmPt').value='';
	}

	else if( ept == '')
	{
		alert('Please select end point');
		ptf=0;
		document.getElementById('txtToPt').select();
		document.getElementById('txtToPt').value='';
	}
	else if(!ptno.test(ept))
	{
		alert('To should be integer');
		ptf=0;
		document.getElementById('txtToPt').value='';
		document.getElementById('txtToPt').focus();
	}
	else if(spt > t1 || ept > t1)
	{
		alert('Out of range.Please enter 0 to '+t1);
		ptf=0;
		document.getElementById('txtFrmPt').select();
		document.getElementById('txtFrmPt').value='';
		document.getElementById('txtToPt').value='';
	}
	//else if(spt >= ept)
//	{
//		alert('From should be less than To');
//		ptf=0;
//		document.getElementById('txtFrmPt').select();
//		document.getElementById('txtFrmPt').value='';
//		document.getElementById('txtToPt').value='';
//	}
	else ptf++;

	if(ptf!=0)
	{
		//alert(t1);
		findDuration(map,myMarker,spt,ept);		
		document.getElementById('txtFrmPt').value='';
		document.getElementById('txtToPt').value='';
		document.getElementById('txtFrmPt').focus();
	}else return false;
}
function HHMMSStime(tim3)
{
	//alert(tim3);
	
	d1=tim3.split(" ");
	dat=d1[0].split("-");
	d2=d1[1].split(":");
	d4=parseInt(d2[1])+30;
	var d3;
	var tem;
	mer="";
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
			d5=parseFloat(tem);
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
	return (d3+":"+parseInt(d2[2]));
}
var secondsPerMinute = 60;
var minutesPerHour = 60;

function convertSecondsToHHMMSS(intSecondsToConvert)
{
	var hours = convertHours(intSecondsToConvert);
	var minutes = getRemainingMinutes(intSecondsToConvert);
	minutes = (minutes == 60) ? "00" : minutes;
	var seconds = getRemainingSeconds(intSecondsToConvert);
	return hours+":"+minutes;
}

function convertHours(intSeconds) 
{
	var minutes = convertMinutes(intSeconds);
	var hours = Math.floor(minutes/minutesPerHour);
	return hours;
}
function convertMinutes(intSeconds) 
{
	return Math.floor(intSeconds/secondsPerMinute);
}
function getRemainingSeconds(intTotalSeconds) 
{
	return (intTotalSeconds%secondsPerMinute);
}
function getRemainingMinutes(intSeconds) 
{
	var intTotalMinutes = convertMinutes(intSeconds);
	return (intTotalMinutes%minutesPerHour);
}

function HMStoSec1(T) 
{ // h:m:s
  var A = T.split(/\D+/) ; return (A[0]*60 + +A[1])*60 + +A[2] 
}
function diffTime(tim1,tim2)
{
	//alert(HHMMSStime(tim1)+","+HHMMSStime(tim2));
	//HHMMSStime(tim1)
	var time1 = HMStoSec1(HHMMSStime(tim1));
	var time2 = HMStoSec1(HHMMSStime(tim2));
	var diff = time2 - time1;
	return (diff);
	//document.write(convertSecondsToHHMMSS(diff));
}
function findDuration(map,myMarker,spt,ept)
{
	var length2 = myMarker.length;
	var dis1=0;
	var t5=0.0;
	var t6=0.0;
	var tmp1=0;
	var dur=null;
	var init=null;
	var spt1=null;
	var ept1=null;
	var t2=0;

		spt1=myMarker[spt].getAttribute("gpsTime");
		ept1=myMarker[ept].getAttribute("gpsTime");
		
	dur=Math.abs(diffTime(spt1,ept1));
	//alert(dur);
	if(dur <  3600)
	{
		alert("Time taken from point 1 to point 2 is : "+Math.round(dur/60)+" Mins");
	}
	else
	{
		hr1=Math.floor(dur/3600);
		mn1=dur-(3600*hr1);
		mn1=Math.floor(mn1/60);
		//alert(hr1);
		alert("Time taken from point 1 to point 2 is : "+hr1+" Hour and "+mn1+" Mins");
	}
}

function callFunction(p,map,markers,length)
{
	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),parseFloat(markers[p].getAttribute("longitude")));
	
	var marker = createMarker(p, length, point,
				 Math.round(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("locationMethod"),
				 markers[p].getAttribute("gpsTime"),
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


function createMarker(i, length, point, speed, direction, locationMethod, gpsTime,
                      phoneNumber, sessionID, accuracy, isLocationValid, extraInfo,latitude,longitude) {
    var icon = new GIcon();

    // make the most current marker red
		if(mapPoints==3 && (i == length - 1))
		{
			icon.image = "images/Truck.png";
			icon.iconSize = new GSize(30, 25);
			//var label = new ELabel(point, cht, "style1");	+"<br/> Route: "+cht
			var label = new ELabel(point, phoneNumber, "style1");
			map.addOverlay(label);

			//icon.shadow = "images/coolshadow_small.png";
			geocoder.getLocations((latitude+","+longitude), function(response)																																						                {
			  if (!response || response.Status.code != 200) {
				//alert("Status Code:" + response.Status.code);
				addr="Status Code:" + response.Status.code;
			  }
			  else {
					place = response.Placemark[0];
					addr=place.address;
					//addr=addr.split(",");
					showMessage(addr);
			  }
			});
		}
		else
		{
		if ((i == length - 1)  && (mapPoints==2 || mapPoints==1))
		{
				icon.image = "images/red_anim.gif";
				icon.iconSize = new GSize(12, 20);
				//var label = new ELabel(point, cht, "style1");
				var label = new ELabel(point, phoneNumber, "style1");
				map.addOverlay(label);

				//icon.shadow = "images/coolshadow_small.png";
				geocoder.getLocations((latitude+","+longitude), function(response)																																						                {
				  if (!response || response.Status.code != 200) {
					//alert("Status Code:" + response.Status.code);
					addr="Status Code:" + response.Status.code;
				  }
				  else {
						place = response.Placemark[0];
						addr=place.address;
						addr=addr.split(",");
						showMessage(addr);
				  }
				});
		}
		else if(i == 0 && (mapPoints==2 || mapPoints==1) )
		{	
		
				icon.image = "images/green_small.png";
				icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(12, 20);
		}
		else if(mapPoints==1)
		{
				icon.image = "images/blue_small.png";
				icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(12, 20);
		}
		}

			icon.shadowSize = new GSize(22, 20);
			icon.iconAnchor = new GPoint(6, 20);
			icon.infoWindowAnchor = new GPoint(5, 1);
	
		GEvent.addListener(map, "maptypechanged", function() {
			//var newMapType = map.getCurrentMapType();
			mapType=map.getCurrentMapType().getName();
			// ...
		})
		GEvent.addListener(map, "zoomend", function() {
				zoomLevel1 = map.getZoom();

		});

	

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

    var str = "</td></tr>";

    // when a user clicks on last marker, let them know it's final one
    if (i == length - 1) {
        str = "</td></tr><tr><td colspan=2 align=center><b>Final point</b></td></tr>";
    }
	else if (i == 0) {
        str = "</td></tr><tr><td colspan=2 align=center><b>Start point</b></td></tr>";
    }
	else {
        str = "</td></tr><tr><td colspan=2 align=center><b>Internal point</b></td></tr>";
    }

	GEvent.addListener(map, "maptypechanged", function() {
		//var newMapType = map.getCurrentMapType();
		mapType=map.getCurrentMapType().getName();
		// ...
	})
	GEvent.addListener(map, "zoomend", function() {
			zoomLevel1 = map.getZoom();

	});
		
		
	// this creates the pop up bubble that displays info when a user clicks on a marker
    GEvent.addListener(marker, "click", function() {

	if(document.getElementById('txtFrmPt').value=='')
		document.getElementById('txtFrmPt').value=i;
	else
		document.getElementById('txtToPt').value=i;

	geocoder.getLocations((latitude+","+longitude), function(response)																																						   {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
			addr=addr.split(",");
			//alert(place.address);
			addr=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
		//alert(place.address);
	  }
	var ing=null;	
	if(extraInfo!='')
	{
		if(extraInfo==0)
		ing="off";
		else ing="On";
	}else ing="-";
	
												 
        marker.openInfoWindowHtml(
        "<table border=0 style=\"font-size:75%;font-family:arial,helvetica,sans-serif; margin-top:-10px;\"  id=\"\">"
        + "<tr><td align=right>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=right><b>Speed:</b></td><td>" + speed +  " Kmph&nbsp;</td></tr>"
        + "<tr><td align=right><b>Date & Time:</b></td><td colspan=2>" + gpsTime +  "</td></tr>"
        + "<tr><td align=right><b>Latitude:</b></td><td>" + latitude + "&nbsp;&nbsp;<b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right><b>Address:</b></td><td>" + addr + "</td><td>&nbsp;</td></tr>"

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
function autoRefresh(date,vehicle_no) 
{

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



