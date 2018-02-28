<?php
class Tb_Produto extends CI_Model {

  public function getHtmlList(){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th>ID</th>";
    $htmlTable .= "      <th>Código</th>";
    $htmlTable .= "      <th>Descrição</th>";
    $htmlTable .= "      <th>Estoque</th>";
    $htmlTable .= "      <th>Preço Venda</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT pro_id, pro_codigo, pro_descricao, pro_estoque, pro_prec_venda ";
    $vSql .= " FROM tb_produto ";
    $vSql .= " WHERE pro_ativo = true ";
    $vSql .= " ORDER BY pro_descricao ";

    $query = $this->db->query($vSql);
    $arrRs = $query->result_array();

    if(count($arrRs) <= 0){
      $htmlTable .= "  <tr class=''>";
      $htmlTable .= "    <td colspan='5'>";
      $htmlTable .= "      <center>Nenhum resultado encontrado!</center>";
      $htmlTable .= "    </td>";
      $htmlTable .= "  </tr>";
    } else {
      foreach($arrRs as $rs1){

      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }

}
