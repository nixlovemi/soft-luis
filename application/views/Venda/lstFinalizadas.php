<h1>Vendas Finalizadas</h1>

<?php
if(isset($errorMsg) && $errorMsg != ""){
  echo $errorMsg;
}
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-tasks"></i></span>
    <h5>Lista das vendas finalizadas</h5>
  </div>
  <div class="widget-content nopadding">
    <?php
    echo $htmlVendaFinalizadasTable;
    ?>
  </div>
</div>
