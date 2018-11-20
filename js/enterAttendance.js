//jQuery for timepicker
$(document).ready(function(){
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
		timeIn(id);
	});
	$('input.timeout1').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});
	$('input.timein2').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeIn(id);
	});
	$('input.timeout2').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});
	$('input.timein3').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeIn(id);
	});
	$('input.timeout3').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});

	$('input[id^=workstatus-]').click(function () {
		
		// console.log("Button clicked.");
	    // if($(this).val() === 'Working' && !$(this).hasClass('saved')) {
	    if($(this).val() === 'Working') {
	    	console.log("Button Not Working");
	    	// console.log(!$(this).hasClass('saved'));
	    	// $(this).button('offwork'); // button text will be "No Work"
	    	// $(this).attr('class', 'btn btn-default');
	    	$(this).attr('value', 'No Work')
	    	$(this).attr('class', 'btn btn-default');

	    	// Call function to remove all inputs from row
	    	removeInputsFromRow(this);
	    }
	    // else if ($(this).hasClass('saved')) { 
	    // 	console.log("Button is SAVED");
	    // 	console.log($(this).button('offwork'));
    	// 	$(this).button('offwork'); // button text will be "Working"
    	// 	$(this).attr('class', 'btn btn-info');
	    // } 
	    else {
    		// Call function to set button to Working when any input is added
    		allowInputsFromRow(this);
	    }
	});

});

function allowInputsFromRow(row) {
	if(row.length <= 12 || row.length >= 7) // If it only has numbers add CSS selector
		var id = 'input[id^=workstatus-'+row+']';
	else
		var id = row;

	console.log("Allow inputs from row" + row);

	if($(id).val() === 'No Work' && !$(id).hasClass('saved')) {
		console.log("Button has Working");
		// $(id).button('offwork');
		// $(id).attr('class', 'btn btn-info');
		$(id).attr('value', 'Working')
	    $(id).attr('class', 'btn btn-info');
		// console.log(id);
		// console.log($(id).parent().parent().parent().find('.attendance').val(''));
		$(id).parent().parent().parent().find('.attendance').val('')
	} 
	// else if ($(id).hasClass('saved')) {
	// 	console.log("Button is SAVED");
	// 	$(id).button('reset');
	// 	$(id).attr('class', 'btn btn-default');
	// }
}
 
function removeInputsFromRow(row) {

	// Get empid without workstatus- prefix
	if(row.length <= 12 || row.length >= 7)
		var id = row;
	else
		var id = (row.id).substring(11);

	var mainRow = document.getElementById(id);

	mainRow.querySelector('.timein1').placeholder = "";
	mainRow.querySelector('.timeout1').placeholder = "";
	mainRow.querySelector('.timein2').placeholder = "";
	mainRow.querySelector('.timeout2').placeholder = "";
	mainRow.querySelector('.timein3').placeholder = "";
	mainRow.querySelector('.timeout3').placeholder = "";
	// delete values
	mainRow.querySelector('.timein1').value = "";
	mainRow.querySelector('.timeout1').value = "";
	mainRow.querySelector('.timein2').value = "";
	mainRow.querySelector('.timeout2').value = "";
	mainRow.querySelector('.timein3').value = "";
	mainRow.querySelector('.timeout3').value = "";
	mainRow.querySelector('.workinghours').value = "";
	mainRow.querySelector('.overtime').value = "";
	mainRow.querySelector('.undertime').value = "";
	mainRow.querySelector('.nightdiff').value = "";
	//for hidden rows
	mainRow.querySelector('.workinghoursH').value = "";
	mainRow.querySelector('.overtimeH').value = "";
	mainRow.querySelector('.undertimeH').value = "";
	mainRow.querySelector('.nightdiffH').value = "";

	mainRow.querySelector('.timein2').disabled = false; // unset the textbox to readonly
	mainRow.querySelector('.timeout2').disabled = false; // Unset the textbox to readonly
	mainRow.querySelector('.nightshiftChk').checked = false;// Uncheck ND checkbox

	mainRow.querySelector('.attendance').value = "NOWORK"; // Unset the attendance status to 
	// If absent was initially placed, changed to success
	if(mainRow.classList.contains('danger'))
	{
		mainRow.classList.remove('danger');
	}
	if (mainRow.classList.contains('success'))
	{
		mainRow.classList.remove('success');
	}
}



//Payroll
//jQuery for timepicker
function timeVerify() {
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
		timeIn(id);
	});
	$('input.timeout1').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});
	$('input.timein2').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeIn(id);
	});
	$('input.timeout2').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});
	$('input.timein3').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeIn(id);
	});
	$('input.timeout3').change(function(){
		var id = $(this).parent().parent().attr('id');
		timeOut(id);
	});

}
	
function halfDay(id){
	var mainRow = document.getElementById(id); // Get row to be computed
	if(mainRow.querySelector('.halfdayChk').checked == false) // enable checkbox)
	{
		mainRow.querySelector('.nightshiftChk').checked = false;// Uncheck ND checkbox
		
		mainRow.querySelector('.timein2').placeholder = "";
		mainRow.querySelector('.timeout2').placeholder = "";
		mainRow.querySelector('.timein3').placeholder = "";
		mainRow.querySelector('.timeout3').placeholder = "";
		// delete values
		mainRow.querySelector('.timein2').value = "";
		mainRow.querySelector('.timeout2').value = "";
		mainRow.querySelector('.timein3').value = "";
		mainRow.querySelector('.timeout3').value = "";
		mainRow.querySelector('.workinghours').value = "";
		mainRow.querySelector('.overtime').value = "";
		mainRow.querySelector('.undertime').value = "";
		mainRow.querySelector('.nightdiff').value = "";
		//for hidden rows
		mainRow.querySelector('.workinghoursH').value = "";
		mainRow.querySelector('.overtimeH').value = "";
		mainRow.querySelector('.undertimeH').value = "";
		mainRow.querySelector('.nightdiffH').value = "";

		mainRow.querySelector('.timein2').disabled = false; // unset the textbox to readonly
		mainRow.querySelector('.timeout2').disabled = false; // Unset the textbox to readonly
		mainRow.querySelector('.nightshiftChk').checked = false;// Uncheck ND checkbox

		mainRow.querySelector('.attendance').value = ""; // Unset the attendance status to 
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
		mainRow.querySelector('.nightshiftChk').checked = false;// Uncheck ND checkbox
		var timein1 = mainRow.querySelector('.timein1').value; // Get time in value
		var timeout1 = mainRow.querySelector('.timeout1').value; // Get time out value

		//Delete value
		mainRow.querySelector('.timein2').value = "";
		mainRow.querySelector('.timeout2').value = "";
		mainRow.querySelector('.timein3').value = "";
		mainRow.querySelector('.timeout3').value = "";
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
		mainRow.querySelector('.timein2').disabled = true; // Set the textbox to readonly
		mainRow.querySelector('.timein2').placeholder = "";
		mainRow.querySelector('.timeout2').disabled = true; // Set the textbox to readonly
		mainRow.querySelector('.timeout2').placeholder = "";
		mainRow.querySelector('.timein3').disabled = true; // Set the textbox to readonly
		mainRow.querySelector('.timein3').placeholder = "";
		mainRow.querySelector('.timeout3').disabled = true; // Set the textbox to readonly
		mainRow.querySelector('.timeout3').placeholder = "";
		computeTime(mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
	}
	
}
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
	allowInputsFromRow(id);
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
	
	if(mainRow.querySelector('.halfdayChk').checked == true)//eto
		halfDay(id);

	else if(timein3 !== '' && timeout3 !== '')//if there is value inside nightshift
		computeTimeNightshift( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2, timeinhour3, timeinmin3, timeouthour3, timeoutmin3);
	
	// Function call to compute for working hours, undertime and overtime
	else if(!nightShiftAuth && timein3 === '' && timeout3 === '')
		computeTime( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
}

function timeOut(id) {
	allowInputsFromRow(id);
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
	
	if(mainRow.querySelector('.halfdayChk').checked == true)//eto
		halfDay(id);

	else if(timein3 !== '' && timeout3 !== '')//if there is value inside nightshift
		computeTimeNightshift( mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2, timeinhour3, timeinmin3, timeouthour3, timeoutmin3);
	
	// Function call to compute for working hours, undertime and overtime
	else if(!nightShiftAuth && timein3 === '' && timeout3 === '')
		computeTime(mainRow, timeinhour1, timeinmin1, timeouthour1, timeoutmin1, timeinhour2, timeinmin2, timeouthour2, timeoutmin2);
}	

function halfDayCheckbox(id) {
	var mainRow = document.getElementById(id); // Get row to be computed
	mainRow.querySelector('.halfdayChk').disabled = false; // enable checkbox
}

//Submit the form
function save() {
	var a = confirm("Are you sure you want to save this attendance? All of the blank fields will remain empty.")
	if(a)
	{
		document.getElementById('form').submit();
	}
}


function remarksValidation(id) {
	var mainRow = document.getElementById(id);
	var remarks = mainRow.querySelector('.hiddenRemarks').value;

	var remarksCounter = remarks.length - 100;
	remarksCounter = Math.abs(remarksCounter);
	
	document.getElementById('remarksCounter').innerHTML = remarksCounter;

}

function remarksListener(val) {
	var remarkInput = 100 - val.length;

	if(remarkInput < 0)
		document.getElementById('saveRemarks').classList.add('disabletotally');
	else
		document.getElementById('saveRemarks').classList.remove('disabletotally');

	document.getElementById('remarksCounter').innerHTML = remarkInput;
}

function remarks(id) {	
	allowInputsFromRow(id);
	// show modal here to input for remarks
	var mainRow = document.getElementById(id);
	if(mainRow.querySelector('.hiddenRemarks').value != null)
	{

		var input = mainRow.querySelector('.hiddenRemarks').value;
		input = input.replace(/\\/g, '');
		document.getElementById('remark').value = input;
	}
	else
	{
		document.getElementById('remark').value = "";
	}
	var empName = mainRow.querySelector('.empName').innerHTML.trim();
	var modal = document.getElementById('dito').innerHTML = "Remarks for " + empName;
	document.getElementById('saveRemarks').setAttribute('onclick', "saveRemarks(\""+ id +"\")");
	
}


// Transfer content to hidden input field
function saveXAllow(id) {
	var mainRow = document.getElementById(id);
	var xAllow = document.getElementById('xAllowanceInput').value.trim();
	var hiddenXAllow = mainRow.querySelector('.hiddenXAllow').setAttribute('value', xAllow);

	var paragraph = document.createElement('span');
	paragraph.innerHTML = xAllow;
	paragraph.id = 'xAllowValue';

	if(xAllow !== null && xAllow !== "")
	{
		
		mainRow.querySelector('.xall-icon').classList.add('badge');
		if(mainRow.querySelector('#xAllowValue') !== null)
			mainRow.querySelector('.xall-icon').removeChild(mainRow.querySelector('#xAllowValue'));
		mainRow.querySelector('.xall-icon').appendChild(paragraph);
	}
	else
	{
		mainRow.querySelector('.xall-icon').classList.remove('badge');
		mainRow.querySelector('.xall-icon').removeChild(mainRow.querySelector('#xAllowValue'));
	}

}

function xAllowance(id) {	
	allowInputsFromRow(id);
	// show modal here to input for remarks
	var mainRow = document.getElementById(id);
	if(mainRow.querySelector(".hiddenXAllow").value != null)
	{
		var input = mainRow.querySelector(".hiddenXAllow").value;
		input = input.replace(/\\/g, '');
		document.getElementById("xAllowanceInput").value = input;
	}

	document.getElementById("saveXAllow").setAttribute("onclick", "saveXAllow('"+id+"')");
	if(mainRow.querySelector(".empName") !== null) {
		var empName = mainRow.querySelector(".empName").innerHTML.trim();
		var modal = document.getElementById("AllowDisplay").innerHTML = "Extra allowance for " + empName;
	}
	
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
		
		mainRow.querySelector('.remarks-icon').classList.add('glyphicon', 'glyphicon-edit');
		
		//alert("Time to add a badge here!");
		//alert(remarks);
			
	}
	else
	{
		//alert("Nothing to do!");
		mainRow.querySelector('.remarks-icon').classList.remove('glyphicon', 'glyphicon-edit');
	}

}

