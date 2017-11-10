		// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Open and close payroll
		function () {
			
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
		































