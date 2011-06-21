var interval=5;
var data1;
var routeData;
var cirPts;
var responseCode1;
var ptno=/^[0-9]*$/;
var zoomLevel1;
var myMarker;
var mapType;
var t1=null;


function getAllVehicle(date_offline,sessionid)
{
	showWait('Getting Vehicles...');
	zoomLevel=10;
	var url = 'showAllDevice.php?date_offline='+date_offline + '&sessionID='+sessionid;
	//document.write(url);
	showWait('Loading ...');
	GDownloadUrl(url, loadAllGPSLocations);
}

function getFromOneVehicle(stHr1,endHr1)
{
    if (data1.length == 0) {
        showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = '';
    }
    else 
	{
        if (hasMap()) {
        var xml = GXml.parse(data1);
		var root = xml.getElementsByTagName("gps");
		//cirPts = root[0].getAttribute("totPt");	
		//alert(cirPts);
		//showCirclePts("all",cirPts);	
			
        var markers = xml.getElementsByTagName("locations");
        showWait("Loading Map...");
		
		var length = markers.length;
		if(length!=0)
		{
			if(mapType==1)
			map.setMapType(G_NORMAL_MAP);
			else if(mapType==2)
			map.setMapType(G_SATELLITE_MAP); 
			else if(mapType==3)
			map.setMapType(G_HYBRID_MAP); 
			
			for (i = 0; i < markers.length; i++) 
			{
				curTime = parseInt(markers[i].getAttribute("curTime")); 
				
				if(curTime >= stHr1 && curTime<=endHr1)
				{
					//alert(curTime +">="+ stHr1+" && "+curTime+" <= "+ endHr1);
					map.setCenter(new GLatLng(parseFloat(markers[i].getAttribute("latitude")),parseFloat(markers[i].getAttribute("longitude"))), zoomLevel);
					callFunction(i,map,markers,length);
				}
			}
		// center map on last marker so we can see progress during refreshes
			/*map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);	//zoomLevel
			
				for (var i = 0; i < length; i++) 
				{
					callAllFunction(i,map,markers,length);
				}*/
			}
			hideWait();
		}
    }
	
	/*zoomLevel=16;
	//document.write(url);
	showWait('Getting map...');
	GDownloadUrl(url, loadGPSLocations);
//	hideWait();*/
}
function getOneVehicle(url)
{
	alert(url);
	zoomLevel=15;	
	showWait('Getting map...');
	GDownloadUrl(url, loadGPSLocations);
	//hideWait();
}
function getAddress(lat,long,div)
{
	var addr="";
	address=((lat)+","+(long));
	var geocoder2 = new GClientGeocoder();
	geocoder2.getLocations(address, function(response) {
	  if (!response || response.Status.code != 200) {
		//alert("Status Code:" + response.Status.code);
		document.getElementById(div).innerHTML= "Status Code: not identified";
	  } else {
		place = response.Placemark[0];
	
		addr=place.address;
		//addr=addr.split(",");
		//alert(place.address);
		//document.getElementById(div).innerHTML=place.address;
		return addr;
	}
	});
}
function loadAllGPSLocations(data, responseCode) {
	//data1 = data;
	//responseCode1 = responseCode;
	var totalStop=0;
	var t1=0.0;
	var t2=0.0;
	var c1=0;
	var d1;
	var dis=0;
	
	
    if (data.length < 12) {
        showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = 'There is no tracking data to view';
    }
    else 
	{
        if (hasMap()) {
		var xmlRoot=GXml.parse(data);
		var root = xmlRoot.getElementsByTagName("gps");
        var xml = GXml.parse(data);
        var markers = xml.getElementsByTagName("locations");
        showWait("Loading Map...");
		
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
			showWait("Showing All Unit(s)");
			//hideWait();
		}
    }
}
function callAllFunction(p,map,markers,length)
{
	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),parseFloat(markers[p].getAttribute("longitude")));
	var marker = createAllMarker(p, length, point,
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("gpsTime"),
				 markers[p].getAttribute("deviceName"),
				 markers[p].getAttribute("deviceIMEI"),
				 markers[p].getAttribute("distance"),
				 markers[p].getAttribute("sessionID"),
				 markers[p].getAttribute("accuracy"),
				 markers[p].getAttribute("isLocationValid"),
				 markers[p].getAttribute("extraInfo"),
				 markers[p].getAttribute("icon"),
				 parseFloat(markers[p].getAttribute("altitute")),
				 parseFloat(markers[p].getAttribute("latitude")),
				 parseFloat(markers[p].getAttribute("longitude")));

	// add markers to map
	map.addOverlay(marker);
}

