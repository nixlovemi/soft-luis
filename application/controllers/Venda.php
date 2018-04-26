<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Venda extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function listaIncluidas(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["VendaListaIncluidas_error_msg"]) ? $userData["VendaListaIncluidas_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('VendaListaIncluidas_error_msg');
    }

    $this->load->model('Tb_Venda');
    $htmlVendaIncTable               = $this->Tb_Venda->getHtmlListIncluidas();
    $data["htmlVendaIncluidasTable"] = $htmlVendaIncTable;
    $data["errorMsg"]                = $errorMsg;

    $this->template->load('template', 'Venda/lstIncluidas', $data);
  }

  public function listaFinalizadas(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["VendaListaFinalizadas_error_msg"]) ? $userData["VendaListaFinalizadas_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('VendaListaFinalizadas_error_msg');
    }

    $this->load->model('Tb_Venda');
    $htmlVendaFinTable               = $this->Tb_Venda->getHtmlListFinalizadas();
    $data["htmlVendaFinalizadasTable"] = $htmlVendaFinTable;
    $data["errorMsg"]                = $errorMsg;

    $this->template->load('template', 'Venda/lstFinalizadas', $data);
  }

  public function incluir(){
    $data = [];

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();

    $data["arrVendaDados"] = array();
    $data["arrClientes"]   = $arrClientes;
    $data["arrVendedores"] = $arrVendedores;
    $this->template->load('template', 'Venda/novo', $data);
  }

  public function postIncluir(){
    $this->load->helper('utils');

    // variaveis ============
    $vVdaCliente  = $this->input->post('vdaCliente');
    $vVdaVendedor = $this->input->post('vdaVendedor');

    $vdaStatusInc = 1;
    $arrSession   = $this->session->get_userdata('logged_in');
    $loggedUsuId  = $arrSession["logged_in"]["usu_id"];
    // ======================

    $arrVendaDados = [];
    $arrVendaDados["vda_id"]     = null;
    $arrVendaDados["vda_data"]   = date("Y-m-d H:i:s");
    $arrVendaDados["vda_cli_id"] = $vVdaCliente;
    $arrVendaDados["vda_usu_id"] = $loggedUsuId;
    $arrVendaDados["vda_ven_id"] = $vVdaVendedor;
    $arrVendaDados["vda_status"] = $vdaStatusInc;

    $this->load->model('Tb_Venda');
    $retInsert = $this->Tb_Venda->insert($arrVendaDados);
    $data      = [];

    // info default
    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();

    $data["arrClientes"]   = $arrClientes;
    $data["arrVendedores"] = $arrVendedores;
    // ============

    if( $retInsert["erro"] ){
      $data["arrVendaDados"] = $arrVendaDados;
      $data["errorMsg"]      = isset($retInsert["msg"]) ? $retInsert["msg"]: "Erro ao inserir venda!";
      $data["okMsg"]         = "";

      $this->template->load('template', 'Venda/novo', $data);
    } else {
      // $data["arrVendaDados"]   = array();
      // $data["errorMsg"]        = "";
      // $data["okMsg"]           = isset($retInsert["msg"]) ? $retInsert["msg"]: "Venda inserida com sucesso!";

      $redirect = base_url() . "Venda/editar/" . $retInsert["vda_id"];
      header("location:$redirect");
    }
  }

  public function editar($vdaId){
    $data = [];

    $this->load->model('Tb_Venda');
    $retVenda = $this->Tb_Venda->getVenda($vdaId);
    $arrVenda = (!$retVenda["erro"]) ? $retVenda["arrVendaDados"]: array();

    $this->load->model('Tb_Cliente');
    $retClientes = $this->Tb_Cliente->getClientes();
    $arrClientes = (!$retClientes["erro"]) ? $retClientes["arrClientes"]: array();

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();

    $this->load->model('Tb_Produto');
    $retProdutos = $this->Tb_Produto->getProdutos();
    $arrProdutos = (!$retProdutos["erro"]) ? $retProdutos["arrProdutos"]: array();

    $this->load->model('Tb_Venda_Itens');
    $htmlVendaItens  = $this->Tb_Venda_Itens->getHtmlList($vdaId);
    $htmlVendaTotais = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);;

    $data["arrVenda"]        = $arrVenda;
    $data["arrClientes"]     = $arrClientes;
    $data["arrVendedores"]   = $arrVendedores;
    $data["arrProdutos"]     = $arrProdutos;
    $data["htmlVendaItens"]  = $htmlVendaItens;
    $data["htmlVendaTotais"] = $htmlVendaTotais;
    $this->template->load('template', 'Venda/editar', $data);
  }
}