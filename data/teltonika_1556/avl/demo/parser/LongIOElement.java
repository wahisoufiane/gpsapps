package teltonika_1556.avl.demo.parser;


import java.util.Hashtable;

/**
 * @author Ernestas Vaiciukevicius (ernestas.vaiciukevicius@teltonika.lt)
 *
 * <p>IOElement with support of "long" (8 byte) property values.</p>
 */
public class LongIOElement extends IOElement {
 
 private Hashtable<Integer, Long> longProperties = new Hashtable();

 public long[] getLongProperty(int id) {
  Long longValue = (Long)longProperties.get(Integer.valueOf(id));
  long[] ret = null;
  
  if (longValue != null) {
   ret = new long[] { id, longValue.longValue() };
  } else {
   int[] intRet = super.getProperty(id);
   if (intRet != null) {
    ret = new long[] { intRet[0], intRet[1] };
   }
  }
  
  return ret;
 }
 
 public void addLongProperty(long[] prop) {
  if (prop[0] < Integer.MIN_VALUE || prop[0] > Integer.MAX_VALUE) {
   throw new IllegalArgumentException("Wrong id value:" + prop[0]);
  }
  
  longProperties.put(Integer.valueOf((int) prop[0]), Long.valueOf(prop[1]));
 }
 
 public void removeLongProperty(int id) {
  longProperties.remove(Integer.valueOf(id));
 }
 
 public int[] getAvailableLongProperties() {
  int[] intRet = super.getAvailableProperties();
  int[] ret = null;
  
  if (intRet == null) {
   intRet = new int[0];
  }

  synchronized (longProperties) {
   ret = new int[intRet.length + longProperties.size()];
   System.arraycopy(intRet, 0, ret, 0, intRet.length);
   
   int ind = intRet.length;
   while (longProperties.keys().hasMoreElements()) {
    Integer key = (Integer) longProperties.keys().nextElement();
    ret[ind++] = key.intValue();
   }
  }

  return ret;
 }
 
 @Override
 public String toString() {
        StringBuffer stringBuffer = new StringBuffer();

        for (Integer id : longProperties.keySet()) {
            stringBuffer.append("[" + id + "=" + longProperties.get(id) + "] ");            
        }
        
  return super.toString() + stringBuffer.toString();
 }
 
 public boolean equals(Object arg0) {
  if (arg0 instanceof LongIOElement) {
   if (! super.equals(arg0)) {
    return false;
   }
   return longProperties.equals(((LongIOElement)arg0).longProperties);
  } else {
   if (longProperties.size() == 0) {
    return super.equals(arg0);
   } else {
    return false;
   }
  }
 }
 
}
