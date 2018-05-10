<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$statusVendaInc       = 1;
$editar               = (isset($editar)) ? $editar: false;
$strH1                = ($editar) ? "EDITAR VENDA": "VISUALIZAR VENDA";
$arrVenda             = (isset($arrVenda)) ? $arrVenda: array();
$arrClientes          = (isset($arrClientes)) ? $arrClientes: array();
$arrVendedores        = (isset($arrVendedores)) ? $arrVendedores: array();
$arrProdutos          = (isset($arrProdutos)) ? $arrProdutos: array();
$htmlVendaItens       = (isset($htmlVendaItens)) ? $htmlVendaItens: "";
$htmlVendaTotais      = (isset($htmlVendaTotais)) ? $htmlVendaTotais: "";
$htmlContasVenda      = (isset($htmlContasVenda)) ? $htmlContasVenda: "";
$htmlTotalContasVenda = (isset($htmlTotalContasVenda)) ? $htmlTotalContasVenda: "";

$vVdaId     = isset($arrVenda["vda_id"]) ? $arrVenda["vda_id"]: null;
$vVdaData   = isset($arrVenda["vda_data"]) && $arrVenda["vda_data"] != "" ? formata_data_hora($arrVenda["vda_data"]): null;
$vVdaCliId  = isset($arrVenda["vda_cli_id"]) ? $arrVenda["vda_cli_id"]: null;
$vVdaVenId  = (isset($arrVenda["vda_ven_id"])) ? $arrVenda["vda_ven_id"]: null;
$vVdaStatus = (isset($arrVenda["vda_status"])) ? $arrVenda["vda_status"]: null;
if($vVdaStatus != $statusVendaInc){
  $editar = false;
}

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "";
$strDisabled  = "";
$arrButtons   = [];
// $arrButtons[] = "<input type='submit' value='Incluir Produtos' class='btn btn-success' />";
?>

<h1><?php echo $strH1; ?></h1>

<?php
if($errorMsg != ""){
  echo showWarning($errorMsg);
} else if($okMsg != ""){
  echo showSuccess($okMsg);
}
?>

