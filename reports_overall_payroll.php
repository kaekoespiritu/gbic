<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	$location = $_GET['site'];
	$site = "SELECT * FROM site WHERE location = '$location'";
	$siteQuery = mysql_query($site);

	if(mysql_num_rows($siteQuery) == 0)
	{
		header("location:reports_overall_earnings.php?type=Earnings&period=Weekly");
	}
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

		<h3 class="pull-down">Overall Payroll Report for <?php Print $location?></h3>
		Requirements:
		<select>
			<option hidden>All</option>
			<option>With Requirements</option>
			<option>W/o Requirements</option>
		</select>


		Payroll Dates:
		<select>
			<option hidden>Select date</option>
			<?php
			$payrollDays = "SELECT DISTINCT date FROM Payroll ORDER BY date ASC";//gets non repeatable dates
			$payrollDaysQuery = mysql_query($payrollDays);

			if(mysql_num_rows($payrollDaysQuery))
			{
				while($PdaysOptions = mysql_fetch_assoc($payrollDaysQuery))
				{
					$startDate = date('F j, Y', strtotime('-6 day', strtotime($PdaysOptions['date'])));
					Print "<option value='".$PdaysOptions['date']."'>".$startDate." - ".$PdaysOptions['date']."</option>";
				}
				
			}
			else
			{
				Print "<option>No payroll date</option>";
			}
			?>
		</select>

		<?php 

		if(isset($_POST['date']))
		{
			Print '
					<table class="table table-bordered pull-down">
						<tr>
							<td colspan="6">
								Site with requirements
							</td>
							<td colspan="21" rowspan="2" class="vertical-align">
								PAYROLL
							</td>
						</tr>
						<tr>
							<td colspan="6">
								Date covered: Start - End
							</td>
						</tr>
						<tr>
							<td>
								#
							</td>
							<td>
								Name
							</td>
							<td>
								Position
							</td>
							<td>
								Rate
							</td>
							<td>
								# of days
							</td>
							<td>
								O.T.
							</td>
							<td>
								# of hours
							</td>
							<td>
								Allow.
							</td>
							<td>
								COLA
							</td>
							<td>
								Sun
							</td>
							<td>
								D
							</td>
							<td>
								hrs
							</td>
							<td>
								N.D.
							</td>
							<td>
								#
							</td>
							<td>
								Reg. Hol
							</td>
							<td>
								#
							</td>
							<td>
								Spe. Hol
							</td>
							<td>
								#
							</td>
							<td>
								X All.
							</td>
							<td>
								SSS
							</td>
							<td>
								Philhealth
							</td>
							<td>
								PagIBIG
							</td>
							<td>
								Old vale
							</td>
							<td>
								vale
							</td>
							<td>
								tools
							</td>
							<td>
								Total Salary
							</td>
						</tr>
						

						<tr>
							<td><!-- # -->
								'.$payrollArr['rate'].'
							</td>
							<td><!-- Name -->
								'.$payrollArr['rate'].'
							</td>
							<td><!-- Position -->
								'.$payrollArr['rate'].'
							</td>
							<td><!-- Rate -->
								'.$payrollArr['rate'].'
							</td>
							<td><!-- # of days -->
								'.$payrollArr['num_days'].'
							</td>
							<td><!-- OT -->
								'.$payrollArr['overtime'].'
							</td>
							<td><!-- # of hours -->
								'.$payrollArr['ot_num'].'
							</td>
							<td><!-- Allow -->
								'.$payrollArr['allow'].'
							</td>
							<td><!-- COLA -->
								'.$payrollArr['cola'].'
							</td>
							<td><!-- Sun -->
								'.$payrollArr['sunday_rate'].'
							</td>
							<td><!-- D -->
								'.$payrollArr['sunday_att'].'
							</td>
							<td><!-- hrs -->
								'.$payrollArr['sunday_hrs'].'
							</td>
							<td><!-- ND -->
								'.$payrollArr['nightdiff_rate'].'
							</td>
							<td><!-- # -->
								'.$payrollArr['nightdiff_num'].'
							</td>
							<td><!-- Reg.hol -->
								'.$payrollArr['reg_holiday'].'
							</td>
							<td><!-- # -->
								'.$payrollArr['reg_holiday_num'].'
							</td>
							<td><!-- Spe. hol -->
								'.$payrollArr['spe_holiday'].'
							</td>
							<td><!-- # -->
								'.$payrollArr['spe_holiday_num'].'
							</td>
							<td><!-- X.All -->
								'.$payrollArr['x_allowance'].'
							</td>
							<td><!-- SSS -->
								'.$payrollArr['sss'].'
							</td>
							<td><!-- Philhealth -->
								'.$payrollArr['philhealth'].'
							</td>
							<td><!-- Pagibig -->
								'.$payrollArr['pagibig'].'
							</td>
							<td><!-- Old vale -->
								'.$payrollArr['old_vale'].'
							</td>
							<td><!-- vale -->
								'.$payrollArr['new_vale'].'
							</td>
							<td><!-- tools -->
								'.$payrollArr['tools_paid'].'
							</td>
							<td><!-- Total Salary -->
								'.$payrollArr['total_salary'].'
							</td>
						</tr>
					</table>

					';
		}
		else
		{
			Print "<h4>Please select date</h4>";
		}
		?>
		

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>

		
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>