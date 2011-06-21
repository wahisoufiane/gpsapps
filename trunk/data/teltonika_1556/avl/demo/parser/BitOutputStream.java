package teltonika_1556.avl.demo.parser;


import java.io.IOException;
import java.io.OutputStream;

/**
 * @author Vincentas Vienozinskis
 * <p>
 * This class allows to write bits to it and later retrieve byte array with written bits.
 * Retrieved array can be user with BitInputStream to read written bits.
 * </p>
 */
public class BitOutputStream extends OutputStream{

    /**
     * Where to write values
     */
    private byte[] buffer = null;
    
    /**
     * Index of next bit to write
     */
    private int bitIndex = 0;
    
    private boolean resizableBuffer = true;
    private int offsetInBuffer = 0;
    
    public BitOutputStream(){
        buffer = new byte[100];
    }
    
    public BitOutputStream(byte[] buffer,int offsetInBuffer){
        this.buffer = buffer;
        this.offsetInBuffer = offsetInBuffer;
        this.bitIndex = offsetInBuffer*8;
        this.resizableBuffer = false;
    }
    /**
     * Writes value to byte array
     * @param value Value to write.
     * @param bits number of bits to write. First bit is lowes bit in value.
     * Maximum bits to write is 64 if this value is greater than 64 it is
     * assigned 64 anyway
     * @return value written
     */
    public long writeBits(long value, int bits)throws IOException{
        if (bits > 64) {
            bits = 64;
        }
        
        if ((bitIndex + bits) / 8 >= buffer.length){
         if(!resizableBuffer){
          throw new IOException("data doesnt fit");
         }
            extendBuffer(20);
        }

        long result = 0;
        
        int index = 0;
        int offset = 0;
        for (int i = 0; i < bits; i++){
            index = bitIndex / 8;
            offset = bitIndex % 8;
            
            result = result | (value & (1L << i));
            
            if ((value & (1L << i)) == 0){                
                buffer[index] = (byte) (buffer[index] & (0xFF ^ (1 << offset)));                                
            } else {
                buffer[index] = (byte) (buffer[index] | (1 << offset));                
            }
            
            bitIndex++;
        }
        
        return result;
    }
    
    /**
     * Extends bit array by size in bytes
     * @param bySize Number af bytes to extend array
     */
    private void extendBuffer(int bySize){
        byte[] temp = new byte[buffer.length + bySize];
        System.arraycopy(buffer, 0, temp, 0, buffer.length);
        buffer = temp;
    }
    
    /**
     * 
     * @return Returns array with values written to it
     */
    public byte[] getByteArray(){
        byte[] result = new byte[bitIndex / 8 + ((bitIndex % 8 == 0) ? 0 : 1)-offsetInBuffer];
        System.arraycopy(buffer, offsetInBuffer , result, 0, result.length);
        return result;
    }
    
    public int getWritenDataLength(){
     return (bitIndex / 8 + ((bitIndex % 8 == 0) ? 0 : 1))-offsetInBuffer;
    }

    /* (non-Javadoc)
     * @see java.io.OutputStream#write(int)
     */
    public void write(int arg0) throws IOException {
        writeBits(arg0, 8);        
    }
}