function createAllMarker(i, length, point, speed, direction, gpsTime, deviceName,
                      deviceIMEI, distance,sessionID, accuracy, isLocationValid, extraInfo,vehiIcon,altitute,latitude,longitude) {
    var icon = new GIcon();
	//icon.image = "images/RedCar.png";
	icon.image = "images/RedCar.png";

	var label = new ELabel(point, deviceName, "style1");
	map.addOverlay(label);

	
	//icon.shadow = "images/coolshadow_small.png";
    //icon.shadow = "images/coolshadow_small.png";
    icon.iconSize = new GSize(35, 20);
    icon.shadowSize = new GSize(22, 20);
    icon.iconAnchor = new GPoint(6, 20);
    icon.infoWindowAnchor = new GPoint(5, 1);

    var marker = new GMarker(point,icon);
	var latlng;
    var lm = "";

	GEvent.addListener(map, "maptypechanged", function() {
		//var newMapType = map.getCurrentMapType();
		mapType=map.getCurrentMapType().getName();
		// ...
	})
	GEvent.addListener(map, "zoomend", function() {
			zoomLevel1 = map.getZoom();

	});

	var addrs;
    var str = "</td></tr>";
	
	var oneDate = gpsTime.split(" ");	
	date = oneDate[0].split("-");

	var devTxt='';
	devTxt='<a href=# onclick="onlineGTracker('+sessionID+','+date[0]+','+date[1]+','+date[2]+','+deviceIMEI+');">&nbsp;<strong class=input_submit>View</strong></a>';
	
	
	str = "</td></tr><tr><td align=left colspan=2><b>Vehicle "+(i+1)+" : "+deviceName+"</b></td></tr>";
    GEvent.addListener(marker, "click", function() {
	
	geocoder.getLocations((latitude+","+longitude), function(response)																																						    {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
			//addr=addr.split(",");
			//alert(place.address);
			//addr=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
	  }
																																						 
        marker.openInfoWindowHtml(
        "<table border=0>"
        + "<tr><td align=left>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=left><b>Speed:</b>:" + speed +  " Kmph&nbsp;</td><td align=left><b>Distance:</b>:"+distance + " Km</td></tr>"
        + "<tr><td align=left><b>Date & Time:</b>:" + gpsTime +  "</td><td align=left><b>Click To:</b>:" + devTxt +  "</td></tr>"
        + "<tr><td align=left><b>Latitude:</b>:" + latitude + "</td><td align=left><b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
        + "<tr><td align=left colspan=3><b>Address:</b>:" + addr + "</td></tr>"
        + "</table>"
        );
		});
    });

    return marker;
}
// this will get the map and route, the route is selected from the dropdown box - for offline details
function getRouteForMap_offline(date_offline,vehicle_no) {
	//alert(date_offline+","+vehicle_no);
    if (date_offline) {
        showWait('Getting map...');
		date_is=date_offline;
		if(routeSelect.options[routeSelect.selectedIndex].value=="&sessionID=all&deviceIMEI=Show All")
		{
			
			zoomLevel=10;
			var url = 'showAllDevice.php?date_offline='+date_offline + '&sessionID=all';
			//document.write(url);
			GDownloadUrl(url, loadAllGPSLocations);
			//document.getElementById('vehiDataSpan').style.display = 'none';
		}
		else
		{
			
			var url = 'locations.php?date_offline='+date_offline + routeSelect.options[routeSelect.selectedIndex].value;
			//getOneVehicle(url)
			//document.getElementById('vehiDataSpan').style.display = 'block';
			//alert(routeSelect.options[routeSelect.selectedIndex].value);
			
			//GDownloadUrl(url, loadGPSLocations);
		}
	}
	else {
	    alert("Please select a route before trying to refresh map.");
	}
}
function createReport(data, responseCode) 
{
	if (data.length == 0) {

        showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = '';
    }
    else {

	var xml = GXml.parse(data);

	var markers = xml.getElementsByTagName("locations");
	var length = markers.length;
	var reportData = '';
	for (var i = 0; i < (length-1); i++) 
	{
			reportData += i+','+
				 Math.floor(markers[i].getAttribute("speed"))+','+
				 markers[i].getAttribute("direction")+','+
				 markers[i].getAttribute("gpsTime")+','+
				 markers[i].getAttribute("deviceName")+','+
				 markers[i].getAttribute("deviceIMEI")+','+
				 markers[i].getAttribute("sessionID")+','+
				 markers[i].getAttribute("accuracy")+','+
				 markers[i].getAttribute("isLocationValid")+','+
				 markers[i].getAttribute("extraInfo")+','+
				 parseFloat(markers[i].getAttribute("altitute"))+','+
				 parseFloat(markers[i].getAttribute("latitude"))+','+
				 parseFloat(markers[i].getAttribute("longitude"))+"@";
	}
	
	
	exeReportTable(reportData);
	showWait('Report Generated');
	}
}
function showMapOnDate(sDate,sTime,eDate,eTime,devId) {
	//exeRefreshTable();
	sTime = sTime.split(":");
	sTime = (parseInt(sTime[0]*60) + parseInt(sTime[1]));
	
	eTime = eTime.split(":");
	eTime = (parseInt(eTime[0]*60) + parseInt(eTime[1]));
	showWait('Generating Report...');
	var url = 'locations.php?sDate='+sDate+'&sTime='+sTime+'&eDate='+eDate+'&eTime='+eTime+'&deviceIMEI='+devId;
	//alert("1"+url);
	GDownloadUrl(url, createReport);
	/*var interval = setInterval( function() { 
	//getOneVehicle(url); 
	var url = 'locations.php?sDate='+sDate+'&sTime='+sTime+'&eDate='+eDate+'&eTime='+eTime+'&deviceIMEI='+devId;
	//alert("2"+url);
	getOneVehicle(url);
	exeRefreshTable();
	}, 20000 );*/
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
			//map.enableScrollWheelZoom();
		    //map.enableContinuousZoom();
			map.addControl(new GScaleControl());
			//map.setUIToDefault();
			//map.addControl(new GOverviewMapControl());
			
			
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
	if(!document.getElementById('showPath').checked)
	{
		mapPoints=mp;
		timDv=document.getElementById('timdDiv').innerHTML.split("&nbsp;");
		document.getElementById('timdDiv').innerHTML=timDv[0];
		document.getElementById('shPt').style.display='none';
		interval=1;
		//document.getElementById('txtPtDiff').value='';
		//loadGPSLocations(data1, responseCode1);	
	}
	else
	{
		mapPoints=mp;
		document.getElementById('shPt').style.display='block';
		//document.getElementById('txtPtDiff').value='';
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
		
		if(root[0].getAttribute('geoData')!='')
		{
			 geoData=root[0].getAttribute('geoData');
		}
		if(root[0].getAttribute('geoPointName')!='')
		{
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
		//document.getElementById("vehiId").innerHTML=markers[0].getAttribute("deviceIMEI");
	

		var length = markers.length;
		t1=markers.length;
		if(length!=0)
		{
		// center map on last marker so we can see progress during refreshes
	    if(zoomLevel1)
		{
			map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel1);
		}
		else 
		{
			 map.setCenter(new GLatLng(parseFloat(markers[length-1].getAttribute("latitude")),parseFloat(markers[length-1].getAttribute("longitude"))), zoomLevel);
		}
		
		cirPts = root[0].getAttribute("totPt");	
		//showCirclePts("all",cirPts);
		
		/*if(mapType==1)
		map.setMapType(G_NORMAL_MAP);
		else if(mapType==2)
		map.setMapType(G_SATELLITE_MAP); 
		else if(mapType==3)
		map.setMapType(G_HYBRID_MAP); */
		
		var reportData = '';
		var baseIcon = new GIcon();
		baseIcon.iconSize=new GSize(12,12);
		baseIcon.iconAnchor=new GPoint(8,8);
		baseIcon.infoWindowAnchor=new GPoint(10,0);
		
		var redIcon = (new GIcon(baseIcon, "../Maps/images/redCircle.png", null, ""));

		var gf2= new Array();
		var COLORS=["#0000ff", "#00ff00", "#ff0000"];
		var COLORI=0;

		if(geoData!='')
		{
			var y=0;
			var stName=geoPointName.split(",");
			var gfArr=geoData.split("@");
			for(var f=0;f<(gfArr.length)-1;f++)
			{
				var stopHtml='';
				cColor = COLORS[COLORI++ % 3];
				gpts1=gfArr[f].split(",");
				pnts=new GLatLng(Number(gpts1[1]),Number(gpts1[2]));
				
				var label = new ELabel(pnts, stName[f], "style2");
				map.addOverlay(label);
				
				var marker5 = createGeo(pnts,redIcon,'<b>Stop Name : </b>'+stName[f]);
				map.addOverlay(marker5);
				
				setTimeout("createCircle(new GLatLng("+ Number(gpts1[1]) + ", " + Number(gpts1[2]) +"), "+ ((Number(gpts1[0])*5280)/3.2808399)+",'"+cColor+"');", 300);				
			}
		}
		
		reportData += 0+','+
				 Math.floor(markers[0].getAttribute("speed"))+','+
				 markers[0].getAttribute("direction")+','+
				 markers[0].getAttribute("gpsTime")+','+
				 markers[0].getAttribute("deviceName")+','+
				 markers[0].getAttribute("deviceIMEI")+','+
				 markers[0].getAttribute("sessionID")+','+
				 markers[0].getAttribute("accuracy")+','+
				 markers[0].getAttribute("isLocationValid")+','+
				 markers[0].getAttribute("extraInfo")+','+
				 parseFloat(markers[0].getAttribute("altitute"))+','+
				 parseFloat(markers[0].getAttribute("latitude"))+','+
				 parseFloat(markers[0].getAttribute("longitude"))+"@";
				 
		//callFunction(0,map,markers,length);
		//callDistance(map,markers);
//		document.getElementById("distance").innerHTML="&nbsp;&nbsp;<strong>"+callDistance(map,markers)+" Km</strong>";
		var t=0;
		var pits1;
		var pits2;
		var t7=0.0;
		var t8=0.0;
		var totalDistance=0;
		var stopIn=0;
		var strTime='';
		var endTime='';
		var stoptime='';

		for (var i = 0; i < (length-1); i++) 
		{
			callFunction(i,map,markers,length);

		}
		//document.getElementById("distance").innerHTML="Traveled&nbsp;&nbsp;: "+Math.round(totalDistance)+" Km";
		j = length - 1;
		
		reportData += j+','+
				 Math.floor(markers[j].getAttribute("speed"))+','+
				 markers[j].getAttribute("direction")+','+
				 markers[j].getAttribute("gpsTime")+','+
				 markers[j].getAttribute("deviceName")+','+
				 markers[j].getAttribute("deviceIMEI")+','+
				 markers[j].getAttribute("sessionID")+','+
				 markers[j].getAttribute("accuracy")+','+
				 markers[j].getAttribute("isLocationValid")+','+
				 markers[j].getAttribute("extraInfo")+','+
				 parseFloat(markers[j].getAttribute("altitute"))+','+
				 parseFloat(markers[j].getAttribute("latitude"))+','+
				 parseFloat(markers[j].getAttribute("longitude"))+"@";
				 
		callFunction(j,map,markers,length);
		
		exeReportTable(reportData);
		
		/*document.getElementById("ctSpd").innerHTML='Speed&nbsp;:&nbsp;'+Math.round(parseFloat(markers[j].getAttribute("speed")))+' kmph';
		document.getElementById("dateTime").innerHTML='Date & Time&nbsp;:&nbsp;'+markers[j].getAttribute("gpsTime");
		document.getElementById("noSate").innerHTML='Satelite(s)&nbsp;:&nbsp;'+markers[j].getAttribute("extraInfo");
		document.getElementById("posLatPt").innerHTML='Latitude&nbsp;:&nbsp;'+markers[j].getAttribute("latitude");
		document.getElementById("posLongPt").innerHTML='Logntitude&nbsp;:&nbsp;'+markers[j].getAttribute("longitude");
		document.getElementById("posAltPt").innerHTML='Altitude&nbsp;:&nbsp;'+markers[j].getAttribute("altitute");

		if(Math.round(sts)>0)
		{
			document.getElementById("sts").innerHTML="Status&nbsp;&nbsp;: Running";
		}
		else document.getElementById("sts").innerHTML="Status&nbsp;&nbsp;: Stopped";*/
		
		}

        }
        //showMessage(routeSelect);
    }
}
function showCirclePts(type,ptData)
{
	//alert(ptData);
	var pts = [];
	var ptDt1=ptData.split("@");
	for(var f=0;f<(ptDt1.length)-1;f++)		//(ptDt1.length)-1
	{
		ptDt2 = ptDt1[f].split(",");
		pts[f] = new GLatLng(parseFloat(ptDt2[0]),parseFloat(ptDt2[1]));
		//alert(pts[f]);
	}
	//pts[ptDt1.length] = new GLatLng(parseFloat(ptDt2[0]),parseFloat(ptDt2[1]));
	//alert(pts[ptDt1.length-2]+" "+ptDt1.length);
	var poly = new BDCCArrowedPolyline(pts,"#FF0000",1,0.60,null,1,7,"#0000FF",1,0);
	map.addOverlay(poly);
}
function createCircle (centerHandlePosition,radius,color)
{
    var points = [];
    var distance = radius/1000;
    for (i = 0; i < 72; i++) {
      points.push(destination(centerHandlePosition, i * 360/72, distance) );
    }
    points.push(destination(centerHandlePosition, 0, distance) );
    //this._polyline = new GPolyline(this._points, this._color, 6);
    var polyline = new GPolygon(points, color, 1, 1, color, 0.2);
    map.addOverlay(polyline)
    //this._control.render();
}
function destination(orig, hdng, dist) {
  var R = 6371; // earth's mean radius in km
  var oX, oY;
  var x, y;
  var d = dist/R;  
  hdng = hdng * Math.PI / 180; // degrees to radians
  oX = orig.x * Math.PI / 180;
  oY = orig.y * Math.PI / 180;

  y = Math.asin( Math.sin(oY)*Math.cos(d) + Math.cos(oY)*Math.sin(d)*Math.cos(hdng) );
  x = oX + Math.atan2(Math.sin(hdng)*Math.sin(d)*Math.cos(oY), Math.cos(d)-Math.sin(oY)*Math.sin(y));

  y = y * 180 / Math.PI;
  x = x * 180 / Math.PI;
  return new GLatLng(y, x);
}
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
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("gpsTime"),
				 markers[p].getAttribute("deviceName"),
				 markers[p].getAttribute("deviceIMEI"),
				 markers[p].getAttribute("distance"),
				 markers[p].getAttribute("sessionID"),
				 markers[p].getAttribute("accuracy"),
				 markers[p].getAttribute("isLocationValid"),
				 markers[p].getAttribute("extraInfo"),
				 parseFloat(markers[p].getAttribute("altitute")),
				 parseFloat(markers[p].getAttribute("latitude")),
				 parseFloat(markers[p].getAttribute("longitude")));
	
	// add markers to map
	map.addOverlay(marker);
}


