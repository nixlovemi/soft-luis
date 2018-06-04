<?php
// variaveis ============
$arrRelVendas = isset($arrRelVendas) ? $arrRelVendas: array();
// ======================

include APPPATH . 'third_party/customFPDF/customFPDF.php';
$userData = $this->session->get_userdata();
$username = $userData["logged_in"]["usu_nome"];

$cFPDF = new customFPDF("RELATÓRIO DE VENDAS", "Relatorio/pdfRelVendas", "L", $username); //289
$cFPDF->Open();
$cFPDF->AliasNbPages();
$cFPDF->AddPage();

$cFPDF->SetFont('arial', 'B', 10);
$cFPDF->SetFillColor(230, 225, 225);

$cFPDF->Cell(19, 5, 'ID', 1, 0, 'C', true);
$cFPDF->Cell(35, 5, 'Data', 1, 0, 'C', true);
$cFPDF->Cell(86, 5, 'Cliente', 1, 0, 'C', true);
$cFPDF->Cell(85, 5, 'Vendedor', 1, 0, 'C', true);
$cFPDF->Cell(25, 5, 'Itens', 1, 0, 'C', true);
$cFPDF->Cell(39, 5, 'Valor', 1, 0, 'C', true);
$cFPDF->Ln(5);

$cFPDF->SetFont('arial', '', 9);
$cFPDF->SetFillColor(255, 255, 255);

$qtVendas  = 0;
$totVendas = 0;
$totItens  = 0;

foreach($arrRelVendas as $row){
  $vdaId       = $row["vda_id"];
  $vdaData     = ($row["vda_data"] != "") ? date("d/m/Y H:i", strtotime($row["vda_data"])): "";
  $vdaCliente  = $row["cli_nome"];
  $vdaVendedor = $row["ven_nome"];
  $vdaQtItens  = $row["vda_tot_itens"];
  $vdaTotal    = ($row["vda_vlr_itens"] >= 0) ? "R$ " . number_format($row["vda_vlr_itens"], 2, ",", "."): "";

  $qtVendas++;
  $totItens  += $row["vda_tot_itens"];
  $totVendas += $row["vda_vlr_itens"];

  $cFPDF->Cell(19, 5, $vdaId, 1, 0, 'C', true);
  $cFPDF->Cell(35, 5, $vdaData, 1, 0, 'C', true);
  $cFPDF->Cell(86, 5, $vdaCliente, 1, 0, 'C', true);
  $cFPDF->Cell(85, 5, $vdaVendedor, 1, 0, 'C', true);
  $cFPDF->Cell(25, 5, $vdaQtItens, 1, 0, 'C', true);
  $cFPDF->Cell(39, 5, $vdaTotal, 1, 0, 'C', true);
  $cFPDF->Ln(5);
}

// totais
$cFPDF->Ln(15);

$cFPDF->SetFont('arial', 'B', 10);
$cFPDF->SetFillColor(230, 225, 225);

$cFPDF->Cell(170, 5, "", 0, 0, 'C', false);
$cFPDF->Cell(119, 5, "TOTAIS:", 1, 0, 'C', true);
$cFPDF->Ln(5);

$cFPDF->SetFont('arial', '', 9);
$cFPDF->SetFillColor(255, 255, 255);

$cFPDF->Cell(170, 5, "", 0, 0, 'C', false);
$cFPDF->Cell(69, 5, "VENDAS:", 1, 0, 'C', true);
$cFPDF->Cell(50, 5, $qtVendas, 1, 0, 'C', true);
$cFPDF->Ln(5);

$cFPDF->Cell(170, 5, "", 0, 0, 'C', false);
$cFPDF->Cell(69, 5, "PRODUTOS VENDIDOS:", 1, 0, 'C', true);
$cFPDF->Cell(50, 5, $totItens, 1, 0, 'C', true);
$cFPDF->Ln(5);

$cFPDF->Cell(170, 5, "", 0, 0, 'C', false);
$cFPDF->Cell(69, 5, "TOTAL VENDA:", 1, 0, 'C', true);
$cFPDF->Cell(50, 5, "R$" . number_format($totVendas, 2, ",", "."), 1, 0, 'C', true);
$cFPDF->Ln(5);
// ======

$cFPDF->Output();
