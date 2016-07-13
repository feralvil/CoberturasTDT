<?php

/**
 * Descriptción de pdfTerminal
 *
 * @author alfonso_fer
 */

include_once 'tcpdf/tcpdf.php';

class pdfAnexo extends TCPDF {
    public $titulo;

    // Cabecera
    public function Header() {
        // Logo CHAP
        $this->Image('../webroot/img/logochme.jpg', '', '', 60, 15);
        // Cabecera DGTI:
        // Reducimos el tamaño de la fuente por defecto
        $this->SetFont('Helvetica', '', 8);
        $txt = "\n DIRECCIÓN GENERAL DE TECNOLOGÍAS DE LA INFORMACIÓN Y LAS COMUNICACIONES\n";
        $txt .= "Ciudad Administrativa 9 d’Octubre \n";
        $txt .= "C/ Castán Tobeñas, 77 - 46018 VALENCIA \n";
        $this->MultiCell(0, 20, $txt, 0, 'R', 0, 1, 10, 12, 1, 0, false, false, 0, 'M', false);
        $this->Ln();
    }

    // Pie de Página

    public function Footer() {
        // Posición at 1.5 cm del fin de página
        $this->SetY(-10);
        // Establecemos la fuente y colores
        $this->SetDrawColor(0, 0, 0);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont('helvetica', 'B', 8);
        $fecha = date('d-m-Y');
        // Número de Página
        $this->Cell(0, 10, __('Nota: Programas emitidos a fecha') . ' ' . $fecha, 'T', 0, 'C');
    }
}

?>
