<?php
include_once 'modules/Classes/PHPExcel.php';
// try{

  /* Some test data */
  $data = array(
    array(1, 10   , 2             ,),
    array(3, 'qqq', 'some string' ,),
  );

  $objPHPExcel = new PHPExcel();
  $activeSheet = $objPHPExcel -> createSheet(0);
  // $activeSheet->setActiveSheetIndex(0);

  /* Fill the excel sheet with the data */
  $rowI = 0;
  foreach($data as $row){
    $colI = 0;
    foreach($row as $v){
      $colChar = PHPExcel_Cell::stringFromColumnIndex($colI++);
      $cellId = $colChar.($rowI+1);
      $activeSheet->SetCellValue($cellId, $v);
    }
    $rowI++;
  }

  // $styleArr = array( 'borders' => array(
  //                       'allborders' => array(
  //                                 'style' => 'medium',
  //                                 'color' => array('rgb' => 'FF0000FF')
  //                               )
  //                           ));

     // $styleArr = array( 'fill' => array(
     //                    'color' => 
     //                            array(
     //                              'rgb' => 'FF0000FF')
     //                            )
     //                        );
     $activeSheet-> getStyle('A1:C2')->
                    getFill()->
                    setFillType(PHPExcel_Style_Fill::FILL_SOLID)->
                    getStartColor()->
                    setRGB('FF851B');//Header 
    

  // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  // header('Content-Disposition: attachment;filename="export.xlsx"');
  // header('Cache-Control: max-age=0');

  // $objWriter = PHPExcel_IOFactory::createWriter($activeSheet, 'Excel2007');
  // $objWriter->save('php://output');

  header('Content-Type: application/vnd.ms-excel');
  header('Content-Disposition: attachment; filename="sample.xls"');
  header('Cache-Control: max-age=0');

  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
  $objWriter->save('php://output');
  exit;

// }catch(Exception $e){
//   echo $e->__toString();
// }
?>