<?php
class Tb_Venda_Itens extends CI_Model {
  public function getHtmlList($vdaId, $delete=true){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Produto</th>";
    $htmlTable .= "      <th width='8%'>Quantidade</th>";
    $htmlTable .= "      <th width='8%'>Estoque</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Desconto</th>";
    $htmlTable .= "      <th width='10%'>Total</th>";
    $htmlTable .= "      <th width='8%'>Alterar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vdi_id, pro_id, pro_descricao, pro_estoque, vdi_qtde, vdi_valor, vdi_desconto, vdi_total ";
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
        $vVdiId        = $rs1["vdi_id"];
        $vVdiProId     = $rs1["pro_id"];
        $vProDescricao = $rs1["pro_descricao"];
        $vVdiQtde      = $rs1["vdi_qtde"];
        $vProEstoque   = $rs1["pro_estoque"];
        $vVdiValor     = "R$" . number_format($rs1["vdi_valor"], 2, ",", ".");
        $vVdiDesconto  = "R$" . number_format($rs1["vdi_desconto"], 2, ",", ".");
        $vVdiTotal     = "R$" . number_format($rs1["vdi_total"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdiProId</td>";
        $htmlTable .= "  <td>$vProDescricao</td>";
        $htmlTable .= "  <td>$vVdiQtde</td>";
        $htmlTable .= "  <td>$vProEstoque</td>";
        $htmlTable .= "  <td>$vVdiValor</td>";
        $htmlTable .= "  <td>$vVdiDesconto</td>";
        $htmlTable .= "  <td>$vVdiTotal</td>";
        if($delete){
          $htmlTable .= "  <td><a href='javascript:;' class='TbVendaItem_alterar' data-id='$vVdiId'><i class='icon-edit icon-lista'></i></a></td>";
          $htmlTable .= "  <td><a href='javascript:;' class='TbVendaItem_deletar' data-id='$vVdiId'><i class='icon-trash icon-lista'></i></a></td>";
        } else {
          $htmlTable .= "  <td>&nbsp;</td>";
          $htmlTable .= "  <td>&nbsp;</td>";
        }
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getHtmlTotVenda($vdaId){
    $this->load->database();

    $vTotProdutos = "0";
    $vVlrProdutos = "R$ 0,00";
    $vTotDesconto = "R$ 0,00";
    $vTotVenda    = "R$ 0,00";

    $vSql  = " SELECT vda_id, vda_tot_itens, vda_vlr_itens, SUM(vdi_qtde * vdi_valor) AS vlr_produtos, SUM(vdi_desconto) AS vlr_desconto ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " INNER JOIN tb_venda_itens ON vdi_vda_id = vda_id ";
    $vSql .= " WHERE vda_id = $vdaId ";
    $vSql .= " GROUP BY vda_id ";

    $query = $this->db->query($vSql);
    $rs    = $query->row();

    if($rs){
      $vTotProdutos = isset($rs->vda_tot_itens) ? $rs->vda_tot_itens: "0";
      $vVlrProdutos = isset($rs->vlr_produtos) ? "R$ " . number_format($rs->vlr_produtos, 2, ",", "."): "R$ 0,00";
      $vTotDesconto = isset($rs->vlr_desconto) ? "R$ " . number_format($rs->vlr_desconto, 2, ",", "."): "R$ 0,00";
      $vTotVenda    = isset($rs->vda_vlr_itens) ? "R$ " . number_format($rs->vda_vlr_itens, 2, ",", "."): "R$ 0,00";
    }

    $this->load->helper('utils');
    $htmlProdutos    = getHtmlBlocoTotais("PRODUTOS", $vTotProdutos);
    $htmlTotProdutos = getHtmlBlocoTotais("TOTAL PRODUTOS", $vVlrProdutos);
    $htmlTotDesconto = getHtmlBlocoTotais("TOTAL DESCONTO", $vTotDesconto);
    $htmlTotVenda    = getHtmlBlocoTotais("TOTAL VENDA", $vTotVenda, "bg_lg");

    $htmlTable = "<div class='control-group' style='width: 100%; display: block; overflow: hidden;'>
                    <div class='span3 m-wrap'>
                      $htmlProdutos
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotProdutos
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotDesconto
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotVenda
                    </div>
                  </div>";
    return $htmlTable;
  }

  public function getVendaItem($vdiId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrVendaItemDados"]  = array();

    if(!is_numeric($vdiId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o item da venda!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vdi_id, vdi_vda_id, vdi_pro_id, pro_descricao, vdi_qtde, vdi_valor, vdi_desconto, vdi_total, vdi_status");
    $this->db->from("tb_venda_itens");
    $this->db->join('tb_produto', 'pro_id = vdi_pro_id', 'left');
    $this->db->where("vdi_id", $vdiId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrVendaItemDados = [];
      $arrVendaItemDados["vdi_id"]        = $row->vdi_id;
      $arrVendaItemDados["vdi_vda_id"]    = $row->vdi_vda_id;
      $arrVendaItemDados["vdi_pro_id"]    = $row->vdi_pro_id;
      $arrVendaItemDados["vdi_qtde"]      = $row->vdi_qtde;
      $arrVendaItemDados["vdi_valor"]     = $row->vdi_valor;
      $arrVendaItemDados["vdi_desconto"]  = $row->vdi_desconto;
      $arrVendaItemDados["vdi_total"]     = $row->vdi_total;
      $arrVendaItemDados["vdi_status"]    = $row->vdi_status;
      $arrVendaItemDados["pro_descricao"] = $row->pro_descricao;

      $arrRet["arrVendaItemDados"] = $arrVendaItemDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
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

    // verifica se ja tem msm item com msm Valor
    $sqlV = "SELECT vdi_id, vdi_qtde
             FROM tb_venda_itens
             WHERE vdi_vda_id = $vVdiVdaId
             AND vdi_pro_id = $vVdiProId
             AND vdi_valor = $vVdiValor";
    $rsV  = $this->db->query($sqlV);
    $rowV = $rsV->row();
    // =========================================

    if(isset($rowV)){
      $vdiId       = $rowV->vdi_id;
      $retVdaItens = $this->getVendaItem($vdiId);

      if($retVdaItens["erro"]){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $retVdaItens["msg"];
        return $arrRet;
      } else {
        $VendaItem = $retVdaItens["arrVendaItemDados"];
        $VendaItem["vdi_qtde"] += $vVdiQtde;

        return $this->edit($VendaItem);
      }
    } else {
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

  private function validaEdit($arrVendaItens){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVdiId = (isset($arrVendaItens["vdi_id"])) ? $arrVendaItens["vdi_id"]: null;
    if(!$vVdiId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao identificar item da venda!";
      return $arrRet;
    }

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

  public function edit($arrVendaItens){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaEdit($arrVendaItens);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $vVdiId       = (isset($arrVendaItens["vdi_id"])) ? $arrVendaItens["vdi_id"]: "";
    $vVdiVdaId    = isset($arrVendaItens["vdi_vda_id"]) ? $arrVendaItens["vdi_vda_id"]: "";
    $vVdiProId    = isset($arrVendaItens["vdi_pro_id"]) ? $arrVendaItens["vdi_pro_id"]: "";
    $vVdiQtde     = isset($arrVendaItens["vdi_qtde"]) ? $arrVendaItens["vdi_qtde"]: "";
    $vVdiValor    = isset($arrVendaItens["vdi_valor"]) ? $arrVendaItens["vdi_valor"]: 0.01;
    $vVdiDesconto = isset($arrVendaItens["vdi_desconto"]) ? $arrVendaItens["vdi_desconto"]: 0.01;

    $data = array(
      'vdi_vda_id'   => $vVdiVdaId,
      'vdi_pro_id'   => $vVdiProId,
      'vdi_qtde'     => $vVdiQtde,
      'vdi_valor'    => $vVdiValor,
      'vdi_desconto' => $vVdiDesconto,
    );

    $this->db->where('vdi_id', $vVdiId);
    $retInsert = $this->db->update('tb_venda_itens', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Item editado com sucesso!";
    }

    return $arrRet;
  }

  public function delete($vdiId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($vdiId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->where('vdi_id', $vdiId);
      $retDelete = $this->db->delete('tb_venda_itens');

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Produto removido com sucesso!";
      }

      return $arrRet;
    }
  }
}
