function sssCheckboxFunc() {
	var sss = document.getElementById('sssCheckbox');
	var sssRow = document.getElementById('sss');
	if(sss.checked == true)
	{
		console.log('checked');
		sssbox();
		sssRow.readOnly = false;
		sssRow.placeholder = "";
	}
	else
	{
		console.log('unchecked');
		sssRow.placeholder = "No document";
		sssRow.readOnly = true;
		sssRow.value = "";
	}
}
function philhealthCheckboxFunc() {
	var philhealth = document.getElementById('philhealthCheckbox');
	var philhealthRow = document.getElementById('philhealth');
	if(philhealth.checked == true)
	{
		philhealthRow.readOnly = false;
		philhealthRow.required = true;
		philhealthRow.placeholder = "";
	}
	else
	{
		philhealthRow.placeholder = "No document";
		philhealthRow.readOnly = true;
		philhealthRow.required = false;
		philhealthRow.value = "";
	}
}
function pagibigCheckboxFunc() {
	var pagibig = document.getElementById('pagibigCheckbox');
	var pagibigRow = document.getElementById('pagibig');
	if(pagibig.checked == true)
	{
		pagibigRow.readOnly = false;
		pagibigRow.required = true;
		pagibigRow.placeholder = "";
	}
	else
	{
		pagibigRow.placeholder = "No document";
		pagibigRow.readOnly = true;
		pagibigRow.required = false;
		pagibigRow.value = "";
	}
}

function monthlySalary(salary){
	var salary = document.getElementById('monthlysalary').value;
	var dailyRate = document.getElementById('rate');
	dailyRate.setAttribute('value',(salary/25).toFixed(2));
	if(document.getElementById('sssCheckbox').checked)
		sssbox();
}

function salaryDecimal(){
	var salary = document.getElementById('monthlysalary');
	var value = salary.value;
	var decimal = parseInt(value).toFixed(2);
	if(value != "")
		salary.value = decimal;
}

function allowanceDecimal(){
	var allowance = document.getElementById('allowance');
	var value = allowance.value;
	var decimal = parseInt(value).toFixed(2);
	if(value != "")
		allowance.value = decimal;
}

function pagibigDecimal(evt){
	var pagibig = document.getElementById('pagibig');
	var value = document.getElementById('pagibig').value;
	var decimal = parseInt(value).toFixed(2);
	pagibig.value=decimal;	

	// REGEX
	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /[0-9]|\./;
	if( !regex.test(key) ) 
		 theEvent.returnValue = false;
	if(theEvent.preventDefault) 
		theEvent.preventDefault();
}

