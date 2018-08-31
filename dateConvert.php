<?php
include('directives/session.php');
include_once('directives/db.php');

function getMonth($month)
{
	switch($month)
	{
		case "01": $output = "January";break;
		case "02": $output = "February";break;
		case "03": $output = "March";break;
		case "04": $output = "April";break;
		case "05": $output = "May";break;
		case "06": $output = "June";break;
		case "07": $output = "July";break;
		case "08": $output = "August";break;
		case "09": $output = "September";break;
		case "10": $output = "October";break;
		case "11": $output = "November";break;
		case "12": $output = "December";break;
	}
	return $output;
}

$employee = "SELECT * FROM employee";
$empQuery = mysql_query($employee);

while($row = mysql_fetch_array($empQuery)) 
{
	$dateHiredEx = explode('-', $row['datehired']);// convert the format
	$month = $dateHiredEx[0];
	$day = $dateHiredEx[1];
	$year = $dateHiredEx[2];

	$dateHired = getMonth($month)." ".$day.", ".$year;
	$empid = $row['empid'];
	$update = "UPDATE employee SET datehired = '$dateHired' WHERE empid = '$empid'";
	Print $update."<br>";
	mysql_query($update);
	
}



















?>