package teltonika_1556.avl.demo.tcplistener;

import java.text.ParseException;
import java.sql.*;
import java.io.*;
import java.util.Calendar;
import java.util.Date;
import java.text.DateFormat;
import java.text.SimpleDateFormat;


public class GeoFun
{
  String time ;
  Date d;

  public String getToday()
  {
    DateFormat dateFormat1 = new SimpleDateFormat("dd-MM-yyyy");
    d = new Date();    
    return dateFormat1.format(d);
  }
  public String convertTimeStamp(String timeStamp)
  {
     DateFormat formatter ; 
     formatter = new SimpleDateFormat("dd-MM-yyyy");
     //String input = "1285821658000";
     long unixTime = Long.parseLong(timeStamp);             
     java.util.Date d = new java.util.Date(unixTime); 
     //System.out.println( "input = " + formatter.format(d)  ); 
     return formatter.format(d).toString();
  }
  
  public String convertTimeStampToDate(String timeStamp)
  {
     DateFormat formatter ; 
     formatter = new SimpleDateFormat("dd-MM-yyyy");
     //String input = "1285821658000";
     long unixTime = Long.parseLong(timeStamp);             
     java.util.Date d = new java.util.Date(unixTime); 
     //System.out.println( "input = " + formatter.format(d)  ); 
     return formatter.format(d).toString();
  }
 
  public String convertTimeStampToTime(String timeStamp)
  {
     DateFormat formatter ; 
     formatter = new SimpleDateFormat("HH:mm:ss");
     //String input = "1285821658000";
     long unixTime = Long.parseLong(timeStamp);             
     java.util.Date d = new java.util.Date(unixTime); 
     //System.out.println( "input = " + formatter.format(d)  ); 
     return formatter.format(d).toString();
  }
  public String convertDateToString()
  {
    DateFormat dateFormat1 = new SimpleDateFormat("HH:mm:ss");
    Calendar calendar = Calendar.getInstance();
    int hour =calendar.get(calendar.HOUR_OF_DAY);
    int minut = calendar.get(calendar.MINUTE);
    int sec =  calendar.get(calendar.SECOND);
    time =  hour+ ":" +minut;
    // System.out.println(calendar.getTime());
    System.out.println("Time is :"+ time);
    return (time);
  }
  public Connection getConnection() throws Exception 
  {
     Runtime.getRuntime().freeMemory();
     String driver = "com.mysql.jdbc.Driver";
     String url = "jdbc:mysql://localhost/trackerdb";
     String username = "PPwd1";
     String password = "PPwd1";
     Class.forName(driver);
     Connection conn = DriverManager.getConnection(url, username, password);
     return conn;
  }
  public static String reverseIt(String source) 
  {
     int i, len = source.length();
     StringBuffer dest = new StringBuffer(len);
     for (i = (len - 1); i >= 0; i--)
      dest.append(source.charAt(i));
     return dest.toString();
  }
 
  public String makeDate(String d1,String t1)
  {
     
     int d2=Integer.parseInt(d1);
     int t2=Integer.parseInt(t1);
    
     SimpleDateFormat dateFormat = new SimpleDateFormat("ddMMyyyy");
     SimpleDateFormat timeFormat = new SimpleDateFormat("dd-MM-yy");
     String strDate=null;
        
        String in = d1;
        try 
        {            
           Date theDate = dateFormat.parse(in);
           //System.out.println("Date parsed = " + dateFormat.format(theDate)+ " Parsed Time "+ timeFormat.format(d2)+am_pm);
           strDate= timeFormat.format(theDate).toString();
           //System.out.println("Date "+strDate);
            
        } catch (ParseException e) {
            e.printStackTrace();
        }
        return strDate;
     
    }
 
