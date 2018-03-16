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
	if($contributionType == "all") {
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
if($contributionType == 'all') {
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
			ORDER BY STR_TO_DATE(payroll.date, '%M %e, %Y') DESC, employee.empid";

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

		$rowCounter = 4; // Start for the data in the row of excel

		$GrandTotalSSS = $GrandTotalPhilHealth = $GrandTotalPagIBIG = $totalEmployeeSSS = $totalEmployerSSS = $totalEmployeePhilHealth = $totalEmployerPhilHealth = $totalEmployeePagIBIG = $totalEmployerPagIBIG = "";

		$activeSheet->setCellValue('A1', 'Overall '.$contributionType.' Contribution at '.$site);


		if($contributionType === 'SSS') {
			if($period === 'week') {
				$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
				$empQuery = mysql_query($employee) or die (mysql_error());
				$sssBool = false;//if employee dont have sss contribution
				if(mysql_num_rows($empQuery))//there's employee in the site
				{
					$overallSSS = 0;
					while($empArr = mysql_fetch_assoc($empQuery))
					{
						$empid = $empArr['empid'];
						if(isset($date))
						{
							$changedPeriod = $date;
							if($changedPeriod == 'all'){
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
							else {
								$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date= '$changedPeriod' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							}
							
						}
						else
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";

						$payrollDateQuery = mysql_query($payrollDate);
						
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							
							//For the specfied week in first column
							$endDate = $payDateArr['date'];
							$startDate = date('F j, Y', strtotime('-6 day', strtotime($endDate)));

							$payroll = "SELECT * FROM payroll WHERE date = '$endDate' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								$payrollArr = mysql_fetch_assoc($payrollQuery);
								if($payrollArr['sss'] != 0)
								{
									$sssBool = true;

									$totalEmployerSSS += $payrollArr['sss_er'];
									$totalEmployeeSSS += $payrollArr['sss'];
									
									$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period

									$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
									$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

									$activeSheet->setCellValue('D'.$rowCounter, $totalEmployeeSSS); // Employee
									$activeSheet->setCellValue('E'.$rowCounter, $totalEmployerSSS); // Employer
									$GrandTotalSSS = $totalEmployerSSS + $totalEmployeeSSS;
									$activeSheet->setCellValue('F'.$rowCounter, $GrandTotalSSS); // Employer
								}
							}
						}
					}
				$rowCounter++;
				}
			}

			if($period === 'month') {

				$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
				$empQuery = mysql_query($employee) or die (mysql_error());
				$sssBool = false;//if employee dont have sss contribution
				$overallSSS = 0;
				if(mysql_num_rows($empQuery))//there's employee in the site
				{
					
					$sssBool = false;//if employee dont have sss contribution
					if(isset($date))
					{
						
						if($date == "all"){
							$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						}
						else {
							$changedPeriod = explode(' ',$date);
							$monthPeriod = $changedPeriod[0];
							$yearPeriod = $changedPeriod[1];
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						}
					}
					else
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					}
					while($empArr = mysql_fetch_assoc($empQuery))
					{
						$empid = $empArr['empid'];

						$payrollDateQuery = mysql_query($payrollDate);

						$monthNoRepeat = "";

						$sssBool = true;

						//Evaluates the attendance and compute the sss contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$month = $dateExploded[0];//gets the month
							$year = $dateExploded[2];// gets the year

							$payrollDay = $payDateArr['date'];

							$payroll = "SELECT * FROM payroll WHERE (date LIKE '$month%' AND date LIKE '%$year') AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								

								while($payrollArr = mysql_fetch_assoc($payrollQuery))
								{
									if($payrollArr['sss'] != 0)
									{
										$sssBool = true;
										$totalEmployerSSS += $payrollArr['sss_er'];
										$totalEmployeeSSS += $payrollArr['sss'];
									}
									else
									{
										$sssBool = false;
									}
								}
								if($sssBool)
								{
									if($monthNoRepeat != $month.$year)
									{

										$activeSheet->setCellValue('A'.$rowCounter, $month.' - '.$year); // Period

										$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
										$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

										$activeSheet->setCellValue('D'.$rowCounter, $totalEmployeeSSS); // Employee
										$activeSheet->setCellValue('E'.$rowCounter, $totalEmployerSSS); // Employer
										$GrandTotalSSS = $totalEmployerSSS + $totalEmployeeSSS;
										$activeSheet->setCellValue('F'.$rowCounter, $GrandTotalSSS); // Employer
									}
									
								}
							}

							$monthNoRepeat = $month.$year;
						}
					}
					$rowCounter++;
				}
			}

			if($period === 'year') {
				$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
				$empQuery = mysql_query($employee) or die (mysql_error());
				$sssBool = false;//if employee dont have sss contribution
				$overallSSS = 0;
				if(mysql_num_rows($empQuery))//there's employee in the site
				{
					
					$sssBool = false;//if employee dont have sss contribution
					if(isset($date))
					{
						if($date == 'all'){
							$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						}
						else {
							$changedPeriod = explode(' ',$date);
							$yearPeriod = $changedPeriod[0];
							$payrollDate = "SELECT DISTINCT date FROM payroll WHERE  date LIKE '%$yearPeriod' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						}
					}
					else
					{
						$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					}
					while($empArr = mysql_fetch_assoc($empQuery))
					{
						$empid = $empArr['empid'];

						$payrollDateQuery = mysql_query($payrollDate);

						$yearNoRepeat = "";

						$sssBool = true;

						//Evaluates the attendance and compute the sss contribution
						while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
						{
							$dateExploded = explode(" ", $payDateArr['date']);
							$year = $dateExploded[2];// gets the year

							$payrollDay = $payDateArr['date'];

							$payroll = "SELECT * FROM payroll WHERE date LIKE '%$year' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
							$payrollQuery = mysql_query($payroll);
							if(mysql_num_rows($payrollQuery) > 0)
							{
								
								while($payrollArr = mysql_fetch_assoc($payrollQuery))
								{
									if($payrollArr['sss'] != 0)
									{
										$sssBool = true;

										$totalEmployerSSS += $payrollArr['sss_er'];
										$totalEmployeeSSS += $payrollArr['sss'];
										
									}
									else
									{
										$sssBool = false;
									}
								}
								if($sssBool)
								{
									if($yearNoRepeat != $year)
									{

										$yearBefore = $year - 1;
										
										$activeSheet->setCellValue('A'.$rowCounter, $yearBefore.' - '.$year); // Period

										$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
										$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

										$activeSheet->setCellValue('D'.$rowCounter, $totalEmployeeSSS); // Employee
										$activeSheet->setCellValue('E'.$rowCounter, $totalEmployerSSS); // Employer
										$GrandTotalSSS = $totalEmployerSSS + $totalEmployeeSSS;
										$activeSheet->setCellValue('F'.$rowCounter, $GrandTotalSSS); // Employer

									}
									
								}
							}

							$yearNoRepeat = $year;
						}
					}
				$rowCounter++;
				}
			}
		}

		if($contributionType === 'PhilHealth') {
			if($period === 'week') {

			}
			if($period === 'month') {

			}
			if($period === 'year') {

			}
		}

		if($contributionType === 'PagIbig') {
			if($period === 'week') {

			}
			if($period === 'month') {

			}
			if($period === 'year') {

			}
		}

		switch($contributionType){
			case 'SSS':
				$GrandTotal = $GrandTotalSSS;
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













