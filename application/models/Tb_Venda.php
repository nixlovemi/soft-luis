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
    $htmlTable .= "      <th width='8%'>Itens</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Status</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Editar</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vda_id, vda_data, cli_nome, vda_tot_itens, vda_vlr_itens, vds_status ";
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
        $vTotItens = $rs1["vda_tot_itens"];
        $vVlrItens = "R$" . number_format($rs1["vda_vlr_itens"], 2, ",", ".");
        $vStatus   = $rs1["vds_status"];

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vVdaData</td>";
        $htmlTable .= "  <td>$vCliente</td>";
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
    $htmlTable .= "      <th width='8%'>Itens</th>";
    $htmlTable .= "      <th width='10%'>Valor</th>";
    $htmlTable .= "      <th width='10%'>Status</th>";
    $htmlTable .= "      <th width='8%'>Ver</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT vda_id, vda_data, cli_nome, vda_tot_itens, vda_vlr_itens, vds_status ";
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
        $vTotItens = $rs1["vda_tot_itens"];
        $vVlrItens = "R$" . number_format($rs1["vda_vlr_itens"], 2, ",", ".");
        $vStatus   = $rs1["vds_status"];

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vVdaData</td>";
        $htmlTable .= "  <td>$vCliente</td>";
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
    $this->db->select("vda_id, vda_data, vda_cli_id, vda_usu_id, vda_ven_id, vda_tot_itens, vda_vlr_itens, vda_status");
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
    if( !is_numeric($vCliId) && !$vCliId > 0 ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por Favor, informe o Cliente!";
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

    $data = array(
      'vda_data'   => $vVdaData,
      'vda_cli_id' => $vVdaCliId,
      'vda_usu_id' => $vVdaUsuId,
      'vda_ven_id' => $vVdaVenId,
      'vda_status' => $vVdaStatus,
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
}
