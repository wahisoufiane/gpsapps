<?php
require_once("../Mail/class.phpmailer.php");

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
?>