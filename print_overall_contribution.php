<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';
include('directives/print_styles.php'); //Styles for PHPexcel

$site = $_GET['site'];
$period = $_GET['period'];
$date = $_GET['date'];

$contributionDisplay = $_GET['contribution'];
$contributionType = strtolower($_GET['contribution']);

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

$rowCounter = 4; // Start for the data in the row of excel

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
			$filename = "Overall ".$contributionDisplay." Contributions for ".$site.".xls";
			
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
			$activeSheet->setCellValue('D2', $contributionDisplay);
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
$rowCounter = 4;
// * ======= Data feeding ======= * //
if($contributionType == 'all') //Overall
{
	if($period == "week")
	{
		$employee = "SELECT * FROM employee WHERE site = '$site'";
		$empQuery = mysql_query($employee) or die (mysql_error());
		$contBool = false;//if employee dont have sss contribution
		if(mysql_num_rows($empQuery))//there's employee in the site
		{
			$overallContributions = 0;
			while($empArr = mysql_fetch_assoc($empQuery))
			{
				$empid = $empArr['empid'];
				
				$changedPeriod = $date;
				if($changedPeriod == 'all'){
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
				else {
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date= '$changedPeriod' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
			
				$payrollDateQuery = mysql_query($payrollDate);
				
				while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
				{
					
					//For the specfied week in first column
					$payDay = $payDateArr['date'];
					$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
					$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

					$payroll = "SELECT * FROM payroll WHERE date = '$payDay' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					$payrollQuery = mysql_query($payroll);
					if(mysql_num_rows($payrollQuery) > 0)
					{
						$payrollArr = mysql_fetch_assoc($payrollQuery);
						$monthly = $payrollArr['rate'] * 25;

						//Boolean to know if employee has sss/philhealth/contribution
						$sssBool = false;
						$philhealthBool = false;
						$pagibigBool = false;

						//pre set value
						$sssContribution = 0;
						$pagibigContribution = 0;
						$philhealthContribution = 0;
						//pre set sub total
						$sssContributionSub = 0;
						$pagibigContributionSub = 0;
						$philhealthContributionSub = 0;

						if($payrollArr['sss'] != 0)
						{
							$contBool = true;
							$sssBool = true;
							$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

							$sssContribution = $sssEmployer;

							$sssContributionSub = $sssContribution + $payrollArr['sss'];
						}
						if($payrollArr['philhealth'] != 0)
						{
							$contBool = true;
							$philhealthBool = true;

							$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the sss table

							$philhealthContribution = $philhealthEmployer;
							$philhealthContributionSub = $philhealthContribution + $payrollArr['philhealth'];
						
						}
						if($payrollArr['pagibig'] != 0)
						{
							$contBool = true;
							$pagibigBool = true;

							$pagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the sss table

							$pagibigContribution = $pagibigEmployer;
							$pagibigContributionSub = $pagibigContribution + $payrollArr['pagibig'];
						}
						//conputes the subtotal 
						$totalOverallContribution = $pagibigContributionSub + $philhealthContributionSub + $sssContributionSub;

						$activeSheet->setCellValue('A'.$rowCounter, $startDate." - ".$endDate);
						$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
						$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); 

						//SSS
						if($sssBool)
						{
							$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($payrollArr['sss'], 2, '.', true));
							$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($sssContribution, 2, '.', true));
						}
						else
						{
							$activeSheet->mergeCells('D'.$rowCounter.':E'.$rowCounter); 
							$activeSheet->setCellValue('D'.$rowCounter, 'No Document');
						}

						//Pagibig
						if($pagibigBool)	
						{
							$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($payrollArr['pagibig'], 2, '.', true));
							$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($pagibigContribution, 2, '.', true));
						}			
							
						else
						{
							$activeSheet->mergeCells('F'.$rowCounter.':G'.$rowCounter); 
							$activeSheet->setCellValue('F'.$rowCounter, 'No Document');
						}
						//Philhealth
						if($philhealthBool)
						{
							$activeSheet->setCellValue('H'.$rowCounter, numberExactFormat($payrollArr['philhealth'], 2, '.', true));
							$activeSheet->setCellValue('I'.$rowCounter, numberExactFormat($philhealthContribution, 2, '.', true));
						}
						else
						{
							$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
							$activeSheet->setCellValue('H'.$rowCounter, 'No Document');
						}

						$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($totalOverallContribution, 2, '.', true));

						$overallContributions += $totalOverallContribution;
						$rowCounter++;
					}
				}
			}
		}
		if($contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':G'.$rowCounter); 
			$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
			$activeSheet->setCellValue('H'.$rowCounter, 'Grand Total');
			$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($overallContributions, 2, '.', true));
		}
		if(!$contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':J'.$rowCounter); 
			$activeSheet->setCellValue('A'.$rowCounter, 'No Report data as of the moment');
		}
	}
		
	else if($period == "month")
	{
		$employee = "SELECT * FROM employee WHERE site = '$site'";
		$empQuery = mysql_query($employee) or die (mysql_error());
		$contBool = false;//if employee dont have sss contribution
		$overallSSS = 0;
		if(mysql_num_rows($empQuery))//there's employee in the site
		{
			
			$contBool = false;//if employee dont have sss contribution
			
			if($date != "all")
			{
				$changedPeriod = explode(' ',$date);
				$monthPeriod = $changedPeriod[0];
				$yearPeriod = $changedPeriod[1];
				$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
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

				$contBool = true;
				$EEContribution = 0;
				$ERContribution = 0;
				$totalContribution = 0;

				$sssBool = false;
				$philhealthBool = false;
				$pagibigBool = false;

				//pre set value
				$sssContribution = 0;
				$pagibigContribution = 0;
				$philhealthContribution = 0;
				//pre set sub total
				$sssEEContribution = 0;
				$philhealthEEContribution = 0;
				$pagibigEEContribution = 0;

				$sssERContribution = 0;
				$philhealthERContribution = 0;
				$pagibigERContribution = 0;

				$subTotalContribution = 0;
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
						//Boolean to know if employee has sss/philhealth/contribution
						


						
						while($payrollArr = mysql_fetch_assoc($payrollQuery))
						{
							
							$monthly = $payrollArr['rate'] * 25;
							if($payrollArr['sss'] != 0)
							{
								$contBool = true;
								$sssBool = true;


								$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

								$sssERContribution += $sssEmployer;
								$sssEEContribution += $payrollArr['sss'];

								$sssContribution = $sssERContribution + $sssEEContribution;
								
							}
							if($payrollArr['pagibig'] != 0)
							{
								$contBool = true;
								$pagibigBool = true;


								$pagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the sss table

								$pagibigERContribution += $pagibigEmployer;
								$pagibigEEContribution += $payrollArr['pagibig'];


								$pagibigContribution = $pagibigERContribution + $pagibigEEContribution;

								
								
								

							}
							if($payrollArr['philhealth'] != 0)
							{
								$contBool = true;
								$philhealthBool = true;

								$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the philhealth table
								$philhealthERContribution += $philhealthEmployer;
								$philhealthEEContribution += $payrollArr['philhealth'];

								$philhealthContribution = $philhealthERContribution + $philhealthEEContribution;
								
								
								
							}
						}
						if($contBool)
						{
							if($monthNoRepeat != $month.$year)
							{
								$subTotalContribution += $philhealthContribution + $pagibigContribution + $sssContribution;
								$totalContribution += $subTotalContribution;

								$activeSheet->setCellValue('A'.$rowCounter, $month." ".$year);
								$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
								$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']);
							
								//SSS
								if($sssBool)
								{

									$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($sssEEContribution, 2, '.', true));
									$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($sssERContribution, 2, '.', true));
								}
								else
								{
									$activeSheet->mergeCells('D'.$rowCounter.':E'.$rowCounter); 
									$activeSheet->setCellValue('D'.$rowCounter, 'No Document');		
								}


								//Pagibig
								if($pagibigBool)		
								{
									$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($philhealthEEContribution, 2, '.', true));
									$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($philhealthERContribution, 2, '.', true));
								}		
								else
								{
									$activeSheet->mergeCells('F'.$rowCounter.':G'.$rowCounter); 
									$activeSheet->setCellValue('F'.$rowCounter, 'No Document');		
									
								}
								//Philhealth

								if($philhealthBool)
								{
									$activeSheet->setCellValue('H'.$rowCounter, numberExactFormat($pagibigEEContribution, 2, '.', true));
									$activeSheet->setCellValue('I'.$rowCounter, numberExactFormat($pagibigERContribution, 2, '.', true));
								}
								else
								{
									$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
									$activeSheet->setCellValue('H'.$rowCounter, 'No Document');	
									
								}
								$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($subTotalContribution, 2, '.', true));

								$rowCounter++;
								$totalContribution += $subTotalContribution;
							}
							
						}
					}


					$monthNoRepeat = $month.$year;

				}
			}
		}
		if($contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':G'.$rowCounter); 
			$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
			$activeSheet->setCellValue('H'.$rowCounter, 'Grand Total');
			$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($totalContribution, 2, '.', true));
		}
		if(!$contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':J'.$rowCounter); 
			$activeSheet->setCellValue('A'.$rowCounter, 'No Report data as of the moment');
		}


	}
	else if($period = "year")//dito
	{
		$employee = "SELECT * FROM employee WHERE site = '$site'";
		$empQuery = mysql_query($employee) or die (mysql_error());
		$contBool = false;//if employee dont have sss contribution
		$overallSSS = 0;
		if(mysql_num_rows($empQuery))//there's employee in the site
		{
			
			$contBool = false;//if employee dont have sss contribution
			
			if($date != "all")
			{
				$yearPeriod = $date;
				$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
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

				$contBool = true;
				$EEContribution = 0;
				$ERContribution = 0;
				$totalContribution = 0;

				$sssBool = false;
				$philhealthBool = false;
				$pagibigBool = false;

				//pre set value
				$sssContribution = 0;
				$pagibigContribution = 0;
				$philhealthContribution = 0;
				//pre set sub total
				$sssEEContribution = 0;
				$philhealthEEContribution = 0;
				$pagibigEEContribution = 0;

				$sssERContribution = 0;
				$philhealthERContribution = 0;
				$pagibigERContribution = 0;

				$subTotalContribution = 0;
				//Evaluates the attendance and compute the sss contribution
				while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
				{
					$dateExploded = explode(" ", $payDateArr['date']);
					$year = $dateExploded[2];// gets the year

					$payrollDay = $payDateArr['date'];

					$payroll = "SELECT * FROM payroll WHERE  date LIKE '%$year' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					$payrollQuery = mysql_query($payroll);
					if(mysql_num_rows($payrollQuery) > 0)
					{
						//Boolean to know if employee has sss/philhealth/contribution
						
						while($payrollArr = mysql_fetch_assoc($payrollQuery))
						{
							
							$monthly = $payrollArr['rate'] * 25;
							if($payrollArr['sss'] != 0)
							{
								$contBool = true;
								$sssBool = true;

								$sssEmployer = $payrollArr['sss_er'];//Gets the value in the sss table

								$sssERContribution += $sssEmployer;
								$sssEEContribution += $payrollArr['sss'];

								$sssContribution = $sssERContribution + $sssEEContribution;
								
							}
							if($payrollArr['pagibig'] != 0)
							{
								$contBool = true;
								$pagibigBool = true;

								$pagibigEmployer = $payrollArr['pagibig_er'];//Gets the value in the sss table

								$pagibigERContribution += $pagibigEmployer;
								$pagibigEEContribution += $payrollArr['pagibig'];

								$pagibigContribution = $pagibigERContribution + $pagibigEEContribution;

								
								
								
							}
							if($payrollArr['philhealth'] != 0)
							{

								$contBool = true;
								$philhealthBool = true;

								$philhealthEmployer = $payrollArr['philhealth_er'];//Gets the value in the philhealth table
								$philhealthERContribution += $philhealthEmployer;
								$philhealthEEContribution += $payrollArr['philhealth'];

								$philhealthContribution = $philhealthERContribution + $philhealthEEContribution;
								
								
							}
						}
						if($contBool)
						{
							if($yearNoRepeat != $year)
							{
								$subTotalContribution += $philhealthContribution + $pagibigContribution + $sssContribution;

								$totalContribution += $subTotalContribution;

								$yearBefore = $year - 1;

								$activeSheet->setCellValue('A'.$rowCounter, $yearBefore." - ".$year);
								$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']);
								$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']);

								//SSS
								if($sssBool)
								{
									$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($sssEEContribution, 2, '.', true));
									$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($sssERContribution, 2, '.', true));
								}
								else
								{
									$activeSheet->mergeCells('D'.$rowCounter.':E'.$rowCounter); 
									$activeSheet->setCellValue('D'.$rowCounter, 'No Document');
								}

								//Pagibig
								if($pagibigBool)	
								{
									$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($pagibigEEContribution, 2, '.', true));
									$activeSheet->setCellValue('G'.$rowCounter, numberExactFormat($pagibigERContribution, 2, '.', true));
								}			
									
								else
								{
									$activeSheet->mergeCells('F'.$rowCounter.':G'.$rowCounter); 
									$activeSheet->setCellValue('F'.$rowCounter, 'No Document');
								}
								//Philhealth
								if($philhealthBool)
								{
									$activeSheet->setCellValue('H'.$rowCounter, numberExactFormat($philhealthEEContribution, 2, '.', true));
									$activeSheet->setCellValue('I'.$rowCounter, numberExactFormat($philhealthERContribution, 2, '.', true));
								}
								else
								{
									$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
									$activeSheet->setCellValue('H'.$rowCounter, 'No Document');
								}

								$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($subTotalContribution, 2, '.', true));

								$rowCounter++;
								$totalContribution += $subTotalContribution;
							}
							
						}
					}

					$yearNoRepeat = $year;
				}
			}
		}
		if($contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':G'.$rowCounter); 
			$activeSheet->mergeCells('H'.$rowCounter.':I'.$rowCounter); 
			$activeSheet->setCellValue('H'.$rowCounter, 'Grand Total');
			$activeSheet->setCellValue('J'.$rowCounter, numberExactFormat($totalContribution, 2, '.', true));
		}
		if(!$contBool)
		{
			$activeSheet->mergeCells('A'.$rowCounter.':J'.$rowCounter); 
			$activeSheet->setCellValue('A'.$rowCounter, 'No Report data as of the moment');
		}

	}
					

