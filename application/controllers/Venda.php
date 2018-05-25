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
    $htmlVendaTotais = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);

    $this->load->model('Tb_Cont_Receber');
    $htmlContasVenda      = $this->Tb_Cont_Receber->getHtmlContasVenda($vdaId);
    $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vdaId);

    $data["editar"]               = true;
    $data["arrVenda"]             = $arrVenda;
    $data["arrClientes"]          = $arrClientes;
    $data["arrVendedores"]        = $arrVendedores;
    $data["arrProdutos"]          = $arrProdutos;
    $data["htmlVendaItens"]       = $htmlVendaItens;
    $data["htmlVendaTotais"]      = $htmlVendaTotais;
    $data["htmlContasVenda"]      = $htmlContasVenda;
    $data["htmlTotalContasVenda"] = $htmlTotalContasVenda;
    $this->template->load('template', 'Venda/editar', $data);
  }

  public function ver($vdaId){
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
    $htmlVendaItens  = $this->Tb_Venda_Itens->getHtmlList($vdaId, false);
    $htmlVendaTotais = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);

    $this->load->model('Tb_Cont_Receber');
    $htmlContasVenda      = $this->Tb_Cont_Receber->getHtmlContasVenda($vdaId);
    $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vdaId);

    $data["arrVenda"]             = $arrVenda;
    $data["arrClientes"]          = $arrClientes;
    $data["arrVendedores"]        = $arrVendedores;
    $data["arrProdutos"]          = $arrProdutos;
    $data["htmlVendaItens"]       = $htmlVendaItens;
    $data["htmlVendaTotais"]      = $htmlVendaTotais;
    $data["htmlContasVenda"]      = $htmlContasVenda;
    $data["htmlTotalContasVenda"] = $htmlTotalContasVenda;
    $data["editar"]               = false;
    $this->template->load('template', 'Venda/editar', $data);
  }

  public function deletar($vdaId){
    $data     = [];
    $errorMsg = "";

    $this->load->model('Tb_Venda');
    $retEdit = $this->Tb_Venda->cancelarVenda($vdaId);
    if( $retEdit["erro"] ){
      $errorMsg = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao cencelar venda!";
    }

    $this->session->set_userdata('VendaIndex_error_msg', $errorMsg);
    $redirect = base_url() . "Venda/listaIncluidas";
    header("location:$redirect");
  }

  public function indexMostruario(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["MostruarioIndex_error_msg"]) ? $userData["MostruarioIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('MostruarioIndex_error_msg');
    }

    $this->load->model('Tb_Venda_Mostruario');
    $htmlMostruarioTable         = $this->Tb_Venda_Mostruario->getHtmlList();
    $data["htmlMostruarioTable"] = $htmlMostruarioTable;
    $data["errorMsg"]            = $errorMsg;

    $this->template->load('template', 'Venda/mostruario', $data);
  }

  public function jsonHtmlNovoMostruario(){
    $dados          = [];
    $retArr         = [];
    $retArr["html"] = "";

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();

    $dados["arrVendedores"] = $arrVendedores;
    $dados["ehJson"]        = true;

    $htmlView = $this->load->view("Venda/novoMostruario", $dados, true);
    $retArr["html"] = $htmlView;
    echo json_encode($retArr);
  }

  public function postIncluirMostruario(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]   = true;
    $arrRet["msg"]    = "";
    $arrRet["vdm_id"] = "";

    // variaveis ============
    $vVdmVendedor  = $this->input->post('vdmVendedor');
    $vVdmDtEntrega = $this->input->post('vdmDtEntrega');
    // ======================

    $VendaMostruario = [];
    $VendaMostruario["vdm_ven_id"]    = $vVdmVendedor;
    $VendaMostruario["vdm_dtentrega"] = (strlen($vVdmDtEntrega) == 10) ? acerta_data($vVdmDtEntrega): "";

    $this->load->model('Tb_Venda_Mostruario');
    $retInsert = $this->Tb_Venda_Mostruario->insert($VendaMostruario);

    if($retInsert["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $arrRet["erro"]   = false;
      $arrRet["msg"]    = "Mostruário inserido com sucesso!";
      $arrRet["vdm_id"] = $retInsert["vdm_id"];

      echo json_encode($arrRet);
      return;
    }
  }

  public function editarMostruario($vdmId){
    $data = [];

    $this->load->model('Tb_Produto');
    $retProdutos = $this->Tb_Produto->getProdutos();
    $arrProdutos = (!$retProdutos["erro"]) ? $retProdutos["arrProdutos"]: array();

    $this->load->model('Tb_Venda_Mostruario');
    $retMostruario = $this->Tb_Venda_Mostruario->getMostruario($vdmId);
    $Mostruario    = (!$retMostruario["erro"]) ? $retMostruario["arrVendaMostruarioDados"]: array();

    $this->load->model('Tb_Vendedor');
    $retVendedores = $this->Tb_Vendedor->getVendedores();
    $arrVendedores = (!$retVendedores["erro"]) ? $retVendedores["arrVendedores"]: array();

    $this->load->model('Tb_Venda_Mostruario_Itens');
    $htmlVendaMostruItens  = $this->Tb_Venda_Mostruario_Itens->getHtmlList($vdmId);
    $htmlVendaMostruTotais = $this->Tb_Venda_Mostruario_Itens->getHtmlTotVendaMostru($vdmId);

    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["VendaMostruario_error_msg"]) ? $userData["VendaMostruario_error_msg"]: "";
    $this->session->set_userdata('VendaMostruario_error_msg', "");

    $data["errorMsg"]              = $errorMsg;
    $data["editar"]                = true;
    $data["Mostruario"]            = $Mostruario;
    $data["arrProdutos"]           = $arrProdutos;
    $data["arrVendedores"]         = $arrVendedores;
    $data["htmlVendaMostruItens"]  = $htmlVendaMostruItens;
    $data["htmlVendaMostruTotais"] = $htmlVendaMostruTotais;
    $this->template->load('template', 'Venda/editarMostruario', $data);
  }

  public function acertoMostruario($vdmId){
    $data = [];

    $this->load->model('Tb_Venda_Mostruario');
    $retMostruario = $this->Tb_Venda_Mostruario->getMostruario($vdmId);
    $Mostruario    = (!$retMostruario["erro"]) ? $retMostruario["arrVendaMostruarioDados"]: array();

    $this->load->model('Tb_Venda_Mostruario_Itens');
    $htmlVendaMostruItens  = $this->Tb_Venda_Mostruario_Itens->getHtmlList($vdmId, false);

    $retProdutos = $this->Tb_Venda_Mostruario_Itens->getProdutosVdm($vdmId);
    $arrProdutos = (!$retProdutos["erro"]) ? $retProdutos["arrProdutosVdm"]: array();

    $this->load->model('Tb_Venda_Mostruario_Itens_Ret');
    $retHtmlItensConfVendido = $this->Tb_Venda_Mostruario_Itens_Ret->getHtmlItensConfVendido($vdmId);
    $htmlVendidos   = $retHtmlItensConfVendido["htmlVendido"];
    $htmlConferidos = $retHtmlItensConfVendido["htmlConferido"];

    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["VendaMostruarioAcerto_error_msg"]) ? $userData["VendaMostruarioAcerto_error_msg"]: "";
    $this->session->set_userdata('VendaMostruarioAcerto_error_msg', "");

    $data["errorMsg"]             = $errorMsg;
    $data["editar"]               = true;
    $data["Mostruario"]           = $Mostruario;
    $data["arrProdutos"]          = $arrProdutos;
    $data["htmlVendaMostruItens"] = $htmlVendaMostruItens;
    $data["htmlVendidos"]         = $htmlVendidos;
    $data["htmlConferidos"]       = $htmlConferidos;
    $this->template->load('template', 'Venda/acertoMostruario', $data);
  }

  public function finalizaAcerto($vdmId){
    $erro = false;
    $msg  = "";

    $this->load->model('Tb_Venda_Mostruario_Itens_Ret');
    $retArrConfVend = $this->Tb_Venda_Mostruario_Itens_Ret->getArrItensConfVendido($vdmId);
    $arrProdVendido = (!$retArrConfVend["erro"]) ? $retArrConfVend["arrItens"]["vendidos"]: array();

    if(count($arrProdVendido) <= 0){
      $erro = true;
      $msg  = "Acerto sem nenhum item!";
    } else {
      $this->load->model('Tb_Venda_Mostruario');
      $retFinaliza = $this->Tb_Venda_Mostruario->finalizaAcerto($vdmId, $arrProdVendido);

      if($retFinaliza["erro"]){
        $this->session->set_userdata('VendaMostruarioAcerto_error_msg', $retFinaliza["msg"]);
        $redirect = base_url() . "Venda/acertoMostruario/$vdmId";
        header("location:$redirect");
      } else {
        $vdaId = $retFinaliza["vda_id"];
        $this->editar($vdaId);
      }
    }
  }
}
