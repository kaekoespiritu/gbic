	
	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");

	$( document ).ready(function() {
    	if($('#outstandingPayable').val()){
    		$('#amountToPay').removeAttr("readonly");
    	}
	});

	

	function addRow(){
		var toolsLength = document.getElementsByName('toolname[]').length;
		
		var ct = parseInt(toolsLength);
		
		var div1 = document.createElement('div');
		div1.id = ct;
		div1.setAttribute('name','toolsRow[]');

		var delLink = '<div class="col-md-1 col-lg-1 nopadding">'+
		'<a class="btn-sm btn btn-danger" name="rowDelete[]" onclick="deleteRow('+ ct +')">'+
		'<span class="glyphicon glyphicon-minus"></span>'+
		'</a>'+
		'</div>';

		var template ='<div class="col-md-4 col-lg-4">' +
		'<input type="text" id="toolstemp" name="toolname[]" class="form-control input-sm" onchange="checkName(this)">' +
		'</div>' +
		'<div class="col-md-4 col-lg-4"><input id="pricetemp" name="toolprice[]" class="form-control input-sm toolpricetemp" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)">' +
		'</div>' + 
		'<div class="col-md-3 col-lg-3">' + 
		'<input type="text" id="quantity" name="toolquantity[]" class="form-control input-sm" onkeypress="validateprice(event)" onchange="getTotal(this)" onblur="addDecimal(this)">' +
		'</div>';

		div1.innerHTML = delLink + template;
		document.getElementById('toolform').appendChild(div1);

	}

	function deleteRow(eleId){
		var ele = document.getElementById(eleId);
		var parentEle = document.getElementById('toolform');
		parentEle.removeChild(ele);

		getTotal();

		var toolsLength = document.getElementsByName('toolsRow[]').length;
		if(toolsLength > 1)
		{
			for(var count = 0; count < toolsLength; count++)
			{

				document.getElementsByName('toolsRow[]')[count].setAttribute('id',count+1);
				document.getElementsByName('rowDelete[]')[count].setAttribute('onclick','deleteRow('+(count+1)+')');
			}
		}
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
		var modalValue = document.getElementById("newAddVale").value;
		var addVale = parseFloat(modalValue).toFixed(2); 
		//var compute = parseFloat(addVale);
		var child = document.getElementById('newValeText');
		var oNewVale = document.getElementsByName('newVale')[0].value;//this is the old new-vale
		var modalNewVale = document.getElementsByName('newValeAdded')[0].value;//this is the new-vale from Modal

		modalNewVale = modalValue;
		//checker for N/A value

		if(addVale > 0)
		{
			if(oNewVale != "")
			{
				if(child.innerHTML != "N/A")
				{
					var newVale = parseFloat(oNewVale) + parseFloat(addVale);
					var oNewVale = parseFloat(oNewVale).toFixed(2);
					newVale = parseFloat(newVale).toFixed(2);
					child.innerHTML = null;
					child.innerHTML += addCommas(oNewVale)+"<br><u>+ "+addCommas(addVale)+"</u><br>"+addCommas(newVale);
				}
				else
				{
					child.innerHTML = addCommas(addVale);
				}
				
			}
			else
			{
				child.innerHTML = addCommas(addVale);
			}
			// Show value to payroll page	
		}
		else
		{
			child.innerHTML = oNewVale;
			if(modalValue === "")
			child.innerHTML = "N/A";
		}
		
		// Save to hidden input for database access
		var remarks = document.getElementById('newValeRemarks').value;
		document.querySelector(".addRemarks").value = remarks;

		var saveToAdd = document.querySelector(".added");
		saveToAdd.value = modalValue;
	}

	function getTotal(evt) {

	// Add sum of all items and show amount to deduct
	var totalcost = 0;
	var length = document.getElementsByName('toolname[]').length;
	var toolprices = document.getElementsByName('toolprice[]');
	var names = document.getElementsByName('toolname[]');
	var quantity = document.getElementsByName('toolquantity[]');
	var amountToPay = document.getElementById('amountToPay');

	// For the first tool
	if(document.getElementById('price').value!==""){ // If there is a cost
		names[0].setAttribute('required','');
		if(names[0].value!==""){
			names[0].parentElement.classList.add('has-success');
		}
		else {
			names[0].parentElement.classList.add('has-error');
		}
	}
	else { // If there is no cost added
		names[0].removeAttribute('required','');
		if(names[0].parentElement.classList.contains('has-error')){
			names[0].parentElement.classList.remove('has-error');
		}
		if(names[0].parentElement.classList.contains('has-success')){
			names[0].parentElement.classList.remove('has-success');
		}	
	}

	// Looping through the dynamic list of tools
	if( length > 1 ) { // If there are many tools 
		for(var i = 1; i < length; i++) {
			if(toolprices[i].value!="") {
				totalcost += (parseFloat(toolprices[i].value) * parseInt(quantity[i].value));
				
				// Require name if there is an amount placed
				names[i].setAttribute('required','');
				if(names[i].value!=""){
					names[i].parentElement.classList.add('has-success');
				}
				else {
					names[i].parentElement.classList.add('has-error');
				}

			}
			else {
				names[i].removeAttribute('required','');
					if(names[i].parentElement.classList.contains('has-error')){
						names[i].parentElement.classList.remove('has-error');
					}
					if(names[i].parentElement.classList.contains('has-success')){
						names[i].parentElement.classList.remove('has-success');
					}	
					break;
			}
		}

		totalcost += (parseFloat(document.getElementById('price').value) * parseInt(document.getElementById('quantity').value));
	}
	else if(length == 1) { // If only 1 tool was entered
		totalcost += (parseFloat(document.getElementById('price').value) * parseInt(document.getElementById('quantity').value));
	}

	// Only allowing numbers and null to be displayed
	if(!isNaN(totalcost)) {
		document.getElementById('totalcost').value = totalcost.toFixed(2);
	}
	else {
		document.getElementById('totalcost').value = "";
	}

	// Remove readonly from amount to pay field
	if(amountToPay.hasAttribute('readonly') && totalcost !== ""){
		amountToPay.removeAttribute('readonly');
		amountToPay.parentElement.classList.add('has-error');
		//amountToPay.setAttribute('required','');
	}
	else if (isNaN(totalcost)){
		amountToPay.setAttribute('readonly','');
		//amountToPay.removeAttribute('required','');
		if(amountToPay.parentElement.classList.contains('has-error')){
			amountToPay.parentElement.classList.remove('has-error');
		}
		if(amountToPay.parentElement.classList.contains('has-success')){
			amountToPay.parentElement.classList.remove('has-success');
		}
	}
	
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
	var oldvale = document.getElementById('oldValeDeduct');
	var oldvaleVal = document.getElementById('oldvaleValue');;

	if(!sssVal){
		// Set disabled to input field
		sss.setAttribute('readonly','');
	}

	if(!pagibigVal){
		// Set disabled to input field
		pagibig.setAttribute('readonly', '');
	}

	if(!oldvaleVal){
		// Set disabled to input field
		oldvale.setAttribute('readonly', '');	
	}

}

