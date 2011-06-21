<?php
  $content = $_POST['content'];

  switch($content) {
    case 'home':
      include("home.php");
      break;
    case 'user':
	   include("addUser.php");
      break;
    default:
      include("home.php");
	  break;
  }
?>