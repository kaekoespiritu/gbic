<?php

include('directives/db.php');
include('directives/session.php');
include('directives/admin_historical.php');

//employee History
$empid = $_POST['empid'];
$type = $_POST['type'];
$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$type' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC, time DESC";
$historyQuery = mysql_query($history);

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($employeeQuery);
$output = "";//this is the object that will be fetch to the loans_view.php

$output .= "<div class='col-md-12'>
					<h4 class='modal-title'>".$empArr['lastname'].", ".$empArr['firstname']."'s loan history</h4>
				</div>
				<div class='modal-body pull-down'>
					<table class='table table-bordered'>
					<tr>
						<td>Balance</td>
						<td>Amount</td>
						<td>Action</td>
						<td>Remarks</td>
						<td>Date</td>
						<td>Approved by</td>
					</tr>";
if(mysql_num_rows($historyQuery) > 0)
{
	while($row = mysql_fetch_assoc($historyQuery))
	{
		$output .= "
					<tr>
						<td>".number_format($row['balance'], 2, '.', ',')."</td>";
		if($row['action'] == '1')
		{
			$output .= "<td> +".number_format($row['amount'], 2, '.', ',')."</td>
						<td>Loaned</td>";
		}
		else
		{
			$output .= "<td> -".number_format($row['amount'], 2, '.', ',')."</td>
						<td>Paid</td>";
		}
		
		$output .= 	"	<td>".$row['remarks']."</td>
						<td>".$row['date']."</td>
						<td>".$row['admin']."</td>
					</tr>
				";
	}
	
}
$output .= "
					</table>
				</div>
				</div>
					<form method='POST' action='print_history_loans.php'>
						<input type='hidden' name='employee' value='".$empid."'>
						<input type='hidden' name='type' value='".$type."'>

						<input type='submit' value='Print loan history' class='btn btn-success pull-up'>
					</form>
				</div>

			";
echo $output;

?>