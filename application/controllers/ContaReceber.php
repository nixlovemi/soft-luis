<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContaReceber extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["ContaReceberIndex_error_msg"]) ? $userData["ContaReceberIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('ContaReceberIndex_error_msg');
    }

    $this->load->model('Tb_Cont_Receber');
    $htmlContasReceber = $this->Tb_Cont_Receber->getHtmlContasReceber();
    $data["htmlContaRecebTable"] = $htmlContasReceber;

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();
    $data["arrClientes"] = $arrClientes;

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();
    $data["arrVendedores"] = $arrVendedores;

    $this->template->load('template', 'ContaReceber/index', $data);
  }

  public function jsonAddContaVenda(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlContasVenda"] = "";

    // variaveis ============
    $vCtrContaPaga    = $this->input->post('ctrContaPaga');
    $vCtrDtVencimento = $this->input->post('ctrDtVencimento');
    $vCtrValor        = $this->input->post('ctrValor');
    $vVdaId           = $this->input->post('vdaId');
    // ======================

    $this->load->model('Tb_Venda');
    $retVenda = $this->Tb_Venda->getVenda($vVdaId);
    if($retVenda["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao pesquisar Venda.";

      echo json_encode($arrRet);
      return;
    } else {
      $VendaDados = $retVenda["arrVendaDados"];
    }

    $vVcto  = ($vCtrDtVencimento != "") ? acerta_data($vCtrDtVencimento): "";
    $vValor = ($vCtrValor != "") ? acerta_moeda($vCtrValor): "";

    $ContaReceber = [];
    $ContaReceber["ctr_cli_id"]       = $VendaDados["vda_cli_id"];
    $ContaReceber["ctr_vda_id"]       = $VendaDados["vda_id"];
    $ContaReceber["ctr_dtvencimento"] = $vVcto;
    $ContaReceber["ctr_valor"]        = $vValor;
    if($vCtrContaPaga == "S"){
      $ContaReceber["ctr_dtpagamento"] = $vVcto;
      $ContaReceber["ctr_valor_pago"]  = $vValor;
    }

    $this->load->model('Tb_Cont_Receber');
    $retInsert = $this->Tb_Cont_Receber->insert($ContaReceber);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vVdaId);
      $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vVdaId);

      $arrRet["htmlContasVenda"] = $htmlContasVenda . "<br />" . $htmlTotalContasVenda;
    }

    echo json_encode($arrRet);
  }

  public function jsonDelContaVenda(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlContasVenda"] = "";

    // variaveis ============
    $vCtrId = $this->input->post('ctrId');
    // ======================

    $this->load->model('Tb_Cont_Receber');

    // Conta Receber ========
    $retContaReceb = $this->Tb_Cont_Receber->getContaReceber($vCtrId);
    if($retContaReceb["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar Parcela. Msg: " . $retContaReceb["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $ContaReceber = $retContaReceb["arrContaRecebDados"];
      $vVdaId       = $ContaReceber["ctr_vda_id"];
    }
    // ======================

    $retInsert = $this->Tb_Cont_Receber->delete($vCtrId);
    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao deletar Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vVdaId);
      $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vVdaId);

      $arrRet["htmlContasVenda"] = $htmlContasVenda . "<br />" . $htmlTotalContasVenda;
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlContasReceber(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlContasReceb"] = "";

    // variaveis ============
    $vVctoIni    = $this->input->post('filterDtVctoIni');
    $vVctoFim    = $this->input->post('filterDtVctoFim');
    $vPgtoIni    = $this->input->post('filterDtPgtoIni');
    $vPgtoFim    = $this->input->post('filterDtPgtoFim');
    $vClienteId  = $this->input->post('filterCliente');
    $vVendedorId = $this->input->post('filterVendedor');
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

    if($vClienteId != ""){
      $arrFilters["clienteId"] = $vClienteId;
    }

    if($vVendedorId != ""){
      $arrFilters["vendedorId"] = $vVendedorId;
    }

    if($vPagas != ""){
      $arrFilters["apenasPagas"] = $vPagas;
    }
    // ======

    $this->load->model('Tb_Cont_Receber');
    $htmlContasRecebTable = $this->Tb_Cont_Receber->getHtmlContasReceber($arrFilters);
    $arrRet["htmlContasReceb"] = $htmlContasRecebTable;

    echo json_encode($arrRet);
  }

  public function jsonDelContaReceber(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]                = false;
    $arrRet["msg"]                 = "";
    $arrRet["htmlContaRecebTable"] = "";

    // variaveis ============
    $vCtrId = $this->input->post('ctrId');
    // ======================

    $this->load->model('Tb_Cont_Receber');

    // Conta Receber ========
    $retContaReceb = $this->Tb_Cont_Receber->getContaReceber($vCtrId);
    if($retContaReceb["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar Parcela. Msg: " . $retContaReceb["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $ContaReceber                 = $retContaReceb["arrContaRecebDados"];
      $ContaReceber["ctr_deletado"] = 1;
    }
    // ======================

    $retInsert = $this->Tb_Cont_Receber->edit($ContaReceber);
    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao deletar Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $this->load->model('Tb_Cont_Receber');
      $htmlContasRecebTable = $this->Tb_Cont_Receber->getHtmlContasReceber();
      $arrRet["htmlContasReceb"] = $htmlContasRecebTable;
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlAddConta(){
    $data     = [];

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();
    $data["arrClientes"] = $arrClientes;

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();
    $data["arrVendedores"] = $arrVendedores;

    $htmlView = $this->load->view('ContaReceber/novo', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonAddContaReceb(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $vCtrCliente      = ($this->input->post('ctrCliente') > 0) ? $this->input->post('ctrCliente'): null;
    $vCtrDtpagamento  = (strlen($this->input->post('ctrDtpagamento')) == 10) ? acerta_data($this->input->post('ctrDtpagamento')): null;
    $vCtrDtvencimento = (strlen($this->input->post('ctrDtvencimento')) == 10) ? acerta_data($this->input->post('ctrDtvencimento')): null;
    $vCtrObservacao   = ($this->input->post('ctrObservacao') != "") ? $this->input->post('ctrObservacao'): null;
    $vCtrValor        = ($this->input->post('ctrValor') > 0) ? acerta_moeda($this->input->post('ctrValor')): null;
    $vCtrValorPago    = ($this->input->post('ctrValorPago') > 0) ? acerta_moeda($this->input->post('ctrValorPago')): null;
    $vCtrVendedor     = ($this->input->post('ctrVendedor') > 0) ? $this->input->post('ctrVendedor'): null;
    // ======================

    $ContaReceber = [];
    $ContaReceber["ctr_cli_id"]       = $vCtrCliente;
    $ContaReceber["ctr_ven_id"]       = $vCtrVendedor;
    $ContaReceber["ctr_dtvencimento"] = $vCtrDtvencimento;
    $ContaReceber["ctr_valor"]        = $vCtrValor;
    $ContaReceber["ctr_dtpagamento"]  = $vCtrDtpagamento;
    $ContaReceber["ctr_valor_pago"]   = $vCtrValorPago;
    $ContaReceber["ctr_obs"]          = $vCtrObservacao;

    $this->load->model('Tb_Cont_Receber');
    $retInsert = $this->Tb_Cont_Receber->insert($ContaReceber);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Recebimento inserido com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }

  public function jsonHtmlEditConta(){
    $data   = [];
    $arrRet = [];
    $arrRet["html"] = "";
    $arrRet["msg"]  = "";
    $arrRet["erro"] = false;

    $ctrId = $this->input->post('ctrId');

    $this->load->model('Tb_Cont_Receber');
    $retContReceb = $this->Tb_Cont_Receber->getContaReceber($ctrId);
    if($retContReceb["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao buscar Conta a Receber. Msg: " . $retContReceb["msg"];
    } else {
      $ContReceber         = $retContReceb["arrContaRecebDados"];
      $data["ContReceber"] = $ContReceber;
      $data["editar"]      = true;
    }

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();
    $data["arrClientes"] = $arrClientes;

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();
    $data["arrVendedores"] = $arrVendedores;

    $htmlView       = $this->load->view('ContaReceber/novo', $data, true);
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonEditContaReceb(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";

    // variaveis ============
    $vCtrId           = $this->input->post('ctrId') > 0 ? $this->input->post('ctrId'): -1;
    $vCtrCliente      = ($this->input->post('ctrCliente') > 0) ? $this->input->post('ctrCliente'): null;
    $vCtrDtpagamento  = (strlen($this->input->post('ctrDtpagamento')) == 10) ? acerta_data($this->input->post('ctrDtpagamento')): null;
    $vCtrDtvencimento = (strlen($this->input->post('ctrDtvencimento')) == 10) ? acerta_data($this->input->post('ctrDtvencimento')): null;
    $vCtrObservacao   = ($this->input->post('ctrObservacao') != "") ? $this->input->post('ctrObservacao'): null;
    $vCtrValor        = ($this->input->post('ctrValor') > 0) ? acerta_moeda($this->input->post('ctrValor')): null;
    $vCtrValorPago    = ($this->input->post('ctrValorPago') > 0) ? acerta_moeda($this->input->post('ctrValorPago')): null;
    $vCtrVendedor     = ($this->input->post('ctrVendedor') > 0) ? $this->input->post('ctrVendedor'): null;
    // ======================

    $this->load->model('Tb_Cont_Receber');
    $retContReceb = $this->Tb_Cont_Receber->getContaReceber($vCtrId);
    if($retContReceb["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao encontrar Parcela. Msg: " . $retContReceb["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $ContaReceber = $retContReceb["arrContaRecebDados"];
    }

    $ContaReceber["ctr_cli_id"]       = $vCtrCliente;
    $ContaReceber["ctr_ven_id"]       = $vCtrVendedor;
    $ContaReceber["ctr_dtvencimento"] = $vCtrDtvencimento;
    $ContaReceber["ctr_valor"]        = $vCtrValor;
    $ContaReceber["ctr_dtpagamento"]  = $vCtrDtpagamento;
    $ContaReceber["ctr_valor_pago"]   = $vCtrValorPago;
    $ContaReceber["ctr_obs"]          = $vCtrObservacao;

    $this->db->where('ctr_id', $vCtrId);
    $retInsert = $this->db->update('tb_cont_receber', $ContaReceber);
    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao editar Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = "Recebimento editado com sucesso.";

      echo json_encode($arrRet);
      return;
    }
  }
}