function setsssLimit(value){
	var sss = document.getElementById('sssValue').innerHTML;
	var num = sss.replace(',','');
	var classList = value.parentElement.classList;

	// Alert error
	if(parseFloat(value.value) > parseFloat(num) ){
		alert("You have entered an amount greater than the loaned amount. Please re-enter an amount less than or equal to " + num + ".");
		value.value = "";
	}

	// Update validation state
	if(classList.contains('has-error') && value.value != ""){
		classList.remove('has-error');
		classList.add('has-success');
	}
	else {
		if(classList.contains('has-success')){
			classList.remove('has-success');
			classList.add('has-error');
		}
	}

}

function setpagibigLimit(value){
	var pagibig = document.getElementById('pagibigValue').innerHTML;
	var num = pagibig.replace(',','');
	var classList = value.parentElement.classList;

	// Alert error
	if(parseFloat(value.value) > parseFloat(num) ){
		alert("You have entered an amount greater than the loaned amount. Please re-enter an amount less than or equal to " + num + ".");
		value.value = "";
	}

	// Update validation state
	if(classList.contains('has-error') && value.value != ""){
		classList.remove('has-error');
		classList.add('has-success');
	}
	else {
		if(classList.contains('has-success')){
			classList.remove('has-success');
			classList.add('has-error');
		}
	}

}

function setoldvaleLimit(value) { 
	var oldvale = document.getElementById('oldvaleValue').innerHTML;
	var num = oldvale.replace(',','');
	var classList = value.parentElement.classList;

	if(parseFloat(value.value) > parseFloat(num)) {
		alert("You have entered an amount greater than the loaned amount. Please re-enter an amount less than or equal to " + num + ".");
		value.value = "";
	}

	// Update validation state
	if(classList.contains('has-error') && value.value != ""){
		classList.remove('has-error');
		classList.add('has-success');
	}
	else {
		if(classList.contains('has-success')){
			classList.remove('has-success');
			classList.add('has-error');
		}
	}
}

function settotalLimit(value){
	var total = document.getElementById('totalcost').value;
	var OPtotal = document.getElementById('outstandingPayable').value;
	var num = total.replace(',','');
	var OPnum = OPtotal.replace(',','');

	var payable = num + OPnum;

	var parent = value.parentElement.classList;

	if(parseFloat(value.value) > parseFloat(payable)) {
		alert("You have entered an amount greater than the loaned amount. Please re-enter an amount less than or equal to " + payable + ".");
		value.value = "";
	}

	if(value.value!=="") {
		parent.remove('has-error');
		parent.add('has-success');
	}
	else {
		if(parent.contains('has-success')){
			parent.remove('has-success');
			parent.add('has-error');
		}
	}
}

function checkName(value) {
	var parent = value.parentElement.classList;

	if(parent.contains('has-error') && value.value !== ""){
		parent.remove('has-error');
		parent.add('has-success');
	}
	else {
		if(parent.contains('has-success')){
			parent.remove('has-success');
			parent.add('has-error');
		}
	}
}