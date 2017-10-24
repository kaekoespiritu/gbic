<?php
include('directives/db.php');
include('directives/session.php');
 
$site = $_POST['dd_site'];
$cola = $_POST['cola'];

$cola = "UPDATE site SET cola = '$cola' WHERE location = '$site'";
mysql_query($cola);
Print "<script>alert('You have successfully added COLA to ".$site.".')</script>";
Print "<script>window.location.assign('options.php')</script>";
?>