/* AUTOMATED COMPUTATION FOR SSS BASED ON MONTHLY SALARY */
function sssbox() {
		var monthly = document.getElementById('monthlysalary').value;
		var sssContribution = 0;

		if(monthly >= 1000 && monthly <= 1249.9)
		sssContribution = 36.30;
		//1250 ~ 1749.9 = 54.50
		else if(monthly >= 1250 && monthly <= 1749.9)
		sssContribution = 54.50;
		//1750 ~ 2249.9 = 72.70
		else if(monthly >= 1750 && monthly <= 2249.9)
		sssContribution = 72.70;
		//2250 ~ 2749.9 = 90.80
		else if(monthly >= 2250 && monthly <= 2749.9)
		sssContribution = 90.80;
		//2750 ~ 3249.9 = 109.0
		else if(monthly >= 2750 && monthly <= 3249.9)
		sssContribution = 109.00;
		//3250 ~ 3749.9 = 127.20
		else if(monthly >= 3250 && monthly <= 3749.9)
		sssContribution = 127.20;
		//3750 ~ 4249.9 = 145.30
		else if(monthly >= 3750 && monthly <= 4249.9)
		sssContribution = 145.30;
		//4250 ~ 4749.9 = 163.50
		else if(monthly >= 4250 && monthly <= 4749.9)
		sssContribution = 163.50;
		//4750 ~ 5249.9 = 181.70
		else if(monthly >= 4750 && monthly <= 5249.9)
		sssContribution = 181.70;
		//5250 ~ 5749.9 = 199.80
		else if(monthly >= 5250 && monthly <= 5749.9)
		sssContribution = 199.80;
		//5750 ~ 6249.9 = 218.0
		else if(monthly >= 5750 && monthly <= 6249.9)
		sssContribution = 218.00;
		//6250 ~ 6749.9 = 236.20
		else if(monthly >= 6250 && monthly <= 6749.9)
		sssContribution = 236.20;
		//6750 ~ 7249.9 = 254.30
		else if(monthly >= 6750 && monthly <= 7249.9 )
		sssContribution = 254.30;
		//7250 ~ 7749.9 = 272.50
		else if(monthly >= 7250 && monthly <= 7749.9 )
		sssContribution = 272.50;
		//7750 ~ 8249.9 = 290.70
		else if(monthly >= 7750 && monthly <=  8249.9 )
		sssContribution = 290.70;
		//8250 ~ 8749.9 = 308.80
		else if(monthly >= 8250 && monthly <= 8749.9)
		sssContribution = 308.80;
		//8750 ~ 9249.9 = 327.0
		else if(monthly >= 8750 && monthly <= 9249.9 )
		sssContribution = 327.00;
		//9250 ~ 9749.9 = 345.20
		else if(monthly >= 9250 && monthly <= 9749.9)
		sssContribution = 345.20;
		//9750 ~ 10249.9 = 363.30
		else if(monthly >= 9750 && monthly <= 10249.9)
		sssContribution = 363.30;
		//10250 ~ 10749.9 = 381.50
		else if(monthly >= 10250 && monthly <=  10749.9)
		sssContribution = 381.50;
		//10750 ~ 11249.9 = 399.70
		else if(monthly >= 10750 && monthly <= 11249.9)
		sssContribution = 399.70;
		//11250 ~ 11749.9 = 417.80
		else if(monthly >= 11250 && monthly <= 11749.9)
		sssContribution = 417.80;
		//11750 ~ 12249.9 = 436.0
		else if(monthly >= 11750 && monthly <= 12249.9)
		sssContribution = 436.00;
		//12250 ~ 12749.9 = 454.20
		else if(monthly >= 12250 && monthly <= 12749.9)
		sssContribution = 454.20;
		//12750 ~ 13249.9 = 472.30
		else if(monthly >= 12750 && monthly <= 13249.9)
		sssContribution = 472.30;
		//13250 ~ 13749.9 = 490.50
		else if(monthly >= 13250 && monthly <= 13749.9)
		sssContribution = 490.50;
		//13750 ~ 14249.9 = 508.70
		else if(monthly >= 13750 && monthly <= 14249.9 )
		sssContribution = 508.70;
		//14250 ~ 14749.9 = 526.80
		else if(monthly >= 14250 && monthly <= 14749.9)
		sssContribution = 526.80;
		//14750 ~ 15249.9 = 545.0
		else if(monthly >= 14750 && monthly <= 15249.9 )
		sssContribution = 545.00;
		//15250 ~ 15749.9 = 563.20
		else if(monthly >= 15250 && monthly <= 15749.9)
		sssContribution = 563.20;
		//15750 ~ higher = 581.30
		else if(monthly >= 15750)
		sssContribution = 581.30;
		
		sssContribution = sssContribution.toFixed(2);
		document.getElementById('sss').value = sssContribution;
	
}

/* AUTOMATED PHILHEALTH COMPUTATION BASED ON MONTHLY SALARY */
// function philhealthbox() {
// 	var philhealthCheck = document.getElementById('philhealthCheck');
// 	if(philhealthCheck.checked) {
// 			document.getElementById('txt_philhealth').readOnly = false;
// 			document.getElementById('txt_philhealth').value = '';
// 			document.getElementById('txt_philhealthAppear').style.display = 'block';
// 		}
// 		else {
// 			document.getElementById('txt_philhealth').readOnly = true;
// 			document.getElementById('txt_philhealth').value = '0.00';
// 			document.getElementById('txt_philhealthAppear').style.display = 'none';

