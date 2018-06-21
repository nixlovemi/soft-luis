<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendedor extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["VendedorIndex_error_msg"]) ? $userData["VendedorIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('VendedorIndex_error_msg');
    }

    $this->load->model('Tb_Vendedor');
    $htmlVenTable         = $this->Tb_Vendedor->getHtmlList();
    $data["errorMsg"]     = $errorMsg;
    $data["htmlVenTable"] = $htmlVenTable;

    $this->template->load('template', 'Vendedor/index', $data);
	}

  public function ver($venId){
    // esquema para dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Vendedor');
    $retVendedor      = $this->Tb_Vendedor->getVendedor($venId);
    $arrVendedorDados = [];
    if(!$retVendedor["erro"]){
      $arrVendedorDados = $retVendedor["arrVendedorDados"];
    }

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data = [];
    $data["detalhes"]         = true;
    $data["arrVendedorDados"] = $arrVendedorDados;
    $data["arrEstados"]       = $arrEstados;
    $data["errorMsg"]         = "";
    $data["okMsg"]            = "";
    $data["queries"]          = $queries;

    $this->template->load('template', 'Vendedor/novo', $data);
  }

  public function novoVendedor(){
    $data = [];

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Vendedor/novo', $data);
  }

  public function salvaNovo(){
    $this->load->helper('utils');

    // variaveis ============
    $vVenNome          = $this->input->post('venNome');
    $vVenCpfCnpj       = $this->input->post('venCpfCnpj');
    $vVenRgIe          = $this->input->post('venRgIe');
    $vVenTelDdd        = $this->input->post('venTelDdd');
    $vVenTelNumero     = $this->input->post('venTelNumero');
    $vVenCelDdd        = $this->input->post('venCelDdd');
    $vVenCelNumero     = $this->input->post('venCelNumero');
    $vVenEndCep        = $this->input->post('venEndCep');
    $vVenEndTpLgr      = $this->input->post('venEndTpLgr');
    $vVenEndLogradouro = $this->input->post('venEndLogradouro');
    $vVenEndNumero     = $this->input->post('venEndNumero');
    $vVenEndBairro     = $this->input->post('venEndBairro');
    $vVenEndCidade     = $this->input->post('venEndCidade');
    $vVenEndEstado     = $this->input->post('venEndEstado');
    $vVenObs           = $this->input->post('venObservacao');
    $vVenAtivo         = $this->input->post('venAtivo');
    $vVenComissao      = acerta_moeda($this->input->post('venComissao'));
    // ======================

    $arrVendedorDados = [];
    $arrVendedorDados["ven_nome"]           = $vVenNome;
    $arrVendedorDados["ven_cpf_cnpj"]       = $vVenCpfCnpj;
    $arrVendedorDados["ven_rg_ie"]          = $vVenRgIe;
    $arrVendedorDados["ven_tel_ddd"]        = $vVenTelDdd;
    $arrVendedorDados["ven_tel_numero"]     = $vVenTelNumero;
    $arrVendedorDados["ven_cel_ddd"]        = $vVenCelDdd;
    $arrVendedorDados["ven_cel_numero"]     = $vVenCelNumero;
    $arrVendedorDados["ven_end_cep"]        = $vVenEndCep;
    $arrVendedorDados["ven_end_tp_lgr"]     = $vVenEndTpLgr;
    $arrVendedorDados["ven_end_logradouro"] = $vVenEndLogradouro;
    $arrVendedorDados["ven_end_numero"]     = $vVenEndNumero;
    $arrVendedorDados["ven_end_bairro"]     = $vVenEndBairro;
    $arrVendedorDados["ven_end_cidade"]     = $vVenEndCidade;
    $arrVendedorDados["ven_end_estado"]     = $vVenEndEstado;
    $arrVendedorDados["ven_observacao"]     = $vVenObs;
    $arrVendedorDados["ven_ativo"]          = $vVenAtivo;
    $arrVendedorDados["ven_comissao"]       = $vVenComissao;

    $this->load->model('Tb_Vendedor');
    $retInsert = $this->Tb_Vendedor->insert($arrVendedorDados);
    $data      = [];

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    if( $retInsert["erro"] ){
      $data["arrVendedorDados"] = $arrVendedorDados;
      $data["errorMsg"]        = isset($retInsert["msg"]) ? $retInsert["msg"]: "Erro ao inserir vendedor!";
      $data["okMsg"]           = "";
    } else {
      $data["arrVendedorDados"] = array();
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retInsert["msg"]) ? $retInsert["msg"]: "Vendedor inserido com sucesso!";
    }

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Vendedor/novo', $data);
  }

  public function editar($venId){
    // esquema para dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Vendedor');
    $retVendedor      = $this->Tb_Vendedor->getVendedor($venId);
    $arrVendedorDados = [];
    if(!$retVendedor["erro"]){
      $arrVendedorDados = $retVendedor["arrVendedorDados"];
    }

    $data = [];
    $data["editar"]           = true;
    $data["arrVendedorDados"] = $arrVendedorDados;
    $data["errorMsg"]         = "";
    $data["okMsg"]            = "";
    $data["queries"]          = $queries;

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["arrEstados"] = $arrEstados;
    $this->template->load('template', 'Vendedor/novo', $data);
  }

  public function salvaEditar(){
    $this->load->helper('utils');
    $this->load->model('Tb_Vendedor');

    // variaveis ============
    $vVenId            = $this->input->post('venId');
    $vVenNome          = $this->input->post('venNome');
    $vVenCpfCnpj       = $this->input->post('venCpfCnpj');
    $vVenRgIe          = $this->input->post('venRgIe');
    $vVenTelDdd        = $this->input->post('venTelDdd');
    $vVenTelNumero     = $this->input->post('venTelNumero');
    $vVenCelDdd        = $this->input->post('venCelDdd');
    $vVenCelNumero     = $this->input->post('venCelNumero');
    $vVenEndCep        = $this->input->post('venEndCep');
    $vVenEndTpLgr      = $this->input->post('venEndTpLgr');
    $vVenEndLogradouro = $this->input->post('venEndLogradouro');
    $vVenEndNumero     = $this->input->post('venEndNumero');
    $vVenEndBairro     = $this->input->post('venEndBairro');
    $vVenEndCidade     = $this->input->post('venEndCidade');
    $vVenEndEstado     = $this->input->post('venEndEstado');
    $vVenObs           = $this->input->post('venObservacao');
    $vVenAtivo         = $this->input->post('venAtivo');
    $vVenComissao      = acerta_moeda($this->input->post('venComissao'));
    // ======================

    $retVendedor      = $this->Tb_Vendedor->getVendedor($vVenId);
    $arrVendedorDados = [];
    if(!$retVendedor["erro"]){
      $arrVendedorDados = $retVendedor["arrVendedorDados"];
    }

    $arrVendedorDados["ven_id"]             = $vVenId;
    $arrVendedorDados["ven_nome"]           = $vVenNome;
    $arrVendedorDados["ven_cpf_cnpj"]       = $vVenCpfCnpj;
    $arrVendedorDados["ven_rg_ie"]          = $vVenRgIe;
    $arrVendedorDados["ven_tel_ddd"]        = $vVenTelDdd;
    $arrVendedorDados["ven_tel_numero"]     = $vVenTelNumero;
    $arrVendedorDados["ven_cel_ddd"]        = $vVenCelDdd;
    $arrVendedorDados["ven_cel_numero"]     = $vVenCelNumero;
    $arrVendedorDados["ven_end_cep"]        = $vVenEndCep;
    $arrVendedorDados["ven_end_tp_lgr"]     = $vVenEndTpLgr;
    $arrVendedorDados["ven_end_logradouro"] = $vVenEndLogradouro;
    $arrVendedorDados["ven_end_numero"]     = $vVenEndNumero;
    $arrVendedorDados["ven_end_bairro"]     = $vVenEndBairro;
    $arrVendedorDados["ven_end_cidade"]     = $vVenEndCidade;
    $arrVendedorDados["ven_end_estado"]     = $vVenEndEstado;
    $arrVendedorDados["ven_observacao"]     = $vVenObs;
    $arrVendedorDados["ven_ativo"]          = $vVenAtivo;
    $arrVendedorDados["ven_comissao"]       = $vVenComissao;

    $retEdit = $this->Tb_Vendedor->edit($arrVendedorDados);
    $data    = [];
    if( $retEdit["erro"] ){
      $data["errorMsg"]        = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar vendedor!";
      $data["okMsg"]           = "";
    } else {
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retEdit["msg"]) ? $retEdit["msg"]: "Vendedor editado com sucesso!";
    }

    $this->load->model('Tb_Estado');
    $retEstados = $this->Tb_Estado->getEstados();
    $arrEstados = (!$retEstados["erro"]) ? $retEstados["arrEstados"]: array();

    $data["editar"]          = true;
    $data["arrVendedorDados"] = $arrVendedorDados;
    $data["arrEstados"]      = $arrEstados;
    $this->template->load('template', 'Vendedor/novo', $data);
  }

  public function deletar($venId){
    $data     = [];
    $errorMsg = "";

    $this->load->model('Tb_Vendedor');
    $retVendedor      = $this->Tb_Vendedor->getVendedor($venId);
    $arrVendedorDados = [];
    if(!$retVendedor["erro"]){
      $arrVendedorDados = $retVendedor["arrVendedorDados"];
    } else {
      $errorMsg = "Erro ao buscar Vendedor ID " . $venId;
    }

    $arrVendedorDados["ven_ativo"] = 0;

    $retEdit = $this->Tb_Vendedor->edit($arrVendedorDados);
    if( $retEdit["erro"] ){
      $errorMsg = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar vendedor!";
    }

    $this->session->set_userdata('VendedorIndex_error_msg', $errorMsg);
    $redirect = base_url() . "Vendedor";
    header("location:$redirect");
  }
}
