<?php
$rsVendas = isset($rsVendas) ? $rsVendas: array();

$htmlTable  = "";
$htmlTable .= "<table class='table table-bordered' id=''>";
$htmlTable .= "  <thead>";
$htmlTable .= "    <tr>";
$htmlTable .= "      <th width='6%'>ID</th>";
$htmlTable .= "      <th width='12%'>Data</th>";
$htmlTable .= "      <th>Cliente</th>";
$htmlTable .= "      <th>Vendedor</th>";
$htmlTable .= "      <th width='8%'>Itens</th>";
$htmlTable .= "      <th width='10%'>Valor</th>";
$htmlTable .= "    </tr>";
$htmlTable .= "  </thead>";
$htmlTable .= "  <tbody>";

foreach($rsVendas as $row){
  $vdaId       = $row["vda_id"];
  $vdaData     = ($row["vda_data"] != "") ? date("d/m/Y H:i", strtotime($row["vda_data"])): "";
  $vdaCliente  = $row["cli_nome"];
  $vdaVendedor = $row["ven_nome"];
  $vdaQtItens  = $row["vda_tot_itens"];
  $vdaTotal    = ($row["vda_vlr_itens"] >= 0) ? "R$ " . number_format($row["vda_vlr_itens"], 2, ",", "."): "";

  $htmlTable .= "<tr>";
  $htmlTable .= "  <td>$vdaId</td>";
  $htmlTable .= "  <td>$vdaData</td>";
  $htmlTable .= "  <td>$vdaCliente</td>";
  $htmlTable .= "  <td>$vdaVendedor</td>";
  $htmlTable .= "  <td>$vdaQtItens</td>";
  $htmlTable .= "  <td>$vdaTotal</td>";
  $htmlTable .= "</tr>";
}

$htmlTable .= "  </tbody>";
$htmlTable .= "</table>";
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>RESULTADO</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlTable;
    ?>
  </div>
</div>
