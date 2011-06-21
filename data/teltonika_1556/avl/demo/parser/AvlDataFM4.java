package teltonika_1556.avl.demo.parser;


import java.io.DataInputStream;
import java.io.IOException;

/**
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 *
 * <p>Implementation of data codec used in FM4 modules.</p>
 */
public class AvlDataFM4 extends AvlData {
 private int eventSource = 0;
 
 public AvlDataFM4() {
 }
 
    public AvlDataFM4(long timestamp, GpsElement gpsElement, IOElement ioElement, byte priority, int eventSourceId) {
     super(timestamp, gpsElement, ioElement, priority);
     this.eventSource = eventSourceId;
    }
 

    @Override
 protected AvlData read(BitInputStream codecInputStream) throws IOException {
        DataInputStream dataInputStream = new DataInputStream(codecInputStream);
        long timestamp = dataInputStream.readLong();
        byte priority = dataInputStream.readByte();
        GpsElement gpsData = GpsElement.read(codecInputStream);

        int eventSourceId = 0xFF & codecInputStream.read();
  
        LongIOElement iodata = readIOElement(codecInputStream);
        
        AvlDataFM4 ret = new AvlDataFM4(timestamp, gpsData, iodata, priority, eventSourceId);
        
        return ret; 
 }
 
 protected LongIOElement readIOElement(BitInputStream codecInputStream) throws IOException {
  DataInputStream dis = new DataInputStream(codecInputStream);
  
  LongIOElement ret = new LongIOElement();
  int totalProperties = dis.readUnsignedByte();
  int propertiesRead = 0;
  
  // read one-byte properties
  int oneByteProperties = dis.readUnsignedByte();
  for (int i = 0; i < oneByteProperties; ++i) {
   int id = dis.readUnsignedByte();
   int value = dis.readByte();
   ret.addProperty(new int[] { id, value });
   ++ propertiesRead;
  }
  
  // read two-byte properties
  int twoByteProperties = dis.readUnsignedByte();
  for (int i = 0; i < twoByteProperties; ++i) {
   int id = dis.readUnsignedByte();
   int value = dis.readShort();
   ret.addProperty(new int[] { id, value });
   ++ propertiesRead;
  }
  
  // read four-byte properties
  int fourByteProperties = dis.readUnsignedByte();
  for (int i = 0; i < fourByteProperties; ++i) {
   int id = dis.readUnsignedByte();
   int value = dis.readInt();
   ret.addProperty(new int[] { id, value });
   ++ propertiesRead;
  }
  
  // read eight-byte properties
  int eightByteProperties = dis.readUnsignedByte();
  for (int i = 0; i < eightByteProperties; ++i) {
   int id = dis.readUnsignedByte();
   long value = dis.readLong();
   ret.addLongProperty(new long[] { id, value });
   ++ propertiesRead;
  }
  
  if (totalProperties != propertiesRead) {
   throw new IOException("Wrong totalProperties field");
  }
  
  return ret;
 }
 
    /**
     * Instance of data codec used to encode/decode this data element
     */
    private static AvlData dataCodec = null;

    /**
     * 
     * @return Returns DataCodec for encoding/decoding this data element
     */
    public static AvlData getCodec() {
        if (dataCodec == null) {
            dataCodec = new AvlDataFM4();

        }

        return dataCodec;
    }
 
 public byte getCodecId() {
  return 8;
 }

 public int getTriggeredPropertyId() {
  return eventSource;
 }
 
 public String toString() {
        //return "FM4 [Priority=" + getPriority() + "] [GPS element=" + getGpsElement() + "] [IO=" + getInputOutputElement() + "] [Timestamp=" + getTimestamp() + "] [EventSource=" + getTriggeredPropertyId() + "]";
        return getGpsElement() + "," + getInputOutputElement() + "," + getTimestamp() + "#";
 }

}
