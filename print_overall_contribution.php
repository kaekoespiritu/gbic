<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php'); //Styles for PHPexcel

$site = $_GET['site'];
$period = $_GET['period'];
$contributionType = $_GET['contribution'];
$date = $_GET['date'];

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

// * ======= Styling tables ======= * //
	if($contributionType == "All") {
		// * ======= Styling table Overall ======= * //
			$filename = "Overall Contributions for ".$site.".xls";
			// Merge cells
			$activeSheet->mergeCells('A1:J1'); // Header
			$activeSheet->mergeCells('A2:A3'); // Period
			$activeSheet->mergeCells('B2:B3'); // Name
			$activeSheet->mergeCells('C2:C3'); // Position
			$activeSheet->mergeCells('D2:E2'); // Contribution Header
			$activeSheet->mergeCells('F2:G2'); // Contribution Header
			$activeSheet->mergeCells('H2:I2'); // Contribution Header
			$activeSheet->mergeCells('J2:J3'); // Total

			// Fill in Header text
			$activeSheet->setCellValue('A1', 'Overall Site Contribution	');
			$activeSheet->setCellValue('A2', ucwords($period));
			$activeSheet->setCellValue('B2', 'Name');
			$activeSheet->setCellValue('C2', 'Position');
			$activeSheet->setCellValue('D2', 'SSS');
			$activeSheet->setCellValue('F2', 'Pagibig');
			$activeSheet->setCellValue('H2', 'Philhealth');
			$activeSheet->setCellValue('J2', 'Total');
			$activeSheet->setCellValue('D3', 'Employee');
			$activeSheet->setCellValue('E3', 'Employer');
			$activeSheet->setCellValue('F3', 'Employee');
			$activeSheet->setCellValue('G3', 'Employer');
			$activeSheet->setCellValue('H3', 'Employee');
			$activeSheet->setCellValue('I3', 'Employer');


			// Centering text
			$activeSheet->getStyle('A1')->applyFromArray($align_center); // Centered header text
			$activeSheet->getStyle('A2')->applyFromArray($align_center); // Centered Period type text
			$activeSheet->getStyle('B2')->applyFromArray($align_center); // Centered name text
			$activeSheet->getStyle('C2')->applyFromArray($align_center); // Centered position text
			$activeSheet->getStyle('D2')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('F2')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('H2')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('J2')->applyFromArray($align_center); // Centered contribution type text

			$activeSheet->getStyle('D3')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('E3')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('F3')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('G3')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('H3')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('I3')->applyFromArray($align_center); // Centered contribution type text

			
			// Changing cell width
			$activeSheet->getColumnDimension('A')->setAutoSize(true); // Lengthen Period
			$activeSheet->getColumnDimension('B')->setAutoSize(true); // Lengthen Name
			$activeSheet->getColumnDimension('C')->setAutoSize(true); // Lengthen Position
		// END OF STYLING...
	}
	else {
		// * ======= Styling table for SSS, Philhealth, Pagibig ======= * //
			$filename = "Overall ".$contributionType." Contributions for ".$site.".xls";
			
			// Merge cells
			$activeSheet->mergeCells('A1:F1'); // Header
			$activeSheet->mergeCells('A2:A3'); // Period
			$activeSheet->mergeCells('B2:B3'); // Name
			$activeSheet->mergeCells('C2:C3'); // Position
			$activeSheet->mergeCells('D2:E2'); // Contribution Header
			$activeSheet->mergeCells('F2:F3'); // Total
			// Fill in Header text
			$activeSheet->setCellValue('A1', 'Overall Site Contribution	');
			$activeSheet->setCellValue('A2', $period);
			$activeSheet->setCellValue('B2', 'Name');
			$activeSheet->setCellValue('C2', 'Position');
			$activeSheet->setCellValue('D2', $contributionType);
			$activeSheet->setCellValue('F2', 'Total');
			$activeSheet->setCellValue('D3', 'Employee');
			$activeSheet->setCellValue('E3', 'Employer');
			// Centering text
			$activeSheet->getStyle('A1')->applyFromArray($align_center); // Centered header text
			$activeSheet->getStyle('A2')->applyFromArray($align_center); // Centered Period type text
			$activeSheet->getStyle('B2')->applyFromArray($align_center); // Centered name text
			$activeSheet->getStyle('C2')->applyFromArray($align_center); // Centered position text
			$activeSheet->getStyle('D2')->applyFromArray($align_center); // Centered contribution type text
			$activeSheet->getStyle('F2')->applyFromArray($align_center); // Centered contribution type text
			
			// Changing cell width
			$activeSheet->getColumnDimension('A')->setAutoSize(true); // Lengthen Period
			$activeSheet->getColumnDimension('B')->setAutoSize(true); // Lengthen Name
			$activeSheet->getColumnDimension('C')->setAutoSize(true); // Lengthen Position
		// END OF STYLING...
	}
