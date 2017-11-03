<?php
include('directives/session.php');
include_once('directives/db.php');
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
	//adds Holiday to the finished attendance for that day
	$attendanceArr = "UPDATE attendance SET holiday = '$holidayDate' WHERE date = '$holidayDate' AND(attendance = '1' OR attendance = '2')";
	mysql_query($attendanceArr);


	//Query for holiday
	mysql_query($holiday);

	 
	Print "<script>window.location.assign('attendance.php')</script>";
}
else if(isset($_GET['date']))//Cancel holiday
{
	$date = $_GET['date'];
	$empHoliday = "UPDATE attendance SET holiday = '0' WHERE date = '$date'";
	mysql_query($empHoliday);
	Print "<script>window.location.assign('attendance.php')</script>";

}
?>