function createMarker(i, length, point, speed, direction, gpsTime, deviceName,
                      deviceIMEI, distance,sessionID, accuracy, isLocationValid, extraInfo,altitute,latitude,longitude) {
    var icon = new GIcon();

    // make the most current marker red
//alert(mapPoints+" sds "+i);

		if ((i == length - 1)  && (mapPoints==2 || mapPoints==1 || mapPoints==5))
		{
				icon.image = "images/red_anim.gif";
				icon.iconSize = new GSize(12, 20);
				var label = new ELabel(point, deviceName, "style1");
				map.addOverlay(label);

				geocoder.getLocations((latitude+","+longitude), function(response)																																						                {
				  if (!response || response.Status.code != 200) {
					//alert("Status Code:" + response.Status.code);
				  }
				  else {
						place = response.Placemark[0];
						addr=place.address;
						//addr=addr.split(",");
						showMessage(addr);
				  }
				});
		}
		else if(i == 0 && (mapPoints==2 || mapPoints==1 || mapPoints==5) )
		{	
				icon.image = "images/green_small.png";
				icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(12, 20);
		}
		else if(mapPoints==1|| mapPoints==5)
		{
				icon.image = "images/blue_small.png";
				icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(12, 20);
		}
		
			
		icon.shadowSize = new GSize(22, 20);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
	

    var marker = new GMarker(point,icon);
	var latlng;
	// this describes how we got our location data, either by satellite or by cell phone tower
  

    var str = "</td></tr>";

    // when a user clicks on last marker, let them know it's final one
    if (i == length - 1) {
        str = "</td></tr><tr><th align=center colspan=2>Final location</th></tr>";
    }
	else if (i == 0) {
        str = "</td></tr><tr><th align=center colspan=2>Start point</td></tr>";
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

	geocoder.getLocations((latitude+","+longitude), function(response)																																						    {
	  if (!response || response.Status.code != 200) {
        //alert("Status Code:" + response.Status.code);
		addr="Status Code:" + response.Status.code;
      }
	  else 
	  {
        	place = response.Placemark[0];
			addr=place.address;
	  }
	showMessage(addr);
	
	var ptText='';
	ptText='<tr><td colspan=2 align=center><input type=text name="txtMapPoint" id="txtMapPoint" value="pointName" />&nbsp;&nbsp;<a href=# onclick="addThisPoint('+latitude+','+longitude+');">&nbsp;<strong class=input_submit>Set</strong></a></td></tr>';
	  
        marker.openInfoWindowHtml(
		 "<table border=0>"
        + "<tr><td align=left>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=left><b>Speed:</b>:" + speed +  " Kmph&nbsp;</td><td align=left><b>Distance:</b>:"+distance + " Km</td></tr>"
        + "<tr><td align=left><b>Date & Time:</b>:" + gpsTime +  "</td><td align=left>&nbsp;</td></tr>"
        + "<tr><td align=left><b>Latitude:</b>:" + latitude + "</td><td align=left><b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
        + "<tr><td align=left colspan=3><b>Address:</b>:" + addr + "</td></tr>"
        + "</table>"
       
        );
		});
    });
    return marker;
}

