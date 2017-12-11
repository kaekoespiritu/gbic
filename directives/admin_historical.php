<?php
//Admin info for historical data
$AdminResp = $_SESSION['user_logged_in'];
$admin = "SELECT * FROM administrator WHERE username = '$AdminResp'";
$adminQuery = mysql_query($admin) or die (mysql_error());

$adminArr = mysql_fetch_assoc($adminQuery);
$adminName = $adminArr['firstname']." ".$adminArr['lastname'];
?>