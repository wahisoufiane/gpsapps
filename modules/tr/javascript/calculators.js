function DMS_to_Degrees(d,m,s,dir) {
	var deg = parseFloat(Math.abs(d)) + parseFloat(Math.abs(m)/60) + parseFloat(Math.abs(s)/3600);
	if (dir == 'W' || dir == 'S') { deg = parseFloat(-1*deg); }
	if (d == '' && m == '' && s == '') { deg = ''; }
	return comma2point(deg);
}

function Degrees_to_DMM(deg,type,spacer,minutemark) {
	if (!deg.toString().match(/[0-9]/)) { return ''; }
	if (!spacer) { spacer = ''; }
	if (!minutemark) { minutemark = ''; }
	if (type == 'lat') {	
		if (parseFloat(deg) < 0) { var dir = 'S'; } else { var dir = 'N'; }
	} else {
		if (parseFloat(deg) < 0) { var dir = 'W'; } else { var dir = 'E'; }
	}
	var d = Math.floor(Math.abs(parseFloat(deg)));
	var m = 60 * (Math.abs(parseFloat(deg)) - parseFloat(d))
	m = Math.round(1000000 * m) / 1000000;
	if (type == 'lon') {
		if (d < 10) { d = '00'+d; } else if (d < 100) { d = '0'+d; }
	} else {
		if (d < 10) { d = '0'+d; }
	}
	if (parseFloat(m) == Math.floor(parseFloat(m))) { m = m + '.0'; }
	return dir + spacer + d + String.fromCharCode(176) + spacer + comma2point(m) + minutemark;
}

function Degrees_to_DMS(deg,type,spacer) {
	if (!deg.toString().match(/[0-9]/)) { return ''; }
	if (!spacer) { spacer = ''; }
	if (type == 'lat') {	
		if (parseFloat(deg) < 0) { var dir = 'S'; } else { var dir = 'N'; }
	} else {
		if (parseFloat(deg) < 0) { var dir = 'W'; } else { var dir = 'E'; }
	}
	var d = Math.floor(Math.abs(parseFloat(deg)));
	var mmm = 60 * (Math.abs(parseFloat(deg)) - parseFloat(d))
	mmm = Math.round(1000000 * mmm) / 1000000;
	var m = Math.floor(parseFloat(mmm));
	var s = 60 * (parseFloat(mmm) - parseFloat(m))
	s = Math.round(1000 * s) / 1000;
	return dir + spacer + d + String.fromCharCode(176) + spacer + m + '\'' + spacer + comma2point(s) + '"';
}

function deg2rad (deg) {
	return (parseFloat(comma2point(deg)) * 3.14159265358979/180);
}
function rad2deg (radians) {
	return (Math.round(10000000 * parseFloat(radians) * 180/3.14159265358979) / 10000000);
}
function comma2point (number) {
	number = number+''; // force number into a string context
	return (number.replace(/,/g,'.'));
}

function parseCoordinate(coordinate,type,format,spaced) {
	coordinate = coordinate.toString();
	var neg = 0; if (coordinate.match(/(^-|[WS])/i)) { neg = 1; }
	if (coordinate.match(/[EW]/i) && !type) { type = 'lon'; }
	if (coordinate.match(/[NS]/i) && !type) { type = 'lat'; }
	coordinate = coordinate.replace(/[NESW\-]/gi,' ');
	if (!coordinate.match(/[0-9]/i)) {
		return '';
	}
	parts = coordinate.match(/([0-9\.\-]+)[^0-9\.]*([0-9\.]+)?[^0-9\.]*([0-9\.]+)?/);
	if (!parts || parts[1] == null) {
		return '';
	} else {
		n = parseFloat(parts[1]);
		if (parts[2]) { n = n + parseFloat(parts[2])/60; }
		if (parts[3]) { n = n + parseFloat(parts[3])/3600; }
		if (neg && n >= 0) { n = 0 - n; }
		if (format == 'dmm') {
			if (spaced) {
				n = Degrees_to_DMM(n,type,' ');
			} else {
				n = Degrees_to_DMM(n,type);
			}
		} else if (format == 'dms') {
			if (spaced) {
				n = Degrees_to_DMS(n,type,' ');
			} else {
				n = Degrees_to_DMS(n,type,'');
			}
		} else {
			n = Math.round(10000000 * n) / 10000000;
			if (n == Math.floor(n)) { n = n + '.0'; }
		}
		return comma2point(n);
	}
}


