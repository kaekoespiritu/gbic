<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php');//Styles for PHPexcel

$site = $_GET['site'];
$period = $_GET['period'];
$date = $_GET['date'];
//Middleware
//site Checker 
$siteCheck = "SELECT * FROM site WHERE location = '$site'";
$siteQuery = mysql_query($siteCheck);
if(mysql_num_rows($siteQuery) == 0)
	header("location: index.php");
//period Checker
switch($period)
{
	case "all": $periodDisplay = "All"; break;
	case "week": $periodDisplay = "Weekly"; break;
	case "month": $periodDisplay = "Monthly"; break;
	case "year": $periodDisplay = "Yearly"; break;
	default: header("location: index.php");
}
$loansBool = false;//boolean for post all
if($date == "all")
	$loansBool = true;

$dateDisplay = ($date != "all" ? " ".$date : "");
// Get requirements type (with or without)


$filename = $periodDisplay." ".$site." Loan Report".$dateDisplay.".xls";
// Last Name, First Name of Site (Date) - Payroll.xls


$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);


if($loansBool)// if period is set to All
{
	//Merge cells
	$activeSheet->mergeCells('A1:C1');
	$activeSheet->mergeCells('D1:G1');

	//Title Contents
	$activeSheet->setCellValue('A1', "Period: All");
	$activeSheet->setCellValue('D1', $site." Loan Report");

	//Header Contents
	$activeSheet->setCellValue('A2', 'Name');
	$activeSheet->setCellValue('B2', 'Site');
	$activeSheet->setCellValue('C2', 'Position');
	$activeSheet->setCellValue('D2', 'SSS');
	$activeSheet->setCellValue('E2', 'PAGIBIG');
	$activeSheet->setCellValue('F2', 'Old Vale');
	$activeSheet->setCellValue('G2', 'New Vale');

	$rowCounter = 3; //start for the data in the row of excel
}	
else
{
	//Merge cells
	$activeSheet->mergeCells('A1:C1');//period
	$activeSheet->mergeCells('D1:G2');//title 
	$activeSheet->mergeCells('A2:C2');//date

	//Title Contents
	$activeSheet->setCellValue('A1', "Period: ".$periodDisplay);
	$activeSheet->setCellValue('A2', "Date: ".$date);
	$activeSheet->setCellValue('D1', $site." Loan Report");

	//Header Contents
	$activeSheet->setCellValue('A3', 'Name');
	$activeSheet->setCellValue('B3', 'Site');
	$activeSheet->setCellValue('C3', 'Position');
	$activeSheet->setCellValue('D3', 'SSS');
	$activeSheet->setCellValue('E3', 'PAGIBIG');
	$activeSheet->setCellValue('F3', 'Old Vale');
	$activeSheet->setCellValue('G3', 'New Vale');

	$rowCounter = 4; //start for the data in the row of excel
}

	


//----------------- Body ---------------------//


$employee = "SELECT * FROM employee WHERE site = '$site' AND employment_status = '1' ORDER BY lastname ASC, position ASC";
$empQuery = mysql_query($employee) or die (mysql_error());
$sssGrandTotal = 0;
$PagibigGrandTotal = 0;
$newValeGrandTotal = 0;
$oldValeGrandTotal = 0;



