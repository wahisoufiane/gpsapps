<?php
if(isset($_GET[gpsdata]) && $_GET[gpsdata])
{
echo 'ss';
}
?>
<script src="../js/ajax.js" type="text/javascript"></script>
<script src="../js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo GOOGLE_API_KEY;?>" type="text/javascript"></script>

<script language="javascript">
var ajax1=new sack();

function gup( name )
{
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}
var frank_param = gup( 'gpsdata' );

//checkGeoPoint(frank_param);

function strrev(str) 
{
   if (!str) return '';
   var revstr='';
   for (i = str.length-1; i>=0; i--)
       revstr+=str.charAt(i)
   return revstr;
}

function calLat(lat)
{
	latArr= new Array();
	lat=lat.split("");
	l2=0;
	for(l1=lat.length;l1>=0;l1--)
	{
		if(l1==1)
		{
			latArr[l2]="."+lat[l1];
			l2++;
		}		
		else
		{
			latArr[l2]=lat[l1];
			l2++;
		}
	}
	latArr=latArr.join("");
	return strrev(latArr);
}
function calLong(long)
{
	longArr=new Array();
	long=long.split("");
	$l2=0;
	for(l1=long.length;l1>=0;l1--)
	{
		if(l1==1)
		{
			longArr[l2]="."+long[l1];
			l2++;
		}
		
		else
		{
			longArr[l2]=long[l1];
			l2++;
		}
	}
	longArr=longArr.join("");
	return strrev(longArr);;
}
function checkGeoPoint(val)
{
	var spltVal = val.split(",");
	var devDate = spltVal[8];
	var d = new Date();
	var curr_date = d.getDate();
	var curr_month = d.getMonth();
	var curr_year = d.getFullYear();
	var today = curr_date + "-" + (curr_month+1) + "-" + curr_year;
	if(devDate == today)
	{
		/*var devDateTime = spltVal[8]+" "+spltVal[9].replace("$","");
		var devImei = spltVal[0].replace("@","");
		lat = calLat(spltVal[2]);
		lng = calLong(spltVal[1]);
		ajax1.requestFile = 'geoPointChk.php?gpsdata='+val;
		//alert(ajax1.requestFile);
		ajax1.onCompletion = function(){
		
		var resSrv = ajax1.response.split("#");
		for(k=0; k<resSrv.length; k++)
		{
			var resSrv1= resSrv[k].split("@");	
			//alert(resSrv1);
			statusGeoPoint(lat,lng,resSrv1[0],resSrv1[1],resSrv1[2],resSrv1[3],resSrv1[4],devDateTime,devImei);
		}
		
		};
		ajax1.runAJAX();*/
		window.document.write("1");
	}
	else
	{
		document.write("0");
	}
}

function statusGeoPoint(lat,lng,gid,aid,src,nooftime,inout,devDateTime,devImei)
{
	var point= new GPoint(lat,lng);
	$.ajax({
			type: "GET", url: 'geoPointChk.php', data: "getpointId="+gid,
			complete: function(data){
				var gpsParam = data.responseText.split("#");
				var pts = new Array();
				for(u=0;u<gpsParam.length-1;u++)
				{
					var spltParam = gpsParam[u].split(",");
					pts[u]= new GPoint(spltParam[1],spltParam[0]);
					
				}
				var polyside = inPoly(pts,point);
				if (polyside) 
				{
					ajax1.requestFile = 'geoPointChk.php?geoAssId='+aid+'&srcdata='+src+'&notime='+nooftime+'&inoutPoint='+inout+'&inoutFlag=in'+'&devDateTime='+devDateTime+'&devImei='+devImei;
					alert(ajax1.requestFile);
					ajax1.onCompletion = function(){ alert(ajax1.response) };
					ajax1.runAJAX();
				}
				else
				{
				 	ajax1.requestFile = 'geoPointChk.php?geoAssId='+aid+'&srcdata='+src+'&notime='+nooftime+'&inoutPoint='+inout+'&inoutFlag=out'+'&devDateTime='+devDateTime+'&devImei='+devImei;
					alert(ajax1.requestFile);
					ajax1.onCompletion = function(){ alert(ajax1.response) };
					ajax1.runAJAX();
				}
				//statusGeoPoint(lat,lng,resSrv1[0],resSrv1[1],resSrv1[2],resSrv1[3])
			}
		});

}

function inPoly(poly,pt){
     var npoints = poly.length-1; // number of points in polygon
	// this assumes that last point is same as first
	//alert(pt);
     var xnew,ynew,xold,yold,x1,y1,x2,y2,i;
     var inside=false;

     if (npoints < 3) { // points don't describe a polygon
          return false;
     }
     xold=poly[npoints-1].x; yold=poly[npoints-1].y;
     
     for (i=0 ; i < npoints ; i++) {
          xnew=poly[i].x; ynew=poly[i].y;
          if (xnew > xold) {
               x1=xold; x2=xnew; y1=yold; y2=ynew;
          }else{
               x1=xnew; x2=xold; y1=ynew; y2=yold;
          }
          if ((xnew < pt.x) == (pt.x <= xold) && ((pt.y-y1)*(x2-x1) < (y2-y1)*(pt.x-x1))) {
               inside=!inside;
          }
          xold=xnew; yold=ynew;
     }; // for

     return inside;
}; // function inPoly
</script>