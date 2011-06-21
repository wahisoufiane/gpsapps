package teltonika_1556.avl.demo.tcplistener;

import java.net.ServerSocket;
import java.net.Socket;

import teltonika.avl.demo.Version;
import teltonika.avl.demo.parser.AvlData;
import teltonika.avl.demo.parser.AvlDataFM4;
import teltonika.avl.demo.parser.AvlDataGH;
import teltonika.avl.demo.parser.CodecStore;

public class Listener {

 public static void main(String[] args) throws Exception {
  int port = 1556;
  
  /*if (args.length < 1) {
   System.out.println("v"+Version.getVersion());
   System.out.println("Usage: java -jar avlreceiver.jar listenPortNumber");
   System.exit(1);
  } else {
   port = Integer.parseInt(args[0]);
  }*/
  
  // register supported codecs
  CodecStore.getInstance().register(AvlData.getCodec());
  CodecStore.getInstance().register(AvlDataFM4.getCodec());
  CodecStore.getInstance().register(AvlDataGH.getCodec());
  
  ServerSocket serverSocket = new ServerSocket(port);
  System.out.println("Listening on TCP port " + port);
  
  while (true) {
   Socket sock = serverSocket.accept();
   
   new Thread(new ModuleHandler(sock)).start();
  }
 }
}
