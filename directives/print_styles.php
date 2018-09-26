<?php
//Style of the spreadsheet
$signature = array(
                'alignment' => array(
                  'horizontal' => 'right',
                  'vertical' => 'center'
                )
              );

$title_style = array(
           		 	'alignment' => array(
           		 		'horizontal' => 'center',
           		 		'vertical' => 'center'
           		 	),
           		 	'font' => array(
           		 		'style' => 'underline'
           		 	)
				);

$border_all_thin = array(	
					'borders' => array(
						'allborders' => array(
                			'style' => 'thin',
                			'color' => array('rgb' => '000000')
                		)
           		 	),
           		 	'alignment' => array(
           		 		'horizontal' => 'center',
           		 		'vertical' => 'center'
           		 	)
				);

$border_all_medium = array( 
          'borders' => array(
            'allborders' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    )
                ),
                'alignment' => array(
                  'horizontal' => 'center',
                  'vertical' => 'center'
                )
        );

$border_allsides_medium = array(  
          'borders' => array(
            'top' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    ),
            'bottom' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    ),
            'left' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    ),
            'right' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    )
                )
        );

$border_buttom_left_thin = array(  
          'borders' => array(
            'bottom' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    ),
            'left' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    )
                )
          );


$border_buttom_thin = array(  
          'borders' => array(
            'bottom' => array(
                      'style' => 'medium',
                      'color' => array('rgb' => '000000')
                    )
                )
          );

$align_center = array(  
          'alignment' => array(
                  'horizontal' => 'center',
                  'vertical' => 'center'
                )
        );

$align_right = array(
          'alignment' => array(
                  'horizontal' => 'right',
                  'vertical' => 'center'
                )
        );
$align_left = array(
          'alignment' => array(
                  'horizontal' => 'left',
                  'vertical' => 'center'
                )
        );

$font_bold = array(
          'font' => array(
                  'bold' => true
                )
        );

$border_thin = array(  
          'borders' => array(
            'allborders' => array(
                      'style' => 'thin',
                      'color' => array('rgb' => '000000')
                    )
                )
        );


$border_top_double = array(  
          'borders' => array(
            'top' => array(
                      'style' => 'double',
                      'color' => array('rgb' => '000000')
                    )
                )
        );
$payroll_font = array(
                  'font' => array(
                    'name' => 'Calibri',
                    'size' => 40
                  )
                );
$grand_total_font = array(
                  'font' => array(
                    'name' => 'Calibri',
                    'size' => 18
                  )
                );
$data_font = array(
                  'font' => array(
                    'name' => 'Calibri',
                    'size' => 15
                  )
                );
$column_header_font = array(
                  'font' => array(
                    'name' => 'Calibri',
                    'size' => 11
                  )
                );
$font_red = array(
              'font'  => array(
                  'color' => array('rgb' => 'FF0000'),
            ));

?>