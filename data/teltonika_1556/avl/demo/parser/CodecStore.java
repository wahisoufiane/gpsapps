/**
 * 
 */
package teltonika_1556.avl.demo.parser;

import java.util.Enumeration;
import java.util.Vector;

/**
 * @author Vincentas Vienozinskis
 * 
 * <p>
 * </p>
 */
public class CodecStore {

    private static CodecStore instance = null;
    
    public static synchronized CodecStore getInstance() {
        if (instance == null) {
            instance = new CodecStore();
            
        }

        return instance;
    }
    
    /**
     * Empty hidden constructor
     *
     */
    private CodecStore() {
        // Empty default constructor
    }    
   
    private final Vector registeredCodecs = new Vector();
    
    /**
     * Registers codec
     * 
     * @param dataCodec
     *            Codec to register
     */
    public void register(AvlData dataCodec) {
        registeredCodecs.addElement(dataCodec);
        //System.out.println("Registered codec (id=" + dataCodec.getCodecId() + ") :" + dataCodec);
    }
    
    /**
     * Returns suitable codec to encode data
     * 
     * @param dataToEncode
     *            Data to encode
     * @return Returns dataCodec or null if no suitable codec found
     */
    public synchronized AvlData getSuitableCodec(AvlData avlData) {
        for (Enumeration codecs = registeredCodecs.elements(); codecs.hasMoreElements();) {
            AvlData dataCodec = (AvlData) codecs.nextElement();
            if (dataCodec.getCodecClass().equals(avlData.getClass())) {
                return dataCodec;
            }
        }

        return null;
    }
    
    /**
     * Returns suitable codec to decode byte buffer
     * 
     * @param dataToDecode
     *            Byte buffer to decode
     * @return Returns dataCodec or null if no suitable codec found
     */
    public synchronized AvlData getSuitableCodec(byte[] dataToDecode) {
        for (Enumeration codecs = registeredCodecs.elements(); codecs.hasMoreElements(); ) {
            AvlData dataCodec = (AvlData) codecs.nextElement();
            if (dataToDecode[0] == dataCodec.getCodecId()) {
                return dataCodec;
            }            
        }
        
        return null;
    }
}
