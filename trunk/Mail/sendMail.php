<?php
require_once("class.phpmailer.php");
$t = "manivannan.k@shastrasoftech.com,vannan03@gmail.com";
$sub ="test";
$msg ='inside the cron'.date('d-m-Y H:i:s');
$fr = "Dialmeguru";
//echo $msg;
if(sendMail($t,$sub,$msg,$fr))
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
	$mail->Host     = "mail.shastrasoftech.com"; // SMTP servers
	$mail->SMTPAuth = true;     // turn on SMTP authentication
	$mail->Username = "manivannan.k@shastrasoftech.com";  // SMTP username
	$mail->Password = "ssmanivannank"; // SMTP password


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
	
	$mail->From     = "manivannan.k@shastrasoftech.com";
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
