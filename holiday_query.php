<?php
include('directives/session.php');
include_once('directives/db.php');
//New Holiday & Update holiday

if(isset($_GET['name']) && isset($_GET['type']) && isset($_GET['date']))
{
	$holidayName = $_GET['name'];
	$holidayType = $_GET['type'];
	$holidayDate = $_GET['date'];
	$holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDate'";
	$checkerQuery = mysql_query($holidayChecker);
	$exist = mysql_num_rows($checkerQuery);
	if($exist < 1)//check if there is an existing holiday
	{
		$holiday = "INSERT INTO holiday(holiday, date, type) VALUES('$holidayName', '$holidayDate', '$holidayType')";
	}
	else
	{
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
	//Removes all of the holidays on the employee on that specific date
	$empHoliday = "UPDATE attendance SET holiday = '0' WHERE date = '$date'";
	//Delete Holiday from the database
	$deleteHoliday = "DELETE FROM holiday WHERE date = '$date'";
	mysql_query($deleteHoliday);
	mysql_query($empHoliday);

	//Unset the holiday session
	unset($_SESSION['holidayName']);
	unset($_SESSION['holidayDate']);
	unset($_SESSION['holidayType']);
	Print "<script>window.location.assign('attendance.php')</script>";

}
?>