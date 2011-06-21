<?php //echo $_GET[map_textbox_id]; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">


<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1"/>

<title>Chekhra :: Find Postal Address of any Location</title>
<meta name="Description" content="Find the Postal Address of any Location on Google Maps" />
<link href="css/tracker_styles.css" rel="stylesheet" type="text/css" />

    <script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=ABQIAAAA8SX_4QC_U9ppzhLCV-6gChTVHROJnGkihDrNOfbXg-A52thNtRQ9IWOyFUNJMs6tCrJxab43r0aiVg"
    type="text/javascript"></script>
<script type="text/javascript">

    var map = null;
    var geocoder = null;

    function initialize() {
     // if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("map"));
		map.addControl(new GSmallMapControl());
		map.addControl(new GMenuMapTypeControl());
		map.enableScrollWheelZoom();
		map.enableContinuousZoom();
		map.addControl(new GScaleControl());
		map.addControl(new GOverviewMapControl());
     	map.setCenter(new GLatLng(17.385044,78.486671), 14);
        geocoder = new GClientGeocoder();
        GEvent.addListener(map, "click", clicked);
      //}
    }

    function showAddress(address) {
      if (geocoder) {
        geocoder.getLatLng(
          address,
          function(point) {
            if (!point) {
              alert("We're sorry but '" + address + "' cannot be found on Google Maps. Please try again.");
            } else {
     	     map.panTo(point); 
        }
      });
    }
  }

    function clicked(overlay, latlng) {
        geocoder.getLocations(latlng, function(response) {
  		if (!response || response.Status.code != 200) {
            alert("reverse geocoder failed to find an address for " + latlng.toUrlValue());
          }
          else {
			place = response.Placemark[0];
			opener.document.getElementById('<?php echo $_GET[map_textbox_id].point; ?>').value=place.address;		//latlng;
			opener.document.getElementById('<?php echo $_GET[map_textbox_id].LatLng; ?>').value=latlng;		//latlng;
			//window.location='../FMS/index.php?ch=addTask&latlng='+latlng+'loca='+address.address;
			window.close();
            var myHtml = place.address;
            map.openInfoWindow(latlng, myHtml);
          }
        });
      
    }
    </script>
</head>
<body onload="initialize()" onunload="GUnload()" style="background:#fff">
	
<!-- google_ad_section_start(weight=ignore) -->
<table width="900" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr class="corner_mid">
    <td><img src="images/corner_LT.gif" width="22" height="20" /></td>
    <td width="900">&nbsp;</td>
    <td><img src="images/corner_RT.gif" width="22" height="20" /></td>
  </tr>
  <tr>
    <td class="corner_mid_left">&nbsp;</td>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="popup_map">
      <tr>
        <td><div id="map_div"><div id="map"></div></div>
          <div id="map_panel">
          <ul>
          <li><input type="text" name="address" id="address" value="Hyderabad" onfocus="this.value='';" /></li>
          <li><input name="submit" type="submit" value="Show Address" class="blue_btn" onClick="showAddress(document.getElementById('address').value)"/></li>
          
          <li>Find the exact address of any point on earth with Google Map</li>
		  <li><strong> Step 1</strong>: Use the search box to find an approximate location on Google Maps.</li>
		  <li><strong>Step 2</strong>: Now click any point on Google Maps to see its address.</li>
          </ul>
          <span class="map_logo"><img src="images/map_logo.png" width="70" height="21" /><br />
          © 2008 - 2009,Chekhra.com</span>          </div></td>
      </tr>
    </table></td>
    <td class="corner_mid_right">&nbsp;</td>
  </tr>
  <tr class="corner_mid_bot">
    <td><img src="images/corner_LB.gif" width="22" height="20" /></td>
    <td>&nbsp;</td>
    <td><img src="images/corner_RB.gif" width="22" height="20" /></td>
  </tr>
</table>
</body>
</html>
