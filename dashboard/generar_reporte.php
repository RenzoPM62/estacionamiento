<?php
// Incluir la configuración principal y el controlador del dashboard
require_once('../app/templeates/TCPDF-main/tcpdf.php');
include('controller_dashboard.php'); // Reutilizamos toda la lógica de consulta

// --- Creación del PDF ---

class MYPDF extends TCPDF {
    // Cabecera de página
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'../public/imagenes/mercado_huascar.png'; // Ruta a tu logo
        $this->Image($image_file, 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Título
        $this->Cell(0, 15, 'Reporte Diario del Estacionamiento', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 15, 'Fecha: ' . date('d/m/Y'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln(5);
        $this->Cell(0, 15, 'Generado: ' . date('d/m/Y H:i:s'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

    // Pie de página
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// Crear nuevo documento PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Información del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Sistema de Estacionamiento');
$pdf->SetTitle('Reporte Diario - ' . date('d/m/Y'));
$pdf->SetSubject('Reporte de KPIs del Estacionamiento');

// Setear cabecera y pie de página
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Setear márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Setear auto-salto de página
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Añadir una página
$pdf->AddPage();

// --- Contenido del PDF ---

$pdf->SetFont('helvetica', '', 11);
$pdf->Ln(20); // Espacio después de la cabecera

// Crear el contenido HTML para el PDF
$html = '
    <h3>Resumen de Indicadores Clave (KPIs) del Día</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr style="background-color:#f0f0f0; text-align:center;">
            <th width="50%"><b>Indicador</b></th>
            <th width="50%"><b>Valor</b></th>
        </tr>
        <tr>
            <td width="50%">Ingresos Totales del Día</td>
            <td width="50%">S/ ' . number_format($ingresos_hoy, 2) . '</td>
        </tr>
        <tr>
            <td width="50%">Total de Vehículos (Tickets) Registrados</td>
            <td width="50%">' . $tickets_hoy . ' vehículos</td>
        </tr>
        <tr>
            <td width="50%">Nuevos Clientes Registrados Hoy</td>
            <td width="50%">' . $clientes_hoy . ' clientes</td>
        </tr>
        <tr>
            <td width="50%">Ingreso Promedio por Vehículo</td>
            <td width="50%">S/ ' . number_format($ingreso_promedio_vehiculo, 2) . '</td>
        </tr>
        <tr>
            <td width="50%">Total de Espacios de Parqueo</td>
            <td width="50%">' . $total_espacios . ' espacios</td>
        </tr>
        <tr>
            <td width="50%">Espacios Ocupados Actualmente</td>
            <td width="50%">' . $total_ocupados . ' espacios</td>
        </tr>
         <tr>
            <td width="50%">Espacios Libres Actualmente</td>
            <td width="50%">' . $total_libres . ' espacios</td>
        </tr>
        <tr>
            <td width="50%"><b>Tasa de Ocupación Actual</b></td>
            <td width="50%"><b>' . number_format($tasa_ocupacion, 1) . ' %</b></td>
        </tr>
    </table>
    <br><br>
    <p>Este reporte refleja la actividad del día hasta la fecha y hora de su generación.</p>
';

// Escribir el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// --- Fin y Salida del PDF ---
$pdf_nombre = 'Reporte_Estacionamiento_' . date('Y-m-d') . '.pdf';
$pdf->Output($pdf_nombre, 'I'); // 'I' muestra el PDF en el navegador, 'D' lo descarga