<?php
include_once 'modules/Classes/PHPExcel.php';

$sheet = new PHPExcel();

$activeSheet = $sheet -> createSheet(0);

$activeSheet->setCellValue('A1', 'Good');
$activeSheet->setCellValue('B1', 'hello world!');
$activeSheet->setTitle('Chesse1');

$nextsheet = $sheet -> createSheet(1);

$border_style = array(	
					'borders' => array(
						'allborders' => array(
                			'style' => 'thin',
                			'color' => array('rgb' => '000000')
                		)
           		 	)
				);

$nextsheet->setCellValue('A1', 'Good');
$nextsheet->setCellValue('B1', 'hello world!');
$nextsheet->setCellValue('C1', 'hello world!');
$nextsheet->setTitle('Chesse2');
//$nextsheet->getDefaultStyle()->applyFromArray($BStyle);


//$sheet = $objPHPExcel->getActiveSheet();
$nextsheet->getStyle("A1:B20")->applyFromArray($border_style);
               


header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="report.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel5');
$objWriter->save('php://output');
exit;
?>