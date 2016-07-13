<?php

// Importamos la clase para generar el PDF
App::import('Vendor', 'pdfAnexo');

// Path donde se dejan los ficheros PDF y el ZIP:
//$path = '/home/vagrant/webdev/coberturastdt/app/tmp/';
//$path = '/var/www/html/coberturatdt/app/tmp/cartapdf/';
$path = '../../tmp/cartapdf/';

// Creamos el PDF
$pdf = new pdfAnexo ("P","mm","A4",true,"UTF-8",false);

// Propiedades del Documento
$pdf->SetAuthor('Servicio Telecomunicaciones');
$pdf->SetTitle(__('Anexo TDT'));
$pdf->SetSubject(__('Anexo TDT'));
$pdf->SetKeywords('TDT, Anexo');

// Márgenes
$pdf->SetMargins(10, 40, 10, 12);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetHeaderMargin(12);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Salto automático de página
$pdf->SetAutoPageBreak(TRUE, 15);

// Factor de Escala de las imágenes
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Establecemos la fuente por defecto
$pdf->SetFont('helvetica', '', 10.5);
// Título
$pdf->titulo = __('DGTI');

// Añadir una página
$pdf->AddPage();
// Establecemos la fuente por defecto
$pdf->SetFont('helvetica', '', 10.5);

$html = '<h2 style="text-align: center; border-top: #DDDDDD solid 1px; border-bottom: #DDDDDD solid 1px;">'.__('Servicio TDT de la Generalitat en '). $municipio['Municipio']['nombre'] . '</h2>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<h3>'.__('Datos del Municipio '). '</h3>';
$estilth = ' style="border-bottom: #DDDDDD solid 1px; border-top: #DDDDDD solid 1px; font-weight: bold; text-align: center;';
$estiltd = ' style= "text-align: center; border-bottom: #DDDDDD solid 1px;';
$html .= '<table>';
$html .= '<tr>';
$html .= '<th'.$estilth.'width: 10%;">'. __('Cód. INE') .'</th>';
$html .= '<th'.$estilth.'width: 30%;">'. __('Provincia') .'</th>';
$html .= '<th'.$estilth.'width: 40%;">'. __('Municipio') .'</th>';
$html .= '<th'.$estilth.'width: 20%;">'. __('Habitantes (2012)') .'</th>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td' . $estiltd . 'width: 10%;">'. $municipio['Municipio']['id'] .'</td>';
$html .= '<td' . $estiltd . 'width: 30%;">'. $municipio['Municipio']['provincia'] .'</td>';
$html .= '<td' . $estiltd . 'width: 40%;">'. $municipio['Municipio']['nombre'] .'</td>';
$html .= '<td' . $estiltd . 'width: 20%;">'. $this->Number->format($municipio['Municipio']['poblacion'], array('places' => 0, 'before' => '', 'thousands' => '.')) .'</td>';
$html .= '</tr>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');

