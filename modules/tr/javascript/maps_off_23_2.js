var interval=5;
var data1;
var routeData;
var cirPts;
var responseCode1;
var ptno=/^[0-9]*$/;
var zoomLevel1;
var myMarker;
var gmarkers = [];
var imeiArr = [];
var mapType;
var t1=null;
var side_bar_html = "";
var progressBar;
function getAllVehicle(date_offline,sessionid)
{
	showWait('Getting Vehicles...');
	zoomLevel=10;
	var url = 'showAllDevice.php?date_offline='+date_offline + '&sessionid='+sessionid;
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
	//alert(url);
	zoomLevel=15;	
	showWait('Getting map...');
	GDownloadUrl(url, updateAllGPSLocations);
	showWait('Loading...');
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
		return addr;
	}
	});
}
function updateMarker(i, length, point, markers, speed, direction, gpsTime, geodate, deviceName,
                      deviceIMEI, distance,sessionID, accuracy, isLocationValid, extraInfo,vehiIcon,altitute,latitude,longitude) {
						  
	var divStr = document.getElementById(curId).innerHTML.split("-");
	
	if(speed !=0)
	{					  
		var arrowIcon = new Image();
		var marker = new ELabel( new GLatLng(parseFloat(latitude),parseFloat(longitude)), 
                                 '<canvas id="arrowcanvas" width="32" height="32"><\/canvas>',
                                 null,
                                 new GSize(-16, 16));

       
		arrowIcon.src = "images/green-arrow.png";
		//map.addOverlay(marker);
		map.addOverlay(marker);
		var canvas = document.getElementById("arrowcanvas").getContext('2d');
		var angleRadians = (direction / 180) * Math.PI;
		
		var cosa = Math.cos(angleRadians);
		var sina = Math.sin(angleRadians);
		
		canvas.clearRect(0, 0, 32, 32);
		canvas.save();
		canvas.rotate(angleRadians);
		canvas.translate(16 * sina + 16 * cosa, 16 * cosa - 16 * sina);
		canvas.drawImage(arrowIcon, -12, -12);
		canvas.restore();
		//alert(divStr[0]);
  	    document.getElementById(curId).innerHTML = divStr[0]+" - "+gpsTime;
		geocoder.getLocations((latitude+","+longitude), function(response)	
		{
		  if (!response || response.Status.code != 200) {
				addr="Status Code:" + response.Status.code;
		  }
		  else {
				place = response.Placemark[0];
				addr=place.address;
		  }
		  showMessage(addr);
		  showParameters(i,markers);
		}); 
		
		//marker.setIcon(canvas);

	 
	//icon.image = "images/RedCar.png";
	//if(speed !=0)
	//icon.image = "images/green-arrow.png";
	}
	else
	{
		document.getElementById(curId).innerHTML = divStr[0]+" - "+gpsTime;
		var icon = new GIcon();
		icon.image = "images/redCircle.png";
		
		//var label = new ELabel(point, deviceName, "style2");
		//map.addOverlay(label);		
		//icon.shadow = "images/coolshadow_small.png";
		icon.iconSize = new GSize(10, 10);
		icon.shadowSize = new GSize(22, 20);
		icon.iconAnchor = new GPoint(6, 6);
		icon.infoWindowAnchor = new GPoint(6, 2);
		var marker = new GMarker(point,icon);	
	
    //var marker = new GMarker(point);
	var latlng;
    var lm = "";

	GEvent.addListener(map, "maptypechanged", function() {		
		mapType=map.getCurrentMapType().getName();
		
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
	
	//side_bar_html += '<a href="javascript:myclick(' + (i) + ')">' + deviceName + '<\/a><br>';
	 
	str = "</td></tr><tr><td align=left colspan=2><b>Device  : "+deviceName+"</b></td></tr>";
	
	geocoder.getLocations((latitude+","+longitude), function(response)																																						    {
	  if (!response || response.Status.code != 200) {
			addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
	  }
	  showMessage(addr);
	  showParameters(i,markers);
	});  
    GEvent.addListener(marker, "click", function() {
												 
		geocoder.getLocations((latitude+","+longitude), function(response)																																						    	{
		  if (!response || response.Status.code != 200) {
				addr="Status Code:" + response.Status.code;
		  }
		  else {
				place = response.Placemark[0];
				addr=place.address;
		  }
	  
	  	showMessage(addr);
		showParameters(i,markers);
		zoomLevel1 = 15;
		map.setCenter(point, zoomLevel1);				
						
        marker.openInfoWindowHtml(
        "<table border=0>"
        + "<tr><td align=left>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=left><b>Speed:</b>:" + speed +  " Kmph&nbsp;</td><td align=left><b>Distance:</b>:"+distance + " Km</td></tr>"
        + "<tr><td align=left><b>Date & Time:</b>:" + geodate + "</td><td align=left>&nbsp;</td></tr>"
        + "<tr><td align=left><b>Latitude:</b>:" + latitude + "</td><td align=left><b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
        + "<tr><td align=left colspan=3><b>Address:</b>:" + addr + "</td></tr>"
        + "</table>"
        );
		});
    });
	}

    return marker;
}
function updateAllGPSLocations(data, responseCode) {
	//data1 = data;
	//responseCode1 = responseCode;
	var totalStop=0;
	var t1=0.0;
	var t2=0.0;
	var c1=0;
	var d1;
	var dis=0;
	
	
    if (data.length < 12) {
        showMessage('There is no data to view.');
        //document.getElementById("map").innerHTML = 'There is no tracking data to view';
    }
    else 
	{
        map.clearOverlays();
		var xmlRoot=GXml.parse(data);
		var root = xmlRoot.getElementsByTagName("gps");
        var xml = GXml.parse(data);
        var markers = xml.getElementsByTagName("locations");
		
		var otherData = xml.getElementsByTagName("OtherData");
		var points2 = [];
		//alert(otherData[0].getAttribute('totPt'));
		if(otherData[0].getAttribute('totPt')!='')
		{
			var cirPts1 = otherData[0].getAttribute("totPt");	
			
			var ptDt1=cirPts1.split("@");
			
			for(var h = 0; h < (ptDt1.length); h++) {
				  
				ptDt2 = ptDt1[h].split(",");
				points2[h] = new GLatLng(parseFloat(ptDt2[0]),parseFloat(ptDt2[1]));
				
			}
			
			 var poly2= new GPolyline(points2, "#228B22", 2, 1);
		 	
		}
		 map.addOverlay(poly2);
			//myMarker = xml.getElementsByTagName("locations");
        showWait("Refreshing Map...");
		
		var length = markers.length;
		 //progressBar.start(length);
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
					var point = new GLatLng(parseFloat(markers[i].getAttribute("latitude")),parseFloat(markers[i].getAttribute("longitude")));
					var marker = updateMarker(i, length, point, markers,
								 Math.floor(markers[i].getAttribute("speed")),
								 markers[i].getAttribute("direction"),
								 markers[i].getAttribute("gpsTime"),
								 markers[i].getAttribute("geodate"),
								 markers[i].getAttribute("deviceName"),
								 markers[i].getAttribute("deviceIMEI"),
								 markers[i].getAttribute("distance"),
								 markers[i].getAttribute("sessionID"),
								 markers[i].getAttribute("accuracy"),
								 markers[i].getAttribute("isLocationValid"),
								 markers[i].getAttribute("extraInfo"),
								 markers[i].getAttribute("icon"),
								 parseFloat(markers[i].getAttribute("altitute")),
								 parseFloat(markers[i].getAttribute("latitude")),
								 parseFloat(markers[i].getAttribute("longitude")));
					
					map.addOverlay(marker);
					//setTimeout('i('+i+','+map+','+markers+','+length+')', 10);
				}
				GEvent.trigger(marker, "click");
			}
    }
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
        showMessage('There is no data to view.');
        document.getElementById("map").innerHTML = 'Data not available';
    }
    else 
	{
       
		var xmlRoot=GXml.parse(data);
		var root = xmlRoot.getElementsByTagName("gps");
        var xml = GXml.parse(data);
        var markers = xml.getElementsByTagName("locations");
			//myMarker = xml.getElementsByTagName("locations");
        showWait("Loading Map...");
		
		var length = markers.length;
		 //progressBar.start(length);
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
					//setTimeout('callAllFunction('+i+','+map+','+markers+','+length+')', 10);
				}
		}
		showWait("Choose device to display navigation");
		
    }
}
function toggleRefesh(status)
{
	if(status)
	{
		document.getElementById('spAutoRef').innerHTML =  "Refresh On";
		intervalID = setInterval(function() { 
		txturl = 'showUpdate.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devName;
		//refreshMapTable(sessionid,date_offline);
		getOneVehicle(txturl); 
		}, newInterval * 1000);
	}
	else
	{
		document.getElementById('spAutoRef').innerHTML =  "Refresh Off";
		clearInterval(intervalID);
	}
}
var tmpUid = -1;
var intervalID;
var tmpNdiv = '';
var txturl = "";
var curId = -1;
function myclick(sessionid,d1,d2,d3,devName,runflag) 
{
	map.clearOverlays();
	//alert("out "+autoRefresh+","+newInterval+","+intervalID);
	if(runflag == 1)
	{
		if(gmarkers.length > 0)
		{
			for(k=0;k<gmarkers.length;k++)
			{
				if(imeiArr[k] == devName)
				{
					showMessage('Loading...');
					//map.clearOverlays();
					//GEvent.trigger(gmarkers[k], "click");	
					map.addOverlay(gmarkers[k]);
					pickThisDevice(devName);
					
					document.getElementById('totDist').innerHTML = 0;
					
					if(intervalID!=0)
					{
						clearInterval(intervalID);
						
					}
					
					if(tmpUid == -1)
					{
						tmpUid = k;				
						document.getElementById(devName).className = 'visited_link';
					}
					else if(tmpUid != k)
					{
						document.getElementById(imeiArr[k]).className = 'visited_link';
						document.getElementById(imeiArr[tmpUid]).className = 'green_link';
						tmpUid = k;
						//document.getElementById(devName).className = 'active_link';
					}
					//alert("in1 "+autoRefresh+","+newInterval+","+intervalID);
						if(autoRefresh == 1)				
						{
							date_offline = d1+"-"+d2+"-"+d3;
							txturl = 'showUpdate.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devName;
							getOneVehicle(txturl); 
							intervalID = setInterval(function() { 
							//alert(document.getElementById(devName).innerHTML);
							txturl = 'showUpdate.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devName;
							//refreshMapTable(sessionid,date_offline);
							getOneVehicle(txturl); 
							}, newInterval * 1000);
						}
					curId = devName;
				}
				else
				{
					//alert("in2 "+autoRefresh+","+newInterval+","+intervalID);
					autoRefresh == 0;
					if(tmpNdiv == '')
					{
						document.getElementById(devName).className = 'visited_link';
						tmpNdiv = devName;
						
					}
					else
					{
						document.getElementById(tmpNdiv).className = 'not_live';
						document.getElementById(devName).className = 'visited_link';
						tmpNdiv = devName;
					}
					curId = devName;
					/*if(d2<10)
					date_offline = d1+"-0"+d2+"-"+d3;
					else
					date_offline = d1+"-"+d2+"-"+d3;
					
					document.getElementById('from_date').value = date_offline;
					var txturl = 'showUpdate.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devName;
					//getOneVehicle(txturl); 
					//getNotAvailVehi(sessionid,date_offline,devName);*/
				}
			}
		}
	}
	else
	{
		//alert("in3 "+autoRefresh+","+newInterval+","+intervalID);
		autoRefresh == 0;
		map.clearOverlays();
		if(intervalID)
		{
			clearInterval(intervalID);
			
		}
		if(tmpNdiv == '')
		{
			document.getElementById(devName).className = 'visited_link';
			tmpNdiv = devName;
			
		}
		else
		{
			document.getElementById(tmpNdiv).className = 'not_live';
			document.getElementById(devName).className = 'visited_link';
			tmpNdiv = devName;
		}
		curId = devName;
		
		date_offline = d1+"-"+d2+"-"+d3;
		document.getElementById('from_date').value = date_offline;
		var txturl = 'showUpdate.php?date_offline='+date_offline + '&sessionID=' + sessionid + '&deviceIMEI=' + devName;
		getOneVehicle(txturl); 
		pickThisDevice(devName);
		showWait('Please Change Start Date');
		//getNotAvailVehi(sessionid,d1,d2,d3,devName);
		
		
	}
}

