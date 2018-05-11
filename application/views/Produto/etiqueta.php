<?php
include APPPATH . 'third_party/fpdf16/fpdf.php';
include APPPATH . 'third_party/ultimate-barcode-generator/mainfiles/barcode.class.php';

// variaveis
$Produto   = isset($Produto) ? $Produto: array();
$proId     = isset($Produto["pro_id"]) ? $Produto["pro_id"]: "";
$proCodigo = isset($Produto["pro_codigo"]) ? $Produto["pro_codigo"]: "";
$proEan    = isset($Produto["ean"]) ? $Produto["ean"]: "0000000000000";
// =========

$pdf = new FPDF('L','mm',array(17, 34));
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
$vBarCode = $proEan;

$bar  = new BARCODE();
$bar->BarCode_save('EAN-13', $vBarCode, $nome, $path, 'jpeg', 50, 2, "#FFFFFF", "#000000", true);
$vNomeArq = $path.$nome.'.jpeg';
$pdf->Image($vNomeArq, 1, null, 35, 10, 'jpeg');
$pdf->Ln(1);
unlink($vNomeArq);

$pdf->SetFont('Arial', '', 7);
$pdf->Cell(34, 4, utf8_decode("ID $proId - Cód: $proCodigo"), 0, 0, 'C', true);
$pdf->Ln(4);

// $pdf->SetFont('Arial', '', 7);
// $pdf->MultiCell(34, 4, utf8_decode('Nome do Produto é Muito Grande mas não temos o que fazer'));

$pdf->Output();
