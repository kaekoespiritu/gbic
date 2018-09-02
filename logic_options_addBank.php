<?php
include_once("directives/db.php");
include("directives/session.php");

if(isset($_POST['bank_name']) && isset($_POST['color']))
{

	$newBank = mysql_escape_string($_POST['bank_name']);
	$newBank = strtoupper($newBank);
	$bankColor = $_POST['color'][0];
	$bankChecker = "SELECT * FROM banks WHERE name = '$newBank'";
	$checkerQuery = mysql_query($bankChecker);

	if(mysql_num_rows($checkerQuery) == 0)
	{
		$insertBank = "INSERT banks(name, color) VALUES('$newBank', '$bankColor')";
		mysql_query($insertBank);
		Print "<script>
						alert('Successfully added ".$newBank." to bank list.');
						window.location.assign('options.php')</script>";
	}
	else
	{
		Print "<script>
						alert('Bank is already on the list');
						window.location.assign('options.php')</script>";
	}
	
		
}
else
{
	Print "<script>window.location.assign('index.php')</script>";
}


?>