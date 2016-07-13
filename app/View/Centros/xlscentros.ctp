<?php
$ncentros = count($centros);
$totHabitantes = 0;

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
$objPHPExcel->getActiveSheet()->setTitle('Centros_TDT');

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
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Centros TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:O1');


// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Provincia'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Tipología'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Nº Múltiples'));
$columna = array('F', 'G', 'H', 'I', 'J', 'K');
$i = 0;
foreach ($multiples as $nom_mux) {
    //$celda = $columna[$i] . '3';
    $objPHPExcel->getActiveSheet()->setCellValue($columna[$i] . '3', $nom_mux);
    $i++;
}
$objPHPExcel->getActiveSheet()->setCellValue("L3", __('Equipo'));
$objPHPExcel->getActiveSheet()->setCellValue("M3", __('Polaridad'));
$objPHPExcel->getActiveSheet()->setCellValue("N3", __('Referencia Catastral'));
$objPHPExcel->getActiveSheet()->setCellValue("O3", __('Municipios Cubiertos'));
$objPHPExcel->getActiveSheet()->setCellValue("P3", __('Habitantes Cubiertos'));
$objPHPExcel->getActiveSheet()->getStyle('A3:P3')->applyFromArray($estiloTh);
// Imprimimos los Centros:
$fila =  $finicio = 3;
foreach ($centros as $centro) {
    $fila++;
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $centro['Centro']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $centro['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $centro['Centro']['provincia']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $centro['Centro']['tipologia']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, count($centro['Emision']));
    $idcol = 0;
    foreach ($multiples as $nom_mux) {
        $objPHPExcel->getActiveSheet()->setCellValue($columna[$idcol] . $fila , $centro['Centro'][$nom_mux]);
        $idcol++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue("L" . $fila, $centro['Centro']['equipo']);
    $objPHPExcel->getActiveSheet()->setCellValue("M" . $fila, $centro['Centro']['polaridad']);
    $objPHPExcel->getActiveSheet()->setCellValue("N" . $fila, $centro['Centro']['catastro']);
    $objPHPExcel->getActiveSheet()->setCellValue("O" . $fila, count($centro['Cobertura']));
    $objPHPExcel->getActiveSheet()->setCellValue("P" . $fila, $centro['Centro']['habCubiertos']);
    $totHabitantes = $totHabitantes + $centro['Centro']['habCubiertos'];
    if (($fila % 2) == 1){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'P'.$fila)->applyFromArray($estiloRelleno);
    }
}
$filafin = $fila;
$objPHPExcel->getActiveSheet()->getStyle('A'.$finicio.':'.'P'.$filafin)->applyFromArray($estiloCelda);
$objPHPExcel->getActiveSheet()->getStyle('D'.$finicio.':'.'K'.$filafin)->applyFromArray($estiloCentro);
$fila++;
$objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, __('Total Habitantes Cubiertos'));
$objPHPExcel->getActiveSheet()->mergeCells('A'.$fila.':'.'O'.$fila);
$objPHPExcel->getActiveSheet()->setCellValue("P" . $fila, $this->Number->format($totHabitantes, array('places' => 0, 'before' => '', 'thousands' => '.')));
$objPHPExcel->getActiveSheet()->getStyle('P'.$fila)->getNumberFormat()->setFormatCode('#.##0');
if (($fila % 2) == 1){
    $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'P'.$fila)->applyFromArray($estiloRelleno);
}
$objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'P'.$fila)->applyFromArray($estiloCelda);
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
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);

// Generamos el fichero:
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="CentrosTDT_GVA.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
