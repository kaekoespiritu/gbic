// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Checkbox to trigger dropdown
		function triggerInput(dayOfWeek)
		{
			//alert('yow');
			var checkbox = document.getElementsByName('checkboxes'), i;
			var checkboxlength = document.querySelectorAll('input[type=checkbox]').length;
			var changeDefault = document.getElementById(dayOfWeek);

			// Disabled dropdown when checkbox is deselected
			if(document.getElementById(dayOfWeek+"BOX").checked==false)
			{
				var cellUNCHECK = document.getElementById(dayOfWeek);
				cellUNCHECK.setAttribute('disabled', '');

				if(changeDefault.options[2].hasAttribute('selected'))
				{
					changeDefault.options[2].removeAttribute('selected');
					changeDefault.options[0].setAttribute('selected','');
					document.getElementById('close').value = "";//set hidden text to the day
				}
					
				else if(changeDefault.options[1].hasAttribute('selected'))
				{
					changeDefault.options[1].removeAttribute('selected');
					changeDefault.options[0].setAttribute('selected','');
					document.getElementById('open').value = "";//set hidden text to the day
				}
			}

			// Enable dropdown when checkbox is selected
			else if(document.getElementById(dayOfWeek+"BOX").checked==true)
			{
				var cellCHECK = document.getElementById(dayOfWeek);
				cellCHECK.removeAttribute('disabled');
				cellCHECK.options[0].removeAttribute('selected');
				cellCHECK.options[1].setAttribute('selected','');
				if(document.getElementById('open').value == "") 
				{
					document.getElementById('open').value = dayOfWeek;//set hidden text to the day
				}
				else
				{
					document.getElementById('close').value = dayOfWeek;//set hidden text to the day
				}
				
			}

			// Checking if 2 checkboxes are active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 2)
			{
				changeDefault.options[2].setAttribute('selected','');
				document.getElementById('close').value = dayOfWeek;//set hidden text to the day
				
				 for(i = 0; i <= checkboxlength; i++)
				 {

				 	if(!checkbox[i].checked)
				 	{
				 		checkbox[i].setAttribute('disabled', 'disabled');	
				 	}

				 	if(checkbox[i].checked==true)
				 	{
				 		console.log(changeDefault);
				 	}
				 }
			} 
			
			// Checking if only 1 checkbox is active
			if(document.querySelectorAll('input[type=checkbox]:checked').length === 1)
			{		
				for(var i = 0; i <= checkboxlength; i++)
				{
				    if(!checkbox[i].checked)
				    {
				    	checkbox[i].removeAttribute('disabled');
				    	document.getElementById(dayOfWeek).options[0].selected = true;
				    }   
				}
			}
		}

		// Swapping elements after selecting a different dropdown option
		function swap(chosenDay) {
			var day = ['Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday'];		
			var first = "";	

			for(var a = 0; a < 7; a++)
			{
				
				if(chosenDay != day[a])
				{
					if(document.getElementById(day[a]).disabled == false)
					{	
						//alert(chosenDay +" "+ day[a]);
						if(document.getElementById(day[a]).options[1].selected == true) // OPEN
						{
							document.getElementById(day[a]).options[2].selected = true; // CLOSE
							document.getElementById(day[a]).options[1].selected = false;
							document.getElementById(chosenDay).options[1].selected = true;
							document.getElementById('close').value = day[a];//set hidden text to the day
							document.getElementById('open').value = chosenDay;//set hidden text to the day
							//alert('yeah');

						}
						else if(document.getElementById(day[a]).options[2].selected == true)
						{
							document.getElementById(day[a]).options[1].selected = true; // CLOSE
							document.getElementById(day[a]).options[2].selected = false;
							document.getElementById(chosenDay).options[2].selected = true;
							document.getElementById('open').value = day[a];//set hidden text to the day
							document.getElementById('close').value = chosenDay;//set hidden text to the day
							//alert('yeah');
						}
					}
				}
			}
		}

		function siteRemove(){
			var a = confirm("Are you sure you want to Archive these Site(s)?")
			if(a)
			{
				document.getElementById('siteForm').submit();
			}
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
					checkbox[i].disabled = false;
				}

			}
			else
			{
				// If previously selected admin revert changes
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = true;
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
		































