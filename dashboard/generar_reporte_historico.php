<?php
// 1. INCLUIR ARCHIVOS
require_once('../app/config.php'); // Usamos require_once para asegurar una sola carga
require_once('../app/templeates/TCPDF-main/tcpdf.php');
include_once('controller_dashboard.php'); // Reutilizamos el array $meses_formulario

// 2. OBTENER VARIABLES DEL FORMULARIO
$tipo_reporte = $_GET['tipo_reporte'] ?? 'mensual';
$mes_num      = $_GET['mes'] ?? date('m');
$anio         = $_GET['anio'] ?? date('Y');
$mes_nombre   = $meses_formulario[intval($mes_num)];


// 3. CLASE PDF PERSONALIZADA (Para cabecera y pie)
class MYPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'../public/imagenes/mercado_huascar.png';
        $this->Image($image_file, 10, 10, 20, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 15);
        $this->Cell(0, 15, 'Reporte de Estacionamiento', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetFont('helvetica', '', 10);
        $this->Cell(0, 15, 'Generado: ' . date('d/m/Y H:i:s'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Página '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// 4. FUNCIÓN PARA DIBUJAR GRÁFICOS (¡La parte visual!)
function dibujarGraficoSimple($pdf, $data, $labels, $titulo, $x, $y, $ancho, $alto) {
    if (empty($data)) {
        $pdf->SetXY($x, $y);
        $pdf->Cell($ancho, $alto, 'No hay datos para mostrar en el gráfico.', 1, 0, 'C');
        return;
    }

    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY($x, $y);
    $pdf->Cell($ancho, 8, $titulo, 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 8);

    $y_grafico = $y + 10;
    $x_grafico = $x + 10; // Margen para etiquetas Y
    $ancho_grafico = $ancho - 15;
    $alto_grafico = $alto - 15;
    
    // Dibujar borde
    $pdf->Rect($x_grafico, $y_grafico, $ancho_grafico, $alto_grafico);

    $max_valor = max($data) > 0 ? max($data) : 1;
    $num_barras = count($data);
    $ancho_barra = ($ancho_grafico / $num_barras) * 0.8;
    $espacio_barra = ($ancho_grafico / $num_barras) * 0.2;

    $pdf->SetFillColor(0, 100, 200); // Color azul
    $pdf->SetDrawColor(0, 0, 0);

    for ($i = 0; $i < $num_barras; $i++) {
        $altura_barra = ($data[$i] / $max_valor) * $alto_grafico;
        $x_barra = $x_grafico + ($i * ($ancho_barra + $espacio_barra)) + ($espacio_barra / 2);
        $y_barra = $y_grafico + $alto_grafico - $altura_barra;

        // Dibujar barra
        $pdf->Rect($x_barra, $y_barra, $ancho_barra, $altura_barra, 'DF');

        // Etiqueta (valor) encima de la barra
        $pdf->SetXY($x_barra, $y_barra - 5);
        $pdf->Cell($ancho_barra, 5, $data[$i], 0, 0, 'C');

        // Etiqueta (label) debajo de la barra
        $pdf->SetXY($x_barra, $y_grafico + $alto_grafico);
        $pdf->Cell($ancho_barra, 5, $labels[$i], 0, 0, 'C');
    }
    
    // Etiqueta del eje Y (Max Valor)
    $pdf->SetXY($x, $y_grafico);
    $pdf->Cell(10, 5, round($max_valor, 0), 0, 0, 'R');
    // Etiqueta del eje Y (Cero)
    $pdf->SetXY($x, $y_grafico + $alto_grafico - 5);
    $pdf->Cell(10, 5, '0', 0, 0, 'R');
}


// 5. INICIALIZAR PDF
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->AddPage();
$pdf->Ln(15); // Espacio después de la cabecera


// 6. LÓGICA DE CONTENIDO Y CONSULTAS
$html = '';
$datos_grafico = [];
$labels_grafico = [];
$titulo_grafico = '';
$titulo_principal = '';

if ($tipo_reporte == 'mensual') {
    // --- LÓGICA MENSUAL ---
    $titulo_principal = "Reporte Mensual: $mes_nombre de $anio";
    
    // Consulta para los totales
    $query_total = $pdo->prepare("
        SELECT SUM(ingresos_dia) as total_ingresos, SUM(tickets_dia) as total_tickets, SUM(clientes_nuevos_dia) as total_clientes 
        FROM tb_reportes_diarios 
        WHERE MONTH(fecha_reporte) = :mes AND YEAR(fecha_reporte) = :anio");
    $query_total->execute(['mes' => $mes_num, 'anio' => $anio]);
    $totales = $query_total->fetch(PDO::FETCH_ASSOC);

    // Consulta para el gráfico (Ingresos por día)
    $query_grafico = $pdo->prepare("
        SELECT DAY(fecha_reporte) as dia, SUM(ingresos_dia) as ingresos 
        FROM tb_reportes_diarios 
        WHERE MONTH(fecha_reporte) = :mes AND YEAR(fecha_reporte) = :anio 
        GROUP BY DAY(fecha_reporte) ORDER BY dia ASC");
    $query_grafico->execute(['mes' => $mes_num, 'anio' => $anio]);
    $datos_por_dia = $query_grafico->fetchAll(PDO::FETCH_ASSOC);

    foreach ($datos_por_dia as $dato) {
        $datos_grafico[] = $dato['ingresos'];
        $labels_grafico[] = $dato['dia']; // Label es el día (1, 2, 3...)
    }
    $titulo_grafico = "Ingresos por dia (S/) - $mes_nombre $anio";
    $pdf_nombre = "Reporte_Mensual_${mes_num}_${anio}.pdf";

    // HTML para la tabla de totales
    $html = '
        <h3>' . $titulo_principal . '</h3>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr style="background-color:#f0f0f0; text-align:center;">
                <th width="50%"><b>Indicador</b></th>
                <th width="50%"><b>Valor Total del Mes</b></th>
            </tr>
            <tr>
                <td width="50%">Ingresos Totales (S/)</td>
                <td width="50%">S/ ' . number_format($totales['total_ingresos'] ?? 0, 2) . '</td>
            </tr>
            <tr>
                <td width="50%">Total de Vehículos (Tickets)</td>
                <td width="50%">' . ($totales['total_tickets'] ?? 0) . ' vehículos</td>
            </tr>
            <tr>
                <td width="50%">Nuevos Clientes Registrados</td>
                <td width="50%">' . ($totales['total_clientes'] ?? 0) . ' clientes</td>
            </tr>
        </table>
    ';

} else if ($tipo_reporte == 'anual') {
    // --- LÓGICA ANUAL ---
    $titulo_principal = "Reporte Anual: $anio";

    // Consulta para los totales
    $query_total = $pdo->prepare("
        SELECT SUM(ingresos_dia) as total_ingresos, SUM(tickets_dia) as total_tickets, SUM(clientes_nuevos_dia) as total_clientes 
        FROM tb_reportes_diarios 
        WHERE YEAR(fecha_reporte) = :anio");
    $query_total->execute(['anio' => $anio]);
    $totales = $query_total->fetch(PDO::FETCH_ASSOC);

    // Consulta para el gráfico (Ingresos por mes)
    $query_grafico = $pdo->prepare("
        SELECT MONTH(fecha_reporte) as mes_num, SUM(ingresos_dia) as ingresos 
        FROM tb_reportes_diarios 
        WHERE YEAR(fecha_reporte) = :anio 
        GROUP BY MONTH(fecha_reporte) ORDER BY mes_num ASC");
    $query_grafico->execute(['anio' => $anio]);
    $datos_por_mes = $query_grafico->fetchAll(PDO::FETCH_ASSOC);

    // Mapear números de mes a nombres cortos
    foreach ($datos_por_mes as $dato) {
        $datos_grafico[] = $dato['ingresos'];
        $labels_grafico[] = substr($meses_formulario[intval($dato['mes_num'])], 0, 3); // "Ene", "Feb", "Mar"
    }
    $titulo_grafico = "Ingresos por Mes (S/) - $anio";
    $pdf_nombre = "Reporte_Anual_${anio}.pdf";

    // HTML para la tabla de totales
    $html = '
        <h3>' . $titulo_principal . '</h3>
        <table border="1" cellpadding="5" cellspacing="0">
            <tr style="background-color:#f0f0f0; text-align:center;">
                <th width="50%"><b>Indicador</b></th>
                <th width="50%"><b>Valor Total del Año</b></th>
            </tr>
            <tr>
                <td width="50%">Ingresos Totales (S/)</td>
                <td width="50%">S/ ' . number_format($totales['total_ingresos'] ?? 0, 2) . '</td>
            </tr>
            <tr>
                <td width="50%">Total de Vehículos (Tickets)</td>
                <td width="50%">' . ($totales['total_tickets'] ?? 0) . ' vehículos</td>
            </tr>
            <tr>
                <td width="50%">Nuevos Clientes Registrados</td>
                <td width="50%">' . ($totales['total_clientes'] ?? 0) . ' clientes</td>
            </tr>
        </table>
    ';
}


// 7. RENDERIZAR EL PDF
// Escribir la tabla HTML
$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Ln(10); // Espacio

// Dibujar el gráfico
// Parámetros: ($pdf, $data, $labels, $titulo, $x, $y, $ancho, $alto)
dibujarGraficoSimple($pdf, $datos_grafico, $labels_grafico, $titulo_grafico, 15, $pdf->GetY(), 180, 80);


// 8. SALIDA
$pdf->Output($pdf_nombre, 'I'); // 'I' muestra el PDF en el navegador