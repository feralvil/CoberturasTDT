<?php
$ncentros = count($centros);

$provincias = array('03' => 'Alicante', '12' => 'Castellón', '46' => 'Valencia');
$hojaAct = 0;
$provact = 'NN';

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

// Creamos la primera hoja como hoja activa:
$objPHPExcel->setActiveSheetIndex($hojaAct);

// Fijamos los estilos generales de la hoja:
$objPHPExcel->getDefaultStyle()->getFont()->setName('Calibri');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

// Estilos para la hoja:
$estiloCelda = array(
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER),
    'borders' => array(
        'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
        )
    ),
);
$estiloTh = array(
    'font' => array('bold' => true, 'size' => 10),
);
$estiloIzquierda = array(
    'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT),
);

// Imprimimos los Centros:
$centroact = 0;
foreach ($centros as $centro) {
    if ($centro['Centro']['provincia'] != $provact){
        $provact = $centro['Centro']['provincia'];
        if ($centroact > 0){
            $objPHPExcel->createSheet();
            $hojaAct++;
        }
        $objPHPExcel->setActiveSheetIndex($hojaAct);
        $nomhoja = $provincias[$centro['Centro']['provincia']];
        $objPHPExcel->getActiveSheet()->setTitle($nomhoja);
        $fila = $finicio = 1;
        // Fila de títulos:
        $objPHPExcel->getActiveSheet()->setCellValue("A1", __('Centro'));
        $objPHPExcel->getActiveSheet()->setCellValue("B1", __('Potencia (W)'));
        $objPHPExcel->getActiveSheet()->setCellValue("C1", __('Tipología'));
        $objPHPExcel->getActiveSheet()->setCellValue("D1", __('Nº Múltiples'));
        $columna = array('E', 'F', 'G', 'H', 'I', 'J');
        $i = 0;
        foreach ($multiples as $nom_mux) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$i] . '1', $nom_mux);
            $i++;
        }
        $objPHPExcel->getActiveSheet()->setCellValue("K1", __('Equipo'));
        $objPHPExcel->getActiveSheet()->setCellValue("L1", __('Supervisión'));
        $objPHPExcel->getActiveSheet()->setCellValue("M1", __('Conectividad Supervisión'));
    }
    $fila++;
    $objPHPExcel->getActiveSheet()->setCellValue("A" . $fila, $centro['Centro']['centro']);
    $objPHPExcel->getActiveSheet()->setCellValue("B" . $fila, $centro['Centro']['pajustada']);
    $objPHPExcel->getActiveSheet()->setCellValue("C" . $fila, $centro['Centro']['tipologia']);
    $objPHPExcel->getActiveSheet()->setCellValue("D" . $fila, count($centro['Emision']));
    $idcol = 0;
    foreach ($multiples as $nom_mux) {
        $objPHPExcel->getActiveSheet()->setCellValue($columna[$idcol] . $fila , $centro['Centro'][$nom_mux]);
        $idcol++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue("K" . $fila, $centro['Centro']['equipo']);
    $superv = "No";
    $cobertura = "-";
    if (count($centro['Sim']) > 0){
        $superv = "Sí";
        foreach ($centro['Sim'] as $sim) {
            if ($sim['uso'] == "TDT"){
                $cobertura = $sim['cobertura'];
            }
        }
    }
    $objPHPExcel->getActiveSheet()->setCellValue("L" . $fila, $superv);
    $objPHPExcel->getActiveSheet()->setCellValue("M" . $fila, $cobertura);
    $centroact++;
}
// Eliminamos la hoja vacía>
$objPHPExcel->setActiveSheetIndex(0);
// Formateamos las hojas
$nhojas = $objPHPExcel->getSheetCount();
for ($i = 0; $i < $nhojas; $i++){
    $objPHPExcel->setActiveSheetIndex($i);
    $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($estiloTh);
    $maxfila = $objPHPExcel->getActiveSheet()->getHighestRow();
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.'M'.$maxfila)->applyFromArray($estiloCelda);
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.'A'.$maxfila)->applyFromArray($estiloIzquierda);
    // Ajustamos el tamaño de celda
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

}
$objPHPExcel->setActiveSheetIndex(0);

// Generamos el fichero:
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Anexo_Centros.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
