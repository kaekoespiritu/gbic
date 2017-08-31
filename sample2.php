<?php 
$counter = 0;
$book = "";
if (isset($_POST['checkbox_submit'])) {

	for($b = 0; $b<= 17; $b++){
	    if(isset($_POST['selectedSite'][$b]))
	    {
	        $book .= ' '.$_POST['selectedSite'][$b];
	        $counter++;
	    }

	}
Print "<script>alert('".$book."')</script>;";
Print "<script>alert('".$counter."')</script>;";
}
?>