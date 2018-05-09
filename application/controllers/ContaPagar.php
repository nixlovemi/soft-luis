<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContaPagar extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["ContaPagarIndex_error_msg"]) ? $userData["ContaPagarIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('ContaPagarIndex_error_msg');
    }

    $this->load->model('Tb_Cont_Pagar');
    $htmlContasPagar = $this->Tb_Cont_Pagar->getHtmlContasPagar();
    $data["htmlContasPagarTable"] = $htmlContasPagar;

    $this->template->load('template', 'ContaPagar/index', $data);
  }

  public function jsonHtmlContasPagar(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlContasPagar"] = "";

    // variaveis ============
    $vVctoIni    = $this->input->post('filterDtVctoIni');
    $vVctoFim    = $this->input->post('filterDtVctoFim');
    $vPgtoIni    = $this->input->post('filterDtPgtoIni');
    $vPgtoFim    = $this->input->post('filterDtPgtoFim');
    $vPagas      = $this->input->post('filterApenasPagas');
    // ======================

    // filtro
    $arrFilters = [];

    if($vVctoIni != ""){
      $arrFilters["vctoIni"] = acerta_data($vVctoIni);
    }

    if($vVctoFim != ""){
      $arrFilters["vctoFim"] = acerta_data($vVctoFim);
    }

    if($vPgtoIni != ""){
      $arrFilters["pgtoIni"] = acerta_data($vPgtoIni);
    }

    if($vPgtoFim != ""){
      $arrFilters["pgtoFim"] = acerta_data($vPgtoFim);
    }

    if($vPagas != ""){
      $arrFilters["apenasPagas"] = $vPagas;
    }
    // ======

    $this->load->model('Tb_Cont_Pagar');
    $htmlContasPagarTable = $this->Tb_Cont_Pagar->getHtmlContasPagar($arrFilters);
    $arrRet["htmlContasPagar"] = $htmlContasPagarTable;

    echo json_encode($arrRet);
  }

  public function jsonHtmlAddConta(){
    $data     = [];
    $htmlView = $this->load->view('ContaPagar/novo', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonAddContaPagar(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $vctpDtpagamento  = (strlen($this->input->post('ctpDtpagamento')) == 10) ? acerta_data($this->input->post('ctpDtpagamento')): null;
    $vctpDtvencimento = (strlen($this->input->post('ctpDtvencimento')) == 10) ? acerta_data($this->input->post('ctpDtvencimento')): null;
    $vctpObservacao   = ($this->input->post('ctpObservacao') != "") ? $this->input->post('ctpObservacao'): null;
    $vctpValor        = ($this->input->post('ctpValor') != "") ? acerta_moeda($this->input->post('ctpValor')): null;
    $vctpValorPago    = ($this->input->post('ctpValorPago') != "") ? acerta_moeda($this->input->post('ctpValorPago')): null;
    $vctpFornecedor   = ($this->input->post('ctpFornecedor') != "") ? $this->input->post('ctpFornecedor'): null;
    // ======================

    $ContaPagar = [];
    $ContaPagar["ctp_dtvencimento"] = $vctpDtvencimento;
    $ContaPagar["ctp_valor"]        = $vctpValor;
    $ContaPagar["ctp_dtpagamento"]  = $vctpDtpagamento;
    $ContaPagar["ctp_valor_pago"]   = $vctpValorPago;
    $ContaPagar["ctp_obs"]          = $vctpObservacao;
    $ContaPagar["ctp_fornecedor"]   = $vctpFornecedor;

    $this->load->model('Tb_Cont_Pagar');
    $retInsert = $this->Tb_Cont_Pagar->insert($ContaPagar);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Pagamento. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Pagamento inserido com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }

  public function jsonDelContaPagar(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]                = false;
    $arrRet["msg"]                 = "";
    $arrRet["htmlContaPagarTable"] = "";

    // variaveis ============
    $vCtpId = $this->input->post('ctpId');
    // ======================

    $this->load->model('Tb_Cont_Pagar');

    // Conta Receber ========
    $retContaPagar = $this->Tb_Cont_Pagar->getContaPagar($vCtpId);
    if($retContaPagar["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar Pagamento. Msg: " . $retContaPagar["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $ContaPagar                 = $retContaPagar["arrContaPagarDados"];
      $ContaPagar["ctp_deletado"] = 1;
    }
    // ======================

    $retInsert = $this->Tb_Cont_Pagar->edit($ContaPagar);
    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao deletar Pagamento. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $this->load->model('Tb_Cont_Pagar');
      $htmlContasPagarTable = $this->Tb_Cont_Pagar->getHtmlContasPagar();
      $arrRet["htmlContaPagarTable"] = $htmlContasPagarTable;
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlEditPagamento(){
    $data   = [];
    $arrRet = [];
    $arrRet["html"] = "";
    $arrRet["msg"]  = "";
    $arrRet["erro"] = false;

    $ctpId = $this->input->post('ctpId');

    $this->load->model('Tb_Cont_Pagar');
    $retContPagar = $this->Tb_Cont_Pagar->getContaPagar($ctpId);
    if($retContPagar["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar o Pagamento. Msg: " . $retContPagar["msg"];
    } else {
      $ContPagar         = $retContPagar["arrContaPagarDados"];
      $data["ContPagar"] = $ContPagar;
      $data["editar"]    = true;
    }

    $htmlView       = $this->load->view('ContaPagar/novo', $data, true);
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonEditContaPagar(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $vCtpId           = $this->input->post('ctpId') > 0 ? $this->input->post('ctpId'): -1;
    $vctpDtpagamento  = (strlen($this->input->post('ctpDtpagamento')) == 10) ? acerta_data($this->input->post('ctpDtpagamento')): null;
    $vctpDtvencimento = (strlen($this->input->post('ctpDtvencimento')) == 10) ? acerta_data($this->input->post('ctpDtvencimento')): null;
    $vctpObservacao   = ($this->input->post('ctpObservacao') != "") ? $this->input->post('ctpObservacao'): null;
    $vctpValor        = ($this->input->post('ctpValor') != "") ? acerta_moeda($this->input->post('ctpValor')): null;
    $vctpValorPago    = ($this->input->post('ctpValorPago') != "") ? acerta_moeda($this->input->post('ctpValorPago')): null;
    $vctpFornecedor   = ($this->input->post('ctpFornecedor') != "") ? $this->input->post('ctpFornecedor'): null;
    // ======================

    $this->load->model('Tb_Cont_Pagar');
    $retContPagar = $this->Tb_Cont_Pagar->getContaPagar($vCtpId);
    if($retContPagar["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao encontrar Pagamento. Msg: " . $retContPagar["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $ContaPagar = $retContPagar["arrContaPagarDados"];
    }

    $ContaPagar["ctp_dtvencimento"] = $vctpDtvencimento;
    $ContaPagar["ctp_valor"]        = $vctpValor;
    $ContaPagar["ctp_dtpagamento"]  = $vctpDtpagamento;
    $ContaPagar["ctp_valor_pago"]   = $vctpValorPago;
    $ContaPagar["ctp_obs"]          = $vctpObservacao;
    $ContaPagar["ctp_fornecedor"]   = $vctpFornecedor;

    $this->db->where('ctp_id', $vCtpId);
    $retInsert = $this->db->update('Tb_Cont_Pagar', $ContaPagar);
    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao editar Pagamento. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Pagamento editado com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }
}
