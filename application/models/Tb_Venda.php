<?php
class Tb_Venda extends CI_Model {
  public function getHtmlListIncluidas(){
    $statusIncluidas = 1;

    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='6%'>ID</th>";
    $htmlTable .= "      <th width='12%'>Data</th>";
    $htmlTable .= "      <th>Cliente</th>";
    $htmlTable .= "      <th>Vendedor</th>";
    $htmlTable .= "      <th width='8%'>Itens</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Status</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Editar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vda_id, vda_data, cli_nome, ven_nome, vda_tot_itens, vda_vlr_itens, vds_status ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " INNER JOIN tb_venda_status ON vds_id = vda_status ";
    $vSql .= " LEFT JOIN tb_cliente ON cli_id = vda_cli_id ";
    $vSql .= " LEFT JOIN tb_vendedor ON ven_id = vda_ven_id ";
    $vSql .= " WHERE vds_id = $statusIncluidas ";
    $vSql .= " ORDER BY vda_id ";

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
        $vVdaId    = $rs1["vda_id"];
        $vVdaData  = ($rs1["vda_data"] != "") ? date("d/m/Y H:i", strtotime($rs1["vda_data"])): "";
        $vCliente  = $rs1["cli_nome"];
        $vVendedor = $rs1["ven_nome"];
        $vTotItens = $rs1["vda_tot_itens"];
        $vVlrItens = "R$" . number_format($rs1["vda_vlr_itens"], 2, ",", ".");
        $vStatus   = $rs1["vds_status"];

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vVdaData</td>";
        $htmlTable .= "  <td>$vCliente</td>";
        $htmlTable .= "  <td>$vVendedor</td>";
        $htmlTable .= "  <td>$vTotItens</td>";
        $htmlTable .= "  <td>$vVlrItens</td>";
        $htmlTable .= "  <td>$vStatus</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Venda/ver/$vVdaId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Venda/editar/$vVdaId" . "'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbVenda_deletar' data-id='$vVdaId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getHtmlListFinalizadas(){
    $statusFinalizadas = 2;

    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='6%'>ID</th>";
    $htmlTable .= "      <th width='12%'>Data</th>";
    $htmlTable .= "      <th>Cliente</th>";
    $htmlTable .= "      <th>Vendedor</th>";
    $htmlTable .= "      <th width='8%'>Itens</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Status</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vda_id, vda_data, cli_nome, ven_nome, vda_tot_itens, vda_vlr_itens, vds_status ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " INNER JOIN tb_venda_status ON vds_id = vda_status ";
    $vSql .= " LEFT JOIN tb_cliente ON cli_id = vda_cli_id ";
    $vSql .= " LEFT JOIN tb_vendedor ON ven_id = vda_ven_id ";
    $vSql .= " WHERE vds_id = $statusFinalizadas ";
    $vSql .= " ORDER BY vda_id ";

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
        $vVdaId    = $rs1["vda_id"];
        $vVdaData  = ($rs1["vda_data"] != "") ? date("d/m/Y H:i", strtotime($rs1["vda_data"])): "";
        $vCliente  = $rs1["cli_nome"];
        $vVendedor = $rs1["ven_nome"];
        $vTotItens = $rs1["vda_tot_itens"];
        $vVlrItens = "R$" . number_format($rs1["vda_vlr_itens"], 2, ",", ".");
        $vStatus   = $rs1["vds_status"];

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vVdaData</td>";
        $htmlTable .= "  <td>$vCliente</td>";
        $htmlTable .= "  <td>$vVendedor</td>";
        $htmlTable .= "  <td>$vTotItens</td>";
        $htmlTable .= "  <td>$vVlrItens</td>";
        $htmlTable .= "  <td>$vStatus</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='dynatableLink' data-url='".base_url() . "Venda/ver/$vVdaId" . "'><i class='icon-eye-open icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbVenda_deletar' data-id='$vVdaId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

  public function getVenda($vdaId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrVendaDados"]  = array();

    if(!is_numeric($vdaId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar a venda!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("vda_id, vda_data, vda_cli_id, vda_usu_id, vda_ven_id, vda_tot_itens, vda_vlr_itens, vda_status, vda_comissao");
    $this->db->from("tb_venda");
    $this->db->where("vda_id", $vdaId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrVendaDados = [];
      $arrVendaDados["vda_id"]        = $row->vda_id;
      $arrVendaDados["vda_data"]      = $row->vda_data;
      $arrVendaDados["vda_cli_id"]    = $row->vda_cli_id;
      $arrVendaDados["vda_usu_id"]    = $row->vda_usu_id;
      $arrVendaDados["vda_ven_id"]    = $row->vda_ven_id;
      $arrVendaDados["vda_tot_itens"] = $row->vda_tot_itens;
      $arrVendaDados["vda_vlr_itens"] = $row->vda_vlr_itens;
      $arrVendaDados["vda_status"]    = $row->vda_status;
      $arrVendaDados["vda_comissao"]  = $row->vda_comissao;

      $arrRet["arrVendaDados"] = $arrVendaDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrVendaDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vCliId = (isset($arrVendaDados["vda_cli_id"])) ? $arrVendaDados["vda_cli_id"]: null;
    $vVenId = (isset($arrVendaDados["vda_ven_id"])) ? $arrVendaDados["vda_ven_id"]: null;

    $temCli = is_numeric($vCliId) && $vCliId > 0;
    $temVen = is_numeric($vVenId) && $vVenId > 0;

    if( !$temCli && !$temVen ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Cliente OU o Vendedor!";
      return $arrRet;
    }

    $vUsuId = (isset($arrVendaDados["vda_usu_id"])) ? $arrVendaDados["vda_usu_id"]: null;
    if( !is_numeric($vUsuId) && !$vUsuId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Usuário!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrVendaDados){
    $arrRet           = [];
    $arrRet["vda_id"] = null;
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    $retValidacao = $this->validaInsert($arrVendaDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vdaStatusInc = 1;
    $vVdaData     = isset($arrVendaDados["vda_data"]) ? $arrVendaDados["vda_data"]: null;
    $vVdaCliId    = isset($arrVendaDados["vda_cli_id"]) && $arrVendaDados["vda_cli_id"] > 0 ? $arrVendaDados["vda_cli_id"]: null;
    $vVdaUsuId    = isset($arrVendaDados["vda_usu_id"]) && $arrVendaDados["vda_usu_id"] > 0 ? $arrVendaDados["vda_usu_id"]: null;
    $vVdaVenId    = isset($arrVendaDados["vda_ven_id"]) && $arrVendaDados["vda_ven_id"] > 0 ? $arrVendaDados["vda_ven_id"]: null;
    $vVdaStatus   = isset($arrVendaDados["vda_status"]) ? $arrVendaDados["vda_status"]: $vdaStatusInc;

    if($vVdaVenId > 0){
      $this->db->select("ven_comissao");
      $this->db->from("tb_vendedor");
      $this->db->where("ven_id", $vVdaVenId);
      $query = $this->db->get();
      $row   = $query->row();

      $vVdaComissao = (isset($row->ven_comissao) && $row->ven_comissao > 0) ? $row->ven_comissao: 0;
      $this->db->reset_query();
    } else {
        $vVdaComissao = 0;
    }

    $data = array(
      'vda_data'     => $vVdaData,
      'vda_cli_id'   => $vVdaCliId,
      'vda_usu_id'   => $vVdaUsuId,
      'vda_ven_id'   => $vVdaVenId,
      'vda_status'   => $vVdaStatus,
      'vda_comissao' => $vVdaComissao,
    );

    $retInsert = $this->db->insert('tb_venda', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $vda_id = $this->db->insert_id();

      $arrRet["vda_id"] = $vda_id;
      $arrRet["erro"]   = false;
      $arrRet["msg"]    = "Venda inserida com sucesso!";
    }

    return $arrRet;
  }

  public function cancelarVenda($vdaId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($vdaId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID de Venda inválido para cencelar!";

      return $arrRet;
    }

    $retGetVenda = $this->getVenda($vdaId);
    if($retGetVenda["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao pesquisar venda Msg: " . $retGetVenda["msg"];

      return $arrRet;
    } else {
      $statusVendaCanc = 3;
      $Venda = $retGetVenda["arrVendaDados"];

      if($Venda["vda_status"] == $statusVendaCanc){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = "A Venda ID $vdaId já está cancelada!";

        return $arrRet;
      } else {
        $this->load->database();
        $this->db->trans_start();

        $sql3 = " UPDATE tb_venda_mostruario SET vdm_deletado = 1 WHERE vdm_vda_id = $vdaId; ";
        $ret = $this->db->query($sql3);

        $sql2 = " UPDATE tb_cont_receber SET ctr_deletado = 1 WHERE ctr_vda_id = $vdaId; ";
        $ret = $this->db->query($sql2);

        $sql  = " UPDATE tb_venda SET vda_status = $statusVendaCanc WHERE vda_id = $vdaId; ";
        $ret = $this->db->query($sql);

        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
          $arrRet["erro"] = true;
          $arrRet["msg"]  = "Erro ao cancelar Venda!";
        } else {
          $arrRet["erro"] = false;
          $arrRet["msg"]  = "Venda cancelada com sucesso!";
        }

        return $arrRet;
      }
    }
  }

  public function finalizaVenda($vdaId){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    if(!is_numeric($vdaId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID de Venda inválido para cencelar!";

      return $arrRet;
    }

    $this->load->database();

    // verifica se tem pelo menos 1 item
    $sqlVI = "SELECT COALESCE(COUNT(*), 0) AS tot
              FROM tb_venda_itens
              WHERE vdi_vda_id = $vdaId";
    $queryVI = $this->db->query($sqlVI);
    $rowVI   = $queryVI->row();

    if(!isset($rowVI) || $rowVI->tot <= 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível finalizar venda - sem itens!";

      return $arrRet;
    }
    // =================================

    // valida estoque dos itens ========
    $sqlES = "SELECT COUNT(*) AS tot
              FROM tb_venda_itens
              LEFT JOIN tb_produto ON pro_id = vdi_pro_id
              WHERE vdi_vda_id = $vdaId
              AND vdi_qtde > pro_estoque";
    $queryES = $this->db->query($sqlES);
    $rowES   = $queryES->row();

    if(!isset($rowES) || $rowES->tot > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível finalizar venda - itens sem estoque!";

      return $arrRet;
    }
    // =================================

    // verifica parcelas ===============
    $sqlCR = "SELECT COUNT(*) AS tot
              FROM tb_cont_receber
              WHERE ctr_vda_id = $vdaId";
    $queryCR = $this->db->query($sqlCR);
    $rowCR   = $queryCR->row();

    if(!isset($rowCR) || $rowCR->tot <= 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível finalizar venda - sem parcelas!";

      return $arrRet;
    }
    // =================================

    // valida total da venda com as parcelas
    $sqlTV = "SELECT vda_id, vda_vlr_itens, SUM(COALESCE(ctr_valor, 0)) AS parcelas, vda_comissao
              FROM tb_venda
              LEFT JOIN tb_cont_receber ON ctr_vda_id = vda_id
              WHERE vda_id = $vdaId
              GROUP BY vda_id";
    $queryTV = $this->db->query($sqlTV);
    $rowTV   = $queryTV->row();

    $vlrComissao = ($rowTV->vda_comissao / 100) * $rowTV->vda_vlr_itens;
    if(!isset($rowTV) || $rowTV->parcelas <> $rowTV->vda_vlr_itens - $vlrComissao){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Não é possível finalizar venda - parcelas diferem do total da venda!";

      return $arrRet;
    }
    // =====================================

    // finaliza venda - estoque eh controlado por trigger
    $statusFin = 2;
    $sql1  = "UPDATE tb_venda SET vda_status = $statusFin WHERE vda_id = $vdaId";
    $retUP = $this->db->query($sql1);

    if($retUP == false){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao finalizar venda!";
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Venda finalizada com sucesso!";
    }

    return $arrRet;
  }

  public function relVendas($arrFiltros){
    $statusFinalizadas = 2;

    $arrRet = [];
    $arrRet["erro"]      = false;
    $arrRet["msg"]       = "";
    $arrRet["recordset"] = array();

    $vDataIni  = isset($arrFiltros["vdaDataIni"]) ? $arrFiltros["vdaDataIni"]: "";
    $vDataFim  = isset($arrFiltros["vdaDataFim"]) ? $arrFiltros["vdaDataFim"]: "";
    $vCliente  = isset($arrFiltros["vdaCliente"]) ? $arrFiltros["vdaCliente"]: "";
    $vVendedor = isset($arrFiltros["vdaVendedor"]) ? $arrFiltros["vdaVendedor"]: "";

    if( $vDataIni == "" && $vDataFim == "" ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe pelo menos uma data!";

      return $arrRet;
    }

    $vSql  = " SELECT vda_id, vda_data, cli_nome, ven_nome, vda_tot_itens, vda_vlr_itens ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " INNER JOIN tb_venda_status ON vds_id = vda_status ";
    $vSql .= " LEFT JOIN tb_cliente ON cli_id = vda_cli_id ";
    $vSql .= " LEFT JOIN tb_vendedor ON ven_id = vda_ven_id ";
    $vSql .= " WHERE vds_id = $statusFinalizadas ";

    if(strlen($vDataIni) == 10){
      $vSql .= " AND vda_data >= '$vDataIni 00:00:00' ";
    }

    if(strlen($vDataFim) == 10){
      $vSql .= " AND vda_data <= '$vDataFim 23:59:59' ";
    }

    if($vCliente > 0){
      $vSql .= " AND vda_cli_id = $vCliente ";
    }

    if($vVendedor > 0){
      $vSql .= " AND vda_ven_id = $vVendedor ";
    }

    $vSql .= " ORDER BY vda_data ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    $arrRet["recordset"] = $arrRs;
    return $arrRet;
  }
}
