<h1>Vendas Incluídas</h1>

<!--<a class="btn btn-info btn-large" href="<?php echo base_url() . "Produto/novoProduto"; ?>">NOVO PRODUTO</a>-->

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-tasks"></i></span>
    <h5>Lista das vendas incluídas</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlVendaIncluidasTable;
    ?>
  </div>
</div>
