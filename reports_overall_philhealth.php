<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');
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

		<h3 class="pull-down">Overall Payroll Report for [SITE]</h3>

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
				<td>
					Signature
				</td>
			</tr>
			<tr>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
				<td>
					1
				</td>
			</tr>
		</table>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>