<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body style="font-family: QuicksandMed;">
	<div class="container-fluid">
		<?php
		require_once('directives/modals/addEmployee.php');

		?>
		<div class="modal fade" id="editEmployee" role="dialog">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div id="fetch-modal">
					</div>
				</div>
			</div>
		</div>
		<!-- NAVIGATION BAR -->
		<?php
		require_once("directives/nav.php");
		?>

		<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-1 pull-down">
					<div class="input-group">
						<form method="post" action="" id="search_form">
							<input type="text" placeholder="Search" id="search_box" name="txt_search" onkeypress="enter(enter)" class="form-control">
						</form>
					</div>
				</div>
				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-5 col-md-pull-1 pull-down text-right">
					Filter by:
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<?php
								$position = "SELECT position FROM job_position";
								$position_query = mysql_query($position);

								while($row_position = mysql_fetch_assoc($position_query))
								{
									$positionReplaced = str_replace('/+/', ' ', $_GET['position']);
									$position = mysql_real_escape_string($row_position['position']);
									if($position == $positionReplaced)
									{
										Print '<option value="'. $position .'" selected="selected">'. $position .'</option>';
									}
									else
									{
									Print '<option value="'. $position .'">'. $position .'</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="btn-group">
						<select class="form-control" id="site" onchange="site()">
							<option hidden>Site</option>
							<?php
								$site = "SELECT location FROM site";
								$site_query = mysql_query($site);

								while($row_site = mysql_fetch_assoc($site_query))
								{
									$siteReplaced = str_replace('/+/', ' ', $_GET['site']);
									if($row_site['location'] == $siteReplaced)
									{
										Print '<option value="'. $row_site['location'] .'" selected="selected">'. $row_site['location'] .'</option>';
									}
									else
									{
										Print '<option value="'. $row_site['location'] .'">'. $row_site['location'] .'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
				<div class="col-md-1 col-md-pull-1 pull-down pull-left">
					<button type="button" class="btn btn-danger" onclick="clearFilter()">Clear Filters</button>
					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEmployee">Add new Employee</button>
				</div>
			</div>
		</div>

		<!-- EMPLOYEE TABLE -->
		<div class="row pull-down">
			<div class="col-md-10 col-md-offset-1">
				<table class="table table-bordered" style="background-color:white;">
					<tr>
						<td>Employee ID</td>
						<td>Name</td>
						<td>Position</td>
						<td>Site</td>
						<td>Actions</td>
					</tr>

					<div id="change_table">
						<?php
						$emp_query = "SELECT * FROM employee ORDER BY site";
						$emp_display = mysql_query($emp_query);
//--------Search
						if(isset($_POST['txt_search']))
						{
							$find = mysql_real_escape_string($_POST['txt_search']);
							$search = "SELECT empid, firstname, lastname, position, site FROM employee WHERE 
											empid LIKE '%$find%' OR 
											firstname LIKE '%$find%' OR 
											lastname LIKE '%$find%' OR
											position LIKE '%$find%' OR
											site LIKE '%$find%'";
							$searchQuery = mysql_query($search);
							
								while($search_row = mysql_fetch_assoc($searchQuery))
								{
								Print "	<tr>
											<td>".$search_row['empid']."</td>
											<td>".$search_row['firstname']." ".$search_row['lastname']."</td>
											<td>".$search_row['position']."</td>
											<td>".$search_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$search_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							
						}
//--------site		
						
						else if($_GET['site'] != "null")
						{
							
							$site = $_GET['site'];
							$siteReplaced = str_replace('/+/', ' ', $site);
							if($_GET['position'] != "null")
							{
								
								$position = $_GET['position'];
								$positionReplaced = str_replace('/+/', ' ', $position);
								$pos_query = "SELECT * FROM employee WHERE position = '$positionReplaced' AND site = '$siteReplaced'";
								$position_query = mysql_query($pos_query);
								while($PosEmp_row = mysql_fetch_assoc($position_query))
								{
								Print "	<tr>
											<td>".$PosEmp_row['empid']."</td>
											<td>".$PosEmp_row['firstname']." ".$PosEmp_row['lastname']."</td>
											<td>".$PosEmp_row['position']."</td>
											<td>".$PosEmp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$PosEmp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}
							else
							{
								
								$query = "SELECT * FROM employee WHERE site = '$siteReplaced'";
								$site_query = mysql_query($query);
								while($site_row = mysql_fetch_assoc($site_query))
								{
								Print "	<tr>
											<td>".$site_row['empid']."</td>
											<td>".$site_row['firstname']." ".$site_row['lastname']."</td>
											<td>".$site_row['position']."</td>
											<td>".$site_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$site_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}
							
						} 
						
//--------position						
						else if($_GET['position'] != "null")
						{
					
							$position = $_GET['position'];
							$positionReplaced = str_replace('/+/', ' ', $position);
							if($_GET['site'] != "null")
							{
							
								$site = $_GET['site'];
								$siteReplaced = str_replace('/+/', ' ', $site);
								$pos_query = "SELECT * FROM employee WHERE position = '$positionReplaced' AND site = '$siteReplaced'";
								$position_query = mysql_query($pos_query);
								while($PosEmp_row = mysql_fetch_assoc($position_query))
								{
								Print "	<tr>
											<td>".$PosEmp_row['empid']."</td>
											<td>".$PosEmp_row['firstname']." ".$PosEmp_row['lastname']."</td>
											<td>".$PosEmp_row['position']."</td>
											<td>".$PosEmp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$PosEmp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}

							else
							{
								$query = "SELECT * FROM employee WHERE position = '$positionReplaced'";
								$position_query = mysql_query($query);
								while($position_row = mysql_fetch_assoc($position_query))
								{
								Print "	<tr>
											<td>".$position_row['empid']."</td>
											<td>".$position_row['firstname']." ".$position_row['lastname']."</td>
											<td>".$position_row['position']."</td>
											<td>".$position_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$position_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
								}
							}
						}
//-------default
						else
						{
						
							while($emp_row = mysql_fetch_assoc($emp_display))
							{
								Print "	<tr>
											<td>".$emp_row['empid']."</td>
											<td>".$emp_row ['firstname']." ".$emp_row['lastname']."</td>
											<td>".$emp_row['position']."</td>
											<td>".$emp_row['site']."</td>
											<td>
												<button type='button' class='btn btn-default' onclick='Edit(\"".$emp_row["empid"]."\")' id='editEmployee'>View / Edit details</button>
											</td>
										</tr>";
							}
						}
						?>
					</div>
				</table>
			</div>	
		</div>


	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->

	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	
	<script>
		function enter(e) {
		    if (e.keyCode == 13) {
		        document.getElementById('search_form').submit();
		    }
		}
		function sssbox() {
    		if (document.getElementById('sss').checked) 
    		{
    			var ratePerDay = document.getElementById('rate').value;
	            var monthly = ratePerDay * 24;
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
				sssContribution = 109.0;
				//3250 ~ 3749.9 = 127.20
				else if(monthly >= 3250 && monthly <= 3749.9)
				sssContribution = 127.20;
				//3750 ~ 4249.9 = 145.30
				else if(monthly >= 3750 && monthly <= 4249.9)
				sssContribution = 145.30;
				//4250 ~ 4749.9 = 163.50
				else if(monthly >= 4250 && monthly <= 4749.9 )
				sssContribution = 163.50;
				//4750 ~ 5249.9 = 181.70
				else if(monthly >= 4750 && monthly <= 5249.9)
				sssContribution = 181.70;
				//5250 ~ 5749.9 = 199.80
				else if(monthly >= 5250 && monthly <= 5749.9)
				sssContribution = 199.80;
				//5750 ~ 6249.9 = 218.0
				else if(monthly >= 5750 && monthly <= 6249.9)
				sssContribution = 218.0;
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
				sssContribution = 327.0;
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
				sssContribution = 436.0;
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
				sssContribution = 545.0;
				//15250 ~ 15749.9 = 563.20
				else if(monthly >= 15250 && monthly <= 15749.9)
				sssContribution = 563.20;
				//15750 ~ higher = 581.30
				else if(monthly >= 15750)
				sssContribution = 581.30;
				document.getElementById('txt_sss').value = sssContribution;
        		document.getElementById('txt_sssAppear').style.display = 'block';
    		} 
    		else 
    		{
        		document.getElementById('txt_sssAppear').style.display = 'none';
    		}
    	}
    	function philhealthbox() {
    		if (document.getElementById('philhealth').checked) 
    		{
    			var ratePerDay = document.getElementById('rate').value;
	            var monthlySalary = ratePerDay * 24;
	            var philhealthContribution = 0;

    			if(monthlySalary >= 1 && monthlySalary <= 8999.9)
				philhealthContribution = 200;
				//9000 ~ 9999.9 = 225
				else if(monthlySalary >= 9000 && monthlySalary <= 9999.9)
				philhealthContribution = 225;
				//10000 ~ 10999.9 = 250
				else if(monthlySalary >= 10000 && monthlySalary <= 10999.9)
				philhealthContribution = 250;
				//11000 ~ 11999.9 = 275
				else if(monthlySalary >= 11000 && monthlySalary <= 11999.9)
				philhealthContribution = 222755;
				//12000 ~ 12999.9 = 300
				else if(monthlySalary >= 12000 && monthlySalary <= 12999.9)
				philhealthContribution = 300;
				//13000 ~ 13999.9 = 325
				else if(monthlySalary >= 13000 && monthlySalary <= 13999.9)
				philhealthContribution = 325;
				//14000 ~ 14999.9 = 350
				else if(monthlySalary >= 14000 && monthlySalary <= 14999.9)
				philhealthContribution = 350;
				//15000 ~ 15999.9 = 375
				else if(monthlySalary >= 15000 && monthlySalary <= 15999.9)
				philhealthContribution = 375;
				//16000 ~ 16999.9 = 400
				else if(monthlySalary >= 16000 && monthlySalary <= 16999.9)
				philhealthContribution = 400;
				//17000 ~ 17999.9 = 425
				else if(monthlySalary >= 17000 && monthlySalary <= 17999.9)
				philhealthContribution = 425;
				//18000 ~ 18999.9 = 450
				else if(monthlySalary >= 18000 && monthlySalary <= 18999.9)
				philhealthContribution = 450;
				//19000 ~ 19999.9 = 475
				else if(monthlySalary >= 19000 && monthlySalary <= 19999.9)
				philhealthContribution = 475;
				//20000 ~ 20999.9 = 500
				else if(monthlySalary >= 20000 && monthlySalary <= 20999.9)
				philhealthContribution = 500;
				//21000 ~ 21999.9 = 525
				else if(monthlySalary >= 21000 && monthlySalary <= 21999.9)
				philhealthContribution = 525;
				//22000 ~ 22999.9 = 550
				else if(monthlySalary >= 22000 && monthlySalary <= 22999.9)
				philhealthContribution = 550;
				//23000 ~ 23999.9 = 575
				else if(monthlySalary >= 23000 && monthlySalary <= 23999.9)
				philhealthContribution = 575;
				//24000 ~ 24999.9 = 600
				else if(monthlySalary >= 24000 && monthlySalary <= 24999.9)
				philhealthContribution = 600;
				//25000 ~ 25999.9 = 625
				else if(monthlySalary >= 25000 && monthlySalary <= 25999.9)
				philhealthContribution = 625;
				//26000 ~ 26999.9 = 650
				else if(monthlySalary >= 26000 && monthlySalary <= 26999.9 )
				philhealthContribution = 650;
				//27000 ~ 27999.9 = 675
				else if(monthlySalary >= 27000 && monthlySalary <= 27999.9)
				philhealthContribution = 675;
				//28000 ~ 28999.9 = 700
				else if(monthlySalary >= 28000 && monthlySalary <= 28999.9)
				philhealthContribution = 700;
				//29000 ~ 29999.9 = 725
				else if(monthlySalary >= 29000 && monthlySalary <= 29999.9)
				philhealthContribution = 725;
				//30000 ~ 30999.9 = 750
				else if(monthlySalary >= 30000 && monthlySalary <= 30999.9)
				philhealthContribution = 750;
				//31000 ~ 31999.9 = 775
				else if(monthlySalary >= 31000 && monthlySalary <= 31999.9)
				philhealthContribution = 775;
				//32000 ~ 32999.9 = 800
				else if(monthlySalary >= 32000 && monthlySalary <= 32999.9)
				philhealthContribution = 800;
				//33000 ~ 339999.9 = 825
				else if(monthlySalary >= 33000 && monthlySalary <= 339999.9)
				philhealthContribution = 825;
				//34000 ~ 349999.9 = 850
				else if(monthlySalary >= 34000 && monthlySalary <= 349999.9)
				philhealthContribution = 850;
				//35000 ~ higher = 875
				else if(monthlySalary >= 35000)
				philhealthContribution = 875;
				document.getElementById('txt_philhealth').value = philhealthContribution;
        		document.getElementById('txt_philhealthAppear').style.display = 'block';
    		} 
    		else 
    		{
        		document.getElementById('txt_philhealthAppear').style.display = 'none';
    		}
		}
		document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

		$( "#dtpkr_addEmployee" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: 'mm-dd-yy',
			showAnim: 'blind',
			defaultDate: new Date(),
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
			beforeShow: function(){    
           $(".ui-datepicker").css('font-size', 10) 
       		}
		});


		function site() {
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
		function clearFilter() {
			localStorage.clear();
			window.location.assign("employees.php?site=null&position=null");
		}
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
	</script>

	<script rel="javascript" src="js/dropdown.js"></script>
	<script>
	function Edit(id) {
	
	  	window.location.assign("editEmployee.php?empid="+id);
	}

	</script>

</body>
</html>
