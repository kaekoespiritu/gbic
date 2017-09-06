<?php

if(isset($_POST['date'])) { //if i have this post
    Print "<script>alert('complete '+ ". $_POST['date'] .")</script>"; // print it
   	$date = $_POST['date'];
     $_SESSION['date'] = $date;
}
?>