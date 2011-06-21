package teltonika_1556.avl.demo.tcplistener;

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.EOFException;
import java.net.Socket;
import java.util.Date;
import java.io.*;



import teltonika.avl.demo.parser.AvlData;
import teltonika.avl.demo.parser.AvlDataFM4;
import teltonika.avl.demo.parser.AvlDataGH;
import teltonika.avl.demo.parser.CodecStore;
import teltonika.avl.demo.tools.Tools;

public class ModuleHandler implements Runnable {
 private Socket moduleSocket;
 String imei;
 String liveData;
 
 
 public ModuleHandler(Socket sock) {
  this.moduleSocket = sock;
 }

  public void run() {
  try {
   //System.out.println("New connection from module:" + moduleSocket);
   
   DataInputStream dis = new DataInputStream(moduleSocket.getInputStream());
   DataOutputStream dos = new DataOutputStream(moduleSocket.getOutputStream());
   
   imei = dis.readUTF();
   //System.out.println("Module IMEI:" + imei);
   dos.writeBoolean(true);
   
   
   
   while (true) {
    byte[] packet = ByteWrapper.unwrapFromStream(dis);
    
    if (packet == null) {
     //System.out.println("Closing connection: " + moduleSocket);
     break;
    }
    
    AvlData decoder = CodecStore.getInstance().getSuitableCodec(packet);

    if (decoder == null) {
     System.out.println("Unknown packet format: " + Tools.bufferToHex(packet));
     dos.writeInt(0);
    } else {
     //System.out.println("Codec found: " + decoder);
     
     AvlData[] decoded = decoder.decode(packet);
     
     //System.out.println(new Date().toLocaleString() + ": Received records:" + decoded.length);
     for (AvlData avlData : decoded) {
       liveData = "@"+imei+","+avlData;
       //System.out.println("Data "+liveData+"\n\n");
       new writeToFileClass().readData(liveData,imei);
     }
     //new writeToFileClass().readData(liveData,imei);  
     //liveData = "";
     dos.writeInt(decoded.length);
    }
   }
   
  } catch (EOFException ee) {
   System.out.println("Closed connection:" + moduleSocket);
  } catch (Exception e) {
   e.printStackTrace();
  }
 }

}
