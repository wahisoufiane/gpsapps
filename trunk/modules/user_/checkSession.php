<?php
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
if(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
{
	
}
else
{
	header("location:../../");
}
?>