<?php
class Tb_Venda_Mostruario_Itens_Ret extends CI_Model {
  /**
  * retorna array com itens conferência e os vendidos
  */
  public function getArrItensConfVendido($vdmId){
    $arrRet = [];
    $arrRet["erro"]     = true;
    $arrRet["msg"]      = "";

    $arrRet["arrItens"] = [];
    $arrRet["arrItens"]["vendidos"]   = [];
    $arrRet["arrItens"]["conferidos"] = [];


    if( !is_numeric($vdmId) ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para busca!";
      return $arrRet;
    }

    $this->load->database();

    // array itens Mostruario
    $vSql = "SELECT vmi_id, vmi_pro_id, pro_descricao, pro_codigo, vmi_qtde, vmi_valor
             FROM tb_venda_mostruario_itens
             INNER JOIN tb_produto ON pro_id = vmi_pro_id
             WHERE vmi_vdm_id = $vdmId
             ORDER BY pro_descricao";
    $rsV  = $this->db->query($vSql);

    $arrMostrItens = [];
    foreach($rsV->result() as $row){
      $v_vmi_id   = $row->vmi_id;
      $v_pro_id   = $row->vmi_pro_id;
      $v_pro_desc = $row->pro_descricao;
      $v_pro_cod  = $row->pro_codigo;
      $v_qtde     = $row->vmi_qtde;
      $v_valor    = $row->vmi_valor;

      $idx = $v_pro_id . "-" . $v_qtde . "-" . $v_valor;
      if( array_key_exists($idx, $arrMostrItens) ){
        $arrMostrItens[$idx]["qtde"] += $v_qtde;
      } else {
        $arrMostrItens[$idx] = [];
        $arrMostrItens[$idx]["vmi_id"]   = $v_vmi_id;
        $arrMostrItens[$idx]["pro_id"]   = $v_pro_id;
        $arrMostrItens[$idx]["pro_desc"] = $v_pro_desc;
        $arrMostrItens[$idx]["pro_cod"]  = $v_pro_cod;
        $arrMostrItens[$idx]["qtde"]     = $v_qtde;
        $arrMostrItens[$idx]["valor"]    = $v_valor;
      }
    }
    // ======================

    // array itens conferência
    $vSql2 = "SELECT vmir_id, vmir_pro_id, pro_descricao, pro_codigo, vmir_qtde, vmir_valor
              FROM tb_venda_mostruario_itens_ret
              INNER JOIN tb_produto ON pro_id = vmir_pro_id
              WHERE vmir_vdm_id = $vdmId
              ORDER BY pro_descricao";
    $rsV2  = $this->db->query($vSql2);

    $arrMostrItensRet = [];
    foreach($rsV2->result() as $row2){
      $v_vmir_id   = $row2->vmir_id;
      $v_pro_id2   = $row2->vmir_pro_id;
      $v_pro_desc2 = $row2->pro_descricao;
      $v_pro_cod2  = $row2->pro_codigo;
      $v_qtde2     = $row2->vmir_qtde;
      $v_valor2    = $row2->vmir_valor;

      $idx2 = $v_pro_id2 . "-" . $v_qtde2 . "-" . $v_valor2;
      if( array_key_exists($idx2, $arrMostrItensRet) ){
        $arrMostrItensRet[$idx2]["qtde"] += $v_qtde;
      } else {
        $arrMostrItensRet[$idx2] = [];
        $arrMostrItensRet[$idx2]["vmir_id"] = $v_vmir_id;
        $arrMostrItensRet[$idx2]["pro_id"] = $v_pro_id2;
        $arrMostrItensRet[$idx2]["pro_desc"] = $v_pro_desc2;
        $arrMostrItensRet[$idx2]["pro_cod"]  = $v_pro_cod2;
        $arrMostrItensRet[$idx2]["qtde"]   = $v_qtde2;
        $arrMostrItensRet[$idx2]["valor"]  = $v_valor2;
      }
    }
    // =======================

    // array itens vendidos
    $arrItensVenda = [];

    foreach($arrMostrItens as $miKey => $mostruarioItem){
      foreach($arrMostrItensRet as $mirKey => $mostruarioItemRet){
        $ehMsmItem  = $mostruarioItem["pro_id"] == $mostruarioItemRet["pro_id"];
        $ehMsmValor = $mostruarioItem["valor"] == $mostruarioItemRet["valor"];
        if($ehMsmItem && $ehMsmValor){
          $mostruarioItem["qtde"] -= $mostruarioItemRet["qtde"];
        }
      }

      if($mostruarioItem["qtde"] > 0){
        $arrItensVenda[$miKey] = $mostruarioItem;
      }
    }
    // ====================

    $arrRet["erro"] = false;
    $arrRet["arrItens"]["vendidos"]   = $arrItensVenda;
    $arrRet["arrItens"]["conferidos"] = $arrMostrItensRet;
    return $arrRet;
  }

