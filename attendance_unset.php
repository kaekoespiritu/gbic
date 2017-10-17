<?php
include('directives/session.php');
include('directives/db.php');
if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
{
	$holidayDate = $_SESSION['holidayDate'];
	$holidayChecker = "SELECT * FROM holiday WHERE date = '$holidayDate'";
	$holidayQuery = mysql_query($holidayChecker);
	if($holidayQuery)
	{
		$holidayRow = mysql_num_rows($holidayQuery);
		if($holidayRow != 0)
		{
			$delHoliday = "DELETE FROM holiday WHERE date = '$holidayDate'";
			$delHolidayQuery = mysql_query($delHoliday);
		}
	}
	

	unset($_SESSION['holidayName']);
	unset($_SESSION['holidayType']);
	unset($_SESSION['holidayDate']);

	Print "<script>window.location.assign('attendance.php')</script>";
}
?>