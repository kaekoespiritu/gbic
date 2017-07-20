<?php
// Saving local database configuration
include('directives/db.php');

session_start();

$usercheck = $_SESSION['user_logged_in'];

$query = "SELECT username FROM administrator WHERE username = '$usercheck'";
$session_sql = mysql_query($query);
$row = mysql_fetch_array($session_sql);
$login_session = $row['username'];

if(!isset($_SESSION['user_logged_in']))
{
	header('location: login.php');
}

?>