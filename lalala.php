<?php
session_start();


$tempDate = $_SESSION['date'];
echo date('l', strtotime( $tempDate));
?>