  public String calLat(String lat1) 
  {
  String latArr= "";
  StringBuffer lat = new StringBuffer(reverseIt(lat1));
  int i, len=lat1.length();
  int l2=0;
  for (i = 0; i < len; i++) {
   if(i==7) {
    latArr +=" "+lat.charAt(i);
    l2++;
   }else if(i==5) {
    latArr+="."+lat.charAt(i);
    l2++;
   }else if(i==1){
    latArr+=" "+lat.charAt(i);
    l2++;
    }
   else{
    latArr+=lat.charAt(i);
    l2++;
   }
  }
  latArr=(reverseIt(latArr));
  return latArr;
   }
   public String calLong(String lng1) {
  String longArr= "";
  StringBuffer lng = new StringBuffer(reverseIt(lng1));
  int i, len=lng1.length();
  int l2=0;
  for (i = 0; i < len; i++){
   if(i==7){
    longArr +=" "+lng.charAt(i);
    l2++;
    }else if(i==5){
    longArr+="."+lng.charAt(i);
    l2++;
    }else if(i==1){
    longArr+=" "+lng.charAt(i);
    l2++;
     }else{
    longArr+=lng.charAt(i);
    l2++;
       }
   }
  longArr=(reverseIt(longArr));
  return longArr;
  }
  public String arrayToString(String[] stringarray)
  {
     String str = " ";
     for (int i = 0; i < stringarray.length; i++) {
        str = str + stringarray[i];
     }
     return str;
  }
  public String convert(String v) 
  {
    String[] D = v.split(" ");
    String[] M1 = D[1].split("\\."); 
    double s = ((Float.parseFloat(M1[1])/10000)*60);  
    String res=D[0]+" "+M1[0]+" "+s+" "+D[2];
    return (res);
  }
  public double convertLat(String lat) 
  {
     String[] lat1=convert(lat).split(" ");
     double lat3=0.0;
     double LatDeg = Float.parseFloat(lat1[0]);
     double LatMin = Float.parseFloat(lat1[1]);
     double LatSec = Float.parseFloat(lat1[2]);
     if (LatDeg==0){
       LatDeg=0;
     }
     if (LatMin==0){
       LatMin=0;
     }
     if (LatSec==0){
       LatSec=0;
     } 
     if (LatDeg != Math.round(LatDeg) || LatMin != Math.round(LatMin) ){
        System.out.println("ERROR");
     }else if (LatDeg < -90 || LatDeg > 90 || LatMin < -60 || LatMin > 60 || LatSec < -60 || LatSec > 60 ){
          System.out.println("ERROR");
     }else{
      String LatDMSDirect = lat1[3];
      if (LatDMSDirect==null){
         if (LatDeg<0) {
           LatDMSDirect = "S";
          }else {
            LatDMSDirect ="N";
           }
      }
      LatDeg = Math.abs(LatDeg);
      LatMin = Math.abs(LatMin);
      LatSec = Math.abs(LatSec);
      lat3 = LatDeg + (LatMin/60) + (LatSec / 60 / 60);
      if ( lat3 <= 90 && lat3 >=0 ) {
         lat3 = lat3;
       } 
     }
     return lat3;
  }
  public double convertLong(String lng) 
  {
     String[] lng1=convert(lng).split(" ");
     double lon3=0.0;

     // Retrieve Lat and Lon information
     double LonDeg = Float.parseFloat(lng1[0]);
     double LonMin = Float.parseFloat(lng1[1]);
     double LonSec = Float.parseFloat(lng1[2]);
 
     // Assume the value to be zero if the user does not enter value
     if (LonDeg==0)
       LonDeg=0;
     if (LonMin==0) {
       LonMin=0;
     }
     if (LonSec==0){
         LonSec=0;
     } 
     // Check if any error occurred
     if (LonDeg != Math.round(LonDeg) || LonMin != Math.round(LonMin)) {
        System.out.println("ERROR");
     }else if (LonDeg < -180 || LonDeg > 180 || LonMin < -60 || LonMin > 60 || LonSec < -60 || LonSec > 60) {
          System.out.println("ERROR");
       }else {
         // If no error, then go on
       // Retrieve the longitude direction for Deg/Min/Sec
          String LonDMSDirect = lng1[3];
      
       // If the user does not click direct button,
       // then a positive latitude value regards East, negative latitude value regards West
    if (LonDMSDirect==null) {
         if (LonDeg<0) {
            LonDMSDirect = "W";
                
       }else {
             LonDMSDirect ="E";
           }
        }
          // Change to absolute value
    LonDeg = Math.abs(LonDeg);
    LonMin = Math.abs(LonMin);
    LonSec = Math.abs(LonSec);
   
    // Convert to Decimal Degrees Representation
     lon3 = LonDeg + (LonMin/60) + (LonSec / 60 / 60);
        if ( lon3 <= 180 && lon3 >= 0 ){
           // Rounding off
           lon3 = lon3;
       } 
    }return lon3;
     }
     
