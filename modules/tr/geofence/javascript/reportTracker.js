// Please leave the link below with the source code, thank you.
// http://www.websmithing.com/portal/Programming/tabid/55/articleType/ArticleView/articleId/6/Google-Map-GPS-Cell-Phone-Tracker-Version-2.aspx

var c2=0;
var c3=0;

function saveCSV(data) {
	//alert(data);
	var xmlDoc;
	
    if (data.length == 0) {
        //showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = '';
    }
    else {
		try //Internet Explorer
		  {
		  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		  xmlDoc.async="false";
		  xmlDoc.loadXML(data);
		  }
		catch(e)
		  {
		  try //Firefox, Mozilla, Opera, etc.
		  {
		  parser=new DOMParser();
		  xmlDoc=parser.parseFromString(data,"text/xml");
		  }
		  catch(e)
		  {
		  alert(e.message);
		  return;
		  }
		}
	
		var markers = xmlDoc.getElementsByTagName('locations');
		var length = markers.length;
		if(length!=0)
		findAddress(markers);
		else alert('No data found');
		//callDistance(markers)
	
        }
		//loadAddress(markers);
        //showMessage(routeSelect);
}


function loadGPSLocations(data) {
	data1 = data;
	//responseCode1 = responseCode;
	var totalStop=0;
	var t1=0.0;
	var t2=0.0;
	var c1=0;
	var d1;
	var dis1=0;
	var xmlDoc;
	
	var pts = [];
	
    if (data.length == 0) {
        //showMessage('There is no tracking data to view.');
        document.getElementById("map").innerHTML = '';
    }
    else {
		try //Internet Explorer
		  {
		  xmlDoc=new ActiveXObject("Microsoft.XMLDOM");
		  xmlDoc.async="false";
		  xmlDoc.loadXML(data);
		  }
		catch(e)
		  {
		  try //Firefox, Mozilla, Opera, etc.
		  {
		  parser=new DOMParser();
		  xmlDoc=parser.parseFromString(data,"text/xml");
		  }
		  catch(e)
		  {
		  alert(e.message);
		  return;
		  }
		}
	
		var markers = xmlDoc.getElementsByTagName('locations');
		var length = markers.length;
		if(length!=0)
		findAddress(markers);
		else
		{
			if(document.getElementById('selVehicle').value==0)
			alert('Select vehicle no & date');
			else alert('No data found');
		}
		//callDistance(markers)
	
        }

		//loadAddress(markers);
        //showMessage(routeSelect);
}
function showAddress(response) {
      //map.clearOverlays();
	 // alert(inc);
	  if(response.Status.code==620)
	  {
		  findAddress(markers);
	  }
//      if (!response || response.Status.code != 200) {
//        //alert("Status Code:" + response.Status.code);
//		locations[inc]="Status Code:" + response.Status.code;
//		inc++;
//      }
	  else {
        place = response.Placemark[0];
//		locations[inc]=place.address;
//		inc++;
	  }
	  
	  //alert(inc+","+c2);
	  if(inc==c2)
	  {
	  	//dispArray(markers,locations);
	  }
	  else
	  {
		  document.getElementById('addrs_data').innerHTML='<img src="images/ajax-loader.gif"' +
                    'style="position:absolute;top:225px;left:325px;">';
	  }
	  
}
function pause(numberMillis)
{
var now = new Date();
var exitTime = now.getTime() + numberMillis;
while (true)
{
now = new Date();
if (now.getTime() > exitTime)
return;
}
}

