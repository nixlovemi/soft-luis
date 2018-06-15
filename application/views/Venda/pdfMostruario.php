<?php
// variaveis ============
$VendaMostruario = isset($VendaMostruario) ? $VendaMostruario: array();
$VdaMostItens    = isset($VdaMostItens) ? $VdaMostItens: array();

$vdmId     = isset($VendaMostruario["vdm_id"]) ? $VendaMostruario["vdm_id"]: "";
$vendedor  = isset($VendaMostruario["ven_nome"]) ? utf8_decode($VendaMostruario["ven_nome"]): "";
$dtEntrega = isset($VendaMostruario["vdm_dtentrega"]) ? date("d/m/Y", strtotime($VendaMostruario["vdm_dtentrega"])): "";
// ======================

include APPPATH . 'third_party/customFPDF/customFPDF.php';
$userData = $this->session->get_userdata();
$username = $userData["logged_in"]["usu_nome"];

$cFPDF = new customFPDF("MOSTRUÁRIO ID $vdmId", "Venda/pdfMostruario", "P", $username); //202
$cFPDF->Open();
$cFPDF->AliasNbPages();
$cFPDF->AddPage();

$cFPDF->SetFont('arial', 'B', 10);
$cFPDF->SetFillColor(230, 225, 225);
$cFPDF->Cell(112, 5, 'Vendedor', 1, 0, 'C', true);
$cFPDF->Cell(30, 5, 'Data Entrega', 1, 0, 'C', true);
$cFPDF->Cell(30, 5, 'Qtd. Itens', 1, 0, 'C', true);
$cFPDF->Cell(30, 5, 'Total Itens', 1, 0, 'C', true);
$cFPDF->Ln(5);

$totQtItens  = 0;
$totVlrItens = 0;
foreach($VdaMostItens as $item){
  $totQtItens  += $item["vmi_qtde"];
  $totVlrItens += ($item["vmi_qtde"] * $item["vmi_valor"]) - $item["vmi_desconto"];
}

$cFPDF->SetFont('arial', '', 9);
$cFPDF->SetFillColor(255, 255, 255);
$cFPDF->Cell(112, 5, $vendedor, 1, 0, 'C', true);
$cFPDF->Cell(30, 5, $dtEntrega, 1, 0, 'C', true);
$cFPDF->Cell(30, 5, $totQtItens, 1, 0, 'C', true);
$cFPDF->Cell(30, 5, 'R$' . number_format($totVlrItens, 2, ",", "."), 1, 0, 'C', true);
$cFPDF->Ln(15);

$cFPDF->SetFont('arial', 'B', 10);
$cFPDF->SetFillColor(230, 225, 225);
$cFPDF->Cell(202, 5, utf8_decode('ITENS DO MOSTRUÁRIO'), 1, 0, 'C', true);
$cFPDF->Ln(5);
$cFPDF->Cell(20, 5, 'ID', 1, 0, 'C', true);
$cFPDF->Cell(82, 5, 'Item', 1, 0, 'L', true);
$cFPDF->Cell(30, 5, 'Qtd', 1, 0, 'C', true);
$cFPDF->Cell(35, 5, 'Valor', 1, 0, 'C', true);
$cFPDF->Cell(35, 5, 'Total', 1, 0, 'C', true);
$cFPDF->Ln(5);

foreach($VdaMostItens as $item){
  $vId    = $item["vmi_pro_id"];
  $vItem  = utf8_decode($item["pro_descricao"]);
  $vQtd   = $item["vmi_qtde"];
  $vValor = $item["vmi_valor"];
  $vTotal = $item["vmi_qtde"] * $item["vmi_valor"];

  $cFPDF->SetFont('arial', '', 9);
  $cFPDF->SetFillColor(255, 255, 255);
  $cFPDF->Cell(20, 5, $vId, 1, 0, 'C', true);
  $cFPDF->Cell(82, 5, $vItem, 1, 0, 'L', true);
  $cFPDF->Cell(30, 5, $vQtd, 1, 0, 'C', true);
  $cFPDF->Cell(35, 5, 'R$' . number_format($vValor, 2, ",", "."), 1, 0, 'C', true);
  $cFPDF->Cell(35, 5, 'R$' . number_format($vTotal, 2, ",", "."), 1, 0, 'C', true);
  $cFPDF->Ln(5);
}

$cFPDF->Output();
