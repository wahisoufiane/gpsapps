<?php
require_once("includes/config.inc.php"); 
require_once("includes/Database.class.php"); 
require_once("Mail/class.phpmailer.php");
$db = new Database(DB_SERVER, DB_USER, DB_PASS, DB_DATABASE); 
$db->connect(); 

function sendSMTPMail($to,$subject,$message,$fromName,$host='',$uname='',$pass='',$fromEmail='')
{
	$mail = new PHPMailer();
	$mail->IsSMTP(); // send via SMTP
	
	if($host == '')
		$mail->Host     = "smtp.abstracking.com"; // SMTP servers
	else
		$mail->Host     = $host; // SMTP servers
		
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	
	if($uname == '')
		$mail->Username = "care@abstracking.com";  // SMTP username
	else
		$mail->Username = $uname;  // SMTP username
	
	if($pass == '')
		$mail->Password = "care123"; // SMTP password
	else
		$mail->Password = $pass; // SMTP password

	$ids = explode(',',$to);
	for($i=0;$i<count($ids)-1;$i++)
	{
		$mail->AddAddress($ids[$i]);
	}
	
	if($fromEmail == '')
		$mail->From     = "care@abstracking.com";
	else
		$mail->From     = $fromEmail;
		
	if($fromName!='')
		 $mail->FromName = $fromName." Support";
	else $mail->FromName = "Support";
	
	$mail->WordWrap = 50; 
	$mail->IsHTML(true);
	
	$mail->Subject = $subject;			
	$mail->Body = $message;
	
	if($mail->Send())
	{
		return 1;	
	}
	else
	{
		return 0;	
	}
}
function sendMail($to,$subject,$message,$fromName)
{
	$mail = new PHPMailer();
	$mail->IsSMTP(); // send via SMTP
	$mail->Host     = "smtp.abstracking.com"; // SMTP servers
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "care@abstracking.com";  // SMTP username
	$mail->Password = "care123"; // SMTP password


	$ids = explode(',',$to);
	for($i=0;$i<count($ids)-1;$i++)
	{
		$mail->AddAddress($ids[$i]);
	}
	
	
	$mail->From     = "care@abstracking.com";
	if($fromName!='')
		 $mail->FromName = $fromName." Support";
	else $mail->FromName = "Support";
	
	$mail->WordWrap = 50; 
	$mail->IsHTML(true);
	
	$mail->Subject = $subject;			
	$mail->Body = $message;
	
	if($mail->Send())
	{
		return 1;	
	}
	else
	{
		return 0;	
	}
}


$getReseller = "select * from tb_clientinfo where ci_id = 4";
$resReseller = $db->query($getReseller);
$fetReseller = @mysql_fetch_assoc($resReseller);
//print_r($fetReseller);

//echo sendSMTPMail("manivannan.k@shastrasoftech.com,","test","new smtp",$fetReseller[ci_clientName],$fetReseller[ci_smtpHostname],$fetReseller[ci_smtpUsername],$fetReseller[ci_smtpPassword],$fetReseller[ci_smtpUsername]);

echo 'ss'.sendMail("manivannan.k@shastrasoftech.com,","test","new smtp".date("d-m-Y H:i:s"),$fetReseller[ci_clientName]);

?>