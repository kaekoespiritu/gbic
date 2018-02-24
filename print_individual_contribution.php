<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php'); //Styles for PHPexcel

$empid = $_GET['empid'];
$period = $_GET['period'];
$contributionType = $_GET['contribution'];

// Getting employee info for filename
$employee = "SELECT * FROM employee WHERE empid = '$empid'";
$empquery = mysql_query($employee);
$empArr = mysql_fetch_assoc($empquery);
$firstname = $empArr['firstname'];
$lastname = $empArr['lastname'];
$site = $empArr['site'];
$position = $empArr['position'];

$filename = $lastname.", ".$firstname."'s ".$contributionType." Contributions.xls";
// Filename: Last name, First name SSS contribution

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

// Merge cells
$activeSheet->mergeCells('A1:D1'); // Header
$activeSheet->mergeCells('A2:A3'); // Period
$activeSheet->mergeCells('B2:C2'); // Contribution Type Header
$activeSheet->mergeCells('D2:D3'); // Total

// Header
$activeSheet->setCellValue('A1', 'Last name, First name - Position at Site');
$activeSheet->setCellValue('A2', ucfirst($period).'s');
$activeSheet->setCellValue('B2', 'SSS'); // Contribution type
$activeSheet->setCellValue('D2', 'Total');
$activeSheet->setCellValue('B3', 'Employee');
$activeSheet->setCellValue('C3', 'Employer');

// Style
// ---  Centering text
$activeSheet->getStyle('A')->applyFromArray($align_center); // Centered Period text
$activeSheet->getStyle('A2')->applyFromArray($align_center); // Centered Period text
$activeSheet->getStyle('D2')->applyFromArray($align_center); // Centered Total text
$activeSheet->getStyle('B2')->applyFromArray($align_center); // Centered header 
$activeSheet->getStyle('A1')->applyFromArray($align_center); // Centered header text

// --- Lengthen cell width
$activeSheet->getColumnDimension('A')->setAutoSize(true); // Lengthen period

// Contents
// Get contribution details for selected employee
$contributions = "SELECT * FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') DESC";
$contributionsQuery = mysql_query($contributions) or die (mysql_error());
$rowCounter = 4; // Start for the data in the row of excel
$monthlyRowCounter = 4;
$GrandTotal = $totalEmployee = $totalEmployer = $monthlyCounter = 0;
$monthNoRep = "";

$activeSheet->setCellValue('A1', $lastname.', '.$firstname.' '.$position.' at '.$site);

$contributionBool = false;
$ERContribution = $EEContribution = $totalSSSContribution = $overallSSS = 0;

