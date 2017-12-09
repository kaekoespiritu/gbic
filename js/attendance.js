document.getElementById("attendance").setAttribute("style", "background-color: #10621e;");
	

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

	fittext();