function absent(id) {
	allowInputsFromRow(id);
	removeInputsFromRow(id);
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
	mainRow.querySelector('.timein3').placeholder = "ABSENT";
	mainRow.querySelector('.timeout3').placeholder = "ABSENT";

	//Checkboxes
	if(mainRow.querySelector('.nightshiftChk').checked == true)//Nightdiff
		mainRow.querySelector('.nightshiftChk').checked = false;
	if(mainRow.querySelector('.halfdayChk').checked == true)//HalfDay
		mainRow.querySelector('.halfdayChk').checked = false;
	
	// delete values
	mainRow.querySelector('.timein1').value = "";
	mainRow.querySelector('.timeout1').value = "";
	mainRow.querySelector('.timein2').value = "";
	mainRow.querySelector('.timeout2').value = "";
	mainRow.querySelector('.timein3').value = "";
	mainRow.querySelector('.timeout3').value = "";
	mainRow.querySelector('.workinghours').value = "";
	mainRow.querySelector('.overtime').value = "";
	mainRow.querySelector('.undertime').value = "";

	mainRow.querySelector('.timein3').disabled = true;
	mainRow.querySelector('.timeout3').disabled = true;
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
	if(time) {
		var hour = time.split(":"); // Split hour + min + AM/PM
		var min = hour[1].split(" "); // Split min + AM/PM
		var diff; // Determine if AM/PM

		if(min[1] == "AM" && parseInt(hour[0],10) == 12) {
			hr = 24;
			return hr;
		}
		if(min[1] == "PM" && parseInt(hour[0],10) != 12) {
			diff = 12; // Add 12hrs if PM
			var hr = parseInt(hour[0],10) + diff;
			return hr;
		}
		else {
			var hr = parseInt(hour[0]);
			return hr;
		}

	}	
	else {
		return 0;
	}		
}

