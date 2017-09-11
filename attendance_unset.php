<?php
include('directives/session.php');

if(isset($_SESSION['holidayName']) && isset($_SESSION['holidayType']))
{
	unset($_SESSION['holidayName']);
	unset($_SESSION['holidayType']);
	unset($_SESSION['holidayDate']);
	Print "<script>window.location.assign('attendance.php')</script>";
}
?>