<?php
// Saving local database configuration
$server = "localhost";
$username = "root";
$password = "";
$database = "gbic";

$db = mysqli_connect($server,$username,$password,$database);

if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>