// 		}
	// if (document.getElementById('philhealth').checked) 
	// {
	// 	var ratePerDay = document.getElementById('rate').value;
	// 	var monthlySalary = ratePerDay * 25;
	// 	var philhealthContribution = 0;

	// 	if(monthlySalary >= 1 && monthlySalary <= 8999.9)
	// 	philhealthContribution = 100.00;
	// 	//9000 ~ 9999.9 = 225
	// 	else if(monthlySalary >= 9000 && monthlySalary <= 9999.9)
	// 	philhealthContribution = 112.50;
	// 	//10000 ~ 10999.9 = 250
	// 	else if(monthlySalary >= 10000 && monthlySalary <= 10999.9)
	// 	philhealthContribution = 125.00;
	// 	//11000 ~ 11999.9 = 275
	// 	else if(monthlySalary >= 11000 && monthlySalary <= 11999.9)
	// 	philhealthContribution = 137.50;
	// 	//12000 ~ 12999.9 = 300
	// 	else if(monthlySalary >= 12000 && monthlySalary <= 12999.9)
	// 	philhealthContribution = 150.00;
	// 	//13000 ~ 13999.9 = 325
	// 	else if(monthlySalary >= 13000 && monthlySalary <= 13999.9)
	// 	philhealthContribution = 162.50;
	// 	//14000 ~ 14999.9 = 350
	// 	else if(monthlySalary >= 14000 && monthlySalary <= 14999.9)
	// 	philhealthContribution = 175.00;
	// 	//15000 ~ 15999.9 = 375
	// 	else if(monthlySalary >= 15000 && monthlySalary <= 15999.9)
	// 	philhealthContribution = 187.50;
	// 	//16000 ~ 16999.9 = 400
	// 	else if(monthlySalary >= 16000 && monthlySalary <= 16999.9)
	// 	philhealthContribution = 200.00;
	// 	//17000 ~ 17999.9 = 425
	// 	else if(monthlySalary >= 17000 && monthlySalary <= 17999.9)
	// 	philhealthContribution = 212.50;
	// 	//18000 ~ 18999.9 = 450
	// 	else if(monthlySalary >= 18000 && monthlySalary <= 18999.9)
	// 	philhealthContribution = 225.00;
	// 	//19000 ~ 19999.9 = 475
	// 	else if(monthlySalary >= 19000 && monthlySalary <= 19999.9)
	// 	philhealthContribution = 237.50;
	// 	//20000 ~ 20999.9 = 500
	// 	else if(monthlySalary >= 20000 && monthlySalary <= 20999.9)
	// 	philhealthContribution = 250.00;
	// 	//21000 ~ 21999.9 = 525
	// 	else if(monthlySalary >= 21000 && monthlySalary <= 21999.9)
	// 	philhealthContribution = 262.50;
	// 	//22000 ~ 22999.9 = 550
	// 	else if(monthlySalary >= 22000 && monthlySalary <= 22999.9)
	// 	philhealthContribution = 275.00;
	// 	//23000 ~ 23999.9 = 575
	// 	else if(monthlySalary >= 23000 && monthlySalary <= 23999.9)
	// 	philhealthContribution = 287.50;
	// 	//24000 ~ 24999.9 = 600
	// 	else if(monthlySalary >= 24000 && monthlySalary <= 24999.9)
	// 	philhealthContribution = 300.00;
	// 	//25000 ~ 25999.9 = 625
	// 	else if(monthlySalary >= 25000 && monthlySalary <= 25999.9)
	// 	philhealthContribution = 312.50;
	// 	//26000 ~ 26999.9 = 650
	// 	else if(monthlySalary >= 26000 && monthlySalary <= 26999.9 )
	// 	philhealthContribution = 325.00;
	// 	//27000 ~ 27999.9 = 675
	// 	else if(monthlySalary >= 27000 && monthlySalary <= 27999.9)
	// 	philhealthContribution = 337.50;
	// 	//28000 ~ 28999.9 = 700
	// 	else if(monthlySalary >= 28000 && monthlySalary <= 28999.9)
	// 	philhealthContribution = 350.00;
	// 	//29000 ~ 29999.9 = 725
	// 	else if(monthlySalary >= 29000 && monthlySalary <= 29999.9)
	// 	philhealthContribution = 362.50;
	// 	//30000 ~ 30999.9 = 750
	// 	else if(monthlySalary >= 30000 && monthlySalary <= 30999.9)
	// 	philhealthContribution = 375.00;
	// 	//31000 ~ 31999.9 = 775
	// 	else if(monthlySalary >= 31000 && monthlySalary <= 31999.9)
	// 	philhealthContribution = 387.50;
	// 	//32000 ~ 32999.9 = 800
	// 	else if(monthlySalary >= 32000 && monthlySalary <= 32999.9)
	// 	philhealthContribution = 400.00;
	// 	//33000 ~ 339999.9 = 825
	// 	else if(monthlySalary >= 33000 && monthlySalary <= 339999.9)
	// 	philhealthContribution = 412.50;
	// 	//34000 ~ 349999.9 = 850
	// 	else if(monthlySalary >= 34000 && monthlySalary <= 349999.9)
	// 	philhealthContribution = 425.00
	// 	//35000 ~ higher = 875
	// 	else if(monthlySalary >= 35000)
	// 	philhealthContribution = 437.50;

		

	// 	philhealthContribution = philhealthContribution.toFixed(2);
	// 	document.getElementById('txt_philhealth').value = philhealthContribution;
	// 	document.getElementById('txt_philhealthAppear').style.display = 'block';
	// } 
	// else 
	// {
	// 	document.getElementById('txt_philhealthAppear').style.display = 'none';
	// }
