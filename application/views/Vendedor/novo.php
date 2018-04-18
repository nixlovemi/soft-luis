<?php
$this->load->helper('utils');
$this->load->helper('alerts');

// variaveis =====
$arrVendedorDados   = isset($arrVendedorDados) ? $arrVendedorDados: array();
$errorMsg          = isset($errorMsg) ? $errorMsg: "";
$okMsg             = isset($okMsg) ? $okMsg: "";
$detalhes          = isset($detalhes) ? $detalhes: false;
$editar            = isset($editar) ? $editar: false;
$queries           = isset($queries) && $queries != "" ? "?" . $queries: ""; // esquema do dynatables
$arrEstados        = isset($arrEstados) ? $arrEstados: array();

$vVenId            = isset($arrVendedorDados["ven_id"]) ? $arrVendedorDados["ven_id"]: "";
$vVenNome          = isset($arrVendedorDados["ven_nome"]) ? $arrVendedorDados["ven_nome"]: "";
$vVenCpfCnpj       = isset($arrVendedorDados["ven_cpf_cnpj"]) ? $arrVendedorDados["ven_cpf_cnpj"]: "";
$vVenRgIe          = isset($arrVendedorDados["ven_rg_ie"]) ? $arrVendedorDados["ven_rg_ie"]: "";
$vVenTelDdd        = isset($arrVendedorDados["ven_tel_ddd"]) ? $arrVendedorDados["ven_tel_ddd"]: "";
$vVenTelNumero     = isset($arrVendedorDados["ven_tel_numero"]) ? $arrVendedorDados["ven_tel_numero"]: "";
$vVenCelDdd        = isset($arrVendedorDados["ven_cel_ddd"]) ? $arrVendedorDados["ven_cel_ddd"]: "";
$vVenCelNumero     = isset($arrVendedorDados["ven_cel_numero"]) ? $arrVendedorDados["ven_cel_numero"]: "";
$vVenEndCep        = isset($arrVendedorDados["ven_end_cep"]) ? $arrVendedorDados["ven_end_cep"]: "";
$vVenEndTpLgr      = isset($arrVendedorDados["ven_end_tp_lgr"]) ? $arrVendedorDados["ven_end_tp_lgr"]: "";
$vVenEndLogradouro = isset($arrVendedorDados["ven_end_logradouro"]) ? $arrVendedorDados["ven_end_logradouro"]: "";
$vVenEndNumero     = isset($arrVendedorDados["ven_end_numero"]) ? $arrVendedorDados["ven_end_numero"]: "";
$vVenEndBairro     = isset($arrVendedorDados["ven_end_bairro"]) ? $arrVendedorDados["ven_end_bairro"]: "";
$vVenEndCidade     = isset($arrVendedorDados["ven_end_cidade"]) ? $arrVendedorDados["ven_end_cidade"]: "";
$vVenEndEstado     = isset($arrVendedorDados["ven_end_estado"]) ? $arrVendedorDados["ven_end_estado"]: "";
$vVenObs           = isset($arrVendedorDados["ven_observacao"]) ? $arrVendedorDados["ven_observacao"]: "";
$vVenAtivo         = isset($arrVendedorDados["ven_ativo"]) ? $arrVendedorDados["ven_ativo"]: "";
// ===============

// info do form ==
if($vVenAtivo === false || $vVenAtivo == "false" || $vVenAtivo == "f"){
  $selecAtivoS = "";
  $selecAtivoN = " selected ";
} else {
  $selecAtivoS = " selected ";
  $selecAtivoN = "";
}

$strReadyonly = ($detalhes) ? " readonly ": "";
$strDisabled  = ($detalhes) ? " disabled ": "";

$arrButtons = [];
$h1Title    = "Novo Vendedor";