  public function getHtmlItensConfVendido($vdmId){
    $arrRet = [];
    $arrRet["erro"]          = true;
    $arrRet["msg"]           = "";
    $arrRet["htmlConferido"] = "";
    $arrRet["htmlVendido"]   = "";

    if( !is_numeric($vdmId) ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para busca!";
      return $arrRet;
    }

    $retArrConfVend = $this->getArrItensConfVendido($vdmId);
    if($retArrConfVend["erro"]){
      return $retArrConfVend;
    }

    $arrConferidos = $retArrConfVend["arrItens"]["conferidos"];
    $arrVendidos   = $retArrConfVend["arrItens"]["vendidos"];

    // totais
    $vTotQtde  = 0;
    $vTotValor = 0;
    // ======

    // html vendidos
    $htmlTableV = "";
    if( count($arrVendidos) > 0 ){
      $htmlTableV .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
      $htmlTableV .= "  <thead>";
      $htmlTableV .= "    <tr>";
      $htmlTableV .= "      <th width='8%'>ID</th>";
      $htmlTableV .= "      <th>Produto</th>";
      $htmlTableV .= "      <th width='8%'>Quantidade</th>";
      $htmlTableV .= "      <th width='10%'>Valor</th>";
      $htmlTableV .= "      <th width='10%'>Total</th>";
      $htmlTableV .= "    </tr>";
      $htmlTableV .= "  </thead>";
      $htmlTableV .= "  <tbody>";

      foreach($arrVendidos as $itemVendido){
        $vProIdV    = $itemVendido["pro_id"];
        $vProDescV  = $itemVendido["pro_desc"];
        $vProQtdeV  = $itemVendido["qtde"];
        $vProValorV = $itemVendido["valor"];
        $vProTotalV = $vProQtdeV * $vProValorV;

        $vTotQtde  += $vProQtdeV;
        $vTotValor += $vProTotalV;

        $htmlTableV .= "  <tr>";
        $htmlTableV .= "    <td width='8%'>$vProIdV</td>";
        $htmlTableV .= "    <td>$vProDescV</td>";
        $htmlTableV .= "    <td width='8%'>$vProQtdeV</td>";
        $htmlTableV .= "    <td width='10%'>R$ ".number_format($vProValorV, 2, ",", ".")."</td>";
        $htmlTableV .= "    <td width='10%'>R$ ".number_format($vProTotalV, 2, ",", ".")."</td>";
        $htmlTableV .= "  </tr>";
      }

      $htmlTableV .= "  </tbody>";
      $htmlTableV .= "</table>";
    }
    // =============

    // html conferido
    $htmlTableC = "";

    if( count($arrConferidos) > 0 ){
      $htmlTableC .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
      $htmlTableC .= "  <thead>";
      $htmlTableC .= "    <tr>";
      $htmlTableC .= "      <th width='8%'>ID</th>";
      $htmlTableC .= "      <th>Produto</th>";
      $htmlTableC .= "      <th width='8%'>Quantidade</th>";
      $htmlTableC .= "      <th width='10%'>Valor</th>";
      $htmlTableC .= "      <th width='10%'>Total</th>";
      $htmlTableC .= "      <th width='8%'>Deletar</th>";
      $htmlTableC .= "    </tr>";
      $htmlTableC .= "  </thead>";
      $htmlTableC .= "  <tbody>";

      foreach($arrConferidos as $itemConferido){
        $vmirId     = $itemConferido["vmir_id"];
        $vProIdC    = $itemConferido["pro_id"];
        $vProDescC  = $itemConferido["pro_desc"];
        $vProQtdeC  = $itemConferido["qtde"];
        $vProValorC = $itemConferido["valor"];
        $vProTotalC = $vProQtdeC * $vProValorC;

        $htmlTableC .= "  <tr>";
        $htmlTableC .= "    <td width='8%'>$vProIdC</td>";
        $htmlTableC .= "    <td>$vProDescC</td>";
        $htmlTableC .= "    <td width='8%'>$vProQtdeC</td>";
        $htmlTableC .= "    <td width='10%'>R$ ".number_format($vProValorC, 2, ",", ".")."</td>";
        $htmlTableC .= "    <td width='10%'>R$ ".number_format($vProTotalC, 2, ",", ".")."</td>";
        $htmlTableC .= "    <td width='10%'><a href='javascript:;' class='TbVendaMostruarioItemRet_deletar' data-id='$vmirId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTableC .= "  </tr>";
      }

      $htmlTableC .= "  </tbody>";
      $htmlTableC .= "</table>";
    }
    // =============

    // html totais
    $this->load->helper('utils');

    $htmlProdutos    = getHtmlBlocoTotais("PRODUTOS", $vTotQtde);
    $htmlTotVenda    = getHtmlBlocoTotais("TOTAL ACERTO", "R$ " . number_format($vTotValor, 2, ",", "."), "bg_lg");

    $htmlTable = "<div class='control-group' style='width: 100%; display: block; overflow: hidden;'>
                    <div class='span2 m-wrap'>
                      &nbsp;
                    </div>
                    <div class='span2 m-wrap'>
                      $htmlProdutos
                    </div>
                    <div class='span3 m-wrap'>
                      $htmlTotVenda
                    </div>
                  </div>";
    $htmlTableV .= "<br />$htmlTable";
    // ===========

    $arrRet["erro"]          = false;
    $arrRet["htmlConferido"] = $htmlTableC;
    $arrRet["htmlVendido"]   = $htmlTableV;

    return $arrRet;
  }

