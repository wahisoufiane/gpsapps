<?php
@session_start();
error_reporting (E_ALL ^ E_NOTICE);
if(isset($_SESSION[superID]) && $_SESSION[superID]!='')
{
	
}
else
{
	header("location:../../");
}
?>