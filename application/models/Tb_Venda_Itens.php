<?php
class Tb_Venda_Itens extends CI_Model {
  public function getHtmlList($vdaId){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Produto</th>";
    $htmlTable .= "      <th width='8%'>Quantidade</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Desconto</th>";
    $htmlTable .= "      <th width='10%'>Total</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT pro_id, pro_descricao, vdi_qtde, vdi_valor, vdi_desconto, vdi_total ";
    $vSql .= " FROM tb_venda_itens ";
    $vSql .= " LEFT JOIN tb_produto ON pro_id = vdi_pro_id ";
    $vSql .= " WHERE vdi_vda_id = $vdaId ";
    $vSql .= " ORDER BY pro_descricao ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    if(count($arrRs) <= 0){
      /*$htmlTable .= "  <tr class=''>";
      $htmlTable .= "    <td colspan='8'>";
      $htmlTable .= "      <center>Nenhum resultado encontrado!</center>";
      $htmlTable .= "    </td>";
      $htmlTable .= "  </tr>";*/
    } else {
      foreach($arrRs as $rs1){
        $vProId        = $rs1["pro_id"];
        $vProDescricao = $rs1["pro_descricao"];
        $vVdiQtde      = $rs1["vdi_qtde"];
        $vVdiValor     = "R$" . number_format($rs1["vdi_valor"], 2, ",", ".");
        $vVdiDesconto  = "R$" . number_format($rs1["vdi_desconto"], 2, ",", ".");
        $vVdiTotal     = "R$" . number_format($rs1["vdi_total"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vProId</td>";
        $htmlTable .= "  <td>$vProDescricao</td>";
        $htmlTable .= "  <td>$vVdiQtde</td>";
        $htmlTable .= "  <td>$vVdiValor</td>";
        $htmlTable .= "  <td>$vVdiDesconto</td>";
        $htmlTable .= "  <td>$vVdiTotal</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbProduto_deletar' data-id='$vProId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  private function validaInsert($arrVendaItens){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVdiVdaId = (isset($arrVendaItens["vdi_vda_id"])) ? $arrVendaItens["vdi_vda_id"]: null;
    if( !is_numeric($vVdiVdaId) && !$vVdiVdaId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Venda!";
      return $arrRet;
    }

    $vVdiProId = (isset($arrVendaItens["vdi_pro_id"])) ? $arrVendaItens["vdi_pro_id"]: null;
    if( !is_numeric($vVdiProId) && !$vVdiProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVdiQtde = (isset($arrVendaItens["vdi_qtde"])) ? $arrVendaItens["vdi_qtde"]: null;
    if( !is_numeric($vVdiQtde) && !$vVdiQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVdiValor = (isset($arrVendaItens["vdi_valor"])) ? $arrVendaItens["vdi_valor"]: null;
    if( !is_numeric($vVdiValor) && !$vVdiValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaItens){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaItens);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vdaStatusInc = 1;
    $vVdiVdaId    = isset($arrVendaItens["vdi_vda_id"]) ? $arrVendaItens["vdi_vda_id"]: null;
    $vVdiProId    = isset($arrVendaItens["vdi_pro_id"]) ? $arrVendaItens["vdi_pro_id"]: null;
    $vVdiQtde     = isset($arrVendaItens["vdi_qtde"]) ? $arrVendaItens["vdi_qtde"]: null;
    $vVdiValor    = isset($arrVendaItens["vdi_valor"]) ? $arrVendaItens["vdi_valor"]: null;
    $vVdiDesconto = isset($arrVendaItens["vdi_desconto"]) ? $arrVendaItens["vdi_desconto"]: 0;

    $data = array(
      'vdi_vda_id' => $vVdiVdaId,
      'vdi_pro_id' => $vVdiProId,
      'vdi_qtde' => $vVdiQtde,
      'vdi_valor' => $vVdiValor,
      'vdi_desconto' => $vVdiDesconto,
      'vdi_status' => $vdaStatusInc,
    );

    $retInsert = $this->db->insert('tb_venda_itens', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $vdi_id = $this->db->insert_id();

      $arrRet["vdi_id"] = $vdi_id;
      $arrRet["erro"]   = false;
      $arrRet["msg"]    = "Produto inserido com sucesso!";
    }

    return $arrRet;
  }
}