      /*getDay() Method returns day of the Week in Integer format as Sunday-0,Monday-1 etc..,*/
  public int getDay() 
  {
   Calendar cals =  Calendar.getInstance();
   int dayOfWeek = cals.get(Calendar.DAY_OF_WEEK);    
   return dayOfWeek-1;
  }
 /*This Method Converts 24 hours time format into Minutes
  *Converts Hours into Minutes and Add's it with Minutes to convert toatal time into minutes.
  */
  public int convertTime(String convtime) 
  {
     int var2;
     String[] hrs1 = convtime.split(":");
     int var1 = Integer.parseInt(hrs1[0]); 
     var2 = var1*60 + Integer.parseInt(hrs1[1]);
     return var2;
  }
   /*This Mehhod finds the difference between  double values and returns the absolute value
  * of the result.
  */
  public double  findDiff(double sec , double lat) 
  {
    return Math.abs(sec-lat);
  }
  public String[] drawCircle(double lt1,double  ln1,double radius) 
  {
 
      String[] Cpoints = new String[33];
      double Cy,Cx;
 
      double d2r = Math.PI/180;
      double r2d = 180/Math.PI;
      double Clat = radius * 0.014483;  // Convert statute miles into degrees latitude radius * 0.014483;
     
      double Clng = Clat/Math.cos(lt1*d2r); 
      for (int i=0; i < 33; i++) 
      { 
       
       double d1 = ((i*10000)/16);
       d1=d1/10000;
       double theta = Math.PI * (d1); 
       Cy = lt1 + (Clat * Math.sin(theta)); 
       Cx = ln1 + (Clng * Math.cos(theta)); 
       String P = Cx+","+Cy; 
       Cpoints[i]=P; 
      
      }
      return Cpoints;     
   }
 public String[] render (double lt1,double  ln1,double radius)
 {
     String[] points = new String[73];
     int c=0;
     double distance = ((radius*5280)/3.2808399)/1000;
     for (int i = 0; i < 72; i++) {
       points[c]=destination(lt1,ln1, i * 360/72, distance);
       c++;
       //System.out.println(points[i].toString());
     }
     points[c]=destination(lt1,ln1, 0, distance);
     return points;
     //this._points.push(destination(this._centerHandlePosition, 0, distance) );
     //this._polyline = new GPolyline(this._points, this._color, 6);
 }
 public String destination(double lt2,double ln2, double hdng, double dist) 
 {
   String rest=null;
   int R = 6371; // earth's mean radius in km
   double oX=0.0, oY=0.0,x=0.0,y=0.0;
   double d = dist/R;  // d = angular distance covered on earth's surface
   hdng = hdng * Math.PI / 180; // degrees to radians
   oX = lt2 * Math.PI / 180;
   oY = ln2 * Math.PI / 180;
 
   y = Math.asin( Math.sin(oY)*Math.cos(d) + Math.cos(oY)*Math.sin(d)*Math.cos(hdng) );
   x = oX + Math.atan2(Math.sin(hdng)*Math.sin(d)*Math.cos(oY), Math.cos(d)-Math.sin(oY)*Math.sin(y));
 
   y = y * 180 / Math.PI;
   x = x * 180 / Math.PI;
   rest= x+","+y;
   return rest;
 }
}
 