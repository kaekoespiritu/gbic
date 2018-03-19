<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$site = $_GET['site'];
$period = $_GET['period'];
$position = $_GET['position'];
$require = $_GET['req'];
$date = $_GET['date'];

//Checks if site in HTTP is altered by user manually
$siteChecker = "SELECT * FROM site WHERE location = '$site'";
//Checks if position in HTTP is altered by user manually 
$positionChecker = "SELECT * FROM job_position WHERE position = '$position'";
$siteCheckerQuery = mysql_query($siteChecker);
$positionCheckerQuery = mysql_query($positionChecker);
if(mysql_num_rows($siteCheckerQuery) == 0)
{
	header("location:index.php");
}
if($position != 'all')
{
	if(mysql_num_rows($positionCheckerQuery) == 0)
	{
		header("location:index.php");
	}
}
	
// Checks if requirement in HTTP is altered by user manually 
switch($require) {
	case "all":break;
	case "withReq":break;
	case "withOReq":break;
	default: header("location:index.php");
}

// Get requirements type (with or without)

$dateDisplay = "";
$periodDisplay = "";
if($period == "week")
{
	$weekBefore = date('F j, Y', strtotime('-6 day', strtotime($date)));
	$filename =  $site." Expense Report ".$weekBefore." - ".$date.".xls";

	$dateDisplay = $weekBefore." - ".$date;
	$periodDisplay = "Weekly";
}
else if($period == "month" || $period == "year")
{
	$filename =  $site." Expense Report ".$date.".xls";
	$dateDisplay = $date;
	$periodDisplay = ($period == "month" ? "Monthly" : "Yearly");
}
else
	header("location:index.php");

// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

//Merge cells
$activeSheet->mergeCells('A1:C1');// Site
$activeSheet->mergeCells('A2:C2');// Period
$activeSheet->mergeCells('D1:L2');// Report header

$activeSheet->mergeCells('A3:A5');// Name
$activeSheet->mergeCells('B3:B5');// Position
$activeSheet->mergeCells('C3:C5');// Salary

$activeSheet->mergeCells('D3:I3');// Contribution
$activeSheet->mergeCells('D4:E4');// SSS
$activeSheet->mergeCells('F4:G4');// Pagibig
$activeSheet->mergeCells('H4:I4');// Philhealth

$activeSheet->mergeCells('J3:K4');// Vale

$activeSheet->mergeCells('L3:L5');// Total

//----------------- Header Contents ---------------------//
$activeSheet->setCellValue('A1', 'Site: '.$site);
$activeSheet->setCellValue('A2', 'Period: '.$dateDisplay);
$activeSheet->setCellValue('D1', $periodDisplay.' Expenses Report');

$activeSheet->setCellValue('A3', 'Name');
$activeSheet->setCellValue('B3', 'Position');
$activeSheet->setCellValue('C3', 'Salary Received(w/o Contributions)');

$activeSheet->setCellValue('D3', 'Contributions');
$activeSheet->setCellValue('D4', 'SSS');
$activeSheet->setCellValue('F4', 'Pag-IBIG');
$activeSheet->setCellValue('H4', 'PhilHealth');
$activeSheet->setCellValue('D5', 'Employee');
$activeSheet->setCellValue('E5', 'Employer');
$activeSheet->setCellValue('F5', 'Employee');
$activeSheet->setCellValue('G5', 'Employer');
$activeSheet->setCellValue('H5', 'Employee');
$activeSheet->setCellValue('I5', 'Employer');

$activeSheet->setCellValue('J3', 'Vale');
$activeSheet->setCellValue('J5', 'Old');
$activeSheet->setCellValue('K5', 'New');

$activeSheet->setCellValue('L3', 'Total');


//filters
$filter = "";
if($require != "withReq")
	$filter .= "AND complete_doc = '1' ";
else if($require != "withOReq")
	$filter .= "AND complete_doc = '0' ";
if($position != "all")
{
	if($filter != "")
		$filter .= "AND";

	$filter .= " position = '".$position."' ";
}


$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' $filter ORDER BY lastname ASC, position ASC";

$empQuery = mysql_query($employee);

$rowCounter = 6; // row start of date