function getMin(time) {
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
	console.log("COMPUTE TIME");
	var originalMins;
	row.querySelector('.attendance').value = "";

	var isSunday = (row.querySelector('#isSunday') ? true : false); 
 	var compReq = (row.querySelector('#completeReq') ? true : false); 

 	// console.log("sunday : "+ isSunday)
 	console.dir(row)
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

		// If employee chooses to straight the shift hours
		// if(timeinhour1 >= timeouthour1)
		// {
		// 	var time
		// 	timeouthour1 = 12;
		// }
		// if(timeinhour2 >= timeouthour2)
		// 	timeouthour2 += 12;

		// console.log("timein: "+timeinhour1+"timeout: "+ timeouthour1);

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

			// if timeinhour is greater than the timeouthour
			if(timeinhour1 >= timeouthour1)
			{
				workinghours = Math.abs(workinghours) - 24;
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
		}
		

		// MORNING SHIFT
		if(workinghours >= 1)
		{
			console.log("Morning shift1");
			// Computing minutes
			//Before break
			//If employee chooses halfday

		
			if(timeinhour2 == "HD")
			{
				if(timeinmin1 > timeoutmin1)
				{
					var time_temp = 60 - timeinmin1;
					workingmins = time_temp + timeoutmin1;
				}
				if(timeoutmin1 > timeinmin1)
				{
					workingmins = timeoutmin1 - timeinmin1;
				}
				if(timeinmin1 == timeoutmin1)
				{
					workingmins = 0;
				}
				originalMins = workingmins;//minutes for night diff
			}
			else
			{
				if(timeinmin1 > timeoutmin1)
				{
					var time_temp = 60 - timeinmin1;
					workingmins1 = time_temp + timeoutmin1;
				}
				else if(timeoutmin1 > timeinmin1)
				{
					workingmins1 = timeoutmin1 - timeinmin1;
				}
				else if(timeinmin1 == timeoutmin1)
				{
					workingmins1 = 0;
				}
				//After break
				if(timeinmin2 > timeoutmin2)
				{
					var time_temp = 60 - timeinmin2;
					workingmins2 = time_temp + timeoutmin2;
				}
				if(timeoutmin2 > timeinmin2)
				{
					workingmins2 = timeoutmin2 - timeinmin2;
				}
				if(timeinmin2 == timeoutmin2)
				{
					workingmins2 = 0;
				}

				workingmins = Math.abs(workingmins1) + Math.abs(workingmins2);
				originalMins = workingmins;//minutes for night diff
			}
				

			


			//Undeclared break

			//1st Time in and Time out
			var checkTime1 = timeinhour1 - timeouthour1;
			var checkTime2 = timeinhour2 - timeouthour2;
			var useOnce = true;
			console.log("Time stamp: "+timeinhour1+" | "+timeouthour1);
			if(row.querySelector('.driver').value == '1' && isSunday)
			{
				console.log("driver");
				if(timeouthour1 >= 21)// If employee timeout at 9pm onward 
				{
					
					if(workingmins == 0)
					{
						// console.log("1");
						if(useOnce)
						{
							// console.log("2");
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						// console.log("3");
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						// console.log("4");
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				//2nd Time in and Time out
				// useOnce = true;
				workingmins = Math.abs(workingmins);
				if(timeouthour2 >= 21)// If employee timeout at 9pm onward 
				{
					// console.log("5");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							// console.log("6");
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else if(useOnce)
					{
						// console.log("7");
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}

			}
			else
			{
				console.log("hindi driver");
				if(timeouthour1 >= 21)// If employee timeout at 9pm onward 
				{
					console.log("9pm mark-1");
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				// This is to prevent it to deduct 30mins from employee on 12pm to 1pm straight
				var deduction1211 = true;
				if(timeinhour1 == 12 && timeinmin1 > 0)
					deduction1211 = false;
				if(timeinhour1 <= 12 && timeouthour1 >= 13 && deduction1211)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("12pm-1pm mark-1");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//2nd Time in and Time out
				useOnce = true;
				if(timeouthour2 >= 21)// If employee timeout at 9pm onward 
				{
					console.log("9pm mark-2");
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				// This is to prevent it to deduct 30mins from employee on 12pm to 1pm straight
				var deduction1212 = true;
				if(timeinhour2 == 12 && timeinmin2 > 0)
					deduction1212 = false;

				if(timeinhour2 <= 12 && timeouthour2 >= 13 && deduction1212)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("12pm-1pm mark-2");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
			}
				


			if(workingmins >= 60)
			{
				workinghours++;
				 workingmins = Math.abs(workingmins) - 60;
			}

			// console.log("time: "+workinghours);
			//alert(workinghours);
			//set the attendance status to PRESENT
			row.querySelector('.attendance').value = "PRESENT";
		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hrs";
					row.querySelector('.workinghoursH').value = workinghours + " hrs";
				}
				else if(workinghours == 0)
				{
					row.querySelector('.workinghours').value = workingmins + " mins";
					row.querySelector('.workinghoursH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";
					row.querySelector('.workinghoursH').value = workinghours + " hrs, " +  + workingmins + " mins";
				}
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
			if(!isSunday)
			{
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
				
				if(workinghours == 7)
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					var Uworkinghour = workinghours + 1;
					var Uworkingmins = 60 - workingmins;
					Uworkinghour = Math.abs(Uworkinghour - 8);
					row.querySelector('.undertime').value = Uworkinghour + " hrs, " + Uworkingmins + " mins";
					row.querySelector('.undertimeH').value = Uworkinghour + " hrs, " + Uworkingmins + " mins";
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
			if(compReq)// If Employee has complete requirements. Employees that have no requirements dont have nightdiff
			{
				if(!isSunday)// If today is Sunday
				{
					console.log("ND1");
					console.log(timeinhour1 +" : "+ timeouthour1+" | "+ timeinhour2+" : "+ timeouthour2)
					//If employee chooses halfday
					if(timeinhour2 == "HD")//Night diff
					{

						if ((timeinhour1 <= 22 && timeouthour1 <= 6) ||
					   		(timeinhour1 <= 22 && timeouthour1 <= 24) ||
					   		(timeouthour1 == 22 && timeoutmin1 != 0))
						{
							var nightdiffMins = originalMins;
							var nightdiffBool = false;
							if(timeouthour1 == 22 && timeoutmin1 != 0)// If employee's timeout time is 10:30pm 
							{
								nightdiffMins = timeoutmin1;
								nightdiffBool = true;
							}
							else if(timeinhour1 <= 24 && timeouthour1 >= 22)
							{
								nightdiff = timeouthour1 - 22;
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

						// if(nightdiffBool == false && nightdiff == "")
						// 	nightdiffBool = true;

						if(nightdiffBool && nightdiffMins != 0 && nightdiff == "")
						{
							row.querySelector('.nightdiff').value = nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiffMins + "mins";
						}
						else if(nightdiff != "")
						{
							if(nightdiffMins != 0)
							{
								row.querySelector('.nightdiff').value = nightdiff + " hrs, " + nightdiffMins + "mins";
								row.querySelector('.nightdiffH').value = nightdiff + " hrs, " + nightdiffMins + "mins";
							}
							else
							{
								row.querySelector('.nightdiff').value = nightdiff + " hrs";
								row.querySelector('.nightdiffH').value = nightdiff + " hrs";
							}
							
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
					   (timeinhour1 <= 22 && timeouthour1 >= 22) ||
					   (timeinhour2 <= 22 && timeouthour2 <= 6) ||

					   (timeinhour2 <= 6 && timeouthour2 >= 6) || // If timeinhour2 is 1pm onwards but notgreater than 6pm

					   (timeinhour2 <= 22 && timeouthour2 >= 22) ||
					   (timeouthour1 == 22 && timeoutmin1 != 0) ||
					   (timeouthour2 == 22 && timeoutmin2 != 0))//night diff 
					{
						console.log(timeinhour1 +" : "+ timeouthour1+" | "+ timeinhour2+" : "+ timeouthour2)
						var nightdiffBool = false;
						var nightdiffMins = originalMins;
						if(timeouthour1 == 22 && timeoutmin1 != 0)
						{
							nightdiffMins = timeoutmin1;
							nightdiffBool = true;
						}
						else if(timeouthour2 == 22 && timeoutmin2 != 0) 
						{

							nightdiffMins = timeoutmin2;
							nightdiffBool = true;
						}
					//posibility 1
						if(timeouthour2 <= 24 && timeouthour2 >= 22)
						{
							nightdiff = timeouthour2 - 22;
						}
					// possibility 2 -- When after break time is past 12AM (and started past 10PM)
						else if(timeouthour2 >= 24 && timeouthour2 <= 6 && (timeinhour1 <= 22 || timeinhour1 >= 22 || timeinhour2 <= 22 || timeouthour2 >= 22))
						{
							nightdiff = timeouthour1 - 22 + (timeouthour2 - timeinhour2);

							// To retain night differential value, must retain computation based on boundary time
							if(timeouthour2 > 6)
							{

								nightdiff = timeouthour1 - 22 + (6 - timeinhour2);
							}

						}
						else if (timeinhour1 <= 22 && timeouthour1 >= 22)
						{
							nightdiff = timeouthour1 - 22;

							if(timeouthour2 > 6)
							{
								temp = (timeinhour2 - 6);
								nightdiff += Math.abs(temp); 
							}
							else if(timeouthour2 <= 6)
							{
								temp = timeinhour2 - timeouthour2;
								nightdiff += Math.abs(temp); 
							}

						}
						else if(timeinhour2 <= 6 && timeouthour2 >= 6)
						{
							nightdiff = timeinhour2 - 6;
						}
						else
						{
							nightdiff = "";
						}
						if(Number.isInteger(nightdiff))
						{
						   	nightdiff = Math.abs(nightdiff);		
						}
						// deduct nightdiff if timeout minutes is greater than timein minutes
						if(nightdiff != "")
						{
							if(timeinmin1 > timeoutmin1)
							{
								nightdiff--;
							}
							if(timeinmin2 > timeoutmin2)
							{
								nightdiff--;
							}
						}
						// if(nightdiffBool == false && nightdiff == "")
						// 	nightdiffBool = true;

						if(nightdiffBool && nightdiffMins != 0 && nightdiff == "")
						{
							row.querySelector('.nightdiff').value = nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiffMins + "mins";
						}
						else if(nightdiff != "")
						{
							
							if(nightdiffMins != 0)
							{
								row.querySelector('.nightdiff').value = nightdiff + " hrs, " + nightdiffMins + "mins";
								row.querySelector('.nightdiffH').value = nightdiff + " hrs, " + nightdiffMins + "mins";
							}
							else
							{
								row.querySelector('.nightdiff').value = nightdiff + " hrs";
								row.querySelector('.nightdiffH').value = nightdiff + " hrs";
							}
						}
						else
						{
							
							row.querySelector('.nightdiff').value = "";
							row.querySelector('.nightdiffH').value = "";
						}

					}
				}
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
			console.log("Nightshift3");
		// Night differential starts at 10pm - 6am

			//Invert the time to make the computation the same as the morning shift
			// timeinhour1 -= 12;
			// timeouthour1 += 12;
			// timeinhour2 += 12;
			// timeouthour2 += 12;
			// console.log("timein: "+timeinhour1 + " | timeout: " + timeouthour1);
			if(timeinhour1 < 12)
				timeinhour1 +=12;
			else
				timeinhour1 -=12;
			if(timeouthour1 < 12)
				timeouthour1 +=12;
			else
				timeouthour1 -=12;
			//If employee chooses halfday
			if(timeinhour2 != "HD")//Night diff
			{
				if(timeinhour2 < 12)
				timeinhour2 +=12;
				else
					timeinhour2 -=12;
				if(timeouthour2 < 12)
					timeouthour2 +=12;
				else
					timeouthour2 -=12;
			}
			console.log("timein: "+timeinhour1 + " | timeout: " + timeouthour1);
			
			
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

			// if timeinhour is greater than the timeouthour
			if(timeinhour1 >= timeouthour1)
			{
				workinghours1 = Math.abs(workinghours1) - 24;
			}
			// if timeinhour is greater than the timeouthour
			if(timeinhour2 >= timeouthour2)
			{
				workinghours2 = Math.abs(workinghours2) - 24;
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
				var time_temp = 60 - timeinmin1;
				workingmins1 = time_temp + timeoutmin1;
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
					var time_temp = 60 - timeinmin2;
					workingmins2 = time_temp + timeoutmin2;
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
				originalMins = workingmins;//minutes for night diff
			}
			else
			{
				workingmins = workingmins1;
				originalMins = workingmins;//minutes for night diff
			}

			

			//Undeclared break
			var checkTime1 = null;
			var checkTime2 = null;

			//1st Time in and Time out
			if(timeinhour1 >= timeouthour1)
			{
				var tempTime = 24 - timeinhour1;
				checkTime1 = tempTime + timeouthour1;
			}
			else
			{
				checkTime1 = timeinhour1 - timeouthour1;
			}
			if(timeinhour2 != "HD")
			{
				if(timeinhour2 >= timeouthour2)
				{
					var tempTime = 24 - timeinhour1;
					checkTime2 = tempTime + timeouthour1;
					
				}
				else
				{
					checkTime2 = timeinhour2 - timeouthour2;
				}
			}
			
			//Undeclared break
			var timeinEx1 = null;
			var timeoutEx1 = null;
			var timeinEx2 = null;
			var timeoutEx2 = null;

			if(timeinhour1 >= timeouthour1)
			{
				timeinEx1 = timeouthour1;
				timeoutEx1 = timeinhour1;
			}
			if(timeinhour2 >= timeouthour2)
			{
				timeinEx2 = timeouthour2;
				timeoutEx2 = timeinhour2;
			}

			//1st Time in and Time out
			var checkTime1 = workinghours1;
			var checkTime2 = workinghours2;
			var useOnce = true;

			console.log("checkTime1: "+timeinhour1+" | "+timeouthour1);
			
			if(row.querySelector('.driver').value == '1' && isSunday)
			{
				// console.log("1: "+useOnce);
				// console.log("checkTime1: "+checkTime1);
				if((timeinhour1 >= timeouthour1 && timeinhour1 >= 9) || (timeouthour1 <= timeinhour1 && timeouthour1 <= 9))// if employee timed out at 9pm onward
				{
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				//2nd Time in and Time out
				// useOnce = true;
				workingmins = Math.abs(workingmins);
				if((timeinhour1 >= timeouthour1 && timeinhour1 >= 9) || (timeouthour1 <= timeinhour1 && timeouthour1 <= 9))// if employee timed out at 9pm onward
				{

					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else if(useOnce)
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
			}
			else
			{
				//if( (timeinhour1 <= 9 && timeouthour1 >= 9) && !(timeinEx1 < 9 && timeoutEx1 > 9) )// if employee timed out at 9pm onward
				if((timeinhour1 >= timeouthour1 && timeinhour1 >= 9) || (timeouthour1 <= timeinhour1 && timeouthour1 <= 9))// if employee timed out at 9pm onward
				{
					console.log("yow");
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				// if(timeinhour1 <= 24 && timeouthour1 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				if((timeinhour1 >= timeouthour1) && timeouthour1 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("yow1");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//2nd Time in and Time out
				useOnce = true;
				if((timeinhour2 >= timeouthour2 && timeinhour2 >= 9) || (timeouthour2 <= timeinhour2 && timeouthour2 <= 9))// if employee timed out at 9pm onward
				{
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);
				if((timeinhour2 >= timeouthour2) && timeouthour2 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
			}

			if(workingmins >= 60)
			{
				workinghours++;
				 workingmins = Math.abs(workingmins) - 60;
			}
			
			
			row.querySelector('.attendance').value = "PRESENT";
		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hrs";
					row.querySelector('.workinghoursH').value = workinghours + " hrs";
				}
				else if(workinghours == 0)
				{
					row.querySelector('.workinghours').value = workingmins + " mins";
					row.querySelector('.workinghoursH').value = workingmins + " mins";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";
					row.querySelector('.workinghoursH').value = workinghours + " hrs, " +  workingmins + " mins";
				}
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
			if(!isSunday)
			{
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
			}
			

		// UNDERTIME if Working Hours don't reach 8
			if(workinghours < 8 && workingmins == 0)
			{
				row.querySelector('.undertime').value = Math.abs(workinghours - 8) + " hrs";
				row.querySelector('.undertimeH').value = Math.abs(workinghours - 8) + " hrs";

			}
			else if(workinghours <= 8 && workingmins != 0)
			{
				if(workinghours == 8)
				{
					row.querySelector('.undertime').value = workingmins + " mins";
					row.querySelector('.undertimeH').value = workingmins + " mins";
				}
				else
				{
					// console.log("yes: "+workinghours);
					var Uworkinghour = workinghours + 1;
					var Uworkingmins = 60 - workingmins;
					if(Uworkinghour != 8)
					{
						row.querySelector('.undertime').value = Math.abs(8 - Uworkinghour) + " hrs, " + Uworkingmins + " mins";
						row.querySelector('.undertimeH').value = Math.abs(8 - Uworkinghour) + " hrs, " + Uworkingmins + " mins";	
					}
					else
					{
						row.querySelector('.undertime').value = Uworkingmins + " mins";
						row.querySelector('.undertimeH').value = Uworkingmins + " mins";	
					}
					
				}
			}
			else
			{
				row.querySelector('.undertime').value = "";
				row.querySelector('.undertimeH').value = "";
			}
		
		// NIGHT DIFF if Working Hours is in between 10pm - 6am
		// 10 is 10pm and 18 is 6pm
			var nightdiff = "";
			if(compReq)// If Employee has complete requirements. Employees that have no requirements dont have nightdiff
			{
				if(!isSunday)
				{
					//If employee chooses halfday
					if(timeinhour2 != "HD")
					{
						console.log("ywwww");
						console.log("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1+"// timeinhour2: "+ timeinhour2+"// timeouthour2: "+ timeouthour2);
						if(	(timeinhour1 <= 10 && timeouthour1 <= 18) || 
							(timeinhour2 <= 10 && timeouthour2 <= 18) || 
							(timeouthour1 == 10 && timeoutmin1 != 0) ||
							(timeouthour2 == 10 && timeoutmin2 != 0) ||//night diff needs reconfiguration
							(timeinhour2 >= 10 && timeouthour2 <= 18))/// If timeinhour3  is 1am onwards but not greater than 6 
						{
							var NDin;
							var NDout;
							var workhrs;
							var nightdiffMins = 0;

							var nightdiffBool = false;
							// console.log("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1+"// timeinhour2: "+ timeinhour2+"// timeouthour2: "+ timeouthour2);
							
							// If employee only exceeded the nightdiff by minutes
							if(timeouthour1 == 10 && timeoutmin1 != 0)
							{
								nightdiffMins = timeoutmin1;
								nightdiffBool = true;
							}
							else if(timeouthour1 == 10 && timeoutmin2 != 0)
							{
								nightdiffMins = timeoutmin2;
								nightdiffBool = true;
							}
							//Possibility 2: if 10pm is in after lunch
							// console.log("timeinhour2: "+timeinhour2+" | timeinhour1: "+timeinhour1);
							if(timeinhour2 <= 10)
							{ 
								// console.log("1");
								nightdiffBool = false;
								NDin = timeinhour2 - 10;
								NDout = 0;
								if(timeouthour2 >= 18)
								{
									NDout = timeouthour2 - 18;
								}
								workhrs = timeinhour2 - timeouthour2;
								nightdiff = Math.abs(NDin) - Math.abs(workhrs);
								nightdiff += NDout;
							}
							//Possibility 1: if 10pm is in before lunch
							else if(timeinhour1 <= 10)
							{
								// console.log("2");
								nightdiffBool = false;
								if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
								{

									NDin = timeinhour1 - 10;
									NDout = timeouthour1 - 18;
									workhrs = timeinhour1 - timeouthour1;
									nightdiff = (Math.abs(NDin) + Math.abs(NDout1)) - Math.abs(workhrs);
								}
								else//the normal night diff
								{

									workhrs1 = timeinhour1 - timeouthour1;
									NDin1 = timeinhour1 - 10;
									nightdiff1 = Math.abs(NDin1) - Math.abs(workhrs1);
									
									NDin2 = timeinhour2 - 10;
									if(timeouthour2 <=18)
									{
										var temp = 0;
										if(timeouthour1 > 10)
											temp = timeouthour1 - 10;
										NDout2 = timeouthour2 - timeinhour2;
										NDout2 = Math.abs(NDout2) + temp;
									}
									else
									{
										NDout2 = 8;
									}
									if(timeouthour2 <=18)
										nightdiff = Math.abs(NDout2);
									else
									{
										nightdiff2 = Math.abs(NDin2) - Math.abs(NDout2);
										nightdiff = nightdiff1 + nightdiff2;
										nightdiff = Math.abs(nightdiff);
									}
									
								}
								
							}
							else if(timeinhour2 >= 10 && timeouthour2 <= 18)
							{

								nightdiff = timeinhour2 - timeouthour2;
							}
								
							
							if(Number.isInteger(nightdiff))
							{
							   	nightdiff = Math.abs(nightdiff);		
							}
						}
					}
					else// halfday/straight
					{
						var nightdiffBool = false;
						var nightdiffMins = null;

						//checking for overlap time
						var timePeriodIn = null;

						if(timeinhour1 >= 13 && timeinhour1 <= 24)
							timeInPeriod = "pm";
						else if(timeinhour1 >= 0 && timeinhour1 <= 12)
							timeInPeriod = "am";
						if(timeouthour1 >= 13 && timeouthour1 <= 24)
							timeOutPeriod = "pm";
						else if(timeouthour1 >= 0 && timeouthour1 <= 12)
							timeOutPeriod = "am";

						// var overlapCheck = timeinhour1 + 13;
						// var overlapRange = overlapCheck;
						// if(overlapCheck > 24)
						// 	overlapRange = overlapCheck - 24;

						// console.log("ND: timeinhour1: "+ timeinhour1+"// timeouthour1: "+ timeouthour1);
						if(	(timeinhour1 <= 10 && timeouthour1 <= 18) || 
							(timeinhour1 <= 10 && timeouthour1 >= 18) || 
							(timeinhour1 >= 10 && timeinhour1 <= 18 && timeouthour1 >= 10 && timeouthour1 <= 18) ||
							(timeinhour1 >= timeouthour1 && timeInPeriod == timeOutPeriod))//night diff needs reconfiguration
						{
							// console.log("ND")
							var NDin;
							var NDout;
							var workhrs;
							var temp = null;
							
							if(timeinhour1 >= timeouthour1)
								temp = timeinhour1 - 12;

							// If employee only exceeded the nightdiff by minutes
							if(timeouthour1 == 10 && timeoutmin1 != 0)
							{
								nightdiffMins = timeoutmin1;
								nightdiffBool = true;
							}
							//timein is before 10pm
							if(timeinhour1 <= 10 && temp)
							{ 
								// console.log("pasok");
								//Possibility 2 : timein and timeout covers the whole nightdiff
								if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
								{
									// console.log("possibility 2");
									nightdiff = 8;
								}
								//Posibility 1: time out is inside the nightdiff
								else if(timeouthour1 <= 18 && timeouthour1 >= 10)
								{
									// console.log("possibility 1");
									nightdiff = 10 - timeouthour1;
								}
								//Possibility 3: timein hour is before 10pm but employee timed out at more than 12hours 
								else if(timeouthour1 <= timeinhour1 && temp)
								{
									// console.log("possibility 3");
									nightdiff = 8;
								}
							}
							// Possibility 4: timein hour is after 6am but employee timed out at more than 12hours and managed to time out inside the nightdiff hours
							else if(timeinhour1 >= 18 && timeouthour1 <= 18 && timeouthour1 >= 10)
							{
								// console.log("possibility 4");
								nightdiff = 10 - timeouthour1;
							}
							// Possibility 5: timein hour is after 6am and employee timed out at more than 12hours and also timed out after 6am but not greater than 6am
							else if(timeinhour1 >= 18 && timeinhour1 >= timeouthour1 && timeouthour1 >= 18) 
							{
								// console.log("possibility 5");
								nightdiff = 8;
							}
							// Possibility 6: Timein hour is inside the nightdiff range but employee timed out more than 12 hours and is also inside the nightdiff range
							else if(timeinhour1 <= 18 && 
									timeinhour1 >= 10 && 
									timeinhour1 >= timeouthour1 && 
									timeouthour1 >= 10 && 
									timeouthour1 <= 18)
							{
								// console.log("possibility 6");
								nightdiff = timeouthour1 - 10;
							}
							else if(timeinhour1 <= 10)
							{ 
								// console.log("pasok");
								//Possibility 2 : timein and timeout covers the whole nightdiff
								if(timeouthour1 >= 18)//If timein encapsulated all of the night diff
								{
									// console.log("possibility 2.1");
									nightdiff = 8;
								}
								//Posibility 1: time out is inside the nightdiff
								else if(timeouthour1 <= 18 && timeouthour1 >= 10)
								{
									// console.log("possibility 1.1");
									nightdiff = 10 - timeouthour1;
								}
								//Possibility 3: timein hour is before 10pm but employee timed out at more than 12hours 
								else if(timeouthour1 <= timeinhour1 && temp)
								{
									// console.log("possibility 3.1");
									nightdiff = 8;
								}
							}
							// If time in hour and time out hour is inside the nightdiff
							else if(timeinhour1 >= 10 && timeinhour1 <= 18 && timeouthour1 >= 10 && timeouthour1 <= 18)
							{
								nightdiff = timeinhour1 - timeouthour1;
							}

							// if(originalMins != null)
							// {
							// 	// console.log("nightdiff mins");
							// 	nightdiffMins = originalMins;
							// 	nightdiffBool = true;
							// }
						}
						if(Number.isInteger(nightdiff))
						{
						   	nightdiff = Math.abs(nightdiff);		
						}
					}

					// deduct nightdiff if timeout minutes is greater than timein minutes
					if(nightdiff != "")
					{
						if(timeinmin1 > timeoutmin1)
						{
							nightdiff--;
						}
						if(timeinmin2 > timeoutmin2)
						{
							nightdiff--;
						}
					}
					// console.log("nightdiffBool: "+ nightdiffBool);
					// if(nightdiffBool == false && nightdiff == "")
					// 	nightdiffBool = true;
					
					//Nightdiff mins
					nightdiffMins = 0;

					if(	(timeinhour1 <= 10 && timeouthour1 <= 18 && timeoutmin1 >= 0) || 
						(timeouthour1 == 10 && timeoutmin1 != 0 && timeoutmin1 >= 0))
					{
						console.log("tim1");
						nightdiffMins += timeoutmin1;
						nightdiffBool = true;
					}
					else if(timeinhour1 == timeouthour1 && timeoutmin1 >= 0)
					{
						nightdiffMins += timeoutmin1;
						nightdiffBool = true;
					}

					if(	(timeinhour2 <= 10 && timeouthour2 <= 18 && timeoutmin2 >= 0) || 
						(timeouthour2 == 10 && timeoutmin2 != 0 && timeoutmin2 >= 0) ||//night diff needs reconfiguration
						(timeinhour2 >= 10 && timeouthour2 <= 18 && timeoutmin2 >= 0))
					{
						console.log("tim2");
						nightdiffMins += workingmins2;
						nightdiffBool = true;
					}

					if(nightdiffMins >= 60)
					{
						nightdiff++; //increment nightdiff
						nightdiffMins -= 60;
					}

					if(nightdiffBool && nightdiffMins != 0 && nightdiff == "")
					{
							row.querySelector('.nightdiff').value = nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiffMins + "mins";
					}
					else if(nightdiff != "")
					{
						if(nightdiffMins != 0)
						{
							row.querySelector('.nightdiff').value = nightdiff + " hrs, " + nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs, " + nightdiffMins + "mins";
						}
						else
						{
							row.querySelector('.nightdiff').value = nightdiff + " hrs";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs";
						}
					}
					else
					{
						
						row.querySelector('.nightdiff').value = "";
						row.querySelector('.nightdiffH').value = "";
					}
				}
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
			row.querySelector('.timein2').placeholder = "";
			row.querySelector('.timeout2').placeholder = "";
			row.querySelector('.timein3').placeholder = "";
			row.querySelector('.timeout3').placeholder = "";
		}
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
	console.log("COMPUTE TIME NIGHT SHIFT1");
	var originalMins;
	row.querySelector('.attendance').value = "";

	var isSunday = (row.querySelector('#isSunday') ? true : false); 
	var compReq = (row.querySelector('#completeReq') ? true : false); 

	// Verifies that time in and time out input fields have value
	if(timeinhour1 && timeouthour1 && timeinhour2 && timeouthour2 && timeinhour3 && timeouthour3)
	{
		if(row.querySelector('.empName'))
		{
			var employeeName = row.querySelector('.empName').innerHTML.trim();
			if(timeinhour1 < timeouthour3 && timeinhour1 < 12 && timeouthour3 < 12)
			{
				alert("You have exceeded the 24hour mark for "+employeeName+"'s attendance.");
			}
			else if(timeinhour1 < timeouthour3 && timeinhour1 < 24 && timeouthour3 < 24 && timeinhour1 > 12 && timeouthour3 > 12)
			{
				alert("You have exceeded the 24hour mark for "+employeeName+"'s attendance.");
			}
		}
			

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
		
		

		// MORNING SHIFT
		if(workinghours >= 1)
		{
		// Computing minutes
		
			//Before break
			if(timeinmin1 > timeoutmin1)
			{
				var time_temp = 60 - timeinmin1;
				workingmins1 = time_temp + timeoutmin1;
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
				var time_temp = 60 - timeinmin2;
				workingmins2 = time_temp - timeoutmin2;
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
				var time_temp = 60 - timeinmin3;
				workingmins3 = time_temp + timeoutmin3;
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
			originalMins = workingmins;//minutes for night diff
			//Undeclared break

			//1st Time in and Time out
			var checkTime1 = timeinhour1 - timeouthour1;
			var checkTime2 = timeinhour2 - timeouthour2;
			var checkTime3 = timeinhour3 - timeouthour3;

			var useOnce = true;

			//if employee is a driver or pahinante
			if(row.querySelector('.driver').value == 1 && isSunday)
			{
				console.log("driver");
				// This is to prevent it to deduct 30mins from employee on 12pm to 1pm straight
				var deduction1211 = true;

				if(timeinhour1 == 12 && timeinmin1 > 0)
					deduction1211 = false;

				if(timeinhour1 <= 12 && timeouthour1 >= 13 && deduction1211)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("12-1 deduct2");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}

				// This is to prevent it to deduct 30mins from employee on 12pm to 1pm straight
				var deduction1212 = true;
				if(timeinhour2 == 12 && timeinmin2 > 0)
					deduction1212 = false;
				//2nd Time in and Time out
				if(timeinhour2 <= 12 && timeouthour2 >= 13 && deduction1212)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("12-1 deduct");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//3nd Time in  and Time out
				workingmins = Math.abs(workingmins);
				var deduction1213 = true;
				if(timeinhour1 == 12 && timeinmin1 > 0)
					deduction1213 = false;
				if(timeinhour3 <= 12 && timeouthour3 >= 13 && deduction1213)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					console.log("12-1 deduct3");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
			}
			else
			{
				console.log("hindi driver");
				if(timeouthour1 >= 21)// If employee timeout at 9pm onward 
				// if(Math.abs(checkTime1) >= 13)// if accumulated time is more than 13 hours 
				{
					console.log("9 deduct");
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				var deduction1211 = true;
				if(timeinhour1 == 12 && timeinmin1 > 0)
					deduction1211 = false;
				if(timeinhour1 <= 12 && timeouthour1 >= 13 && deduction1211)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				// if(Math.abs(checkTime1) >= 8)// if accumulated time is more than 8 hours 
				{
					console.log("12-1 deduct1");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//2nd Time in and Time out
				useOnce = true;
				if(timeouthour2 >= 21)// If employee timeout at 9pm onward 
				{
					console.log("9 deduct1");
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				// This is to prevent it to deduct 30mins from employee on 12pm to 1pm straight
				var deduction1212 = true;
				if(timeinhour2 == 12 && timeinmin2 > 0)
					deduction1212 = false;
				if(timeinhour2 <= 12 && timeouthour2 >= 13 && deduction1212)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				// if(Math.abs(checkTime2) >= 8)// if accumulated time is more than 8 hours 
				{
					console.log("12-1 deduct4");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}

				//3nd Time in and Time out
				useOnce = true;
				// if(timeouthour3 >= 21)// If employee timeout at 9pm onward 
				// {
				// 	console.log("9 deduct5");
				// 	if(workingmins == 0)// if no minutes are rendered
				// 	{
				// 		useOnce = false;
				// 		workinghours -= 1;
				// 		workingmins = 30;//minus 30mins
				// 	}
				// 	else // if there are minutes entered
				// 	{
				// 		workingmins = workingmins - 30;//minus 30mins
				// 	}
				// 	// if workingmins is negative it should get the 
				// 	if(workingmins < 0)
				// 	{
				// 		workinghours -= 1;
				// 		useOnce = false;
				// 		if(workingmins == 0)
				// 			workingmins = Math.abs(workingmins);
				// 		else
				// 			workingmins = 60 - Math.abs(workingmins);
				// 	}
				// }
				// workingmins = Math.abs(workingmins);
				var deduction1213 = true;

				if(timeinhour3 == 12 && timeinmin3 > 0)
					deduction1213 = false;

				if(timeinhour3 <= 12 && timeouthour3 >= 13 && deduction1213)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				// if(Math.abs(checkTime3) >= 8)// if accumulated time is more than 8 hours 
				{
					console.log("12-1 deduct5");
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
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
				if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hrs";
					row.querySelector('.workinghoursH').value = workinghours + " hrs";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";
					row.querySelector('.workinghoursH').value = workinghours + " hrs, " +  + workingmins + " mins";
				}
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

			if(!isSunday)
			{
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
					var Uworkinghour = workinghours + 1;
					var Uworkingmins = 60 - workingmins;
					Uworkinghour = Math.abs(Uworkinghour - 8);
					row.querySelector('.undertime').value = Uworkinghour + " hrs, " + Uworkingmins + " mins";
					row.querySelector('.undertimeH').value = Uworkinghour + " hrs, " + Uworkingmins + " mins";
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

			var nightdiffMins = 0;
			if(compReq)// If Employee has complete requirements. Employees that have no requirements dont have nightdiff
			{
				if(!isSunday)
				{
					console.log("1) timein1: "+timeinhour1+" / timeout1: "+timeouthour1+" | timein2: "+timeinhour2+" / timeout2: "+timeouthour2+" | timein3: "+timeinhour3+" / timeout3: "+timeouthour3);
					if ((timeinhour1 <= 22 && timeouthour1 >= 22) ||// pos1 ~ 6
						(timeinhour1 <= 22 && timeouthour1 <= 6) ||// pos7 ~ 8
						(timeinhour2 <= 22 && timeouthour2 >= 22) ||// pos9 ~ 10
						(timeinhour2 <= 22 && timeouthour2 <= 6) ||// pos 11 ~ 12
						(timeinhour3 <= 22 && timeouthour3 >= 22) ||// pos 13
						(timeinhour3 <= 22 && timeouthour3 <= 6) ||

						(timeinhour2 <= 6 && timeouthour2 >= 6) || /// If timeinhour3  is 1am onwards but not greater than 6 
						(timeinhour3 <= 6 && timeouthour3 >= 6) || /// If timeinhour3  is 1am onwards but not greater than 6 

						(timeouthour1 == 22 && timeoutmin1 != 0) ||
						(timeouthour2 == 22 && timeoutmin2 != 0) ||
						(timeouthour3 == 22 && timeoutmin3 != 0))// pos 14
					{
						console.log("Nightdiff3");
						var nightdiffBool = false;

						// Nightdiff mins
						if(((timeinhour1 <= 22 && timeouthour1 >= 22) ||
							(timeinhour1 <= 22 && timeouthour1 <= 6) ||
							(timeouthour1 == 22 && timeoutmin1 != 0)) && workingmins1 != 0)
						{
							console.log("1");
							if(	(timeinhour1 <= 22 && timeouthour1 >= 22 && timeoutmin1 >= 0) || 
								(timeinhour1 <= 22 && timeouthour1 <= 6 && timeoutmin1 >= 0))
							{
								console.log("1.1");
								nightdiffMins += timeoutmin1;
							}
							else
							{
								console.log("1.2");
								nightdiffMins += workingmins1;
							}
							nightdiffBool = true;
						}
						if(((timeinhour2 <= 22 && timeouthour2 >= 22) ||
								(timeinhour2 <= 22 && timeouthour2 <= 6) ||
								(timeouthour2 == 22 && timeoutmin2 != 0) ||
								(timeinhour2 <= 6 && timeouthour2 >= 6)) && workingmins2 != 0)
						{
							console.log("2");
							if(	(timeinhour2 <= 22 && timeouthour2 >= 22 && timeoutmin2 >= 0) || 
								(timeinhour2 <= 22 && timeouthour2 <= 6 && timeoutmin2 >= 0))
							{
								console.log("2.1");
								nightdiffMins += timeoutmin2;
							}
							else
							{
								console.log("2.2");
								nightdiffMins += workingmins2;
							}
							nightdiffBool = true;
						}
						if(((timeinhour3 <= 22 && timeouthour3 >= 22) ||
								(timeinhour3 <= 22 && timeouthour3 <= 6) ||
								(timeouthour3 == 22 && timeoutmin3 != 0) ||
								(timeinhour3 <= 6 && timeouthour3 >= 6)) && workingmins3 != 0)
						{
							console.log("3");//dow
							console.log(timeoutmin3);//dow
							if(	(timeinhour3 <= 22 && timeouthour3 >= 22 && timeoutmin3 >= 0) || 
								(timeinhour3 <= 22 && timeouthour3 <= 6 && timeoutmin3 >= 0))
							{
								console.log("3.1");
								nightdiffMins += timeoutmin3;
							}
							else
							{
								console.log("3.2");
								nightdiffMins += workingmins3;
							}
							nightdiffBool = true;
						}


						if(timeinhour1 <= 22 && timeouthour1 >= 22)// pos1 ~ 6
						{
							if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 >= 1 && timeouthour3 <= 6))// pos 1
							{
								console.log("4");
								time1 = timeouthour1 - 22;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 2
							{
								console.log("5");
								time1 = timeouthour1 - 22;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - 6;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 22 && timeouthour2 <= 6) && (timeinhour3 >= 1 && timeouthour3 <= 6))// pos 3
							{
								console.log("6");
								time1 = timeouthour1 - 22;

								var time2n1 = timeinhour2 - 24;
								time2 = Math.abs(time2n1) + timeouthour2;

								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 22 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 4
							{
								console.log("7");
								time1 = timeouthour1 - 22;

								var time2n1 = timeinhour2 - 24;
								time2 = Math.abs(time2n1) + timeouthour2;

								time3 = timeinhour3 - 6;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 1 && timeouthour2 <= 6) && timeinhour3 >= 6)// pos 5
							{
								console.log("8");
								time1 = timeouthour1 - 22;
								time2 = timeinhour2 - timeouthour2;
								nightdiff = Math.abs(time1) + Math.abs(time2);
							}
							else// pos 6 -- 1st time is the only one inside nightdiff time
							{
								console.log("9");
								time1 = timeouthour1 - 22;
								nightdiff = Math.abs(time1);
							}

						}
						else if(timeinhour1 <= 22 && timeouthour1 <= 6)// pos7 ~ 8
						{
							if((timeinhour2 >= 1 && timeouthour2 <= 6) && timeinhour3 > 6)// pos 7
							{
								console.log("10");
								var time1n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
								time1 = Math.abs(time1n1) + timeouthour1;

								time2 = timeinhour2 - timeouthour2;
								nightdiff = Math.abs(time1) + Math.abs(time2);
							}
							else if((timeinhour2 >= 1 && timeouthour2 <= 6) && (timeinhour3 <= 6 && timeouthour3 >= 6))// pos 8
							{
								console.log("11");
								var time1n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
								time1 = Math.abs(time1n1) + timeouthour1;

								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - 6;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
						}
						else if(timeinhour2 <= 22 && timeouthour2 >= 22)// pos9 ~ 10
						{
							if(timeinhour3 >= 1 && timeouthour3 <= 6) // pos 9
							{
								console.log("12");
								time2 = timeouthour2 - 22;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
							else // pos 10
							{
								console.log("13");
								time2 = timeouthour2 - 22;
								nightdiff = Math.abs(time2);
							}
						}
						else if(timeinhour2 <= 22 && timeouthour2 <= 6)// pos 11 ~ 12
						{
							if(timeinhour3 <= 6 && timeouthour3 >= 6)// pos 11
							{
								console.log("14");
								var time2n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
								time2 = Math.abs(time2n1) + timeouthour2;;

								time3 = timeinhour3 - 6;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
							else if(timeinhour3 >= 1 && timeouthour3 <= 6)// pos 12
							{
								console.log("15");
								var time2n1 = 24 - 22; //because in this possibility the time in is before 10 and will last until the next day.
								time2 = Math.abs(time2n1) + timeouthour2;;

								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
						}
						else if(timeinhour2 <= 6 && timeouthour2 >= 6)// If timeinhour3  is 1am onwards but not greater than 6 
						{
							console.log("16");
							nightdiff = timeinhour2 - 6;
						}
						else if(timeinhour3 <= 6 && timeouthour3 >= 6)// If timeinhour3  is 1am onwards but not greater than 6 
						{
							console.log("17");
							nightdiff = timeinhour3 - 6;
						}
						else if(timeinhour3 <= 22 && timeouthour3 >= 22)// pos 13
						{
							console.log("18");
							time3 = timeouthour3 - 22;
							nightdiff = Math.abs(time3);
						}
						else if(timeinhour3 <= 6 && timeouthour3 <= 6)
						{
							nightdiff = timeinhour3 - timeouthour3;
							nightdiff = Math.abs(nightdiff);
						}
						else if(timeinhour3 <= 22 && timeouthour3 <= 6)// pos 14
						{
							console.log("19");
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

					// deduct nightdiff if timeout minutes is greater than timein minutes
					if(nightdiff != "")
					{
						if(timeinmin1 > timeoutmin1)
						{
							nightdiff--;
						}
						if(timeinmin2 > timeoutmin2)
						{
							nightdiff--;
						}
						if(timeinmin3 > timeoutmin3)
						{
							nightdiff--;
						}
					}

					if(nightdiffMins >= 60)// Night diff mins is greater than 60mins then add 1hour to nightdiff
					{
						nightdiffMins -= 60;
						nightdiffMins = abs(nightdiffMins);
						nightdiff += 1;
					}
					// if(nightdiffBool == false && nightdiff == "")
					// 	nightdiffBool = true;

					if(nightdiffBool && nightdiffMins != 0 && nightdiff == "")
					{
						row.querySelector('.nightdiff').value = nightdiffMins + "mins";
						row.querySelector('.nightdiffH').value = nightdiffMins + "mins";
					}
					else if(nightdiff != "")
					{
						// console.log("1");
						if(nightdiffMins != 0)
						{
								// console.log("2");
							row.querySelector('.nightdiff').value = nightdiff + " hrs, " + nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs, " + nightdiffMins + "mins";
						}
						else
						{
								// console.log("3");
							row.querySelector('.nightdiff').value = nightdiff + " hrs";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs";
						}
					}
					else
					{
							// console.log("4");
						row.querySelector('.nightdiff').value = "";
						row.querySelector('.nightdiffH').value = "";
					}
				}
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
			if(timeinhour2 < 12)
			timeinhour2 +=12;
			else
				timeinhour2 -=12;

			if(timeouthour2 < 12)
				timeouthour2 +=12;
			else
				timeouthour2 -=12;
			//night shift
			if(timeinhour3 < 12)
			timeinhour3 +=12;
			else
				timeinhour3 -=12;

			if(timeouthour3 < 12)
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
		
			workinghours1 = timeouthour1 - timeinhour1;
			workinghours2 = timeouthour2 - timeinhour2;
			workinghours3 = timeouthour3 - timeinhour3;

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
			workinghours = Math.abs(workinghours1) + Math.abs(workinghours2) + Math.abs(workinghours3);
			
			
			//alert(workinghours);
			//alert("timein: "+timeinhour + " timeout: " + timeouthour);
			//Computing minutes
			if(timeinmin1 > timeoutmin1)
			{
				var time_temp = 60 - timeinmin1;
				workingmins1 = time_temp + timeoutmin1;
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
				var time_temp = 60 - timeinmin2;
				workingmins2 = time_temp + timeoutmin2;
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
				var time_temp = 60 - timeinmin3;
				workingmins3 = time_temp + timeoutmin3;
			}
			if(timeoutmin3 > timeinmin3)
			{
				workingmins3 = timeoutmin3 - timeinmin3;
			}
			if(timeinmin3 == timeoutmin3)
			{
				workingmins3 = 0;
			}
			
			workingmins = Math.abs(workingmins1) + Math.abs(workingmins2) + Math.abs(workingmins3);
			originalMins = workingmins;//minutes for night diff
						//Undeclared break

			//1st Time in and Time out
			var checkTime1 = timeinhour1 - timeouthour1;
			var checkTime2 = timeinhour2 - timeouthour2;
			var checkTime3 = timeinhour3 - timeouthour3;

			var useOnce = true;

			//if employee is a driver or pahinante
			if(row.querySelector('.driver').value == 1 && isSunday)
			{
				console.log("driver");
				if((timeinhour1 >= timeouthour1 && timeinhour1 >= 9) || (timeouthour1 <= timeinhour1 && timeouthour1 <= 9))// if employee timed out at 9pm onward
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//2nd Time in and Time out
				if((timeinhour2 >= timeouthour2 && timeinhour2 >= 9) || (timeouthour2 <= timeinhour2 && timeouthour2 <= 9))// if employee timed out at 9pm onward
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//3nd Time in  and Time out
				workingmins = Math.abs(workingmins);
				if((timeinhour2 >= timeouthour2 && timeinhour2 >= 9) || (timeouthour2 <= timeinhour2 && timeouthour2 <= 9))// if employee timed out at 9pm onward
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
			}
			else
			{
				console.log("hindi driver");
				if((timeinhour1 >= timeouthour1 && timeinhour1 >= 9) || (timeouthour1 <= timeinhour1 && timeouthour1 <= 9))// if employee timed out at 9pm onward
				{
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);

				if((timeinhour1 >= timeouthour1) && timeouthour1 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}
				//2nd Time in and Time out
				useOnce = true;
				if((timeinhour2 >= timeouthour2 && timeinhour2 >= 9) || (timeouthour2 <= timeinhour2 && timeouthour2 <= 9))// if employee timed out at 9pm onward
				{
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				// console.log(workinghours+" : "+workingmins)
				workingmins = Math.abs(workingmins);
				if((timeinhour2 >= timeouthour2) && timeouthour2 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
					
				}

				//3nd Time in and Time out
				useOnce = true;
				if((timeinhour3 >= timeouthour3 && timeinhour3 >= 9) || (timeouthour3 <= timeinhour3 && timeouthour3 <= 9))// if employee timed out at 9pm onward
				{
					if(workingmins == 0)// if no minutes are rendered
					{
						useOnce = false;
						workinghours -= 1;
						workingmins = 30;//minus 30mins
					}
					else // if there are minutes entered
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					// if workingmins is negative it should get the 
					if(workingmins < 0)
					{
						workinghours -= 1;
						useOnce = false;
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
				workingmins = Math.abs(workingmins);
				if((timeinhour3 >= timeouthour3) && timeouthour3 >= 1)// If time in and time out encapsulates the 12pm-1pm mark deduct 30mins
				{
					
					if(workingmins == 0)
					{
						if(useOnce)
						{
							useOnce = false;
							workinghours -= 1;
						}
						workingmins = 30;//minus 30mins
					}
					else
					{
						workingmins = workingmins - 30;//minus 30mins
					}
					
					if(workingmins < 0)
					{
						if(useOnce)
							workinghours -= 1;
						
						if(workingmins == 0)
							workingmins = Math.abs(workingmins);
						else
							workingmins = 60 - Math.abs(workingmins);
					}
				}
			}
			if(workingmins >= 60)
			{
				workinghours++;
				 workingmins = Math.abs(workingmins) - 60;
			}

			
			row.querySelector('.attendance').value = "PRESENT";
		// WORKING HOURS
			if(workinghours <= 5)//HALF DAY
			{
				if(workingmins == 0)
				{
					row.querySelector('.workinghours').value = workinghours + " hrs";
					row.querySelector('.workinghoursH').value = workinghours + " hrs";
				}
				else
				{
					row.querySelector('.workinghours').value = workinghours + " hrs, " + workingmins + " mins";
					row.querySelector('.workinghoursH').value = workinghours + " hrs, " +  + workingmins + " mins";
				}
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
			if(!isSunday)
			{
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
				var Uworkinghour = workinghours + 1;
				var Uworkingmins = 60 - workingmins;
				row.querySelector('.undertime').value = Math.abs(Uworkinghour - 8) + " hrs, " + Uworkingmins + " mins";
				row.querySelector('.undertimeH').value = Math.abs(Uworkinghour - 8) + " hrs, " + Uworkingmins + " mins";
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
			var nightdiffMins = 0;
			//Declaration of variable to be computed
			var time1 = 0;
			var time2 = 0;
			var time3 = 0;
			if(compReq)// If Employee has complete requirements. Employees that have no requirements dont have nightdiff
			{
				console.log("Complete Req");
				if(!isSunday)
				{
					console.log("2) timein1: "+timeinhour1+" / timeout1: "+timeouthour1+" | timein2: "+timeinhour2+" / timeout2: "+timeouthour2+" | timein3: "+timeinhour3+" / timeout3: "+timeouthour3);
					if ((timeinhour1 <= 10 && timeouthour1 >= 10) ||// pos1 ~ 6
						(timeinhour1 <= 10 && timeouthour1 <= 18) ||// pos7 ~ 8
						(timeinhour2 <= 10 && timeouthour2 >= 10) ||// pos9 ~ 10
						(timeinhour2 <= 10 && timeouthour2 <= 18) ||// pos 11 ~ 12
						(timeinhour3 <= 10 && timeouthour3 >= 10) ||// pos 13
						(timeinhour3 <= 10 && timeouthour3 <= 18) ||

						(timeinhour2 >= 10 && (timeinhour2 <= 18 && timeouthour2 >= 18)) || /// If timeinhour3  is 1am onwards but not greater than 6 
						(timeinhour3 >= 10 && (timeinhour3 <= 18 && timeouthour3 >= 18)) || /// If timeinhour3  is 1am onwards but not greater than 6 

						(timeouthour1 == 10 && timeoutmin1 != 0) ||
						(timeouthour2 == 10 && timeoutmin2 != 0) ||
						(timeouthour3 == 10 && timeoutmin3 != 0))// pos 14
					{	
						console.log('pasok ND');

						console.log('TIME IN 1: ' + timeinhour1 + ' ' + timeouthour1);
						console.log('TIME IN 2: ' + timeinhour2 + ' ' + timeouthour2);
						console.log('TIME IN 3: ' + timeinhour3 + ' ' + timeouthour3);
						var nightdiffBool = false;//boolean if ND is just minutes

						// Nightdiff mins
							if(	(timeinhour1 <= 10 && timeouthour1 >= 10) ||
								(timeinhour1 <= 10 && timeouthour1 >= 18) ||
								(timeouthour1 == 10 && timeoutmin1 != 0) || 

								(timeinhour1 <= 10 && timeouthour1 >= 10 && workingmins1 != 0) ||
								(timeinhour1 <= 10 && timeouthour1 >= 18 && workingmins1 != 0) ||
								(timeouthour1 == 10 && timeoutmin1 != 0 && workingmins1 != 0))
							{
								console.log("1");
								if(	(timeinhour1 <= 10 && timeouthour1 >= 10 && timeoutmin1 >= 0) || 
									(timeinhour1 <= 10 && timeouthour1 >= 18 && timeoutmin1 >= 0))
								{
									console.log("1.1");
									nightdiffMins += timeoutmin1;
								}
								else
								{
									console.log("1.2");
									nightdiffMins += workingmins1;
								}
								nightdiffBool = true;
							}
							if(	(timeinhour2 <= 10 && timeouthour2 >= 10) ||
								(timeinhour2 <= 10 && timeouthour2 >= 18) ||
								(timeouthour2 == 10 && timeoutmin2 != 0) ||
								(timeinhour2 <= 18 && timeouthour2 >= 18) ||

								(timeinhour2 <= 10 && timeouthour2 >= 10 && workingmins2 != 0) ||
								(timeinhour2 <= 10 && timeouthour2 >= 18 && workingmins2 != 0) ||
								(timeouthour2 == 10 && timeoutmin2 != 0 && workingmins2 != 0) ||
								(timeinhour2 <= 18 && timeouthour2 >= 18 && workingmins2 != 0) )
							{
								console.log("2");
								if(	(timeinhour2 <= 10 && timeouthour2 >= 10 && timeoutmin2 >= 0) || 
									(timeinhour2 <= 10 && timeouthour2 >= 18 && timeoutmin2 >= 0))
								{
									console.log("2.1");
									nightdiffMins += timeoutmin2;
								}
								else
								{
									console.log("2.2");
									nightdiffMins += workingmins2;
								}
								nightdiffBool = true;
							}
							if(	(timeinhour3 <= 10 && timeouthour3 >= 10) ||
								(timeinhour3 <= 10 && timeouthour3 >= 18) ||
								(timeouthour3 == 10 && timeoutmin3 != 0) ||
								(timeinhour3 <= 18 && timeouthour3 >= 18) || 

								(timeinhour3 <= 10 && timeouthour3 >= 10 && workingmins3 != 0) ||
								(timeinhour3 <= 10 && timeouthour3 >= 18 && workingmins3 != 0) ||
								(timeouthour3 == 10 && timeoutmin3 != 0 && workingmins3 != 0) ||
								(timeinhour3 <= 18 && timeouthour3 >= 18 && workingmins3 != 0))
							{
								console.log("3");
								// nightdiff = timeouthour3 - 10;
								if(	(timeinhour3 <= 10 && timeouthour3 >= 10 && timeoutmin3 >= 0) || 
									(timeinhour3 <= 10 && timeouthour3 >= 18 && timeoutmin3 >= 0))
								{
									console.log("3.1");
									nightdiffMins += timeoutmin3;
								}
								else
								{
									console.log("3.3");
									nightdiffMins += workingmins3;
								}
								nightdiffBool = true;
							}


						if((timeinhour1 <= 10 && timeouthour1 >= 10) && timeouthour1 <= 12)// pos1 ~ 6
						{
							if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 >= 12 && timeouthour3 <= 18))// pos 1
							{
								console.log("pos1");
								time1 = timeouthour1 - 10;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 2
							{
								console.log("pos2");
								time1 = timeouthour1 - 10;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - 18;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							if((timeinhour2 >= 10 && timeouthour2 <= 18) && (timeinhour3 >= 12 && timeouthour3 <= 18))// pos 3
							{
								console.log("pos3");
								time1 = timeouthour1 - 10;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 10 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 4
							{
								console.log("pos4");
								time1 = timeouthour1 - 10;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - 18;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
							else if((timeinhour2 >= 12 && timeouthour2 <= 18) && timeinhour3 >= 18)// pos 5
							{
								console.log("pos5");
								time1 = timeouthour1 - 10;
								time2 = timeinhour2 - timeouthour2;
								nightdiff = Math.abs(time1) + Math.abs(time2);
							}
							else// pos 6 -- 1st time is the only one inside nightdiff time
							{
								console.log("pos6");
								time1 = timeouthour1 - 10;
								nightdiff = Math.abs(time1);
							}

						}
						else if(timeinhour1 <= 10 && timeouthour1 >= 10)// pos7 ~ 8
						{
							if((timeinhour2 >= 12 && timeouthour2 <= 18) && timeinhour3 > 18)// pos 7
							{
								console.log("pos7");
								time1 = 10 - timeouthour1;
								time2 = timeinhour2 - timeouthour2;
								nightdiff = Math.abs(time1) + Math.abs(time2);
							}
							else if((timeinhour2 >= 12 && timeouthour2 <= 18) && (timeinhour3 <= 18 && timeouthour3 >= 18))// pos 8
							{
								console.log("pos8");
								time1 = 10 - timeouthour1;
								time2 = timeinhour2 - timeouthour2;
								time3 = timeinhour3 - 18;
								nightdiff = Math.abs(time1) + Math.abs(time2) + Math.abs(time3);
							}
						}
						else if(timeinhour2 <= 10 && timeouthour2 >= 10)// pos9 ~ 10
						{
							if(timeinhour3 >= 12 && timeouthour3 <= 18) // pos 9
							{
								console.log("pos9");
								time2 = timeouthour2 - 10;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
							else // pos 10
							{
								console.log("pos10");
								time2 = timeouthour2 - 10;
								nightdiff = Math.abs(time2);
							}
						}
						else if((timeinhour2 <= 10 && timeouthour2 >= 10) && timeouthour2 >= 12)// pos 11 ~ 12
						{
							if(timeinhour3 <= 18 && timeouthour3 >= 18)// pos 11
							{
								console.log("pos11");
								time2 = 10 - timeouthour2;
								time3 = timeinhour3 - 18;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
							else if(timeinhour3 >= 12 && timeouthour3 <= 18)// pos 12
							{
								console.log("pos12");
								time2 = 10 - timeouthour2;
								time3 = timeinhour3 - timeouthour3;
								nightdiff = Math.abs(time2) + Math.abs(time3);
							}
						}
						else if(timeinhour2 >= 10 && (timeinhour2 <= 18 && timeouthour2 >= 18))
						{
							nightdiff = timeinhour2 - 18;
						}
						else if(timeinhour3 >= 10 && (timeinhour3 <= 18 && timeouthour3 >= 18))
						{
							nightdiff = timeinhour3 - 18;
						}
						else if(timeinhour3 <= 10 && timeouthour3 >= 10 && timeouthour3 <= 18)// pos 13
						{
							console.log("pos13a");
							time3 = timeouthour3 - 10;
							nightdiff = Math.abs(time3);
						}
						else if(timeinhour3 <= 10 && timeouthour3 >= 10 && timeouthour3 >= 18)// pos 14
						{
							console.log("pos14");
							time3 = 8;
							nightdiff = Math.abs(time3);
						}
						else if(timeinhour3 <= 10 && timeouthour3 <= 18)// pos 15
						{
							console.log("pos15");
							time3 = 10 - timeouthour3;
							nightdiff = Math.abs(time3);
						}
						else if(timeinhour3 >=10 && timeouthour3 <=18)// pos 16
						{
							console.log("pos16");
							time3 = timeinhour3 - timeouthour3;
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
					// deduct nightdiff if timeout minutes is greater than timein minutes
					if(nightdiff != "")
					{
						if(timeinmin1 > timeoutmin1)
						{
							nightdiff--;
						}
						if(timeinmin2 > timeoutmin2)
						{
							nightdiff--;
						}
						if(timeinmin3 > timeoutmin3)
						{
							nightdiff--;
						}
					}

					//problema
					if(nightdiffMins >= 60)// Night diff mins is greater than 60mins then add 1hour to nightdiff
					{
						nightdiffMins -= 60;
						nightdiffMins = Math.abs(nightdiffMins);
						nightdiff += 1;
					}

					// if(nightdiffBool == false && nightdiff == 0)//if nightdiff is zero 
					// 	nightdiffBool = true;

					if(nightdiffBool && nightdiffMins != 0 && nightdiff == "")
					{
						row.querySelector('.nightdiff').value = nightdiffMins + "mins";
						row.querySelector('.nightdiffH').value = nightdiffMins + "mins";
					}
					else if(nightdiff != "")
					{
						if(nightdiffMins != 0)
						{
							row.querySelector('.nightdiff').value = nightdiff + " hrs, " + nightdiffMins + "mins";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs, " + nightdiffMins + "mins";
						}
						else
						{
							row.querySelector('.nightdiff').value = nightdiff + " hrs";
							row.querySelector('.nightdiffH').value = nightdiff + " hrs";
						}
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
				console.log("No Req");
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

function AutoTimeIn85(id) {
	allowInputsFromRow(id);
	var mainRow = document.getElementById(id);

	mainRow.querySelector('.timein1').value = '08:00 AM';
	mainRow.querySelector('.timeout1').value = '12:00 PM';
	mainRow.querySelector('.timein2').value = '01:00 PM';
	mainRow.querySelector('.timeout2').value = '05:00 PM';
	mainRow.querySelector('.timein1').placeholder = '';
	mainRow.querySelector('.timeout1').placeholder = '';
	mainRow.querySelector('.timein2').placeholder = '';
	mainRow.querySelector('.timeout2').placeholder = '';
	mainRow.querySelector('.timein3').placeholder = '';
	mainRow.querySelector('.timeout3').placeholder = '';
	mainRow.querySelector('.workinghours').value = '8 hrs';
	mainRow.querySelector('.workinghoursH').value = '8 hrs';

	mainRow.querySelector('.timein2').disabled = false;
	mainRow.querySelector('.timeout2').disabled = false;

	mainRow.querySelector('.halfdayChk').checked = false;
	mainRow.querySelector('.nightshiftChk').checked = false;

	mainRow.querySelector('.halfdayChk').disabled = false;
	mainRow.querySelector('.nightshiftChk').disabled = false;

	mainRow.querySelector('.attendance').value = 'PRESENT'; 
	mainRow.classList.add('success');
	if(mainRow.classList.contains('danger'))
	{
		mainRow.classList.remove('danger');
	}
	
}

function AutoTimeIn74(id) {
	allowInputsFromRow(id);
	var mainRow = document.getElementById(id);

	mainRow.querySelector('.timein1').value = '07:00 AM';
	mainRow.querySelector('.timeout1').value = '12:00 PM';
	mainRow.querySelector('.timein2').value = '01:00 PM';
	mainRow.querySelector('.timeout2').value = '04:00 PM';
	mainRow.querySelector('.timein1').placeholder = '';
	mainRow.querySelector('.timeout1').placeholder = '';
	mainRow.querySelector('.timein2').placeholder = '';
	mainRow.querySelector('.timeout2').placeholder = '';
	mainRow.querySelector('.timein3').placeholder = '';
	mainRow.querySelector('.timeout3').placeholder = '';
	mainRow.querySelector('.workinghours').value = '8 hrs';
	mainRow.querySelector('.workinghoursH').value = '8 hrs';

	mainRow.querySelector('.timein2').disabled = false;
	mainRow.querySelector('.timeout2').disabled = false;

	// Uncheck checkboxes
	mainRow.querySelector('.halfdayChk').checked = false;
	mainRow.querySelector('.nightshiftChk').checked = false;
	// Undisable checkboxes
	mainRow.querySelector('.halfdayChk').disabled = false;
	mainRow.querySelector('.nightshiftChk').disabled = false;

	mainRow.querySelector('.attendance').value = 'PRESENT'; 
	mainRow.classList.add('success');
	if(mainRow.classList.contains('danger'))
	{
		mainRow.classList.remove('danger');
	}
	
}















