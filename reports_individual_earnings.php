<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
	include("pagination/reports_individual_function.php");//For pagination
	if(isset($_GET['type']) && isset($_GET['period']))
	{
		// Allow only these types
		switch($_GET['type'])
		{
			case "Attendance": break;
			case "Payroll": break;
			case "Loans": break;
			case "Payslip": break;
			case "Contributions": break;
			case "Earnings": break;
			default: Print Print "<script>window.location.assign('index.php')</script>";
		}
		// Allow only these periods
		switch($_GET['period'])
		{
			case "week": break;
			case "month": break;
			case "year": break;
			default: Print Print "<script>window.location.assign('index.php')</script>";
		}
	}
	else
	{
		Print "<script>window.location.assign('index.php')</script>";
	}
	if(!isset($_GET['site']) && !isset($_GET['position']))
	{
		Print "<script>window.location.assign('index.php')</script>";
	}
	//for pagination
	$site_page = $_GET['site'];
	$position_page = $_GET['position'];
	$statement = "";
	$period = $_GET['period'];
	$reportType = $_GET['type'];
?>
<html>
<head>
	<title>Payroll</title>
	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">

	<!-- For pagination -->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link href="pagination/css/pagination.css" rel="stylesheet" type="text/css" />
	<link href="pagination/css/A_green.css" rel="stylesheet" type="text/css" />
</head>
<body style="font-family: Quicksand;">

	<div class="container-fluid">

		<?php
		require_once("directives/nav.php");
		?>

		<div class="container pull-down">
			<div class="col-md-12 pull-down">
				<h2>Individual <?php Print $_GET['type']?> Report</h2>
			</div>

			<!-- SEARCH BAR, ADD EMPLOYEE, FILTER EMPLOYEES -->
			<div class="row">
				<div class="col-md-3 col-md-offset-1 pull-down">
					<div class="form-group">
						<input type="text" placeholder="Search" id="search_box" name="txt_search"  class="form-control" autocomplete="off">
					</div>
					
				</div>
			<!-- FOR LIVE SEARCH -->
				<input type="hidden" id="report_type" value="<?php Print $reportType?>">
				<input type="hidden" id="period" value="<?php Print $period?>">
				<input type="hidden" id="position" value="<?php Print $position_page?>">
				<input type="hidden" id="site" value="<?php Print $site_page?>">



				<!-- FILTER EMPLOYEE BY POSITION -->
				<div class="col-md-7 pull-down text-right">
					Filter by:
					<!-- POSITION DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="position" onchange="position()">
							<option hidden>Position</option>
							<?php 
								$position = "SELECT * FROM job_position WHERE active = '1'";
								$positionQuery = mysql_query($position);

								while($positionArr = mysql_fetch_assoc($positionQuery))
								{
									Print "<option value='".$positionArr['position']."'>".$positionArr['position']."</option>";
								}

							?>
						</select>
					</div>
					<!-- END OF POSITION DROPDOWN -->
					<!-- SITES DROPDOWN -->
					<div class="btn-group">
						<select class="form-control" id="site" onchange="site()">
							<option hidden>Site</option>
							<?php 
								$site = "SELECT * FROM site WHERE active = '1'";
								$siteQuery = mysql_query($site);

								while($siteArr = mysql_fetch_assoc($siteQuery))
								{
									Print "<option value='".$siteArr['location']."'>".$siteArr['location']."</option>";
								}

							?>
						</select>
					</div>
					<!-- END OF SITES DROPDOWN -->
					<button type="button" class="btn btn-danger text-right" onclick="clearFilter()">Clear Filters</button>
				</div>
				<!-- ACTION BUTTONS FOR FILTERS -->
				<!-- END OF ACTION BUTTONS FOR FILTERS-->
			</div>

			
						<div id="search_result" class="" >
							
						</div>
							
						
			<?php
				echo "<div id='pagingg' >";
				if($statement && $limit && $page && $site_page && $position_page && $reportType && $period)
					echo pagination($statement,$limit,$page, $site_page, $position_page, $reportType, $period);
				echo "</div>";
			?>
		</div>
	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");


		$(document).ready(function(){
			var period = $('#period').val();
			load_data("",period);
			function load_data(search, period)
			{

			  	$.ajax({
			   		url:"livesearch_reports.php",
			   		method:"POST",
			   		data:{
			   			search: search,
			   			period:period

			   		},
			   		success:function(data)
			   		{
			    		$('#search_result').html(data);
			   		}
			  	});
			}
			$('#search_box').keyup(function(){
			  	var search = $(this).val();
			  	
			   		load_data(search, period);
			  	
			});
		});

		function viewPayrollBtn(id, period) {
			window.location.assign("reports_individual_payroll.php?empid="+id+"&period="+period);
		}

		function view13thmonthpayBtn(id, type) {
			window.location.assign("reports_individual_13thmonthpay.php?empid="+id+"&per="+type);
		}

		function changePeriod(period, position, site, type) {

			window.location.assign("reports_individual.php?site="+site+"&position="+position+"&type="+type+"&period="+period)
		}

		function searchBox(id) {

		}
	</script>
</body>
</html>





















