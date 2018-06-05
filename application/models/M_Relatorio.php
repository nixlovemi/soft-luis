<?php
class M_Relatorio extends CI_Model {
  public function relFluxoCx($arrFiltros){
    $arrRet = [];
    $arrRet["erro"]      = false;
    $arrRet["msg"]       = "";
    $arrRet["recordset"] = array();

    $vDataIni   = isset($arrFiltros["cxaDataIni"]) ? $arrFiltros["cxaDataIni"]: "";
    $vDataFim   = isset($arrFiltros["cxaDataFim"]) ? $arrFiltros["cxaDataFim"]: "";
    $vDataSaldo = isset($arrFiltros["cxaDataSaldo"]) ? $arrFiltros["cxaDataSaldo"]: "";
    $vVlrSaldo  = isset($arrFiltros["cxaVlrSaldo"]) ? $arrFiltros["cxaVlrSaldo"]: "";

    if( $vDataIni == "" || $vDataFim == "" ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe o período do relatório!";

      return $arrRet;
    }

    if($vDataIni > $vDataFim){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe o período corretamente!";

      return $arrRet;
    }

    if($vDataSaldo == ""){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe a data do saldo inicial!";

      return $arrRet;
    }

    if(!is_numeric($vVlrSaldo)){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Por favor, informe o valor do saldo inicial!";

      return $arrRet;
    }

    $this->load->database();

    // contas a receber
    $arrCR = [];

    $sqlCR = "SELECT ctr_dtvencimento AS vcto, SUM(ctr_valor) AS total
              FROM tb_cont_receber
              WHERE ctr_dtpagamento IS NULL
              AND ctr_dtvencimento BETWEEN '$vDataIni' AND '$vDataFim'
              GROUP BY ctr_dtvencimento";
    $qryCR = $this->db->query($sqlCR);
    $rsCR = $qryCR->result_array();
    foreach($rsCR as $row){
      $vcto  = $row["vcto"];
      $total = $row["total"];

      $arrCR[$vcto] = $total;
    }
    // ================

    // contas a pagar =
    $arrCP = [];

    $sqlCP = "SELECT ctp_dtvencimento AS vcto, SUM(ctp_valor) AS total
              FROM tb_cont_pagar
              WHERE ctp_dtpagamento IS NULL
              AND ctp_dtvencimento BETWEEN '$vDataIni' AND '$vDataFim'
              GROUP BY ctp_dtvencimento";
    $qryCP = $this->db->query($sqlCP);
    $rsCP = $qryCP->result_array();
    foreach($rsCP as $row){
      $vcto  = $row["vcto"];
      $total = $row["total"];

      $arrCP[$vcto] = $total;
    }
    // ================

    $arrRecordset = [];
    $dtAtual      = $vDataIni;
    $acumulado    = 0;

    while($dtAtual <= $vDataFim){
      $arrRecordset[$dtAtual] = [];

      $nrDiaSemana                   = date("w", strtotime($dtAtual));
      $dowMap                        = array('Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb');
      $txtDiaSemana                  = $dowMap[$nrDiaSemana];
      $arrRecordset[$dtAtual]["dia"] = "[$txtDiaSemana] " . date("d/m/y", strtotime($dtAtual));

      $vlrContaReceb = (isset($arrCR[$dtAtual])) ? $arrCR[$dtAtual]: 0;
      $arrRecordset[$dtAtual]["receber"] = $vlrContaReceb;

      $vlrContaPagar = (isset($arrCP[$dtAtual])) ? $arrCP[$dtAtual]: 0;
      $arrRecordset[$dtAtual]["pagar"] = $vlrContaPagar;

      $arrRecordset[$dtAtual]["saldo_inicial"] = ($dtAtual == $vDataSaldo) ? $vVlrSaldo: 0;
      $arrRecordset[$dtAtual]["saldo_dia"] = ($arrRecordset[$dtAtual]["saldo_inicial"] + $arrRecordset[$dtAtual]["receber"]) - $arrRecordset[$dtAtual]["pagar"];
      $arrRecordset[$dtAtual]["acumulado"] = $acumulado + $arrRecordset[$dtAtual]["saldo_dia"];

      $acumulado = $arrRecordset[$dtAtual]["acumulado"];
      $dtAtual   = date('Y-m-d', strtotime($dtAtual . ' +1 day'));
    }

    $arrRet["recordset"] = $arrRecordset;
    return $arrRet;
  }
}
