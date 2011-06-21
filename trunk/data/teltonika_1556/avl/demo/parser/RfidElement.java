package teltonika_1556.avl.demo.parser;


import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;


/**
 * <p>
 * Rfid element data
 * </p>
 */
public class RfidElement {

    /**
     * 
     */
    private int siteCode = 0;

    /**
     * 
     */
    private int age = 0;

    /**
     * 
     */
    private int id = 0;

    /**
     * 
     */
    private byte tagType = 0;

    /**
     * 
     */
    private byte alarmByte = 0;

    /**
     * 
     */
    private byte reedSwitchCounter = 0;

    /**
     * 
     */
    private byte repeatRate = 0;
    
    /**
     * 
     */
    private int signalStrength = 0;

    /**
     * Empty default construcor
     *
     */
    public RfidElement() {
        // Empty default constructor
    }
    
    /**
     * Creates new rfid element with passed values
     * @param age
     * @param alarmByte
     * @param id
     * @param reedSwitchCounter
     * @param repeatRate
     * @param siteCode
     * @param tagType
     */
    public RfidElement(int age, byte alarmByte, int id, byte reedSwitchCounter, byte repeatRate, int siteCode, byte tagType, int signalStrength) {
        setAge(age);
        setAlarmByte(alarmByte);
        setId(id);
        setReedSwitchCounter(reedSwitchCounter);
        setRepeatRate(repeatRate);
        setSiteCode(siteCode);
        setTagType(tagType);
        setSignalStrength(signalStrength);
    }
    
    /**
     * @return Returns the age.
     */
    public int getAge() {
        return age;
    }

    /**
     * @param age The age to set.
     */
    public void setAge(int age) {
        this.age = age;
    }

    /**
     * @return Returns the alarmByte.
     */
    public byte getAlarmByte() {
        return alarmByte;
    }

    /**
     * @param alarmByte The alarmByte to set.
     */
    public void setAlarmByte(byte alarmByte) {
        this.alarmByte = alarmByte;
    }

    /**
     * @return Returns the id.
     */
    public int getId() {
        return id;
    }

    /**
     * @param id The id to set.
     */
    public void setId(int id) {
        this.id = id;
    }
    
    /**
     * @return Returns the reedSwitchCounter.
     */
    public byte getReedSwitchCounter() {
        return reedSwitchCounter;
    }

    /**
     * @param reedSwitchCounter The reedSwitchCounter to set.
     */
    public void setReedSwitchCounter(byte reedSwitchCounter) {
        this.reedSwitchCounter = reedSwitchCounter;
    }
    
    /**
     * @return Returns the repeatRate.
     */
    public byte getRepeatRate() {
        return repeatRate;
    }
    
    /**
     * @param repeatRate The repeatRate to set.
     */
    public void setRepeatRate(byte repeatRate) {
        this.repeatRate = repeatRate;
    }

    /**
     * @return Returns the siteCode.
     */
    public int getSiteCode() {
        return siteCode;
    }
    
    /**
     * @param siteCode The siteCode to set.
     */
    public void setSiteCode(int siteCode) {
        this.siteCode = siteCode;
    }
    
    /**
     * @return Returns the tagType.
     */
    public byte getTagType() {
        return tagType;
    }

    /**
     * @param tagType The tagType to set.
     */
    public void setTagType(byte tagType) {
        this.tagType = tagType;
    }
        
    /**
     * @return Returns the signalStrength.
     */
    public int getSignalStrength() {
        return signalStrength;
    }

    /**
     * @param signalStrength The signalStrength to set.
     */
    public void setSignalStrength(int signalStrength) {
        this.signalStrength = signalStrength;
    }

    /* (non-Javadoc)
     * @see java.lang.Object#toString()
     */
    public String toString() { 
        return "[Age=" + getAge() + "] [Alarm byte=" + getAlarmByte() + "] [Id=" + getId() + "] [Read switch counter=" + getReedSwitchCounter() + "] [Repeat rate=" + getRepeatRate() + "] [Site code=" + getSiteCode() + "] [Signal strength=" + getSignalStrength() + "]";
    }

    /* (non-Javadoc)
     * @see java.lang.Object#equals(java.lang.Object)
     */
    public boolean equals(Object arg0) {
        if (arg0 instanceof RfidElement) {
            RfidElement rfidData = (RfidElement) arg0;
            
            if (getAge() == rfidData.getAge() && 
                    getAlarmByte() == rfidData.getAlarmByte() &&
                    getId() == rfidData.getId() &&
                    getReedSwitchCounter() == rfidData.getReedSwitchCounter() &&
                    getRepeatRate() == rfidData.getRepeatRate() &&
                    getSiteCode() == rfidData.getSiteCode() &&
                    getTagType() == rfidData.getTagType() &&
                    getSignalStrength() == rfidData.getSignalStrength()) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Reads rfid element from codec input stream, written by write method
     * @param inputStream Input stream to read from
     * @return Returns read rfid element
     * @throws IOException Thrown by passed input stream
     */
    public static RfidElement read(DataInputStream dataInputStream) throws IOException{
        int age = dataInputStream.readInt();
        byte alarmByte = dataInputStream.readByte();
        int id = dataInputStream.readInt();
        byte reedSwitchCounter = dataInputStream.readByte();
        byte repeatRate = dataInputStream.readByte();
        int siteCode = dataInputStream.readInt();
        byte tagType = dataInputStream.readByte();
        int signalStrength = (dataInputStream.readByte() & 0xFF);
        
        return new RfidElement(age, alarmByte, id, reedSwitchCounter, repeatRate, siteCode, tagType, signalStrength);
    }
        
    /**
     * Writes rfid element to output stream. Can be read by read method
     * @param outputStream Output stream to write to
     * @param rfidElement Rfid element to write
     * @throws IOException Thrown by passed output stream
     */
    public static void write(DataOutputStream dataOutputStream, RfidElement rfidElement) throws IOException{
        dataOutputStream.writeInt(rfidElement.getAge());
        dataOutputStream.writeByte(rfidElement.getAlarmByte());
        dataOutputStream.writeInt(rfidElement.getId());
        dataOutputStream.writeByte(rfidElement.getReedSwitchCounter());
        dataOutputStream.writeByte(rfidElement.getRepeatRate());
        dataOutputStream.writeInt(rfidElement.getSiteCode());
        dataOutputStream.writeByte(rfidElement.getTagType());
        dataOutputStream.writeByte(rfidElement.getSignalStrength());
    }

    /* (non-Javadoc)
     * @see java.lang.Object#hashCode()
     */
    public int hashCode() {
        return getId() * getSiteCode();
    }

    
}