// }

/* DATE PICKER CONFIGURATIONS*/
$( "#dtpkr_addEmployee" ).datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat: 'mm-dd-yy',
	showAnim: 'blind',
	defaultDate: new Date(),
	yearRange: "-50:+0",
	maxDate: (0),
	beforeShow: function(){    
	$(".ui-datepicker").css('font-size', 10) 
	}
});
$( "#dtpkr_addDOB").datepicker({
	changeMonth: true,
	changeYear: true,
	dateFormat: 'mm-dd-yy',
	showAnim: 'blind',
	defaultDate: new Date(),
	yearRange: "-70:+0",
	maxDate: (0),
	beforeShow: function(){    
	$(".ui-datepicker").css('font-size', 10) 
	}
});
	// SITE FILTER 
	function site() {
		//alert('yea');
		if(document.URL.match(/site=([0-9]+)/))
		{
			var arr = document.URL.match(/site=([0-9]+)/)
			var siteUrl = arr[1];
			if(siteUrl)
			{
				localStorage.setItem("counter", 0);
			}
			else if(localStorage.getItem('counter') > 2)
			{
				localStorage.clear();
			}
		}
		var site = document.getElementById("site").value;
		var siteReplaced = site.replace(/\s/g , "+");
		localStorage.setItem("glob_site", siteReplaced);
		window.location.assign("employees.php?site="+siteReplaced+"&position="+localStorage.getItem('glob_position'));
	}

	// POSITION FILTER 
	function position() {
		if(document.URL.match(/position=([0-9]+)/))
		{
			var arr = document.URL.match(/position=([0-9]+)/)
			var positionUrl = arr[1];
			if(positionUrl)
			{
				localStorage.setItem("counter", 0);
			}
			else if(localStorage.getItem('counter') > 2)
			{
				localStorage.clear();
			}
		}
		var position = document.getElementById("position").value;
		var positionReplaced = position.replace(/\s/g , "+");
		localStorage.setItem("glob_position", positionReplaced);
		window.location.assign("employees.php?site="+localStorage.getItem("glob_site")+"&position="+positionReplaced);
	}

/* CLEAR FILTER */
function clearFilter() {
	localStorage.clear();
	window.location.assign("employees.php?site=null&position=null");
}

/* SEARCH FILTER */
function search(key) {
	var search = this.value;
	if(localStorage.getItem("search")==null)
	{
		localStorage.setItem("search", search);
	}	
	else
	{
		var find = localStorage.getItem("search");
		var findSearch = find + search;
		localStorage.setItem("search", findSearch);
	}
	window.location.assign("employees.php?site=null&position=null&search="+localStorage.getItem("search"));
}


     function validatenumber(evt) {
  		var theEvent = evt || window.event;
 		var key = theEvent.keyCode || theEvent.which;
 		key = String.fromCharCode( key );
  		var regex = /[0-9]|\./;
  		if( !regex.test(key) ) {
   			 theEvent.returnValue = false;
   		if(theEvent.preventDefault) 
   			theEvent.preventDefault();
 		}
	}
	function validateletter(evt) {
  		var theEvent = evt || window.event;
 		var key = theEvent.keyCode || theEvent.which;
 		key = String.fromCharCode( key );
  		var regex = /^[a-zA-Z ]*$/;
  		if( !regex.test(key) ) {
   			 theEvent.returnValue = false;
   		if(theEvent.preventDefault) 
   			theEvent.preventDefault();
  		}
	}

















