<?php
class Tb_Cont_Receber extends CI_Model {
  public function getContaReceber($ctrId){
    $arrRet                     = [];
    $arrRet["erro"]             = true;
    $arrRet["msg"]              = "";
    $arrRet["arrContaRecebDados"]  = array();

    if(!is_numeric($ctrId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar a parcela!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("ctr_id, ctr_cli_id, ctr_ven_id, ctr_vda_id, ctr_dtvencimento, ctr_valor, ctr_dtpagamento, ctr_valor_pago, ctr_obs, ctr_deletado");
    $this->db->from("tb_cont_receber");
    $this->db->where("ctr_id", $ctrId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrContaRecebDados = [];
      $arrContaRecebDados["ctr_id"]           = $row->ctr_id;
      $arrContaRecebDados["ctr_cli_id"]       = $row->ctr_cli_id;
      $arrContaRecebDados["ctr_ven_id"]       = $row->ctr_ven_id;
      $arrContaRecebDados["ctr_vda_id"]       = $row->ctr_vda_id;
      $arrContaRecebDados["ctr_dtvencimento"] = $row->ctr_dtvencimento;
      $arrContaRecebDados["ctr_valor"]        = $row->ctr_valor;
      $arrContaRecebDados["ctr_dtpagamento"]  = $row->ctr_dtpagamento;
      $arrContaRecebDados["ctr_valor_pago"]   = $row->ctr_valor_pago;
      $arrContaRecebDados["ctr_obs"]          = $row->ctr_obs;
      $arrContaRecebDados["ctr_deletado"]     = $row->ctr_deletado;

      $arrRet["arrContaRecebDados"] = $arrContaRecebDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

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

  public function getHtmlTotaisContasVenda($vdaId){
    $this->load->database();

    $vSql  = " SELECT vda_id, COALESCE(COUNT(ctr_id), 0) AS qt_parcelas, COALESCE(vda_vlr_itens, 0) AS total_venda, COALESCE(SUM(ctr_valor), 0) AS total_parcelas, COALESCE(SUM(COALESCE(ctr_valor, 0)) - vda_vlr_itens, 0) AS diferenca ";
    $vSql .= " FROM tb_venda ";
    $vSql .= " LEFT JOIN tb_cont_receber ON ctr_vda_id = vda_id ";
    $vSql .= " WHERE vda_id = $vdaId ";
    $vSql .= " GROUP BY vda_id ";

    $query = $this->db->query($vSql);
    $rs    = $query->row();

    if($rs){
      $qtParcelas    = $rs->qt_parcelas;
      $totalParcelas = isset($rs->total_parcelas) ? "R$ " . number_format($rs->total_parcelas, 2, ",", "."): "R$ 0,00";
      $totalVenda    = isset($rs->total_venda) ? "R$ " . number_format($rs->total_venda, 2, ",", "."): "R$ 0,00";

      $sinalDif = "";
      if($rs->diferenca > 0){
        $sinalDif = "+";
      }
      $diferenca = isset($rs->diferenca) ? $sinalDif . "R$ " . number_format($rs->diferenca, 2, ",", "."): "R$ 0,00";
    } else {
      $qtParcelas    = 0;
      $totalParcelas = "R$ 0,00";
      $totalVenda    = "R$ 0,00";
      $diferenca     = "R$ 0,00";
    }

    $this->load->helper('utils');
    $htmlQtdParcelas = getHtmlBlocoTotais("Qtd. Parcelas", $qtParcelas);
    $htmlVlrParcelas = getHtmlBlocoTotais("Total Parcelas", $totalParcelas);
    $htmlVlrPedido   = getHtmlBlocoTotais("Total Venda", $totalVenda);
    $htmlDiferenca   = getHtmlBlocoTotais("Diferença", $diferenca);

    $htmlTable  = "<div id='htmlTotaisContasVenda' class='widget-content'>";
    $htmlTable .= "  <div class='control-group' style='width: 100%; display: block; overflow: hidden;'>
                       <div class='span3 m-wrap'>
                         $htmlQtdParcelas
                       </div>
                       <div class='span3 m-wrap'>
                         $htmlVlrParcelas
                       </div>
                       <div class='span3 m-wrap'>
                         $htmlVlrPedido
                       </div>
                       <div class='span3 m-wrap'>
                         $htmlDiferenca
                       </div>
                     </div>";
    $htmlTable .= "</div>";

    return $htmlTable;
  }

  public function delete($ctrId){
    $arrRet           = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";

    if(!is_numeric($ctrId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para deletar!";

      return $arrRet;
    } else {
      $this->load->database();
      $this->db->where('ctr_id', $ctrId);
      $retDelete = $this->db->delete('tb_cont_receber');

      if(!$retDelete){
        $arrRet["erro"] = true;
        $arrRet["msg"]  = $this->db->_error_message();
      } else {
        $arrRet["erro"] = false;
        $arrRet["msg"] = "Parcela removida com sucesso!";
      }

      return $arrRet;
    }
  }
}
