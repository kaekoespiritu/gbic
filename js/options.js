// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Open and close payroll
		function setPayroll() {
			// Get value of open and close dropdown
			var open = document.getElementById('open');
			var close = document.getElementById('close');
			var openIndex = open.selectedIndex;
			var closeIndex = close.selectedIndex;

			// selectedIndex = 0-6
			// options[#].text = days of week

			/* 
			0 - Monday 
			1 - Tuesday
			2 - Wednesday
			3 - Thursday
			4 - Friday
			5 - Saturday
			6 - Sunday
			*/

			// If open is set, be sure that close is only +/- 1 away from selection
			// Prompt an error for selecting a close payroll that exceeds said limit
			// Static open, close will determine the validity of duration
			// Can be more or less than make a new variable

			if(closeIndex==0 && openIndex==6){
				// Reset value to Monday
				for(var i = 0; i < close.options.length; i++){
					if(close.options[i].value==closeIndex){
						close.options[closeIndex-closeIndex].selected = true;
					}
				}
			}

			if((closeIndex > 0) && (closeIndex > openIndex+1) || (closeIndex > 0) && (closeIndex < openIndex - 1)){
				alert("You have selected an invalid date range. Please select dates that are adjacent. Like Monday-Tuesday. Close payroll will be reset to Monday.");
				// Reset value to Monday
				for(var i = 0; i < close.options.length; i++){
					if(close.options[i].value==closeIndex){
						close.options[0].selected = true;
					}
				}
			}
			if(closeIndex == openIndex){
				alert("Error. Please select two different days. Close payroll will be reset to Monday.");
				// Reset value to Monday
				for(var i = 0; i < close.options.length; i++){
					if(close.options[i].value==closeIndex){
						close.options[0].selected = true;
					}
				}
			}

			console.log("close:" + closeIndex + " open: " + openIndex);

			// 
		}

		function hideRestrictions() {
			var admin = document.getElementById('adminradio');
			
			// To change when admin is selected
			var checkboxlength = document.querySelectorAll('input[type="checkbox"]').length;
			var checkbox = document.querySelectorAll('input[type="checkbox"]');

			// If admin is selected, hide restrictions pane and set width to full
			if(admin.checked == true)
			{
				
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = true;
				}

			}
			else
			{
				// If previously selected admin revert changes
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = false;
				}
			}
		}

		function removeAccount(user){
			var a = confirm("Are you sure you want to remove this employee account?");
			if(a)
			{
				var username = user;
				//Create form to pass the values through POST and not GET
				var form = document.createElement("form");
				form.setAttribute("method","post");
				form.setAttribute("action","logic_options_removeUser.php");
				form.setAttribute("id","RemoveAcctForm");

				var user = document.createElement("input");
				user.setAttribute("type","hidden");
				user.setAttribute("name","userTerminate");
				user.setAttribute("value",username);
				//append User inside form
				form.appendChild(user);

				document.getElementById('hiddenFormDiv').appendChild(form);
				document.getElementById('RemoveAcctForm').submit();
			}
		}

		function optionAccount() {
			var a = confirm("Are you sure?");
			if(a)
			{
				document.getElementById('account_option').submit();
			}
		}

		function newAccountFunction() {
		 	document.getElementById('newAccount_submit').click();
		  
		}

		function passwordReset(user) {
			$.ajax({
				url:"fetch_password_reset.php",
				method:"POST",
				data:{
						username: user
				},
				success:function(data)
				{
					$('#newGeneratedPassword').html(data)
				}
			});
		}
