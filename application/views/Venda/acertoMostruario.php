<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$Mostruario           = (isset($Mostruario)) ? $Mostruario: array();
$arrProdutos          = (isset($arrProdutos)) ? $arrProdutos: array();
$htmlVendaMostruItens = (isset($htmlVendaMostruItens)) ? $htmlVendaMostruItens: "";
$htmlVendidos         = (isset($htmlVendidos)) ? $htmlVendidos: "";
$htmlConferidos       = (isset($htmlConferidos)) ? $htmlConferidos: "";

$vVdmId        = isset($Mostruario["vdm_id"]) ? $Mostruario["vdm_id"]: null;
$vVdmDtEntrega = isset($Mostruario["vdm_dtentrega"]) && $Mostruario["vdm_dtentrega"] != "" ? formata_data_hora($Mostruario["vdm_dtentrega"]): null;
$vVdmDtAcerto  = isset($Mostruario["vdm_dtacerto"]) && $Mostruario["vdm_dtacerto"] != "" ? formata_data_hora($Mostruario["vdm_dtacerto"]): null;
$vVendedor     = (isset($Mostruario["ven_nome"])) ? $Mostruario["ven_nome"]: null;

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "";
$strDisabled  = "";
$arrButtons   = [];
?>

<h1>ACERTO DO MOSTRUÁRIO <?php echo $vVdmId; ?></h1>

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
                    <input class="span10 mask_datepicker" type="text" name="vdmDtAcerto" id="vdmDtAcerto" value="<?php echo $vVdmDtAcerto; ?>" />
                  </div>
                </div>
                <div class="control-group">
                  <label class="control-label">Vendedor</label>
                  <div class="controls">
                    <input disabled class="span10" type="text" name="vdmVendedor" id="vdmVendedor" value="<?php echo $vVendedor; ?>" />
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title">
    <span class="icon"> <i class="icon icon-tasks"></i> </span>
    <h5>Acerto</h5>
  </div>
  <div class="widget-content">
    <div class="widget-box collapsible">
      <div class="widget-title">
        <a data-toggle="collapse" href="#collapseOne">
          <span class="icon"><i class="icon-plus"></i></span>
          <h5>Código Barra</h5>
        </a>
      </div>
      <div id="collapseOne" class="collapse in">
        <div class="widget-content">
          <table class="table table-bordered table-striped" width="">
            <thead>
              <tr>
                <th width="82%">EAN</th>
                <th>Quantidade</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <input class="span7" type="text" name="proEan" id="proEanAddProdVendaMostruRet" value="" />
                </td>
                <td>
                  <input maxlength="3" class="span1 mask_inteiro" type="text" name="proEanQtdeRet" id="proEanQtdeRet" value="1" />
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
          <form id="frmAddProdVendaMostruRet" class="form-horizontal form-validation" method="post" action="">
            <div class="control-group">
              <table class="table table-bordered table-striped" width="">
                <thead>
                  <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor</th>
                    <th>&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <?php
                      echo "<select name='vmirProId' id='vmirProId'>";
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
                      <input maxlength="3" class="span1 mask_inteiro" type="text" name="vmirQtde" id="vmirQtde" value="" />
                    </td>
                    <td>
                      <div class="input-prepend">
                        <span class="add-on">R$</span>
                        <input class="span2 mask_moeda" type="text" name="vmirValor" id="vmirValor" value="" />
                      </div>
                    </td>
                    <td>
                      <input id="addProdVendaMostruRet" type='button' value='Incluir Produto' class='btn btn-success' />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="widget-box">
  <div class="widget-title">
    <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#tab2">Itens Vendidos</a>
      </li>
      <li class="">
        <a data-toggle="tab" href="#tab3">Itens Conferência</a>
      </li>
    </ul>
  </div>
  <div class="widget-content tab-content">
    <div id="tab2" class="tab-pane active vmirHtmlVendidos">
      <?php echo $htmlVendidos; ?>
    </div>
    <div id="tab3" class="tab-pane vmirHtmlConferidos">
      <?php echo $htmlConferidos; ?>
    </div>
  </div>
</div>

<center>
  <input onClick="document.location.href='<?php echo base_url() ?>Venda/finalizaAcerto/<?php echo $vVdmId; ?>'" id="finalizaAcerto" type='button' value='FINALIZA ACERTO E LANÇA CONTAS' class='btn btn-success' />
</center>
