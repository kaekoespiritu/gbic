<?php
include_once 'modules/Classes/PHPExcel.php';
try{

  /* Some test data */
  $data = array(
    array(1, 10   , 2             ,),
    array(3, 'qqq', 'some string' ,),
  );

  $objPHPExcel = new PHPExcel();
  $objPHPExcel->setActiveSheetIndex(0);

  /* Fill the excel sheet with the data */
  $rowI = 0;
  foreach($data as $row){
    $colI = 0;
    foreach($row as $v){
      $colChar = PHPExcel_Cell::stringFromColumnIndex($colI++);
      $cellId = $colChar.($rowI+1);
      $objPHPExcel->getActiveSheet()->SetCellValue($cellId, $v);
    }
    $rowI++;
  }

  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename="export.xlsx"');
  header('Cache-Control: max-age=0');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');

}catch(Exception $e){
  echo $e->__toString();
}
?>