<?php
@session_start();
unset($_SESSION[superID]);
header("location:../../");
?>