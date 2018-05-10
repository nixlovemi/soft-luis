<?php
$this->load->helper('utils');

$arrButtons = [];
$h1Title    = "Editar Item da Venda";

$VendaItem = isset($VendaItem) ? $VendaItem : array();

$vVdiId       = isset($VendaItem["vdi_id"]) ? $VendaItem["vdi_id"]: "";
$vVdiProId    = isset($VendaItem["vdi_pro_id"]) ? $VendaItem["vdi_pro_id"]: "";
$vProduto     = isset($VendaItem["pro_descricao"]) ? $VendaItem["pro_descricao"]: "";
$vVdiQtde     = isset($VendaItem["vdi_qtde"]) ? $VendaItem["vdi_qtde"]: "";
$vVdiValor    = isset($VendaItem["vdi_valor"]) ? number_format($VendaItem["vdi_valor"], 2, ",", ""): "";
$vVdiDesconto = isset($VendaItem["vdi_desconto"]) ? number_format($VendaItem["vdi_desconto"], 2, ",", ""): "";

$strProDescricao = "$vVdiProId | $vProduto";
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
          <form id="frmJsonEditVendaItem" class="form-horizontal form-validation" method="post" action="">
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="editVdiId" id="editVdiId" value="<?php echo $vVdiId; ?>" />
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
                <input maxlength="3" class="span10 mask_inteiro" name="editVdiQtde" id="editVdiQtde" value="<?php echo $vVdiQtde; ?>" type="text">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Valor</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="editVdiValor" id="editVdiValor" value="<?php echo $vVdiValor; ?>" />
                </div>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label">Desconto</label>
              <div class="controls">
                <div class="input-prepend">
                  <span class="add-on">R$</span>
                  <input class="span10 mask_moeda" type="text" name="editVdiDesconto" id="editVdiDesconto" value="<?php echo $vVdiDesconto; ?>" />
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
