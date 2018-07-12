<?php
include('directives/session.php');
include_once('directives/db.php');
include_once 'modules/Classes/PHPExcel.php';

if(!isset($_POST['checkbox_submit']))
{
	header("location:login.php");
}
// TIMEZONE
date_default_timezone_set('Asia/Hong_Kong');
$date =  strftime("%h %e, %Y");
$filename = $date." - Attendance.xls";

$location_q = "SELECT * FROM site WHERE active = '1'";
$location_query = mysql_query($location_q);

if($location_query)
{
	$num_site = mysql_num_rows($location_query);
}
else
{
	Print "<script>alert('There is no Site nor employee to be printed');
			window.location.assign('attendance.php');</script>";
}

$sheet = new PHPExcel();
$page = 0;
for($counter = 0; $counter < $num_site; $counter++)//1st loop for sites
{
	
	if(isset($_POST['selectedSite'][$counter]))
	{
		$selected = $_POST['selectedSite'][$counter];
		$query = "SELECT * FROM site WHERE location = '$selected' AND active = '1'";
		$selected_query = mysql_query($query);
		$site = mysql_fetch_assoc($selected_query);

		$location = $site['location'];

		$activeSheet = $sheet -> createSheet($page);
		$page++;

		$activeSheet->setCellValue('A1', $location);
		$activeSheet->setCellValue('A2', 'ATTENDACE - '.$date);
		$activeSheet->setCellValue('B2', 'Author: admin1');

		$activeSheet->setCellValue('A3', 'NAME');
		$activeSheet->setCellValue('B3', 'TIME IN');
		$activeSheet->setCellValue('C3', 'TIME OUT');
		$activeSheet->setCellValue('D3', 'REMARKS');

		$activeSheet->mergeCells('A1:D1');
		$activeSheet->mergeCells('B2:D2');

		$activeSheet->getColumnDimension('A')->setWidth(30);
		$activeSheet->getColumnDimension('B')->setWidth(11);
		$activeSheet->getColumnDimension('C')->setWidth(11);
		$activeSheet->getColumnDimension('D')->setWidth(11);

		$activeSheet->getRowDimension(1)->setRowHeight(25);
		$activeSheet->getRowDimension(2)->setRowHeight(25);
		$activeSheet->getRowDimension(3)->setRowHeight(25);
		
		$employee = "SELECT * FROM employee WHERE site = '$selected' AND employment_status = '1' ORDER BY lastname";
		$employee_query = mysql_query($employee);
		if(!$employee_query)
		{
			Print "<script>alert('There is no employee in ". $location ." to be printed');
					window.location.assign('attendance.php');</script>";
		}
		
		$activeSheet->setTitle($location);
		
		$emp_count = 3;
		while($employees = mysql_fetch_assoc($employee_query))
		{
			$emp_count++;
			$activeSheet->setCellValue('A'.$emp_count, $employees['lastname'].', '.$employees['firstname']);
			$activeSheet->getRowDimension($emp_count)->setRowHeight(25);
			
			
		}
		$border_style = array(	
						'borders' => array(
							'allborders' => array(
	                			'style' => 'thin',
	                			'color' => array('rgb' => '000000')
	                		)
	           		 	),
	           		 	'font' => array(
	           		 		'name' => 'Calibri',
	           		 		'size' => 14
	           		 	)
					);
		$header = array(	
						'borders' => array(
							'allborders' => array(
	                			'style' => 'thin',
	                			'color' => array('rgb' => '000000')
	                		)
	           		 	),
	           		 	'font' => array(
	           		 		'bold' => true,
	           		 		'size' => 14
	           		 	),
	           		 	'alignment' => array(
	           		 		'horizontal' => 'center'
	           		 	)
	           		 );
		$site_header = 	array(	
			 				'fill' => array(
	                			'type' => 'solid',
	                			'color' => array('rgb' => '00994C')
	                		),
	                		'font' => array(
	                			'color' => array('rgb' => 'FFF2E5'),
	                			'size' => 18
	                		)
			 			);

		$activeSheet->getStyle("A1:D".$emp_count)->applyFromArray($border_style);
		$activeSheet->getStyle("A1:D3")->applyFromArray($header);
		$activeSheet->getStyle("A1")->applyFromArray($site_header);
	}
}

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;
?>