while($contributionsArr = mysql_fetch_assoc($contributionsQuery)){

	$endDate = $contributionsArr['date'];
	$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));

	$arraySize = mysql_num_rows($contributionsQuery);

	if($period==='week') {
		switch($contributionType) {
			case 'SSS':
				$totalEmployee += $contributionsArr['sss'];
				$totalEmployer += $contributionsArr['sss_er'];
				
				$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period
				$activeSheet->setCellValue('B'.$rowCounter, $contributionsArr['sss']); // Employee
				$activeSheet->setCellValue('C'.$rowCounter, $contributionsArr['sss_er']); // Employer
				$activeSheet->setCellValue('D'.$rowCounter, $contributionsArr['sss'] + $contributionsArr['sss_er']); // Total
			break;
			case 'PhilHealth':
				$totalEmployee += $contributionsArr['philhealth'];
				$totalEmployer += $contributionsArr['philhealth_er'];
				
				$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period
				$activeSheet->setCellValue('B'.$rowCounter, $contributionsArr['philhealth']); // Employee
				$activeSheet->setCellValue('C'.$rowCounter, $contributionsArr['philhealth_er']); // Employer
				$activeSheet->setCellValue('D'.$rowCounter, $contributionsArr['philhealth'] + $contributionsArr['philhealth_er']); // Total
			break;
			case 'PagIbig':
				$totalEmployee += $contributionsArr['pagibig'];
				$totalEmployer += $contributionsArr['pagibig_er'];
				
				$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period
				$activeSheet->setCellValue('B'.$rowCounter, $contributionsArr['pagibig']); // Employee
				$activeSheet->setCellValue('C'.$rowCounter, $contributionsArr['pagibig_er']); // Employer
				$activeSheet->setCellValue('D'.$rowCounter, $contributionsArr['pagibig'] + $contributionsArr['pagibig_er']); // Total
			break;
		}
		$rowCounter++;
	}

	
}

	if($period == "month") {

		$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

		$payrollDateQuery = mysql_query($payrollDate);

		$contributionBool = false;

		//Evaluates the attendance and compute the sss contribution
		while($payDateArr = mysql_fetch_assoc($payrollDateQuery)) {
			$dateExploded = explode(" ", $payDateArr['date']);
			$month = $dateExploded[0];//gets the month
			$year = $dateExploded[2];// gets the year

			$payrollDay = $payDateArr['date'];

			//Print "<script>console.log('".$month." - ".$year."')</script>";

			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '$month%' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$payrollQuery = mysql_query($payroll);
			
			if(mysql_num_rows($payrollQuery) > 0) {
				$contributionBool = true;
				$EEContribution = 0;
				$ERContribution = 0;
				$totalSSSContribution = 0;

				//prevent from repeating the same month
				if($monthNoRep != $month.$year) {
					while($payrollArr = mysql_fetch_assoc($payrollQuery)) {
						if($contributionType === 'SSS') {
							if($payrollArr['sss'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['sss'];
								$totalEmployer += $payrollArr['sss_er'];
							}
						}

						elseif($contributionType === 'PhilHealth') {
							if($payrollArr['philhealth'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['philhealth'];
								$totalEmployer += $payrollArr['philhealth_er'];
							}
						}

						elseif($contributionType === 'PagIbig') {
							if($payrollArr['pagibig'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['pagibig'];
								$totalEmployer += $payrollArr['pagibig_er'];
							}
						}
						else {
							$contributionBool = false;
						}
					}
				}

				if($contributionBool) {
					if($monthNoRep != $month.$year) {

					$activeSheet->setCellValue('A'.$rowCounter, $month.' '.$year); // Period
					$activeSheet->setCellValue('B'.$rowCounter, $totalEmployee); // Employee
					$activeSheet->setCellValue('C'.$rowCounter, $totalEmployer); // Employer
					$activeSheet->setCellValue('D'.$rowCounter, $totalEmployee + $totalEmployer); // Total
					$rowCounter++;

					}
				}

				$monthNoRep = $month.$year;
			}
			else {
				$contributionBool = true;
			}

		}
	}

	if($period == "year") {
		
		$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";

		$payrollDateQuery = mysql_query($payrollDate);

		//gets the overall sss total
		$overallSSS = 0;

		$contributionBool = false;//if employee dont have sss contribution

		$yearNoRepeat = "";
		//Evaluates the attendance and compute the sss contribution
		while($payDateArr = mysql_fetch_assoc($payrollDateQuery)) {
			$dateExploded = explode(" ", $payDateArr['date']);
			$year = $dateExploded[2];// gets the year
			$payrollDay = $payDateArr['date'];

			$payroll = "SELECT * FROM payroll WHERE empid = '$empid' AND date LIKE '%$year' ORDER BY STR_TO_DATE(date, '%M %e, %Y') ASC";
			$payrollQuery = mysql_query($payroll);
			if(mysql_num_rows($payrollQuery) > 0) {
				$contributionBool = true;

				//prevent from repeating the same month
				if($yearNoRepeat != $year) {
					while($payrollArr = mysql_fetch_assoc($payrollQuery)) {
						if($contributionType === 'SSS') {
							if($payrollArr['sss'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['sss'];
								$totalEmployer += $payrollArr['sss_er'];
							}
						}

						elseif($contributionType === 'PhilHealth') {
							if($payrollArr['philhealth'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['philhealth'];
								$totalEmployer += $payrollArr['philhealth_er'];
							}
						}

						elseif($contributionType === 'PagIbig') {
							if($payrollArr['pagibig'] != 0) {
								$contributionBool = true;
								$totalEmployee += $payrollArr['pagibig'];
								$totalEmployer += $payrollArr['pagibig_er'];
							}
						}
						else {
							$contributionBool = false;
						}
					}
				}
				if($contributionBool) {
					if($yearNoRepeat != $year) {
						$yearBefore = $year - 1;

					$activeSheet->setCellValue('A'.$rowCounter, $yearBefore ." - ".$year); // Period
					$activeSheet->setCellValue('B'.$rowCounter, $totalEmployee); // Employee
					$activeSheet->setCellValue('C'.$rowCounter, $totalEmployer); // Employer
					$activeSheet->setCellValue('D'.$rowCounter, $totalEmployee + $totalEmployer); // Total
					$rowCounter++;

					}
				}

				$yearNoRepeat = $year;

				
			}
			else {
				$contributionBool = true;
			}

		}

	} 

$GrandTotal = $totalEmployee + $totalEmployer;
$activeSheet->mergeCells('A'.$rowCounter.':C'.$rowCounter);
$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total');
$activeSheet->setCellValue('D'.$rowCounter, $GrandTotal); // Total
$activeSheet->getStyle('A1:D'.$rowCounter)->applyFromArray($border_all_thin); 
$activeSheet->getStyle('A'.$rowCounter)->applyFromArray($align_right); // Centered header text

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;

?>













