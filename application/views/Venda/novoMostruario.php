<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$arrVendedores = (isset($arrVendedores)) ? $arrVendedores: array();
$vVdaVenId     = (isset($VendaMostruario["vdm_ven_id"])) ? $VendaMostruario["vdm_ven_id"]: null;
$vEhJson       = (isset($ehJson)) ? $ehJson: false;

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "Venda/postIncluirMostruario";
$strDisabled  = "";
$arrButtons   = [];
if(!$vEhJson){
  $arrButtons[] = "<input type='submit' value='Incluir Produtos' class='btn btn-success' />";
}
?>

<?php
if($errorMsg != ""){
  echo showWarning($errorMsg);
} else if($okMsg != ""){
  echo showSuccess($okMsg);
}
?>
<div class="container-fluid">
  <h1>NOVO MOSTRUÁRIO</h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Nova Mostruário</h5>
        </div>
        <div class="widget-content nopadding">
          <form class="form-horizontal form-validation" id="frmNovoMostruario" method="post" action="<?php echo base_url() . $frmAction; ?>">
            <div class="control-group">
              <label class="control-label">Vendedor</label>
              <div class="controls">
                <?php
                echo "<select $strDisabled class='span11 m-wrap' name='vdmVendedor' id='vdmVendedor'>";
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
            <div class="control-group">
              <label class="control-label">Data Entrega</label>
              <div class="controls">
                <input value="" class="span11 mask_datepicker" type="text" id="vdmDtEntrega" name="vdmDtEntrega" />
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
</div>
