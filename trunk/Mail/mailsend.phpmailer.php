<?php
require_once("class.phpmailer.php");

if(sendMail("murali1238k@gmail.com","test","message","Shastrasoftech"))
{
	echo "Message Delivered Successfully.";
}
else
{
	echo "Message not deliverd";
}

function sendMail($to,$subject,$message,$fromName)
{
	$mail = new PHPMailer();
	$mail->IsSMTP(); // send via SMTP
	$mail->Host     = "smtp.1and1.com"; // SMTP servers
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "admin@chekhra.com";  // SMTP username
	$mail->Password = "chekhra123"; // SMTP password


	$find = strpos($to,',');
	if($find)
	{
		$ids = explode(',',$to);
		for($i=0;$i<count($ids);$i++)
		{
			$mail->AddAddress($ids[$i]);
		}
	}
	else
	{
		$mail->AddAddress($to);
	}
	
	$mail->From     = "admin@chekhra.com";
	if($fromName!='')
		 $mail->FromName = $fromName." Support";
	else $mail->FromName = "Support";
	//exit;
	
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