function Haversine_Distance(lat1,lon1,lat2,lon2,us) {
	// http://www.movable-type.co.uk/scripts/LatLong.html
	if (Math.abs(parseFloat(lat1)) > 90 || Math.abs(parseFloat(lon1)) > 180 || Math.abs(parseFloat(lat2)) > 90 || Math.abs(parseFloat(lon2)) > 180) { return 'n/a'; }
	lat1 = deg2rad(lat1); lon1 = deg2rad(lon1);
	lat2 = deg2rad(lat2); lon2 = deg2rad(lon2);
	var dlat = lat2-lat1; // delta
	var dlon = lon2-lon1; // delta
	var alat = (lat1+lat2)/2; // average
	var re = 6378137; // equatorial radius
	var rp = 6356752; // polar radius
	var r45 = re * Math.sqrt( (1 + ( (rp*rp-re*re)/(re*re) ) * (Math.sin(45)*Math.sin(45)) ) ) // from http://www.newton.dep.anl.gov/askasci/gen99/gen99915.htm
	var a = ( Math.sin(dlat/2) * Math.sin(dlat/2) ) + ( Math.cos(lat1) * Math.cos(lat2) * Math.sin(dlon/2) * Math.sin(dlon/2) );
	var c = 2 * Math.atan( Math.sqrt(a)/Math.sqrt(1-a) );
	var d_ellipse = r45 * c;
	if (us) {
		var dist = d_ellipse / 1609.344;
		if (dist < 1) {
			return (Math.round(5280 * 1 * dist) / 1) + ' ft';
		} else {
			return (Math.round(100 * dist) / 100) + ' mi';
		}
	} else {
		var dist = d_ellipse / 1000;
		if (dist < 1) {
			return (Math.round(1000 * 1 * dist) / 1) + ' m';
		} else {
			return (Math.round(100 * dist) / 100) + ' km';
		}
	}
}

function Vincenty_Distance(lat1,lon1,lat2,lon2,us) {
	// http://www.movable-type.co.uk/scripts/LatLongVincenty.html
	if (Math.abs(parseFloat(lat1)) > 90 || Math.abs(parseFloat(lon1)) > 180 || Math.abs(parseFloat(lat2)) > 90 || Math.abs(parseFloat(lon2)) > 180) { return 'n/a'; }
	if (lat1 == lat2 && lon1 == lon2) { return '0'; }
	
	lat1 = deg2rad(lat1); lon1 = deg2rad(lon1);
	lat2 = deg2rad(lat2); lon2 = deg2rad(lon2);

	var a = 6378137, b = 6356752.3142, f = 1/298.257223563;
	var L = lon2 - lon1;
	var U1 = Math.atan((1-f) * Math.tan(lat1));
	var U2 = Math.atan((1-f) * Math.tan(lat2));
	var sinU1 = Math.sin(U1), cosU1 = Math.cos(U1);
	var sinU2 = Math.sin(U2), cosU2 = Math.cos(U2);
	var lambda = L, lambdaP = 2*Math.PI;
	var iterLimit = 20;
	while (Math.abs(lambda-lambdaP) > 1e-12 && --iterLimit > 0) {
		var sinLambda = Math.sin(lambda), cosLambda = Math.cos(lambda);
		var sinSigma = Math.sqrt((cosU2*sinLambda) * (cosU2*sinLambda) + 
		  (cosU1*sinU2-sinU1*cosU2*cosLambda) * (cosU1*sinU2-sinU1*cosU2*cosLambda));
		var cosSigma = sinU1*sinU2 + cosU1*cosU2*cosLambda;
		var sigma = Math.atan2(sinSigma, cosSigma);
		var alpha = Math.asin(cosU1 * cosU2 * sinLambda / sinSigma);
		var cosSqAlpha = Math.cos(alpha) * Math.cos(alpha);
		var cos2SigmaM = cosSigma - 2*sinU1*sinU2/cosSqAlpha;
		var C = f/16*cosSqAlpha*(4+f*(4-3*cosSqAlpha));
		lambdaP = lambda;
		lambda = L + (1-C) * f * Math.sin(alpha) * (sigma + C*sinSigma*(cos2SigmaM+C*cosSigma*(-1+2*cos2SigmaM*cos2SigmaM)));
	}
	if (iterLimit==0) { return (NaN); }  // formula failed to converge
	var uSq = cosSqAlpha*(a*a-b*b)/(b*b);
	var A = 1 + uSq/16384*(4096+uSq*(-768+uSq*(320-175*uSq)));
	var B = uSq/1024 * (256+uSq*(-128+uSq*(74-47*uSq)));
	var deltaSigma = B*sinSigma*(cos2SigmaM+B/4*(cosSigma*(-1+2*cos2SigmaM*cos2SigmaM) - B/6*cos2SigmaM*(-3+4*sinSigma*sinSigma)*(-3+4*cos2SigmaM*cos2SigmaM)));
	var s = b*A*(sigma-deltaSigma);
	
	if (us) {
		var dist = s / 1609.344;
		if (dist < 0.2) {
			return (Math.round(5280 * 1 * dist) / 1) + ' ft';
		} else {
			return (Math.round(1000 * dist) / 1000) + ' mi';
		}
	} else {
		var dist = s / 1000;
		if (dist < 1) {
			return (Math.round(1000 * 1 * dist) / 1) + ' m';
		} else {
			return (Math.round(1000 * dist) / 1000) + ' km';
		}
	}
}

