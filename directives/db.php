<?php
// Saving local database configuration
$server = "localhost";
$username = "root";
$password = "";
$database = "GBIC";

	mysql_connect($server, $username,$password) or die(mysql_error()); //Connect to server
	mysql_select_db($database) or die("Cannot connect to database"); //Connect to database

function numberExactFormat($number, $precision, $separator)//For number format
{
    $numberParts = explode($separator, $number);
    $response = number_format($numberParts[0]);

    if(count($numberParts)>1){
        $response .= $separator;
        $response .= substr($numberParts[1], 0, $precision);
    }
    else
    {
    	$response .= $separator;
    	$response .= "00";
    }
    return $response;
}

?>