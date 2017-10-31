document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
	$(function(){
		$("select").multipleSelect({
			placeholder: "Select site for attendance&#9662;",
			selectAll: false,
			width: 200,
			multiple: true,
			multipleWidth: 200
		});

		//var currentDate = new Date();
		var currentDate = "<?php Print "$date"; ?>";
		/* DATE PICKER CONFIGURATIONS*/
		$( "#dtpkr_attendance" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'MM dd, yy',
			showAnim: 'blind',
			maxDate: new Date(),
			beforeShow: function(){    
				$(".ui-datepicker").css('font-size', 15) 
			}
		});

		$("#dtpkr_attendance").datepicker("setDate", currentDate);

	
		$("#dtpkr_attendance").change(function(){
			var date = $(this).val();
			window.location.href = "date_attendance.php?date="+date;
		});
		$( "#cancel").on("click",function(){
			window.location.href = "attendance_unset.php";
		});
		
	});

	window.onload = function checkAttendance(){
			var sites = document.getElementsByName('site');

			for(var i = 0; i < sites.length; i++)
			{
				if(sites[i].getAttribute('value')==1)
				{
					// add checkmark to box
					console.log("First off... " + sites[i]);
					sites[i].setAttribute("class", "checkmark glyphicon glyphicon-ok");
					
				}
				else
				{
					// do nothing
				}
			}
	}
	function fittext()
	{
		// Declare fixed div size
		var maxW = 132, maxH = 72, maxSize = 12;
		var c = document.getElementsByClassName("smalltext");
		var d = document.createElement("span");
		d.style.fontSize = maxSize + "px";

		for (var i = 0; i < c.length; i++)
		{
			d.innerHTML = c[i].innerHTML;
			document.body.appendChild(d);
			var w = d.offsetWidth;
			var h = d.offsetHeight;
			document.body.removeChild(d);
			var x = w > maxW ? maxW / w : 1;
			var y = h > maxH ? maxH / h : 1;
			var r = Math.min(x, y) * maxSize;
			c[i].style.fontSize = r + "px";
		}
	}
	function printAll()
	{
		window.location.assign("print_all_employee.php");
	}
	function Holiday(element)
	{
		/** CREATING THE FORM **/
		// Instantiating fields
		var nameOfHoliday = document.createElement("input");
			nameOfHoliday.setAttribute("type", "text");
			nameOfHoliday.setAttribute("class", "form-control");
			nameOfHoliday.setAttribute("placeholder", "Enter holiday name");
			nameOfHoliday.setAttribute("onkeypress", "txtHoliday(event)");
			nameOfHoliday.setAttribute("id", "nameOfHoliday");

		var cancelButton = document.createElement("a");
			cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down");
			cancelButton.setAttribute("id", "cancel");
			cancelButton.setAttribute("href", "holiday_query.php?date=<?php Print $date ?>");
			cancelButton.innerHTML = "Cancel";

		// Replacing button with input field
		element.parentNode.replaceChild(nameOfHoliday, document.getElementById('holiday'));

		// Adding cancel button
		document.getElementById('dynamicForm').appendChild(cancelButton);
	}
	
	function txtHoliday(e)
	{
		// Adding labels for radio buttons
		var regularLabel = document.createElement("label");
		regularLabel.setAttribute("class", "radio-inline");
		regularLabel.setAttribute("for", "regular");
		regularLabel.innerHTML = "<input type='radio' id='regular' name='holidays' onclick='titleHoliday(this)'> Regular";
		
		var specialLabel = document.createElement("label");
		specialLabel.setAttribute("class", "radio-inline");
		specialLabel.setAttribute("for", "special");
		specialLabel.innerHTML = "<input type='radio' id='special' name='holidays' onclick='titleHoliday(this)'> Special";

		// Transferring saved value to hidden input
		var name = document.getElementById('holidayName');

		// Checking if user pressed ENTER
		if(e.keyCode === 13 || e.which === 13)
		{
			e.preventDefault();
			name.setAttribute("value",document.getElementById('nameOfHoliday').value);

			// Instantiaing fields
			var form = document.getElementById('dynamicForm');
			var inputField = document.getElementById('nameOfHoliday');
			var cancel = document.getElementById('cancel');
			var cancelButton = document.createElement("a");
				cancelButton.setAttribute("class", "btn btn-danger btn-sm pull-down");
				cancelButton.setAttribute("id", "cancel");
				cancelButton.setAttribute("href", "holiday_query.php?date=<?php Print $date ?>");
				//cancelButton.setAttribute("onclick", "cancelHoliday()");
				cancelButton.innerHTML = "Cancel";

			// Removing previous form
			form.removeChild(inputField);
			form.removeChild(cancel);

			// Adding labels
			form.appendChild(regularLabel);
			form.appendChild(specialLabel);

			document.getElementById('dynamicForm').appendChild(cancelButton);
		}
	}

	function titleHoliday(e)
	{
		var specialHoliday = document.getElementById('special');
		var regularHoliday = document.getElementById('regular');
		var holidayTitle = document.getElementById('holidayTitle');
		var name = document.getElementById('holidayName').value;
		var type = document.getElementById('holidayType');

		/* FUNCTIONS */
		if(regularHoliday.checked == true || specialHoliday.checked == true)
		{
			holidayTitle.innerHTML = name + " attendance log";

			if(regularHoliday.checked == true)
			{
				
				type.setAttribute("value", "regular");
				document.getElementById('holidayForm').submit();
				window.location.href = "holiday_query.php?type=regular&name="+name+"&date=<?php Print $date ?>";
			}
			else if(specialHoliday.checked == true)
			{
				
				type.setAttribute("value", "special");
				document.getElementById('holidayForm').submit();
				window.location.href = "holiday_query.php?type=special&name="+name+"&date=<?php Print $date ?>";
			}
		}
	}

	fittext();