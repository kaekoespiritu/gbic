<?php
include_once('directives/db.php');
include_once('directives/session.php');
include("pagination/loans_employees.php");//For pagination

$site_page = $_POST['site'];
$position_page = $_POST['position_page'];
$loanType = $_POST['loanType'];
$pageNum = ($_POST['page'] == 0 ? 1 : $_POST['page']);
$search = $_POST['search'];

Print "
<table class='table table-bordered table-condensed' style='background-color:white;'>
	<tr>
		<td style='width:130px !important;'>ID</td>
		<td style='width:200px !important;'>Name</td>
		<td>Position</td>
		<td>Site</td>
		<td>".
			($loanType == 'SSS' || $loanType == 'PAGIBIG' ? "Monthly Dues" : "Amount to be paid")
		."</td>
		<td>Actions</td>
	</tr>";
		$appendQuery = (($loanType == 'SSS' || $loanType == 'PAGIBIG') ? '' : ' AND a.balance > 0');
		if($site_page != "null")
			$appendQuery .= " AND e.site = '$site_page' ";
		if($position_page != "null")
			$appendQuery .= " AND e.position = '$position_page' ";

		if($search != '')// loans1 is for the table query and loans2 is for the pagination query
		{

			$loans = "loans a INNER JOIN (SELECT empid, max(id) AS other_id FROM loans WHERE type = '$loanType' GROUP BY empid) AS b ON a.empid = b.empid AND a.id = b.other_id INNER JOIN employee e on a.empid = e.empid WHERE e.employment_status = '1' $appendQuery AND (e.lastname LIKE '%$search%' OR e.firstname LIKE '%$search%')";

			// $loans1 = "loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' $appendQuery AND (e.lastname LIKE '%$search%' OR e.firstname LIKE '%$search%') GROUP BY e.empid ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
			// $loans2 = "loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' $appendQuery AND (e.lastname LIKE '%$search%' OR e.firstname LIKE '%$search%') ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
		}
		else
		{
			$loans = "loans a INNER JOIN (SELECT empid, max(id) AS other_id FROM loans WHERE type = '$loanType' GROUP BY empid) AS b ON a.empid = b.empid AND a.id = b.other_id INNER JOIN employee e ON a.empid = e.empid WHERE e.employment_status = '1' $appendQuery";

			// $loans1 = "loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' $appendQuery GROUP BY e.empid ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
			// $loans2 = "loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' $appendQuery ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
		}

		// echo $loans1."<br>";
		// echo $loans2."<br>";
		//TEST
		// $testQuery = "SELECT DISTINCT * FROM loans l INNER JOIN employee e ON l.empid = e.empid WHERE type = '$loanType' AND e.employment_status = '1' GROUP BY l.empid ORDER BY e.lastname ASC, STR_TO_DATE(l.date, '%M %e, %Y') ASC, l.id ASC ";
		// $testQuery = mysql_query($testQuery);
		// echo mysql_num_rows($testQuery)."<br>";
		
		//-TEST

		$page = (int) (!isset($pageNum) ? 1 : $pageNum);
    	$limit = 40; //if you want to dispaly 10 records per page then you have to change here
    	$startpoint = ($page * $limit) - $limit;
        $statement = $loans;
		$loansQuery = mysql_query("SELECT  a.*, e.lastname, e.firstname FROM {$statement} LIMIT {$startpoint}, {$limit}") or die (mysql_error());

		$overallPayable = 0;
		// $loansQuery = mysql_query($loans) or die (mysql_error());
		$noLoanChecker = true;

		if(mysql_num_rows($loansQuery) > 0)
		{

			// $noLoanChecker = true;
			$noRepeat = '';
			
			while($row = mysql_fetch_assoc($loansQuery))
			{

				$empid = $row['empid'];
				$employeeQuery = mysql_query("SELECT * FROM employee WHERE empid = '$empid' AND employment_status = '1' ORDER BY lastname ASC");
				
				if(mysql_num_rows($employeeQuery) > 0)
				{

					$empArr = mysql_fetch_assoc($employeeQuery);
					// echo $empArr['lastname']."</br>";
					//Print "<script>alert(".mysql_num_rows($employeeQuery).")</script>";
					//Check if employee has already fully paid his/her loan
					// $checker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY id DESC LIMIT 1";
			
					// $checkerQuery = mysql_query($checker);
					// $checkerArr = mysql_fetch_assoc($checkerQuery);
					// echo $checker."<br>";
					// echo mysql_num_rows($checkerQuery)."</br>";
					
					if(mysql_num_rows($employeeQuery) != 0)
					{

						if($loanType == 'SSS' || $loanType == 'PAGIBIG')
						{
							if($row['action'] == 1)
							{
									
									Print "
										<tr>
											<input type='hidden' name='empid[]' value='". $empid ."'>
											<td style='vertical-align: inherit'>
												".$empid."
											</td>
											<td style='vertical-align: inherit; text-align: left;' >
												".$empArr['lastname'].", ".$empArr['firstname']."
											</td>
											<td style='vertical-align: inherit'>
												".$empArr['position']."
											</td>
											<td style='vertical-align: inherit'>
												".$empArr['site']."
											</td>
											<td style='vertical-align: inherit'>
												".numberExactFormat($row['monthly'], 2, '.', true)."
											</td>
											<td>";
											if($loanType == 'SSS' || $loanType == 'PAGIBIG')
											{
												Print	"<a class='btn btn-danger' onclick='endLoan(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-minus-sign'></span> End Loan</a>";
											}
											Print	
												"&nbsp<a class='btn btn-primary' data-toggle='modal' data-target='#viewLoanHistory' onclick='load_history(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-list-alt'></span> History</a>
											
											</td>
										</tr>
										";
										$noLoanChecker = false;
										$overallPayable += $row['monthly'];
							}
						}
						else
						{
									
									Print "
										<tr>
											<input type='hidden' name='empid[]' value='". $empid ."'>
											<td style='vertical-align: inherit'>
												".$empid."
											</td>
											<td style='vertical-align: inherit; text-align: left;'>
												".$empArr['lastname'].", ".$empArr['firstname']."
											</td>
											<td style='vertical-align: inherit'>
												".$empArr['position']."
											</td>
											<td style='vertical-align: inherit'>
												".$empArr['site']."
											</td>
											<td style='vertical-align: inherit'>
												".numberExactFormat($row['balance'], 2, '.', true)."
											</td>
											<td>
												&nbsp<a class='btn btn-primary' data-toggle='modal' data-target='#viewLoanHistory' onclick='load_history(\"".$empid."\", \"".$loanType."\")'><span class='glyphicon glyphicon-list-alt'></span> History</a>
											
											</td>
										</tr>
										";
									$noLoanChecker = false;
									$overallPayable += $row['balance'];
							
						}
					}
				}
			}
			if($noLoanChecker)
			{
				Print 
				"
				<tr><td colspan='6'><h3>No records found.</h3></td></tr>
				";
			}
		}
		else
		{
			$loans = "";
			$limit = "";
			$page = "";
			Print 
			"
			<tr><td colspan='6'><h3>No records found.</h3></td></tr>
			";
		}
	
Print "</table>";
// echo $overallPayable ;
Print "<input type='hidden' id='overallPayableUpdate' value='".numberExactFormat($overallPayable, 2, '.', true)."'>
	<script>
		var passValue = $('#overallPayableUpdate').val();
		$('#overallPlacing').html(passValue);
		$('#toTransfer').val(passValue);
		transfer();
		
	
	</script>";

echo "<div id='pagingg' >";
    if($loans && $limit && $page && $site_page && $position_page && $loanType)
       	echo pagination($loans,$limit,$page, $site_page, $position_page, $loanType);
echo "</div>";
?>