function callAllFunction(p,map,markers,length)
{
	//progressBar.updateLoader(1);
	var point = new GLatLng(parseFloat(markers[p].getAttribute("latitude")),parseFloat(markers[p].getAttribute("longitude")));
	var marker = createAllMarker(p, length, point,
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("gpsTime"),
				 markers[p].getAttribute("geodate"),
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
	gmarkers[p] = marker;
	imeiArr[p] = markers[p].getAttribute("deviceIMEI");

	// add markers to map
	map.addOverlay(marker);
}

function createAllMarker(i, length, point, speed, direction, gpsTime, geodate, deviceName,
                      deviceIMEI, distance,sessionID, accuracy, isLocationValid, extraInfo,vehiIcon,altitute,latitude,longitude) {
    var icon = new GIcon();
	//icon.image = "images/RedCar.png";
	//icon.image = "images/car.png";
	if(speed > 0)
	{
		icon.image = "images/green_blink.gif";
		icon.iconSize = new GSize(22, 22);
		icon.shadowSize = new GSize(22, 20);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(19, 2);
	}
	else
	{
		
		icon.image = "images/redCircle.png";
		icon.iconSize = new GSize(10, 10);
		icon.shadowSize = new GSize(22, 20);
		icon.iconAnchor = new GPoint(6, 6);
		icon.infoWindowAnchor = new GPoint(6, 2);
	}

	var label = new ELabel(point, deviceName, "style2");
	map.addOverlay(label);

	
	//icon.shadow = "images/coolshadow_small.png";
    //icon.shadow = "images/coolshadow_small.png";
    

    var marker = new GMarker(point,icon);
	var latlng;
    var lm = "";

	GEvent.addListener(map, "maptypechanged", function() {		
		mapType=map.getCurrentMapType().getName();
		
	})
	GEvent.addListener(map, "zoomend", function() {
			zoomLevel1 = map.getZoom();
	});

	var addrs;
    var str = "</td></tr>";
	
	var oneDate = geodate.split(" ");	
	date = geodate.split("-");

	var ptText='';
	ptText='<tr><td colspan=2 align=left><input type=text name="txtMapPoint" id="txtMapPoint" value="pointName" />&nbsp;&nbsp;<a href=# onclick="addThisPoint('+latitude+','+longitude+');">&nbsp;<strong class=input_submit>Set</strong></a></td></tr>';
	  
	var devTxt='';
	devTxt='<a href=# onclick="onlineGTracker('+sessionID+','+date[0]+','+date[1]+','+date[2]+','+deviceIMEI+');">&nbsp;<strong class=input_submit>View</strong></a>';
	
	//side_bar_html += '<a href="javascript:myclick(' + (i) + ')">' + deviceName + '<\/a><br>';
	 
	str = "</td></tr><tr><td align=left colspan=2><b>Device : "+deviceName+"</b></td></tr>";
    GEvent.addListener(marker, "click", function() {
	
	geocoder.getLocations((latitude+","+longitude), function(response)																																						    {
	  if (!response || response.Status.code != 200) {
			addr="Status Code:" + response.Status.code;
      }
	  else {
        	place = response.Placemark[0];
			addr=place.address;
	  }
	  
	  	showMessage(addr);
		zoomLevel1 = 15;
		map.setCenter(point, zoomLevel1);				
						
        marker.openInfoWindowHtml(
        "<table border=0>"
        + "<tr><td align=left>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
		//+ ptText
        + "<tr><td align=left><b>Speed:</b>:" + speed +  " Kmph&nbsp;</td><td align=left><b>Distance:</b>:"+distance + " Km</td></tr>"
        + "<tr><td align=left><b>Date & Time:</b>:" + geodate+"</td><td align=left>&nbsp;</td></tr>"
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
				 markers[i].getAttribute("geodate")+','+
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
	
	
	//exeReportTable(reportData);
	showWait('Report Generated');
	}
}
function days_between(date1, date2) {

    var ONE_DAY = 1000 * 60 * 60 * 24

    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()

	var difference_ms = date1_ms - date2_ms
	    
    return Math.round(difference_ms/ONE_DAY)

}
function showMapOnDate(sDate,sTime,eDate,eTime,devId) {
	//exeRefreshTable();
	
	var std = sDate.split('-');
	std = new Date(std[2],std[1]-1,std[0]);
	
	var etd = eDate.split('-'); 
	etd = new Date(etd[2],etd[1]-1,etd[0]);
	
	diftd = days_between(etd,std);
	if(diftd <= 6 && diftd >= 0)
	{
		autoRefresh = 0;
		if(intervalID)
		{
			clearInterval(intervalID);
			
		}
		//if(document.getElementById("map").innerHTML != 'Data not available')
		//map.clearOverlays();
		
		sTime = sTime.split(":");
		sTime = (parseInt(sTime[0]*60) + parseInt(sTime[1]));
		
		eTime = eTime.split(":");
		eTime = (parseInt(eTime[0]*60) + parseInt(eTime[1]));
		document.getElementById('showAll').className = 'showlive_on';
		
		showWait('Loading data...');
		var url = 'locations.php?sDate='+sDate+'&sTime='+sTime+'&eDate='+eDate+'&eTime='+eTime+'&deviceIMEI='+devId;
		//alert(url);
		//GDownloadUrl(url, createReport);
		GDownloadUrl(url, loadGPSLocations);
	}
	else if(diftd > 6)
	{
		alert("Date difference should be less then or equal to 7");
		showWait('Date difference should be less then or equal to 7');
	}
	else if(diftd < 0)
	{
		alert("Start Date should not less then End Date");
		showWait('Start Date should not less then End Date');
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
			//progressBar = new ProgressbarControl(map, {width:150}); 
			//map.setUIToDefault();
			//map.addControl(new GOverviewMapControl());
			
			//alert('ss');
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
var points = [];

function loadGPSLocations(data, responseCode) {
	data1 = data;
	responseCode1 = responseCode;
	var totalStop=0;
	var c1=0;
	var d1;
	var dis=0;
    if (data.length == 0 || data.length == 74) {

        showMessage('There is no tracking data to view.');
        //document.getElementById("map").innerHTML = 'Data not available';
    }
    else {
       
		map.clearOverlays();
		var xmlRoot=GXml.parse(data);
		var geoData="";
		var geoPointName="";
		var root = xmlRoot.getElementsByTagName("gps");
		
		// create list of GPS data locations from our XML
		var xml = GXml.parse(data);

		// markers that we will display on Google map
		var data = xml.getElementsByTagName("locations");
		
		var length = data.length;
		if(length != 0)
		{
		
		var otherData = xml.getElementsByTagName("OtherData");

		if(otherData[0].getAttribute('geoData')!='')
		{
			 geoData=otherData[0].getAttribute('geoData');
		}
		if(otherData[0].getAttribute('geoPointName')!='')
		{
 			 geoPointName=otherData[0].getAttribute('geoPointName');
		}
		if(otherData[0].getAttribute('geoPointName')!='')
		{
			cirPts = otherData[0].getAttribute("totPt");	
			var ptDt1=cirPts.split("@");
			var totalDistance=0;
			
			for(var h = 0; h < (ptDt1.length)-1; h++) {
				  
				ptDt2 = ptDt1[h].split(",");
				points[h] = new GLatLng(parseFloat(ptDt2[0]),parseFloat(ptDt2[1]));
				
			}
		}
		if(otherData[0].getAttribute('totalDist')!='')
		{
 			 document.getElementById('totDist').innerHTML = otherData[0].getAttribute('totalDist');
		}

		
		//progressBar.start(length);
		t1=data.length;
		if(length!=0)
		{
		// center map on last marker so we can see progress during refreshes
	    if(zoomLevel1)
		{
			map.setCenter(new GLatLng(parseFloat(data[length-1].getAttribute("latitude")),parseFloat(data[length-1].getAttribute("longitude"))), zoomLevel1);
		}
		else 
		{
			 map.setCenter(new GLatLng(parseFloat(data[length-1].getAttribute("latitude")),parseFloat(data[length-1].getAttribute("longitude"))), zoomLevel);
		}
		

		/*var icon = new GIcon();
		icon.image = "http://www.google.com/intl/en_de/mapfiles/ms/icons/ltblue-dot.png";
		addIcon(icon);*/
		
		
		//points[ptDt1.length] = new GLatLng(parseFloat(ptDt2[0]),parseFloat(ptDt2[1]));
		  var points1 =[]
		  callFunction(0,map,data,length);
		  for(var i = 0; i < data.length; i++) 
		  {
			  //progressBar.updateLoader(1);
		   points1[i] = new GLatLng(parseFloat(data[i].getAttribute("latitude")), parseFloat(data[i].getAttribute("longitude")));	   
		  
		    if(i>0)
			{
			pits1 =  new GLatLng(parseFloat(data[(i-1)].getAttribute("latitude")),parseFloat(data[(i-1)].getAttribute("longitude")));
			pits2 =  new GLatLng(parseFloat(data[i].getAttribute("latitude")),parseFloat(data[i].getAttribute("longitude")));
			dist = pits1.distanceFrom(pits2) / 1000;
			totalDistance += dist;
		
				if(c1!=Math.floor(totalDistance))
				{
					//alert(c1+"!="+Math.floor(totalDistance));
					if(c1!=Math.floor(totalDistance))
					{
						callFunction(i,map,data,length);
						
					}
					c1=Math.floor(totalDistance);
				}
				
			}
		  }
		  j = length - 1;
		  //callFunction(j,map,data,length);
		  // Draw polylines between marker points
		  var poly= new GPolyline(points1, "#228B22", 2, 1);
		  map.addOverlay(poly);
		
		}

		}
		else
		{
			showMessage('There is no tracking data to view on the specified time.');
		}
    }
}
function addIcon(icon) { // Add icon properties

 icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
 icon.iconSize = new GSize(32, 32);
 icon.shadowSize = new GSize(37, 34);
 icon.iconAnchor = new GPoint(15, 34);
 icon.infoWindowAnchor = new GPoint(19, 2);
 icon.infoShadowAnchor = new GPoint(18, 25);
}
function addClickevent(marker) { // Add a click listener to the markers

 GEvent.addListener(marker, "click", function() {
  marker.openInfoWindowHtml(marker.content);
  /* Change count to continue from the last manually clicked marker
  *  Better syntax since Javascript 1.6 - Unfortunately not implemented in IE.
  *  count = gmarkers.indexOf(marker);
  */
  count = marker.nr;
  stopClick = true;
 });
 return marker;
}
var count =0;
var stopClick = false;

function anim() {
	data = data1 ;
	responseCode = responseCode1;
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
		var data = xml.getElementsByTagName("locations");


		var length = data.length;
		count++;
		   //points1[i] = new GLatLng(parseFloat(data[i].getAttribute("latitude")), parseFloat(data[i].getAttribute("longitude")));
		   
		 if(count < data.length) {
		  // Use counter as array index
		  poit = new GLatLng(parseFloat(data[count].getAttribute("latitude")), parseFloat(data[count].getAttribute("longitude")));
		  map.panTo(poit);
		  //alert(data[count]+','+count+','+map);
		  //gmarkers[count].openInfoWindowHtml( gmarkers[count].content);
		  var delay = 3400;
		  if((count+1) != data.length)
		  	pits1 =  new GLatLng(parseFloat(data[(count-1)].getAttribute("latitude")),parseFloat(data[(count-1)].getAttribute("longitude")));
				pits2 =  new GLatLng(parseFloat(data[count].getAttribute("latitude")),parseFloat(data[count].getAttribute("longitude")));
			var	dist = pits1.distanceFrom(pits2);
			//alert(dist);
		   //var dist = data[count].distanceFrom(data[count+1]);
		
		  // Adjust delay
		  if( dist < 10000 ) {
		   delay = 2000;
		  }
		  if( dist > 80000 ) {
		   delay = 4200;
		  }
		  route = setTimeout("anim()", delay);
		 }
		  else {
		  clearTimeout(route);
		  count = 0;
		  route = null;
		 }
		
		}
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
	var marker = createMarker(p, length, point,markers,
				 Math.floor(markers[p].getAttribute("speed")),
				 markers[p].getAttribute("direction"),
				 markers[p].getAttribute("gpsTime"),
				 markers[p].getAttribute("geodate"),
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

function showParameters(i1,marker1)
{
	//document.getElementById("distance").innerHTML="Traveled&nbsp;&nbsp;: "+Math.round(totalDistance)+" Km";
	document.getElementById("ctSpd").innerHTML='Speed&nbsp;:&nbsp;'+Math.round(parseFloat(marker1[i1].getAttribute("speed")))+' kmph';
	document.getElementById("dateTime").innerHTML='Date & Time&nbsp;:&nbsp;<br><br>'+marker1[i1].getAttribute("geodate");
	document.getElementById("noSate").innerHTML='Satelite(s)&nbsp;:&nbsp;'+marker1[i1].getAttribute("extraInfo");
	document.getElementById("posLatPt").innerHTML='Latitude&nbsp;:&nbsp;'+marker1[i1].getAttribute("latitude");
	document.getElementById("posLongPt").innerHTML='Logntitude&nbsp;:&nbsp;'+marker1[i1].getAttribute("longitude");
	document.getElementById("posAltPt").innerHTML='Altitude&nbsp;:&nbsp;'+marker1[i1].getAttribute("altitute");
	document.getElementById("other").innerHTML=marker1[i1].getAttribute("other");

	
}
function createMarker(i, length, point, markers, speed, direction, gpsTime, geodate, deviceName,
                      deviceIMEI, distance,sessionID, accuracy, isLocationValid, extraInfo,altitute,latitude,longitude) {
    var icon = new GIcon();
    // make the most current marker red
//alert(mapPoints+" sds "+i);
		GEvent.addListener(map, "maptypechanged", function() {
			//var newMapType = map.getCurrentMapType();
			mapType=map.getCurrentMapType().getName();
			// ...
		})
		GEvent.addListener(map, "zoomend", function() {
				zoomLevel1 = map.getZoom();
	
		});
		
		if ((i == length - 1)  && (mapPoints==2 || mapPoints==1 || mapPoints==5))
		{
			
				//var newIcon = MapIconMaker.createLabeledMarkerIcon({width: 12, height: 15, addStar: true, label: "C", primaryColor: "#ff0000"});
				icon.image = "marker/icons/dd-end.png";
				//icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(18, 22);
				
				//var label = new ELabel(point, deviceName, "style2");
				//map.addOverlay(label);

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
				//var newIcon = MapIconMaker.createLabeledMarkerIcon({width: 12, height: 15, addStar: true, label: "S", primaryColor: "#00ff00"});
				icon.image = "marker/icons/dd-start.png";
				//icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(20, 20);
		}
		else if(mapPoints==1|| mapPoints==5)
		{
				//var newIcon = MapIconMaker.createLabeledMarkerIcon({width: 10, height: 10, addStar: false, label: "45", primaryColor: "#0FC0FF"});
				if(speed > 0)
				{
					if(speed < 10)
						icon.image = "marker/numeric/black0"+speed+".png";
					else
						icon.image = "marker/numeric/black"+speed+".png";
				}
				else
				{
					icon.image = "marker/numeric/red0"+speed+".png";
				}
				//icon.shadow = "images/coolshadow_small.png";
				icon.iconSize = new GSize(22, 22);
				
		}
		
		//icon.shadowSize = new GSize(22, 20);
		icon.iconAnchor = new GPoint(6, 20);
		icon.infoWindowAnchor = new GPoint(5, 1);
		var marker = new GMarker(point,icon);
			
		
	
	//var newIcon = MapIconMaker.createLabeledMarkerIcon({addStar: true, label: "a", primaryColor: "#00ff00"});
 
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
	
	

	// this creates the pop up bubble that displays info when a user clicks on a marker
    GEvent.addListener(marker, "click", function() {

	showParameters(i,markers);
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
	var str= "</td></tr><tr><td align=left colspan=2><b>Device : "+deviceName+"</b></td></tr>";
	var ptText='<tr><td colspan=2 align=center><input type=text name="txtMapPoint" id="txtMapPoint" value="pointName" />&nbsp;&nbsp;<a href=# onclick="addThisPoint('+latitude+','+longitude+');">&nbsp;<strong class=input_submit>Set</strong></a></td></tr>';
	  
        marker.openInfoWindowHtml(
		 "<table border=0>"
        + "<tr><td align=left>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=left><b>Speed:</b>:" + speed +  " Kmph&nbsp;</td><td align=left><b>Distance:</b>:"+distance + " Km</td></tr>"
        + "<tr><td align=left><b>Date & Time:</b>:" + geodate+"</td><td align=left>&nbsp;</td></tr>"
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
function getPolyLatLngs(latlng) {
  var mapNormalProj = G_NORMAL_MAP.getProjection();
  var mapZoom = map.getZoom();
  var centerPixel = mapNormalProj.fromLatLngToPixel(latlng, mapZoom);

  var polyNumSides = 6;
  var polySideLength = 60;
  var polyRadius = 40;
  var polyLatLngs = [];
  for (var a = 0; a<(polyNumSides+1); a++) {
	var aRad = polySideLength*a*(Math.PI/180);
	var pixelX = centerPixel.x + polyRadius * Math.cos(aRad);
	var pixelY = centerPixel.y + polyRadius * Math.sin(aRad);
	var polyPixel = new GPoint(pixelX,pixelY);
	var polyLatLng = mapNormalProj.fromPixelToLatLng(polyPixel,mapZoom);
	polyLatLngs.push(polyLatLng);
  }
  return polyLatLngs;
}
function addThisPoint(lt1,lng1)
{
	var flg1=validThis();
	var latlng = new GLatLng(parseFloat(lt1),parseFloat(lng1));
	var polyLatLngs = getPolyLatLngs(latlng);
	if(flg1)
	{
		ajax1.requestFile = 'ajax_server.php?add_stop_name='+document.getElementById('txtMapPoint').value+'&mapPt='+polyLatLngs;
		alert(ajax1.requestFile);
		/*ajax1.onCompletion = function(){
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
		ajax1.runAJAX();*/
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