function makeDelay()
{
			clearTimeout(t);

//	document.getElementById('addrs').innerHTML='<img src="images/ajax-loader.gif"' +
//                    'style="position:absolute;top:225px;left:325px;">';
}
function findAddress(markers)
{
	var length1 = markers.length;
	var dis=0;
	var t3=0.0;
	var t4=0.0;
	var tmp=0;
	
	//geocoder.getLocations((parseFloat(markers[0].getAttribute("latitude"))+","+parseFloat(markers[0].getAttribute("longitude"))), showAddress);
	val[c2]=0;
	c2++;
	for (var p4 = 0; p4 < (length1-1); p4++) 
	{

		if((t3!=convertLat(markers[p4].getAttribute("latitude"))) && (t4!=convertLong(markers[p4].getAttribute("longitude"))))
		{
			if(p4==0)
			{
				lat1=parseCoordinate(markers[0].getAttribute("latitude"));
				lat2=parseCoordinate(markers[p4+1].getAttribute("latitude"));
				long1=parseCoordinate(markers[0].getAttribute("longitude"));
				long2=parseCoordinate(markers[p4+1].getAttribute("longitude"));
				dis=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));

			}
			else if(p4 > 0 && p4 < (length1-2))
			{
				lat1=parseCoordinate(markers[p4+1].getAttribute("latitude"));
				lat2=parseCoordinate(markers[p4+2].getAttribute("latitude"));
				long1=parseCoordinate(markers[p4+1].getAttribute("longitude"));
				long2=parseCoordinate(markers[p4+2].getAttribute("longitude"));
				dis+=parseInt(comma2point(Vincenty_Distance(lat1,long1,lat2,long2,0)));
			}
			if(tmp!=(Math.round(dis/1000)))
			{
				if(tmp>=1)
				{
					val[c2]=p4;
						//geocoder.getLocations((parseFloat(markers[p4].getAttribute("latitude"))+","+parseFloat(markers[p4].getAttribute("longitude"))), showAddress);
						//pause(1000);
						//t=setTimeout("makeDelay()",1000)
						//alert(inc+" ss "+c2);
						c2++;

				}
				tmp=Math.round(dis/1000);
				
			}
			t3=convertLat(markers[p4].getAttribute("latitude"));
			t4=convertLong(markers[p4].getAttribute("longitude"));	
			
		}
	}
		//geocoder.getLocations((parseFloat(markers[length1-1].getAttribute("latitude"))+","+parseFloat(markers[length1-1].getAttribute("longitude"))), showAddress);
	val[c2]=length1-1;
	dispArray(markers,val)
}
function findMyLoca(lat1,long1,div)
{
	geocoder.getLocations((lat1+","+long1), function(response)																																						   {
	 var addr="";
	  if (!response || response.Status.code != 200) {
		  if(response.Status.code == 620)
		  {
			  //alert("Status Code:" + response.Status.code);
			  findMyLoca(lat1,long1,div);
			  pause(100);
		  }
        
		//document.getElementById(div).innerHTML="Status Code:" + response.Status.code;
      }
	  else {
			place = response.Placemark[0];
			addr=place.address;
			addr=addr.split(",");
			//alert(place.address);
			document.getElementById(div).innerHTML=addr[0]+","+addr[1]+","+place.AddressDetails.Country.CountryNameCode;
			locations[inc]=place.address;
			inc++;
			//pause(1000);
			//return addr;
	  }
	});
}
function dispArray(markers,val)
{
	c2=0;
	inc=0;
	var locat;
	var addr1=null;
	//alert(val.length+","+loca.length);
	dataStr='<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" id=\"Grid_tbl\">';
    dataStr+='<tr class=\"\">';
    dataStr+='<td width=\"15\">S.No</td>';
    dataStr+='<td width=\"15\">Speed (km)</td>';
    dataStr+='<td width=\"165\">Address</td>';
    dataStr+='<td width=\"100\">Date & Time</td>';
    dataStr+='<td width=\"60\">Vehicle No.</td>';
    dataStr+='<td width=\"60\">Start Point</td>';
    dataStr+='<td width=\"60\" style=\"border:0px;\">End Point</td>';
//  dataStr+='<td width=\"60\">Address</td></tr>';	
	for(h=0;h<val.length;h++)
	{
		cd=val[h];
		d1=dateTimeConvert(markers[cd].getAttribute("gpsTime"));
		
	var lat1=convertLat(markers[cd].getAttribute("latitude"));
	var long1=convertLat(markers[cd].getAttribute("longitude"));
	
	
	//alert(addr1);
		//dataStr+='<tr class="odd">';
		if((h%2)==0)
		clas="even_row";
		else
		clas="odd_row";

		dataStr+='<tr class="'+clas+'")>';
		dataStr+='<td>'+(h+1)+'</td>';
		dataStr+='<td>'+Math.floor(markers[cd].getAttribute("speed"))+'</td>';
		dataStr+='<td id=\"myap'+h+'\">'+findMyLoca(lat1,long1,'myap'+h)+'</td>';
		dataStr+='<td>'+d1+'</td>';
		dataStr+='<td>'+markers[cd].getAttribute("phoneNumber")+'</td>';
		dataStr+='<td>'+convertLat(markers[cd].getAttribute("latitude"))+'</td>';
		dataStr+='<td>'+convertLong(markers[cd].getAttribute("longitude"))+'</td>';

		
	dataStr+='</tr>';
	
	}
	//alert(dataStr);
		dataStr+='</table>';
	for(var k=0;k<locations.length;k++)
	{
		
		document.getElementById('myap'+k).innerHTML=locations[k];
		//dataStr+='<td>'+locations[k]+'</td>';
	}
	document.getElementById('addrs_data').innerHTML=dataStr;
	document.getElementById('valData').value=val;

	//loadAddress();
}
function executeFromTracker()
{	
  if(eval(ajax1.response) == 1)
  	window.location.href="succMsg.php?img_succ=1&tracker_succ_msg=1";
  else
  	window.location.href="succMsg.php?img_succ=0&tracker_succ_msg=0";
}

