<?php
include('directives/session.php');

if(isset($_GET['date']))
{
	$_SESSION['date'] = $_GET['date'];
	Print "<script>window.location.assign('attendance.php')</script>";
}
?>