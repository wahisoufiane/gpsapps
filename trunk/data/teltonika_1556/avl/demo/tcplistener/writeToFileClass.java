package teltonika_1556.avl.demo.tcplistener;

import java.io.*;
import java.net.*;
import java.lang.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.*;
import java.text.SimpleDateFormat;
import java.util.Calendar;

public class writeToFileClass 
{
  
  int client_id, rowCount;
  Socket socket;    
  String folder,subFolder;
  String vehi;
  FileOutputStream fout;
  String tempTxt=null;
  StringBuilder line=new StringBuilder();
  GeoFun geofun = new GeoFun();
  sendAlert sa = new sendAlert();
  
  
  public void makeFolder(String strFilename,String dataTxt,String vehino) throws Exception
  {
    File fos=new File(strFilename);
    FileWriter fw=new FileWriter(fos,true);
    BufferedWriter bw=new BufferedWriter(fw);
    bw.write(dataTxt);
    bw.close();
  }
  public boolean readFile(String path,String Text,String vehi1) 
  {
    // System.out.println(path+" "+vehi1+"\n");
    String str1=null;
    try
    {
      FileInputStream fstream = new FileInputStream(path);
      DataInputStream in = new DataInputStream(fstream);
      BufferedReader br = new BufferedReader(new InputStreamReader(in)); 
      str1= br.readLine();
      in.close();
    }
    catch (Exception e){
      //System.err.println("Error: " + e.getMessage());
      str1="";
    }
    if(str1.indexOf(Text)==-1)
    {
      return true;
    }
    else
    {
      return false;
    }
  }
  
  public void readData(String strText,String imei) throws Exception
  {
    //System.out.println("Data "+strText+"\n\n");
    try
    {
      
      String[] data3=strText.split(",");
      // vehi=data3[0].substring(1,(data3[0].length()));
      String[] timStmp = data3[8].split("#");
      //System.out.println("Date "+timStmp[0]);   
      String nwDate=new GeoFun().convertTimeStampToDate(timStmp[0]);
      String nwTime=new GeoFun().convertTimeStampToTime(timStmp[0]);
      String nwDateTime = nwDate+","+nwTime;
      //System.out.println("New Date "+nwDateTime);
      String finalData = strText.replaceAll(timStmp[0],nwDateTime);
      //System.out.println("Final "+finalData);
      
     /* Connection conn = null;
      ResultSet rs = null;
      PreparedStatement pstmt = null;
      String query = "SELECT di_clientId from tb_deviceinfo where di_status = 1 AND di_imeiId = ?"; //String query = "SELECT tt_clientId from task_truck where tt_vehicleId= ?";
      try 
      {
        conn = new GeoFun().getConnection();
        pstmt = conn.prepareStatement(query);
        pstmt.setString(1, imei);
        rs = pstmt.executeQuery();
        while (rs.next())
        {
          //System.out.println("<br>");
          client_id=rs.getInt(1);
          //System.out.println(client_id);
        }
        rs.last();
        rowCount = rs.getRow();
        //System.out.println("row count "+rowCount);
        
      }
      catch(Exception e) 
      {
        rs.close();
        pstmt.close();
        conn.close();
      }
      if(rowCount > 0)
      {
        folder="data/data_"+client_id+"/";
      }
      else
      {
        folder="data/buffer/";
      }    
      
      folder="data/";
      //System.out.println("date "+finalData);
      File f = new File(folder);
      try
      {
        if(f.mkdirs()){}
        else{}
      }
      catch(Exception e)
      {
        //e.printStackTrace();
      }*/
      subFolder="data/"+nwDate+"/";
      //System.out.println("subFolder "+subFolder);
      File sub = new File(subFolder);
      try
      {
        if(sub.mkdirs()){}
        else{}
      }
      catch(Exception e)
      {
        e.printStackTrace();
      }
      subFolder+=imei+".txt";
      // System.out.println(subFolder);
      
      if(readFile(subFolder,finalData,imei))
      {
        //System.out.println("Data "+finalData+"\n");
        makeFolder(subFolder,finalData,imei);
        sa.sendSMSAlert(finalData);
      } 
    }   
    catch(Exception e)
    {
      //System.out.println("Problem finding file");
    }
  }
  
  /*public static void main (String[] args) 
   {
   writeToFileClass wtf= new writeToFileClass();
   try
   {
   //wtf.readData("$CHD5991,27,07,09,10,10,01,17266264N,078260380E,00.0,164#$CHD5991,27,07,09,10,10,21,17266264N,078260380E,00.0,178#");
   wtf.readData("$c5991,120310,023603,1726.76920,N,07825.87531,E,0.0,A,07,AD,1.23#");
   }
   catch(Exception e)
   {}
   }*/
}