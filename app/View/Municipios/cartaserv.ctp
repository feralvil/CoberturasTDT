<?php

// Importamos la clase para generar el PDF
App::import('Vendor', 'pdfAnexo');

// Path donde se dejan los ficheros PDF y el ZIP:
//$path = '/var/www/html/coberturatdt/app/tmp/cartapdf/';
$path = '/home/vagrant/webdev/coberturastdt/app/tmp/cartapdf/';
$archZip = new ZipArchive();
$fecha = date('Y-m-d');
$nomZip = $path . 'Anexo-TDT_' . $fecha . '.zip';
$archZip->open($nomZip, ZipArchive::CREATE);

// Creamos el contenido:
$nmunicipios = count($municipios);

if ($nmunicipios > 0){
    foreach ($municipios as $municipio){

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

        //$html = '<div style="background-color: #DDDDDD;">';
        $html = '<h2 style="text-align: center; border-top: #DDDDDD solid 1px; border-bottom: #DDDDDD solid 1px;">'.__('Servicio TDT de la Generalitat en '). $municipio['Municipio']['nombre'] . '</h2>';
        //$html .= '<h1 style="text-align: center; border-bottom: #DDDDDD solid 1px;">'.$municipio['Municipio']['nombre'].'</h1>';
        //$html .= '</div>';
        $pdf->writeHTML($html, true, false, true, false, '');
        //$pdf->Ln(3);

        // Reducimos el tamaño de la fuente por defecto
        $pdf->SetFont('helvetica', '', 9);

        $estilth = ' style="border-bottom: #DDDDDD solid 1px; border-top: #DDDDDD solid 1px; font-weight: bold; text-align: center;';
        foreach ($municipio['Centros'] as $centro){
            $html = '<h3>'. __('Centro Emisor') . ' &mdash; ' . $centro['Centro']['centro'].'</h3>';
            $html .= '<table>';
            $html .= '<tr>';
            $html .= '<th'.$estilth.'width: 7%;">'. __('Múltiple') .'</th>';
            $html .= '<th'.$estilth.'width: 5%;">'. __('Canal') .'</th>';
            $html .= '<th'.$estilth.'width: 10%;">'. __('Frecuencia') .'</th>';
            $html .= '<th'.$estilth.'width: 78%;" colspan="6">'. __('Programas').'</th>';
            $html .= '</tr>';
            $relleno = FALSE;
            foreach ($centro['Emision'] as $emision){
                $nprogramas = count($emision['programas']);
                $estiltd = ' text-align: center; border-bottom: #DDDDDD solid 1px;';
                if ($relleno){
                    $estiltr = ' style="background-color: #F9F9F9;"';
                }
                else{
                    $estiltr = '';
                }
                $frecuencia = ($emision['canal'] - 21) * 8 + 474 .' MHz';
                $html .= '<tr'.$estiltr.'>';
                $html .= '<td  style="width: 7%;'.$estiltd.'"><br /><br />'. $emision['nombre'] .'</td>';
                $html .= '<td  style="width: 5%;'.$estiltd.'"><br /><br />'. $emision['canal'] .'</td>';
                $html .= '<td style="width: 10%;'.$estiltd.'"><br /><br />'. $frecuencia.'</td> ';
                foreach ($emision['programas'] as $programa){
                        $html .= '<td style="width: 13%;'.$estiltd.'">';
                        $html .= '<img src="img/'.$programa['Programa']['logo'].'" alt="'. $programa['Programa']['nombre'].'"/>';
                        $html .= '<br/>'.$programa['Programa']['nombre'];
                        $html .= '</td>';
                }
                for ($i = 0; $i < (6 - $nprogramas); $i++){
                        $html .= '<td style="width: 13%;'.$estiltd.'">&nbsp;</td>';
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
        $pdf->Output($nomFich, 'F');
        $archZip->addFile($nomFich, $nombreFich);
    }
    $archZip->close();

    header('Content-Disposition: attachment; filename="Anexo-TDT_' . $fecha . '.zip"');
    readfile($nomZip);
}
else{
?>
    <div class="ink-alert block warning">
        <h4><?php echo __('No hay resultados');?></h4>
        <p><?php echo __('No se han encontrado canales emitidos desde este centro'); ?></p>
    </div>
<?php
}
