<!DOCTYPE html>
<?php
	include('directives/session.php');
	include('directives/db.php');

	if(!isset($_GET['empid']) && !isset($_GET['type']))
	{
		header("location: index.php");
	}
	else
	{
		$empid = $_GET['empid'];
		$type = $_GET['type'];

		$employee = "SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1'";
		$empQuery = mysql_query($employee);
		$empArr = mysql_fetch_assoc($empQuery);

		if(mysql_num_rows($empQuery) == 0)
			header("location: index.php");

		switch($type)
		{
			case "sss": $loanType = "SSS" ;break;
			case "pagibig": $loanType = "PagIbig" ;break;
			case "oldvale": $loanType = "Old Vale" ;break;
			case "newvale": $loanType = "New Vale" ;break;
			default: header("location: index.php");
		}
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

		<div class="col-md-10 col-md-offset-1">
			<div class="row"><br>
				<div class="row text-center">
					<ol class="breadcrumb text-left">
						<li><a href='reports_individual_loans.php?type=Loans&period=week&site=null&position=null' class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Loans</a></li>
						<li>Individual <?php Print $loanType ?> Loan Report for <?php Print $empArr['lastname'].", ".$empArr['firstname']." | ".$empArr['position']." at ".$empArr['site']?></li>
					</ol>
				</div>
			</div>

			<div class="col-md-12">
				<div class="pull-down">
				<button class="btn btn-default">
					Print <?php Print $loanType ?>
				</button>
				<table class="table table-bordered pull-down">
					<tr>
						<td>
							Date
						</td>
						<td>
							Action
						</td>
						<td>
							Amount
						</td>
						<td>
							Balance
						</td>
						<td>
							Remarks
						</td>
						<td>
							Approved By
						</td>
					</tr>
					<?php
					$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$type' ORDER BY date ASC, time ASC";
					$historyQuery = mysql_query($history);

					if(mysql_num_rows($historyQuery) > 0)
					{
						while($row = mysql_fetch_assoc($historyQuery))
						{
							Print "	<tr>
										<td>".$row['date']."</td>";
							if($row['action'] == '1')
							{
								Print 	"
										<td>Loaned</td>
										<td> +".number_format($row['amount'], 2, '.', ',')."</td>
										";
							}
							else
							{
								$output .= "
											<td>Paid</td>
											<td> -".number_format($row['amount'], 2, '.', ',')."</td>
											";
							}
							Print 		"<td>".number_format($row['balance'], 2, '.', ',')."</td>";
							
							
							Print 	"	<td>".$row['remarks']."</td>
										<td>".$row['admin']."</td>
									</tr>
									";
						}
						
					}
					
					?>
					
				</table>
				</div>
			</div>
		</div>

	</div>

	<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
	<script rel="javascript" src="js/jquery.min.js"></script>
	<script rel="javascript" src="js/bootstrap.min.js"></script>
	<script>
		document.getElementById("reports").setAttribute("style", "background-color: #10621e;");
	</script>
</body>
</html>