<?php


	function updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate)
	{

		//Print "<script>alert('workinghrs ". $workinghrs ."')</script>";
		//Print "<script>alert('".$attendance."')</script>";
		Print "<script>console.log('1.0')</script>";
		if((!empty($timein) && !empty($timeout)) && $day == "Sunday")
		{
			Print "<script>console.log('1.1')</script>";
			//Print "<script>alert('1')</script>";
			$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
											  	position= '".$position."',
											  	timein= '".$timein1."',
											  	timeout='".$timeout1."',
											  	afterbreak_timein= '".$timein2."',
											  	afterbreak_timeout='".$timeout2."',
											  	nightshift_timein= '".$timein3."',
											  	nightshift_timeout='".$timeout3."',
											  	workhours='".$workinghrs."',
											  	overtime='".$OtHrs."',
											  	undertime='".$undertime."',
											 	nightdiff='".$nightdiff."',
											  	remarks='".$remarks."',
											  	attendance='".$attendance."',
											  	date='".$date."',
											  	site='".$location."',
											  	sunday='".$sunday."',
											  	holiday='".$holidayDate."' WHERE date = '$date' AND empid = '$empid'";
		}
		else if((empty($timein) && empty($timeout)) && $day == "Sunday")
		{
			Print "<script>console.log('1.2')</script>";
			//Print "<script>alert('2')</script>";
			$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
											  	position= '".$position."',
											  	timein= '".$timein1."',
											  	timeout='".$timeout1."',
											  	afterbreak_timein= '".$timein2."',
											  	afterbreak_timeout='".$timeout2."',
											  	nightshift_timein= '".$timein3."',
											  	nightshift_timeout='".$timeout3."',
											  	workhours='".$workinghrs."',
											  	overtime='".$OtHrs."',
											  	undertime='".$undertime."',
											 	nightdiff='".$nightdiff."',
											  	remarks='".$remarks."',
											  	attendance='". $attendance ."',
											  	date='".$date."',
											  	site='".$location."',
											  	sunday='0',
											  	holiday='".$holidayDate."' WHERE date = '$date' AND empid = '$empid'";
		}
		else
		{
			Print "<script>console.log('2.0')</script>";
			//Print "<script>alert('3')</script>";
			if(!empty($holidayDate))
			{
				Print "<script>console.log('2.1')</script>";
				//Print "<script>alert('4')</script>";
				$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
												  	position= '".$position."',
												  	timein= '".$timein1."',
											  		timeout='".$timeout1."',
											  		afterbreak_timein= '".$timein2."',
											  		afterbreak_timeout='".$timeout2."',
											  		nightshift_timein= '".$timein3."',
											  		nightshift_timeout='".$timeout3."',
												  	workhours='".$workinghrs."',
												  	overtime='".$OtHrs."',
												  	undertime='".$undertime."',
												 	nightdiff='".$nightdiff."',
												  	remarks='".$remarks."',
												  	attendance='".$attendance."',
												  	date='".$date."',
												  	site='".$location."',
												  	sunday='0',
												  	holiday='".$holidayDate."' WHERE date = '$date' AND empid = '$empid'";
			}
			else
			{
				Print "<script>console.log('2.2')</script>";

				//Print "<script>alert('5')</script>";
				$AttQuery = "UPDATE attendance SET 	empid='".$empid."',
												  	position= '".$position."',
												  	timein= '".$timein1."',
											  		timeout='".$timeout1."',
											  		afterbreak_timein= '".$timein2."',
											  		afterbreak_timeout='".$timeout2."',
											  		nightshift_timein= '".$timein3."',
											  		nightshift_timeout='".$timeout3."',
												  	workhours='".$workinghrs."',
												  	overtime='".$OtHrs."',
												  	undertime='".$undertime."',
												 	nightdiff='".$nightdiff."',
												  	remarks='".$remarks."',
												  	attendance='".$attendance."',
												  	date='".$date."',
												  	site='".$location."',
												  	sunday='0',
												  	holiday='0' WHERE date = '$date' AND empid = '$empid'";
				// Print "<script>console.log('".$AttQuery."')</script>";
			}
		}
		//Print "<script>alert('".$AttQuery."')</script>";
		return $AttQuery;
	}

	function newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate)
	{
		Print "<script>console.log('3.0')</script>";
		if((!empty($timein) && !empty($timeout)) && $day == "Sunday")
		{
			$AttQuery .= "('".$empid."',
					  '".$position."',
					  '".$timein1."',
					  '".$timeout1."',
					  '".$timein2."',
					  '".$timeout2."',
					  '".$timein3."',
					  '".$timeout3."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '".$attendance."',
					  '".$date."',
					  '".$location."',
					  '".$sunday."',
					  '".$holidayDate."')";
		}
		else if((empty($timein) && empty($timeout)) && $day == "Sunday")
		{
			$AttQuery .= "('".$empid."',
					  '".$position."',
					  '".$timein1."',
					  '".$timeout1."',
					  '".$timein2."',
					  '".$timeout2."',
					  '".$timein3."',
					  '".$timeout3."',
					  '".$workinghrs."',
					  '".$OtHrs."',
					  '".$undertime."',
					  '".$nightdiff."',
					  '".$remarks."',
					  '".$attendance."',
					  '".$date."',
					  '".$location."',
					  '0',
					  '0')";
		}
		else
		{
			if(!empty($holidayDate))
			{
				$AttQuery .= "('".$empid."',
						  '".$position."',
						  '".$timein1."',
						  '".$timeout1."',
						  '".$timein2."',
						  '".$timeout2."',
						  '".$timein3."',
						  '".$timeout3."',
						  '".$workinghrs."',
						  '".$OtHrs."',
						  '".$undertime."',
						  '".$nightdiff."',
						  '".$remarks."',
						  '".$attendance."',
						  '".$date."',
						  '".$location."',
						  '0',
						  '".$holidayDate."')";
			}
			else
			{
				$AttQuery .= "('".$empid."',
						  '".$position."',
						  '".$timein1."',
						  '".$timeout1."',
						  '".$timein2."',
						  '".$timeout2."',
						  '".$timein3."',
					  	  '".$timeout3."',
						  '".$workinghrs."',
						  '".$OtHrs."',
						  '".$undertime."',
						  '".$nightdiff."',
						  '".$remarks."',
						  '".$attendance."',
						  '".$date."',
						  '".$location."',
						  '0',
						  '0')";
			}
		}
		//Print "<script>alert('".$AttQuery."')</script>";

		return $AttQuery;
	}
?>