var ajax1=new sack();
var stopName=/^[a-zA-Z0-9][a-zA-Z0-9 ]*$/;

function validThis()
{
 	if(document.getElementById('txtMapPoint').value=="")
	{
		alert('Stop Name required');
		document.getElementById('txtMapPoint').focus();
		return false;
	}
	else if (!stopName.test(document.getElementById('txtMapPoint').value)) 
	{
		alert('Only alphanumeric allowed');
		document.getElementById('txtMapPoint').focus();
		document.getElementById('txtMapPoint').select();
		return false;	
	}
	else
	{
		return true;
	}
}
function addThisPoint(lt1,lng1)
{
	var flg1=validThis();
	if(flg1)
	{
		ajax1.requestFile = 'ajax_server.php?add_stop_name='+document.getElementById('txtMapPoint').value+'&mapPt1='+lt1+'&mapPt2='+lng1;
		//document.write(ajax1.requestFile);
		ajax1.onCompletion = function(){
		if(eval(ajax1.response) == 0)
		{
			ajax1.requestFile = 'ajax_server.php?mapLati='+lt1+'&mapLong='+lng1+'&mapPoint='+document.getElementById('txtMapPoint').value;
			//document.write(ajax1.requestFile);
			ajax1.onCompletion = function(){exeAddPoints()};
			ajax1.runAJAX();
		}
		else
		{
			alert('Same point name already exist. Please use different.');
			document.getElementById('txtMapPoint').focus();
			document.getElementById('txtMapPoint').select();
		}
		};
		ajax1.runAJAX();
	}
}
function exeAddPoints()
{
	if(eval(ajax1.response) == 1)
	{
		alert("Point added successfully.");
		map.closeInfoWindow();
	}
	else
	{
		alert("Point adding failed.");
		map.closeInfoWindow();
	}
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
     messages.innerHTML = 'Status: ' + message;
}

function showRouteName() {
    //showMessage(routeSelect);
}

function showWait(theMessage) {
	map.innerHTML = '<img src="images/ajax-loader.GIF"' + 'style="left:325px;">';
    //map.innerHTML = "";
    showMessage(theMessage);
}

function hideWait() {
    map.innerHTML = '';
    messages.innerHTML = 'Status:';
}


