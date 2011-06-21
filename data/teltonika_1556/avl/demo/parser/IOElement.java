package teltonika_1556.avl.demo.parser;
/**
 * 
 */

import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.util.Arrays;


/**
 * @author Vincentas Vienozinskis
 * 
 * <p>
 * </p>
 */
public class IOElement {

    /**
     * 
     * @param id
     *            Id of property
     * @return Returns property associated with id, if property not found null
     *         is returned
     */
    public int[] getProperty(int id) {
        int[] result = null;
        int propertyIndex = getPropertyIndex(id);

        if (propertyIndex != -1) {
            int value = propertyValues[propertyIndex];

            result = new int[] { id, value };
            if (value == Integer.MAX_VALUE) {
                result[1] = 1;
            } else if (value == Integer.MIN_VALUE) {
                result[1] = 0;
            }
        }

        return result;
    }

    /**
     * Number of properties added
     */
    private int propertiesCount = 0;

    /**
     * Adds property to io element. If element already contains property,
     * overwrite it.
     * 
     * @param property
     *            Property to add
     * @throws UnacceptablePropertyException
     *             If binary property with higher than 255 id is added or id is
     *             negative
     */
    public void addProperty(int[] property) throws IllegalArgumentException {
        if (property[0] > 255 || property[0] < 0) {
            throw new IllegalArgumentException("Property id is invalid " + property[0]);
        }

        int value = property[1];

        if (property[1] == 1) {
            /*
             * True is indicated by Integer.MAX_VALUE;
             */
            property[1] = Integer.MAX_VALUE;
        } else if (property[1] == 0) {
            /*
             * False is indicated by Integer.MIN_VALUE
             */
            property[1] = Integer.MIN_VALUE;
        }

        setPropertyValue(property[0], value);
    }

    /*
     * (non-Javadoc)
     * 
     * @see java.lang.Object#toString()
     */
    public String toString() {
        StringBuffer stringBuffer = new StringBuffer();
        
        for (int i = 0; i < propertyIds.length && propertyIds[i] != 0; i++) {
            stringBuffer.append("[" + (propertyIds[i] - 1) + "=" + propertyValues[i] + "] ");            
        }

        return stringBuffer.toString();
    }

