<?php


	function updateQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery)
	{
		if((!empty($timein) && !empty($timeout)) && $day == "Sunday")
		{
			$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
											  	position= '".$position."',
											  	timein= '".$timein."',
											  	timeout='".$timeout."',
											  	workhours='".$workinghrs."',
											  	overtime='".$OtHrs."',
											  	undertime='".$undertime."',
											 	nightdiff='".$nightdiff."',
											  	remarks='".$remarks."',
											  	attendance='".$attendance."',
											  	date='".$date."',
											  	site='".$location."',
											  	sunday='".$sunday."' WHERE date = '$date' AND empid = '$empid'";
		}
		else if((empty($timein) && empty($timeout)) && $day == "Sunday")
		{
			$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
											  	position= '".$position."',
											  	timein= '".$timein."',
											  	timeout='".$timeout."',
											  	workhours='".$workinghrs."',
											  	overtime='".$OtHrs."',
											  	undertime='".$undertime."',
											 	nightdiff='".$nightdiff."',
											  	remarks='".$remarks."',
											  	attendance='0',
											  	date='".$date."',
											  	site='".$location."',
											  	sunday='0' WHERE date = '$date' AND empid = '$empid'";
		}
		else
		{
			$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
											  	position= '".$position."',
											  	timein= '".$timein."',
											  	timeout='".$timeout."',
											  	workhours='".$workinghrs."',
											  	overtime='".$OtHrs."',
											  	undertime='".$undertime."',
											 	nightdiff='".$nightdiff."',
											  	remarks='".$remarks."',
											  	attendance='".$attendance."',
											  	date='".$date."',
											  	site='".$location."',
											  	sunday='0' WHERE date = '$date' AND empid = '$empid'";
		}
		Print "<script>alert('".$AttQuery."')</script>";
		return $AttQuery;
	}

	function newQuery($timein, $timeout, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery)
	{
		if((!empty($timein) && !empty($timeout)) && $day == "Sunday")
		{
			$AttQuery .= "('".$empid."',
					  '".$position."',
					  '".$timein."',
					  '".$timeout."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '".$attendance."',
					  '".$date."',
					  '".$location."',
					  '".$sunday."')";
		}
		else if((empty($timein) && empty($timeout)) && $day == "Sunday")
		{
			$AttQuery .= "('".$empid."',
					  '".$position."',
					  '".$timein."',
					  '".$timeout."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '0',
					  '".$date."',
					  '".$location."',
					  '0')";
		}
		else
		{
			$AttQuery .= "('".$empid."',
					  '".$position."',
					  '".$timein."',
					  '".$timeout."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '".$attendance."',
					  '".$date."',
					  '".$location."',
					  '0')";
		}
		Print "<script>alert('".$AttQuery."')</script>";

		return $AttQuery;
	}
?>










