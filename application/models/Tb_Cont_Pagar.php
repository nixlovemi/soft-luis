<?php
class Tb_Cont_Pagar extends CI_Model {
  public function getHtmlContasPagar($arrFilters=array()){
    // filtros
    $vVctoIni    = isset($arrFilters["vctoIni"]) ? $arrFilters["vctoIni"]: "";
    $vVctoFim    = isset($arrFilters["vctoFim"]) ? $arrFilters["vctoFim"]: "";
    $vPgtoIni    = isset($arrFilters["pgtoIni"]) ? $arrFilters["pgtoIni"]: "";
    $vPgtoFim    = isset($arrFilters["pgtoFim"]) ? $arrFilters["pgtoFim"]: "";
    $vPagas      = isset($arrFilters["apenasPagas"]) ? $arrFilters["apenasPagas"]: "";
    // =======

    // sql filter
    $sqlFilter = "";

    if($vVctoIni != ""){
      $sqlFilter .= " AND ctp_dtvencimento >= '$vVctoIni' ";
    }

    if($vVctoFim != ""){
      $sqlFilter .= " AND ctp_dtvencimento <= '$vVctoFim' ";
    }

    if($vPgtoIni != ""){
      $sqlFilter .= " AND ctp_dtpagamento >= '$vPgtoIni' ";
    }

    if($vPgtoFim != ""){
      $sqlFilter .= " AND ctp_dtpagamento <= '$vPgtoFim' ";
    }

    if($vPagas == "S"){
      $sqlFilter .= " AND ctp_dtpagamento IS NOT NULL ";
    } else if ($vPagas == "N"){
      $sqlFilter .= " AND ctp_dtpagamento IS NULL ";
    }
    // ==========

    $this->load->database();
    $htmlTable  = "";
    $htmlTable .= "<table class='table table-bordered dynatable' id='tbProdutoGetHtmlList'>";
    $htmlTable .= "  <thead>";
    $htmlTable .= "    <tr>";
    $htmlTable .= "      <th width='7%'>ID</th>";
    $htmlTable .= "      <th>Pagar à</th>";
    $htmlTable .= "      <th width='9%'>Vencimento</th>";
    $htmlTable .= "      <th width='9%'>Valor</th>";
    $htmlTable .= "      <th width='9%'>Pagamento</th>";
    $htmlTable .= "      <th width='9%'>Valor Pago</th>";
    $htmlTable .= "      <th width='7%'>Alterar</th>";
    $htmlTable .= "      <th width='7%'>Deletar</th>";
    $htmlTable .= "    </tr>";
    $htmlTable .= "  </thead>";
    $htmlTable .= "  <tbody>";

    $vSql  = " SELECT ctp_id, ctp_dtvencimento, ctp_valor, ctp_dtpagamento, ctp_valor_pago, ctp_fornecedor ";
    $vSql .= " FROM tb_cont_pagar ";
    $vSql .= " WHERE 1=1 ";
    $vSql .= " AND ctp_deletado = 0 ";
    $vSql .= " $sqlFilter ";
    $vSql .= " ORDER BY ctp_dtvencimento ";

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
        $vCtpId   = $rs1["ctp_id"];
        $vCtpForn = $rs1["ctp_fornecedor"];
        $vVcto    = (strlen($rs1["ctp_dtvencimento"]) == 10) ? date("d/m/Y", strtotime($rs1["ctp_dtvencimento"])): "";
        $vValor   = (is_numeric($rs1["ctp_valor"])) ? "R$ " . number_format($rs1["ctp_valor"], 2, ",", "."): "";
        $vPgto    = (strlen($rs1["ctp_dtpagamento"]) == 10) ? date("d/m/Y", strtotime($rs1["ctp_dtpagamento"])): "";
        $vValorPg = (is_numeric($rs1["ctp_valor_pago"])) ? "R$ " . number_format($rs1["ctp_valor_pago"], 2, ",", "."): "";

        $htmlTable .= "<tr>";
        $htmlTable .= "  <td>$vCtpId</td>";
        $htmlTable .= "  <td>$vCtpForn</td>";
        $htmlTable .= "  <td>$vVcto</td>";
        $htmlTable .= "  <td>$vValor</td>";
        $htmlTable .= "  <td>$vPgto</td>";
        $htmlTable .= "  <td>$vValorPg</td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbContReceber_ajax_alterar' data-id='$vCtpId'><i class='icon-edit icon-lista'></i></a></td>";
        $htmlTable .= "  <td><a href='javascript:;' class='TbContPagar_ajax_deletar' data-id='$vCtpId'><i class='icon-trash icon-lista'></i></a></td>";
        $htmlTable .= "</tr>";
      }
    }

    $htmlTable .= "  </tbody>";
    $htmlTable .= "</table>";

    // totais
    $vSqlT  = " SELECT COUNT(*) AS qt_contas, SUM(COALESCE(ctp_valor, 0)) AS tot_valor, SUM(COALESCE(ctp_valor_pago, 0)) AS tot_pago ";
    $vSqlT .= " FROM tb_cont_pagar ";
    $vSqlT .= " WHERE 1=1 ";
    $vSqlT .= " AND ctp_deletado = 0 ";
    $vSqlT .= " $sqlFilter ";

    $queryT = $this->db->query($vSqlT);
    $rs    = $queryT->row();
    if($rs){
      $qtContas   = $rs->qt_contas;
      $totalValor = isset($rs->tot_valor) ? "R$ " . number_format($rs->tot_valor, 2, ",", "."): "R$ 0,00";
      $totalPago  = isset($rs->tot_pago) ? "R$ " . number_format($rs->tot_pago, 2, ",", "."): "R$ 0,00";
    } else {
      $qtContas   = 0;
      $totalValor = "R$ 0,00";
      $totalPago  = "R$ 0,00";
    }

    $this->load->helper('utils');
    $htmlQtdContas = getHtmlBlocoTotais("Qt Contas", $qtContas);
    $htmlValor     = getHtmlBlocoTotais("Total Valor", $totalValor);
    $htmlPago      = getHtmlBlocoTotais("Total Pago", $totalPago);

    $htmlTable .= "<div id='htmlTotaisContaPagar' class='widget-content'>";
    $htmlTable .= "  <div class='control-group' style='width: 100%; display: block; overflow: hidden;'>
                       <div class='span4 m-wrap'>
                         $htmlQtdContas
                       </div>
                       <div class='span4 m-wrap'>
                         $htmlValor
                       </div>
                       <div class='span4 m-wrap'>
                         $htmlPago
                       </div>
                     </div>";
    $htmlTable .= "</div>";
    // ======

    return $htmlTable;
  }

  public function getContaPagar($ctpId){
    $arrRet                       = [];
    $arrRet["erro"]               = true;
    $arrRet["msg"]                = "";
    $arrRet["arrContaPagarDados"] = array();

    if(!is_numeric($ctpId)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para buscar o pagamento!";
      return $arrRet;
    }

    $this->load->database();
    $this->db->select("ctp_id, ctp_dtvencimento, ctp_valor, ctp_dtpagamento, ctp_valor_pago, ctp_fornecedor, ctp_obs, ctp_deletado");
    $this->db->from("tb_cont_pagar");
    $this->db->where("ctp_id", $ctpId);
    $query = $this->db->get();

    if($query->num_rows() > 0){
      $row = $query->row();

      $arrContaPagarDados = [];
      $arrContaPagarDados["ctp_id"]           = $row->ctp_id;
      $arrContaPagarDados["ctp_dtvencimento"] = $row->ctp_dtvencimento;
      $arrContaPagarDados["ctp_valor"]        = $row->ctp_valor;
      $arrContaPagarDados["ctp_dtpagamento"]  = $row->ctp_dtpagamento;
      $arrContaPagarDados["ctp_valor_pago"]   = $row->ctp_valor_pago;
      $arrContaPagarDados["ctp_fornecedor"]   = $row->ctp_fornecedor;
      $arrContaPagarDados["ctp_obs"]          = $row->ctp_obs;
      $arrContaPagarDados["ctp_deletado"]     = $row->ctp_deletado;

      $arrRet["arrContaPagarDados"] = $arrContaPagarDados;
    }

    $arrRet["erro"] = false;
    return $arrRet;
  }

  private function validaInsert($arrContPagarDados){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vData       = (isset($arrContPagarDados["ctp_dtvencimento"])) ? $arrContPagarDados["ctp_dtvencimento"]: "";
    $isVctoValid = isValidDate($vData, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um data de vencimento válida!";
      return $arrRet;
    }

    $vValor = (isset($arrContPagarDados["ctp_valor"])) ? $arrContPagarDados["ctp_valor"]: "";
    if(!is_numeric($vValor) || !$vValor > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function insert($arrContPagarDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaInsert($arrContPagarDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    $this->load->database();

    $vVcto       = isset($arrContPagarDados["ctp_dtvencimento"]) && strlen($arrContPagarDados["ctp_dtvencimento"]) == 10 ? $arrContPagarDados["ctp_dtvencimento"]: null;
    $vValor      = isset($arrContPagarDados["ctp_valor"]) && $arrContPagarDados["ctp_valor"] > 0 ? $arrContPagarDados["ctp_valor"]: 0.01;
    $vPgto       = isset($arrContPagarDados["ctp_dtpagamento"]) && strlen($arrContPagarDados["ctp_dtpagamento"]) == 10 ? $arrContPagarDados["ctp_dtpagamento"]: null;
    $vValorPg    = isset($arrContPagarDados["ctp_valor_pago"]) && $arrContPagarDados["ctp_valor_pago"] > 0 ? $arrContPagarDados["ctp_valor_pago"]: null;
    $vFornecedor = isset($arrContPagarDados["ctp_fornecedor"]) && $arrContPagarDados["ctp_fornecedor"] != "" ? $arrContPagarDados["ctp_fornecedor"]: "";
    $vObs        = isset($arrContPagarDados["ctp_obs"]) && $arrContPagarDados["ctp_obs"] != "" ? $arrContPagarDados["ctp_obs"]: "";
    $vDeletado   = isset($arrContPagarDados["ctp_deletado"]) ? $arrContPagarDados["ctp_deletado"]: false;

    $data = array(
      'ctp_dtvencimento' => $vVcto,
      'ctp_valor'        => $vValor,
      'ctp_dtpagamento'  => $vPgto,
      'ctp_valor_pago'   => $vValorPg,
      'ctp_fornecedor'   => $vFornecedor,
      'ctp_obs'          => $vObs,
      'ctp_deletado'     => $vDeletado,
    );

    $retInsert = $this->db->insert('tb_cont_pagar', $data);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Pagamento inserido com sucesso!";
    }

    return $arrRet;
  }

  private function validaEdit($arrContPagarDados){
    $this->load->helper('utils');

    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $vCtpId = (isset($arrContPagarDados["ctp_id"])) ? $arrContPagarDados["ctp_id"]: "";
    if(!$vCtpId > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "ID inválido para editar o Pagamento!";
      return $arrRet;
    }

    $vData       = (isset($arrContPagarDados["ctp_dtvencimento"])) ? $arrContPagarDados["ctp_dtvencimento"]: "";
    $isVctoValid = isValidDate($vData, "Y-m-d");
    if(!$isVctoValid){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um data de vencimento válida!";
      return $arrRet;
    }

    $vValor = (isset($arrContPagarDados["ctp_valor"])) ? $arrContPagarDados["ctp_valor"]: "";
    if(!is_numeric($vValor) || !$vValor > 0){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe um valor válido!";
      return $arrRet;
    }

    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    return $arrRet;
  }

  public function edit($arrContPagarDados){
    $arrRet         = [];
    $arrRet["erro"] = true;
    $arrRet["msg"]  = "";

    $retValidacao = $this->validaEdit($arrContPagarDados);
    if($retValidacao["erro"]){
      return $retValidacao;
    }

    // Pagamento
    $vCtpId        = (isset($arrContPagarDados["ctp_id"])) ? $arrContPagarDados["ctp_id"]: "";
    $retContaPagar = $this->getContaPagar($vCtpId);
    if($retContaPagar["erro"]){
      $arrRet["msg"] = $retContaPagar["msg"];
      return $arrRet;
    } else {
      $ContaPagar = $retContaPagar["arrContaPagarDados"];
    }
    // =============

    $this->load->database();

    $vVcto       = isset($arrContPagarDados["ctp_dtvencimento"]) && strlen($arrContPagarDados["ctp_dtvencimento"]) == 10 ? $arrContPagarDados["ctp_dtvencimento"]: null;
    $vValor      = isset($arrContPagarDados["ctp_valor"]) && $arrContPagarDados["ctp_valor"] > 0 ? $arrContPagarDados["ctp_valor"]: 0.01;
    $vPgto       = isset($arrContPagarDados["ctp_dtpagamento"]) && strlen($arrContPagarDados["ctp_dtpagamento"]) == 10 ? $arrContPagarDados["ctp_dtpagamento"]: null;
    $vValorPg    = isset($arrContPagarDados["ctp_valor_pago"]) && $arrContPagarDados["ctp_valor_pago"] > 0 ? $arrContPagarDados["ctp_valor_pago"]: null;
    $vFornecedor = isset($arrContPagarDados["ctp_fornecedor"]) && $arrContPagarDados["ctp_fornecedor"] != "" ? $arrContPagarDados["ctp_fornecedor"]: "";
    $vObs        = isset($arrContPagarDados["ctp_obs"]) && $arrContPagarDados["ctp_obs"] != "" ? $arrContPagarDados["ctp_obs"]: "";
    $vDeletado   = isset($arrContPagarDados["ctp_deletado"]) ? $arrContPagarDados["ctp_deletado"]: false;

    $ContaPagar["ctp_dtvencimento"] = $vVcto;
    $ContaPagar["ctp_valor"]        = $vValor;
    $ContaPagar["ctp_dtpagamento"]  = $vPgto;
    $ContaPagar["ctp_valor_pago"]   = $vValorPg;
    $ContaPagar["ctp_fornecedor"]   = $vFornecedor;
    $ContaPagar["ctp_obs"]          = $vObs;
    $ContaPagar["ctp_deletado"]     = $vDeletado;

    $this->db->where('ctp_id', $vCtpId);
    $retInsert = $this->db->update('tb_cont_pagar', $ContaPagar);
    if(!$retInsert){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $this->db->_error_message();
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Pagamento editado com sucesso!";
    }

    return $arrRet;
  }
}