    /*
     * (non-Javadoc)
     * 
     * @see java.lang.Object#equals(java.lang.Object)
     */
    public boolean equals(Object arg0) {
        if (arg0 instanceof IOElement) {
            IOElement data = (IOElement) arg0;

            if (data.propertiesCount != propertiesCount) {
                return false;
            }

            int[] a = null;
            int[] b = null;

            for (int i = 0; i < propertyIds.length && propertyIds[i] != 0; i++) {
                a = getProperty(propertyIds[i] - 1);
                b = data.getProperty(propertyIds[i] - 1);

                if (a == null || b == null) {
                    return false;
                }

                if (!Arrays.equals(a, b)) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Reads io element from codec input stream, written by write method
     * 
     * @param codecInputStream
     *            Input stream to read from
     * @return Returns read io element
     * @throws IOException
     *             Thrown by passed input stream, or if unable to build io data
     *             from input stream
     */
    public static IOElement read(BitInputStream codecInputStream) throws IOException {
        DataInputStream dataInputStream = new DataInputStream(codecInputStream);
        IOElement result = new IOElement();

        int properties = dataInputStream.readUnsignedByte();
        int propertiesRead = 0;
        int propertiesIndex = 0;

        while (propertiesRead < properties) {
            if (codecInputStream.readBits(1) == 1) {
                byte type = (byte) codecInputStream.readBits(TYPE_LENGTH);

                switch (type) {
                case BOOLEAN_TYPE: {
                    result.setPropertyValue(propertiesIndex, (codecInputStream.readBits(1) == 0) ? Integer.MIN_VALUE : Integer.MAX_VALUE);
                    break;
                }
                case INTEGER_TYPE: {
                    result.setPropertyValue(propertiesIndex, dataInputStream.readInt());
                    break;
                }
                default:
                    throw new IOException("Unknown type value " + type);
                }

                propertiesRead++;
            }

            propertiesIndex++;
        }

        return result;
    }

    /**
     * Indicates that next field is of boolean type (1 bit)
     */
    private static final byte BOOLEAN_TYPE = 0;

    /**
     * Indicates that next field is of integer type (4 bytes)
     */
    private static final byte INTEGER_TYPE = 1;

    /**
     * Number of bits to identify field type
     */
    private static final byte TYPE_LENGTH = 3;

    /**
     * Writes io element to output stream. Can be read by read method
     * 
     * @param codecOutputStream
     *            Output stream to write to
     * @param ioElement
     *            Io element to write
     * @throws IOException
     *             Thrown by passed output stream
     */
    public static void write(BitOutputStream codecOutputStream, IOElement ioElement) throws IOException {
        DataOutputStream dataOutputStream = new DataOutputStream(codecOutputStream);
        dataOutputStream.writeByte(ioElement.propertiesCount);

        int id = 0;
        int toAdd = ioElement.propertiesCount;

        while (toAdd > 0) {
            int index = ioElement.getPropertyIndex(id);
            if (index != -1) {
                codecOutputStream.writeBits(1, 1);
                int value = ioElement.propertyValues[index];

                if (value == Integer.MAX_VALUE) {
                    codecOutputStream.writeBits(BOOLEAN_TYPE, TYPE_LENGTH);
                    codecOutputStream.writeBits(1, 1);
                } else if (value == Integer.MIN_VALUE) {
                    codecOutputStream.writeBits(BOOLEAN_TYPE, TYPE_LENGTH);
                    codecOutputStream.writeBits(0, 1);
                } else {
                    codecOutputStream.writeBits(INTEGER_TYPE, TYPE_LENGTH);
                    dataOutputStream.writeInt(value);
                }

                toAdd--;
            } else {
                codecOutputStream.writeBits(0, 1);
            }

            id++;
        }
    }

    private static final int increaseSize = 10;

    private int[] propertyIds = new int[increaseSize];

    private int[] propertyValues = new int[increaseSize];

    /**
     * Returns all available id's of properties
     * 
     * @return Array of id's. Array is empty is no properties available
     */
    public int[] getAvailableProperties() {
        int[] tempResult = new int[propertyIds.length];
        int index = 0;
        for (int i = 0; i < tempResult.length; i++) {
            if (propertyIds[i] != 0) {
                tempResult[index] = propertyIds[i] - 1;
                index++;
            }
        }

        int[] result = new int[index];
        System.arraycopy(tempResult, 0, result, 0, index);

        return result;
    }

    /**
     * Finds index of property value
     * 
     * @param propertyId
     *            Id Of property whose value is needed
     * @return Returns Index in values array or null if property not found
     */
    private int getPropertyIndex(int propertyId) {
        propertyId++;

        for (int i = 0; i < propertyIds.length; i++) {
            if (propertyIds[i] == propertyId) {
                return i;
            }
        }

        return -1;
    }

    /**
     * This method writes property value to array. If this property's id is in
     * id's array, new value is written to values array, if not, id is written to
     * first free array index. Free array indexes is that one that contains zero.
     * If array is full (no free indexes) array is extended.
     * 
     * @param propertyId
     *            Property id
     * @param propertyValue
     *            Property value
     */
    private void setPropertyValue(int propertyId, int propertyValue) {
        int propertyIndex = getPropertyIndex(propertyId);

        if (propertyIndex == -1) {
            for (int i = 0; i < propertyIds.length; i++) {
                if (propertyIds[i] == 0) {
                    propertyIndex = i;
                    break;
                }
            }

            if (propertyIndex == -1) {
                /*
                 * Increase size of arrays
                 */
                propertyIndex = propertyIds.length;

                int temp[] = new int[propertyIds.length + increaseSize];
                System.arraycopy(propertyIds, 0, temp, 0, propertyIds.length);
                propertyIds = temp;

                temp = new int[propertyValues.length + increaseSize];
                System.arraycopy(propertyValues, 0, temp, 0, propertyValues.length);
                propertyValues = temp;
            }

            propertyIds[propertyIndex] = propertyId + 1;
            propertiesCount++;

        }

        propertyValues[propertyIndex] = propertyValue;
    }
    
    /**
     * This method adds all properties from passed ioelement to this element.
     * 
     * @param element
     *            Element to read from
     * @param overwrite
     *            Do owervrite values if present in this element
     */
    public void addAll(IOElement element, boolean overwrite) {
        int[] available = element.getAvailableProperties();
        for (int i = 0; i < available.length; i++) {
            if (getProperty(available[i]) == null || overwrite) {
                addProperty(element.getProperty(available[i]));
            }
        }
    }
}
