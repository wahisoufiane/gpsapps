package teltonika.avl.demo.udplistener;

import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.ServerSocket;
import java.net.Socket;
import java.util.Date;

import sun.security.krb5.internal.UDPClient;
import teltonika.avl.demo.Version;
import teltonika.avl.demo.parser.AvlData;
import teltonika.avl.demo.parser.AvlDataFM4;
import teltonika.avl.demo.parser.AvlDataGH;
import teltonika.avl.demo.parser.CodecStore;

public class Listener {

	public static void main(String[] args) throws Exception {
		int port = 0;
		
		if (args.length < 1) {
			System.out.println("v"+Version.getVersion());
			System.out.println("Usage: java -jar avlreceiver-udp.jar listenPortNumber");
			System.exit(1);
		} else {
			port = Integer.parseInt(args[0]);
		}
		
		// register supported codecs
		CodecStore.getInstance().register(AvlData.getCodec());
		CodecStore.getInstance().register(AvlDataFM4.getCodec());
		CodecStore.getInstance().register(AvlDataGH.getCodec());
		
		DatagramSocket serverSocket = new DatagramSocket(port);
		System.out.println("Listening on UDP port " + port);
		
		byte[] buffer = new byte[4000];
		DatagramPacket packet = new DatagramPacket(buffer, buffer.length);
		
		while (true) {
			serverSocket.receive(packet);
			
			System.out.println(new Date().toLocaleString() + ": Receiving UDP packet from: " + packet.getAddress());
			
			new UdpChannelParser(serverSocket, packet.getData().clone(), packet.getSocketAddress()).parse();
		}
	}
}