// END OF STYLING...


// * ======= Data feeding ======= * //
if($contributionType == 'All') {
	// * ======= Data Feeding Overall  ======= * //
		// Get contribution details for selected site
		$employeelist = "
			SELECT
				employee.empid,
				employee.lastname,
				employee.firstname,
				employee.position,
				payroll.date,
				payroll.sss,
				payroll.sss_er,
				payroll.philhealth,
				payroll.philhealth_er,
				payroll.pagibig,
				payroll.pagibig_er
			FROM `payroll` INNER JOIN employee ON payroll.empid=employee.empid
			WHERE employee.site = '$site'
			ORDER BY payroll.date DESC, employee.empid";

		switch($period) {
			case 'week':
				if($date != 'All') {
					$changedPeriod = $date;
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' AND date= '$changedPeriod' ORDER BY date ASC";
				}
				else {
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY date ASC";
				}
			break;
			case 'month':
			break;
			case 'year':
			break;
		}

		$employeelistQuery = mysql_query($employeelist) or die (mysql_error());

		$rowCounter = 4; // Start for the data in the row of excel


		$GrandTotalSSS = $GrandTotalPhilHealth = $GrandTotalPagIBIG = $totalEmployeeSSS = $totalEmployerSSS = $totalEmployeePhilHealth = $totalEmployerPhilHealth = $totalEmployeePagIBIG = $totalEmployerPagIBIG = 0;

		$activeSheet->setCellValue('A1', 'Overall Contributions at '.$site);

		while($employeelistArr = mysql_fetch_assoc($employeelistQuery)){

			$endDate = $employeelistArr['date'];
			$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));

			$name = $employeelistArr['lastname'] . ", " . $employeelistArr['firstname'];

			$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period

			$activeSheet->setCellValue('B'.$rowCounter, $name); // Name
			$activeSheet->setCellValue('C'.$rowCounter, $employeelistArr['position']); // Position

			$activeSheet->setCellValue('D'.$rowCounter, $employeelistArr['sss']); // Employee
			$activeSheet->setCellValue('E'.$rowCounter, $employeelistArr['sss_er']); // Employer
			
			$totalEmployeeSSS += $employeelistArr['sss'];
			$totalEmployerSSS += $employeelistArr['sss_er'];

			$activeSheet->setCellValue('F'.$rowCounter, $employeelistArr['philhealth']); // Employee
			$activeSheet->setCellValue('G'.$rowCounter, $employeelistArr['philhealth_er']); // Employer
			
			$totalEmployeePhilHealth += $employeelistArr['philhealth'];
			$totalEmployerPhilHealth += $employeelistArr['philhealth_er'];

			$activeSheet->setCellValue('H'.$rowCounter, $employeelistArr['pagibig']); // Employee
			$activeSheet->setCellValue('I'.$rowCounter, $employeelistArr['pagibig_er']); // Employer
			$activeSheet->setCellValue('J'.$rowCounter, $employeelistArr['sss'] + $employeelistArr['sss_er'] + $employeelistArr['philhealth'] + $employeelistArr['philhealth_er'] + $employeelistArr['pagibig'] + $employeelistArr['pagibig_er']); // Total for row
			
			$totalEmployeePagIBIG += $employeelistArr['pagibig'];
			$totalEmployerPagIBIG += $employeelistArr['pagibig_er'];

			$rowCounter++;

		}

		$GrandTotalSSS = $totalEmployeeSSS + $totalEmployerSSS;
		$GrandTotalPhilHealth = $totalEmployeePhilHealth + $totalEmployerPhilHealth;
		$GrandTotalPagIBIG = $totalEmployeePagIBIG + $totalEmployerPagIBIG;
		$GrandTotal = $GrandTotalSSS + $GrandTotalPagIBIG + $GrandTotalPhilHealth;

		$activeSheet->mergeCells('A'.$rowCounter.':I'.$rowCounter);
		$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total');
		$activeSheet->setCellValue('J'.$rowCounter, $GrandTotal); // Total
		$activeSheet->getStyle('A1:I'.$rowCounter)->applyFromArray($border_all_thin); 
		$activeSheet->getStyle('J1:J'.$rowCounter)->applyFromArray($border_all_thin); 
		$activeSheet->getStyle('A'.$rowCounter)->applyFromArray($align_right); // Centered header text
	// END OF DATA FEEDING...
}
else {
	// * ======= Data Feeding ======= * //
		// Get contribution details for selected site
		$contributionQuery = " AND payroll.".strtolower($contributionType)." != 0 ";
		$employeelist = "
			SELECT
				employee.empid,
				employee.lastname,
				employee.firstname,
				employee.position,
				payroll.date,
				payroll.sss,
				payroll.sss_er,
				payroll.philhealth,
				payroll.philhealth_er,
				payroll.pagibig,
				payroll.pagibig_er
			FROM `payroll` INNER JOIN employee ON payroll.empid=employee.empid
			WHERE employee.site = '$site' $contributionQuery
			ORDER BY STR_TO_DATE(payroll.date, '%M %e, %Y') DESC, employee.empid";

		$employeelistQuery = mysql_query($employeelist) or die (mysql_error());

		$rowCounter = 4; // Start for the data in the row of excel

		$GrandTotalSSS = $GrandTotalPhilHealth = $GrandTotalPagIBIG = $totalEmployeeSSS = $totalEmployerSSS = $totalEmployeePhilHealth = $totalEmployerPhilHealth = $totalEmployeePagIBIG = $totalEmployerPagIBIG = 0;

		$activeSheet->setCellValue('A1', 'Overall '.$contributionType.' Contribution at '.$site);

		while($employeelistArr = mysql_fetch_assoc($employeelistQuery)){

			$endDate = $employeelistArr['date'];
			$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));

			$name = $employeelistArr['lastname'] . ", " . $employeelistArr['firstname'];

			$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period

			$activeSheet->setCellValue('B'.$rowCounter, $name); // Name
			$activeSheet->setCellValue('C'.$rowCounter, $employeelistArr['position']); // Position

			switch($contributionType){
				case 'SSS':
					$activeSheet->setCellValue('D'.$rowCounter, $employeelistArr['sss']); // Employee
					$activeSheet->setCellValue('E'.$rowCounter, $employeelistArr['sss_er']); // Employer
					$activeSheet->setCellValue('F'.$rowCounter, $employeelistArr['sss'] + $employeelistArr['sss_er']); // Total
					$totalEmployeeSSS += $employeelistArr['sss'];
					$totalEmployerSSS += $employeelistArr['sss_er'];
				break;
				case 'PhilHealth':
					$activeSheet->setCellValue('D'.$rowCounter, $employeelistArr['philhealth']); // Employee
					$activeSheet->setCellValue('E'.$rowCounter, $employeelistArr['philhealth_er']); // Employer
					$activeSheet->setCellValue('F'.$rowCounter, $employeelistArr['philhealth'] + $employeelistArr['philhealth_er']); // Total
					$totalEmployeePhilHealth += $employeelistArr['philhealth'];
					$totalEmployerPhilHealth += $employeelistArr['philhealth_er'];
				break;
				case 'PagIbig':
					$activeSheet->setCellValue('D'.$rowCounter, $employeelistArr['pagibig']); // Employee
					$activeSheet->setCellValue('E'.$rowCounter, $employeelistArr['pagibig_er']); // Employer
					$activeSheet->setCellValue('F'.$rowCounter, $employeelistArr['pagibig'] + $employeelistArr['pagibig_er']); // Total
					$totalEmployeePagIBIG += $employeelistArr['pagibig'];
					$totalEmployerPagIBIG += $employeelistArr['pagibig_er'];
				break;
				case 'Overall':

				break;
			}

			$rowCounter++;


		}

		switch($contributionType){
			case 'SSS':
				$GrandTotal = $totalEmployeeSSS + $totalEmployerSSS;
			break;
			case 'PhilHealth':
				$GrandTotal = $totalEmployeePhilHealth + $totalEmployerPhilHealth;
			break;
			case 'PagIbig':
				$GrandTotal = $totalEmployeePagIBIG + $totalEmployerPagIBIG;
			break;
		}

		$activeSheet->mergeCells('A'.$rowCounter.':E'.$rowCounter);
		$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total');
		$activeSheet->setCellValue('F'.$rowCounter, $GrandTotal); // Total
		$activeSheet->getStyle('A1:F'.$rowCounter)->applyFromArray($border_all_thin); 

		$activeSheet->getStyle('A'.$rowCounter)->applyFromArray($align_right); // Centered header text	
	// END OF DATA FEEDING...
}



	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
	$objWriter->save('php://output');
	exit;

?>













