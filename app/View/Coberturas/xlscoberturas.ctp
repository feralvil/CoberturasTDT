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

// Creamos la primera hoja como hoja activa la primera y fijamos el título:
//$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Coberturas_TDT');

// Fijamos los estilos generales de la hoja:
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Cobertura de Centros TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:J1');

// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('ID_Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Nº Múx'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Código INE'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Provincia'));
$objPHPExcel->getActiveSheet()->setCellValue("G3", __('Municipio'));
$objPHPExcel->getActiveSheet()->setCellValue("H3", __('Población (2016)'));
$objPHPExcel->getActiveSheet()->setCellValue("I3", __('% Cobertura'));
$objPHPExcel->getActiveSheet()->setCellValue("J3", __('Hab. Cubiertos'));
$objPHPExcel->getActiveSheet()->getStyle('A3:J3')->applyFromArray($estiloTh);

// Imprimimos los Municipios:
$fila =  $finicio = 3;
foreach ($coberturas as $cobertura) {
    $fila++;
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $cobertura['Cobertura']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $cobertura['Centro']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $cobertura['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $cobertura['nmux']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $cobertura['Municipio']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $cobertura['Municipio']['provincia']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $fila, $cobertura['Municipio']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $fila, $cobertura['Municipio']['poblacion']);
    $objPHPExcel->getActiveSheet()->setCellValue("I" . $fila, $cobertura['Cobertura']['porcentaje']);
    $habcub = round($cobertura['Municipio']['poblacion'] * $cobertura['Cobertura']['porcentaje'] / 100);
    $objPHPExcel->getActiveSheet()->setCellValue("J" . $fila, $this->Number->format($habcub, array('places' => 0, 'before' => '', 'thousands' => '.')));
    if (($fila % 2) == 1){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'J'.$fila)->applyFromArray($estiloRelleno);
    }
}

// Bordes de Celda
$filafin = $fila;
$objPHPExcel->getActiveSheet()->getStyle('A'.$finicio.':'.'J'.$fila)->applyFromArray($estiloCelda);

// Ajuste de Columna
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
$fecha = date('Y-m-d');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="CoberturasTDT_' . $fecha . '.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;

?>
