<div>Click to <a href="#" onclick="refreshHomeMapTable('<?php echo $_SESSION[clientID];?>','<?php echo date("d-m-Y");?>')">Refresh</a></div>

<div id="homeMap">
<table width="100%" class="gridform_final" border="0" cellpadding="3" cellspacing="2">
<tr>
	<th width="20%">Device&nbsp;Name</th><th width="30%">Location</th><th width="15%">Status</th><th width="10%">OdoMeter</th>			<th width="10%">Subscription Exp. Date</th>
</tr>
<?php
	if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription,tb_clientinfo WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=ci_id AND ci_id=".$_SESSION[clientID]." ORDER BY di_deviceName,di_deviceId ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Client" && $recordUserInfo[ui_isAdmin] == "0" && $recordUserInfo[ui_roleId] == "1")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId=".$_SESSION[clientID]." AND di_assignedUserId = ".$_SESSION[userID]." ORDER BY di_deviceName,di_deviceId ASC";
	}
	else if($recordUserInfo[ci_clientType] == "Reseller")
	{
		$getDevice = "SELECT * FROM tb_deviceinfo,tb_clientinfo,tb_client_subscription WHERE tcs_isActive = 1 AND tcs_deviceId = di_id AND di_status = 1 AND di_clientId = ci_id AND ci_clientId=".$_SESSION[clientID]." ORDER BY di_deviceName,di_deviceId ASC";
	}
	//echo $getDevice;
	$resDevice= $db->query($getDevice);	
	if($db->affected_rows > 0 )
	{
	while ($fetDevice = $db->fetch_array($resDevice)) 
	{
		if($fetDevice[di_deviceName])
			$devName = $fetDevice[di_deviceName];
		else
			$devName = $fetDevice[di_deviceId];
		
		$renewDate = date("d-m-Y",strtotime("-1 days ".($fetDevice[tcs_noOfMonths]) ."months ".$fetDevice[tcs_renewalDateFrom]));
				
		$odoMeter = "";
		$getReading = "SELECT SUM(tmsi_kmpd) as dist,tmsi_imei FROM tb_speed_meter_info WHERE tmsi_clientId = ".$_SESSION[clientID]." AND tmsi_imei = '".$fetDevice[di_imeiId]."' group by tmsi_imei";
		
		$resReading = $db->query($getReading);
		
		if($db->affected_rows > 0 )
		{
			$fetReading = @mysql_fetch_assoc($resReading);
			$odoMeter = $fetDevice[di_odoMeter] + $fetReading[dist];
		}
		else
		{
			$odoMeter = $fetDevice[di_odoMeter]+$totKm;
		}
		

?>
		<tr><td><?php echo $devName;?></td><td>Refresh</td><td>Refresh</td><td><?php echo $odoMeter;?></td><td><?php echo $renewDate;?></td></tr>
<?php
		
	}
	}
	else
	{
?>
		<tr><td colspan="3" style="border-top:1px solid #c5d4da; border-right:0px; background-color:#e8e9ea;">No Records found</td></tr>
<?php
	}
?>
</table>
</div>
<script language="javascript">
var ajax1=new sack();
refreshHomeMapTable("<?php echo $_SESSION[clientID];?>","<?php echo date("d-m-Y");?>");
//var refreshId = setInterval("refreshHomeMapTable('<?php echo $_SESSION[clientID];?>','<?php echo date("d-m-Y");?>')", 60000);

function refreshHomeMapTable(sessionid,date_offline)
{
	ajax1.requestFile = 'ajax_server.php?date_offline='+date_offline+'&sessionid='+sessionid;
	//document.write(ajax1.requestFile);
	document.getElementById('homeMap').innerHTML = "Refreshing...";
	ajax1.onCompletion = function(){exeRefreshHomeTable()};
	ajax1.runAJAX();
}
function exeRefreshHomeTable()
{
	//alert(ajax1.response);
	var result = ajax1.response.split("@");
	//document.write(result);	
	mapTable = '<table width="90%" class="gridform_final" border="0" cellpadding="2" cellspacing="2">';
    	mapTable +='<tr><th width="8%" align="left">Device</th><th width="20%" align="left">Device&nbsp;Name</th><th width="30%" align="left">Location</th><th width="12%" align="left">Lat Recv.</th><th width="10%" align="left">OdoMeter</th><th width="20%" align="left">Subscription Exp. Date</th><th width="5%" align="left">Status</th>';
	
	no_of_rows= result.length;
	//alert(no_of_rows+" "+ajax1.response)
	if(ajax1.response != "")
	{
	
		for(i=0;i<no_of_rows;i++)
		{
			data = result[i].split(',');
			
			if(data[5]==0)
				status = '<img src="../tr/images/stop.gif" width="25px" height="25px" title="Stopped"/> Stopped';
			if(data[5]>0)
				status = '<img src="../tr/images/run.gif" width="25px" height="25px" title="Running"/> Running';
			else if(!data[5])
				status = '<img src="../tr/images/idle.gif" width="25px" height="25px" title="No data for today"/><br> No data for today';
				
			if(data[8]==1)
				color = 'style="color:green;"';
			else if(data[8]==2)
				color = 'style="color:blue;"';
			else if(data[8]==0)
				color = 'style="color:red;"';
			
			mapTable +='<tr>';
			mapTable +='<td valign="top" align="left"><img src="unit_img/'+data[9]+'" width="75" height="75"/></td>';
			mapTable +='<td valign="top" align="left">'+data[1]+'</td>';
			mapTable +='<td '+color+' valign="top" align="left">'+data[4]+'</td>';
			mapTable +='<td valign="top" align="left">'+data[6]+' '+data[7]+'</td>';
			mapTable +='<td valign="top" align="left">'+data[2]+'</td>';
			mapTable +='<td valign="top" align="left">'+data[3]+'</td>';
			mapTable +='<td valign="top" align="left">'+status+'</td>';
			mapTable +='</tr>';
			
		}//end of for loop
	}//end of if loop
	else
	{
		mapTable +='<tr><td colspan="6" style="border-top:1px solid #c5d4da; border-right:0px; background-color:#e8e9ea;">No Records found</td></tr>';
	}
	mapTable +='</table>';
	document.getElementById('homeMap').innerHTML = mapTable;
	
}
</script>