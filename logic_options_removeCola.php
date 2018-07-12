<?php
include_once('directives/db.php');
include('directives/session.php');
 
$site = $_GET['site'];

$cola = "UPDATE site SET cola = NULL WHERE location = '$site'";
mysql_query($cola);
Print "<script>alert('You have removed COLA from ".$site.".')</script>";
Print "<script>window.location.assign('options.php')</script>";
?>