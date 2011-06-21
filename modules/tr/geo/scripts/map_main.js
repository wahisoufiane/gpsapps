/**
 * Main Map Script
 */
var geometryControls; //for testing, give firebug access
var polygonControl;
var map;
GEvent.addDomListener(window,"load",function(){
  if (GBrowserIsCompatible()) {
  
    map = new GMap2(document.getElementById("map_canvas"));
    map.setCenter(new GLatLng(17.443898333333,78.43386), 14);
    map.addControl(new GSmallMapControl());
    map.addControl(new GMenuMapTypeControl());
    map.enableScrollWheelZoom();
	map.enableGoogleBar();
	 
    geometryControls = new GeometryControls();
    polygonControl = new PolygonControl();
    geometryControls.addControl(polygonControl);
    map.addControl(geometryControls);
    
    geometryControls.loadData({
      type:"kml",
      url:"data/showAllGeofence.php"
    });
    
    
  
    //for testing
   geometryControls.Options.autoSave = true;    
  }
});

GEvent.addDomListener(window,"unload",function(){
	GUnload();
});

/**
 * Toggles the autoSaveProperty which determines if ajax post requests are made
 * after changing geometries
 * For testing only
 * @param {Object} button
 */
function mockAutoSave(button){
  if(button.value.indexOf("On") > -1){
    geometryControls.Options.autoSave = true;
    button.value = "Turn AutoSave Off";
  } else {
    geometryControls.Options.autoSave = false;
    button.value = "Turn AutoSave On";
  }
}



var ajax1=new sack();
var stopName=/^[a-zA-Z0-9][a-zA-Z0-9 ]*$/;
var validFlag = 1;
function validThis()
{
	
 	if(document.getElementById('emmc-geom-title').value=="")
	{
		alert('Stop Name required');
		document.getElementById('emmc-geom-title').focus();
		validFlag = 0;
	}
	else if (!stopName.test(document.getElementById('emmc-geom-title').value)) 
	{
		alert('Only alphanumeric allowed');
		document.getElementById('emmc-geom-title').focus();
		document.getElementById('emmc-geom-title').select();
		validFlag = 0;
	}
	else
	{
		return validFlag;
	}
}
function addThisPoint(data)
{
	var flg1=validThis();
	if(flg1)
	{
		ajax1.requestFile = '../ajax_server.php?'+data;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){exeAddPoints()};
		ajax1.runAJAX();
	}
}
function exeAddPoints()
{
	//alert(ajax1.response);
	if(eval(ajax1.response) == 1)
	{
		alert("Point added successfully.");
		map.closeInfoWindow();
	}
	else if(eval(ajax1.response) == 3)
	{
		alert("Point updated successfully.");
		//map.closeInfoWindow();
	}
	else if(eval(ajax1.response) == 0)
	{
		alert("Point adding failed.");
		//map.closeInfoWindow();
	}
	else if(eval(ajax1.response) == 2)
	{
		alert('Same point name already exist. Please use different.');
		//map.closeInfoWindow();
	}
	else if(eval(ajax1.response) == 4)
	{
		alert('Point deleted successfully.');
		//map.closeInfoWindow();
	}
	else if(eval(ajax1.response) == 5)
	{
		alert('Point not deleted.');
		//map.closeInfoWindow();
	}
}

function showThisGeoPoint(id)
{
		//alert("geoid "+id);
		//GEvent.trigger(gmarkers[0],"click"); 
		
}