function Bearing(lat1,lon1,lat2,lon2,radians) { // input is in degrees, output is your choice
	// http://www.movable-type.co.uk/scripts/LatLong.html
	if (Math.abs(parseFloat(lat1)) > 90 || Math.abs(parseFloat(lon1)) > 180 || Math.abs(parseFloat(lat2)) > 90 || Math.abs(parseFloat(lon2)) > 180) { return 'n/a'; }
	lat1 = deg2rad(lat1); lon1 = deg2rad(lon1);
	lat2 = deg2rad(lat2); lon2 = deg2rad(lon2);
	var dlat = lat2-lat1; // delta
	var dlon = lon2-lon1; // delta
	var bearing = Math.atan2( (Math.sin(dlon)*Math.cos(lat2)) , (Math.cos(lat1)*Math.sin(lat2) - Math.sin(lat1)*Math.cos(lat2)*Math.cos(dlon)) );
	
	if (radians) {
		return (bearing);
	} else {
		bearing = rad2deg(bearing);
		if (bearing < 0) { bearing += 360; }
		return (Math.round(1000 * bearing) / 1000) + String.fromCharCode(176);
	}
}

function Combine_Coordinates(lat,lon) {
	if (lat && lon) {
		var coords = lat + "," + lon;
	} else {
		var coords = '';
	}
	return coords;
}

function Calculate_Distance_Form() {
	document.distance.distance_lat1.value = parseCoordinate(document.distance.distance_lat1.value);
	document.distance.distance_lon1.value = parseCoordinate(document.distance.distance_lon1.value);
	document.distance.distance_lat2.value = parseCoordinate(document.distance.distance_lat2.value);
	document.distance.distance_lon2.value = parseCoordinate(document.distance.distance_lon2.value);
	document.distance.distance_metric.value = comma2point(Vincenty_Distance(document.distance.distance_lat1.value,document.distance.distance_lon1.value,document.distance.distance_lat2.value,document.distance.distance_lon2.value,0));
	document.distance.distance_us.value = comma2point(Vincenty_Distance(document.distance.distance_lat1.value,document.distance.distance_lon1.value,document.distance.distance_lat2.value,document.distance.distance_lon2.value,1));
	document.distance.distance_bearing.value = comma2point(Bearing(document.distance.distance_lat1.value,document.distance.distance_lon1.value,document.distance.distance_lat2.value,document.distance.distance_lon2.value));
}
function Prepare_Distance_Map() {
	document.distance_map.lat1.value = document.distance.distance_lat1.value;
	document.distance_map.lon1.value = document.distance.distance_lon1.value;
	document.distance_map.lat2.value = document.distance.distance_lat2.value;
	document.distance_map.lon2.value = document.distance.distance_lon2.value;
}

function Convert_Coordinates(f) { // f is for "form"
	var lat = f.coordinates_lat.value;
	var lon = f.coordinates_lon.value;
	var spaced = f.coordinates_space.checked;
	f.coordinates_lat_ddd.value = parseCoordinate(lat,'lat','ddd',spaced);
	f.coordinates_lon_ddd.value = parseCoordinate(lon,'lon','ddd',spaced);
	f.coordinates_lat_dmm.value = parseCoordinate(lat,'lat','dmm',spaced);
	f.coordinates_lon_dmm.value = parseCoordinate(lon,'lon','dmm',spaced);
	f.coordinates_lat_dms.value = parseCoordinate(lat,'lat','dms',spaced);
	f.coordinates_lon_dms.value = parseCoordinate(lon,'lon','dms',spaced);
	f.coordinates_pair_ddd.value = f.coordinates_lat_ddd.value+', '+f.coordinates_lon_ddd.value;
	f.coordinates_pair_dmm.value = f.coordinates_lat_dmm.value+', '+f.coordinates_lon_dmm.value;
	f.coordinates_pair_dms.value = f.coordinates_lat_dms.value+', '+f.coordinates_lon_dms.value;
}

