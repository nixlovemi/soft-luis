<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["ClienteIndex_error_msg"]) ? $userData["ClienteIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('ClienteIndex_error_msg');
    }

    $this->load->model('Tb_Cliente');
    $htmlCliTable         = $this->Tb_Cliente->getHtmlList();
    $data["errorMsg"]     = $errorMsg;
    $data["htmlCliTable"] = $htmlCliTable;

    $this->template->load('template', 'Cliente/index', $data);
	}

  public function ver($cliId){
    // esquema para dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Cliente');
    $retCliente      = $this->Tb_Cliente->getCliente($cliId);
    $arrClienteDados = [];
    if(!$retCliente["erro"]){
      $arrClienteDados = $retCliente["arrClienteDados"];
    }

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data = [];
    $data["detalhes"]        = true;
    $data["arrClienteDados"] = $arrClienteDados;
    $data["arrEstados"]      = $arrEstados;
    $data["errorMsg"]        = "";
    $data["okMsg"]           = "";
    $data["queries"]         = $queries;

    $this->template->load('template', 'Cliente/novo', $data);
  }

  public function novoCliente(){
    $data = [];

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Cliente/novo', $data);
  }

  public function salvaNovo(){
    $this->load->helper('utils');

    // variaveis ============
    $vCliNome          = $this->input->post('cliNome');
    $vCliCpfCnpj       = $this->input->post('cliCpfCnpj');
    $vCliRgIe          = $this->input->post('cliRgIe');
    $vCliTelDdd        = $this->input->post('cliTelDdd');
    $vCliTelNumero     = $this->input->post('cliTelNumero');
    $vCliCelDdd        = $this->input->post('cliCelDdd');
    $vCliCelNumero     = $this->input->post('cliCelNumero');
    $vCliEndCep        = $this->input->post('cliEndCep');
    $vCliEndTpLgr      = $this->input->post('cliEndTpLgr');
    $vCliEndLogradouro = $this->input->post('cliEndLogradouro');
    $vCliEndNumero     = $this->input->post('cliEndNumero');
    $vCliEndBairro     = $this->input->post('cliEndBairro');
    $vCliEndCidade     = $this->input->post('cliEndCidade');
    $vCliEndEstado     = $this->input->post('cliEndEstado');
    $vCliObs           = $this->input->post('cliObservacao');
    $vCliAtivo         = $this->input->post('cliAtivo');
    // ======================

    $arrClienteDados = [];
    $arrClienteDados["cli_nome"]           = $vCliNome;
    $arrClienteDados["cli_cpf_cnpj"]       = $vCliCpfCnpj;
    $arrClienteDados["cli_rg_ie"]          = $vCliRgIe;
    $arrClienteDados["cli_tel_ddd"]        = $vCliTelDdd;
    $arrClienteDados["cli_tel_numero"]     = $vCliTelNumero;
    $arrClienteDados["cli_cel_ddd"]        = $vCliCelDdd;
    $arrClienteDados["cli_cel_numero"]     = $vCliCelNumero;
    $arrClienteDados["cli_end_cep"]        = $vCliEndCep;
    $arrClienteDados["cli_end_tp_lgr"]     = $vCliEndTpLgr;
    $arrClienteDados["cli_end_logradouro"] = $vCliEndLogradouro;
    $arrClienteDados["cli_end_numero"]     = $vCliEndNumero;
    $arrClienteDados["cli_end_bairro"]     = $vCliEndBairro;
    $arrClienteDados["cli_end_cidade"]     = $vCliEndCidade;
    $arrClienteDados["cli_end_estado"]     = $vCliEndEstado;
    $arrClienteDados["cli_observacao"]     = $vCliObs;
    $arrClienteDados["cli_ativo"]          = $vCliAtivo;

    $this->load->model('Tb_Cliente');
    $retInsert = $this->Tb_Cliente->insert($arrClienteDados);
    $data      = [];

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    if( $retInsert["erro"] ){
      $data["arrClienteDados"] = $arrClienteDados;
      $data["errorMsg"]        = isset($retInsert["msg"]) ? $retInsert["msg"]: "Erro ao inserir cliente!";
      $data["okMsg"]           = "";
    } else {
      $data["arrClienteDados"] = array();
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retInsert["msg"]) ? $retInsert["msg"]: "Cliente inserido com sucesso!";
    }

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Cliente/novo', $data);
  }

  public function editar($cliId){
    // esquema para dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Cliente');
    $retCliente      = $this->Tb_Cliente->getCliente($cliId);
    $arrClienteDados = [];
    if(!$retCliente["erro"]){
      $arrClienteDados = $retCliente["arrClienteDados"];
    }

    $data = [];
    $data["editar"]          = true;
    $data["arrClienteDados"] = $arrClienteDados;
    $data["errorMsg"]        = "";
    $data["okMsg"]           = "";
    $data["queries"]         = $queries;

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Cliente/novo', $data);
  }

  public function salvaEditar(){
    $this->load->helper('utils');
    $this->load->model('Tb_Cliente');

    // variaveis ============
    $vCliId            = $this->input->post('cliId');
    $vCliNome          = $this->input->post('cliNome');
    $vCliCpfCnpj       = $this->input->post('cliCpfCnpj');
    $vCliRgIe          = $this->input->post('cliRgIe');
    $vCliTelDdd        = $this->input->post('cliTelDdd');
    $vCliTelNumero     = $this->input->post('cliTelNumero');
    $vCliCelDdd        = $this->input->post('cliCelDdd');
    $vCliCelNumero     = $this->input->post('cliCelNumero');
    $vCliEndCep        = $this->input->post('cliEndCep');
    $vCliEndTpLgr      = $this->input->post('cliEndTpLgr');
    $vCliEndLogradouro = $this->input->post('cliEndLogradouro');
    $vCliEndNumero     = $this->input->post('cliEndNumero');
    $vCliEndBairro     = $this->input->post('cliEndBairro');
    $vCliEndCidade     = $this->input->post('cliEndCidade');
    $vCliEndEstado     = $this->input->post('cliEndEstado');
    $vCliObs           = $this->input->post('cliObservacao');
    $vCliAtivo         = $this->input->post('cliAtivo');
    // ======================

    $retCliente      = $this->Tb_Cliente->getCliente($vCliId);
    $arrClienteDados = [];
    if(!$retCliente["erro"]){
      $arrClienteDados = $retCliente["arrClienteDados"];
    }

    $arrClienteDados["cli_id"]             = $vCliId;
    $arrClienteDados["cli_nome"]           = $vCliNome;
    $arrClienteDados["cli_cpf_cnpj"]       = $vCliCpfCnpj;
    $arrClienteDados["cli_rg_ie"]          = $vCliRgIe;
    $arrClienteDados["cli_tel_ddd"]        = $vCliTelDdd;
    $arrClienteDados["cli_tel_numero"]     = $vCliTelNumero;
    $arrClienteDados["cli_cel_ddd"]        = $vCliCelDdd;
    $arrClienteDados["cli_cel_numero"]     = $vCliCelNumero;
    $arrClienteDados["cli_end_cep"]        = $vCliEndCep;
    $arrClienteDados["cli_end_tp_lgr"]     = $vCliEndTpLgr;
    $arrClienteDados["cli_end_logradouro"] = $vCliEndLogradouro;
    $arrClienteDados["cli_end_numero"]     = $vCliEndNumero;
    $arrClienteDados["cli_end_bairro"]     = $vCliEndBairro;
    $arrClienteDados["cli_end_cidade"]     = $vCliEndCidade;
    $arrClienteDados["cli_end_estado"]     = $vCliEndEstado;
    $arrClienteDados["cli_observacao"]     = $vCliObs;
    $arrClienteDados["cli_ativo"]          = $vCliAtivo;

    $retEdit = $this->Tb_Cliente->edit($arrClienteDados);
    $data    = [];
    if( $retEdit["erro"] ){
      $data["errorMsg"]        = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar cliente!";
      $data["okMsg"]           = "";
    } else {
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retEdit["msg"]) ? $retEdit["msg"]: "Cliente editado com sucesso!";
    }

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["editar"]          = true;
    $data["arrClienteDados"] = $arrClienteDados;
    $data["arrEstados"]      = $arrEstados;
    $this->template->load('template', 'Cliente/novo', $data);
  }

  public function deletar($cliId){
    $data     = [];
    $errorMsg = "";

    $this->load->model('Tb_Cliente');
    $retCliente      = $this->Tb_Cliente->getCliente($cliId);
    $arrClienteDados = [];
    if(!$retCliente["erro"]){
      $arrClienteDados = $retCliente["arrClienteDados"];
    } else {
      $errorMsg = "Erro ao buscar Cliente ID " . $cliId;
    }

    $arrClienteDados["cli_ativo"] = 0;

    $retEdit = $this->Tb_Cliente->edit($arrClienteDados);
    if( $retEdit["erro"] ){
      $errorMsg = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar cliente!";
    }

    $this->session->set_userdata('ClienteIndex_error_msg', $errorMsg);
    $redirect = base_url() . "Cliente";
    header("location:$redirect");
  }
}
