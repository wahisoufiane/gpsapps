package teltonika_1556.avl.demo.parser;


import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;


/**
 * <p>
 * Class containing gps information
 * </p>
 */
public class GpsElement {

    /**
     * Value by which the x and y coordinates are multiplied (no float type)
     */
    public static final long WGS_PRECISION = 10000000;

    /**
     * X coordinate in WGS format, multiplied by WGS_PRECISION
     */
    private int x = 0;

    /**
     * Y coordinate in WGS format, multiplied by WGS_PRECISION
     */
    private int y = 0;

    /**
     * Speed in km/h
     */
    private short speed = 255;

    /**
     * Direction in degrees, 0 equals north, increasing clockwise (45 equals
     * north east)
     */
    private short angle = 0;

    /**
     * Altitude in meters from see level
     */
    private short altitude = 0;

    /**
     * Number of visible satellites
     */
    private byte satellites;

    /**
     * Empty default constructor
     * 
     */
    public GpsElement() {
        // Empty default constructor
    }

    /**
     * Creates new GPS data with values
     * 
     * @param x
     *            X coordinate in WGS format multiplied by WGS_PRECISION
     * @param y
     *            Y coordinate in WGS format multiplied by WGS_PRECISION
     * @param altitude
     *            Altitude in meters from sea level
     * @param angle
     *            Angle of movement 0 equals NORTH increasing clockwise
     * @param satellites
     *            Number of visible satellites max 12
     * @param speed
     *            Speed in km/h
     * @throws IllegalAccessException
     *             If any of passed parameters is illegal, see setters
     */
    public GpsElement(int x, int y, short altitude, short angle, byte satellites, short speed) {
        setX(x);
        setY(y);
        setAltitude(altitude);
        setAngle(angle);
        setSatellites(satellites);
        setSpeed(speed);
    }

    /**
     * @return Returns altitude in meters from see level
     */
    public short getAltitude() {
        return altitude;
    }

    /**
     * @param altitude
     *            Altitude in meters from see level
     */
    public void setAltitude(short altitude) {
        this.altitude = altitude;
    }

    /**
     * @return Returns direction in degrees, 0 equals north, increasing
     *         clockwise (45 equals north east)
     */
    public short getAngle() {
        return angle;
    }

    /**
     * @param angle
     *            Direction in degrees, 0 equals north, increasing clockwise
     *            (45 equals north east)
     * @throws IllegalArgumentException
     *             if angle is less 0 and greater than 360
     */
    public void setAngle(short angle) {
        if (angle < 0 || angle > 360) {
            throw new IllegalArgumentException("Acceptable angle is [0..360 " + angle);
        }
        this.angle = angle;
    }

    /**
     * @return Returns Y coordinate in WGS format, multiplied by WGS_PRECISION
     */
    public int getY() {
        return y;
    }

    /**
     * @param y
     *            Y coordinate in WGS format, multiplied by WGS_PRECISION y to
     *            set.
     * @throws IllegalArgumentException
     *             If y is less than -90 * WGS_PRECISION and greater than 90 *
     *             WGS_PRECISION
     */
    public void setY(int y) {
        if (y < -90 * WGS_PRECISION || y > 90 * WGS_PRECISION) {
            throw new IllegalArgumentException("Acceptable y value is [" + (-90 * WGS_PRECISION) + ".." + (90 * WGS_PRECISION) + "] " + y);
        }
        this.y = y;
    }

    /**
     * @return Returns number of visible satellites
     */
    public byte getSatellites() {
        return satellites;
    }

    /**
     * @param satellites
     *            Number of visible satellites
     * @throws IllegalArgumentException
     *             If satellites is negative or greater than 12
     */
    public void setSatellites(byte satellites) {
        this.satellites = satellites;
    }

    /**
     * @return Returns speed in km/h
     */
    public short getSpeed() {
        return speed;
    }

    /**
     * @param speed
     *            Speed in km/h
     * @throws IllegalArgumentException
     *             If speed is negative or greater than 255
     */
    public void setSpeed(short speed) {
        this.speed = speed;
    }