function loadAddress(markers)
{
	alert('completed');
	for(var k=0;k<locations.length;k++)
	{
		if(k==0)
			document.getElementById('addrs_data').innerHTML=(k+1)+". "+locations[k]+"<br>";
		else
			document.getElementById('addrs_data').innerHTML+=(k+1)+". "+locations[k]+"<br>";
	}
	dispArray(markers,locations);
	//setTimeout(dispArray(markers,locations),1000);
//	alert(locations);
}


function dateTimeConvert(str)
{
	mer="";
	d1=str.split(" ");
	dat=d1[0].split("-");
	d2=d1[1].split(":");
	d4=parseFloat(d2[1])+30;
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
			
			//alert("ss");
			d3=d5+":0"+d6;
			
		}
		else 
		{
			d3=d5+":"+d6;
		}
	//}
	
	return (ct+"-"+dat[1]+"-"+dat[0]+" "+d3+" "+mer);
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
function converter(lat2,lng2)
{
lat1=convert(lat2).split(" ");
lng1=convert(lng2).split(" ");
    // Retrieve Lat and Lon information
    var LatDeg = lat1[0];
    var LatMin = lat1[1];
    var LatSec = lat1[2];
    var LonDeg = lng1[0];
    var LonMin = lng1[1];
    var LonSec = lng1[2];

    // Assume the value to be zero if the user does not enter value
    if (LatDeg==null)
      LatDeg=0;
    if (LatMin==null) {
      LatMin=0;
    }
    if (LatSec==null) {
      LatSec=0;
    }
    if (LonDeg==null)
      LonDeg=0;
    if (LonMin==null) {
      LonMin=0
    }
    if (LonSec==null){
      LonSec=0;
    }

    // Check if any error occurred
   if (LatDeg != Math.round(LatDeg) || LonDeg != Math.round(LonDeg) || LatMin != Math.round(LatMin) || LonMin != Math.round(LonMin)) {
      alert("ERROR");
    } else if (LatDeg < -90 || LatDeg > 90 || LonDeg < -180 || LonDeg > 180 || LatMin < -60 || LatMin > 60 || LonMin < -60 || LonMin > 60 || LatSec < -60 || LatSec > 60 || LonSec < -60 || LonSec > 60) {
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
    LatDeg = Math.abs(LatDeg);
    LonDeg = Math.abs(LonDeg);
    LatMin = Math.abs(LatMin);
    LonMin = Math.abs(LonMin);
    LatSec = Math.abs(LatSec);
    LonSec = Math.abs(LonSec);
    //setAllEnabled(Location);

    // Convert to Decimal Degrees Representation
    var lat = LatDeg + (LatMin/60) + (LatSec / 60 / 60);
    var lon = LonDeg + (LonMin/60) + (LonSec / 60 / 60);
    if ( lat <= 90 && lon <= 180 && lat >=0 && lon >= 0 )
    {

      // Rounding off
      lat = (Math.round(lat*1000000)/1000000);
      lon = (Math.round(lon*1000000)/1000000);
	//alert(lat+","+lon);

	 return (lat+","+lon);
      } else
        alert("ERROR!!");
    }
}