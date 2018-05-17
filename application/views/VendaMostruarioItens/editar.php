<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Editar Item do Mostruário";

$VendaMostruarioItem = isset($VendaMostruarioItem) ? $VendaMostruarioItem : array();

$vVmiId       = isset($VendaMostruarioItem["vmi_id"]) ? $VendaMostruarioItem["vmi_id"]: "";
$vVmiProId    = isset($VendaMostruarioItem["vmi_pro_id"]) ? $VendaMostruarioItem["vmi_pro_id"]: "";
$vProduto     = isset($VendaMostruarioItem["pro_descricao"]) ? $VendaMostruarioItem["pro_descricao"]: "";
$vVmiQtde     = isset($VendaMostruarioItem["vmi_qtde"]) ? $VendaMostruarioItem["vmi_qtde"]: "";
$vVmiValor    = isset($VendaMostruarioItem["vmi_valor"]) ? number_format($VendaMostruarioItem["vmi_valor"], 2, ",", ""): "";
$vVmiDesconto = isset($VendaMostruarioItem["vmi_desconto"]) ? number_format($VendaMostruarioItem["vmi_desconto"], 2, ",", ""): "";

$strProDescricao = "$vVmiProId | $vProduto";
?>

<div class="container-fluid">
  <h1><?php echo $h1Title; ?></h1>

  <div class="row-fluid">
    <div class="span12">
      <div class="widget-box">
        <div class="widget-title">
          <span class="icon"> <i class="icon icon-money"></i> </span>
          <h5>Item</h5>
        </div>
        <div class="widget-content nopadding">
          <form id="frmJsonEditVendaMostruItem" class="form-horizontal form-validation" method="post" action="">
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="editVmiId" id="editVmiId" value="<?php echo $vVmiId; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Produto</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="editProDescricao" id="editProDescricao" value="<?php echo $strProDescricao; ?>" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Quantidade</label>
              <div class="controls">
                <input maxlength="3" class="span10 mask_inteiro" name="editVmiQtde" id="editVmiQtde" value="<?php echo $vVmiQtde; ?>" type="text">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="editVmiValor" id="editVmiValor" value="<?php echo $vVmiValor; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Desconto</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="editVmiDesconto" id="editVmiDesconto" value="<?php echo $vVmiDesconto; ?>" />
                </div>
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