$TotalBool = false;//boolean for displaying grandtotal
if(mysql_num_rows($empQuery) != 0)
{
	//Checker to know what the employer chose (weekly, monthly, yearly)
	$numChecker = explode(" ",$date);
	$countChecker = count($numChecker); // 3 = weekly | 2 = monthly | 1 = yearly

	$GrandTotal = 0;
	while($empArr = mysql_fetch_assoc($empQuery))
	{
		$employeeTotal = 0;
		$empid = $empArr['empid'];
		//Create Query for Monthly and yearly
		if($countChecker == 3)//Weekly
		{
			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date = '$date'";
		}
		else if($countChecker == 2)//Monthly
		{
			$month = $numChecker[0];
			$year = $numChecker[1];

			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND (date LIKE '$month%' AND date LIKE '%$year')";

		}
		else if($countChecker == 1)//Yearly
		{
			$year = $numChecker[0];

			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '%$year'";
		}
		$payrollQuery = mysql_query($payroll);

		if(mysql_num_rows($payrollQuery) != 0)
		{ 
			$TotalBool = true;//there is data
			$counter = 0;
			$numPayrollEmp = mysql_num_rows($payrollQuery);

			$totalSalary = 0;
			$NewValeBalance = 0;
			$OldValeBalance = 0;
			$sssEE = 0;
			$sssER = 0;
			$philhealthEE = 0;
			$philhealthER = 0;
			$pagibigEE = 0;
			$pagibigER = 0;

			while($payrollArr = mysql_fetch_assoc($payrollQuery))
			{
				$counter++;//counter for getting the payroll and compiling all the data
				
				//contributions

				$subTotalSalary = $payrollArr['total_salary'] - $payrollArr['sss'] - $payrollArr['philhealth'] - $payrollArr['pagibig'];
				$subTotalSalary = abs($subTotalSalary);

				$totalSalary += $subTotalSalary;
				$sssEE += $payrollArr['sss'];
				$sssER += $payrollArr['sss_er'];
				$philhealthEE += $payrollArr['philhealth'];
				$philhealthER += $payrollArr['philhealth_er'];
				$pagibigEE += $payrollArr['pagibig'];
				$pagibigER += $payrollArr['pagibig_er'];

				$startDate = date('F j, Y', strtotime('-6 day', strtotime($payrollArr['date'])));
				$endDate = $payrollArr['date'];

				$loanCheckNew = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'newVale' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC";
				$loanCheckOld = "SELECT * FROM loans WHERE empid = '$empid' AND type = 'oldVale' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY STR_TO_DATE(date, '%M %e, %Y')  DESC";
				
				$loanQueryNew = mysql_query($loanCheckNew);
				$loanQueryOld = mysql_query($loanCheckOld);

				$NewValeBalance = 0;
				while($loanArrNew = mysql_fetch_assoc($loanQueryNew));
				{
					if($loanArrNew['action'] == '1')//loaned
						$NewValeBalance += $loanArrNew['amount'];
					else
						$NewValeBalance -= $loanArrNew['amount'];
					$NewValeBalance = abs($NewValeBalance);//absolute value
				}
				$OldValeBalance = 0;
				while($loanArrOld = mysql_fetch_assoc($loanQueryOld));
				{
					if($loanArrOld['action'] == '1')//loaned
						$OldValeBalance += $loanArrOld['amount'];
					else
						$OldValeBalance -= $loanArrOld['amount'];
					$OldValeBalance = abs($OldValeBalance);//absolute value
				}


				if($counter == $numPayrollEmp)
				{
					$employeeTotal = $totalSalary + $sssEE + $sssER + $pagibigEE + $pagibigER + $philhealthEE + $philhealthER + $OldValeBalance  + $NewValeBalance;

					$activeSheet->setCellValue('A'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
					$activeSheet->setCellValue('B'.$rowCounter, $empArr['position']);
					$activeSheet->setCellValue('C'.$rowCounter, numberExactFormat($totalSalary, 2, '.', true));
					$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($sssEE, 2, '.', true));
					$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($sssER, 2, '.', true));
					$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($pagibigEE, 2, '.', true));
					$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($pagibigER, 2, '.', true));
					$activeSheet->setCellValue('H'.$rowCounter, numberExactFormat($philhealthEE, 2, '.', true));
					$activeSheet->setCellValue('I'.$rowCounter, numberExactFormat($philhealthER, 2, '.', true));
					$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($OldValeBalance, 2, '.', true));
					$activeSheet->setCellValue('K'.$rowCounter, numberExactFormat($NewValeBalance, 2, '.', true));
					$activeSheet->setCellValue('L'.$rowCounter, numberExactFormat($employeeTotal, 2, '.', true));

					$GrandTotal += $employeeTotal;

					$rowCounter++;
				}
			}
		}
	}
}
else
{
	$activeSheet->mergeCells('A'.$rowCounter.':L'.$rowCounter);
	$activeSheet->setCellValue('L'.$rowCounter, 'No employee as of the moment.');
}

if($TotalBool)
{
	$activeSheet->mergeCells('A'.$rowCounter.':J'.$rowCounter);
	$activeSheet->setCellValue('K'.$rowCounter, 'Grand Total:');
	$activeSheet->setCellValue('L'.$rowCounter, numberExactFormat($GrandTotal, 2, '.', true));
}

//Style for the Spreadsheet
$activeSheet->getStyle('A1:L5')->applyFromArray($border_all_medium);//Header 
$activeSheet->getStyle('A6:L'.$rowCounter)->applyFromArray($border_all_thin);//Content
$activeSheet->getStyle('A1:L'.$rowCounter)->applyFromArray($align_center);//Centered header text

$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(false);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);
$activeSheet->getColumnDimension('H')->setAutoSize(true);
$activeSheet->getColumnDimension('I')->setAutoSize(true);
$activeSheet->getColumnDimension('J')->setAutoSize(true);
$activeSheet->getColumnDimension('K')->setAutoSize(true);
$activeSheet->getColumnDimension('L')->setAutoSize(true);


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













