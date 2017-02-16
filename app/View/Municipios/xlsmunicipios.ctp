<?php

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
$objPHPExcel->getActiveSheet()->setTitle('Municipios_TDT');

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
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Cobertura de Municipios de la Comunitat Valenciana'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');

// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('Cod. INE'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Provincia'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Municipio'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Habitantes (2016)'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Hogares (2011)'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Idioma'));
$objPHPExcel->getActiveSheet()->setCellValue("G3", __('Centros TDT'));
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTh);

// Imprimimos los Municipios:
$fila =  $finicio = 3;
foreach ($municipios as $municipio) {
    $fila++;
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $municipio['Municipio']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $municipio['Municipio']['provincia']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $municipio['Municipio']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $municipio['Municipio']['poblacion']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $municipio['Municipio']['hogares']);
    $idioma = __('Castellano');
    if ($municipio['Municipio']['idioma'] == 'VA'){
        $idioma = __('Valencià');
    }
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $idioma);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $fila, count($municipio['Cobertura']));
    if (($fila % 2) == 1){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'G'.$fila)->applyFromArray($estiloRelleno);
    }
}
$filafin = $fila;
$objPHPExcel->getActiveSheet()->getStyle('A'.$finicio.':'.'G'.$fila)->applyFromArray($estiloCelda);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

// Generamos el fichero:
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Municipios_TDT.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
