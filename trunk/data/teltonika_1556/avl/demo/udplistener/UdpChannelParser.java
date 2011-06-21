/**
 * 
 */
package teltonika.avl.demo.udplistener;

import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.net.DatagramPacket;
import java.net.DatagramSocket;
import java.net.InetAddress;
import java.net.SocketAddress;
import java.util.Date;

import teltonika.avl.demo.parser.AvlData;
import teltonika.avl.demo.parser.CodecException;
import teltonika.avl.demo.parser.CodecStore;
import teltonika.avl.demo.tools.Tools;

/**
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 *
 * <p></p>
 */
public class UdpChannelParser {
	private DatagramSocket socket = null;
	private byte[] receivedDatagram = null;
	private SocketAddress sender = null;
	
	public UdpChannelParser(DatagramSocket socket, byte[] receivedDatagram, SocketAddress sender) {
		this.socket = socket;
		this.receivedDatagram = receivedDatagram;
		this.sender = sender;
	}
	
	public void sendAvlResponse(byte avlPacketId, byte numberOfAcceptedElements) throws IOException {
		// create AVL response packet
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		
		bos.write(avlPacketId);
		bos.write(numberOfAcceptedElements);
		
		byte[] packet = bos.toByteArray();
	
		System.out.println(new Date().toLocaleString() + ": Sending avl response: " + Tools.bufferToHex(packet));

		sendUdpChannelPacket((short)System.currentTimeMillis(), (byte)1, packet);
	}
	
	public void parse() throws IOException, CodecException {
		byte[] payload = getUdpChannelPacketPayload(receivedDatagram);

		InputStream is = new ByteArrayInputStream(payload);
		DataInputStream dis = new DataInputStream(is);
		
		// read AvlPacketId
		byte avlPacketId = dis.readByte();
		
		// read module's IMEI
		String imei = dis.readUTF();
		
		System.out.println(new Date().toLocaleString() + ": Receiving data from module with imei: " + imei);
		
		byte[] avlDataArray = new byte[dis.available()];
		dis.readFully(avlDataArray);
		
		AvlData decoder = CodecStore.getInstance().getSuitableCodec(avlDataArray);
		
		if (decoder == null) {
			System.out.println(new Date().toLocaleString() + ": Unknown packet format: " + Tools.bufferToHex(avlDataArray));
			sendAvlResponse(avlPacketId, (byte)0);
		} else {
			System.out.println(new Date().toLocaleString() + ": Codec found: " + decoder);
			
			AvlData[] decoded = decoder.decode(avlDataArray);
			
			System.out.println(new Date().toLocaleString() + ": Received records:" + decoded.length);
			for (AvlData avlData : decoded) {
				System.out.println(avlData);
			}
			sendAvlResponse(avlPacketId, (byte)decoded.length);
		}
		
	}
	
	/**
	 * Send UDP channel packet back to module 
	 */
	public void sendUdpChannelPacket(short packetId, byte packetType, byte[] payload) throws IOException {
		// create packet
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		DataOutputStream dos = new DataOutputStream(bos);
		
		dos.writeShort(payload.length+3); // packet length
		dos.writeShort(packetId); // packet id
		dos.write(packetType); // packet type
		dos.write(payload);
		
		byte[] packetData = bos.toByteArray();
		
		DatagramPacket packet = new DatagramPacket(packetData, packetData.length);
		packet.setSocketAddress(sender);
		packet.setData(packetData);
		
		socket.send(packet);
	}
	
	public void sendAck(short packetId) throws IOException {
		// send acknoledgement packet
		sendUdpChannelPacket(packetId, (byte)2, new byte[0]);
	}
	
	public byte[] getUdpChannelPacketPayload(byte[] packet) throws IOException {
		InputStream is = new ByteArrayInputStream(packet);
		DataInputStream dis = new DataInputStream(is);

		// read udp channel packet length
		int length = dis.readUnsignedShort();
		
		// read packetId field
		short packetId = dis.readShort();
		
		// read packetType field
		byte packetType = dis.readByte();
		
		if (packetType == 0) {
			// send UDP channel acknoledgment if requested
			sendAck(packetId);
		}
		
		// read packet payload
		byte[] payload = new byte[length-3];
		dis.readFully(payload);
		
		return payload;
	}
}