function Calculate_Address_Distance_Form() {
	addresses_to_lookup = new Array;
	address_coordinates = new Array;
	addresses_to_lookup[0] = document.distance_address.distance_address_location1.value;
	addresses_to_lookup[1] = document.distance_address.distance_address_location2.value;
	address_lookup_counter = 0;
	GoogleGeocode();
}
function GoogleGeocode() {
	if (!self.google_api_key) { google_api_key = ''; }
	var loc = addresses_to_lookup[address_lookup_counter];
	google_url = 'http://maps.google.com/maps/geo?output=json&callback=googleCallback&key='+google_api_key+'&q='+uri_escape(loc);
	google_geocode_script = new JSONscriptRequest( google_url );
	google_geocode_script.buildScriptTag(); // Build the dynamic script tag
	google_geocode_script.addScriptTag(); // Add the script tag to the page
}
googleCallback = function(data) {
	var coords = [];
	if (data && data.Status && data.Status.code && data.Status.code == 200) {
		coords[0] = data.Placemark[0].Point.coordinates[1].toString();
		coords[1] = data.Placemark[0].Point.coordinates[0].toString();
	}
	address_coordinates.push(coords);
	google_geocode_script.removeScriptTag();
	address_lookup_counter += 1;
	if (address_lookup_counter < addresses_to_lookup.length) {
		GoogleGeocode();
	} else {
		Calculate_Address_Distance_Form2();
	}
}

function Calculate_Address_Distance_Form2() {
	document.distance_address.distance_address_lat1.value = address_coordinates[0][0];
	document.distance_address.distance_address_lon1.value = address_coordinates[0][1];
	document.distance_address.distance_address_lat2.value = address_coordinates[1][0];
	document.distance_address.distance_address_lon2.value = address_coordinates[1][1];
	document.distance_address.distance_address_metric.value = comma2point(Vincenty_Distance(document.distance_address.distance_address_lat1.value,document.distance_address.distance_address_lon1.value,document.distance_address.distance_address_lat2.value,document.distance_address.distance_address_lon2.value,0));
	document.distance_address.distance_address_us.value = comma2point(Vincenty_Distance(document.distance_address.distance_address_lat1.value,document.distance_address.distance_address_lon1.value,document.distance_address.distance_address_lat2.value,document.distance_address.distance_address_lon2.value,1));
	document.distance_address.distance_address_bearing.value = comma2point(Bearing(document.distance_address.distance_address_lat1.value,document.distance_address.distance_address_lon1.value,document.distance_address.distance_address_lat2.value,document.distance_address.distance_address_lon2.value));
}
function Prepare_Address_Distance_Map() {
	document.distance_address_map.lat1.value = document.distance_address.distance_address_lat1.value;
	document.distance_address_map.lon1.value = document.distance_address.distance_address_lon1.value;
	document.distance_address_map.lat2.value = document.distance_address.distance_address_lat2.value;
	document.distance_address_map.lon2.value = document.distance_address.distance_address_lon2.value;
	document.distance_address_map.name1.value = document.distance_address.distance_address_location1.value;
	document.distance_address_map.name2.value = document.distance_address.distance_address_location2.value;
	document.distance_address_map.desc1.value = document.distance_address.distance_address_lat1.value+', '+document.distance_address.distance_address_lon1.value;
	document.distance_address_map.desc2.value = document.distance_address.distance_address_lat2.value+', '+document.distance_address.distance_address_lon2.value;
}




function uri_escape(text) {
	text = escape(text);
	text = text.replace(/\//g,"%2F");
	text = text.replace(/\?/g,"%3F");
	text = text.replace(/=/g,"%3D");
	text = text.replace(/&/g,"%26");
	text = text.replace(/@/g,"%40");
	return (text);
}




// JSONscriptRequest -- a simple class for accessing Yahoo! Web Services
// using dynamically generated script tags and JSON
//
// Author: Jason Levitt
// Date: December 7th, 2005
//
// Constructor -- pass a REST request URL to the constructor
//

function JSONscriptRequest(fullUrl) {
	// REST request path
	this.fullUrl = fullUrl; 
	// Keep IE from caching requests
	this.noCacheIE = '&noCacheIE=' + (new Date()).getTime();
	// Get the DOM location to put the script tag
	this.headLoc = document.getElementsByTagName("head").item(0);
	// Generate a unique script tag id
	this.scriptId = 'YJscriptId' + JSONscriptRequest.scriptCounter++;
}

// Static script ID counter
JSONscriptRequest.scriptCounter = 1;

// buildScriptTag method
//
JSONscriptRequest.prototype.buildScriptTag = function () {

	// Create the script tag
	this.scriptObj = document.createElement("script");
	
	// Add script object attributes
	this.scriptObj.setAttribute("type", "text/javascript");
	this.scriptObj.setAttribute("src", this.fullUrl + this.noCacheIE);
	this.scriptObj.setAttribute("id", this.scriptId);
}
 
// removeScriptTag method
// 
JSONscriptRequest.prototype.removeScriptTag = function () {
	// Destroy the script tag
	this.headLoc.removeChild(this.scriptObj);  
}

// addScriptTag method
//
JSONscriptRequest.prototype.addScriptTag = function () {
	// Create the script tag
	this.headLoc.appendChild(this.scriptObj);
}

