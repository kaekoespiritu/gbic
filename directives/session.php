<?php
// Saving local database configuration
date_default_timezone_set('Asia/Hong_Kong');
session_start();

if(!isset($_SESSION['user_logged_in']))
{
	header('location: login.php');
}


?>