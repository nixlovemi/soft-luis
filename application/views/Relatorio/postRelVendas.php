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

$qtVendas  = 0;
$totVendas = 0;
$totItens  = 0;

foreach($rsVendas as $row){
  $vdaId       = $row["vda_id"];
  $vdaData     = ($row["vda_data"] != "") ? date("d/m/Y H:i", strtotime($row["vda_data"])): "";
  $vdaCliente  = $row["cli_nome"];
  $vdaVendedor = $row["ven_nome"];
  $vdaQtItens  = $row["vda_tot_itens"];
  $vdaTotal    = ($row["vda_vlr_itens"] >= 0) ? "R$ " . number_format($row["vda_vlr_itens"], 2, ",", "."): "";

  $qtVendas++;
  $totItens  += $row["vda_tot_itens"];
  $totVendas += $row["vda_vlr_itens"];

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

$this->load->helper('utils');
$htmlVendas      = getHtmlBlocoTotais("VENDAS", $qtVendas);
$htmlTotProdutos = getHtmlBlocoTotais("PRODUTOS VENDIDOS", $totItens);
$htmlTotVenda    = getHtmlBlocoTotais("TOTAL VENDA", "R$" . number_format($totVendas, 2, ",", "."));

$htmlTable .= "<div class='control-group' style='width: 100%; display: block; overflow: hidden; margin-top:20px; margin-bottom:20px;'>
                <div class='span3 m-wrap'>
                  $htmlVendas
                </div>
                <div class='span3 m-wrap'>
                  $htmlTotProdutos
                </div>
                <div class='span3 m-wrap'>
                  $htmlTotVenda
                </div>
              </div>";
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>RESULTADO</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlTable;
    ?>
    <center>
      <?php
      $jsonStrArrRel = json_encode($rsVendas);
      ?>

      <form target="_blank" method="post" action="<?php echo base_url(); ?>Relatorio/pdfRelVendas">
        <input type="hidden" name="jsonRelVendas" value="<?php echo base64url_encode($jsonStrArrRel); ?>" />
        <button onClick="this.form.submit();" style="margin-bottom:20px;" type="button" class="btn btn-danger">GERAR PDF</button>
      </form>
    </center>
  </div>
</div>
