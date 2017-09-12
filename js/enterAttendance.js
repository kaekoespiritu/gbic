document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");

	$(document).ready(function(){
		console.log("jQuery comes in!");
		$('input.timein').timepicker({
			timeFormat: 'hh:mm p',
			dynamic: false,
			scrollbar: false,
			dropdown: false
		});
		$('input.timein').change(function(){
			var id = $(this).parent().parent().attr('id');
			console.log(id);
			timeIn(id);
		});
		$('input.timeout').timepicker({
			timeFormat: 'hh:mm p',
			dynamic: false,
			scrollbar: false,
			dropdown: false
		});
		$('input.timeout').change(function(){
			var id = $(this).parent().parent().attr('id');
			console.log(id);
			timeOut(id);
		});
	});

	



	//Submit the form
	function save() {
		var a = confirm("Are you sure you want to save this attendance? All of the blank fields will remain empty.")
		if(a)
		{
			document.getElementById('form').submit();
		}
	}

	function remarks(id) {	
		// show modal here to input for remarks
		var mainRow = document.getElementById(id);
		if(mainRow.querySelector('.hiddenRemarks').value != null)
		{
			var input = mainRow.querySelector('.hiddenRemarks').value;
			document.getElementById('remark').value = input;
		}
		else
		{
			document.getElementById('remark').value = "";
		}
		var empName = mainRow.querySelector('.empName').innerHTML.trim();
		var modal = document.getElementById('dito').innerHTML = "Remarks for " + empName;
		document.getElementById('saveRemarks').setAttribute('onclick', "saveRemarks(\""+ id +"\")");
		console.log(modal);
		
	}
	// This triggers the submit of search Form ( 13 = Enter key )
	function enter(e) {
		if (e.keyCode == 13) {
			document.getElementById('search_form').submit();
		}
	}
	// Transfer content to hidden input field
	function saveRemarks(id) {
		var mainRow = document.getElementById(id);
		var remarks = document.getElementById('remark').value.trim();
		var hiddenRemarks = mainRow.querySelector('.hiddenRemarks').setAttribute('value', remarks);
		console.log(remarks);
		if(remarks !== null && remarks !== "")
		{
			
			mainRow.querySelector('.icon').classList.add('glyphicon', 'glyphicon-edit');
			
			//alert("Time to add a badge here!");
			//alert(remarks);
				
		}
		else
		{
			//alert("Nothing to do!");
			mainRow.querySelector('.icon').classList.remove('glyphicon', 'glyphicon-edit');
		}

	}

	function absent(id) {
		
		var mainRow = document.getElementById(id); // Get row to be computed

		// change color of row to shade of red
		mainRow.classList.add('danger');
		// set attendance status to ABSENT
		mainRow.querySelector('.attendance').value = "ABSENT";
		// add text ABSENT to time in and time out
		mainRow.querySelector('.timein').placeholder = "ABSENT";
		mainRow.querySelector('.timeout').placeholder = "ABSENT";
		mainRow.querySelector('.timein').value = "";
		mainRow.querySelector('.timeout').value = "";
		mainRow.querySelector('.workinghours').value = "";
		mainRow.querySelector('.overtime').value = "";
		mainRow.querySelector('.undertime').value = "";
		//for hidden rows
		mainRow.querySelector('.workinghoursH').value = "";
		mainRow.querySelector('.overtimeH').value = "";
		mainRow.querySelector('.undertimeH').value = "";
	}

	function getHour(time) {
		console.log("getHour: " + time);
		if(time)
		{
		var hour = time.split(":"); // Split hour + min + AM/PM
		var min = hour[1].split(" "); // Split min + AM/PM
		var diff; // Determine if AM/PM

		if(min[1] == "AM" && parseInt(hour[0],10) == 12)
		{
			hr = 24;
			return hr;
		}
		if(min[1] == "PM" && parseInt(hour[0],10) != 12)
		{
			diff = 12; // Add 12hrs if PM
			var hr = parseInt(hour[0],10) + diff;
			return hr;
		}
		else
		{
			var hr = parseInt(hour[0]);
			return hr;
		}

	}	
	else
	{
		return 0;
	}		
}

