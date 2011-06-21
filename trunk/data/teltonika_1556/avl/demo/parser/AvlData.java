package teltonika_1556.avl.demo.parser;


import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;

/**
 * <p>
 * </p>
 */
public class AvlData {

    public static final byte HIGH_PRIORITY = 20;
    
    public static final byte ABOVE_NORMAL_PRIORITY = 15;
    
    public static final byte NORMAL_PRIORITY = 10;
    
    public static final byte BELLOW_NORMAL_PRIORITY = 5;

    public static final byte LOW_PRIORITY = 0;

    /**
     * Time stamp when data was acquired
     */
    private long timestamp = 0;

    /**
     * Message priority. Unsigned value. Higher the value higher the priority
     */
    private byte priority = NORMAL_PRIORITY;

    /**
     * Information about status of input and output values
     */
    private IOElement inputOutputElement = new IOElement();

    /**
     * Position information
     */
    private GpsElement gpsElement = new GpsElement();

    /**
     * Empty default constructor
     * 
     */
    public AvlData() {
        // empty constructor
    }

    /**
     * Creates new avl data element with passed values
     * 
     * @param timestamp
     *            Timestamp when data was acquired
     * @param gpsElement
     *            GPS information
     * @param ioElement
     *            Input output information
     */
    public AvlData(long timestamp, GpsElement gpsElement, IOElement ioElement, byte priority) {
        setTimestamp(timestamp);
        setGpsElement(gpsElement);
        setInputOutputElement(ioElement);
        setPriority(priority);
    }

    /**
     * @return Returns the gpsData.
     */
    public GpsElement getGpsElement() {
        return gpsElement;
    }

    /**
     * @param gpsData
     *            The gpsData to set.
     */
    public void setGpsElement(GpsElement gpsData) {
        this.gpsElement = gpsData;
    }

    /**
     * @return Returns the inputOutputElement.
     */
    public IOElement getInputOutputElement() {
        return inputOutputElement;
    }

    /**
     * @param inputOutputElement
     *            The inputOutputElement to set.
     */
    public void setInputOutputElement(IOElement inputOutputElement) {
        this.inputOutputElement = inputOutputElement;
    }

    /**
     * @return Returns the timestamp.
     */
    public long getTimestamp() {
        return timestamp;
    }

    /**
     * @param timestamp
     *            The timestamp to set.
     */
    public void setTimestamp(long timestamp) {
        this.timestamp = timestamp;
    }

    /**
     * @return Returns the priority.
     */
    public byte getPriority() {
        return priority;
    }

    /**
     * @param priority
     *            The priority to set.
     */
    public void setPriority(byte priority) {
        this.priority = priority;
    }

    /*
     * (non-Javadoc)
     * 
     * @see java.lang.Object#toString()
     */
    public String toString() {
        //return "FM3 [Priority=" + getPriority() + "] [GPS element=" + getGpsElement() + "] [IO=" + getInputOutputElement() + "] [Timestamp=" + getTimestamp() + "]";
        return getGpsElement() + "," + getInputOutputElement() + "," + getTimestamp() + "#";
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
            dataCodec = new AvlData();

        }

        return dataCodec;
    }

    /**
     * Number of maximum elements encoded is 255
     */
    public byte[] encode(AvlData[] datas) throws CodecException {
        if (datas.length > 255) {
            throw new CodecException("Maximum elements is 255");
        }

        BitOutputStream codecOutputStream = new BitOutputStream();
        DataOutputStream dataOutputStream = new DataOutputStream(codecOutputStream);

        try {
            dataOutputStream.writeByte(getCodecId());
            dataOutputStream.writeByte(datas.length);
            for (int i = 0; i < datas.length; i++) {
                write(codecOutputStream, datas[i]);
            }
            dataOutputStream.writeByte(datas.length);
            dataOutputStream.flush();
        } catch (IOException exception) {
            throw new CodecException("Unable to encode data. ", exception);
        } 

        return codecOutputStream.getByteArray();
    }
    
    public int encode(AvlData[] datas, int length, byte[] buffer, int offsetInBuffer) throws CodecException {
        if (length > 255) {
            throw new CodecException("Maximum elements is 255");
        }
        

        BitOutputStream codecOutputStream = new BitOutputStream(buffer,offsetInBuffer);
        DataOutputStream dataOutputStream = new DataOutputStream(codecOutputStream);

        try {
            dataOutputStream.writeByte(getCodecId());
            dataOutputStream.writeByte(length);
            for (int i = 0; i < length; i++) {
                write(codecOutputStream, datas[i]);
            }
            dataOutputStream.writeByte(length);
            dataOutputStream.flush();
        } catch (IOException exception) {
            throw new CodecException("Unable to encode data. ", exception);
        } 

     return codecOutputStream.getWritenDataLength();
    }

    /**
     * This method writes passed data to passed output stream
     * 
     * @param codecOutputStream
     *            Where to write
     * @param dataElement
     *            What to write
     * @throws IOException
     *             If writing failed
     */
    protected void write(BitOutputStream codecOutputStream, AvlData dataElement) throws IOException {
        DataOutputStream dataOutputStream = new DataOutputStream(codecOutputStream);
        dataOutputStream.writeLong(dataElement.getTimestamp());
        dataOutputStream.writeByte(dataElement.getPriority());
        dataOutputStream.flush();
        GpsElement.write(codecOutputStream, dataElement.getGpsElement());
        IOElement.write(codecOutputStream, dataElement.getInputOutputElement());

    }

    /*
     * (non-Javadoc)
     * 
     * @see com.teltonika.avl.data.codec.DataCodec#decode(byte[])
     */
    public AvlData[] decode(byte[] dataByteArray) throws CodecException {
        BitInputStream codecInputStream = new BitInputStream(dataByteArray);
        DataInputStream dataInputStream = new DataInputStream(codecInputStream);

        AvlData[] result = null;
        try {
            if (dataInputStream.readByte() != getCodecId()) {
                throw new CodecException("Invalid codec id");
            }

            int elements = dataInputStream.readByte() & 0xFF;
            result = new AvlData[elements];

            for (int i = 0; i < elements; i++) {
                result[i] = read(codecInputStream);
            }

            if ((dataInputStream.readByte() & 0xFF) != elements) {
                throw new CodecException("Unable to decode.");
            }
        } catch (IOException exception) {
            throw new CodecException("Unable to decode. ", exception);
        }

        return result;
    }

    /**
     * This method reads Avl data from passed codec input stream
     * 
     * @param codecInputStream
     *            Where from to read data
     * @return Read data
     * @throws IOException
     *             If unable to read data
     */
    protected AvlData read(BitInputStream codecInputStream) throws IOException {
        DataInputStream dataInputStream = new DataInputStream(codecInputStream);
        long timestamp = dataInputStream.readLong();
        byte priority = dataInputStream.readByte();
        GpsElement gpsData = GpsElement.read(codecInputStream);
        IOElement iodata = IOElement.read(codecInputStream);

        return new AvlData(timestamp, gpsData, iodata, priority);
    }

    /*
     * (non-Javadoc)
     * 
     * @see com.teltonika.avl.data.DataCodec#getCodecId()
     */
    public byte getCodecId() {
        return 1;
    }

    /*
     * (non-Javadoc)
     * 
     * @see com.teltonika.avl.data.DataCodec#getCodecClass()
     */
    public Class getCodecClass() {
        return getClass();
    }

}