$activeSheet->getStyle('A1:J'.$rowCounter)->applyFromArray($border_all_medium); 
$activeSheet->getStyle('A4:J'.$rowCounter)->applyFromArray($border_all_thin); 


}
else {
	// * ======= Data Feeding ======= * //
		// Get contribution details for selected site

	$appendQuery = "";
	if($date != "all")
	{
		$dateExplode = explode(' ', $date);
		if($period == "month")
		{
			$monthQuery = $dateExplode[0];
			$yearQuery = $dateExplode[1];
			$appendQuery .= "AND (date LIKE '".$monthQuery."%' AND date LIKE '%".$yearQuery."') ";
		}
		else if($period == "year")
			$appendQuery .= "AND date LIKE '%".$date."' ";
		else// week
			$appendQuery .= "AND date = '".$date."' ";
	}
		

		$rowCounter = 4; // Start for the data in the row of excel

		$GrandTotal = 0;
		$GrandTotalContribution = 0;
		$totalEmployee = 0;
		$totalEmployer = 0;

		$activeSheet->setCellValue('A1', 'Overall '.$contributionDisplay.' Contribution at '.$site);



		if($period === 'week') {
			$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
			$empQuery = mysql_query($employee) or die (mysql_error());
			$contributionBool = false;//if employee dont have sss contribution
			if(mysql_num_rows($empQuery))//there's employee in the site
			{
				$overallContribution = 0;
				while($empArr = mysql_fetch_assoc($empQuery))
				{
					$empid = $empArr['empid'];
					
					$changedPeriod = $date;

					if($changedPeriod == 'all'){
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE empid = '$empid' $appendQuery ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					}
					else {
						$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date = '$changedPeriod' AND empid = '$empid' $appendQuery ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
					}
						
					

					$payrollDateQuery = mysql_query($payrollDate) or die(mysql_error());
					
					while($payDateArr = mysql_fetch_assoc($payrollDateQuery))
					{
						
						//For the specfied week in first column
						$payDay = $payDateArr['date'];
						$endDate = date('F d, Y', strtotime('-1 day', strtotime($payDateArr['date'])));
						$startDate = date('F d, Y', strtotime('-6 day', strtotime($endDate)));

						$payroll = "SELECT * FROM payroll WHERE date = '$payDay' AND empid = '$empid' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
						$payrollQuery = mysql_query($payroll);
						if(mysql_num_rows($payrollQuery) > 0)
						{
							$payrollArr = mysql_fetch_assoc($payrollQuery);
							if($payrollArr[$contributionType] != 0)
							{
								$contributionBool = true;

								$totalEmployer += $payrollArr[$contributionType.'_er'];
								$totalEmployee += $payrollArr[$contributionType];
								
								$activeSheet->setCellValue('A'.$rowCounter, $startDate.' - '.$endDate); // Period

								$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
								$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

								$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($payrollArr[$contributionType], 2, ".", true)); // Employee
								$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($payrollArr[$contributionType.'_er'], 2, ".", true)); // Employer
								$GrandTotal = $payrollArr[$contributionType] + $payrollArr[$contributionType.'_er'];
								$GrandTotalContribution += $payrollArr[$contributionType] + $payrollArr[$contributionType.'_er'];
								$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($GrandTotal, 2, ".", true)); // Employer
								$rowCounter++;
							}
						}
					}
				}
			
			}
		}
		else if($period === 'month') {

			$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
			$empQuery = mysql_query($employee) or die (mysql_error());
			$contributionBool = false;//if employee dont have Philhealth contribution
			$GrandTotalContribution = 0;
			if(mysql_num_rows($empQuery))//there's employee in the site
			{
				
				$contributionBool = false;//if employee dont have Philhealth contribution
				if($date == 'all')
				{
					$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
				else 
				{
					$changedPeriod = explode(' ',$date);
					$monthPeriod = $changedPeriod[0];
					$yearPeriod = $changedPeriod[1];
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE (date LIKE '$monthPeriod%' AND date LIKE '%$yearPeriod') ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
				
				while($empArr = mysql_fetch_assoc($empQuery))
				{
					$empid = $empArr['empid'];

					$payrollDateQuery = mysql_query($payrollDate);

					$monthNoRepeat = "";

					$contributionBool = true;

					$totalEmployee = 0;
					$totalEmployer = 0;
					$GrandTotal = 0;
					//Evaluates the attendance and compute the Philhealth contribution
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
								if($payrollArr[$contributionType] != 0)
								{
									$contributionBool = true;

									$totalEmployer += $payrollArr[$contributionType.'_er'];//Gets the value in the Philhealth table
									$totalEmployee += $payrollArr[$contributionType];

								}
								else
								{
									$contributionBool = false;
								}
							}
									
							if($contributionBool)
							{
								if($monthNoRepeat != $month.$year)
								{
									$GrandTotal = $totalEmployer + $totalEmployee;
									$GrandTotalContribution += $GrandTotal;

									$activeSheet->setCellValue('A'.$rowCounter, $month.' - '.$year); // Period
									$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
									$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

									$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($totalEmployee, 2, ".", true)); // Employee
									$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($totalEmployer, 2, ".", true)); // Employer
									$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($GrandTotal, 2, ".", true)); 

									$rowCounter++;

								}
								
							}
						}

						$monthNoRepeat = $month.$year;
					}
				}
			}
		}
		else if($period === 'year') {

			$employee = "SELECT * FROM employee WHERE employment_status = '1' AND site = '$site'";
			$empQuery = mysql_query($employee) or die (mysql_error());
			$contributionBool = false;//if employee dont have Philhealth contribution
			$GrandTotalContribution = 0;
			if(mysql_num_rows($empQuery))//there's employee in the site
			{
				
				$contributionBool = false;//if employee dont have Philhealth contribution
				if($date == 'all')
				{
					$payrollDate = "SELECT DISTINCT date FROM payroll ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
				else 
				{
					$changedPeriod = explode(' ',$date);
					$yearPeriod = $changedPeriod[0];
					$payrollDate = "SELECT DISTINCT date FROM payroll WHERE date LIKE '%$yearPeriod' ORDER BY STR_TO_DATE(date, '%M %e, %Y')  ASC";
				}
				
				while($empArr = mysql_fetch_assoc($empQuery))
				{
					$empid = $empArr['empid'];

					$payrollDateQuery = mysql_query($payrollDate);

					$yearNoRepeat = "";

					$contributionBool = true;

					$totalEmployee = 0;
					$totalEmployer = 0;
					$GrandTotal = 0;
					//Evaluates the attendance and compute the Philhealth contribution
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
								if($payrollArr[$contributionType] != 0)
								{
									$contributionBool = true;

									$totalEmployer += $payrollArr[$contributionType.'_er'];//Gets the value in the Philhealth table
									$totalEmployee += $payrollArr[$contributionType];

								}
								else
								{
									$contributionBool = false;
								}
							}
									
							if($contributionBool)
							{
								if($yearNoRepeat != $year)
								{
									$yearBefore = $year - 1;
									$GrandTotal = $totalEmployer + $totalEmployee;
									$GrandTotalContribution += $GrandTotal;

									$activeSheet->setCellValue('A'.$rowCounter, $yearBefore.' - '.$year); // Period
									$activeSheet->setCellValue('B'.$rowCounter, $empArr['lastname'].", ".$empArr['firstname']); // Name
									$activeSheet->setCellValue('C'.$rowCounter, $empArr['position']); // Position

									$activeSheet->setCellValue('D'.$rowCounter, numberExactFormat($totalEmployee, 2, ".", true)); // Employee
									$activeSheet->setCellValue('E'.$rowCounter, numberExactFormat($totalEmployer, 2, ".", true)); // Employer
									$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($GrandTotal, 2, ".", true)); 

									$rowCounter++;

								}
							}
						}
						$yearNoRepeat = $year;
					}
				}
			}
		}
			
		$activeSheet->mergeCells('A'.$rowCounter.':E'.$rowCounter);
		$activeSheet->setCellValue('A'.$rowCounter, 'Grand Total');
		$activeSheet->setCellValue('F'.$rowCounter, numberExactFormat($GrandTotalContribution, 2, ".", true)); // Total
		$activeSheet->getStyle('A1:F'.$rowCounter)->applyFromArray($border_all_medium); 
		$activeSheet->getStyle('A4:F'.$rowCounter)->applyFromArray($border_all_thin); 
		}

		
		

		$activeSheet->getStyle('A'.$rowCounter)->applyFromArray($align_right); // Centered header text	
		$activeSheet->getStyle('B4:B'.$rowCounter)->applyFromArray($align_left);
	// END OF DATA FEEDING...


	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
	$objWriter->save('php://output');
	exit;

?>













