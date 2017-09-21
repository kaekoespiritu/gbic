<?php
include('directives/session.php');

if(isset($_GET['id']))
{
	$_SESSION['empid'] = $_GET['id'];
	Print "<script>window.location.assign('viewabsence.php')</script>";
}
?>