<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$statusVendaInc        = 1;
$editar                = (isset($editar)) ? $editar: false;
$strH1                 = ($editar) ? "EDITAR MOSTRUARIO": "VISUALIZAR MOSTRUARIO";
$Mostruario            = (isset($Mostruario)) ? $Mostruario: array();
$arrProdutos           = (isset($arrProdutos)) ? $arrProdutos: array();
$arrVendedores         = (isset($arrVendedores)) ? $arrVendedores: array();
$htmlVendaMostruItens  = (isset($htmlVendaMostruItens)) ? $htmlVendaMostruItens: "";
$htmlVendaMostruTotais = (isset($htmlVendaMostruTotais)) ? $htmlVendaMostruTotais: "";

$vVdmId        = isset($Mostruario["vdm_id"]) ? $Mostruario["vdm_id"]: null;
$vVdmDtEntrega = isset($Mostruario["vdm_dtentrega"]) && $Mostruario["vdm_dtentrega"] != "" ? formata_data_hora($Mostruario["vdm_dtentrega"]): null;
$vVdmDtAcerto  = isset($Mostruario["vdm_dtacerto"]) && $Mostruario["vdm_dtacerto"] != "" ? formata_data_hora($Mostruario["vdm_dtacerto"]): null;
$vVdmVenId     = (isset($Mostruario["vdm_ven_id"])) ? $Mostruario["vdm_ven_id"]: null;

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "";
$strDisabled  = "";
$arrButtons   = [];
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
    </ul>
  </div>
  <div class="widget-content tab-content">
    <div id="tab1" class="tab-pane active">
      <div class="row-fluid" style="margin-top: 0;">
        <div class="span12">
          <div class="widget-box">
            <div class="widget-title">
              <span class="icon"> <i class="icon icon-money"></i> </span>
              <h5>Mostruario ID <?php echo $vVdmId; ?></h5>
            </div>
            <div class="widget-content nopadding">
              <form id="frmEditVendaMostruInfo" class="form-horizontal form-validation" method="post" action="">
                <div class="control-group">
                  <label class="control-label">ID</label>
                  <div class="controls">
                    <input readonly class="span10" type="text" name="vdmId" id="vdmId" value="<?php echo $vVdmId; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Data Entrega</label>
                  <div class="controls">
                    <input readonly class="span10" type="text" name="vdmDtEntrega" id="vdmDtEntrega" value="<?php echo $vVdmDtEntrega; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Data Acerto</label>
                  <div class="controls">
                    <input readonly class="span10" type="text" name="vdmDtAcerto" id="vdmDtAcerto" value="<?php echo $vVdmDtAcerto; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Vendedor</label>
                  <div class="controls">
                    <?php
                    echo "<select class='span6 m-wrap' name='vdmVendedor' id='vdmVendedor'>";
                    foreach($arrVendedores as $Vendedor){
                      $venId    = $Vendedor["ven_id"];
                      $venNome  = $Vendedor["ven_nome"];
                      $selected = ($vVdmVenId == $venId) ? " selected ": "";

                      echo "<option $selected value='$venId'>$venNome</option>";
                    }
                    echo "</select>";
                    ?>
                    <a href="javascript:;" id="btnTrocaVendMostruario" class="btn btn-mini btn-success"><i class="icon-save"></i></a>
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
                              <input class="span12" type="text" name="proEan" id="proEanAddProdVendaMostru" value="" />
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
                      <form id="frmAddProdVendaMostru" class="form-horizontal form-validation" method="post" action="">
                        <div class="control-group">
                          <table class="table table-bordered table-striped" width="100%">
                            <thead>
                              <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Valor</th>
                                <?php // <th>Desconto</th> ?>
                                <th>&nbsp;</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <?php
                                  echo "<select name='vmiProId' id='vmiProId'>";
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
                                  <input maxlength="3" class="span10 mask_inteiro" type="text" name="vmiQtde" id="vmiQtde" value="" />
                                </td>
                                <td>
                                  <div class="input-prepend">
                                    <span class="add-on">R$</span>
                                    <input class="span10 mask_moeda" type="text" name="vmiValor" id="vmiValor" value="" />
                                  </div>
                                </td>
                                <?php
                                /*
                                <td>
                                  <div class="input-prepend">
                                    <span class="add-on">R$</span>
                                    <input class="span10 mask_moeda" type="text" name="vmiDesconto" id="vmiDesconto" value="" />
                                  </div>
                                </td>
                                */
                                ?>
                                <td>
                                  <input id="addProdVendaMostru" type='button' value='Incluir Produto' class='btn btn-success' />
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
                  <h5>Itens no Mostruário</h5>
                </div>
                <div id="htmlTbVendaItens" class="widget-content nopadding">
                  <?php
                  echo $htmlVendaMostruItens;
                  ?>
                </div>
              </div>

              <div class="widget-box">
                <div class="widget-title">
                  <span class="icon"> <i class="icon icon-tasks"></i> </span>
                  <h5>Totais</h5>
                </div>
                <div id="htmlTbVendaTotais" class="widget-content">
                  <?php echo $htmlVendaMostruTotais; ?>
                </div>
              </div>
            </div>
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
    <input onClick="document.location.href='<?php echo base_url() ?>Venda/acertoMostruario/<?php echo $vVdmId; ?>'" id="finalizaVenda" type='button' value='FAZER ACERTO' class='btn btn-success' />
  </center>
  <?php
}
?>