function getMin(time) {
	console.log("getMin: " + time);
	if(time)
	{
		var hour = time.split(":"); // Split hour + min + AM/PM
		var min = hour[1].split(" "); // Split min + AM/PM

		var mins = parseInt(min[0],10);
		return mins;
	}
	else
	{
		return 0;
	}
}

function computeTime(row, timeinhour,timeinmin,timeouthour,timeoutmin) {
	console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);

	row.querySelector('.attendance').value = "";
	// Verifies that time in and time out input fields have value
	if(timeinhour && timeouthour)
	{	
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
		var workinghours;
		var workingmins;

		// If time is 12AM
		if(timeinhour == 0)
		{
			workinghours = timeouthour;
		}
		else
		{
			workinghours = timeouthour - timeinhour;
			//alert(workinghours);
		}

		// MORNING SHIFT
		if(workinghours >= 1)
		{
		// Computing minutes
			//alert("dayshift");
			if(timeinmin > timeoutmin)
			{
				workingmins = timeinmin - timeoutmin;
			}
			if(timeoutmin > timeinmin)
			{
				workingmins = timeoutmin - timeinmin;
			}
			if(timeinmin === timeoutmin)
			{
				workingmins = 0;
			}
		
		// Computing lunchbreak
			if(timeinhour <= 11 && timeouthour >= 12)
			{
				workinghours = workinghours - 1;
			}
			//alert(workinghours);
			//set the attendance status to PRESENT
			row.querySelector('.attendance').value = "PRESENT";
		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
				row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
			}
			else if(workingmins == 0)
			{
				row.querySelector('.workinghours').value = workinghours + " hours";
				row.querySelector('.workinghoursH').value = workinghours + " hours";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hours, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hours, " + workingmins + " mins";
			}

		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours";
			}
			else if(workinghours < 8)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}

			// NIGHT DIFF if Working Hours is in between 10pm - 6am
		// 10 is 10pm and 18 is 6pm
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
			//timeinhour -= 12;
			//timeouthour += 12;
			var nightdiff = "";
			//nightdiff MORNING
			if(((timeinhour <= 0 && timeouthour >= 6) || (timeinhour >= 0 || timeouthour <= 6)) 
					&& ((timeinhour <= 0 && timeouthour <= 6)|| (timeinhour >= 0 || timeouthour >= 6)))
			{
				//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
				//alert("yeah");
			//posibility: attendance within NightDiff
			//------------------------- MORNING --------------------------------------
				if(timeinhour >= 0 && timeouthour <= 6)
				{
					nightdiff = timeinhour - timeouthour;
					//alert("possibility : 1");
				}
			//posibility: NightDiff is within attendance
				else if(timeinhour < 0 && timeouthour > 6)
				{
					var NDin = timeinhour;
					var NDout = timeouthour - 6;
					var workhrs = timeinhour - timeouthour;

					nightdiff = ((Math.abs(NDin) + Math.abs(NDout)) - Math.abs(workhrs));
					//alert("possibility : 2");
				}
			//posibility: attendance exceeds NightDiff
				else if(timeinhour < 6 && timeouthour > 6)
				{
					nightdiff = timeinhour - 6;
					//alert("possibility : 3");
				}
			//posibility: attendance > NightDiff
				else if(timeinhour <= 0 && timeouthour > 0)
				{
					//alert("timeinhour: "+timeinhour +" timeouthour: "+timeouthour);
					nightdiff = timeouthour; 
					//alert("possibility : 4");
				}
			//------------------------- NIGHT --------------------------------------
				else if(timeinhour >= 22 && timeouthour <= 6)
				{
					nightdiff = timeinhour - timeouthour;
					//alert("possibility : 5");
				}
			//posibility: NightDiff is within attendance
				else if(((timeouthour <= 24) && (timeouthour > 22)) && timeinhour < 22  )
				{
					// var NDin = timeinhour - 6;
					// var NDout = timeouthour - 22;
					// var workhrs = Math.abs(timeinhour) - Math.abs(timeouthour);
					// alert("1-"+NDin);
					// alert("2-"+NDout);
					// alert("3-"+Math.abs(workhrs));
					// workhrs = Math.abs(workhrs);

					// nightdiff = (Math.abs(NDin) - Math.abs(NDout)) - Math.abs(workhrs);
					// alert("4-"+nightdiff);

					var NDout 
					if(timeouthour == 24)
					{
						NDout = 2;
					}
					else
					{
						NDout = timeouthour - 24;
					}
					nightdiff = Math.abs(NDout);
					//alert("possibility : 6");
				}
			//posibility: attendance > NightDiff
				// else if(timeinhour <= 18 && timeouthour > 18)
				// {
				// 	nightdiff = timeouthour - 18; 
				// 	alert("possibility : 8");
				// }
				else
				{
					//alert("possibility : 9");
					nightdiff = "";
				}
				if(Number.isInteger(nightdiff))
				{
				   	nightdiff = Math.abs(nightdiff);		
				}
				
			}
			
			if(nightdiff != "")
			{
				//alert("yeah1");
				row.querySelector('.nightdiff').value = nightdiff + " hours";
				row.querySelector('.nightdiffH').value = nightdiff + " hours";
			}
			else
			{
				//alert("yeah");
				row.querySelector('.nightdiff').value = "";
				row.querySelector('.nightdiffH').value = "";
			}
			// If absent was initially placed, changed to success
			if(row.classList.contains('danger'))
			{
				row.classList.remove('danger');
				row.classList.add('success');
			}
			else
			{
				row.classList.add('success');
			}

		}

	// NIGHT SHIFT (timeout-timein is negative)
		else
		{
		// Night differential starts at 10pm - 6am
			console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);
			console.log("Working hours: " + workinghours + " Working mins: " + workingmins);
			// sets the attendance status to PRESENT
			row.querySelector('.attendance').value = "PRESENT";
			// TIME IN: 22-12 = 10
			// TIME OUT: 6 + 12 = 18
			// RESULT 8
			//alert("before: "+timeinhour);
			timeinhour -= 12;
			//alert("after: "+timeinhour);
			//alert("before: "+timeouthour);
			timeouthour += 12;
			//alert("after: "+timeouthour);
			//alert("nightshift");
			workinghours = timeouthour - timeinhour;
			if(workinghours < 1)
			{
				workinghours *= -1;
			}
			//alert(workinghours);
			//alert("timein: "+timeinhour + " timeout: " + timeouthour);
		// Computing minutes
			if(timeinmin > timeoutmin)
			{
				workingmins = timeinmin - timeoutmin;
			}
			if(timeoutmin > timeinmin)
			{
				workingmins = timeoutmin - timeinmin;
			}
			if(timeinmin === timeoutmin)
			{
				workingmins = 0;
			}
			
		// Computing lunchbreak for nightshift
			if(timeinhour <= 2 && timeouthour >= 3)
			{
				workinghours = workinghours - 1;
			}

		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
				row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
			}
			else if(workingmins == 0)
			{
				row.querySelector('.workinghours').value = workinghours + " hours";
				row.querySelector('.workinghoursH').value = workinghours + " hours";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hours, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hours, " + workingmins + " mins";	
			}
		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours";
			}
			else if(workinghours < 8)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hours, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}
		// If absent was initially placed, changed to success
			if(row.classList.contains('danger'))
			{
				row.classList.remove('danger');
				row.classList.add('success');
			}
			else
			{
				row.classList.add('success');
			}
		// NIGHT DIFF if Working Hours is in between 10pm - 6am
		// 10 is 10pm and 18 is 6pm
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
		var nightdiff = "";
			if(((timeinhour <= 10 && timeouthour >= 18) || (timeinhour >= 10 || timeouthour <= 18)) 
					&& ((timeinhour <= 10 && timeouthour <= 18)|| (timeinhour >= 10 || timeouthour >= 18)))
			{
				//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
				
			//posibility: attendance within NightDiff
				if(timeinhour >= 10 && timeouthour <= 18)
				{
					nightdiff = timeinhour - timeouthour;
					//alert("possibility : 2");
				}
			//posibility: NightDiff is within attendance
				else if(timeinhour < 10 && timeouthour > 18)
				{
					var NDin = timeinhour - 10;
					var NDout = timeouthour - 18;
					var workhrs = timeinhour - timeouthour;

					nightdiff = ((Math.abs(NDin) + Math.abs(NDout)) - Math.abs(workhrs));
					//alert("possibility : 4");
				}
			//posibility: attendance exceeds NightDiff
				else if(timeinhour < 18 && timeouthour > 18)
				{
					nightdiff = timeinhour - 18;
					//alert("possibility : 3");
				}
			//posibility: attendance > NightDiff
				else if(timeinhour <= 10 && timeouthour > 10)
				{
					nightdiff = timeouthour - 10; 
					//alert("possibility : 1");
				}
				else
				{
					nightdiff = "";
				}
				if(Number.isInteger(nightdiff))
				{
				   	nightdiff = Math.abs(nightdiff);		
				}
			}
			if(nightdiff != "")
			{
				
				row.querySelector('.nightdiff').value = nightdiff + " hours";
				row.querySelector('.nightdiffH').value = nightdiff + " hours";
			}
			else
			{
				
				row.querySelector('.nightdiff').value = "";
				row.querySelector('.nightdiffH').value = "";
			}
			
			
			
		}

	}
	else
	{

		row.querySelector('.workinghours').value = "";
		row.querySelector('.overtime').value = "";
		row.querySelector('.undertime').value = "";
		row.querySelector('.nightdiff').value = "";
		row.querySelector('.timein').placeholder = "";
		row.querySelector('.timeout').placeholder = "";
		//for hidden rows
		row.querySelector('.workinghoursH').value = "";
		row.querySelector('.overtimeH').value = "";
		row.querySelector('.undertimeH').value = "";
		row.querySelector('.nightdiffH').value = "";
		row.querySelector('.attendance').value = "";

		if(row.classList.contains('danger'))
		{
			row.classList.remove('danger');
			row.classList.add('');
		}
		else if(row.classList.contains('success'))
		{
			row.classList.remove('success');
			row.classList.add('');
		}
	}
}	

function timeIn(id) {
	var mainRow = document.getElementById(id); // Get row to be computed
	var timein = mainRow.querySelector('.timein').value; // Get time in value

	// Function call to get time
	var timeinhour = getHour(timein);
	var timeinmin = getMin(timein);

	var timeout = mainRow.querySelector('.timeout').value; // Get time out value

	// Function call to get time
	var timeouthour = getHour(timeout);
	var timeoutmin = getMin(timeout);

	// Function call to compute for working hours, undertime and overtime
	computeTime(mainRow, timeinhour,timeinmin,timeouthour,timeoutmin);

}

function timeOut(id) {
	var mainRow = document.getElementById(id); // Get row to be computed
	var timein = mainRow.querySelector('.timein').value; // Get time in value

	// Function call to get time
	var timeinhour = getHour(timein);
	var timeinmin = getMin(timein);

	var timeout = mainRow.querySelector('.timeout').value; // Get time out value

	// Function call to get time
	var timeouthour = getHour(timeout);
	var timeoutmin = getMin(timeout);
	
	// Function call to compute for working hours, undertime and overtime
	computeTime(mainRow, timeinhour,timeinmin,timeouthour,timeoutmin);

}	