<?php
$arrClientes = isset($arrClientes) ? $arrClientes: array();
?>

<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon icon-print"></i></span>
    <h5>RELATÓRIO DE VENDA</h5>
  </div>
  <div class="widget-content nopadding">
    <form id="frmInfoRelVendas" class="form-horizontal form-validation" method="post" action="">
      <div class="control-group">
        <label class="control-label">Período</label>
        <div class="controls">
          <input placeholder="Data Inicial" value="" class="mask_datepicker span3 m-wrap" type="text" id="vdaDataIni" name="vdaDataIni" />
          <input placeholder="Data Final" value="" class="mask_datepicker span3 m-wrap" type="text" id="vdaDataFim" name="vdaDataFim" />
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Cliente</label>
        <div class="controls">
          <?php
          echo "<select class='span3 m-wrap' name='vdaCliente' id='vdaCliente'>";
          echo "<option value=''></option>";
          foreach($arrClientes as $Cliente){
            $cliId    = $Cliente["cli_id"];
            $cliNome  = $Cliente["cli_nome"];

            echo "<option value='$cliId'>$cliNome</option>";
          }
          echo "</select>";
          ?>
        </div>
      </div>

      <div class="control-group">
        <label class="control-label">Vendedor</label>
        <div class="controls">
          <?php
          echo "<select class='span3 m-wrap' name='vdaVendedor' id='vdaVendedor'>";
          echo "<option value=''></option>";
          foreach($arrVendedores as $Vendedor){
            $venId    = $Vendedor["ven_id"];
            $venNome  = $Vendedor["ven_nome"];

            echo "<option value='$venId'>$venNome</option>";
          }
          echo "</select>";
          ?>
        </div>
      </div>

      <div class="form-actions">
        <button id="postRelVendas" type="button" class="btn btn-success">GERAR RELATÓRIO</button>
      </div>
    </form>
  </div>
</div>

<div id="dvPostRelVendas"></div>
