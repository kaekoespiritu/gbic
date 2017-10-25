<!DOCTYPE html>
<?php
include('directives/session.php');
include('directives/db.php');
if(!isset($_POST['empid']))
{
	header("location:index.php");
}
else
{
	$empid = $_POST['empid'];
	Print "<script>console.log('".$empid."')</script>";
}

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empRow = mysql_fetch_assoc($employeeQuery);
?>
<html>
<head>
	<title>Payroll</title>
	<!-- Company Name: Green Built Industrial Corporation -->

	<link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body style="font-family: QuicksandMed;">
	<!-- 
	Vertical Navigation Bar
	HOME | EMPLOYEES | PAYROLL | REPORTS | ADMIN OPTIONS | LOGOUT
	After effects: Will minimize width after mouseover
-->
<div class="container-fluid">

	<?php
	require_once("directives/nav.php");
	?>

	<div class="row pull-down">
		<div class="col-md-10 col-md-offset-1">
		<ol class="breadcrumb text-left">
			<li><a href="applications.php" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span> Absence Notifications</a></li>
			<li class="active">AWOL pending</li>
			<button class="btn btn-danger pull-right" onclick="terminateEmployee()">Terminate employee</button>
			<button class="btn btn-primary pull-right moveright" onclick="">Restore employee</button>
		</ol>
			<h2 class="text-left"><?php Print $empRow['lastname'].", ".$empRow['firstname']?></h2>
			<hr>

			<div class="row">
				<div class="col-md-8 text-left" style="word-break: keep-all">
					<h4><b style="font-family: QuickSandMed">Employee ID:</b><?php Print $empRow['empid']?></h4>
					<h4><b style="font-family: QuickSandMed">Date of hire:</b><?php Print $empRow['datehired']?></h4>
					<h4><b style="font-family: QuickSandMed">Address:</b><?php Print $empRow['address']?></h4>
					<h4><b style="font-family: QuickSandMed">Contact Number:</b><?php Print $empRow['contactnum']?></h4>
				</div>
				<div class="col-md-4 pull-right text-right">
					<h4>Unpaid loans:<br><br>
					<?php
					$empid = $empRow['empid'];

					$sss = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'SSS' ORDER BY date DESC LIMIT 1";
					$pagibig = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'PagIBIG' ORDER BY date DESC LIMIT 1";
					$newVale = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' ORDER BY date DESC LIMIT 1";
					$oldVale = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' ORDER BY date DESC LIMIT 1";
					$sssQuery = mysql_query($sss);
					$pagibigQuery = mysql_query($pagibig);
					$newValeQuery = mysql_query($newVale);
					$oldValeQuery = mysql_query($oldVale);

					$noLoans = true;//checks if employees has pending loans
					if(mysql_num_rows($sssQuery))// SSS loan
					{
						$sssRow = mysql_fetch_assoc($sssQuery);
						if($sssRow['amount'] > 0)
						{
							$noLoans = false;
							Print "SSS: ".$sssRow['amount']."<br>";
						}

					}
					if(mysql_num_rows($pagibigQuery))// PAGIBIG loan
					{
						$pagibigRow = mysql_fetch_assoc($pagibigQuery);
						if($pagibigRow['amount'] > 0)
						{
							$noLoans = false;
							Print "Pag-IBIG: ".$pagibigRow['amount']."<br>";
						}

					}
					if(mysql_num_rows($newValeQuery))// NEW VALE loan
					{
						$newValeRow = mysql_fetch_assoc($newValeQuery);
						if($newValeRow['amount'] > 0)
						{
							$noLoans = false;
							Print "New Vale: ".$newValeRow['amount']."<br>";
						}

					}
					if(mysql_num_rows($oldValeQuery))// OLD VALE loan
					{
						$oldValeRow = mysql_fetch_assoc($oldValeQuery);
						if($oldValeRow['amount'] > 0)
						{
							$noLoans = false;
							Print "Old Vale: ".$oldValeRow['amount']."<br>";
						}

					}
					if($noLoans)
					{
						Print "No unpaid loans.";
					}
					?>
				</div>
			</div>
			<br>
			<?php
				$awol = "SELECT * FROM awol_employees WHERE empid = '$empid'";
				$awolQuery = mysql_query($awol);
				$row = mysql_fetch_assoc($awolQuery);

				$start = new DateTime($row['start_date']);
				$end = new DateTime($row['end_date']);
				$diff = $start->diff($end);
				
			?>
			<div class="well well-sm"><h3>Absent From: <?php Print $row['start_date']?></h3><h3>Absent To: <?php Print $row['end_date']?></h3><h3>Equivalent of <?php print_r($diff->days)?> Days</h3></div>

		</div>	
	</div>
</div>
<!-- Form to Terminate employee -->
<form id="terminationForm" action="logic_absence_termination.php" method="post">
	<input type="hidden" name="empid" value="<?php Print $empid?>">
</form>
<!-- SCRIPTS TO RENDER AFTER PAGE HAS LOADED -->
<script rel="javascript" src="js/jquery.min.js"></script>
<script rel="javascript" src="js/bootstrap.min.js"></script>
<script>
	document.getElementById("employees").setAttribute("style", "background-color: #10621e;");

	function terminateEmployee(){
		var a = confirm("Are you sure you want to terminate this Employee?");
		if(a)
		{
			document.getElementById('terminationForm').submit();
		}
		
	}
</script>


</div>
</body>
</html>
