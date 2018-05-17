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

    $data = array(
      'vdm_ven_id' => $vVdmVenId,
      'vdm_dtentrega' => $vVdmDtEntrega,
      'vdm_dtacerto' => $vVdmDtAcerto,
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
}
