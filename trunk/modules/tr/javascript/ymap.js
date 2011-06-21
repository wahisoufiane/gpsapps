// Please leave the link below with the source code, thank you.
// http://www.websmithing.com/portal/Programming/tabid/55/articleType/ArticleView/articleId/6/Google-Map-GPS-Cell-Phone-Tracker-Version-2.aspx
var interval=5;
var data1;
var responseCode1;
var ptno=/^[0-9]*$/;
var myMarker;
var t1=null;

function loadRoutes(data, responseCode) {
    if (data.length == 0) {
        showMessage('There are no routes available to view.');
        map.innerHTML = '';
    }
    else {
        // get list of routes
        try //Internet Explorer
		  {
		  xml=new ActiveXObject("Microsoft.XMLDOM");
		  xml.async="false";
		  xml.loadXML(data);
		  }
		catch(e)
		  {
		  try //Firefox, Mozilla, Opera, etc.
		  {
		  parser=new DOMParser();
		  xml=parser.parseFromString(data,"text/xml");
		  }
		  catch(e)
		  {
		  alert(e.message);
		  return;
		  }
		}
		//var xml = GXml.parse(data);
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
			option.innerHTML = routes[i].getAttribute("phoneNumber") ;
			if(routes[i].getAttribute("phoneNumber")==routes[i].getAttribute("select"))
			{
				option.setAttribute('selected', 'selected');
				var url = 'getgpslocations_offline.php?date_offline='+routes[i].getAttribute("date") + '&sessionID=' + routes[i].getAttribute("sessionID") + '&phoneNumber=' + routes[i].getAttribute("phoneNumber");
			    //GDownloadUrl(url, loadGPSLocations);
				
				ajax1.requestFile = url;
				ajax1.onCompletion = function(){loadGPSLocations()};
				ajax1.runAJAX();
			}
//			option.innerHTML = routes[i].getAttribute("phoneNumber") + "  " + routes[i].getAttribute("times");
			
			
			//alert(option);

			routeSelect.appendChild(option);
        }

        // need to reset this for firefox
        //routeSelect.selectedIndex = 0;

        //hideWait();
        showMessage('Please select a route below.');
    }

}

