<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$arrVendaDados = (isset($arrVendaDados)) ? $arrVendaDados: array();
$arrClientes   = (isset($arrClientes)) ? $arrClientes: array();
$arrVendedores = (isset($arrVendedores)) ? $arrVendedores: array();

$vVdaCliId = (isset($arrVendaDados["vda_cli_id"])) ? $arrVendaDados["vda_cli_id"]: null;
$vVdaVenId = (isset($arrVendaDados["vda_ven_id"])) ? $arrVendaDados["vda_ven_id"]: null;

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "Venda/postIncluir";
$strDisabled  = "";
$arrButtons   = [];
$arrButtons[] = "<input type='submit' value='Incluir Produtos' class='btn btn-success' />";
?>

<h1>NOVA VENDA</h1>

<?php
if($errorMsg != ""){
  echo showWarning($errorMsg);
} else if($okMsg != ""){
  echo showSuccess($okMsg);
}
?>

<div class="row-fluid">
  <div class="span12">
    <div class="widget-box">
      <div class="widget-title">
        <span class="icon"> <i class="icon icon-money"></i> </span>
        <h5>Nova Venda</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . $frmAction; ?>">
          <div class="control-group">
            <label class="control-label">Cliente</label>
            <div class="controls">
              <?php
              echo "<select $strDisabled class='span3 m-wrap' name='vdaCliente' id='vdaCliente'>";
              echo "<option value=''></option>";
              foreach($arrClientes as $Cliente){
                $cliId    = $Cliente["cli_id"];
                $cliNome  = $Cliente["cli_nome"];
                $selected = ($vVdaCliId == $cliId) ? " selected ": "";

                echo "<option $selected value='$cliId'>$cliNome</option>";
              }
              echo "</select>";
              ?>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Vendedor</label>
            <div class="controls">
              <?php
              echo "<select $strDisabled class='span3 m-wrap' name='vdaVendedor' id='vdaVendedor'>";
              echo "<option value=''></option>";
              foreach($arrVendedores as $Vendedor){
                $venId    = $Vendedor["ven_id"];
                $venNome  = $Vendedor["ven_nome"];
                $selected = ($vVdaVenId == $venId) ? " selected ": "";

                echo "<option $selected value='$venId'>$venNome</option>";
              }
              echo "</select>";
              ?>
            </div>
          </div>

          <div class="form-actions">
            <?php
            foreach($arrButtons as $button){
              echo $button;
            }
            ?>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
