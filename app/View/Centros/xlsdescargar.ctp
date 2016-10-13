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

// Datos de los Centros
// Creamos la primera hoja como hoja activa la primera y fijamos el título:
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Centros');
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Centros TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:P1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Activo'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Tipología'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('P. Ajustada'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Polaridad'));
$objPHPExcel->getActiveSheet()->setCellValue("G3", __('Equipo'));
$objPHPExcel->getActiveSheet()->setCellValue("H3", __('Fuente Mux Nacional'));
$objPHPExcel->getActiveSheet()->setCellValue("I3", __('Fuente Mux Autonómico'));
$objPHPExcel->getActiveSheet()->setCellValue("J3", __('Longitud'));
$objPHPExcel->getActiveSheet()->setCellValue("K3", __('Latitud'));
$objPHPExcel->getActiveSheet()->setCellValue("L3", __('Referencia Catastral'));
$objPHPExcel->getActiveSheet()->setCellValue("M3", __('Provincia'));
$objPHPExcel->getActiveSheet()->setCellValue("N3", __('Prop. Suelo'));
$objPHPExcel->getActiveSheet()->setCellValue("O3", __('Prop. Caseta'));
$objPHPExcel->getActiveSheet()->setCellValue("P3", __('Prop. Torre'));
$objPHPExcel->getActiveSheet()->setCellValue("Q3", __('Sum. Eléctrico'));
$objPHPExcel->getActiveSheet()->setCellValue("R3", __('Info'));
$objPHPExcel->getActiveSheet()->getStyle('A3:R3')->applyFromArray($estiloTh);
// Imprimimos los Centros:
$fila =  $finicio = 3;
$relleno = true;
foreach ($centros as $centro) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $centro['Centro']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $centro['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $centro['Centro']['activo']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $centro['Centro']['tipologia']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $centro['Centro']['pajustada']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $centro['Centro']['polaridad']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $fila, $centro['Centro']['equipo']);
    $objPHPExcel->getActiveSheet()->setCellValue("H" . $fila, $centro['Centro']['depmuxnac']);
    $objPHPExcel->getActiveSheet()->setCellValue("I" . $fila, $centro['Centro']['depmuxaut']);
    $objPHPExcel->getActiveSheet()->setCellValue("J" . $fila, $centro['Centro']['longitud']);
    $objPHPExcel->getActiveSheet()->setCellValue("K" . $fila, $centro['Centro']['latitud']);
    $objPHPExcel->getActiveSheet()->setCellValue("L" . $fila, $centro['Centro']['catastro']);
    $objPHPExcel->getActiveSheet()->getCell("M$fila")->setValueExplicit($centro['Centro']['provincia'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue("N" . $fila, $centro['Centro']['suelo']);
    $objPHPExcel->getActiveSheet()->setCellValue("O" . $fila, $centro['Centro']['caseta']);
    $objPHPExcel->getActiveSheet()->setCellValue("P" . $fila, $centro['Centro']['torre']);
    $objPHPExcel->getActiveSheet()->setCellValue("Q" . $fila, $centro['Centro']['electrico']);
    $objPHPExcel->getActiveSheet()->setCellValue("R" . $fila, $centro['Centro']['info']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'R'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Datos de las Emisiones
// Creamos nueva hoja y la fijamos como activa
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
// Fijamos el título de la Hoja:
$objPHPExcel->getActiveSheet()->setTitle("Emisiones");
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Emisiones TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Centro_ID'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Multiple_ID'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Multiple'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Canal'));
$objPHPExcel->getActiveSheet()->setCellValue("G3", __('Tipo'));
$objPHPExcel->getActiveSheet()->getStyle('A3:G3')->applyFromArray($estiloTh);
// Imprimimos los Datos:
$fila =  $finicio = 3;
$relleno = true;
foreach ($emisions as $emision) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $emision['Emision']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $emision['Emision']['centro_id']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $emision['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $emision['Emision']['multiple_id']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $emision['Multiple']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $emision['Emision']['canal']);
    $objPHPExcel->getActiveSheet()->setCellValue("G" . $fila, $emision['Emision']['tipo']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'G'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Datos de los Múltiples
// Creamos nueva hoja y la fijamos como activa
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
// Fijamos el título de la Hoja:
$objPHPExcel->getActiveSheet()->setTitle("Múltiples");
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Múltiples TDT de la Comunitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Nombre'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Ámbito'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Soportado'));
$objPHPExcel->getActiveSheet()->getStyle('A3:D3')->applyFromArray($estiloTh);
// Imprimimos los Datos:
$fila =  $finicio = 3;
$relleno = true;
foreach ($multiples as $multiple) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $multiple['Multiple']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $multiple['Multiple']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $multiple['Multiple']['ambito']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $multiple['Multiple']['soportado']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'D'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Datos de los Programas
// Creamos nueva hoja y la fijamos como activa
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
// Fijamos el título de la Hoja:
$objPHPExcel->getActiveSheet()->setTitle("Programas");
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Programas TDT de la Comunitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Nombre'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Múltiple_ID'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('Múltiple'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Codificado'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('HD'));
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTh);
// Imprimimos los Datos:
$fila =  $finicio = 3;
$relleno = true;
foreach ($programas as $programa) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $programa['Programa']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $programa['Programa']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $programa['Programa']['multiple_id']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, $programa['Multiple']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $programa['Programa']['codificado']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $programa['Programa']['altadef']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'F'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Datos de los Municipios
// Creamos nueva hoja y la fijamos como activa
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(4);
// Fijamos el título de la Hoja:
$objPHPExcel->getActiveSheet()->setTitle("Municipios");
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Municipios de la Comunitat (Censo 01/01/2015)'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('INE'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('C. Prov'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Provincia'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('C. Mun'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Municipio'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Población'));
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTh);
// Imprimimos los Datos:
$fila =  $finicio = 3;
$relleno = true;
foreach ($municipios as $municipio) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->getCell("A" . $fila)->setValueExplicit($municipio['Municipio']['id'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->getCell("B" . $fila)->setValueExplicit($municipio['Municipio']['cpro'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $municipio['Municipio']['provincia']);
    $objPHPExcel->getActiveSheet()->getCell("D" . $fila)->setValueExplicit($municipio['Municipio']['cmun'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $municipio['Municipio']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $municipio['Municipio']['poblacion']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'F'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Datos de los Coberturas
// Creamos nueva hoja y la fijamos como activa
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(5);
// Fijamos el título de la Hoja:
$objPHPExcel->getActiveSheet()->setTitle("Coberturas");
// Título de la Hoja:
$objPHPExcel->getActiveSheet()->setCellValue('A1', __('Coberturas de los Centros TDT de la Generalitat'));
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($estiloTitulo);
$objPHPExcel->getActiveSheet()->mergeCells('A1:F1');
// Fila de títulos:
$objPHPExcel->getActiveSheet()->setCellValue("A3", __('ID'));
$objPHPExcel->getActiveSheet()->setCellValue("B3", __('Centro_ID'));
$objPHPExcel->getActiveSheet()->setCellValue("C3", __('Centro'));
$objPHPExcel->getActiveSheet()->setCellValue("D3", __('INE'));
$objPHPExcel->getActiveSheet()->setCellValue("E3", __('Municipio'));
$objPHPExcel->getActiveSheet()->setCellValue("F3", __('Porcentaje'));
$objPHPExcel->getActiveSheet()->getStyle('A3:F3')->applyFromArray($estiloTh);
// Imprimimos los Datos:
$fila =  $finicio = 3;
$relleno = true;
foreach ($coberturas as $cobertura) {
    $fila++;
    $relleno = !($relleno);
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $cobertura['Cobertura']['id']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $cobertura['Cobertura']['centro_id']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $cobertura['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->getCell("D" . $fila)->setValueExplicit($cobertura['Cobertura']['municipio_id'], PHPExcel_Cell_DataType::TYPE_STRING);
    $objPHPExcel->getActiveSheet()->setCellValue("E" . $fila, $cobertura['Municipio']['nombre']);
    $objPHPExcel->getActiveSheet()->setCellValue("F" . $fila, $cobertura['Cobertura']['porcentaje']);
    if ($relleno){
        $objPHPExcel->getActiveSheet()->getStyle('A'.$fila.':'.'F'.$fila)->applyFromArray($estiloRelleno);
    }
}
$colmax = $objPHPExcel->getActiveSheet()->getHighestColumn();
$objPHPExcel->getActiveSheet()->getStyle('A' . $finicio .':' . $colmax . $fila)->applyFromArray($estiloCelda);
$maxcol = PHPExcel_Cell::columnIndexFromString($colmax);
for ($i = 0; $i < $maxcol; $i++){
    $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($i)->setAutoSize(true);
}

// Dejamos como hoja activa la primera
$objPHPExcel->setActiveSheetIndex(0);

// Generamos el fichero:
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="BBDD_TDT-GVA.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
