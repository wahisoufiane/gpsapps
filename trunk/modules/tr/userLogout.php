<?php
session_start();
unset($_SESSION[userID]);
unset($_SESSION[clientID]);
header("location:../../");
?>