  public function getVendaItemMostruRet($vmirId){
    $arrRet = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";
    $arrRet["VendaMostruarioItemRet"] = array();

    if(!is_numeric($vmirId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o item do acerto!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vmir_id, vmir_vdm_id, vmir_pro_id, pro_descricao, vmir_qtde, vmir_valor");
    $this->db->from("tb_venda_mostruario_itens_ret");
    $this->db->join('tb_produto', 'pro_id = vmir_pro_id', 'left');
    $this->db->where("vmir_id", $vmirId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $VendaMostruarioItemRet = [];
      $VendaMostruarioItemRet["vmir_id"]       = $row->vmir_id;
      $VendaMostruarioItemRet["vmir_vdm_id"]   = $row->vmir_vdm_id;
      $VendaMostruarioItemRet["vmir_pro_id"]   = $row->vmir_pro_id;
      $VendaMostruarioItemRet["vmir_qtde"]     = $row->vmir_qtde;
      $VendaMostruarioItemRet["vmir_valor"]    = $row->vmir_valor;
      $VendaMostruarioItemRet["total"]         = ($row->vmir_qtde * $row->vmir_valor);
      $VendaMostruarioItemRet["pro_descricao"] = $row->pro_descricao;

      $arrRet["VendaMostruarioItemRet"] = $VendaMostruarioItemRet;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendaMostruItensRet){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVmirVdmId = (isset($arrVendaMostruItensRet["vmir_vdm_id"])) ? $arrVendaMostruItensRet["vmir_vdm_id"]: null;
    if( !is_numeric($vVmirVdmId) && !$vVmirVdmId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Mostruário!";
      return $arrRet;
    }

    $vVmirProId = (isset($arrVendaMostruItensRet["vmir_pro_id"])) ? $arrVendaMostruItensRet["vmir_pro_id"]: null;
    if( !is_numeric($vVmirProId) && !$vVmirProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVmirQtde = (isset($arrVendaMostruItensRet["vmir_qtde"])) ? $arrVendaMostruItensRet["vmir_qtde"]: null;
    if( !is_numeric($vVmirQtde) && !$vVmirQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVmirValor = (isset($arrVendaMostruItensRet["vmir_valor"])) ? $arrVendaMostruItensRet["vmir_valor"]: null;
    if( !is_numeric($vVmirValor) && !$vVmirValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    // nao deixa colocar a mais do que o mostruario tem
    $this->load->database();

    $vSql = "SELECT COALESCE(vmir_qtde, 0) AS vmir_qtde, COALESCE(vmi_qtde, 0) AS vmi_qtde
             FROM tb_venda_mostruario_itens
             LEFT JOIN tb_venda_mostruario_itens_ret ON (vmi_vdm_id = vmir_vdm_id AND vmi_pro_id = vmir_pro_id AND vmi_valor = vmir_valor)
             WHERE vmi_vdm_id = $vVmirVdmId
             AND vmi_pro_id = $vVmirProId
             AND vmi_valor = $vVmirValor";
    $query = $this->db->query($vSql);
    $row   = $query->row();

    if(isset($row)){
      $excede = ($row->vmir_qtde + $vVmirQtde) > $row->vmi_qtde;
      if($excede){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "A quantidade inserida excede a quantidade do mostruário!";
        return $arrRet;
      }
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaMostruItensRet){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaMostruItensRet);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vdaStatusInc = 1;
    $vVmirVdmId    = isset($arrVendaMostruItensRet["vmir_vdm_id"]) ? $arrVendaMostruItensRet["vmir_vdm_id"]: null;
    $vVmirProId    = isset($arrVendaMostruItensRet["vmir_pro_id"]) ? $arrVendaMostruItensRet["vmir_pro_id"]: null;
    $vVmirQtde     = isset($arrVendaMostruItensRet["vmir_qtde"]) ? $arrVendaMostruItensRet["vmir_qtde"]: null;
    $vVmirValor    = isset($arrVendaMostruItensRet["vmir_valor"]) ? $arrVendaMostruItensRet["vmir_valor"]: null;

    // verifica se ja tem msm item com msm Valor
    $sqlV = "SELECT vmir_id, vmir_qtde
             FROM tb_venda_mostruario_itens_ret
             WHERE vmir_vdm_id = $vVmirVdmId
             AND vmir_pro_id = $vVmirProId
             AND vmir_valor = $vVmirValor";
    $rsV  = $this->db->query($sqlV);
    $rowV = $rsV->row();
    // =========================================

    if(isset($rowV)){
      $vmirId = $rowV->vmir_id;
      $retVdaMostItensRet = $this->getVendaItemMostruRet($vmirId);

      if($retVdaMostItensRet["erro"]){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $retVdaMostItensRet["msg"];
        return $arrRet;
      } else {
        $VendaMostItemRet = $retVdaMostItensRet["VendaMostruarioItemRet"];
        $VendaMostItemRet["vmir_qtde"] += $vVmirQtde;

        return $this->edit($VendaMostItemRet);
      }
    } else {
      $data = array(
        'vmir_vdm_id' => $vVmirVdmId,
        'vmir_pro_id' => $vVmirProId,
        'vmir_qtde' => $vVmirQtde,
        'vmir_valor' => $vVmirValor,
      );

      $retInsert = $this->db->insert('tb_venda_mostruario_itens_ret', $data);
      if(!$retInsert){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $vmir_id = $this->db->insert_id();

        $arrRet["vmir_id"] = $vmir_id;
        $arrRet["erro"]    = false;
        $arrRet["msg"]     = "Produto acerto inserido com sucesso!";
      }

      return $arrRet;
    }
  }

  private function validaEdit($arrVendaMostruItensRet){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVmirId = (isset($arrVendaMostruItensRet["vmir_id"])) ? $arrVendaMostruItensRet["vmir_id"]: null;
    if(!$vVmirId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao identificar item do mostruário!";
      return $arrRet;
    }

    $vVmirVdmId = (isset($arrVendaMostruItensRet["vmir_vdm_id"])) ? $arrVendaMostruItensRet["vmir_vdm_id"]: null;
    if( !is_numeric($vVmirVdmId) && !$vVmirVdmId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Mostruário!";
      return $arrRet;
    }

    $vVmirProId = (isset($arrVendaMostruItensRet["vmir_pro_id"])) ? $arrVendaMostruItensRet["vmir_pro_id"]: null;
    if( !is_numeric($vVmirProId) && !$vVmirProId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Produto!";
      return $arrRet;
    }

    $vVmirQtde = (isset($arrVendaMostruItensRet["vmir_qtde"])) ? $arrVendaMostruItensRet["vmir_qtde"]: null;
    if( !is_numeric($vVmirQtde) && !$vVmirQtde > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe a Quantidade!";
      return $arrRet;
    }

    $vVmirValor = (isset($arrVendaMostruItensRet["vmir_valor"])) ? $arrVendaMostruItensRet["vmir_valor"]: null;
    if( !is_numeric($vVmirValor) && !$vVmirValor > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Valor!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrVendaMostruItensRet){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaEdit($arrVendaMostruItensRet);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $vVmirId    = (isset($arrVendaMostruItensRet["vmir_id"])) ? $arrVendaMostruItensRet["vmir_id"]: "";
    $vVmiVdaId  = isset($arrVendaMostruItensRet["vmir_vdm_id"]) ? $arrVendaMostruItensRet["vmir_vdm_id"]: "";
    $vVmirProId = isset($arrVendaMostruItensRet["vmir_pro_id"]) ? $arrVendaMostruItensRet["vmir_pro_id"]: "";
    $vVmirQtde  = isset($arrVendaMostruItensRet["vmir_qtde"]) ? $arrVendaMostruItensRet["vmir_qtde"]: "";
    $vVmirValor = isset($arrVendaMostruItensRet["vmir_valor"]) ? $arrVendaMostruItensRet["vmir_valor"]: 0.01;

    $data = array(
      'vmir_vdm_id'   => $vVmiVdaId,
      'vmir_pro_id'   => $vVmirProId,
      'vmir_qtde'     => $vVmirQtde,
      'vmir_valor'    => $vVmirValor,
    );

    $this->db->where('vmir_id', $vVmirId);
    $retInsert = $this->db->update('tb_venda_mostruario_itens_ret', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Item editado com sucesso!";
    }

    return $arrRet;
  }

  public function delete($vmirId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($vmirId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->where('vmir_id', $vmirId);
      $retDelete = $this->db->delete('tb_venda_mostruario_itens_ret');

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Produto removido do acerto com sucesso!";
      }

      return $arrRet;
    }
  }
}
