package teltonika_1556.avl.demo.tcplistener;


import java.net.HttpURLConnection;
import java.net.URL;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.IOException;

public class sendAlert 
{
  public void sendSMSAlert(String data)
  {
    try 
    {
        String data1 = data.replace(" ","");
        
        URL ourURL = new URL("http://localhost/gpsapp/altSrc/geoAlert.php?gpsdata="+data1); 
       // System.out.println("url "+ourURL);
        HttpURLConnection huc = (HttpURLConnection)ourURL.openConnection();            
        huc.setRequestMethod("GET");
        huc.setRequestProperty("User-Agent", "Mozilla/4.0 (compatible; JVM)");                        
        huc.setRequestProperty("Pragma", "no-cache");
        huc.connect();
        BufferedReader reader = new BufferedReader(new InputStreamReader(huc.getInputStream()));
        int code = huc.getResponseCode();
        String line = null;            
        while ((line = reader.readLine()) != null) 
        {
              System.out.println(line);
        }
        huc.disconnect();
         
    }
    catch(IOException ioe)
    {
        System.out.println("Cannot connect");
    }
    catch(Exception e)
    {
        System.err.println("General Exception " + e);
        e.printStackTrace();
    }
    
 }
    /*public static void main(String args[])
    {
        sendAlert gd= new sendAlert();
        gd.sendSMSAlert("@352848021280876,738404480,154937904,66,124,-5,10,[1=1][2=1],18-12-2010,11:10:18#");
    }*/
}