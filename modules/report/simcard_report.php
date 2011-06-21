<?php




?>
<script type="text/javascript" language="javascript">

function days_between(date1, date2) {

    var ONE_DAY = 1000 * 60 * 60 * 24

    var date1_ms = date1.getTime()
    var date2_ms = date2.getTime()
	var difference_ms = date1_ms - date2_ms
    return Math.round(difference_ms/ONE_DAY)

}
function validateMapReport()
{
  
  if(document.getElementById('from_date').value=='')
  { alert("Select From Date"); document.getElementById('from_date').focus();  return 0;  }
  
  if(document.getElementById('to_date').value=='')
  { alert("Select To Date");  document.getElementById('to_date').focus(); return 0; }
  
	var curdt_array = document.getElementById('curdate').value.split("-");   
	var todt_array = document.getElementById('to_date').value.split("-");
	var frdt_array = document.getElementById('from_date').value.split("-");	
	
	var curdate = new Date(curdt_array[0],(curdt_array[1]-1),curdt_array[2]);
	//alert(curdate)
	var todate = new Date(todt_array[0],(todt_array[1]-1), todt_array[2]);
	var frdate = new Date(frdt_array[0],(frdt_array[1]-1), frdt_array[2]);

	var fr_to_diff = days_between(frdate, todate);
	var days_diff = days_between(todate, frdate);

	
	if(fr_to_diff > 0)
	{ alert("From Date should be prior to To Date."); document.getElementById('to_date').select(); return 0;}
	
	
	return 1;
	
}
function sendCSVData()
{
  document.frmKiloData.submit();
}

function showPreloader()
{
	var returnVal = validateMapReport()
	if(returnVal == 1)
	{

		document.getElementById('popup_div').innerHTML = '<div id="loading_txt" >Loading...</div>';
		document.frm_map_filter.submit();
	}
}

function hidePreLoader()
{
	document.getElementById('popup_div').innerHTML = '&nbsp;';

}


$(function() {
	$( "#from_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
$(function() {
	$( "#to_date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		maxDate: 0,
		showOn: "button",
		buttonImage: "images/calendar.gif",
		buttonImageOnly: true,
		dateFormat : "dd-mm-yy"
	});
});
</script>
<form id="frm_map_filter" name="frm_map_filter" method="post" action="" onsubmit="return validateMapReport();"> 
<table class="gridform">
<tr><th colspan="4">Simcard Report</th></tr>
             <input type="hidden" name="curdate" id="curdate" value="<?php echo date('Y-m-d'); ?>" />
         
    <tr>
    <td width="15%" align="right"><span class="form_text">From Date & Time</span></td>
    <td width="35%" align="left">
    <input type="text" name="from_date" id="from_date" readonly="true" tabindex="2" size="12" value="<?php echo $_POST[from_date]; ?>" style="width:140px;"/>&nbsp;

    </td>
    <td width="15%" align="right"><span class="form_text">To Date & Time</span></td>
    <td width="35%">
    <input type="text" name="to_date" id="to_date" size="12" style="width:140px;" tabindex="4" readonly="true" value="<?php echo $_POST[to_date]; ?>" />&nbsp;
 
    </td>
  </tr>
  <tr>
    <td height="33" colspan="4" align="center">
    <input type="button" name="map_filter_btn"   value="Filter" class="click_btn" tabindex="6"  onclick="showPreloader();"/>
    <input type="hidden" name="map_filter_btn" value="Filter" />
    <input type="button" name="map_cancel_btn" id="map_cancel_btn" value="Reset" class="click_btn" onclick="location.href='index.php?ch=distance';" tabindex="7" /> 
    <?php if(isset($_POST[map_filter_btn]) && $_POST[map_filter_btn]!='')  { ?>
    <input type="button" name="map_export_btn" id="map_export_btn" value="Export" class="click_btn" style="font-weight:bold;" onclick="sendCSVData();" /> <?php } ?></td>
  </tr>
</table>
</form>
<div id="popup_div" style=" display:block; border:0px;" >

</div>	
	
<?php
if(isset($_POST[from_date]) && $_POST[to_date]!='')
{ 
//print_r($_POST);
?>
<div style="width:895px; overflow:scroll; overflow-X:hidden;  border:1px solid #dfe9ed; border-top:0px solid #FFF;">

<?php
	

	$sdate = date('Y-m-d', strtotime($_POST[from_date]));
	$edate = date('Y-m-d', strtotime($_POST[to_date]));
	
	
	

	//exit;
	if($_POST[from_date] == $_POST[to_date])
	{
       $q1 = mysql_query("select * from tb_simcard where date='".$sdate."' and clientid='".$_SESSION[userID]."'");

	} else {
  
	  $q1 = mysql_query("SELECT * FROM tb_simcard where date between '".$sdate."' and '".$edate."' and clientid='".$_SESSION[userID]."' ");

	}
	
	
?>	  
<table cellspacing="2" cellpadding="3" border="0" width="100%" class="gridform_final">
      <tbody><tr>
        <th width="10%">S.no</th>
        <th width="30%">Name</th>
        <th width="25%">Sim Number</th>
        <th width="10%">Subscription Paid/Unpaid</th>
        <th width="5%">GSM Number</th>
        <th width="20%">Amount</th>
       </tr>
	  
		<?php
			$i = $j = '0';
		while($result = mysql_fetch_array($q1)){
        // echo '<pre>';   print_r($result);    echo '</pre><br />';
		 echo '<tr>';
		 echo '<td>'.($i+1).'</td>';
		 echo '<td>'.$result[name].'</td>';
		 echo '<td>'.$result[number].'</td>';
		 echo '<td>'.$result[payment_status].'</td>';
		 echo '<td>'.$result[gsm_number].'</td>';
		 echo '<td>'.$result[amount].'</td>';
		 echo '</tr>';
		 if($result[payment_status]=='Paid'){
		 $j++;
		 }
		 $i++;
		}
		?>
        <tr colspan="6" align="right"><td>Total Purchased Simcards:<?php echo $i;?><br />
        Subscription Paid simcards:<?php echo $j;?>
        </td>
       </tr>  
	 
</tbody>

</table>	
	
	
	
</div>	
<?php }?>
<script type="text/javascript">

hidePreLoader();
</script>
