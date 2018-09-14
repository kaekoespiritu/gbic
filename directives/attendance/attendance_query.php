<?php


	function updateQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $holidayDate, $xAllowance)
	{
		$remarks = mysql_real_escape_string($remarks);
		if((!empty($timein) && !empty($timeout)) && $day == "Sunday")
		{
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
											  	sunday='".$sunday."',
											  	holiday='".$holidayDate."',
											  	xallow='".$xAllowance."' WHERE date = '$date' AND empid = '$empid'";
		}
		else if((empty($timein) && empty($timeout)) && $day == "Sunday")
		{
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
											  	sunday='0',
											  	holiday='".$holidayDate."',
											  	xallow='".$xAllowance."' WHERE date = '$date' AND empid = '$empid'";
		}
		else
		{
			if(!empty($holidayDate))
			{
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
												  	sunday='0',
												  	holiday='".$holidayDate."',
											  		xallow='".$xAllowance."' WHERE date = '$date' AND empid = '$empid'";
			}
			else
			{
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
												  	sunday='0',
												  	holiday='0',
											  		xallow='".$xAllowance."' WHERE date = '$date' AND empid = '$empid'";
			}
		}
		return $AttQuery;
	}

	function newQuery($timein1, $timeout1, $timein2, $timeout2, $timein3, $timeout3, $day, $empid, $position, $workinghrs, $OtHrs, $undertime, $nightdiff, $remarks, $attendance, $date, $location, $sunday, $AttQuery, $holidayDate, $xAllowance)
	{
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
					  '".$sunday."',
					  '".$holidayDate."',
					  '".$xAllowance."')";
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
					  '0',
					  '0',
					  '".$xAllowance."')";
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
						  '0',
						  '".$holidayDate."',
					  	  '".$xAllowance."')";
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
						  '0',
						  '0',
					  	  '".$xAllowance."')";
			}
		}
		//Print "<script>alert('".$AttQuery."')</script>";

		return $AttQuery;
	}
?>










