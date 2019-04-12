<?php
require('fpdf/fpdf.php');

$pdf= new FPDF('L','mm','A5');
/** parametro enviados al objeto FPDF
ORIENTACION DE LA PAGINA
P : normal o vertical
L : Horizontal

TAMAÑO hojas
mm: milimetro
Legal/letter/A3/A4/A5

TAMAÑO PERSONALIZADO
$pdf= new FPDF('L','mm',array(100,150));

**/

//AGREGAR UNA PAGINA
$pdf->AddPage();
//DEFINIR FUENTE  [B]BOLD,[I]ITALIC (CURSIVA) 
$pdf->SetFont('Arial','B','16');
//CREAR CELDA
$pdf->Cell(40,10,'FACTURA DE VENTA');

//MOSTRAR EL PDF
$pdf->Output();


?>