<?php
include('directives/session.php');

if(isset($_GET['loandate']))
{
	$_SESSION['loandate'] = $_GET['loandate'];
	Print "<script>window.location.assign('loans_landing.php')</script>";
}
if(isset($_GET['loanviewdate']))
{
	$_SESSION['loanviewdate'] = $_GET['loanviewdate'];
	$type = $_GET['type'];
	Print "<script>window.location.assign('loans_view.php?type=".$type."')</script>";
}

?>