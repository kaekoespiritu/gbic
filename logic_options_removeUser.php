<?php
include('directives/session.php');
include_once('directives/db.php');

$user = $_POST['userTerminate'];
$employee = "SELECT * FROM administrator WHERE username = '$user'";
$empQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empQuery);

$firstname = $empArr['firstname']; 
$lastname = $empArr['lastname'];

$terminate = "DELETE FROM administrator WHERE username = '$user'";
mysql_query($terminate);

Print "<script>alert('".$lastname.", ".$firstname."s Account has been removed.')</script>";
Print "<script>window.location.assign('options.php')</script>";

?>