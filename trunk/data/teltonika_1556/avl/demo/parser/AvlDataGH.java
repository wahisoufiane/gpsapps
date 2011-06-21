package teltonika_1556.avl.demo.parser;




import java.io.ByteArrayInputStream;
import java.io.DataInputStream;
import java.io.IOException;
import java.io.InputStream;
import java.util.Calendar;
import java.util.TimeZone;
import java.util.Vector;

/**
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 *
 * <p>GH protocol parser</p>
 */
public class AvlDataGH extends AvlData {
 // generated propety id's
 public static int CELLID_PROPERTY_ID = 200;
 public static int SIGNAL_QUALITY_PROPERTY_ID = 201;
 public static int OPCODE_PROPERTY_ID = 202;
 

 // GlobalMask values 
 protected static int MASK_GPS_ELEMENT = 0x01;
 protected static int MASK_IO_ELEMENT_1B = 0x01 << 1;
 protected static int MASK_IO_ELEMENT_2B = 0x01 << 2;
 protected static int MASK_IO_ELEMENT_4B = 0x01 << 3;

 // Mask values
 protected static int MASK_POSITION = 0x01;
 protected static int MASK_ALTITUDE = 0x01 << 1;
 protected static int MASK_ANGLE = 0x01 << 2;
 protected static int MASK_SPEED = 0x01 << 3;
 protected static int MASK_SATELLITES = 0x01 << 4;
 protected static int MASK_CELLID = 0x01 << 5;
 protected static int MASK_SIGNALQUALITY = 0x01 << 6;
 protected static int MASK_OPERATOR_CODE = 0x01 << 7;
 
 /**
  * 
  */
 public AvlDataGH() {
 }

 /**
  * @param timestamp
  * @param gpsElement
  * @param ioElement
  * @param priority
  */
 public AvlDataGH(long timestamp, GpsElement gpsElement,
   IOElement ioElement, byte priority) {
  super(timestamp, gpsElement, ioElement, priority);
 }
 
 public byte[] encode(AvlData[] datas) throws CodecException {
  throw new CodecException("Encoding using " + getCodecClass() + " not implemented");
 }
 
 public AvlData[] decode(byte[] dataByteArray) throws CodecException {
  ByteArrayInputStream bis = new ByteArrayInputStream(dataByteArray);

  if (bis.read() != getCodecId()) {
   throw new CodecException("Wrong codecId");
  }

  int numberOfData = bis.read(); 
  AvlData[] result = new AvlData[numberOfData];

  for (int i = 0; i < numberOfData; ++i) {
   try { 
    result[i] = read(bis);
   } catch (IOException e) {
    throw new CodecException("Error reading " + getCodecClass(), e);
   }
  }

  int secondNumberOfData = bis.read();
  if (secondNumberOfData != numberOfData) {
   throw new CodecException("NumberOfData mismatch: " + secondNumberOfData + "!=" + numberOfData);
  }
  
  return result;
 }
 
 protected AvlDataGH read(InputStream is) throws IOException {
  DataInputStream dis = new DataInputStream(is);
  int timestamp = dis.readInt();
  byte priority = (byte)(0x03 & (timestamp >> 30));
  timestamp = timestamp & 0x3FFFFFFF;
  
  Calendar cal = Calendar.getInstance(TimeZone.getTimeZone("UTC"));
  cal.set(2007, Calendar.JANUARY, 1, 0, 0, 0);
  cal.set(Calendar.MILLISECOND, 0);

  int globalMask = is.read();
  
  GpsElement gpsElement = null;
  IOElement ioElement = new IOElement();
  
  Vector generatedProperties = new Vector();
  
  if ((globalMask & MASK_GPS_ELEMENT) != 0) {
   gpsElement = readGpsElement(is, generatedProperties);
  }

  if ((globalMask & MASK_IO_ELEMENT_1B) != 0) {
   ioElement.addAll(readIOElement(is, 1), true);
  }
  
  if ((globalMask & MASK_IO_ELEMENT_2B) != 0) {
   ioElement.addAll(readIOElement(is, 2), true);
  }

  if ((globalMask & MASK_IO_ELEMENT_4B) != 0) {
   ioElement.addAll(readIOElement(is, 4), true);
  }
  
  // add generated properties to ioElement 
  if (generatedProperties.size() > 0) {
   if (ioElement == null) {
    ioElement = new IOElement();
   }
   for (int i = 0; i < generatedProperties.size(); ++i) {
    int[] prop = (int[]) generatedProperties.elementAt(i);
    ioElement.addProperty(prop);
   }
  }
  
  AvlDataGH ret = new AvlDataGH(
    cal.getTimeInMillis() + timestamp * 1000L, 
    gpsElement, 
    ioElement, 
    priority);
  
  return ret;
 }
 
