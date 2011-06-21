<?php
ob_start();
$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$qry = parse_url($url);
if($qry['query']!='')
	$redirect = $qry['query']."/";
else
	$redirect = 'in/';

header("location:in/".$redirect);
exit;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GPS Application</title>
<script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>

<script type="text/javascript">

/*$(document).ready(function () {
 
  $("#content").load("in/index.php");
  
});*/

	
</script>
</head>
<body>
<span id="progressbar"></span>
<div class="wrapper" id="content">Loading...</div>
</body>
</html>
