<?php
// Include the main TCPDF library (search for installation path).
require_once('../app/templeates/TCPDF-main/tcpdf.php');

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
        <b>SEÑOR(A): </b> ARTURO GELDRES VALDERRAMA <br>
        <b>NIT/CI.: </b> 12345678 <br>
    ----------------------------------------------------------------------------------<br>
        <b>Cuviculo de parqueo: </b> 10 <br>
        <b>Fecha de ingreso: </b> 02/10/2025 <br>
        <b>Hora de ingreso: </b> 20:00 <br>
        ----------------------------------------------------------------------------------<br>
        <b>USUARIO: </b> JESUS <br>
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
