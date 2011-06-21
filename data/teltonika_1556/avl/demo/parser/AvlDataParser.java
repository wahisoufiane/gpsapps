package teltonika_1556.avl.demo.parser;


import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;

import teltonika.avl.demo.Version;
import teltonika.avl.demo.tools.Tools;

/**
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 *
 * <p>Avl data parser main class.</p>
 */
public class AvlDataParser {

    /**
  * @param args
     * @throws CodecException 
     * @throws IOException 
  * @throws IOException 
  */
 public static void main(String[] args) throws CodecException, IOException {
  String hexData = null;
  if (args.length == 0) {
   System.out.println("v" + Version.getVersion());
   System.out.println("Usage: java -jar avlparser.jar -|<avl data array in hex>");
   System.out.println("If '-' is specified instead of <avl data array in hex>, data is read from standard input");
   System.exit(1);
  } else {
   if ("-".equals(args[0])) {
    BufferedReader reader = new BufferedReader(new InputStreamReader(System.in));
    hexData = reader.readLine();
   } else {
    hexData = args[0];
   }
  }
  
  // register supported codecs
  CodecStore.getInstance().register(AvlData.getCodec());
  CodecStore.getInstance().register(AvlDataFM4.getCodec());
  CodecStore.getInstance().register(AvlDataGH.getCodec());
  
  byte[] rawData = Tools.hexToBuffer(hexData);

  AvlData codec = CodecStore.getInstance().getSuitableCodec(rawData);
  
  if (codec == null) {
   System.out.println("Cannot find codec to decode supplied data.");
   System.exit(2);
  }
  
  System.out.println("Codec found: " + codec);
  
  AvlData avlData[] = codec.decode(rawData);
  
  System.out.println("AvlData's parsed:" + avlData.length);
  
  for (AvlData item : avlData) {
   System.out.println("AvlData:" + item);
  }
 }
 
}
