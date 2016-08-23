<?php

// Importamos la clase para generar el Ecel
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
$objPHPExcel->getActiveSheet()->setTitle('Eventos');

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
$estiloCriterio = array(
    'font' => array('bold' => true, 'size' => 10),
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
);
$estiloError = array(
    'font' => array('bold' => true, 'size' => 10, 'color' => array('rgb' => 'FF0000')),
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
);
$estiloCentro = array(
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
);


// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Eventos del Centro TDT de') . ' ' . $centro['Centro']['centro']);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:K1');

// Criterios de Selección:
$fila = 3;
if (count($criterios) > 0){
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, __('Criterios de Selección'));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->applyFromArray($estiloCriterio);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $fila . ':E' . $fila);
    $fila++;
    $col_crit = array('A', 'D', 'F', 'H');
    $encabezado = array(
        'nivel' =>  __('Nivel de Evento'), 'tipo' =>  __('Tipo de Evento'),
        'fechadesde' => __('Eventos desde'), 'fechahasta' => __('Eventos hasta')
    );
    $i = 0;
    foreach ($criterios as $indice => $criterio) {
        $thcrit = $encabezado[$indice];
        $tdcrit = $criterio;
        $columna = $col_crit[$i];
        $objPHPExcel->getActiveSheet()->setCellValue($columna . $fila, $thcrit . ': ' . $tdcrit);
        $objPHPExcel->getActiveSheet()->mergeCells($columna . $fila . ':' . $col_crit[$i+1] . $fila);
        $i = $i + 2;
        if ($i == 4){
            $fila++;
            $i = 0;
        }
    }
    $fila++;
    if ((count($criterios) % 2) == 1){
        $fila++;
    }
}

// Nº de Registros
$neventos = count($eventos);
if ($neventos > 0){
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, __('Nº de Eventos encontrados:') . ' ' . $neventos);
    $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->applyFromArray($estiloCriterio);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $fila . ':C' . $fila);
    $fila = $fila + 2;

    // Fila de títulos:
    $finicio = $fila;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, __('Centro'));
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, __('Ámbito'));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, __('Origen'));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, __('Fecha Activación'));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, __('Fecha Cese'));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, __('Nivel'));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, __('Tipo'));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, __('ID Alarma'));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, __('Alarma'));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, __('Valor Activación'));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, __('Valor Cese'));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $fila . ':K' . $fila)->applyFromArray($estiloTh);
    $fila++;

    // Imprimimos Eventos:
    foreach ($eventos as $evento) {
        $nomcentro = $evento['Evento']['centro'];
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, $evento['Evento']['centro']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $fila, $evento['Evento']['ambito']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $fila, $evento['Evento']['origen']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $fila, $evento['Evento']['fecha_act']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $fila, $evento['Evento']['fecha_cese']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $fila, $evento['Evento']['nivel']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $fila, $evento['Evento']['tipo']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $fila, $evento['Evento']['id_alarma']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $fila, $evento['Evento']['alarma']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $fila, $evento['Evento']['valor_act']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $fila, $evento['Evento']['valor_cese']);
        if (($fila % 2) == 1){
            $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'K'.$fila)->applyFromArray($estiloRelleno);
        }
        $fila++;
    }
    $filafin = $fila - 1;
    $objPHPExcel->getActiveSheet()->getStyle('A'.$finicio.':'.'K'.$filafin)->applyFromArray($estiloCelda);
}
else{
    $nomcentro = "-";
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fila, __('Error: No se han encontrado Eventos con los criterios de búsqueda seleccionados'));
    $objPHPExcel->getActiveSheet()->getStyle('A' . $fila)->applyFromArray($estiloError);
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $fila . ':K' . $fila);
}
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

// Generamos el fichero:
// Normalizamos el centro
$nomcentro = str_replace(' ', '_', $nomcentro);
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Eventos-TDT_' . $nomcentro .  '.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
