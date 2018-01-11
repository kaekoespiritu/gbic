<?php
	include_once('directives/db.php');
	include('directives/session.php');
	
	$empid = $_POST['empid'];

	$employee = "SELECT * FROM employee WHERE empid = '$empid'";
	$employeeQuery = mysql_query($employee);
	$empArr = mysql_fetch_assoc($employeeQuery);
	$output = "
				<div class='modal-header'>
							<div class='col-md-11'>
								<h4 class='modal-title'>Site History for ".$empArr['lastname'].", ".$empArr['firstname']."</h4>
							</div>
							<div class='col-md-1 pull-right'>
					        	<button type='button' class='close col-md-1' style='float:right' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
				        	</div>
						</div>
						<div class='modal-body'>
							<table class='table table-bordered'>
								<thead>
									<tr>
										<td>Date</td>
										<td>Transfered to</td>
										<td>Admin</td>
									</tr>
								</thead>
								<tbody>
			";
	$siteHist = "SELECT * FROM site_history WHERE empid = '$empid' ORDER BY date DESC";
	$histQuery = mysql_query($siteHist);
	$historyBool = false;//to display the printable
	if(mysql_num_rows($histQuery) > 0)
	{
		while($row = mysql_fetch_assoc($histQuery))
		{
			$output .= "<tr>
							<td>".$row['date']."</td>
							<td>".$row['site']."</td>
							<td>".$row['admin']."</td>
						</tr>
						";
		}
		$historyBool = true;
	}		
	else 
	{
		$output .= "<tr>
							<td colspan='3'><h3>No site movement history<h3></td>
						</tr>
						";
	}	
	$output .= "				</tbody>
							</table>
						</div>";
	if($historyBool)
		$output .= "	<form method='POST' action='print_history_employee_site.php'>
							<input type='hidden' name='employee' value='".$empid."'>

							<input type='submit' value='Print site history' class='btn btn-success pull-up'>
						</form>";
	echo $output;			
?>
























