<?php
class Tb_Venda_Mostruario_Itens extends CI_Model {
  public function getHtmlList($vdmId, $delete=true){
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
    $htmlTable .= "      <th width='8%'>Alterar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vmi_id, pro_id, pro_descricao, vmi_qtde, vmi_valor, vmi_desconto ";
    $vSql .= " FROM tb_venda_mostruario_itens ";
    $vSql .= " LEFT JOIN tb_produto ON pro_id = vmi_pro_id ";
    $vSql .= " WHERE vmi_vdm_id = $vdmId ";
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
        $vVmiId        = $rs1["vmi_id"];
        $vVmiProId     = $rs1["pro_id"];
        $vProDescricao = $rs1["pro_descricao"];
        $vVmiQtde      = $rs1["vmi_qtde"];
        $vVmiValor     = "R$" . number_format($rs1["vmi_valor"], 2, ",", ".");
        $vVmiDesconto  = "R$" . number_format($rs1["vmi_desconto"], 2, ",", ".");
        $vVmiTotal     = "R$" . number_format(($rs1["vmi_qtde"] * $rs1["vmi_valor"]) - $rs1["vmi_desconto"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVmiProId</td>";
        $htmlTable .= "  <td>$vProDescricao</td>";
        $htmlTable .= "  <td>$vVmiQtde</td>";
        $htmlTable .= "  <td>$vVmiValor</td>";
        $htmlTable .= "  <td>$vVmiDesconto</td>";
        $htmlTable .= "  <td>$vVmiTotal</td>";
        if($delete){
          $htmlTable .= "  <td><a href='javascript:;' class='TbVendaMostruarioItem_alterar' data-id='$vVmiId'><i class='icon-edit icon-lista'></i></a></td>";
          $htmlTable .= "  <td><a href='javascript:;' class='TbVendaMostruarioItem_deletar' data-id='$vVmiId'><i class='icon-trash icon-lista'></i></a></td>";
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

  public function getHtmlTotVendaMostru($vdmId){
    $this->load->database();

    $vTotProdutos = "0";
    $vVlrProdutos = "R$ 0,00";
    $vTotDesconto = "R$ 0,00";
    $vTotVenda    = "R$ 0,00";

    $vSql  = " SELECT vdm_id, COALESCE(SUM(vmi_qtde), 0) AS tot_itens, COALESCE(SUM(vmi_qtde * vmi_valor), 0) AS vlr_produtos, COALESCE(SUM(vmi_desconto), 0) AS vlr_desconto ";
    $vSql .= " FROM tb_venda_mostruario ";
    $vSql .= " INNER JOIN tb_venda_mostruario_itens ON vmi_vdm_id = vdm_id ";
    $vSql .= " WHERE vdm_id = $vdmId ";
    $vSql .= " GROUP BY vdm_id ";

    $query = $this->db->query($vSql);
    $rs    = $query->row();

    if($rs){
      $vTotProdutos = isset($rs->tot_itens) ? $rs->tot_itens: "0";
      $vVlrProdutos = isset($rs->vlr_produtos) ? "R$ " . number_format($rs->vlr_produtos, 2, ",", "."): "R$ 0,00";
      $vTotDesconto = isset($rs->vlr_desconto) ? "R$ " . number_format($rs->vlr_desconto, 2, ",", "."): "R$ 0,00";
      $vTotVenda    = "R$ " . number_format($rs->vlr_produtos - $rs->vlr_desconto, 2, ",", ".");
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

  public function getVendaItemMostru($vmiId){
    $arrRet                        = [];
    $arrRet["erro"]                = true;
    $arrRet["msg"]                 = "";
    $arrRet["VendaMostruarioItem"] = array();

    if(!is_numeric($vmiId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o item do mostruário!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vmi_id, vmi_vdm_id, vmi_pro_id, pro_descricao, vmi_qtde, vmi_valor, vmi_desconto");
    $this->db->from("tb_venda_mostruario_itens");
    $this->db->join('tb_produto', 'pro_id = vmi_pro_id', 'left');
    $this->db->where("vmi_id", $vmiId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $VendaMostruarioItem = [];
      $VendaMostruarioItem["vmi_id"]        = $row->vmi_id;
      $VendaMostruarioItem["vmi_vdm_id"]    = $row->vmi_vdm_id;
      $VendaMostruarioItem["vmi_pro_id"]    = $row->vmi_pro_id;
      $VendaMostruarioItem["vmi_qtde"]      = $row->vmi_qtde;
      $VendaMostruarioItem["vmi_valor"]     = $row->vmi_valor;
      $VendaMostruarioItem["vmi_desconto"]  = $row->vmi_desconto;
      $VendaMostruarioItem["total"]         = ($row->vmi_qtde * $row->vmi_valor) - $row->vmi_desconto;
      $VendaMostruarioItem["pro_descricao"] = $row->pro_descricao;

      $arrRet["VendaMostruarioItem"] = $VendaMostruarioItem;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getProdutosVdm($vdmId){
    $arrRet = [];
    $arrRet["erro"]           = true;
    $arrRet["msg"]            = "";
    $arrRet["arrProdutosVdm"] = array();

    $this->load->database();
    $this->db->select("pro_id, pro_descricao, pro_codigo, pro_ean, pro_estoque, pro_prec_custo, pro_prec_venda, pro_observacao, pro_ativo");
    $this->db->from("tb_produto");
    $this->db->join('tb_venda_mostruario_itens', 'pro_id = vmi_pro_id');
    $this->db->order_by("pro_descricao", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrProdutosDados = [];
        $arrProdutosDados["pro_id"]         = $rs1["pro_id"];
        $arrProdutosDados["pro_descricao"]  = $rs1["pro_descricao"];
        $arrProdutosDados["pro_codigo"]     = $rs1["pro_codigo"];
        $arrProdutosDados["pro_ean"]        = $rs1["pro_ean"];
        $arrProdutosDados["pro_estoque"]    = $rs1["pro_estoque"];
        $arrProdutosDados["pro_prec_custo"] = $rs1["pro_prec_custo"];
        $arrProdutosDados["pro_prec_venda"] = $rs1["pro_prec_venda"];
        $arrProdutosDados["pro_observacao"] = $rs1["pro_observacao"];
        $arrProdutosDados["pro_ativo"]      = $rs1["pro_ativo"];

        $arrRet["arrProdutosVdm"][] = $arrProdutosDados;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendaMostruItens){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVmiVdmId = (isset($arrVendaMostruItens["vmi_vdm_id"])) ? $arrVendaMostruItens["vmi_vdm_id"]: null;
    if( !is_numeric($vVmiVdmId) && !$vVmiVdmId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Mostruário!";
      return $arrRet;
    }

    $vVmiProId = (isset($arrVendaMostruItens["vmi_pro_id"])) ? $arrVendaMostruItens["vmi_pro_id"]: null;
    if( !is_numeric($vVmiProId) && !$vVmiProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVmiQtde = (isset($arrVendaMostruItens["vmi_qtde"])) ? $arrVendaMostruItens["vmi_qtde"]: null;
    if( !is_numeric($vVmiQtde) && !$vVmiQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVmiValor = (isset($arrVendaMostruItens["vmi_valor"])) ? $arrVendaMostruItens["vmi_valor"]: null;
    if( !is_numeric($vVmiValor) && !$vVmiValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaMostruItens){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaMostruItens);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vdaStatusInc = 1;
    $vVmiVdmId    = isset($arrVendaMostruItens["vmi_vdm_id"]) ? $arrVendaMostruItens["vmi_vdm_id"]: null;
    $vVmiProId    = isset($arrVendaMostruItens["vmi_pro_id"]) ? $arrVendaMostruItens["vmi_pro_id"]: null;
    $vVmiQtde     = isset($arrVendaMostruItens["vmi_qtde"]) ? $arrVendaMostruItens["vmi_qtde"]: null;
    $vVmiValor    = isset($arrVendaMostruItens["vmi_valor"]) ? $arrVendaMostruItens["vmi_valor"]: null;
    $vVmiDesconto = isset($arrVendaMostruItens["vmi_desconto"]) ? $arrVendaMostruItens["vmi_desconto"]: 0;

    // verifica se ja tem msm item com msm Valor
    $sqlV = "SELECT vmi_id, vmi_qtde
             FROM tb_venda_mostruario_itens
             WHERE vmi_vdm_id = $vVmiVdmId
             AND vmi_pro_id = $vVmiProId
             AND vmi_valor = $vVmiValor";
    $rsV  = $this->db->query($sqlV);
    $rowV = $rsV->row();
    // =========================================

    if(isset($rowV)){
      $vmiId = $rowV->vmi_id;
      $retVdaMostItens = $this->getVendaItemMostru($vmiId);

      if($retVdaMostItens["erro"]){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $retVdaMostItens["msg"];
        return $arrRet;
      } else {
        $VendaMostItem = $retVdaMostItens["VendaMostruarioItem"];
        $VendaMostItem["vmi_qtde"] += $vVmiQtde;

        return $this->edit($VendaMostItem);
      }
    } else {
      $data = array(
        'vmi_vdm_id' => $vVmiVdmId,
        'vmi_pro_id' => $vVmiProId,
        'vmi_qtde' => $vVmiQtde,
        'vmi_valor' => $vVmiValor,
        'vmi_desconto' => $vVmiDesconto,
      );

      $retInsert = $this->db->insert('tb_venda_mostruario_itens', $data);
      if(!$retInsert){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $vmi_id = $this->db->insert_id();

        $arrRet["vmi_id"] = $vmi_id;
        $arrRet["erro"]   = false;
        $arrRet["msg"]    = "Produto inserido com sucesso!";
      }

      return $arrRet;
    }
  }

  private function validaEdit($arrVendaMostruItens){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVmiId = (isset($arrVendaMostruItens["vmi_id"])) ? $arrVendaMostruItens["vmi_id"]: null;
    if(!$vVmiId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao identificar item do mostruário!";
      return $arrRet;
    }

    $vVmiVdmId = (isset($arrVendaMostruItens["vmi_vdm_id"])) ? $arrVendaMostruItens["vmi_vdm_id"]: null;
    if( !is_numeric($vVmiVdmId) && !$vVmiVdmId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Mostruário!";
      return $arrRet;
    }

    $vVmiProId = (isset($arrVendaMostruItens["vmi_pro_id"])) ? $arrVendaMostruItens["vmi_pro_id"]: null;
    if( !is_numeric($vVmiProId) && !$vVmiProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVmiQtde = (isset($arrVendaMostruItens["vmi_qtde"])) ? $arrVendaMostruItens["vmi_qtde"]: null;
    if( !is_numeric($vVmiQtde) && !$vVmiQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVmiValor = (isset($arrVendaMostruItens["vmi_valor"])) ? $arrVendaMostruItens["vmi_valor"]: null;
    if( !is_numeric($vVmiValor) && !$vVmiValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrVendaMostruItens){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaEdit($arrVendaMostruItens);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $vVmiId       = (isset($arrVendaMostruItens["vmi_id"])) ? $arrVendaMostruItens["vmi_id"]: "";
    $vVmiVdaId    = isset($arrVendaMostruItens["vmi_vdm_id"]) ? $arrVendaMostruItens["vmi_vdm_id"]: "";
    $vVmiProId    = isset($arrVendaMostruItens["vmi_pro_id"]) ? $arrVendaMostruItens["vmi_pro_id"]: "";
    $vVmiQtde     = isset($arrVendaMostruItens["vmi_qtde"]) ? $arrVendaMostruItens["vmi_qtde"]: "";
    $vVmiValor    = isset($arrVendaMostruItens["vmi_valor"]) ? $arrVendaMostruItens["vmi_valor"]: 0.01;
    $vVmiDesconto = isset($arrVendaMostruItens["vmi_desconto"]) ? $arrVendaMostruItens["vmi_desconto"]: 0.01;

    $data = array(
      'vmi_vdm_id'   => $vVmiVdaId,
      'vmi_pro_id'   => $vVmiProId,
      'vmi_qtde'     => $vVmiQtde,
      'vmi_valor'    => $vVmiValor,
      'vmi_desconto' => $vVmiDesconto,
    );

    $this->db->where('vmi_id', $vVmiId);
    $retInsert = $this->db->update('tb_venda_mostruario_itens', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Item editado com sucesso!";
    }

    return $arrRet;
  }

  public function delete($vdmId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($vdmId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->where('vmi_id', $vdmId);
      $retDelete = $this->db->delete('tb_venda_mostruario_itens');

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