 protected IOElement readIOElement(InputStream iStream, int width) throws IOException {
  IOElement ret = new IOElement();
  
  DataInputStream dis = new DataInputStream(iStream);
  int num = dis.readUnsignedByte();
  
  for (int i = 0; i < num; ++i) {
   int id = dis.readUnsignedByte();
   int value;
   
   switch (width) {
   case 1:
    value = dis.readByte();
    break;
   case 2:
    value = dis.readShort();
    break;
   case 4:
    value = dis.readInt();
    break;
   default:
    throw new IOException("Unsupported IOElement width");
   }
   
   ret.addProperty(new int[] { id, value });
  }
  
  return ret;
 }
 
 /**
  * @param is
  * @param generatedProperties - if not null, in this method it can be filled with 
  * generated propeties (which desn't fit into GpsElement)
  * @return GpsElement read
  * @throws IOException
  */
 protected GpsElement readGpsElement(InputStream is, Vector generatedProperties) throws IOException {
  DataInputStream dis = new DataInputStream(is);
  
  int mask = dis.readByte();
  GpsElement gpsElement = new GpsElement();
  
  if ((mask & MASK_POSITION) != 0) {
   gpsElement.setY((int) (dis.readFloat() * GpsElement.WGS_PRECISION));
   gpsElement.setX((int) (dis.readFloat() * GpsElement.WGS_PRECISION));
  }
  if ((mask & MASK_ALTITUDE) != 0) {
   gpsElement.setAltitude(dis.readShort());
  }
  if ((mask & MASK_ANGLE) != 0) {
   gpsElement.setAngle((short) (dis.readUnsignedByte() * 360 / 256));
  }
  if ((mask & MASK_SPEED) != 0) {
   gpsElement.setSpeed( (short)dis.readUnsignedByte());
  }
  if ((mask & MASK_SATELLITES) != 0) {
   gpsElement.setSatellites(dis.readByte());
  }
  if ((mask & MASK_CELLID) != 0) {
   int cellId = dis.readInt();
   if (generatedProperties != null) {
    generatedProperties.add(new int[] { CELLID_PROPERTY_ID, cellId });
   }
  }
  if ((mask & MASK_SIGNALQUALITY) != 0) {
   int signalQuality = dis.readUnsignedByte();
   if (generatedProperties != null) {
    generatedProperties.add(new int[] { SIGNAL_QUALITY_PROPERTY_ID, signalQuality });
   }
  }
  if ((mask & MASK_OPERATOR_CODE) != 0) {
   int opCode = dis.readInt();
   if (generatedProperties != null) {
    generatedProperties.add(new int[] { OPCODE_PROPERTY_ID, opCode });
   }
  }
  
  // set the N/A position if it's not available
  if (gpsElement.getX() == 0 && gpsElement.getY() == 0) {
   gpsElement.setSpeed((short) 255);
   gpsElement.setSatellites((byte)0);
  }
  
  return gpsElement;
 }

 public byte getCodecId() {
  return 7;
 }
 
    /**
     * Instance of data codec used to encode/decode this data element
     */
    private static AvlDataGH dataCodec = null;

    @Override
    public String toString() {
     //return "GH [Priority=" + getPriority() + "] [GPS element=" + getGpsElement() + "] [IO=" + getInputOutputElement() + "] [Timestamp=" + getTimestamp() + "]";
      return getGpsElement() + "," + getInputOutputElement() + "," + getTimestamp() + "#";
    }
    
    /**
     * 
     * @return Returns DataCodec for encoding/decoding this data element
     */
    public static AvlData getCodec() {
        if (dataCodec == null) {
            dataCodec = new AvlDataGH();

        }

        return dataCodec;
    }
 
 public Class getCodecClass() {
  return this.getClass();
 }

}