<div class="widget-box">
  <div class="widget-title">
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#tab1">Informações</a>
      </li>
      <li>
        <a data-toggle="tab" href="#tab2">Parcelas</a>
      </li>
    </ul>
  </div>
  <div class="widget-content tab-content">
    <div id="tab1" class="tab-pane active">
      <div class="row-fluid" style="margin-top: 0;">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title">
              <span class="icon"> <i class="icon icon-money"></i> </span>
              <h5>Venda ID <?php echo $vVdaId; ?></h5>
            </div>
            <div class="widget-content nopadding">
              <form id="frmEditVendaInfo" class="form-horizontal form-validation" method="post" action="">
                <div class="control-group">
                  <label class="control-label">ID</label>
                  <div class="controls">
                    <input readonly class="span10" type="text" name="vdaId" id="vdaId" value="<?php echo $vVdaId; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Data</label>
                  <div class="controls">
                    <input readonly class="span10" type="text" name="vdaData" id="vdaData" value="<?php echo $vVdaData; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Cliente</label>
                  <div class="controls">
                    <?php
                    echo "<select disabled class='span3 m-wrap' name='vdaCliente' id='vdaCliente'>";
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
                    echo "<select disabled class='span3 m-wrap' name='vdaVendedor' id='vdaVendedor'>";
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

      <div class="row-fluid">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title">
              <span class="icon"> <i class="icon icon-tasks"></i> </span>
              <h5>Produtos</h5>
            </div>
            <div class="widget-content">
              <?php
              if($editar){
                ?>
                <div class="widget-box collapsible">
                  <div class="widget-title">
                    <a data-toggle="collapse" href="#collapseOne">
                      <span class="icon"><i class="icon-plus"></i></span>
                      <h5>Código Barra</h5>
                    </a>
                  </div>
                  <div id="collapseOne" class="collapse in">
                    <div class="widget-content">
                      <table class="table table-bordered table-striped" width="100%">
                        <thead>
                          <tr>
                            <th width="82%">EAN</th>
                            <th>Quantidade</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <input class="span12" type="text" name="proEan" id="proEanAddProdVenda" value="" />
                            </td>
                            <td>
                              <input maxlength="3" class="span12 mask_inteiro" type="text" name="proEanQtde" id="proEanQtde" value="1" />
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div class="widget-title">
                    <a data-toggle="collapse" href="#collapseTwo">
                      <span class="icon"><i class="icon-plus"></i></span>
                      <h5>Manual</h5>
                    </a>
                  </div>
                  <div id="collapseTwo" class="collapse">
                    <div class="widget-content">
                      <form id="frmAddProdVenda" class="form-horizontal form-validation" method="post" action="">
                        <div class="control-group">
                          <table class="table table-bordered table-striped" width="100%">
                            <thead>
                              <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                                <th>Desconto</th>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <?php
                                  echo "<select name='vdiProId' id='vdiProId'>";
                                  echo "<option value=''></option>";
                                  foreach($arrProdutos as $Produto){
                                    $proId    = $Produto["pro_id"];
                                    $proDesc  = $Produto["pro_descricao"];

                                    echo "<option value='$proId'>[$proId] $proDesc</option>";
                                  }
                                  echo "</select>";
                                  ?>
                                </td>
                                <td>
                                  <input maxlength="3" class="span10 mask_inteiro" type="text" name="vdiQtde" id="vdiQtde" value="" />
                                </td>
                                <td>
                                  <div class="input-prepend">
                                    <span class="add-on">R$</span>
                                    <input class="span10 mask_moeda" type="text" name="vdiValor" id="vdiValor" value="" />
                                  </div>
                                </td>
                                <td>
                                  <div class="input-prepend">
                                    <span class="add-on">R$</span>
                                    <input class="span10 mask_moeda" type="text" name="vdiDesconto" id="vdiDesconto" value="" />
                                  </div>
                                </td>
                                <td>
                                  <input id="addProdVenda" type='button' value='Incluir Produto' class='btn btn-success' />
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <?php
              }
              ?>

              <div class="widget-box">
                <div class="widget-title">
                  <span class="icon"> <i class="icon icon-tasks"></i> </span>
                  <h5>Itens na Venda</h5>
                </div>
                <div id="htmlTbVendaItens" class="widget-content nopadding">
                  <?php
                  echo $htmlVendaItens;
                  ?>
                </div>
              </div>

              <div class="widget-box">
                <div class="widget-title">
                  <span class="icon"> <i class="icon icon-tasks"></i> </span>
                  <h5>Totais</h5>
                </div>
                <div id="htmlTbVendaTotais" class="widget-content">
                  <?php echo $htmlVendaTotais; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="tab2" class="tab-pane">
      <div class="row-fluid" style="margin-top: 0;">
        <?php
        if($editar){
          ?>
          <div class="widget-box">
            <div class="widget-title">
              <span class="icon"> <i class="icon icon-plus"></i> </span>
              <h5>Incluir</h5>
            </div>
            <div class="widget-content nopadding">
              <form id="frmAddProdVenda" class="form-horizontal form-validation" method="post" action="">
                <div class="control-group">
                  <table class="table table-bordered table-striped" width="100%">
                    <thead>
                      <tr>
                        <th>Vencimento</th>
                        <th>Valor</th>
                        <th>Incluir Paga?</th>
                        <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                          <input value="" class="span10 mask_datepicker" type="text" id="ctrDtvencimento" name="ctrDtvencimento" />
                        </td>
                        <td>
                          <div class="input-prepend">
                            <span class="add-on">R$</span>
                            <input class="span10 mask_moeda" type="text" name="ctrValor" id="ctrValor" value="" />
                          </div>
                        </td>
                        <td>
                          <select name="ctrContaPaga" id="ctrContaPaga">
                            <option value="N">Não</option>
                            <option value="S">Sim</option>
                          </select>
                        </td>
                        <td>
                          <input id="addParcelaVenda" type='button' value='Incluir Parcela' class='btn btn-success' />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </form>
            </div>
          </div>
          <?php
        }
        ?>

        <div class="widget-box">
          <div class="widget-title">
            <span class="icon"> <i class="icon icon-tasks"></i> </span>
            <h5>Parcelas da Venda</h5>
          </div>
          <div class="widget-content nopadding" id="htmlTbContasVenda">
            <?php echo $htmlContasVenda; ?>
            <br />
            <?php echo $htmlTotalContasVenda ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
if($editar){
  ?>
  <center>
    <input id="finalizaVenda" type='button' value='FINALIZAR VENDA' class='btn btn-success' />
  </center>
  <?php
}
?>
