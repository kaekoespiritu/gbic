<?php
include_once("directives/db.php");
include("directives/session.php");

$id = $_GET['id'];
$type = $_GET['loan'];

$delete = "DELETE FROM loans WHERE id = '$id'";

$deleteQuery = mysql_query($delete);
if($deleteQuery);
	Print "<script>alert('You have successfully deleted a loan.')</script>";

Print "<script>window.location.assign('loans_view.php?type=".$type."')</script>";
?>