<?php

include('directives/db.php');
include('directives/session.php');

$empid = $_POST['empid'];
$type = $_POST['type'];
$history = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$type' ORDER BY date DESC";
$historyQuery = mysql_query($history);

$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$employeeQuery = mysql_query($employee);
$empArr = mysql_fetch_assoc($employeeQuery);
$output = "";//this is the object that will be fetch to the loans_view.php

$output .= "<div class='col-md-12'>
					<h4 class='modal-title pull-down'>".$empArr['lastname'].", ".$empArr['firstname']."'s loan history</h4>
				</div>
				<div class='modal-body pull-down'>
					<table class='table table-bordered'>
					<tr>
						<td>Date</td>
						<td>Balance</td>
						<td>Remarks</td>
					</tr>";
if(mysql_num_rows($historyQuery) > 0)
{
	while($row = mysql_fetch_assoc($historyQuery))
	{
		$output .= "
					<tr>
						<td>".$row['date']."</td>
						<td>".$row['amount']."</td>
						<td>".$row['remarks']."</td>
					</tr>
				";
	}
	
}
$output .= "
					</table>
				</div>
			";
echo $output;

?>