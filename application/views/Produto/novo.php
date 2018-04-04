<?php
$this->load->helper('utils');
$this->load->helper('alerts');

// variaveis =====
$arrProdutoDados = isset($arrProdutoDados) ? $arrProdutoDados: array();
$errorMsg        = isset($errorMsg) ? $errorMsg: "";
$okMsg           = isset($okMsg) ? $okMsg: "";
$detalhes        = isset($detalhes) ? $detalhes: false;
$editar          = isset($editar) ? $editar: false;

$vProId         = isset($arrProdutoDados["pro_id"]) ? $arrProdutoDados["pro_id"]: "";
$vProDescricao  = isset($arrProdutoDados["pro_descricao"]) ? $arrProdutoDados["pro_descricao"]: "";
$vProCodigo     = isset($arrProdutoDados["pro_codigo"]) ? $arrProdutoDados["pro_codigo"]: "";
$vProEan        = isset($arrProdutoDados["pro_ean"]) ? $arrProdutoDados["pro_ean"]: "";
$vProEstoque    = isset($arrProdutoDados["pro_estoque"]) ? $arrProdutoDados["pro_estoque"]: "";
$vProPrecCusto  = isset($arrProdutoDados["pro_prec_custo"]) && is_numeric($arrProdutoDados["pro_prec_custo"]) ? number_format($arrProdutoDados["pro_prec_custo"], 2, ",", ""): "";
$vProPrecVenda  = isset($arrProdutoDados["pro_prec_venda"]) && is_numeric($arrProdutoDados["pro_prec_venda"]) ? number_format($arrProdutoDados["pro_prec_venda"], 2, ",", ""): "";
$vProObservacao = isset($arrProdutoDados["pro_observacao"]) ? $arrProdutoDados["pro_observacao"]: "";
$vProAtivo      = isset($arrProdutoDados["pro_ativo"]) ? $arrProdutoDados["pro_ativo"]: "true";
// ===============

// info do form ==
if($vProAtivo === false || $vProAtivo == "false" || $vProAtivo == "f"){
  $selecAtivoS = "";
  $selecAtivoN = " selected ";
} else {
  $selecAtivoS = " selected ";
  $selecAtivoN = "";
}

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$arrButtons = [];
$h1Title    = "Novo Produto";

if($detalhes){
  $h1Title      = "Detalhes do Produto";
  $arrButtons[] = "<a href='".base_url() . "Produto/index" ."' class='btn'>Voltar</a>";
  $frmAction    = "";
} elseif($editar){
  $h1Title      = "Editar Produto";
  $arrButtons[] = "<input type='submit' value='Gravar Edição' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Produto/index" ."' class='btn'>Voltar</a>";
  $frmAction    = "Produto/salvaEditar";
} else {
  $h1Title      = "Novo Produto";
  $arrButtons[] = "<input type='submit' value='Gravar Produto' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Produto/index" ."' class='btn'>Cancelar</a>";
  $frmAction    = "Produto/salvaNovo";
}
// ===============
?>

<h1><?php echo $h1Title; ?></h1>

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
        <span class="icon"> <i class="icon icon-tasks"></i> </span>
        <h5>Produto</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . $frmAction; ?>">
          <?php
          if($detalhes || $editar){
            ?>
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="proId" id="proId" value="<?php echo $vProId; ?>" />
              </div>
            </div>
            <?php
          }
          ?>
          <div class="control-group">
            <label class="control-label">Descrição</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="proDescricao" id="proDescricao" value="<?php echo $vProDescricao; ?>" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Código</label>
            <div class="controls">
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="proCodigo" id="proCodigo" value="<?php echo $vProCodigo; ?>" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">EAN (Código de Barra)</label>
            <div class="controls">
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="proEan" id="proEan" value="<?php echo $vProEan; ?>" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Estoque (Und)</label>
            <div class="controls">
              <input <?php echo $strReadyonly; ?> class="validate-required span10" type="text" name="proEstoque" id="proEstoque" value="<?php echo $vProEstoque; ?>" />
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Preço Custo</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on">R$</span>
                <input <?php echo $strReadyonly; ?> class="validate-required span10 txt_moeda_jqformat" type="text" name="proPrecCusto" id="proPrecCusto" value="<?php echo $vProPrecCusto; ?>" />
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Preço Venda</label>
            <div class="controls">
              <div class="input-prepend">
                <span class="add-on">R$</span>
                <input <?php echo $strReadyonly; ?> class="validate-required span10 txt_moeda_jqformat" type="text" name="proPrecVenda" id="proPrecVenda" value="<?php echo $vProPrecVenda; ?>" />
              </div>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Observação</label>
            <div class="controls">
              <textarea <?php echo $strReadyonly; ?> class="span10" name="proObservacao" id="proObservacao"><?php echo $vProObservacao; ?></textarea>
            </div>
          </div>
          <div class="control-group">
            <label class="control-label">Ativo</label>
            <div class="controls">
              <select <?php echo $strDisabled; ?> class="" name="proAtivo" id="proAtivo">
                <option value="1" <?php echo $selecAtivoS; ?>>Sim</option>
                <option value="0" <?php echo $selecAtivoN; ?>>Não</option>
              </select>
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
