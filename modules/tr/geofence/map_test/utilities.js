var divMoving;
var moveMaxX;

function drawRect(lat1, lon1, lat2, lon2, color) {
	var line = Array();

	line.push(new GLatLng(lat1,lon1));
	line.push(new GLatLng(lat1,lon2));
	line.push(new GLatLng(lat2,lon2));
	line.push(new GLatLng(lat2,lon1));
	line.push(new GLatLng(lat1,lon1));

	var pLine = new GPolyline(line,color,3,1);


	return pLine;
}




function inspect(obj,maxDepth) {
	maxDepth = maxDepth || 1;
	var debug = document.createElement("DIV")	
	document.body.appendChild(debug);

	debug.innerHTML = DoInspect(obj,0,maxDepth);
}

function DoInspect(obj,depth,maxDepth) {
	var temp = "";
	temp += '<div class="OIContainer"><table cellspacing="0" cellpadding="0">\n';
	var count = 0;
	var color = 255 - depth * 16;

	for (var x in obj) {
		objx = obj[x] + '';
		if ( (objx.match('\\[object')) && (depth < maxDepth) ) {

			temp += '<tr>'
			temp += '<td valign="top" style="background:RGB('+color+','+color+','+color+')">' + x + '</td>'
			temp += '<td valign="top" style="background:RGB('+color+','+color+','+color+')">' + obj[x] + '<br>' + DoInspect(obj[x],depth + 1,maxDepth) + '</td>';
			temp += '</tr>'
		}
		else {
			temp += '<tr>'
			temp += '<td valign="top" style="background:RGB('+color+','+color+','+color+')">' + x + '</td>';
			temp += '<td valign="top" style="background:RGB('+color+','+color+','+color+')">'+ obj[x] + '</td>';
			temp += '</tr>'

		}
	}
	temp += '</table></div>\n';
	return temp;
}



function debug(str) {
	var dbg = document.getElementById("debug");
	if (!dbg) {
		dbg = document.createElement('div');
		document.body.appendChild(dbg);
	}
	if (str == '') {
		dbg.innerHTML = '';
	}
	else {
		dbg.innerHTML += str + '<br>\n';
	}


}


var startTime = new Date();

function timer(str) {
	var end = new Date();
	var elapsed = end.getTime() - startTime.getTime(); // time in milliseconds
	startTime = end;

	debug(str + ': ' + elapsed);
}





// Opacity slider --------------------------------
function divMD() {
	divMoving = true;
	moveMaxX = document.body.clientWidth - 170;
	moveMaxY = document.body.clientHeight - 330;

}

function divMM(obj,evt) {
	var e = evt || window.event;

	var x = Math.min(moveMaxX,e.clientX);
	var y = Math.min(moveMaxY,e.clientY);
	if (divMoving) {
		obj.style.left = x - 10 + 'px';
		obj.style.top = y - 10 + 'px';
	}
}



function divMU() {
	divMoving = false;
}
// End Opacity slider --------------------------------