if($detalhes){
  $h1Title      = "Detalhes do Vendedor";
  $arrButtons[] = "<a href='".base_url() . "Vendedor/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "";
} elseif($editar){
  $h1Title      = "Editar Vendedor";
  $arrButtons[] = "<input type='submit' value='Gravar Edição' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Vendedor/index$queries" ."' class='btn'>Voltar</a>";
  $frmAction    = "Vendedor/salvaEditar";
} else {
  $h1Title      = "Novo Vendedor";
  $arrButtons[] = "<input type='submit' value='Gravar Vendedor' class='btn btn-success' />";
  $arrButtons[] = "<a href='".base_url() . "Vendedor/index$queries" ."' class='btn'>Cancelar</a>";
  $frmAction    = "Vendedor/salvaNovo";
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
        <h5>Vendedor</h5>
      </div>
      <div class="widget-content nopadding">
        <form class="form-horizontal form-validation" method="post" action="<?php echo base_url() . $frmAction; ?>">
          <?php
          if($detalhes || $editar){
            ?>
            <div class="control-group">
              <label class="control-label">ID</label>
              <div class="controls">
                <input readonly class="span10" type="text" name="venId" id="venId" value="<?php echo $vVenId; ?>" />
              </div>
            </div>
            <?php
          }
          ?>

          <div class="control-group">
            <label class="control-label">Nome</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="venNome" id="venNome" value="<?php echo $vVenNome; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">CPF</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10 mask_cpf" type="text" name="venCpfCnpj" id="venCpfCnpj" value="<?php echo $vVenCpfCnpj; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">RG</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="venRgIe" id="venRgIe" value="<?php echo $vVenRgIe; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Telefone</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="venTelDdd" id="venTelDdd" value="<?php echo $vVenTelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Telefone" class="span8 m-wrap" type="text" name="venTelNumero" id="venTelNumero" value="<?php echo $vVenTelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Celular</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> placeholder="DDD" class="span2 m-wrap" type="text" name="venCelDdd" id="venCelDdd" value="<?php echo $vVenCelDdd; ?>" />
              <input <?php echo $strReadyonly; ?> placeholder="Celular" class="span8 m-wrap" type="text" name="venCelNumero" id="venCelNumero" value="<?php echo $vVenCelNumero; ?>" />
            </div>
          </div>

          <div class="control-group">
            <div style="height:20px;"></div>
          </div>

          <div class="control-group">
            <label class="control-label">CEP</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10 mask_cep" type="text" name="venEndCep" id="venEndCep" value="<?php echo $vVenEndCep; ?>" />
            </div>
          </div>

          <?php
          $arrTpLogr = array("", "Alameda", "Avenida", "Condomínio", "Distrito", "Estrada", "Praça", "Residencial", "Rodovia", "Rua");
          ?>
          <div class="control-group">
            <label class="control-label">Endereço</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <select <?php echo $strDisabled; ?> class="span2 m-wrap" name="venEndTpLgr" id="venEndTpLgr">
                <?php
                foreach($arrTpLogr as $tpLogr){
                  $selected = ($tpLogr == $vVenEndTpLgr ) ? " selected ": "";
                  echo "<option $selected value='$tpLogr'>$tpLogr</option>";
                }
                ?>
              </select>
              <input <?php echo $strReadyonly; ?> class="span6 m-wrap" type="text" name="venEndLogradouro" id="venEndLogradouro" value="<?php echo $vVenEndLogradouro; ?>" />
              <input <?php echo $strReadyonly; ?> class="span2 m-wrap" type="text" name="venEndNumero" id="venEndNumero" value="<?php echo $vVenEndNumero; ?>" placeholder="Número" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Bairro</label>
            <div class="controls">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span10" type="text" name="venEndBairro" id="venEndBairro" value="<?php echo $vVenEndBairro; ?>" />
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Cidade</label>
            <div class="controls controls-row">
              <!-- validate-required -->
              <input <?php echo $strReadyonly; ?> class="span7 m-wrap" type="text" name="venEndCidade" id="venEndCidade" value="<?php echo $vVenEndCidade; ?>" />

              <?php
              echo "<select $strDisabled class='span3 m-wrap' name='venEndEstado' id='venEndEstado'>";
              echo "<option value=''></option>";
              foreach($arrEstados as $Estado){
                $estSigla = $Estado["est_sigla"];
                $estDesc  = $Estado["est_descricao"];

                $selected = ($estSigla == $vVenEndEstado ) ? " selected ": "";
                echo "<option $selected value='$estSigla'>$estDesc</option>";
              }
              echo "</select>";
              ?>
            </div>
          </div>

          <div class="control-group">
            <div style="height:20px;"></div>
          </div>

          <div class="control-group">
            <label class="control-label">Observação</label>
            <div class="controls">
              <!-- validate-required -->
              <textarea <?php echo $strReadyonly; ?> class="span10" name="venObservacao" id="venObservacao"><?php echo $vVenObs; ?></textarea>
            </div>
          </div>

          <div class="control-group">
            <label class="control-label">Ativo</label>
            <div class="controls">
              <select <?php echo $strDisabled; ?> class="" name="venAtivo" id="venAtivo">
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
