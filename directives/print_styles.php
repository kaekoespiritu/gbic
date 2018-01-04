<?php
//Style of the spreadsheet
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


$align_center = array(  
          'alignment' => array(
                  'horizontal' => 'center',
                  'vertical' => 'center'
                )
        );
?>