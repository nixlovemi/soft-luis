<?php
class Tb_Venda_Mostruario extends CI_Model {
  public function getHtmlList(){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th>Vendedor</th>";
    $htmlTable .= "      <th>Data Entrega</th>";
    $htmlTable .= "      <th width='8%'>Qt Itens</th>";
    $htmlTable .= "      <th width='8%'>Total</th>";
    $htmlTable .= "      <th width='8%'>PDF</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Editar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vdm_id, ven_nome AS vendedor, vdm_dtentrega AS entrega ";
    $vSql .= "        ,COALESCE(COUNT(vmi_id), 0) AS qt_itens, COALESCE(SUM((vmi_qtde * vmi_valor) - vmi_desconto), 0) AS total ";
    $vSql .= " FROM tb_venda_mostruario ";
    $vSql .= " INNER JOIN tb_vendedor ON ven_id = vdm_ven_id ";
    $vSql .= " LEFT JOIN tb_venda_mostruario_itens ON vmi_vdm_id = vdm_id ";
    $vSql .= " WHERE vdm_dtacerto IS NULL ";
    $vSql .= " AND vdm_deletado = 0 ";
    $vSql .= " GROUP BY vdm_id ";
    $vSql .= " ORDER BY vdm_dtentrega ";

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
        $vVdmId    = $rs1["vdm_id"];
        $vVendedor = $rs1["vendedor"];
        $vEntrega  = ($rs1["entrega"] != "") ? date("d/m/Y", strtotime($rs1["entrega"])): "";
        $vQtItens  = $rs1["qt_itens"];
        $vTotal    = "R$" . number_format($rs1["total"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdmId</td>";
        $htmlTable .= "  <td>$vVendedor</td>";
        $htmlTable .= "  <td>$vEntrega</td>";
        $htmlTable .= "  <td>$vQtItens</td>";
        $htmlTable .= "  <td>$vTotal</td>";
        $htmlTable .= "  <td><a href='".base_url() . "Venda/pdfMostruario/$vVdmId" . "' target='_blank'><i class='icon-print icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Venda/verMostruario/$vVdmId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Venda/editarMostruario/$vVdmId" . "'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbVendaMostruario_deletar' data-id='$vVdmId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getHtmlListFinalizadas(){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID Mostruário</th>";
    $htmlTable .= "      <th>ID Venda</th>";
    $htmlTable .= "      <th>Data</th>";
    $htmlTable .= "      <th>Vendedor</th>";
    $htmlTable .= "      <th width='8%'>Qt Itens</th>";
    $htmlTable .= "      <th width='8%'>Total</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vdm_id, vda_id, ven_nome AS vendedor, vda_data AS data ";
    $vSql .= "        ,COALESCE(COUNT(vdi_id), 0) AS qt_itens, COALESCE(SUM((vdi_qtde * vdi_valor) - vdi_desconto), 0) AS total ";
    $vSql .= " FROM tb_venda_mostruario ";
    $vSql .= " INNER JOIN tb_vendedor ON ven_id = vdm_ven_id ";
    $vSql .= " INNER JOIN tb_venda ON vda_id = vdm_vda_id ";
    $vSql .= " INNER JOIN tb_venda_itens ON vdi_vda_id = vdm_vda_id ";
    $vSql .= " WHERE vdm_vda_id IS NOT NULL ";
    $vSql .= " AND vdm_deletado = 0 ";
    $vSql .= " GROUP BY vdm_id ";
    $vSql .= " ORDER BY vdm_dtentrega ";

    $query   = $this->db->query($vSql);
    $arrRs   = $query->result_array();
    $baseUrl = base_url();

    if(count($arrRs) <= 0){
      /*$htmlTable .= "  <tr class=''>";
      $htmlTable .= "    <td colspan='8'>";
      $htmlTable .= "      <center>Nenhum resultado encontrado!</center>";
      $htmlTable .= "    </td>";
      $htmlTable .= "  </tr>";*/
    } else {
      foreach($arrRs as $rs1){
        $vVdmId    = $rs1["vdm_id"];
        $vVdaId    = $rs1["vda_id"];
        $vData     = ($rs1["data"] != "") ? date("d/m/Y", strtotime($rs1["data"])): "";
        $vVendedor = $rs1["vendedor"];
        $vQtItens  = $rs1["qt_itens"];
        $vTotal    = "R$" . number_format($rs1["total"], 2, ",", ".");

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdmId</td>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vData</td>";
        $htmlTable .= "  <td>$vVendedor</td>";
        $htmlTable .= "  <td>$vQtItens</td>";
        $htmlTable .= "  <td>$vTotal</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='' onClick='document.location.href=\"".$baseUrl."Venda/ver/$vVdaId\"'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getMostruario($vdmId){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";
    $arrRet["arrVendaMostruarioDados"] = array();

    if(!is_numeric($vdmId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o Mostruário!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vdm_id, vdm_ven_id, ven_nome, vdm_dtentrega, vdm_dtacerto, vdm_deletado");
    $this->db->from("tb_venda_mostruario");
    $this->db->join('tb_vendedor', 'ven_id = vdm_ven_id');
    $this->db->where("vdm_id", $vdmId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrVendaMostruarioDados = [];
      $arrVendaMostruarioDados["vdm_id"]        = $row->vdm_id;
      $arrVendaMostruarioDados["vdm_ven_id"]    = $row->vdm_ven_id;
      $arrVendaMostruarioDados["ven_nome"]      = $row->ven_nome;
      $arrVendaMostruarioDados["vdm_dtentrega"] = $row->vdm_dtentrega;
      $arrVendaMostruarioDados["vdm_dtacerto"]  = $row->vdm_dtacerto;
      $arrVendaMostruarioDados["vdm_deletado"]  = $row->vdm_deletado;

      $arrRet["arrVendaMostruarioDados"] = $arrVendaMostruarioDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  public function getMostruarioItens($vdmId){
    $arrRet = [];
    $arrRet["erro"]        = true;
    $arrRet["msg"]         = "";
    $arrRet["arrVmiDados"] = array();

    if(!is_numeric($vdmId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar os itens do Mostruário!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vmi_id, vmi_pro_id, pro_descricao, vmi_qtde, vmi_valor, vmi_desconto");
    $this->db->from("tb_venda_mostruario_itens");
    $this->db->join('tb_produto', 'pro_id = vmi_pro_id', 'left');
    $this->db->where("vmi_vdm_id", $vdmId);
    $this->db->order_by("pro_descricao", "asc");
    $query = $this->db->get();

    foreach ($query->result() as $row) {
      $arrVim = [];
      $arrVim["vmi_id"]        = $row->vmi_id;
      $arrVim["vmi_pro_id"]    = $row->vmi_pro_id;
      $arrVim["pro_descricao"] = $row->pro_descricao;
      $arrVim["vmi_qtde"]      = $row->vmi_qtde;
      $arrVim["vmi_valor"]     = $row->vmi_valor;
      $arrVim["vmi_desconto"]  = $row->vmi_desconto;

      $arrRet["arrVmiDados"][] = $arrVim;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendaMostruarioDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVenId = (isset($arrVendaMostruarioDados["vdm_ven_id"])) ? $arrVendaMostruarioDados["vdm_ven_id"]: null;
    if( !is_numeric($vVenId) && !$vVenId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Vendedor!";
      return $arrRet;
    }

    $vData       = (isset($arrVendaMostruarioDados["vdm_dtentrega"])) ? $arrVendaMostruarioDados["vdm_dtentrega"]: "";
    $isDateValid = isValidDate($vData, "Y-m-d");
    if(!$isDateValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe uma data de entrega válida!";
      return $arrRet;
    }

    // checa se o vendedor ja esta com um Mostruario
    $sql = "SELECT vdm_id, ven_nome
            FROM tb_venda_mostruario
            INNER JOIN tb_vendedor ON ven_id = vdm_ven_id
            WHERE vdm_ven_id = $vVenId
            AND vdm_deletado <> 1
            AND vdm_dtacerto IS NULL";
    $this->load->database();
    $query = $this->db->query($sql);
    $row   = $query->row();

    if(isset($row)){
      $vVdmId   = $row->vdm_id;
      $vVenNome = $row->ven_nome;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "O vendedor $vVenNome já está com um mostruário (ID $vVdmId)! Faça o acerto antes de incluir um novo.";
      return $arrRet;
    }
    // =============================================

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaMostruarioDados){
    $arrRet           = [];
    $arrRet["vdm_id"] = null;
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaMostruarioDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vVdmVenId     = isset($arrVendaMostruarioDados["vdm_ven_id"]) && $arrVendaMostruarioDados["vdm_ven_id"] > 0 ? $arrVendaMostruarioDados["vdm_ven_id"]: null;
    $vVdmDtEntrega = isset($arrVendaMostruarioDados["vdm_dtentrega"]) && strlen($arrVendaMostruarioDados["vdm_dtentrega"]) == 10 ? $arrVendaMostruarioDados["vdm_dtentrega"]: null;
    $vVdmDtAcerto  = isset($arrVendaMostruarioDados["vdm_dtacerto"]) && strlen($arrVendaMostruarioDados["vdm_dtacerto"]) == 10 ? $arrVendaMostruarioDados["vdm_dtacerto"]: null;
    $vVdmVdaId     = isset($arrVendaMostruarioDados["vdm_vda_id"]) && $arrVendaMostruarioDados["vdm_vda_id"] > 0 ? $arrVendaMostruarioDados["vdm_ven_id"]: null;

    $data = array(
      'vdm_ven_id' => $vVdmVenId,
      'vdm_dtentrega' => $vVdmDtEntrega,
      'vdm_dtacerto' => $vVdmDtAcerto,
      'vdm_vda_id' => $vVdmVdaId,
    );

    $retInsert = $this->db->insert('tb_venda_mostruario', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $vdm_id = $this->db->insert_id();

      $arrRet["vdm_id"] = $vdm_id;
      $arrRet["erro"]   = false;
      $arrRet["msg"]    = "Mostruário inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrVendaMostruarioDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vVdmId = (isset($arrVendaMostruarioDados["vdm_id"])) ? $arrVendaMostruarioDados["vdm_id"]: "";
    if(!$vVdmId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível editar o Mostruário! ID inválido!";
      return $arrRet;
    }

    $vVenId = (isset($arrVendaMostruarioDados["vdm_ven_id"])) ? $arrVendaMostruarioDados["vdm_ven_id"]: null;
    if( !is_numeric($vVenId) && !$vVenId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Vendedor!";
      return $arrRet;
    }

    $this->load->helper('utils');

    $vData       = (isset($arrVendaMostruarioDados["vdm_dtentrega"])) ? $arrVendaMostruarioDados["vdm_dtentrega"]: "";
    $isDateValid = isValidDate($vData, "Y-m-d");
    if(!$isDateValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe uma data de entrega válida!";
      return $arrRet;
    }

    // checa se o vendedor ja esta com um Mostruario
    $sql = "SELECT vdm_id, ven_nome
            FROM tb_venda_mostruario
            INNER JOIN tb_vendedor ON ven_id = vdm_ven_id
            WHERE vdm_ven_id = $vVenId
            AND vdm_id <> $vVdmId
            AND vdm_deletado <> 1
            AND vdm_dtacerto IS NULL";
    $this->load->database();
    $query = $this->db->query($sql);
    $row   = $query->row();

    if(isset($row)){
      $vVdmId   = $row->vdm_id;
      $vVenNome = $row->ven_nome;

      $arrRet["erro"] = true;
      $arrRet["msg"]  = "O vendedor $vVenNome já está com um mostruário (ID $vVdmId)! Faça o acerto antes de incluir um novo.";
      return $arrRet;
    }
    // =============================================

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrVendaMostruarioDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrVendaMostruarioDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vVdmId        = isset($arrVendaMostruarioDados["vdm_id"]) ? $arrVendaMostruarioDados["vdm_id"]: null;
    $vVdmVenId     = isset($arrVendaMostruarioDados["vdm_ven_id"]) && $arrVendaMostruarioDados["vdm_ven_id"] > 0 ? $arrVendaMostruarioDados["vdm_ven_id"]: null;
    $vVdmDtEntrega = isset($arrVendaMostruarioDados["vdm_dtentrega"]) && strlen($arrVendaMostruarioDados["vdm_dtentrega"]) == 10 ? $arrVendaMostruarioDados["vdm_dtentrega"]: null;
    $vVdmDtAcerto  = isset($arrVendaMostruarioDados["vdm_dtacerto"]) && strlen($arrVendaMostruarioDados["vdm_dtacerto"]) == 10 ? $arrVendaMostruarioDados["vdm_dtacerto"]: null;
    $vVdmDeletado  = isset($arrVendaMostruarioDados["vdm_deletado"]) ? $arrVendaMostruarioDados["vdm_deletado"]: 0;
    $vVdmVdaId     = isset($arrVendaMostruarioDados["vdm_vda_id"]) && $arrVendaMostruarioDados["vdm_vda_id"] > 0 ? $arrVendaMostruarioDados["vdm_vda_id"]: null;

    $data = array(
      'vdm_ven_id' => $vVdmVenId,
      'vdm_dtentrega' => $vVdmDtEntrega,
      'vdm_dtacerto' => $vVdmDtAcerto,
      'vdm_deletado' => $vVdmDeletado,
      'vdm_vda_id' => $vVdmVdaId,
    );

    $this->db->where('vdm_id', $vVdmId);
    $retInsert = $this->db->update('tb_venda_mostruario', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Mostruario editado com sucesso!";
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

      $data = array(
        'vdm_deletado' => 1,
      );

      $this->db->where('vdm_id', $vdmId);
      $retDelete = $this->db->update('tb_venda_mostruario', $data);

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Mostruário deletado com sucesso!";
      }

      return $arrRet;
    }
  }

  public function finalizaAcerto($vdmId, $arrProdVenda){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";
    $arrRet["vda_id"] = "";

    if( !is_numeric($vdmId) ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Mostruário inválido para finalizar!";

      return $arrRet;
    }

    $semItem = count($arrProdVenda) <= 0;

    $this->load->database();
    $this->db->trans_begin();

    // pega venda mostruario
    $retVendaMostruario = $this->getMostruario($vdmId);
    if($retVendaMostruario["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao finalizar acerto. Msg: " . $retVendaMostruario["msg"];

      return $arrRet;
    } else {
      $VendaMostruario = $retVendaMostruario["arrVendaMostruarioDados"];
    }
    // =====================

    if(!$semItem){
      // insere uma venda ====
      $this->load->model('Tb_Venda');

      $arrSession   = $this->session->get_userdata('logged_in');
      $loggedUsuId  = $arrSession["logged_in"]["usu_id"];

      $Venda = [];
      $Venda["vda_data"]   = date("Y-m-d H:i:s");
      $Venda["vda_usu_id"] = $loggedUsuId;
      $Venda["vda_ven_id"] = $VendaMostruario["vdm_ven_id"];

      $retAddVenda = $this->Tb_Venda->insert($Venda);
      if($retAddVenda["erro"]){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "Erro ao gerar parcelas do acordo. Msg: " . $retAddVenda["msg"];

        return $arrRet;
      } else {
        $vdaId = $retAddVenda["vda_id"];
      }
      // =====================

      // insere os itens da venda
      $this->load->model('Tb_Venda_Itens');

      foreach($arrProdVenda as $key => $ProdVenda){
        $VendaItens = [];
        $VendaItens["vdi_vda_id"]   = $vdaId;
        $VendaItens["vdi_pro_id"]   = $ProdVenda["pro_id"];
        $VendaItens["vdi_qtde"]     = $ProdVenda["qtde"];
        $VendaItens["vdi_valor"]    = $ProdVenda["valor"];
        $VendaItens["vdi_desconto"] = 0;

        $retVendaItens = $this->Tb_Venda_Itens->insert($VendaItens);
        if($retVendaItens["erro"]){
          $arrRet["erro"] = true;
          $arrRet["msg"]  = "Erro ao inserir itens do acordo. Msg: " . $retVendaItens["msg"];

          return $arrRet;
        }
      }
      // ========================
    }

    // atualiza mostruario
    $VendaMostruario["vdm_dtacerto"] = (isset($Venda["vda_data"])) ? $Venda["vda_data"]: date("Y-m-d");
    $VendaMostruario["vdm_vda_id"]   = $vdaId;

    $retEditMostruario = $this->edit($VendaMostruario);
    if($retEditMostruario["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao finalizar acordo. Msg: " . $retEditMostruario["msg"];

      return $arrRet;
    }
    // ===================

    $this->db->trans_commit();

    // tudo certo
    $arrRet["erro"]   = false;
    $arrRet["msg"]    = "Acerto finalizado!";
    $arrRet["vda_id"] = $vdaId;

    return $arrRet;
    // ==========
  }
}
