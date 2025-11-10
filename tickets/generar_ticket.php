<?php
// Include the main TCPDF library (search for installation path).
require_once('../app/templeates/TCPDF-main/tcpdf.php');
include ('../app/config.php');

//cargar la informacion del ticket
$query_tickets = $pdo->prepare("SELECT * FROM tb_tickets WHERE estado = '1' ");
$query_tickets->execute();
$tickets = $query_tickets->fetchAll(PDO::FETCH_ASSOC);
foreach ($tickets as $ticket) {
    $id_ticket = $ticket['id_ticket'];
    $nombre_cliente = $ticket['nombre_cliente'];
    $nit_ci = $ticket['nit_ci'];
    $cuviculo = $ticket['cuviculo'];
    $fecha_ingreso = $ticket['fecha_ingreso'];
    $hora_ingreso = $ticket['hora_ingreso'];
    $user_sesion = $ticket['user_sesion'];
}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79,100), true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicola Asuni');
$pdf->setTitle('TCPDF Example 002');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(5, 5, 5);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, 5);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->setFont('Helvetica','', 7);

// add a page
$pdf->AddPage();

//create some content
$html = '
<div>
<p style="text-align:center">
    <b>SISTEMA DE PARQUEO MERCADO HUASCAR</b> <br>
    COOPERATIVA DE SERVICIOS ESPECIALES MERCADO HUASCAR <br>
    RUC: 20433452659 <br>
    AV. LA UNIÓN, 1199, VILLA MARIA DEL TRIUNFO, LIMA <br>
    LIMA - PERU <br>
    ----------------------------------------------------------------------------------
    <div style="text-align: left">
        <b>DATOS DEL CLIENTE</b> <br>
        <b>SEÑOR(A): </b> '.$nombre_cliente.' <br>
        <b>DNI/RUC: </b> '.$nit_ci.' <br>
    ----------------------------------------------------------------------------------<br>
        <b>Cuviculo de parqueo: </b> '.$cuviculo.' <br>
        <b>Fecha de ingreso: </b> '.$fecha_ingreso.' <br>
        <b>Hora de ingreso: </b> '.$hora_ingreso.' <br>
        ----------------------------------------------------------------------------------<br>
        <b>USUARIO: </b> '.$user_sesion.' <br>
    </div>
</p>
</div>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
