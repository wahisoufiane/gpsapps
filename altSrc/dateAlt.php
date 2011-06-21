#!/opt/lampp/bin/php
<?php
error_reporting (E_ALL ^ E_NOTICE);
require("../includes/config.inc.php"); 
require("../includes/Database.class.php"); 
require("../includes/GPSFunction.php"); 
require("../includes/cronMailSMTP.php"); 
require("../includes/cronSMS.php"); 

$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 



$sql = "select * from tb_device_alert_info,tb_clientinfo,tb_deviceinfo where tdai_alertBy = 'Date' AND di_id = tdai_deviceId AND ci_id = tdai_clientId AND tdai_active = 1 AND tdai_status = 0 AND tdai_alertSrc = '".date("d-m-Y")."' order by tdai_id ASC";
$rows = $db->query($sql);

if($db->affected_rows > 0)
{
	$i = 0;
	while ($record = $db->fetch_array($rows)) 
	{
		//print_r($record);
		$getReseller = "select * from tb_clientinfo where ci_id = ".$record[ci_clientId];
		$resReseller = mysql_query($getReseller);
		$fetReseller = @mysql_fetch_assoc($resReseller);
		
		if($record[di_deviceName])
			$devName = $record[di_deviceName];
		else
			$devName = $record[di_deviceId];

			if($record[tdai_alertType] == "Email") 
			{
				$t = $record[tdai_source];
				$sub = "Alert - ".strip_tags(ucfirst($record[tdai_purpose]))." for ".$fetReseller[ci_clientName];
				$fr = $fetReseller[ci_clientName];				
				
				$message = '<html><body>';
				$message .= "<b>Dear ".ucfirst($record[ci_clientName])."! </b><br><br>";
				$message .= '<table style="border-color: #666;" cellpadding="10" width="100%">';
				$message .= "<tr style='background: #eee;'><td><strong>Greetings from:</strong> </td><td>" . strip_tags($fetReseller[ci_clientName]) . "</td></tr>";
				$message .= "<tr><td><strong>Vehicle Name:</strong> </td><td>" . strip_tags($devName) . "</td></tr>";
				$message .= "<tr><td><strong>Purpose:</strong> </td><td>" . strip_tags(ucfirst($record[tdai_purpose])) . "</td></tr>";
				$message .= "<tr><td><strong>Description:</strong> </td><td>" . strip_tags(ucfirst($record[tdai_description])) . "</td></tr>";
				$message .= "<tr><td><strong>By -:</strong> </td><td>" . $fetReseller[ci_weburl] . "</td></tr>";
				$message .= '<tr><td></td><td><img src="'.$GLOBALS[dataPath].'gpsapp/modules/user/client_logo/'.$fetReseller[ci_clientLogo].'" alt="Website Change Request" /></td></tr>';						
				$message .= "<tr><td><strong>Note:</strong> </td><td style='font:12px normal Arial, Helvetica, sans-serif; color:red'>Do not reply to this system generated mail</td></tr>";
				$message .= "</table>";
				$message .= "</body></html>";
				
				//echo $sub;
				//exit;
				if($mailres = sendMail($t,$sub,$message,$fr))
				{
					
					$data['tdai_status'] = 1;
					$data['tdai_deliveryTime'] = date("Y-m-d H:i:s");
					if($db->query_update("tb_device_alert_info", $data , "tdai_id=".$record[tdai_id]))
					{
						$maildata['tmi_email'] = $t;
						$maildata['tmi_tgai_id'] = $record[tdai_id];
						$maildata['tmi_mailResult'] = $mailres;
						$maildata['tmi_message'] = $message;
						$maildata['tmi_mailType'] = "DATEALERT";		
						//print_r($maildata);		
						//exit;
						if($db->query_insert("tb_mail_info", $maildata))
							echo "done";
						else echo "no";
					}
				}
			}// end if alert type
			elseif($record[tdai_alertType] == "Mobile") 
			{
				$from = "";
				$to = $record[tdai_source];
				$msg = "Dear ".ucfirst($record[ci_clientName])."! ".$devName." has ".$record[tdai_purpose].". pls log in for desc- ".$record[ci_weburl];
				//echo $msg;
				//exit;			
				if($smsres = sendSMS($from,$to,$msg))
				{
					
					$data['tdai_status'] = 1;
					$data['tdai_deliveryTime'] = date("Y-m-d H:i:s");
					//print_r($data);
					//exit;
					if($db->query_update("tb_device_alert_info", $data , "tdai_id=".$record[tdai_id]))
					{
						$smsdata['tsi_mobileno'] = $to;
						$smsdata['tsi_tgai_id'] = $record[tdai_id];
						$smsdata['tsi_smsResult'] = $smsres;
						$smsdata['tsi_message'] = urlencode($msg);
						$smsdata['tsi_smsType'] = "DATEALERT";		
						//print_r($smsdata);		
						//exit;
						if($db->query_insert("tb_smsinfo", $smsdata))
							echo "done";
						else echo "no";
					}
				}
			}		// end else alert type
	}// end while
}// end if
?>