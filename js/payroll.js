	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

	var ct = 1;

	function addRow(){
		ct++;
		var div1 = document.createElement('div');
		div1.id = ct;

		var delLink = '<div class="col-md-1 nopadding">'+
		'<button class="btn-sm btn btn-danger" onclick="deleteRow('+ ct +')">'+
		'<span class="glyphicon glyphicon-minus"></span>'+
		'</button>'+
		'</div>';

		var template = '<label class="control-label col-md-2" for="tools">Name</label>' +
		'<div class="col-md-4">' +
		'<input type="text" id="toolstemp" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)">' +
		'</div>' +
		'<label class="control-label col-md-1" for="price">Cost</label>' +
		'<div class="col-md-4"><input type="number" id="pricetemp" name="toolprice[]" class="form-control input-sm toolpricetemp" onkeypress="validateprice(event)" onchange="getTotal()" onblur="addDecimal(this)">' +
		'</div>';

		div1.innerHTML = delLink + template;
		document.getElementById('toolform').appendChild(div1);
	}

	function deleteRow(eleId){
		var ele = document.getElementById(eleId);
		var parentEle = document.getElementById('toolform');
		parentEle.removeChild(ele);

		getTotal();

		console.log(parentEle);
		console.log(ele);
		console.log(parentEle.children);
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

	function validateprice(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode( key );
		var regex = /^[0-9.,]+$/;
		if( !regex.test(key) ) {
			theEvent.returnValue = false;
			if(theEvent.preventDefault) 
				theEvent.preventDefault();
		}
	}

	function addCommas(nStr) {
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

	// Adding new vale
	function addvale() {

		// Get current amount in vale
		var original = document.querySelector(".vale");
		var oldVale = original.innerHTML;
		var division = oldVale.split(',');
		var len = division.length;
		var builder="";
		for(var a=0; a<len; a++)
		{
			
			builder += division[a];
			
		}
		
		builder = parseFloat(builder).toFixed(2);
		// Get vale from modal and format to currency
		var modalValue = document.querySelector("#newAddVale").value;
		var addVale = parseFloat(modalValue).toFixed(2); 
		var compute = parseFloat(addVale);
		var child = document.getElementById('newValeText');
		if(compute > 0)
		{
			// Show value to payroll page
			child.innerHTML = addCommas(addVale);
			console.log(child);
		}
		else
		{
			child.innerHTML = "N/A";
		}
		// Save to hidden input for database access
		if(document.querySelector('.added').value)
		{
			var saveToAdd = document.querySelector(".added");
			saveToAdd.value = modalValue;
		}

	}

	function getTotal(evt) {

	// Add sum of all items and show amount to deduct
	var totalcost = 0;
	var length = document.getElementsByClassName('toolpricetemp').length;
	var toolprices = document.getElementsByClassName('toolpricetemp');

	console.log("Total Cost = " + totalcost + " | Length of NodeList =  " + length);
	
	// Looping through the dynamic list of tools
	if( length >= 1 ) { // If there are many tools 
		for(var i = 0; i < length; i++) {
			if(toolprices[i].value!="") {
				totalcost += parseFloat(toolprices[i].value);
				console.log("Total Cost = " + totalcost + " | toolpricetemp = " + parseInt(toolprices[i].value));
				if(!document.getElementById(i+1)){
					totalcost -= parseFloat(toolprices[i].value);
					console.log("Total Cost = " + totalcost + " | toolpricetemp = " + parseInt(toolprices[i].value) + " | div = " + i);
				}
			}
			else {
				break;
			}
		}
		totalcost+= parseFloat(document.getElementById('price').value);
		console.log("Inside IF: " + totalcost);
	}
	else { // If only 1 tool was entered
		totalcost = parseFloat(document.getElementById('price').value);
		console.log("Inside ELSE: " + totalcost);
	}

	document.getElementById('totalcost').value = totalcost.toFixed(2);
	
}

function addDecimal(val){
	if(val.value !== ""){
		val.value = parseFloat(val.value).toFixed(2);
	}
	else {
		return "";
	}
}

function checkloans(){
	var sssVal = document.getElementById('sssValue');
	var sss = document.getElementById('sssDeduct');
	var pagibigVal = document.getElementById('pagibigValue');
	var pagibig = document.getElementById('pagibigDeduct');

	if(!sssVal){
		// Set disabled to input field
		sss.setAttribute('readonly','');
	}

	if(!pagibigVal){
		// Set disabled to input field
		pagibig.setAttribute('readonly', '');
	}
}

function setsssLimit(value){
	var sss = document.getElementById('sssValue').innerHTML;
	var num = sss.replace(',','');

	if(parseFloat(value.value) > parseFloat(num) ){
		alert("You have entered an amount greater than the loaned amount.");
		value.value = "";
	}
}

function setpagibigLimit(value){
	var pagibig = document.getElementById('pagibigValue').innerHTML;
	var num = pagibig.replace(',','');

	if(parseFloat(value.value) > parseFloat(num) ){
		alert("You have entered an amount greater than the loaned amount.");
		value.value = "";
	}

}

function setoldvaleLimit(value) { 
	var oldvale = document.getElementById('oldvaleValue').innerHTML;
	var num = oldvale.replace(',','');

	console.log(oldvale);

	if(parseFloat(value.value) > parseFloat(num)) {
		alert("You have entered an amount greater than the loaned amount.");
		value.value = "";
	}
}

function settotalLimit(value){
	var total = document.getElementById('totalcost').value;
	var num = total.replace(',','');

	if(parseFloat(value.value) > parseFloat(num)) {
		alert("You have entered an amount greater than the loaned amount.");
		value.value = "";
	}
}