<?php

include('directives/db.php');
include('directives/session.php');

$username = $_POST['username'];

//Generate 10 character word
$password = chr(rand(65,90)). chr(rand(65,90)). chr(rand(65,90)). chr(rand(97,122)). chr(rand(97,122)). chr(rand(97,122)). rand(0,9). rand(0,9). rand(0,9). rand(0,9);

$updatePassword ="UPDATE administrator SET password = '$password' WHERE username = '$username'";
mysql_query($updatePassword);

$output = "<input type='text' value='".$password."' class='form-control' readonly>";
echo $output;
?>