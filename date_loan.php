<?php
include('directives/session.php');

if(isset($_GET['loandate']))
{
	$_SESSION['loandate'] = $_GET['loandate'];
	Print "<script>window.location.assign('loans_landing.php')</script>";
}
?>