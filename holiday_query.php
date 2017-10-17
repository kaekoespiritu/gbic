<?php
include('directives/session.php');
include('directives/db.php');
//New Holiday & Update holiday
if(isset($_GET['name']) && isset($_GET['type']) && isset($_GET['date']))
{
	$holidayName = $_GET['name'];
	$holidayType = $_GET['type'];
	$holidayDate = $_GET['date'];
	Print "<script>
			console.log('holidayName = ".$holidayName."/holidayType = ".$holidayType."/holidayDate = ".$holidayDate."')</script>";
	$holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDate'";
	$checkerQuery = mysql_query($holidayChecker);
	$exist = mysql_num_rows($checkerQuery);
	if($exist < 1)//check if there is an existing holiday
	{
		Print "<script>console.log('1')</script>";
		$holiday = "INSERT INTO holiday(holiday, date, type) VALUES('$holidayName', '$holidayDate', '$holidayType')";
	}
	else
	{
		Print "<script>console.log('2')</script>";
		$holiday = "UPDATE holiday SET 	holiday='$holidayName',
										date='$holidayDate',
										type='$holidayType' WHERE date = '$holidayDate'";
	}
	mysql_query($holiday);

	 
	Print "<script>window.location.assign('attendance.php')</script>";
}
//Cancel holiday
else if(isset($_GET['date']))
{
	$date = $_GET['date'];
	$empHoliday = "UPDATE attendace SET holiday = '0' WHERE holiday = $date";
	mysql_query($empHoliday);
	Print "<script>window.location.assign('attendance.php')</script>";

}
?>