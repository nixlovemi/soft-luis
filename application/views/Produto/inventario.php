<?php
$errorMsg    = isset($errorMsg) ? $errorMsg: "";
$arrProdutos = isset($arrProdutos) ? $arrProdutos: [];
?>

<h1>Inventário dos Produtos</h1>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<form method="post" action="<?php echo base_url(); ?>Produto/postInventario">
  <div class="widget-box">
    <div class="widget-title">
      <span class="icon"><i class="icon icon-tasks"></i></span>
      <h5>Produtos</h5>
    </div>
    <div class="widget-content nopadding">
      <?php
      if( count($arrProdutos) > 0 ){
        $htmlTable  = "";
        $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
        $htmlTable .= "  <thead>";
        $htmlTable .= "    <tr>";
        $htmlTable .= "      <th width='8%'>ID</th>";
        $htmlTable .= "      <th width='14%'>Código</th>";
        $htmlTable .= "      <th>Descrição</th>";
        $htmlTable .= "      <th width='14%'>Estoque Atual</th>";
        $htmlTable .= "      <th width='14%'>Inventário</th>";
        $htmlTable .= "    </tr>";
        $htmlTable .= "  </thead>";
        $htmlTable .= "  <tbody>";

        foreach($arrProdutos as $Produto){
          $proId      = $Produto["pro_id"];
          $proCod     = $Produto["pro_codigo"];
          $proDesc    = $Produto["pro_descricao"];
          $proEstoque = $Produto["pro_estoque"];

          $htmlTable .= "  <tr>";
          $htmlTable .= "    <td width='8%'>$proId</td>";
          $htmlTable .= "    <td width='14%'>$proCod</td>";
          $htmlTable .= "    <td>$proDesc</td>";
          $htmlTable .= "    <td width='14%'>$proEstoque</td>";
          $htmlTable .= "    <td width='14%'><input class='validate-required span2 mask_inteiro' type='text' name='proEstoque__$proId' id='proEstoque__$proId' value='' /></td>";
          $htmlTable .= "  </tr>";
        }

        $htmlTable .= "  </tbody>";
        $htmlTable .= "</table>";

        echo $htmlTable;
      }
      ?>
    </div>
  </div>

  <center>
    <input type='submit' value='Gravar Inventário' class='btn btn-info btn-large' />
  </center>
</form>