while($empArr = mysql_fetch_assoc($empQuery))
{
	$empid = $empArr['empid'];
	//check if employee has past loans
	for($counter = 0; $counter <= 3 ;$counter++)
	{
		switch($counter) 
		{
			case 0: $loanType = 'PagIBIG';break;
			case 1: $loanType = 'SSS';break;
			case 2: $loanType = 'NewVale';break;
			case 3: $loanType = 'OldVale';break;
		}

		if($date == "all")
			$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' ORDER BY date DESC, time DESC LIMIT 1";
		else if($period == "week")//weekly
		{
			$endDate = $date;
			$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));
			$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND (STR_TO_DATE(date, '%M %e, %Y') BETWEEN STR_TO_DATE('$startDate', '%M %e, %Y') AND STR_TO_DATE('$endDate', '%M %e, %Y')) ORDER BY date DESC, time DESC LIMIT 1";
		}
		else if($period == "month")//monthly
		{
			$monthDate = explode(' ', $date);
			$month = $monthDate[0];
			$year = $monthDate[1];
			$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND (date LIKE '$month%' AND date LIKE '%$year') ORDER BY date DESC, time DESC LIMIT 1";
		}
		else if($period == "year")//yearly
		{
			$year = $date;
			$loanChecker = "SELECT * FROM loans WHERE empid = '$empid' AND type = '$loanType' AND date LIKE '%$year' ORDER BY date DESC, time DESC LIMIT 1";
		}
				
		
		$loanCheckQuery = mysql_query($loanChecker) or die (mysql_error());
		switch($counter) 
		{
			case 0: $pagibigLoan = mysql_fetch_assoc($loanCheckQuery);break;
			case 1: $sssLoan = mysql_fetch_assoc($loanCheckQuery);break;
			case 2: $newValeLoan = mysql_fetch_assoc($loanCheckQuery);break;
			case 3: $oldValeLoan = mysql_fetch_assoc($loanCheckQuery);break;
		}
	}
	if(	$pagibigLoan['balance'] != 0 || 
		$sssLoan['balance'] != 0 || 
		$newValeLoan['balance'] != 0 || 
		$oldValeLoan['balance'] != 0)
	{
		$activeSheet->setCellValue('A'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
		$activeSheet->setCellValue('B'.$rowCounter, $empArr['site']);
		$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']);
		
		if($sssLoan['balance'] != 0)
			$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($sssLoan['balance'], 2, '.', true));
		else
			$activeSheet->setCellValue('D'.$rowCounter, "N/A");
		if($pagibigLoan['balance'] != 0)
			$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($pagibigLoan['balance'], 2, '.', true));
		else
			$activeSheet->setCellValue('E'.$rowCounter, "N/A");
		if($oldValeLoan['balance'] != 0)
			$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($oldValeLoan['balance'], 2, '.', true));
		else
			$activeSheet->setCellValue('F'.$rowCounter, "N/A");
		if($newValeLoan['balance'] != 0)
			$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($newValeLoan['balance'], 2, '.', true));
		else
			$activeSheet->setCellValue('G'.$rowCounter, "N/A");
	
	}
	
	
	$sssGrandTotal += $sssLoan['balance'];
	$PagibigGrandTotal += $pagibigLoan['balance'];
	$newValeGrandTotal += $newValeLoan['balance'];
	$oldValeGrandTotal += $oldValeLoan['balance'];

	$rowCounter++;//Increment row
}

if(	$sssGrandTotal != 0 || 
	$PagibigGrandTotal != 0 || 
	$newValeGrandTotal != 0 || 
	$oldValeGrandTotal != 0)
{
//Total Overall Loans
	$activeSheet->mergeCells('A'.$rowCounter.':C'.$rowCounter);//total overall loans
	$activeSheet->setCellValue('A'.$rowCounter, 'Total Overall Loans');

	$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($sssGrandTotal, 2, '.', true));
	$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($PagibigGrandTotal, 2, '.', true));
	$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($oldValeGrandTotal, 2, '.', true));
	$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($newValeGrandTotal, 2, '.', true));
// Grand total government loans
	$rowCounter++;//add space
	$govGrandtotal = $sssGrandTotal + $PagibigGrandTotal;//gets the sum of government loans
	$activeSheet->mergeCells('A'.$rowCounter.':C'.$rowCounter);//total overall loans
	$activeSheet->mergeCells('D'.$rowCounter.':E'.$rowCounter);//government loan space
	$activeSheet->mergeCells('F'.$rowCounter.':G'.$rowCounter);//company loan space
	$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total Goverment Loans');

	$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($govGrandtotal, 2, '.', true));

// Gran total Company loans
	$rowCounter++;//add space
	$companyGrandtotal = $newValeGrandTotal + $oldValeGrandTotal;// gets the sum of company loans
	$activeSheet->mergeCells('A'.$rowCounter.':C'.$rowCounter);//total overall loans
	$activeSheet->mergeCells('D'.$rowCounter.':E'.$rowCounter);//government loan space
	$activeSheet->mergeCells('F'.$rowCounter.':G'.$rowCounter);//company loan space
	$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total Company Loans');
	$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($companyGrandtotal, 2, '.', true));
}
else
{
	$activeSheet->mergeCells('A'.$rowCounter.':G'.$rowCounter);//total overall loans
	$activeSheet->setCellValue('A'.$rowCounter, 'No loans report as of the moment.');
}

//Style for the Spreadsheet
if($loansBool)// if period is set to All
{
	$activeSheet->getStyle('A1:G2')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A3:G'.$rowCounter)->applyFromArray($border_all_thin);//Content
	$activeSheet->getStyle('A1:G'.$rowCounter)->applyFromArray($align_center);//Centered header text
}
else
{
	$activeSheet->getStyle('A1:G3')->applyFromArray($border_all_medium);//Header 
	$activeSheet->getStyle('A4:G'.$rowCounter)->applyFromArray($border_all_thin);//Content
	$activeSheet->getStyle('A1:G'.$rowCounter)->applyFromArray($align_center);//Centered header text
}
$activeSheet->getColumnDimension('A')->setAutoSize(true);
$activeSheet->getColumnDimension('B')->setAutoSize(true);
$activeSheet->getColumnDimension('C')->setAutoSize(true);
$activeSheet->getColumnDimension('D')->setAutoSize(true);
$activeSheet->getColumnDimension('E')->setAutoSize(true);
$activeSheet->getColumnDimension('F')->setAutoSize(true);
$activeSheet->getColumnDimension('G')->setAutoSize(true);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













