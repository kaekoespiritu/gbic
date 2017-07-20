<?php
// Saving local database configuration
$server = "localhost";
$username = "root";
$password = "";
$database = "GBIC";

	mysql_connect($server, $username,$password) or die(mysql_error()); //Connect to server
	mysql_select_db($database) or die("Cannot connect to database"); //Connect to database



?>