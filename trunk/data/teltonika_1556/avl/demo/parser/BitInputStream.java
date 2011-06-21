package teltonika_1556.avl.demo.parser;
/*
 * Created on 2005.2.17
 *
 * TODO To change the template for this generated file go to
 * Window - Preferences - Java - Code Style - Code Templates
 */


import java.io.ByteArrayInputStream;
import java.io.EOFException;
import java.io.IOException;
import java.io.InputStream;

/**
 * @author Vincentas Vienozinskis
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 * <p>
 * This class allows to read bits one by one from underlying array.
 * First bit read will be lowest bit of first array element. The last 
 * bit read will be the highest bit of last byte in array.
 * </p>
 */
public class BitInputStream extends InputStream {

 private InputStream iStream;
 private int lastByte;
 private int lastByteBit = 0 ;
    
    /**
     * Creates new bit input stream with array to read from
     * @param data Array of data to read from
     */
    public BitInputStream(byte[] data){
        byte[] data2 = new byte[data.length];
        System.arraycopy(data, 0, data2, 0, data.length);
        iStream = new ByteArrayInputStream(data2);
    }
    
    /**
     * Creates bit input stream 
     * @param iStream underlying input stream 
     */
    public BitInputStream(InputStream iStream) {
     this.iStream = iStream;
    }
    
    /**
     * Reads number of bits and returns them as long value
     * @param bits Number of bits to read. Maximum bits that 
     * can be read in one time is number of bits in long data 
     * typs i.e. 64. If this value is greater value of only 64 bits 
     * will be returned
     * @return Read bits as long value. If for instance 3 bits 
     * are read and first is 0 next is 1 and last is 1 returned 
     * value will be long value representing binary number 110
     */
    public long readBits(int bits) throws IOException {
        long result = 0;
        
        int resBit = 0;
        
        while (resBit < bits) {
         // read next byte from underlying input stream if needed
         if (lastByteBit <= 0) {
          lastByte = iStream.read();
          if (lastByte < 0) {
           throw new EOFException();
          }
          lastByteBit = 8;
         }
         
         int copyBits = Math.min(bits - resBit, lastByteBit);
         int bitmask = (0xFF >>> (8-copyBits))  << (8-lastByteBit);
         
         result = result | (((long)(lastByte & bitmask) >>> (8-lastByteBit)) << resBit);
         
         lastByteBit -= copyBits;
         resBit += copyBits;
        }
         
        return result;
    }

    /* (non-Javadoc)
     * @see java.io.InputStream#read()
     */
    public int read() throws IOException {
     try {
      return (int) readBits(8);
     } catch (EOFException e) {
   return -1;
  }
    }

    /* (non-Javadoc)
     * @see java.io.InputStream#available()
     */
    public int available() throws IOException { 
        return iStream.available() + Math.min(lastByteBit, 1);
    }
    
}
