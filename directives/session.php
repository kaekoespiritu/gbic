<?php
// Saving local database configuration
include('directives/db.php');

session_start();

if(!isset($_SESSION['user_logged_in']))
{
	header('location: login.php');
}

?>