$html = '<h3>'.__('Resumen de cobertura del municipio'). '</h3>';
if (!empty($municipio['Cobertura'])){
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<th'.$estilth.'width: 10%;">'. __('Nº') .'</th>';
    $html .= '<th'.$estilth.'width: 50%;">'. __('Centro') .'</th>';
    $html .= '<th'.$estilth.'width: 20%;">'. __('Nº Múltiples') .'</th>';
    $html .= '<th'.$estilth.'width: 20%;">'. __('Hab. Cubiertos') .'</th>';
    $html .= '</tr>';
    $i = 0;
    $totCubiertos = 0;
    foreach ($municipio['Centros'] as $centro) {
        $i++;
        $totCubiertos += $centro['habcub'];
        $html .= '<tr>';
        $html .= '<td' . $estiltd . 'width: 10%;">'. $i .'</td>';
        $html .= '<td' . $estiltd . 'width: 50%;">'. $centro['centro'] .'</td>';
        $html .= '<td' . $estiltd . 'width: 20%;">'. $centro['nmux'] .'</td>';
        $html .= '<td' . $estiltd . 'width: 20%;">'. $this->Number->format($centro['habcub'], array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$centro['cobertura'].' %)' .'</td>';
        $html .= '</tr>';
    }
    $html .= '<tr>';
    $html .= '<th colspan = "3">'. __('Totales') .'</th>';
    $html .= '<th'.$estilth.'width: 20%;">'.$this->Number->format($centro['habcub'], array('places' => 0, 'before' => '', 'thousands' => '.')).' ('.$centro['cobertura'].' %)</th>';
    $html .= '</tr>';
    $html .= '</table>';
}
$pdf->writeHTML($html, true, false, true, false, '');

// Reducimos el tamaño de la fuente por defecto
$pdf->SetFont('helvetica', '', 9);

foreach ($municipio['Centros'] as $centro){
    $html = '<h3>'. __('Centro Emisor') . ' &mdash; ' . $centro['centro'].'</h3>';
    $html .= '<table>';
    $html .= '<tr>';
    $html .= '<th'.$estilth.'width: 7%;">'. __('Múltiple') .'</th>';
    $html .= '<th'.$estilth.'width: 5%;">'. __('Canal') .'</th>';
    $html .= '<th'.$estilth.'width: 10%;">'. __('Frecuencia') .'</th>';
    $html .= '<th'.$estilth.'width: 78%;" colspan="6">'. __('Programas').'</th>';
    $html .= '</tr>';
    $relleno = FALSE;
    foreach ($centro['multiples'] as $multiple){
        $nprogramas = count($multiple['Programas']);
        if ($relleno){
            $estiltr = ' style="background-color: #F9F9F9;"';
        }
        else{
            $estiltr = '';
        }
        $frecuencia = ($multiple['Multiple']['canal'] - 21) * 8 + 474 .' MHz';
        $html .= '<tr'.$estiltr.'>';
        $html .= '<td' . $estiltd . 'width: 7%;"><br /><br />'. $multiple['Multiple']['nombre'] .'</td>';
        $html .= '<td' . $estiltd . 'width: 5%;"><br /><br />'. $multiple['Multiple']['canal'] .'</td>';
        $html .= '<td' . $estiltd . 'width: 10%;"><br /><br />'. $frecuencia.'</td> ';
        foreach ($multiple['Programas'] as $programa){
            $html .= '<td' . $estiltd . 'width: 13%;">';
            $html .= '<img src="img/'.$programa['Programa']['logo'].'" alt="'. $programa['Programa']['nombre'].'"/>';
            $html .= '<br/>'.$programa['Programa']['nombre'];
            $html .= '</td>';
        }
        for ($i = 0; $i < (6 - $nprogramas); $i++){
            $html .= '<td' . $estiltd . 'width: 13%">&nbsp;</td>';
        }
        $html .= '</tr>';
        $relleno = !($relleno);
    }
    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');
}
$pdf->lastPage();
// Generamos el PDF:
// Tratamos el nombre del Municipio:
// Quitamos la barra en nombres bilingües:
$muniarray = explode('/', $municipio['Municipio']['nombre']);
// Quitamos la coma en nombres compuestos:
$muniarray = explode(',', $muniarray[0]);
$nombreMuni = $muniarray[0];
// Reemplazamos espacios, eñes y acentos:
$buscar = array(" ", "á", "é", "í", "ó", "ú", "à", "Á", "É", "Í", "Ó", "Ú", "ñ");
$reemplazo = array("-", "a", "e", "i", "o", "u", "a", "A", "E", "I", "O", "U", "ny");
$nombreMuni = str_replace($buscar, $reemplazo, $nombreMuni);
$nombreFich = 'Anexo_' . $nombreMuni . '.pdf';
$nomFich = $path . $nombreFich;
$pdf->Output($nombreFich, 'I');
