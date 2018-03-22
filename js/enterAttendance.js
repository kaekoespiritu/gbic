document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");

//jQuery for timepicker
$(document).ready(function(){
	console.log("jQuery comes in!");
	$('input.timein1').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});
	
	$('input.timeout1').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});

	//After break Timein Timeout
	$('input.timein2').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});
	
	$('input.timeout2').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});
	$('input.timein3').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});
	$('input.timeout3').timepicker({
		timeFormat: 'hh:mm p',
		dynamic: false,
		scrollbar: false,
		dropdown: false
	});

	//to compute the time, workhours,etc.
	$('input.timein1').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeIn(id);
	});
	$('input.timeout1').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeOut(id);
	});
	$('input.timein2').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeIn(id);
	});
	$('input.timeout2').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeOut(id);
	});
	$('input.timein3').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeIn(id);
	});
	$('input.timeout3').change(function(){
		var id = $(this).parent().parent().attr('id');
		//console.log(id);
		timeOut(id);
	});

});

//Time validation	
function timeValidation(evt)
{
	var time = evt.value;
	var validate = /^(?:(?:0?\d|1[0-2]):[0-5]\d\s[A|P]M)$/;
	var valid = time.match(validate); 

	if(!valid && time != '')
	{
		alert('You entered an invalid time.');
		evt.value = "";
	}
}
function timeIn(id) {
	var mainRow = document.getElementById(id); // Get row to be computed
	var timein1 = mainRow.querySelector('.timein1').value; // Get time in value
	var timein2 = mainRow.querySelector('.timein2').value; // Get time in value of afterbreak
	var timein3 = mainRow.querySelector('.timein3').value; // Get time in value of nightshift
	
	// Function call to get time
	var timeinhour1 = getHour(timein1);
	var timeinmin1 = getMin(timein1);
	// Function call to get time of after break
	var timeinhour2 = getHour(timein2);
	var timeinmin2 = getMin(timein2);
	// Function call to get time of night shift
	var timeinhour3 = getHour(timein3);
	var timeinmin3 = getMin(timein3);

	var timeout1 = mainRow.querySelector('.timeout1').value; // Get time out value
	var timeout2 = mainRow.querySelector('.timeout2').value; // Get time out value of afterbreak
	var timeout3 = mainRow.querySelector('.timeout3').value; // Get time out value of nightshift
	

	// Function call to get time
	var timeouthour1 = getHour(timeout1);
	var timeoutmin1 = getMin(timeout1);
	// Function call to get time of after break
	var timeouthour2 = getHour(timeout2);
	var timeoutmin2 = getMin(timeout2);
	// Function call to get time of night shift
	var timeouthour3 = getHour(timeout3);
	var timeoutmin3 = getMin(timeout3);

	var nightShiftAuth = mainRow.querySelector('.nightshiftChk').checked;

	if(timein1 && timeout1)
		halfDayCheckbox(id);
	
	if(timein3 && timeout3)//if there is value inside nightshift
		computeTimeNightshift( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2, timeinhour3, timeinmin3, timeouthour3, timeoutmin3);
	
	// console.log("auth: "+ nightShiftAuth);
	// Function call to compute for working hours, undertime and overtime
	if(!nightShiftAuth) 
		computeTime( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
}

function timeOut(id) {
	var mainRow = document.getElementById(id); // Get row to be computed
	var timein1 = mainRow.querySelector('.timein1').value; // Get time in value
	var timein2 = mainRow.querySelector('.timein2').value; // Get time in value of afterbreak
	var timein3 = mainRow.querySelector('.timein3').value; // Get time in value of nightshift
	
	// Function call to get time
	var timeinhour1 = getHour(timein1);
	var timeinmin1 = getMin(timein1);
	// Function call to get time of after break
	var timeinhour2 = getHour(timein2);
	var timeinmin2 = getMin(timein2);
	// Function call to get time of night shift
	var timeinhour3 = getHour(timein3);
	var timeinmin3 = getMin(timein3);

	var timeout1 = mainRow.querySelector('.timeout1').value; // Get time out value
	var timeout2 = mainRow.querySelector('.timeout2').value; // Get time out value of afterbreak
	var timeout3 = mainRow.querySelector('.timeout3').value; // Get time out value of nightshift
	
	

	// Function call to get time
	var timeouthour1 = getHour(timeout1);
	var timeoutmin1 = getMin(timeout1);
	// Function call to get time of after break
	var timeouthour2 = getHour(timeout2);
	var timeoutmin2 = getMin(timeout2);
	// Function call to get time of night shift
	var timeouthour3 = getHour(timeout3);
	var timeoutmin3 = getMin(timeout3);

	//checks if nightshift is checked
	var nightShiftAuth = mainRow.querySelector('.nightshiftChk').checked;

	if(timein1 && timeout1)
		halfDayCheckbox(id);
	
	if(timein3 && timeout3)//if there is value inside nightshift
		computeTimeNightshift( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2, timeinhour3, timeinmin3, timeouthour3, timeoutmin3);
	
	// console.log("auth: "+ nightShiftAuth);
	// Function call to compute for working hours, undertime and overtime
	if(!nightShiftAuth)
		computeTime(mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
}	

	function halfDayCheckbox(id) {
		var mainRow = document.getElementById(id); // Get row to be computed
		mainRow.querySelector('.halfdayChk').disabled = false; // enable checkbox
	}

	function halfDay(id){
		var mainRow = document.getElementById(id); // Get row to be computed
		if(mainRow.querySelector('.halfdayChk').checked == false) // enable checkbox)
		{
			mainRow.querySelector('.timein2').placeholder = "";
			mainRow.querySelector('.timeout2').placeholder = "";
			mainRow.querySelector('.timein3').placeholder = "";
			mainRow.querySelector('.timeout3').placeholder = "";
			// delete values
			mainRow.querySelector('.timein2').value = "";
			mainRow.querySelector('.timeout2').value = "";
			mainRow.querySelector('.workinghours').value = "";
			mainRow.querySelector('.overtime').value = "";
			mainRow.querySelector('.undertime').value = "";
			mainRow.querySelector('.nightdiff').value = "";
			//for hidden rows
			mainRow.querySelector('.workinghoursH').value = "";
			mainRow.querySelector('.overtimeH').value = "";
			mainRow.querySelector('.undertimeH').value = "";
			mainRow.querySelector('.nightdiffH').value = "";

			mainRow.querySelector('.timein2').readOnly = false; // unset the textbox to readonly
			mainRow.querySelector('.timeout2').readOnly = false; // Unset the textbox to readonly

			// If absent was initially placed, changed to success
			if(mainRow.classList.contains('danger'))
			{
				mainRow.classList.remove('danger');
			}
			else 
			{
				mainRow.classList.remove('success');
			}
		}
		else
		{
			var timein1 = mainRow.querySelector('.timein1').value; // Get time in value
			var timeout1 = mainRow.querySelector('.timeout1').value; // Get time out value

			//Delete value
			mainRow.querySelector('.timein2').value = "";
			mainRow.querySelector('.timeout2').value = "";
			// Function call to get time
			var timeinhour1 = getHour(timein1);
			var timeinmin1 = getMin(timein1);
			// Function call to get time
			var timeouthour1 = getHour(timeout1);
			var timeoutmin1 = getMin(timeout1);

			var timeinhour2 = "HD";
			var timeinmin2 = "HD";
			var timeouthour2 = "HD";
			var timeoutmin2 = "HD";

			//Disable afterbreak and nightshift
			mainRow.querySelector('.timein2').readOnly = true; // Set the textbox to readonly
			mainRow.querySelector('.timein2').placeholder = "Half Day";
			mainRow.querySelector('.timeout2').readOnly = true; // Set the textbox to readonly
			mainRow.querySelector('.timeout2').placeholder = "Half Day";
			mainRow.querySelector('.timein3').readOnly = true; // Set the textbox to readonly
			mainRow.querySelector('.timein3').placeholder = "Half Day";
			mainRow.querySelector('.timeout3').readOnly = true; // Set the textbox to readonly
			mainRow.querySelector('.timeout3').placeholder = "Half Day";
			computeTime(mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
		}
		
	}

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
		//console.log(modal);
		
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
		mainRow.querySelector('.timein1').placeholder = "ABSENT";
		mainRow.querySelector('.timeout1').placeholder = "ABSENT";
		mainRow.querySelector('.timein2').placeholder = "ABSENT";
		mainRow.querySelector('.timeout2').placeholder = "ABSENT";
		// delete values
		mainRow.querySelector('.timein1').value = "";
		mainRow.querySelector('.timeout1').value = "";
		mainRow.querySelector('.timein2').value = "";
		mainRow.querySelector('.timeout2').value = "";
		mainRow.querySelector('.workinghours').value = "";
		mainRow.querySelector('.overtime').value = "";
		mainRow.querySelector('.undertime').value = "";
		//for hidden rows
		mainRow.querySelector('.workinghoursH').value = "";
		mainRow.querySelector('.overtimeH').value = "";
		mainRow.querySelector('.undertimeH').value = "";
		mainRow.querySelector('.nightdiffH').value = "";

		//for nightshift
		mainRow.querySelector('.nightshiftChk').disabled = true;

		//for halfday
		if(mainRow.querySelector('.halfdayChk').checked = true)
			mainRow.querySelector('.halfdayChk').checked = false;
	}

	function getHour(time) {
		//console.log("getHour: " + time);
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
	//console.log("getMin: " + time);
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

function computeTime(row, timeinhour1,timeinmin1,timeouthour1,timeoutmin1,timeinhour2,timeinmin2,timeouthour2,timeoutmin2) 
{
	//console.log("Time in: " + timeinhour1 + ":" + timeinmin1 + " Time out: " + timeouthour1 + ":" + timeoutmin1 + "/AFTER BREAK - Time in: " + timeinhour2 + ":" + timeinmin2 + " Time out: " + timeouthour2 + ":" + timeoutmin2 );

	row.querySelector('.attendance').value = "";

	// Verifies that time in and time out input fields have value
	if(timeinhour1 && timeouthour1 && timeinhour2 && timeouthour2)
	{	
		if(timeinhour2 != "HD" && timeouthour2 != "HD") 
		{
			row.querySelector('.nightshiftChk').disabled = false;
		}
		else
		{
			row.querySelector('.nightshiftChk').disabled = true;
		}
		
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
		var workinghours;
		var workingmins;

		var workinghours1;
		var workinghours2;

	//If employee chooses halfday
		if(timeinhour2 == "HD")//if Halfday
		{
			// If time is 12AM
			if(timeinhour1 == 0)
			{
				workinghours = timeouthour1;
			}
			else
			{
				workinghours = timeouthour1 - timeinhour1;
				//alert(workinghours);
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin1 > timeoutmin1)
			{
				workinghours = workinghours1 - 1;
			}
		}
		else
		{
			// If time is 12AM
			if(timeinhour1 == 0)
			{
				workinghours1 = timeouthour1;
			}
			else
			{
				workinghours1 = timeouthour1 - timeinhour1;
				//alert(workinghours);
			}
			// If time in 12AM of after break
			if(timeinhour2 == 0)
			{
				workinghours2 = timeouthour2;
			}
			else
			{
				workinghours2 = timeouthour2 - timeinhour2;
				//alert(workinghours);
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin1 > timeoutmin1)
			{
				workinghours1 = workinghours1 - 1;
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin2 > timeoutmin2)
			{
				workinghours2 = workinghours2 - 1;
			}
			// Gets the overal working hours;
			workinghours = workinghours1 + workinghours2;
			//console.log("Workinghours :  "+workinghours);
		}
		

		// MORNING SHIFT
		if(workinghours >= 1)
		{
		// Computing minutes
			console.log("Morning shift");
			//Before break
			//If employee chooses halfday
			if(timeinhour2 == "HD")
			{
				if(timeinmin1 > timeoutmin1)
				{
					workingmins = timeinmin1 - timeoutmin1;
				}
				if(timeoutmin1 > timeinmin1)
				{
					workingmins = timeoutmin1 - timeinmin1;
				}
				if(timeinmin1 == timeoutmin1)
				{
					workingmins = 0;
				}
			}
			else
			{
				if(timeinmin1 > timeoutmin1)
				{
					workingmins1 = timeinmin1 - timeoutmin1;
				}
				if(timeoutmin1 > timeinmin1)
				{
					workingmins1 = timeoutmin1 - timeinmin1;
				}
				if(timeinmin1 == timeoutmin1)
				{
					workingmins1 = 0;
				}
				//After break
				if(timeinmin2 > timeoutmin2)
				{
					workingmins2 = timeinmin2 - timeoutmin2;
				}
				if(timeoutmin2 > timeinmin2)
				{
					workingmins2 = timeoutmin2 - timeinmin2;
				}
				if(timeinmin2 == timeoutmin2)
				{
					workingmins2 = 0;
				}

				workingmins = workingmins1 + workingmins2;
			}
				

			

			if(workingmins >= 60)
			{
				workinghours++;
				 workingmins = Math.abs(workingmins) - 60;
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
				row.querySelector('.workinghours').value = workinghours + " hrs";
				row.querySelector('.workinghoursH').value = workinghours + " hrs";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hrs, " + workingmins + " mins";
			}

		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs";
			}
			else if(workinghours == 8 && workingmins != 0)
			{
				row.querySelector('.overtime').value = workingmins + " mins";
				row.querySelector('.overtimeH').value = workingmins + " mins";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				
				if((workinghours == 7) && (workingmins != 0))
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs";
				}
				
			}
			else if(workinghours < 8)
			{
				undertime = Math.abs(workinghours - 8);
				if(workinghours == 7)
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = undertime + " hrs, " + workingmins + " mins";
					row.querySelector('.undertimeH').value = undertime + " hrs, " + workingmins + " mins";
				}
				
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}

			//Create Nightdiff for Dayshift if workhours enters 10pm
			var nightdiff = "";
			//nightdiff MORNING
			if(timeouthour1 == 0)//change timeout hour to 24 if 12am
			{
				timeouthour1 = 24;
			}
			else if(timeouthour2 == 0)//change timeout hour to 24 if 12am
			{
				timeouthour2 = 24;
			}
			//If employee chooses halfday
			if(timeinhour2 == "HD")//Night diff
			{
				if ((timeinhour1 <= 22 && timeouthour1 <= 6) ||
			   (timeinhour1 <= 22 && timeouthour1 <= 24))
				{
					//console.log("timein: "+timeinhour1+" | timeout: "+timeouthour1);
					//posibility 1
					if(timeinhour1 <= 24 && timeouthour1 >= 22)
					{
						//console.log("possibility 1");
						nightdiff = timeouthour1 - 22;
						//console.log("nightdiff : "+nightdiff);
					}
				// // possibility 2 -- When after break time is past 12AM (and started past 10PM)
				// 	else if((timeinhour1 <= 22 || timeinhour1 >= 22))
				// 	{
				// 		console.log("possibility 2la");

				// 		nightdiff = timeouthour1 - 22;
				// 	}
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
					
					row.querySelector('.nightdiff').value = nightdiff + " hrs";
					row.querySelector('.nightdiffH').value = nightdiff + " hrs";
				}
				else
				{
					
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
			else if ((timeinhour1 <= 22 && timeouthour1 <= 6) ||
			   (timeinhour1 <= 22 && timeouthour1 <= 24) ||
			   (timeinhour2 <= 22 && timeouthour2 <= 6) ||
			   (timeinhour2 <= 22 && timeouthour2 <= 24))//night diff needs reconfiguration
			{
				//console.log("timein1: "+timeinhour1+"// timeout1: "+timeouthour1+"// timein2: "+timeinhour2+"// timeout2: "+timeouthour2);
				// console.log("nightdiff 2");
			//posibility 1
				if(timeouthour2 <= 24 && timeouthour2 >= 22)
				{
					//console.log("possibility 1");
					nightdiff = timeouthour2 - 22;
					//console.log("nightdiff : "+nightdiff);
				}
			// possibility 2 -- When after break time is past 12AM (and started past 10PM)
				else if(timeouthour2 >= 24 && timeouthour2 <= 6 && (timeinhour1 <= 22 || timeinhour1 >= 22 || timeinhour2 <= 22 || timeouthour2 >= 22))
				{
					//console.log("possibility 2");

					nightdiff = timeouthour1 - 22 + (timeouthour2 - timeinhour2);

					// To retain night differential value, must retain computation based on boundary time
					if(timeouthour2 > 6)
					{

						nightdiff = timeouthour1 - 22 + (6 - timeinhour2);
					}

				}
				else if (timeinhour1 <= 22 && timeouthour1 >= 22)
				{
					//console.log("possibility 3");
					nightdiff = timeouthour1 - 22;

					if(timeouthour2 > 6)
					{
						//console.log("possibility 3.1");
						temp = (timeinhour2 - 6);
						nightdiff += Math.abs(temp); 
					}
					else if(timeouthour2 <= 6)
					{
						//console.log("possibility 3.2");
						temp = timeinhour2 - timeouthour2;
						nightdiff += Math.abs(temp); 
					}

				}
				else
				{
					//console.log("possibility 4");
					nightdiff = "";
				}
				if(Number.isInteger(nightdiff))
				{
				   	nightdiff = Math.abs(nightdiff);		
				}
			}
			if(nightdiff != "")
			{
				
				row.querySelector('.nightdiff').value = nightdiff + " hrs";
				row.querySelector('.nightdiffH').value = nightdiff + " hrs";
			}
			else
			{
				
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
			console.log("nightshift");
		// Night differential starts at 10pm - 6am
			//console.log("Time in: " + timeinhour + ":" + timeinmin + " Time out: " + timeouthour + ":" + timeoutmin);
			//console.log("Working hours: " + workinghours + " Working mins: " + workingmins);
			// sets the attendance status to PRESENT
			//row.querySelector('.attendance').value = "PRESENT";

			//Invert the time to make the computation the same as the morning shift
			// timeinhour1 -= 12;
			// timeouthour1 += 12;
			// timeinhour2 += 12;
			// timeouthour2 += 12;

			if(timeinhour1 < 10)
				timeinhour1 +=12;
			else
				timeinhour1 -=12;
			if(timeouthour1 < 10)
				timeouthour1 +=12;
			else
				timeouthour1 -=12;
			//If employee chooses halfday
			if(timeinhour2 != "HD")//Night diff
			{
				if(timeinhour2 < 10)
				timeinhour2 +=12;
				else
					timeinhour2 -=12;
				if(timeouthour2 < 10)
					timeouthour2 +=12;
				else
					timeouthour2 -=12;
			}
			
			
			//console.log("timein1: "+timeinhour1+"// timeout1: "+timeouthour1+"// timein2: "+timeinhour2+"// timeout2: "+timeouthour2);
			workinghours1 = timeouthour1 - timeinhour1;
			if(timeinhour2 != "HD")//Night diff
			{
				workinghours2 = timeouthour2 - timeinhour2;
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin1 > timeoutmin1)
			{
				workinghours1 = workinghours1 - 1;
			}
			//If employee chooses halfday
			if(timeinhour2 != "HD")//Night diff
			{
				//Decrement workinghours if minutes is more than time out mins
				if(timeinmin2 > timeoutmin2)
				{
					workinghours2 = workinghours2 - 1;
				}
			}
			//If employee chooses halfday
			if(timeinhour2 != "HD")//Night diff
			{
				workinghours = Math.abs(workinghours1) + Math.abs(workinghours2);
			}
			else
			{
				workinghours = Math.abs(workinghours1);
			}
			
			//alert(workinghours);
			//alert("timein: "+timeinhour + " timeout: " + timeouthour);
			//Computing minutes
			if(timeinmin1 > timeoutmin1)
			{
				workingmins1 = timeinmin1 - timeoutmin1;
			}
			if(timeoutmin1 > timeinmin1)
			{
				workingmins1 = timeoutmin1 - timeinmin1;
			}
			if(timeinmin1 == timeoutmin1)
			{
				workingmins1 = 0;
			}

			//If employee chooses halfday
			if(timeinhour2 != "HD")//Night diff
			{
				//After break
				if(timeinmin2 > timeoutmin2)
				{
					workingmins2 = timeinmin2 - timeoutmin2;
				}
				if(timeoutmin2 > timeinmin2)
				{
					workingmins2 = timeoutmin2 - timeinmin2;
				}
				if(timeinmin2 == timeoutmin2)
				{
					workingmins2 = 0;
				}

				workingmins = workingmins1 + workingmins2;
			}
			else
			{
				workingmins = workingmins1;
			}
			if(workingmins >= 60)
			{
				workinghours++;
				Math.abs(workingmins) -= 60;
			}
			

			

		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
				row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
			}
			else if(workingmins == 0)
			{
				row.querySelector('.workinghours').value = workinghours + " hrs";
				row.querySelector('.workinghoursH').value = workinghours + " hrs";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hrs, " + workingmins + " mins";	
			}
		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				if((workinghours == 7) && (workingmins != 0))
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs";
				}
			}
			else if(workinghours < 8)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}
		
		// NIGHT DIFF if Working Hours is in between 10pm - 6am
		// 10 is 10pm and 18 is 6pm
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
			var nightdiff = "";

			//If employee chooses halfday
			if(timeinhour2 != "HD")
			{
				//alert("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1+"// timeinhour2: "+ timeinhour2+"// timeouthour2: "+ timeouthour2);
				if((timeinhour1 <= 10 && timeouthour1 <= 18) || (timeinhour2 <= 10 && timeouthour2 <= 18))//night diff needs reconfiguration
				{
					//console.log("nightdiff");
					var NDin;
					var NDout;
					var workhrs;
					//Possibility 2: if 10pm is in after lunch
					if(timeinhour2 < 10)
					{
						//console.log("possibility 2");
						NDin = timeinhour2 - 10;
						NDout = 0;
						if(timeouthour2 >= 18)
						{
							NDout = timeouthour2 - 18;
						}
						//console.log("NDout : "+ NDout);
						workhrs = timeinhour2 - timeouthour2;
						nightdiff = Math.abs(NDin) - Math.abs(workhrs);
						//console.log("nightdiff : "+ nightdiff);
						nightdiff += NDout;
						// console.log("ND: "+nightdiff);
					}
					//Possibility 1: if 10pm is in before lunch
					else if(timeinhour1 <= 10)
					{
						if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
						{
							//console.log("possibility 1-1");

							NDin = timeinhour1 - 10;
							NDout = timeouthour1 - 18;
							workhrs = timeinhour1 - timeouthour1;
							nightdiff = (Math.abs(NDin) + Math.abs(NDout1)) - Math.abs(workhrs);
						}
						else//the normal night diff
						{
							//console.log("possibility 1-2");

							workhrs1 = timeinhour1 - timeouthour1;
							NDin1 = timeinhour1 - 10;
							nightdiff1 = Math.abs(NDin1) - Math.abs(workhrs1);
							// console.log("ND1: "+nightdiff1);
							// console.log(timeouthour2);
							NDin2 = timeinhour2 - 10;
							if(timeouthour2 <=18)
							{
								//console.log("possibility 1-2-1");
								NDout2 = timeouthour2 - 10;
							}
							else
							{
								//console.log("possibility 1-2-2");
								NDout2 = 8;
							}
							nightdiff2 = Math.abs(NDin2) - Math.abs(NDout2);
							// console.log("NDout2: "+NDout2);
							// console.log("ND2: "+nightdiff2);
							nightdiff = nightdiff1 + nightdiff2;
							
							nightdiff = Math.abs(nightdiff);
						}
						
					}
						
					
					if(Number.isInteger(nightdiff))
					{
					   	nightdiff = Math.abs(nightdiff);		
					}
				}
			}
			else
			{
				//alert("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1+"// timeinhour2: "+ timeinhour2+"// timeouthour2: "+ timeouthour2);
				if((timeinhour1 <= 10 && timeouthour1 <= 18))//night diff needs reconfiguration
				{
					console.log("nightdiff");
					var NDin;
					var NDout;
					var workhrs;
					
					//Possibility 1: if 10pm is in before lunch
					if(timeinhour1 <= 10)
					{
						if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
						{
							//console.log("possibility 1-1");

							NDin = timeinhour1 - 10;
							NDout = timeouthour1 - 18;
							workhrs = timeinhour1 - timeouthour1;
							nightdiff = (Math.abs(NDin) + Math.abs(NDout1)) - Math.abs(workhrs);
						}
						else//the normal night diff
						{
							//console.log("possibility 1-2");

							workhrs1 = timeinhour1 - timeouthour1;
							NDin1 = timeinhour1 - 10;
							nightdiff1 = Math.abs(NDin1) - Math.abs(workhrs1);
							// console.log("ND1: "+nightdiff1);
							// console.log(timeouthour2);
							
							
							// console.log("NDout2: "+NDout2);
							// console.log("ND2: "+nightdiff2);
							nightdiff = nightdiff1;
							
							nightdiff = Math.abs(nightdiff);
						}
						
					}
						
					
					if(Number.isInteger(nightdiff))
					{
					   	nightdiff = Math.abs(nightdiff);		
					}
				}
			}
			if(nightdiff != "")
			{
				
				row.querySelector('.nightdiff').value = nightdiff + " hrs";
				row.querySelector('.nightdiffH').value = nightdiff + " hrs";
			}
			else
			{
				
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

	}
	else
	{

		row.querySelector('.workinghours').value = "";
		row.querySelector('.overtime').value = "";
		row.querySelector('.undertime').value = "";
		row.querySelector('.nightdiff').value = "";
		row.querySelector('.timein1').placeholder = "";
		row.querySelector('.timeout1').placeholder = "";

		//for night shift
		row.querySelector('.nightshiftChk').disabled = true;

		if(!row.querySelector('.halfdayChk').checked)
		{
			//console.log('yea');
			row.querySelector('.timein2').placeholder = "";
			row.querySelector('.timeout2').placeholder = "";
			row.querySelector('.timein3').placeholder = "";
			row.querySelector('.timeout3').placeholder = "";
		}
		//console.log('yow');
		//for hidden rows
		row.querySelector('.workinghoursH').value = "";
		row.querySelector('.overtimeH').value = "";
		row.querySelector('.undertimeH').value = "";
		row.querySelector('.nightdiffH').value = "";
		row.querySelector('.attendance').value = "";
		if(row.classList.contains('danger'))
		{
			row.classList.remove('danger');
		}
		if(row.classList.contains('success'))
		{
			row.classList.remove('success');
		}
	}
}	

// For night shift
function computeTimeNightshift( row, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2, timeinhour3, timeinmin3, timeouthour3, timeoutmin3)
{
	//console.log("Time in: " + timeinhour1 + ":" + timeinmin1 + " Time out: " + timeouthour1 + ":" + timeoutmin1 + "/AFTER BREAK - Time in: " + timeinhour2 + ":" + timeinmin2 + " Time out: " + timeouthour2 + ":" + timeoutmin2 );

	row.querySelector('.attendance').value = "";

	// Verifies that time in and time out input fields have value
	if(timeinhour1 && timeouthour1 && timeinhour2 && timeouthour2 && timeinhour3 && timeouthour3)
	{
		console.log('timein1: '+timeinhour1+' || timeout1: '+timeouthour1+' || timein2: '+timeinhour2+' || timeout2: '+timeouthour2+' || timein3: '+timeinhour3+' || timeout3: '+timeouthour3);
		//alert("timein: "+timeinhour+"timeout: "+ timeouthour);
		var workinghours;
		var workingmins;

		var workinghours1;
		var workinghours2;

		var workinghours3;
		var workinghours3;

		

		// If time is 12AM
		if(timeinhour1 == 0)
		{
			workinghours1 = timeouthour1;
		}
		else
		{
			workinghours1 = timeouthour1 - timeinhour1;
			//alert(workinghours);
		}
		// If time in 12AM of after break
		if(timeinhour2 == 0)
		{
			workinghours2 = timeouthour2;
		}
		else
		{
			workinghours2 = timeouthour2 - timeinhour2;
			//alert(workinghours);
		}
		// If time in 12AM of nightshift
		if(timeinhour3 == 0)
		{
			workinghours3 = timeouthour3;
		}
		else
		{
			workinghours3 = timeouthour3 - timeinhour3;
			//alert(workinghours);
		}
		//Decrement workinghours if minutes is more than time out mins
		if(timeinmin1 > timeoutmin1)
		{
			workinghours1 = workinghours1 - 1;
		}
		//Decrement workinghours if minutes is more than time out mins
		if(timeinmin2 > timeoutmin2)
		{
			workinghours2 = workinghours2 - 1;
		}
		//Decrement workinghours if minutes is more than time out mins
		if(timeinmin3 > timeoutmin3)
		{
			workinghours3 = workinghours3 - 1;
		}
		// Gets the overal working hours;
		workinghours = workinghours1 + workinghours2 + workinghours3;
		//console.log("Workinghours :  "+workinghours);
		
		

		// MORNING SHIFT
		if(workinghours >= 1)
		{
		// Computing minutes
			console.log("Morning shift");
		
			//Before break
			if(timeinmin1 > timeoutmin1)
			{
				workingmins1 = timeinmin1 - timeoutmin1;
			}
			if(timeoutmin1 > timeinmin1)
			{
				workingmins1 = timeoutmin1 - timeinmin1;
			}
			if(timeinmin1 == timeoutmin1)
			{
				workingmins1 = 0;
			}
			//After break
			if(timeinmin2 > timeoutmin2)
			{
				workingmins2 = timeinmin2 - timeoutmin2;
			}
			if(timeoutmin2 > timeinmin2)
			{
				workingmins2 = timeoutmin2 - timeinmin2;
			}
			if(timeinmin2 == timeoutmin2)
			{
				workingmins2 = 0;
			}
			//nightshift
			if(timeinmin3 > timeoutmin3)
			{
				workingmins3 = timeinmin3 - timeoutmin3;
			}
			if(timeoutmin3 > timeinmin3)
			{
				workingmins3 = timeoutmin3 - timeinmin3;
			}
			if(timeinmin3 == timeoutmin3)
			{
				workingmins3 = 0;
			}

			workingmins = workingmins1 + workingmins2 + workingmins3;
			
			if(workingmins >= 60)
			{
				workinghours++;
				 workingmins = Math.abs(workingmins) - 60;
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
				row.querySelector('.workinghours').value = workinghours + " hrs";
				row.querySelector('.workinghoursH').value = workinghours + " hrs";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hrs, " + workingmins + " mins";
			}

		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs";
			}
			else if(workinghours == 8 && workingmins != 0)
			{
				row.querySelector('.overtime').value = workingmins + " mins";
				row.querySelector('.overtimeH').value = workingmins + " mins";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				
				if((workinghours == 7) && (workingmins != 0))
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs";
				}
				
			}
			else if(workinghours < 8)
			{
				undertime = Math.abs(workinghours - 8);
				if(workinghours == 7)
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = undertime + " hrs, " + workingmins + " mins";
					row.querySelector('.undertimeH').value = undertime + " hrs, " + workingmins + " mins";
				}
				
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}

			//Create Nightdiff for Dayshift if workhours enters 10pm
			var nightdiff = "";
			//nightdiff MORNING
			if(timeouthour1 == 0)//change timeout hour to 24 if 12am
			{
				timeouthour1 = 24;
			}
			if(timeouthour2 == 0)//change timeout hour to 24 if 12am
			{
				timeouthour2 = 24;
			}
			if(timeouthour3 == 0)//change timeout hour to 24 if 12am
			{
				timeouthour3 = 24;
			}
			
			//Declaration of variable to be computed
			var time1 = 0;
			var time2 = 0;
			var time3 = 0;

			if ((timeinhour1 <= 22 && timeouthour1 >= 22) ||// pos1 ~ 6
				(timeinhour1 <= 22 && timeouthour1 <= 6) ||// pos7 ~ 8
				(timeinhour2 <= 22 && timeouthour2 >= 22) ||// pos9 ~ 10
				(timeinhour2 <= 22 && timeouthour2 <= 6) ||// pos 11 ~ 12
				(timeinhour3 <= 22 && timeouthour3 >= 22) ||// pos 13
				(timeinhour3 <= 22 && timeouthour3 <= 6))// pos 14
			{
				console.log('timein1: '+timeinhour1+' || timeout1: '+timeouthour1+' || timein2: '+timeinhour2+' || timeout2: '+timeouthour2+' || timein3: '+timeinhour3+' || timeout3: '+timeouthour3);
				if(timeinhour1 <= 22 && timeouthour1 >= 22)// pos1 ~ 6
				{
					// console.log('pos 1~6');
					if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 >= 1 && timeouthour3 <= 6))// pos 1
					{
						// console.log('pos 1');
						time1 = timeouthour1 - 22;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 2
					{
						// console.log('pos 2');
						time1 = timeouthour1 - 22;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - 6;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 22 && timeouthour2 <= 6) && (timeinhour3 >= 1 && timeouthour3 <= 6))// pos 3
					{
						// console.log('pos 3');
						time1 = timeouthour1 - 22;

						var time2n1 = timeinhour2 - 24;
						time2 = Math.abs(time2n1) + timeouthour2;

						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 22 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 4
					{
						// console.log('pos 4');
						time1 = timeouthour1 - 22;

						var time2n1 = timeinhour2 - 24;
						time2 = Math.abs(time2n1) + timeouthour2;

						time3 = timeinhour3 - 6;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 1 && timeouthour2 <= 6) && timeinhour3 >= 6)// pos 5
					{
						// console.log('pos 5');
						time1 = timeouthour1 - 22;
						time2 = timeinhour2 - timeouthour2;
						nightdiff = Math.abs(time1) + Math.abs(time2);
					}
					else// pos 6 -- 1st time is the only one inside nightdiff time
					{
						// console.log('pos 6');
						time1 = timeouthour1 - 22;
						nightdiff = Math.abs(time1);
					}

				}
				else if(timeinhour1 <= 22 && timeouthour1 <= 6)// pos7 ~ 8
				{
					// console.log('pos 7~8');
					if((timeinhour2 >= 1 && timeouthour2 <= 6) && timeinhour3 > 6)// pos 7
					{
						// console.log('pos 7');
						var time1n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
						time1 = Math.abs(time1n1) + timeouthour1;

						time2 = timeinhour2 - timeouthour2;
						nightdiff = Math.abs(time1) + Math.abs(time2);
					}
					else if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 8
					{
						// console.log('pos 8');
						var time1n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
						time1 = Math.abs(time1n1) + timeouthour1;

						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - 6;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
				}
				else if(timeinhour2 <= 22 && timeouthour2 >= 22)// pos9 ~ 10
				{
					// console.log('pos 9~10');
					if(timeinhour3 >= 1 && timeouthour3 <= 6) // pos 9
					{
						// console.log('pos 9');
						time2 = timeouthour2 - 22;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
					else // pos 10
					{
						// console.log('pos 10');
						time2 = timeouthour2 - 22;
						nightdiff = Math.abs(time2);
					}
				}
				else if(timeinhour2 <= 22 && timeouthour2 <= 6)// pos 11 ~ 12
				{
					// console.log('pos 11~12');
					if(timeinhour3 <= 6 && timeouthour3 >= 6)// pos 11
					{
						// console.log('pos 11');
						var time2n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
						time2 = Math.abs(time2n1) + timeouthour2;;

						time3 = timeinhour3 - 6;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
					else if(timeinhour3 >= 1 && timeouthour3 <= 6)// pos 12
					{
						// console.log('pos 12');
						var time2n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
						time2 = Math.abs(time2n1) + timeouthour2;;

						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
				}
				else if(timeinhour3 <= 22 && timeouthour3 >= 22)// pos 13
				{
					// console.log('pos 13');
					time3 = timeouthour3 - 22;
					nightdiff = Math.abs(time3);
				}
				else if(timeinhour3 <= 22 && timeouthour3 <= 6)// pos 14
				{
					// console.log('pos 14');
					var time3n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
					time3 = Math.abs(time3n1) + timeouthour3;

					nightdiff = Math.abs(time3);
				}
				else
				{
					nightdiff = "";
				}
			}
			else
			{
				nightdiff = "";
			}

			if(Number.isInteger(nightdiff))
			{
			   	nightdiff = Math.abs(nightdiff);		
			}
			if(nightdiff != "")
			{
				
				row.querySelector('.nightdiff').value = nightdiff + " hrs";
				row.querySelector('.nightdiffH').value = nightdiff + " hrs";
			}
			else
			{
				
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
			console.log("nightshift1");
		console.log('timein1: '+timeinhour1+' || timeout1: '+timeouthour1+' || timein2: '+timeinhour2+' || timeout2: '+timeouthour2+' || timein3: '+timeinhour3+' || timeout3: '+timeouthour3);

		//Invert time for easier computation of night diff
			//before break
			
			if(timeinhour1 < 12)
				timeinhour1 +=12;
			else
				timeinhour1 -=12;

			if(timeouthour1 < 12)
				timeouthour1 +=12;
			else
				timeouthour1 -=12;

			//after break
			if(timeinhour2 < 10)
			timeinhour2 +=12;
			else
				timeinhour2 -=12;

			if(timeouthour2 < 10)
				timeouthour2 +=12;
			else
				timeouthour2 -=12;
			//night shift
			if(timeinhour3 < 10)
			timeinhour3 +=12;
			else
				timeinhour3 -=12;

			if(timeouthour3 < 10)
				timeouthour3 +=12;
			else
				timeouthour3 -=12;

			// All 12pm will turn into 24
			if(timeinhour1 == 0)
				timeinhour1 = 24;
			if(timeouthour1 == 0)
				timeouthour1 = 24;

			if(timeinhour2 == 0)
				timeinhour2 = 24;
			if(timeouthour2 == 0)
				timeouthour2 = 24;

			if(timeinhour3 == 0)
				timeinhour3 = 24;
			if(timeouthour3 == 0)
				timeouthour3 = 24;
			
			//console.log("timein1: "+timeinhour1+"// timeout1: "+timeouthour1+"// timein2: "+timeinhour2+"// timeout2: "+timeouthour2);
			workinghours1 = timeouthour1 - timeinhour1;
			workinghours2 = timeouthour2 - timeinhour2;
			workinghours3 = timeouthour3 - timeinhour3;
			console.log('workinghours1: '+workinghours1+' | workinghours2: '+workinghours2+' | workinghours3: '+workinghours3);

			console.log('timein1: '+timeinhour1+' || timeout1: '+timeouthour1+' || timein2: '+timeinhour2+' || timeout2: '+timeouthour2+' || timein3: '+timeinhour3+' || timeout3: '+timeouthour3);

			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin1 > timeoutmin1)
			{
				workinghours1 = workinghours1 - 1;
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin2 > timeoutmin2)
			{
				workinghours2 = workinghours2 - 1;
			}
			//Decrement workinghours if minutes is more than time out mins
			if(timeinmin3 > timeoutmin3)
			{
				workinghours3 = workinghour3 - 1;
			}
			workinghours = Math.abs(workinghours1) + Math.abs(workinghours2) + Math.abs(workinghours3);
			
			
			//alert(workinghours);
			//alert("timein: "+timeinhour + " timeout: " + timeouthour);
			//Computing minutes
			if(timeinmin1 > timeoutmin1)
			{
				workingmins1 = timeinmin1 - timeoutmin1;
			}
			if(timeoutmin1 > timeinmin1)
			{
				workingmins1 = timeoutmin1 - timeinmin1;
			}
			if(timeinmin1 == timeoutmin1)
			{
				workingmins1 = 0;
			}

			//After break
			if(timeinmin2 > timeoutmin2)
			{
				workingmins2 = timeinmin2 - timeoutmin2;
			}
			if(timeoutmin2 > timeinmin2)
			{
				workingmins2 = timeoutmin2 - timeinmin2;
			}
			if(timeinmin2 == timeoutmin2)
			{
				workingmins2 = 0;
			}

			//night shift
			if(timeinmin3 > timeoutmin3)
			{
				workingmins3 = timeinmin3 - timeoutmin3;
			}
			if(timeoutmin3 > timeinmin3)
			{
				workingmins3 = timeoutmin3 - timeinmin3;
			}
			if(timeinmin3 == timeoutmin3)
			{
				workingmins3 = 0;
			}

			
			workingmins = workingmins1 + workingmins2 + workingmins3;
			
			if(workingmins >= 60)
			{
				workinghours++;
				Math.abs(workingmins) -= 60;
			}
			

			

		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				row.querySelector('.workinghours').value = workinghours + " hrs/HALFDAY";
				row.querySelector('.workinghoursH').value = workinghours + " hrs/HALFDAY";
			}
			else if(workingmins == 0)
			{
				row.querySelector('.workinghours').value = workinghours + " hrs";
				row.querySelector('.workinghoursH').value = workinghours + " hrs";
			}
			else
			{
				row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";	
				row.querySelector('.workinghoursH').value = workinghours + " hrs, " + workingmins + " mins";	
			}
		// OVERTIME if Working Hours exceed 8
			if(workinghours > 8 && workingmins == 0)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs";
			}
			else if (workinghours > 8)
			{
				row.querySelector('.overtime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.overtimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.overtime').value = "";
				row.querySelector('.overtimeH').value = "";
			}

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				if((workinghours == 7) && (workingmins != 0))
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs";
					row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs";
				}
			}
			else if(workinghours < 8)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs, " + workingmins + " mins";
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}
		
		// NIGHT DIFF if Working Hours is in between 10pm - 6am
		// 10 is 10pm and 18 is 6pm
		// 22 = 10 and 6 = 18
			var nightdiff = "";

			//Declaration of variable to be computed
			var time1 = 0;
			var time2 = 0;
			var time3 = 0;

			if ((timeinhour1 <= 10 && timeouthour1 >= 10) ||// pos1 ~ 6
				(timeinhour1 <= 10 && timeouthour1 <= 18) ||// pos7 ~ 8
				(timeinhour2 <= 10 && timeouthour2 >= 10) ||// pos9 ~ 10
				(timeinhour2 <= 10 && timeouthour2 <= 18) ||// pos 11 ~ 12
				(timeinhour3 <= 10 && timeouthour3 >= 10) ||// pos 13
				(timeinhour3 <= 10 && timeouthour3 <= 18))// pos 14
			{
				//console.log('timein1: '+timeinhour1+' || timeout1: '+timeouthour1+' || timein2: '+timeinhour2+' || timeout2: '+timeouthour2+' || timein3: '+timeinhour3+' || timeout3: '+timeouthour3);
				if((timeinhour1 <= 10 && timeouthour1 >= 10) && timeouthour1 <= 12)// pos1 ~ 6
				{
					// console.log('pos 1~6');
					if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 >= 12 && timeouthour3 <= 18))// pos 1
					{
						// console.log('pos 1');
						time1 = timeouthour1 - 10;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 2
					{
						// console.log('pos 2');
						time1 = timeouthour1 - 10;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - 18;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					if((timeinhour2 >= 10 && timeouthour2 <= 18) && (timeinhour3 >= 12 && timeouthour3 <= 18))// pos 3
					{
						// console.log('pos 3');
						time1 = timeouthour1 - 10;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 10 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 4
					{
						// console.log('pos 4');
						time1 = timeouthour1 - 10;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - 18;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
					else if((timeinhour2 >= 12 && timeouthour2 <= 18) && timeinhour3 >= 18)// pos 5
					{
						// console.log('pos 5');
						time1 = timeouthour1 - 10;
						time2 = timeinhour2 - timeouthour2;
						nightdiff = Math.abs(time1) + Math.abs(time2);
					}
					else// pos 6 -- 1st time is the only one inside nightdiff time
					{
						// console.log('pos 6');
						time1 = timeouthour1 - 10;
						nightdiff = Math.abs(time1);
					}

				}
				else if(timeinhour1 <= 10 && timeouthour1 >= 10)// pos7 ~ 8
				{
					// console.log('pos 7~8');
					if((timeinhour2 >= 12 && timeouthour2 <= 18) && timeinhour3 > 18)// pos 7
					{
						// console.log('pos 7');
						time1 = 10 - timeouthour1;
						time2 = timeinhour2 - timeouthour2;
						nightdiff = Math.abs(time1) + Math.abs(time2);
					}
					else if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 8
					{
						// console.log('pos 8');
						time1 = 10 - timeouthour1;
						time2 = timeinhour2 - timeouthour2;
						time3 = timeinhour3 - 18;
						nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
					}
				}
				else if(timeinhour2 <= 10 && timeouthour2 >= 10)// pos9 ~ 10
				{
					// console.log('pos 9~10');
					if(timeinhour3 >= 12 && timeouthour3 <= 18) // pos 9
					{
						// console.log('pos 9');
						time2 = timeouthour2 - 10;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
					else // pos 10
					{
						// console.log('pos 10');
						time2 = timeouthour2 - 10;
						nightdiff = Math.abs(time2);
					}
				}
				else if((timeinhour2 <= 10 && timeouthour2 >= 10) && timeouthour2 >= 12)// pos 11 ~ 12
				{
					// console.log('pos 11~12');
					if(timeinhour3 <= 18 && timeouthour3 >= 18)// pos 11
					{
						// console.log('pos 11');
						time2 = 10 - timeouthour2;
						time3 = timeinhour3 - 18;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
					else if(timeinhour3 >= 12 && timeouthour3 <= 18)// pos 12
					{
						// console.log('pos 12');
						time2 = 10 - timeouthour2;
						time3 = timeinhour3 - timeouthour3;
						nightdiff = Math.abs(time2) + Math.abs(time3);
					}
				}
				else if(timeinhour3 <= 10 && timeouthour3 >= 10)// pos 13
				{
					// console.log('pos 13');
					time3 = timeouthour3 - 10;
					nightdiff = Math.abs(time3);
				}
				else if(timeinhour3 <= 10 && timeouthour3 <= 18)// pos 14
				{
					// console.log('pos 14');
					time3 = 10 - timeouthour3;
					nightdiff = Math.abs(time3);
				}
				else
				{
					nightdiff = "";
				}
			}
			else
			{
				nightdiff = "";
			}

			if(Number.isInteger(nightdiff))
			{
			   	nightdiff = Math.abs(nightdiff);		
			}
			if(nightdiff != "")
			{
				
				row.querySelector('.nightdiff').value = nightdiff + " hrs";
				row.querySelector('.nightdiffH').value = nightdiff + " hrs";
			}
			else
			{
				
				row.querySelector('.nightdiff').value = "";
				row.querySelector('.nightdiffH').value = "";
			}

			
			// //alert("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1+"// timeinhour2: "+ timeinhour2+"// timeouthour2: "+ timeouthour2);
			// if((timeinhour1 <= 10 && timeouthour1 <= 18) || (timeinhour2 <= 10 && timeouthour2 <= 18))//night diff needs reconfiguration
			// {
			// 	//console.log("nightdiff");
			// 	var NDin;
			// 	var NDout;
			// 	var workhrs;
			// 	//Possibility 2: if 10pm is in after lunch
			// 	if(timeinhour2 < 10)
			// 	{
			// 		//console.log("possibility 2");
			// 		NDin = timeinhour2 - 10;
			// 		NDout = 0;
			// 		if(timeouthour2 >= 18)
			// 		{
			// 			NDout = timeouthour2 - 18;
			// 		}
			// 		//console.log("NDout : "+ NDout);
			// 		workhrs = timeinhour2 - timeouthour2;
			// 		nightdiff = Math.abs(NDin) - Math.abs(workhrs);
			// 		//console.log("nightdiff : "+ nightdiff);
			// 		nightdiff += NDout;
			// 		// console.log("ND: "+nightdiff);
			// 	}
			// 	//Possibility 1: if 10pm is in before lunch
			// 	else if(timeinhour1 <= 10)
			// 	{
			// 		if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
			// 		{
			// 			//console.log("possibility 1-1");

			// 			NDin = timeinhour1 - 10;
			// 			NDout = timeouthour1 - 18;
			// 			workhrs = timeinhour1 - timeouthour1;
			// 			nightdiff = (Math.abs(NDin) + Math.abs(NDout1)) - Math.abs(workhrs);
			// 		}
			// 		else//the normal night diff
			// 		{
			// 			//console.log("possibility 1-2");

			// 			workhrs1 = timeinhour1 - timeouthour1;
			// 			NDin1 = timeinhour1 - 10;
			// 			nightdiff1 = Math.abs(NDin1) - Math.abs(workhrs1);
			// 			// console.log("ND1: "+nightdiff1);
			// 			// console.log(timeouthour2);
			// 			NDin2 = timeinhour2 - 10;
			// 			if(timeouthour2 <=18)
			// 			{
			// 				//console.log("possibility 1-2-1");
			// 				NDout2 = timeouthour2 - 10;
			// 			}
			// 			else
			// 			{
			// 				//console.log("possibility 1-2-2");
			// 				NDout2 = 8;
			// 			}
			// 			nightdiff2 = Math.abs(NDin2) - Math.abs(NDout2);
			// 			// console.log("NDout2: "+NDout2);
			// 			// console.log("ND2: "+nightdiff2);
			// 			nightdiff = nightdiff1 + nightdiff2;
						
			// 			nightdiff = Math.abs(nightdiff);
			// 		}
					
			// 	}
					
				
			// 	if(Number.isInteger(nightdiff))
			// 	{
			// 	   	nightdiff = Math.abs(nightdiff);		
			// 	}
			// }
		
			
			// if(nightdiff != "")
			// {
				
			// 	row.querySelector('.nightdiff').value = nightdiff + " hrs";
			// 	row.querySelector('.nightdiffH').value = nightdiff + " hrs";
			// }
			// else
			// {
				
			// 	row.querySelector('.nightdiff').value = "";
			// 	row.querySelector('.nightdiffH').value = "";
			// }
			
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

	}
	else
	{

		row.querySelector('.workinghours').value = "";
		row.querySelector('.overtime').value = "";
		row.querySelector('.undertime').value = "";
		row.querySelector('.nightdiff').value = "";
		row.querySelector('.timein1').placeholder = "";
		row.querySelector('.timeout1').placeholder = "";
		row.querySelector('.timein2').placeholder = "";
		row.querySelector('.timeout2').placeholder = "";
		row.querySelector('.timein3').placeholder = "";
		row.querySelector('.timeout3').placeholder = "";

		//for night shift
		row.querySelector('.nightshiftChk').disabled = true;

		
		
	
		//for hidden rows
		row.querySelector('.workinghoursH').value = "";
		row.querySelector('.overtimeH').value = "";
		row.querySelector('.undertimeH').value = "";
		row.querySelector('.nightdiffH').value = "";
		row.querySelector('.attendance').value = "";
		if(row.classList.contains('danger'))
		{
			row.classList.remove('danger');
		}
		if(row.classList.contains('success'))
		{
			row.classList.remove('success');
		}
	}
}	

