// this will get the map and route, the route is selected from the dropdown box - for offline details
function getRouteForMap_offline(date_offline,vehicle_no) {
//	alert(date_offline+","+vehicle_no);
    if (date_offline) {
        showWait('Getting map...');
	    var url = 'getgpslocations_offline.php?date_offline='+date_offline + routeSelect.options[routeSelect.selectedIndex].value;
		//document.write(url);
	    ajax1.requestFile = url;
		ajax1.onCompletion = function(){loadGPSLocations()};
		ajax1.runAJAX();
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
	if (true) 
	{
			map = new YMap(document.getElementById("map")); 
			//var yPoint = new YGeoPoint(36.301984,-88.908749);
			//var yMarker = createYahooMarker(yPoint, "This is a message box.");
			map.addPanControl();
			map.addZoomLong();
			map.addTypeControl();
			// Set map type to either of: YAHOO_MAP_SAT, YAHOO_MAP_HYB, YAHOO_MAP_REG
			map.setMapType(YAHOO_MAP_REG);
			//map.drawZoomAndCenter( "38237", 6 );
			//map.addOverlay( yMarker );
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
function loadGPSLocations() {
	data = ajax1.response;
	data1=data;
	//responseCode1 = responseCode;
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


		 try //Internet Explorer
		  {
		  xmlRoot=new ActiveXObject("Microsoft.XMLDOM");
		  xmlRoot.async="false";
		  xmlRoot.loadXML(data);
		  }
		catch(e)
		  {
		  try //Firefox, Mozilla, Opera, etc.
		  {
		  parser=new DOMParser();
		  xmlRoot=parser.parseFromString(data,"text/xml");
		  }
		  catch(e)
		  {
		  alert(e.message);
		  return;
		  }
		}

		//var xmlRoot=GXml.parse(data);
		var geoData="";
		var root = xmlRoot.getElementsByTagName("gps");
		if(root[0].getAttribute('dImg')=='#')
			document.getElementById('dimg').innerHTML='<img src="../profile_images/avater.gif" height="100" width="75" border="0" />';
		else
			document.getElementById('dimg').innerHTML='<img src="../profile_images/'+root[0].getAttribute('dImg')+'" height="100" width="75" border="0" />';
				
		document.getElementById('fname').innerHTML=root[0].getAttribute('dFName');
		document.getElementById('lname').innerHTML=root[0].getAttribute('dLName');
		document.getElementById('phone').innerHTML=root[0].getAttribute('dPhone');
		
		
		if(root[0].getAttribute('geoData')!='')
		{
			 geoData=root[0].getAttribute('geoData');
		}

		// create list of GPS data locations from our XML
            // var xmlRoot = GXml.parse(data);

            // markers that we will display on Google map
            var markers = xmlRoot.getElementsByTagName("locations");
				myMarker = xmlRoot.getElementsByTagName("locations");
            // get rid of the wait gif
            //hideWait();

            var length = markers.length;
			map.drawZoomAndCenter(new YGeoPoint(convertLat(markers[length-1].getAttribute("latitude")),convertLong(markers[length-1].getAttribute("longitude"))),zoomLevel);

			t1=markers.length;
			if(length!=0)
			{
	        // center map on last marker so we can see progress during refreshes
	        //map.setCenter(new YGeoPoint(convertLat(markers[length-1].getAttribute("latitude")),convertLong(markers[length-1].getAttribute("longitude"))), zoomLevel);
			

			callFunction(0,map,markers,length);
			
		document.getElementById("distance").innerHTML="&nbsp;&nbsp;<strong>"+callDistance(map,markers)+" Km</strong>";
		
		j = length - 1;
		
		callFunction(j,map,markers,length);
		
							
		var sts=markers[markers.length-1].getAttribute("speed");
		var ignit=markers[markers.length-1].getAttribute("extraInfo");
		//document.getElementById("vehiId").innerHTML=markers[0].getAttribute("phoneNumber");
		
		if(ignit==0 && for_Poly==0)
		{
			document.getElementById("ignit").innerHTML="&nbsp;&nbsp;<strong>Off</strong>";
		}
		if(ignit==1 && for_Poly==0)
		{
			document.getElementById("ignit").innerHTML="&nbsp;&nbsp;<strong>On</strong>";
		}

		
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
function createGeo(point4,icon4,html4) {
	//alert(point4+","+icon4+","+html4);
	var marker5 = new YMarker(point4,{icon:icon4});
	YEvent.Capture(marker5, "click", function() {
	  marker5.openSmartWindow(html4+"<br>"+point4);
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

//	if(spt==0)
//	{
//		spt1=myMarker[0].getAttribute("gpsTime");
//	}
//	for (var p5 = 0; p5 < (length2-1); p5++) 
//	{
//
//		if((t5!=convertLat(myMarker[p5].getAttribute("latitude"))) && (t6!=convertLong(myMarker[p5].getAttribute("longitude"))))
//		{
//			if(p5==0)
//			{
//				lat3=parseCoordinate(myMarker[0].getAttribute("latitude"));
//				lat4=parseCoordinate(myMarker[p5+1].getAttribute("latitude"));
//				long3=parseCoordinate(myMarker[0].getAttribute("longitude"));
//				long4=parseCoordinate(myMarker[p5+1].getAttribute("longitude"));
//				dis1=parseInt(comma2point(Vincenty_Distance(lat3,long3,lat4,long4,0)));
//			}
//			else if(p5 > 0 && p5 < (length2-2))
//			{
//				lat3=parseCoordinate(myMarker[p5+1].getAttribute("latitude"));
//				lat4=parseCoordinate(myMarker[p5+2].getAttribute("latitude"));
//				long3=parseCoordinate(myMarker[p5+1].getAttribute("longitude"));
//				long4=parseCoordinate(myMarker[p5+2].getAttribute("longitude"));
//				dis1+=parseInt(comma2point(Vincenty_Distance(lat3,long3,lat4,long4,0)));
//			}
//			if(tmp1!=(Math.round(dis1/1000)))
//			{
//				if(tmp1>=1)
//				{
//					if(tmp1==spt )
//					{
//						spt1=myMarker[p5].getAttribute("gpsTime");
//						t2++;
//					}
//					if(tmp1==ept)
//					{
//						ept1=myMarker[p5].getAttribute("gpsTime");
//						t2++;
//					}
//				}
//				tmp1=Math.round(dis1/1000);
//				
//			}
//			t5=convertLat(myMarker[p5].getAttribute("latitude"));
//			t6=convertLong(myMarker[p5].getAttribute("longitude"));	
//			
//		}
//
//	}
//	if(ept==t1)
//	{
//		ept1=myMarker[length2-1].getAttribute("gpsTime");
//	}
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
function callDistance(map,markers)
{
	var length1 = markers.length;
	var dis=0;
	var t3=0.0;
	var t4=0.0;
	var tmp=0;
	var t=0;
	var pts = [];
	var stopIn=0;

	for (var p1 = 0; p1 < (length1-1); p1++) 
	{

		if((t3!=convertLat(markers[p1].getAttribute("latitude"))) && (t4!=convertLong(markers[p1].getAttribute("longitude"))))
		{
			if(p1==0)
			{
				lat1=parseCoordinate(markers[0].getAttribute("latitude"));
				lat2=parseCoordinate(markers[p1+1].getAttribute("latitude"));
				long1=parseCoordinate(markers[0].getAttribute("longitude"));
				long2=parseCoordinate(markers[p1+1].getAttribute("longitude"));
				dis=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
				pts[t] = new YGeoPoint(convertLat(markers[0].getAttribute("latitude")),convertLong(markers[0].getAttribute("longitude")));
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
			if((Math.floor(markers[p1].getAttribute("speed"))==0 && markers[p1].getAttribute("extraInfo")==1) && p1>0)
			{
					//stopIn++;
//					var icon4 = new YImage();
//					icon4.src = "images/stop.png";
//					//icon4.shadow = "images/coolshadow_small.png";
//					icon4.size = new YSize(12, 20);
//					//icon4.iconAnchor = new GPoint(6, 20);
//					//icon4.infoWindowAnchor = new GPoint(5, 1);
//				
//					var thisPt=new YGeoPoint(convertLat(markers[p1].getAttribute("latitude")),convertLong(markers[p1].getAttribute("longitude")));
//					var stoptime=dateTimeConvert(markers[p1].getAttribute("gpsTime"));
//					var marker6 =createGeo(thisPt,icon4,"<b>Stop </b>"+stopIn+"<br/>"+stoptime);
//					map.addOverlay(marker6);
			}
			if(tmp!=(Math.round(dis/1000)))
			{
				if(tmp>=1)
				{
					callFunction(p1,map,markers,length1);
					pts[t] = new YGeoPoint(convertLat(markers[p1].getAttribute("latitude")),convertLong(markers[p1].getAttribute("longitude")));
					t++;
				}
				tmp=Math.round(dis/1000);
				
			}
			t3=convertLat(markers[p1].getAttribute("latitude"));
			t4=convertLong(markers[p1].getAttribute("longitude"));	
			
		}
	
	}
	//alert(stopIn);
	pts[t] = new YGeoPoint(convertLat(markers[p1].getAttribute("latitude")),convertLong(markers[p1].getAttribute("longitude")));
	//alert(pts.length);
	//if(for_Poly==1)
//	{
//		var poly = new BDCCArrowedPolyline(pts,"#FF0000",4,0.3,null,1,7,"#0000FF",2,0.5);
//		map.addOverlay(poly);
////		var poly = new GPolyline(pts,"#A2D84C",5,1,"#A2D84C",3.5,{clickable:false});
////					map.addOverlay(poly);
//	}

	//alert(c2+","+length);
	return Math.round(dis/1000);
}
function callFunction(p,map,markers,length)
{
	var point = new YGeoPoint(convertLat(markers[p].getAttribute("latitude")),convertLong(markers[p].getAttribute("longitude")));
	
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
				 convertLat(markers[p].getAttribute("latitude")),
				 convertLong(markers[p].getAttribute("longitude")));

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

	 var icon = new YImage();

    // make the most current marker red
	if(mapPoints==2)
	{
	if(i == (length - 1))
	{
			icon.src = "images/red_anim.gif";
			icon.shadow = "images/coolshadow_small.png";
	}
	else if(i == 0)
	{	
			icon.src = "images/green_small.png";
	}
	}
	else
	{
	if (i == length - 1) 
	{
		//alert(mapPoints);
			icon.src = "images/red_anim.gif";
    }
    else if(i == 0)
	{	
			icon.src = "images/green_small.png";
	}
	else
	{
			icon.src = "images/blue_small.png";
    }
	icon.shadow = "images/coolshadow_small.png";
	}

    //icon.shadow = "images/coolshadow_small.png";
    icon.size = new YSize(12, 20);
    //icon.iconAnchor = new GPoint(6, 20);
    //icon.infoWindowAnchor = new GPoint(5, 1);

    var marker = new YMarker(point,icon);
	
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

    // when a user clicks on last marker, let them know it's final one
    if (i == length - 1) {
        str = "</td></tr><tr><td align=left>&nbsp;</td><td><b>Final location</b></td></tr>";
    }
	else if (i == 0) {
        str = "</td></tr><tr><td align=left>&nbsp;</td><td><b>Start point</b></td></tr>";
    }

	// this creates the pop up bubble that displays info when a user clicks on a marker
    YEvent.Capture(marker, EventsList.MouseClick,
				   function() {
	
	if(document.getElementById('txtFrmPt').value=='')
		document.getElementById('txtFrmPt').value=i;
	else
		document.getElementById('txtToPt').value=i;
	
	var ing=null;																																					 
	if(extraInfo==0)
	ing="off";
	else ing="On";
	
												 
        marker.openSmartWindow(
        "<table border=0 style=\"font-size:75%;font-family:arial,helvetica,sans-serif;\">"
        + "<tr><td align=right>&nbsp;</td><td>&nbsp;</td><td rowspan=2 align=right>"
        + "<img src=images/" + getCompassImage(direction) + ".jpg alt= />"
        + str
        + "<tr><td align=right><b>Speed:</b></td><td>" + speed +  " Kmph&nbsp;&nbsp;<b>Ignition:&nbsp;</b>"+ing+"</td></tr>"
//        + "<tr><td align=right>Distance:</td><td>" +Math.round(distance/1000) +  " Km</td><td>&nbsp;</td></tr>"
        + "<tr><td align=right><b>Date & Time:</b></td><td colspan=2>" + gpsTime +  "</td></tr>"
        + "<tr><td align=right><b>Latitude:</b></td><td>" + latitude + "&nbsp;&nbsp;<b>Longitude:&nbsp;</b>" + longitude +"</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Longitude:</td><td>" + longitude + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right><b>Address:</b></td><td>" + addr + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Accuracy:</td><td>" + accuracy + " ft</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Location Valid:</td><td>" + isLocationValid + "</td><td>&nbsp;</td></tr>"
//        + "<tr><td align=right>Extra Info:</td><td>" + extraInfo + "</td><td>&nbsp;</td></tr>"

        + "</table>"
        );
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
     messages.innerHTML = 'GPS Tracker: <b>' + message + '</b>';
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
    messages.innerHTML = 'GPS Tracker';
}



function convert(v)
{
D = v.split(" ");
M = D[1].split(".");	//(39.225′) = 39′		56
s =	((parseFloat(M[1])/10000)*60);		//s = (39.225 − 39) × 60″ = 0.225 × 60″ = 13.5″
res=D[0]+" "+M[0]+" "+s+" "+D[2];
return (res);
}

function convertLat(lat)
{

lat1=convert(lat).split(" ");
    // Retrieve Lat and Lon information
    var LatDeg = lat1[0];
    var LatMin = lat1[1];
    var LatSec = lat1[2];

    // Assume the value to be zero if the user does not enter value
    if (LatDeg==null)
      LatDeg=0;
    if (LatMin==null) {
      LatMin=0;
    }
    if (LatSec==null) {
      LatSec=0;
    }

    // Check if any error occurred
   if (LatDeg != Math.round(LatDeg) || LatMin != Math.round(LatMin) ) {
      alert("ERROR");
    } else if (LatDeg < -90 || LatDeg > 90 || LatMin < -60 || LatMin > 60 || LatSec < -60 || LatSec > 60 ) {
      alert("ERROR");
    } else {
    // If no error, then go on

    // Retrieve the latitude direction for Degrees Decimal
        var LatDMSDirect = lat1[3];

    // If the user does not click direct button,
    // then a postive latitude value regards North, negative latitude value regards South
    if (LatDMSDirect==null) {
      if (LatDeg<0 || Location.LatDeg.value=="-0") {
        LatDMSDirect = "S";
        //Location.LatDMSDirect[1].click();
      }
      else {
        LatDMSDirect ="N";
        //Location.LatDMSDirect[0].click();
      }
    }

    // Change to absolute value
    LatDeg = Math.abs(LatDeg);
    LatMin = Math.abs(LatMin);
    LatSec = Math.abs(LatSec);
    //setAllEnabled(Location);

    // Convert to Decimal Degrees Representation
    var lat = LatDeg + (LatMin/60) + (LatSec / 60 / 60);
    if ( lat <= 90 && lat >=0 )
    {

      // Rounding off
      lat = (Math.round(lat*1000000)/1000000);

	  return (lat);
      } else
        alert("ERROR!!");
    }
}

function convertLong(lng)
{

lng1=convert(lng).split(" ");
    // Retrieve Lat and Lon information
    var LonDeg = lng1[0];
    var LonMin = lng1[1];
    var LonSec = lng1[2];

    // Assume the value to be zero if the user does not enter value
    if (LonDeg==null)
      LonDeg=0;
    if (LonMin==null) {
      LonMin=0
    }
    if (LonSec==null){
      LonSec=0;
    }

    // Check if any error occurred
   if (LonDeg != Math.round(LonDeg) || LonMin != Math.round(LonMin)) {
      alert("ERROR");
    } else if (LonDeg < -180 || LonDeg > 180 || LonMin < -60 || LonMin > 60 || LonSec < -60 || LonSec > 60) {
      alert("ERROR");
    } else {
    // If no error, then go on

    // Retrieve the longitude direction for Deg/Min/Sec
        var LonDMSDirect = lng1[3];

    // If the user does not click direct button,
    // then a positive latitude value regards East, negative latitude value regards West
    if (LonDMSDirect==null) {
      if (LonDeg<0 || Location.LonDeg.value=="-0") {
        LonDMSDirect = "W";
        //Location.LonDMSDirect[1].click();
      } else {
        LonDMSDirect ="E";
        //Location.LonDMSDirect[0].click();
      }
    }

    // Change to absolute value
    LonDeg = Math.abs(LonDeg);
    LonMin = Math.abs(LonMin);
    LonSec = Math.abs(LonSec);
    //setAllEnabled(Location);

    // Convert to Decimal Degrees Representation
    var lon = LonDeg + (LonMin/60) + (LonSec / 60 / 60);
    if ( lon <= 180 && lon >= 0 )
    {

      // Rounding off
      lon = (Math.round(lon*1000000)/1000000);

	  return (lon);
      } else
        alert("ERROR!!");
    }
}
