<?php
// Saving local database configuration
Include('config.php');

session_start();

$usercheck = $_SESSION['user_logged_in'];

$session_sql = mysqli_query($db, "SELECT username FROM admin WHERE username = '$usercheck'");
$row = mysqli_fetch_array($session_sql, MYSQLI_ASSOC);
$login_session = $row['username'];

if(!isset($_SESSION['user_logged_in']))
{
	header('location: login.php');
}

?>