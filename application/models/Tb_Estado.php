<?php
class Tb_Estado extends CI_Model {
  public function getEstados(){
    $arrRet               = [];
    $arrRet["erro"]       = true;
    $arrRet["msg"]        = "";
    $arrRet["arrEstados"] = array();

    $this->load->database();
    $this->db->select("est_id, est_sigla, est_descricao, est_codigo");
    $this->db->from("tb_estado");
    $this->db->order_by("est_descricao", "asc");
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $arrRs = $query->result_array();
      foreach($arrRs as $rs1){
        $arrEstadoDados = [];
        $arrEstadoDados["est_id"]        = $rs1["est_id"];
        $arrEstadoDados["est_sigla"]     = $rs1["est_sigla"];
        $arrEstadoDados["est_descricao"] = $rs1["est_descricao"];
        $arrEstadoDados["est_codigo"]    = $rs1["est_codigo"];

        $arrRet["arrEstados"][] = $arrEstadoDados;
      }
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }
}
