package teltonika_1556.avl.demo.parser;


/**
 * @author Vincentas Vienozinskis
 * 
 * <p>
 * </p>
 */
public class CodecException extends Exception {

    public CodecException(String message) {
        super(message);
    }
    
    public CodecException(String message, Throwable throwable) {
        super(message + throwable);
    }
}