    /**
     * @return Returns X coordinate in WGS format, multiplied by WGS_PRECISION
     */
    public int getX() {
        return x;
    }

    /**
     * @param x
     *            X coordinate in WGS format, multiplied by WGS_PRECISION
     * @throws IllegalArgumentException
     *             If x is less than -180 * WGS_PRECISION and greater than 180 *
     *             WGS_PRECISION
     */
    public void setX(int x) {
        if (x < -180 * WGS_PRECISION || x > 180 * WGS_PRECISION) {
            throw new IllegalArgumentException("Acceptable x value is [" + (-180 * WGS_PRECISION) + ".." + (180 * WGS_PRECISION) + "] " + x);
        }
        this.x = x;
    }

    /*
     * (non-Javadoc)
     * 
     * @see java.lang.Object#toString()
     */
    public String toString() {
        //return "[X=" + getX() + "] [Y=" + getY() + "] [Speed=" + getSpeed() + "] [Angle=" + getAngle() + "] [Altitude=" + altitude + "] [Satellites=" + getSatellites() + "]";
        return  getX() + "," + getY() + "," + getSpeed() + "," + getAngle() + "," + altitude + "," + getSatellites() ;
    }

    /**
     * Reads GPS element from codec input stream, written by write method
     * 
     * @param inputStream
     *            Input stream to read from
     * @return Returns read GPS element
     * @throws IOException
     *             Thrown by passed input stream, or if unable to read gps data
     *             from input stream
     */
    public static GpsElement read(BitInputStream inputStream) throws IOException {
        DataInputStream dataInputStream = new DataInputStream(inputStream);
        int x = dataInputStream.readInt();
        int y = dataInputStream.readInt();
        short altitude = dataInputStream.readShort();
        short angle = dataInputStream.readShort();
        byte satellites = dataInputStream.readByte();
        short speed = dataInputStream.readShort();

        try {
            return new GpsElement(x, y, altitude, angle, satellites, speed);
        } catch (IllegalArgumentException illegalArgumentException) {
            throw new IOException("Unable to read GPS element. " + illegalArgumentException);
        }
    }

    /**
     * Writes GPS element to output stream. Can be read by read method
     * 
     * @param outputStream
     *            Output stream to write to
     * @param gpsElement
     *            GPS element to write
     * @throws IOException
     *             Thrown by passed output stream
     */
    public static void write(BitOutputStream outputStream, GpsElement gpsElement) throws IOException {
        DataOutputStream dataOutputStream = new DataOutputStream(outputStream);
        dataOutputStream.writeInt(gpsElement.getX());
        dataOutputStream.writeInt(gpsElement.getY());
        dataOutputStream.writeShort(gpsElement.getAltitude());
        dataOutputStream.writeShort(gpsElement.getAngle());
        dataOutputStream.writeByte(gpsElement.getSatellites());
        dataOutputStream.writeShort(gpsElement.getSpeed());
        dataOutputStream.flush();
    }

    /*
     * (non-Javadoc)
     * 
     * @see java.lang.Object#hashCode()
     */
    public int hashCode() {
        return getX() + getY() + getSpeed() + getSatellites();
    }
    

    /* (non-Javadoc)
     * @see de.enough.polish.io.Externalizable#read(java.io.DataInputStream)
     */
    public void read(DataInputStream in) throws IOException {
        setX(in.readInt());
        setY(in.readInt());
        setAltitude(in.readShort());
        setAngle(in.readShort());
        setSatellites(in.readByte());
        setSpeed(in.readShort());
    }

    /* (non-Javadoc)
     * @see de.enough.polish.io.Externalizable#write(java.io.DataOutputStream)
     */
    public void write(DataOutputStream out) throws IOException {        
        out.writeInt(getX());
        out.writeInt(getY());
        out.writeShort(getAltitude());
        out.writeShort(getAngle());
        out.writeByte(getSatellites());
        out.writeShort(getSpeed());        
    }
}
