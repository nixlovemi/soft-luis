<?php
$this->load->helper('utils');
$this->load->helper('alerts');

$arrVenda       = (isset($arrVenda)) ? $arrVenda: array();
$arrClientes    = (isset($arrClientes)) ? $arrClientes: array();
$arrVendedores  = (isset($arrVendedores)) ? $arrVendedores: array();
$arrProdutos    = (isset($arrProdutos)) ? $arrProdutos: array();
$htmlVendaItens = (isset($htmlVendaItens)) ? $htmlVendaItens: "";

$vVdaId    = isset($arrVenda["vda_id"]) ? $arrVenda["vda_id"]: null;
$vVdaData  = isset($arrVenda["vda_data"]) && $arrVenda["vda_data"] != "" ? formata_data_hora($arrVenda["vda_data"]): null;
$vVdaCliId = isset($arrVenda["vda_cli_id"]) ? $arrVenda["vda_cli_id"]: null;
$vVdaVenId = (isset($arrVenda["vda_ven_id"])) ? $arrVenda["vda_ven_id"]: null;

$errorMsg     = isset($errorMsg) ? $errorMsg: "";
$okMsg        = isset($okMsg) ? $okMsg: "";
$frmAction    = "";
$strDisabled  = "";
$arrButtons   = [];
// $arrButtons[] = "<input type='submit' value='Incluir Produtos' class='btn btn-success' />";
?>

<h1>EDITAR VENDA</h1>

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
      </div>
    </div>
  </div>
</div>
