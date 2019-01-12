<?php
include('directives/session.php');
include_once('directives/db.php');

if(isset($_SESSION['dashboard']))
	unset($_SESSION['dashboard']);
else
	$_SESSION['dashboard'] = true;

Print "<script>window.location.assign('loans_landing.php')</script>";
?>