// Changes color of active tab to admin options
		document.getElementById("adminOptions").setAttribute("style", "background-color: #10621e;");

		// Save function to database
		function save()
		{
			confirm("Are you sure you want to save this new open/close payroll schedule?");
		}


		// Open and close payroll
		function setPayroll(day, cond) {
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

			if(closeIndex==0 && openIndex==0){
				// Reset value to Monday
				for(var i = 0; i < open.options.length; i++){
					if(open.options[i].value==closeIndex){
						open.options[closeIndex-closeIndex].selected = true;
					}
				}
			}
			//For open Payroll
			if(cond == "open") {
				if((closeIndex > 0) && (closeIndex > openIndex+1) || (closeIndex > 0) && (closeIndex < openIndex - 1)){
					alert("You have selected an invalid date range. Please select dates that are adjacent. Like Monday-Tuesday.");
					// Reset value to Monday

					for(var i = 0; i < open.options.length; i++){
						if(open.options[i].value == day){
							open.options[i].selected = true;
						}
					}
				}
				if(closeIndex == openIndex){
					alert("Error. Please select two different days. Open payroll will be reset to "+day+".");
					// Reset value to Monday
					for(var a = 0; a < open.options.length; a++){
						if(open.options[a].value == day){
							open.options[a].selected = true;
						}
					}
				}
			}
			// For close payroll
			else if(cond == "close"){
				if((closeIndex > 0) && (closeIndex > openIndex+1) || (closeIndex > 0) && (closeIndex < openIndex - 1)){
					alert("You have selected an invalid date range. Please select dates that are adjacent. Like Monday-Tuesday.");
					// Reset value to Monday

					for(var i = 0; i < close.options.length; i++){
						if(close.options[i].value == day){
							close.options[i].selected = true;
						}
					}
				}
				if(closeIndex == openIndex){
					alert("Error. Please select two different days. Close payroll will be reset to "+day+".");
					// Reset value to Monday
					for(var a = 0; a < close.options.length; a++){
						if(close.options[a].value == day){
							close.options[a].selected = true;
						}
					}
				}
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
					checkbox[i].disabled = true;
					checkbox[i].checked = true;

				}

			}
			else
			{
				// If previously selected admin revert changes
				for(var i = 0; i < checkboxlength; i++){
					checkbox[i].disabled = false;
					checkbox[i].checked = false;
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

		//Restrictions

		function restrictEmployees() {

			var employeeTab = document.getElementById('restrictEmployeesTab');

			if(employeeTab.checked == true){
				document.getElementsByName('res_listOfEmployees')[0].checked = true;
				document.getElementsByName('res_listOfLoanApp')[0].checked = true;
				document.getElementsByName('res_listOfAbsence')[0].checked = true;
				document.getElementsByName('res_listOfSiteManage')[0].checked = true;
			}
			else{
				document.getElementsByName('res_listOfEmployees')[0].checked = false;
				document.getElementsByName('res_listOfLoanApp')[0].checked = false;
				document.getElementsByName('res_listOfAbsence')[0].checked = false;
				document.getElementsByName('res_listOfSiteManage')[0].checked = false;
			}

		}

		function restrictReports() {

			var reportsTab = document.getElementsByName('res_Reports')[0];

			if(reportsTab.checked == true){
				document.getElementsByName('res_EarningsReport')[0].checked = true;
				document.getElementsByName('res_ContributionsReport')[0].checked = true;
				document.getElementsByName('res_LoansReport')[0].checked = true;
				document.getElementsByName('res_AttendanceReport')[0].checked = true;
				document.getElementsByName('res_CompanyExpensesReport')[0].checked = true;
			}
			else{
				document.getElementsByName('res_EarningsReport')[0].checked = false;
				document.getElementsByName('res_ContributionsReport')[0].checked = false;
				document.getElementsByName('res_LoansReport')[0].checked = false;
				document.getElementsByName('res_AttendanceReport')[0].checked = false;
				document.getElementsByName('res_CompanyExpensesReport')[0].checked = false;
			}
		}

		function restrictOptions() {

			var optionsTab = document.getElementsByName('res_Options')[0];

			if(optionsTab.checked == true){
				document.getElementsByName('res_SiteManage')[0].checked = true;
				document.getElementsByName('res_PositionManage')[0].checked = true;
			}
			else{
				document.getElementsByName('res_SiteManage')[0].checked = false;
				document.getElementsByName('res_PositionManage')[0].checked = false;
			}
		}

		//Edit Restrictions

		function restrictEmployeesEdit() {

			var employeeTab = document.getElementById('restrictEmployeesTabEdit');

			if(employeeTab.checked == true){
				document.getElementsByName('res_listOfEmployeesEdit')[0].checked = true;
				document.getElementsByName('res_listOfLoanAppEdit')[0].checked = true;
				document.getElementsByName('res_listOfAbsenceEdit')[0].checked = true;
				document.getElementsByName('res_listOfSiteManageEdit')[0].checked = true;
			}
			else{
				document.getElementsByName('res_listOfEmployeesEdit')[0].checked = false;
				document.getElementsByName('res_listOfLoanAppEdit')[0].checked = false;
				document.getElementsByName('res_listOfAbsenceEdit')[0].checked = false;
				document.getElementsByName('res_listOfSiteManageEdit')[0].checked = false;
			}

		}

		function restrictReportsEdit() {

			var reportsTab = document.getElementsByName('res_ReportsEdit')[0];

			if(reportsTab.checked == true){
				document.getElementsByName('res_EarningsReportEdit')[0].checked = true;
				document.getElementsByName('res_ContributionsReportEdit')[0].checked = true;
				document.getElementsByName('res_LoansReportEdit')[0].checked = true;
				document.getElementsByName('res_AttendanceReportEdit')[0].checked = true;
				document.getElementsByName('res_CompanyExpensesReportEdit')[0].checked = true;
			}
			else{
				document.getElementsByName('res_EarningsReportEdit')[0].checked = false;
				document.getElementsByName('res_ContributionsReportEdit')[0].checked = false;
				document.getElementsByName('res_LoansReportEdit')[0].checked = false;
				document.getElementsByName('res_AttendanceReportEdit')[0].checked = false;
				document.getElementsByName('res_CompanyExpensesReportEdit')[0].checked = false;
			}
		}

		function restrictOptionsEdit() {

			var optionsTab = document.getElementsByName('res_OptionsEdit')[0];

			if(optionsTab.checked == true){
				document.getElementsByName('res_SiteManageEdit')[0].checked = true;
				document.getElementsByName('res_PositionManageEdit')[0].checked = true;
			}
			else{
				document.getElementsByName('res_SiteManageEdit')[0].checked = false;
				document.getElementsByName('res_PositionManageEdit')[0].checked = false;
			}
		}

















