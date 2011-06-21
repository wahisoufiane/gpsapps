<?php
session_start();
if(isset($_SESSION[userID]) && isset($_SESSION[clientID]))
{
	
}
else
{
	header("location:../../");
}
?>