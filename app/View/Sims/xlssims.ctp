<?php
$ntarjetas = count($sims);

// Importamos la clase para generar el PDF
App::import('Vendor', 'Classes/PHPExcel');

//Creamos el Excel
date_default_timezone_set('Europe/Madrid');
$objPHPExcel = new PHPExcel();
$locale = 'Es';
$validLocale = PHPExcel_Settings::setLocale($locale);

// Set properties
$objPHPExcel->getProperties()->setCreator("Servici de Telecomunicacions");
$objPHPExcel->getProperties()->setLastModifiedBy("Servici de Telecomunicacions");
$objPHPExcel->getProperties()->setTitle(__("Centros TDT de la Generalitat"));
$objPHPExcel->getProperties()->setSubject(__("TDT GVA"));
$objPHPExcel->getProperties()->setDescription(__("Centros TDT de la Generalitat"));
$objPHPExcel->getProperties()->setKeywords(__("TDT GVA"));
$objPHPExcel->getProperties()->setCategory(__("TDT GVA"));

// Creamos la primera hoja como hoja activa la primera y fijamos el título:
//$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Tarjetas_SIM');

// Fijamos los estilos generales de la hoja:
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

// Estilos para la hoja:
$estiloTitulo = array(
    'font' => array('bold' => true, 'size' => 12),
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
);
$estiloRelleno = array(
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('argb' => 'FFEFEFEF'),
    )
);
$estiloCelda = array(
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
);
$estiloTh = array(
    'font' => array('bold' => true, 'size' => 10),
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
);
$estiloCentro = array(
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
);


// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Tarjetas de Supervisión de Centros TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');

// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Número'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('ICC'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Uso'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Cobertura'));
$objPHPExcel->getActiveSheet()->setCellValue("G3", __('Dirección IP'));
$objPHPExcel->getActiveSheet()->setCellValue("H3", __('Usuario'));
$objPHPExcel->getActiveSheet()->setCellValue("I3", __('Contraseña'));
$objPHPExcel->getActiveSheet()->setCellValue("J3", __('Comentarios'));
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($estiloTh);
// Imprimimos los Centros:
$fila =  $finicio = 3;
foreach ($sims as $sim) {
    $fila++;
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $sim['Sim']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $sim['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $sim['Sim']['numero']);
    $objPHPExcel->getActiveSheet()->getCell("D" . $fila)->setValueExplicit($sim['Sim']['icc'], PHPExcel_Cell_DataType::TYPE_STRING);
    if ($sim['Sim']['uso'] == "TDT"){
        $uso = __("Supervisión TDT");
    }
    elseif ($sim['Sim']['uso'] == "SUP"){
        $uso = __("Supervisión Centro");
    }
    else{
        $uso = __("Otros usos");
    }
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $uso);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $sim['Sim']['cobertura']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $fila, $sim['Sim']['dir_ip']);
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $fila, $sim['Sim']['usuario']);
    $objPHPExcel->getActiveSheet()->setCellValue("I" . $fila, $sim['Sim']['contrasenya']);
    $objPHPExcel->getActiveSheet()->setCellValue("J" . $fila, $sim['Sim']['comentarios']);
    if (($fila % 2) == 1){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'J'.$fila)->applyFromArray($estiloRelleno);
    }
}
$filafin = $fila;
$objPHPExcel->getActiveSheet()->getStyle('A'.$finicio.':'.'J'.$filafin)->applyFromArray($estiloCelda);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
// Generamos el fichero:
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Tarjetas_TDT-GVA.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
