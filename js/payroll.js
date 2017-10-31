	document.getElementById("payroll").setAttribute("style", "background-color: #10621e;");
	var ct = 1;
	function addRow(){
		ct++;
		var div1 = document.createElement('div');
		div1.id = ct;
		var delLink = '<div class="col-md-1" style="padding:0px"><button class="btn-sm btn btn-danger" onclick="deleteRow('+ ct +')"><span class="glyphicon glyphicon-minus"></span></button></div>';
		var template = '<label class="control-label col-md-2" for="tools">Name</label> <div class="col-md-4"><input type="text" id="toolstemp" name="toolname[]" class="form-control input-sm" onkeypress="validateletter(event)"> </div><label class="control-label col-md-1" for="price">Cost</label><div class="col-md-4"><input type="text" id="pricetemp" name="toolprice[]" class="form-control input-sm toolpricetemp" onkeypress="validateprice(event)" onchange="getTotal()"></div>';
		div1.innerHTML = delLink + template;
		document.getElementById('toolform').appendChild(div1);
	}
	function deleteRow(eleId){
		var ele = document.getElementById(eleId);
		var parentEle = document.getElementById('toolform');
		parentEle.removeChild(ele);
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
	function addCommas(nStr)
	{
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
// Adding new vale and displaying an addition format
function addvale() {
	// Exception when Vale is N/A
	
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
	
	if( length > 1 )
	{
		for(var i = 0; i < length-1; i++){
			if(toolprices[i].value!="")
			{
				totalcost+=parseFloat(toolprices[i].value);
				console.log("Total Cost = " + totalcost + " | toolpricetemp = " + parseInt(toolprices[i].value));
			}
			else
			{
				break;
			}
		}
		totalcost+= parseFloat(document.getElementById('price').value);
		console.log(totalcost);
	}
	else
	{
		totalcost = parseFloat(document.getElementById('price').value);
		console.log(totalcost);
	}
	//console.log("value: "+ evt);
	if(evt != '')
	{
		document.getElementsByName('amountToPay')[0].required = true;
	}
	else
	{
		document.getElementsByName('amountToPay')[0].required = false;
	}
	document.getElementById('totalcost').value = totalcost.toFixed(2);
	
}
function addDecimal(val){
	// console.log(this.parentNode.nodeName);
	// var toolname = this.parentNode.querySelector('#tools').value;
	// console.log("Tool name = "+toolname);
	var val = parseInt(val);
	var number = val.toFixed(2);
	this.value = number; 
}