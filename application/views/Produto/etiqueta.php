<?php
include APPPATH . 'third_party/fpdf16/fpdf.php';
include APPPATH . 'third_party/ultimate-barcode-generator/mainfiles/barcode.class.php';

// variaveis
$Produto = isset($Produto) ? $Produto: array();
$proEan8 = isset($Produto["ean8"]) ? $Produto["ean8"]: "00000000";
// =========

$pdf = new FPDF('L','mm',array(29, 34));
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetMargins(0,0);
$pdf->SetX(0);
$pdf->SetY(0);
$pdf->SetAutoPageBreak(true, 2);
$pdf->SetFillColor(255, 255, 255);
$pdf->SetFont('Arial', '', 8);

$nome     = "etiqueta-produto";
$path     = APPPATH . 'cache/';
$vBarCode = $proEan8;

$bar  = new BARCODE();
$bar->BarCode_save('EAN-8', $vBarCode, $nome, $path, 'jpeg');
$vNomeArq = $path.$nome.'.jpeg';
$pdf->Image($vNomeArq, 2, null, 30, 10, 'jpeg');
$pdf->Ln(1);

$pdf->Cell(34, 4, utf8_decode('ID 1 - Cód: C1254'), 0, 0, 'C', true);
$pdf->Ln(4);

$pdf->SetFont('Arial', '', 7);
$pdf->MultiCell(34, 4, utf8_decode('Nome do Produto é Muito Grande mas não temos o que fazer'));

$pdf->Output();
