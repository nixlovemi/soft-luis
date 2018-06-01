<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Relatorio extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["RelatorioIndex_error_msg"]) ? $userData["RelatorioIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('RelatorioIndex_error_msg');
    }

		$this->template->load('template', 'Relatorio/index', $data);
	}

  public function abreRelVendas(){
    $data = [];

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();
    $data["arrClientes"] = $arrClientes;

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();
    $data["arrVendedores"] = $arrVendedores;

    $this->load->view('Relatorio/relVendas', $data);
  }

  public function postRelVendas(){
    $this->load->helper("utils");

    // variaveis ============
    $vdaDataIni  = $this->input->post('vdaDataIni');
    $vdaDataFim  = $this->input->post('vdaDataFim');
    $vdaCliente  = $this->input->post('vdaCliente');
    $vdaVendedor = $this->input->post('vdaVendedor');
    // ======================

    $arrFiltros = [];
    $arrFiltros["vdaDataIni"]  = (isset($vdaDataIni) && strlen($vdaDataIni) == 10) ? acerta_data($vdaDataIni): "";
    $arrFiltros["vdaDataFim"]  = (isset($vdaDataFim) && strlen($vdaDataFim) == 10) ? acerta_data($vdaDataFim): "";
    $arrFiltros["vdaCliente"]  = (isset($vdaCliente) && is_numeric($vdaCliente)) ? $vdaCliente: "";
    $arrFiltros["vdaVendedor"] = (isset($vdaVendedor) && is_numeric($vdaVendedor)) ? $vdaVendedor: "";

    $this->load->model("Tb_Venda");
    $retRelVendas = $this->Tb_Venda->relVendas($arrFiltros);

    if($retRelVendas["erro"] == true){
      $this->load->helper("alerts");
      echo showWarning($retRelVendas["msg"]);
      return;
    } else {
      $data = [];
      $data["rsVendas"] = $retRelVendas["recordset"];

      $this->load->view('Relatorio/postRelVendas', $data);
    }
  }
}
