<?php
class Tb_Cont_Receber extends CI_Model {
  private function validaInsert($arrContRecebDados){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vCliId = (isset($arrContRecebDados["ctr_cli_id"])) ? $arrContRecebDados["ctr_cli_id"]: "";
    $vVenId = (isset($arrContRecebDados["ctr_ven_id"])) ? $arrContRecebDados["ctr_ven_id"]: "";
    if( $vCliId == "" && $vVenId == "" ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "A Conta a Receber precisa ter um Cliente ou uma Venda!";
      return $arrRet;
    }

    $vData       = (isset($arrContRecebDados["ctr_dtvencimento"])) ? $arrContRecebDados["ctr_dtvencimento"]: "";
    $isVctoValid = isValidDate($vData, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um data de vencimento válida!";
      return $arrRet;
    }

    $vValor = (isset($arrContRecebDados["ctr_valor"])) ? $arrContRecebDados["ctr_valor"]: "";
    if(!is_numeric($vValor) || !$vValor > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrContRecebDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrContRecebDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vCliId    = isset($arrContRecebDados["ctr_cli_id"]) && $arrContRecebDados["ctr_cli_id"] > 0 ? $arrContRecebDados["ctr_cli_id"]: null;
    $vVenId    = isset($arrContRecebDados["ctr_ven_id"]) && $arrContRecebDados["ctr_ven_id"] > 0 ? $arrContRecebDados["ctr_ven_id"]: null;
    $vVdaId    = isset($arrContRecebDados["ctr_vda_id"]) && $arrContRecebDados["ctr_vda_id"] > 0 ? $arrContRecebDados["ctr_vda_id"]: null;
    $vVcto     = isset($arrContRecebDados["ctr_dtvencimento"]) && strlen($arrContRecebDados["ctr_dtvencimento"]) == 10 ? $arrContRecebDados["ctr_dtvencimento"]: null;
    $vValor    = isset($arrContRecebDados["ctr_valor"]) && $arrContRecebDados["ctr_valor"] > 0 ? $arrContRecebDados["ctr_valor"]: 0.01;
    $vPgto     = isset($arrContRecebDados["ctr_dtpagamento"]) && strlen($arrContRecebDados["ctr_dtpagamento"]) == 10 ? $arrContRecebDados["ctr_dtpagamento"]: null;
    $vValorPg  = isset($arrContRecebDados["ctr_valor_pago"]) && $arrContRecebDados["ctr_valor_pago"] > 0 ? $arrContRecebDados["ctr_valor_pago"]: null;
    $vObs      = isset($arrContRecebDados["ctr_obs"]) && $arrContRecebDados["ctr_obs"] != "" ? $arrContRecebDados["ctr_obs"]: "";
    $vDeletado = isset($arrContRecebDados["ctr_deletado"]) ? $arrContRecebDados["ctr_deletado"]: false;

    $data = array(
      'ctr_cli_id'       => $vCliId,
      'ctr_ven_id'       => $vVenId,
      'ctr_vda_id'       => $vVdaId,
      'ctr_dtvencimento' => $vVcto,
      'ctr_valor'        => $vValor,
      'ctr_dtpagamento'  => $vPgto,
      'ctr_valor_pago'   => $vValorPg,
      'ctr_obs'          => $vObs,
      'ctr_deletado'     => $vDeletado,
    );

    $retInsert = $this->db->insert('tb_cont_receber', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Conta Receber inserida com sucesso!";
    }

    return $arrRet;
  }

  public function getHtmlContasVenda($vdaId){
    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='8%'>ID</th>";
    $htmlTable .= "      <th width='8%'>ID Venda</th>";
    $htmlTable .= "      <th>Cliente</th>";
    $htmlTable .= "      <th width='9%'>Vencimento</th>";
    $htmlTable .= "      <th width='9%'>Valor</th>";
    $htmlTable .= "      <th width='9%'>Pagamento</th>";
    $htmlTable .= "      <th width='9%'>Valor Pago</th>";
    $htmlTable .= "      <th width='8%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT ctr_id, ctr_vda_id, cli_nome, ctr_dtvencimento, ctr_valor, ctr_dtpagamento, ctr_valor_pago ";
    $vSql .= " FROM tb_cont_receber ";
    $vSql .= " LEFT JOIN tb_cliente ON cli_id = ctr_cli_id ";
    $vSql .= " WHERE ctr_vda_id = $vdaId ";
    $vSql .= " ORDER BY ctr_dtvencimento ";

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
        $vCtrId   = $rs1["ctr_id"];
        $vVdaId   = $rs1["ctr_vda_id"];
        $vCliNome = $rs1["cli_nome"];
        $vVcto    = (strlen($rs1["ctr_dtvencimento"]) == 10) ? date("d/m/Y", strtotime($rs1["ctr_dtvencimento"])): "";
        $vValor   = (is_numeric($rs1["ctr_valor"])) ? "R$ " . number_format($rs1["ctr_valor"], 2, ",", "."): "";
        $vPgto    = (strlen($rs1["ctr_dtpagamento"]) == 10) ? date("d/m/Y", strtotime($rs1["ctr_dtpagamento"])): "";
        $vValorPg = (is_numeric($rs1["ctr_valor_pago"])) ? "R$ " . number_format($rs1["ctr_valor_pago"], 2, ",", "."): "";

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vCtrId</td>";
        $htmlTable .= "  <td>$vVdaId</td>";
        $htmlTable .= "  <td>$vCliNome</td>";
        $htmlTable .= "  <td>$vVcto</td>";
        $htmlTable .= "  <td>$vValor</td>";
        $htmlTable .= "  <td>$vPgto</td>";
        $htmlTable .= "  <td>$vValorPg</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbContReceber_ajax_deletar' data-id='$vCtrId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    return $htmlTable;
  }
}
