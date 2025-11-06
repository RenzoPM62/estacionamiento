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
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(79,165), true, 'UTF-8', false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Sistema de parqueo');
$pdf->setTitle('Sistema de parqueo');
$pdf->setSubject('Sistema de parqueo');
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
    <br>
    <b>BOLETA DE VENTA ELECTRONICA</b><br>
    Nro.000001
    ----------------------------------------------------------------------------------
    <div style="text-align: left">
        <b>DATOS DEL CLIENTE</b> <br>
        <b>SEÑOR(A): </b> '.$nombre_cliente.' <br>
        <b>DNI/RUC: </b> '.$nit_ci.' <br>
        <b>Fecha facturada: </b> '.$fecha_ingreso.' <br>
    ----------------------------------------------------------------------------------<br>
        <b>De: </b>3/10/2025 <b> Hora: </b>12:00<br>
        <b>Hasta: </b>3/10/2025 <b> Hora: </b>4:00<br>
        <b>Tiempo: </b>2 horas <br>
        <b>Cuviculo de parqueo: </b> '.$cuviculo.' <br>
    ----------------------------------------------------------------------------------<br>
        <table>
        <tr>
            <td style="text-align: center"><b>Detalle</b></td>
            <td style="text-align: center"><b>Precio</b></td>
            <td style="text-align: center"><b>Cantidad</b></td>
            <td style="text-align: center"><b>Total</b></td>
        </tr>
        <tr>
            <td style="text-align: center">Servicio de parqueo</td>
            <td style="text-align: center">S/.7.00</td>
            <td style="text-align: center">2</td>
            <td style="text-align: center">S/.14.00</td>
        </tr>
        </table>
        <p style="text-align: right">
        <b>Monto total: </b>S/.14.00 
        </p>
        <p>
        <b>Son: </b>CATORCE CON 00/100 SOLES 
        </p>

        <br><br>
        
        ----------------------------------------------------------------------------------<br>
        <b>USUARIO: </b> '.$user_sesion.' <br>

        <br><br><br><br><br><br><br><br><br><br>
        <p style="text-align: center">
        Representación impresa de la Boleta de venta Electrónica.<br>
        <b>Gracias por su preferencia. ¡Vuelva pronto!</b>
        </p>
    </div>
</p>
</div>
';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');


$style = array(
    'border' => 0,
    'vpadding' => '3',
    'hpadding' => '3',
    'fgcolor' => array(0, 0, 0),
    'bgcolor' => false, //array(255,255,255)
    'module_width' => 1, // width of a single module in points
    'module_height' => 1 // height of a single module in points
);

$QR = 'Factura realizada por el Sistema de Parqueo MERCADO HUASCAR, al cliente Freddy Hilari con nit: 837737277323 
con el vehiculo con numero de placa 3983FREDD y esta factura se genero en 21 de octubre de 2022 a hr: 18:00';
$pdf->write2DBarcode($QR,'QRCODE,L',  26,110,25,25, $style);


//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
