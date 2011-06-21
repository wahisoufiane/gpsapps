package teltonika_1556.avl.demo.tcplistener;

import java.io.ByteArrayOutputStream;
import java.io.DataInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;

public class ByteWrapper {
     
    public static byte[] wrap(byte[] data){
        ByteArrayOutputStream baos = new ByteArrayOutputStream();
        DataOutputStream dos = new DataOutputStream(baos);

        try{
         dos.write(new byte[]{0, 0, 0, 0});
         dos.writeInt(data.length);
         dos.write(data);
         dos.writeInt(getCrc(data));
         dos.flush();
        } catch (IOException e){
            // This one shuld never hapend
        }
        
        return baos.toByteArray();
    }
    
    public static int getCrc(byte[] buffer) {
     return getCrc16(buffer, 0, buffer.length,  0xA001, 0);
    }

    public static int getCrc16(byte[] buffer, int offset, int bufLen, int polynom, int preset) {
        preset &= 0xFFFF;
        polynom &= 0xFFFF;

        int crc = preset;
        for (int i = 0; i < bufLen; i++) {
            int data = buffer[(i + offset)%buffer.length] & 0xFF;
            crc ^= data;
            for (int j = 0; j < 8; j++) {
                if ((crc & 0x0001) != 0) {
                    crc = (crc >> 1) ^ polynom;
                } else {
                    crc = crc >> 1;
                }
            }
        }

        return crc & 0xFFFF;
    }
    
 public static byte[] unwrapFromStream(InputStream is, long timeout) throws IOException{
        
        long startTime = System.currentTimeMillis();
        
        int zeroCount = 0;
        while (zeroCount < 4){
            if (is.available() != 0) {
                int read = is.read();
                
                if (read == -1){
                    return null;
                }
                
                if (read == 0){
                    zeroCount++;
                } else {
                    zeroCount = 0;
                }
            } else {            
             if (System.currentTimeMillis() - startTime > timeout){
                 return null;
             }
             
    sleep(100);    
            }
        }
        
        DataInputStream dis = new DataInputStream(is);
        while (is.available() < 4){
            if (System.currentTimeMillis() - startTime > timeout){
                return null;
            }
                        
   sleep(100);            
        }
        
        int dataLength = dis.readInt();
        
        while (is.available() < dataLength){
            if (System.currentTimeMillis() - startTime > timeout){
                return null;
            }
            
   sleep(100);   
        }
        
        if (dataLength > 0xFFFF) {
            // most likely incoming data is invalid - we would not use such big packets?
         throw new IOException("Data packet to large (>0xffff)");
        }
        
        byte[] data = new byte[dataLength];
        is.read(data);
        
        while (is.available() < 4){
            if (System.currentTimeMillis() - startTime > timeout){
                return null;
            }
               
   sleep(100);   
        }
        
        int crc = dis.readInt();
        
        if (crc != getCrc(data)){
            return null;
        } 
          
        return data;
 }
 
    /**
     * Unwrapps from input stream without timout. input stream can throw exception to prevent 
     * blocking.
     * @param is Input stream to read from
     * @return Returns read buffer
     * @throws IOException Exception from input stream or if crc test fails
     */
    public static byte[] unwrapFromStream(InputStream is) throws IOException{
        
        int zeroCount = 0;
        while (zeroCount < 4) {
            switch (is.read()) {
            case -1:{
                return null;
            } 
            case 0:{
                zeroCount++;
                break;
            }
            default :{
                zeroCount = 0;
                break;
            }
            }
        }
        
        DataInputStream dis = new DataInputStream(is);
        
        int dataLength = dis.readInt();
        
        if (dataLength > 0xFFFF) {
            // most likely incoming data is invalid - we would not use such big packets?
         throw new IOException("Data packet too large (>0xffff)");
        }
        
        byte[] data = new byte[dataLength];
        dis.readFully(data);
                
        int correctCrc = getCrc(data);
        int crc = dis.readInt();
        
        if (crc != correctCrc) {            
            throw new IOException("Crc test failed: " + crc + " != " + correctCrc);
        } 
          
        return data;
    }
    
    private static void sleep(long time){
        try {
            Thread.sleep(time);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
    }
}
