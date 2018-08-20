<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["ProdutoIndex_error_msg"]) ? $userData["ProdutoIndex_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('ProdutoIndex_error_msg');
    }

    $this->load->model('Tb_Produto');
    $htmlProdTable         = $this->Tb_Produto->getHtmlList();
    $data["htmlProdTable"] = $htmlProdTable;
    $data["errorMsg"]      = $errorMsg;

    $this->template->load('template', 'Produto/index', $data);
	}

  public function novoProduto(){
    $data = [];
    $this->template->load('template', 'Produto/novo', $data);
  }

  public function salvaNovo(){
    $this->load->helper('utils');

    // variaveis ============
    $vProDescricao  = $this->input->post('proDescricao');
    $vProCodigo     = $this->input->post('proCodigo');
    $vProEan        = $this->input->post('proEan');
    $vProEstoque    = $this->input->post('proEstoque');
    $vProPrecCusto  = $this->input->post('proPrecCusto');
    $vProPrecVenda  = $this->input->post('proPrecVenda');
    $vProObservacao = $this->input->post('proObservacao');
    $vProAtivo      = $this->input->post('proAtivo');

    $imageExists = ($_FILES["pro_imagem"]["tmp_name"] != "") ? getimagesize($_FILES["pro_imagem"]["tmp_name"]): false;
    if($imageExists !== false){
      $fileName   = resizeImage($_FILES['pro_imagem']['tmp_name'], 640, 480);
      $contents   = urlImageToBinary($fileName);
      $vProImagem = $contents;
    } else {
      $vProImagem = "";
    }

    if($vProPrecCusto != ""){
      $vProPrecCusto = acerta_moeda($vProPrecCusto);
    }
    if($vProPrecVenda != ""){
      $vProPrecVenda = acerta_moeda($vProPrecVenda);
    }
    // ======================

    $arrProdutoDados = [];
    $arrProdutoDados["pro_descricao"]  = $vProDescricao;
    $arrProdutoDados["pro_codigo"]     = $vProCodigo;
    $arrProdutoDados["pro_ean"]        = $vProEan;
    $arrProdutoDados["pro_estoque"]    = $vProEstoque;
    $arrProdutoDados["pro_prec_custo"] = $vProPrecCusto;
    $arrProdutoDados["pro_prec_venda"] = $vProPrecVenda;
    $arrProdutoDados["pro_observacao"] = $vProObservacao;
    $arrProdutoDados["pro_ativo"]      = $vProAtivo;
    $arrProdutoDados["pro_imagem"]     = $vProImagem;

    $this->load->model('Tb_Produto');
    $retInsert = $this->Tb_Produto->insert($arrProdutoDados);
    $data      = [];

    if( $retInsert["erro"] ){
      $data["arrProdutoDados"] = $arrProdutoDados;
      $data["errorMsg"]        = isset($retInsert["msg"]) ? $retInsert["msg"]: "Erro ao inserir produto!";
      $data["okMsg"]           = "";
    } else {
      $data["arrProdutoDados"] = array();
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retInsert["msg"]) ? $retInsert["msg"]: "Produto inserido com sucesso!";
    }

    $this->template->load('template', 'Produto/novo', $data);
  }

  public function salvaEditar(){
    $this->load->helper('utils');
    $this->load->model('Tb_Produto');

    // variaveis ============
    $vProId         = $this->input->post('proId');
    $vProDescricao  = $this->input->post('proDescricao');
    $vProCodigo     = $this->input->post('proCodigo');
    $vProEan        = $this->input->post('proEan');
    $vProEstoque    = $this->input->post('proEstoque');
    $vProPrecCusto  = $this->input->post('proPrecCusto');
    $vProPrecVenda  = $this->input->post('proPrecVenda');
    $vProObservacao = $this->input->post('proObservacao');
    $vProAtivo      = $this->input->post('proAtivo');

    $imageExists = ($_FILES["pro_imagem"]["tmp_name"] != "") ? getimagesize($_FILES["pro_imagem"]["tmp_name"]): false;
    if($imageExists !== false){
      $fileName   = resizeImage($_FILES['pro_imagem']['tmp_name'], 640, 480);
      $contents   = urlImageToBinary($fileName);
      $vProImagem = $contents;
    } else {
      $vProImagem = "";
    }

    if($vProPrecCusto != ""){
      $vProPrecCusto = acerta_moeda($vProPrecCusto);
    }
    if($vProPrecVenda != ""){
      $vProPrecVenda = acerta_moeda($vProPrecVenda);
    }
    // ======================

    $retProduto      = $this->Tb_Produto->getProduto($vProId);
    $arrProdutoDados = [];
    if(!$retProduto["erro"]){
      $arrProdutoDados = $retProduto["arrProdutoDados"];
    }

    $arrProdutoDados["pro_id"]         = $vProId;
    $arrProdutoDados["pro_descricao"]  = $vProDescricao;
    $arrProdutoDados["pro_codigo"]     = $vProCodigo;
    $arrProdutoDados["pro_ean"]        = $vProEan;
    $arrProdutoDados["pro_estoque"]    = $vProEstoque;
    $arrProdutoDados["pro_prec_custo"] = $vProPrecCusto;
    $arrProdutoDados["pro_prec_venda"] = $vProPrecVenda;
    $arrProdutoDados["pro_observacao"] = $vProObservacao;
    $arrProdutoDados["pro_ativo"]      = $vProAtivo;
    $arrProdutoDados["pro_imagem"]     = $vProImagem;

    $retEdit = $this->Tb_Produto->edit($arrProdutoDados);
    $data    = [];
    if( $retEdit["erro"] ){
      $data["errorMsg"]        = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar produto!";
      $data["okMsg"]           = "";
    } else {
      $data["errorMsg"]        = "";
      $data["okMsg"]           = isset($retEdit["msg"]) ? $retEdit["msg"]: "Produto editado com sucesso!";
    }

    $retProduto              = $this->Tb_Produto->getProduto($vProId);
    $data["arrProdutoDados"] = (!$retProduto["erro"]) ? $retProduto["arrProdutoDados"]: $arrProdutoDados;
    $data["editar"]          = true;
    $this->template->load('template', 'Produto/novo', $data);
  }

  public function ver($proId){
    // esquema pro dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Produto');
    $retProduto      = $this->Tb_Produto->getProduto($proId);
    $arrProdutoDados = [];
    if(!$retProduto["erro"]){
      $arrProdutoDados = $retProduto["arrProdutoDados"];
    }

    $data = [];
    $data["detalhes"]        = true;
    $data["arrProdutoDados"] = $arrProdutoDados;
    $data["errorMsg"]        = "";
    $data["okMsg"]           = "";
    $data["queries"]         = $queries;

    $this->template->load('template', 'Produto/novo', $data);
  }

  public function editar($proId){
    // esquema pro dynatables
    $queries = http_build_query($_GET);

    $this->load->model('Tb_Produto');
    $retProduto      = $this->Tb_Produto->getProduto($proId);
    $arrProdutoDados = [];
    if(!$retProduto["erro"]){
      $arrProdutoDados = $retProduto["arrProdutoDados"];
    }

    $data = [];
    $data["editar"]          = true;
    $data["arrProdutoDados"] = $arrProdutoDados;
    $data["errorMsg"]        = "";
    $data["okMsg"]           = "";
    $data["queries"]         = $queries;

    $this->template->load('template', 'Produto/novo', $data);
  }

  public function deletar($proId){
    $data     = [];
    $errorMsg = "";

    $this->load->model('Tb_Produto');
    $retProduto      = $this->Tb_Produto->getProduto($proId);
    $arrProdutoDados = [];
    if(!$retProduto["erro"]){
      $arrProdutoDados = $retProduto["arrProdutoDados"];
    } else {
      $errorMsg = "Erro ao buscar Produto ID " . $proId;
    }

    $arrProdutoDados["pro_ativo"] = 0;

    $retEdit = $this->Tb_Produto->edit($arrProdutoDados);
    if( $retEdit["erro"] ){
      $errorMsg = isset($retEdit["msg"]) ? $retEdit["msg"]: "Erro ao editar produto!";
    }

    $this->session->set_userdata('ProdutoIndex_error_msg', $errorMsg);
    $redirect = base_url() . "Produto";
    header("location:$redirect");
  }

  public function jsonGetProduto($proId){
    $arrRet            = [];
    $arrRet["erro"]    = false;
    $arrRet["msg"]     = "";
    $arrRet["Produto"] = [];

    $this->load->model('Tb_Produto');
    $retGetProduto = $this->Tb_Produto->getProduto($proId);
    if($retGetProduto["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retGetProduto["msg"];
    } else {
      $arrRet["Produto"] = $retGetProduto["arrProdutoDados"];
    }

    echo json_encode($arrRet);
  }

  public function inventario(){
    $data     = [];
    $errorMsg = "";
    $this->load->helper("alerts");

    $this->load->model('Tb_Produto');
    $retProdutos = $this->Tb_Produto->getProdutos();
    if($retProdutos["erro"]){
      $arrProdutos = [];
      $errorMsg    = showWarning("Erro ao buscar produtos. Msg: " . $retProdutos["msg"]);
    } else {
      $arrProdutos = $retProdutos["arrProdutos"];
    }

    $data["errorMsg"] = $errorMsg;
    $data["arrProdutos"] = $arrProdutos;
    $this->template->load('template', 'Produto/inventario', $data);
  }

  public function postInventario(){
    $this->load->model('Tb_Produto');
    $this->load->helper("alerts");

    $data                = [];
    $data["errorMsg"]    = "";
    $data["arrProdutos"] = [];
    $arrProdutos         = [];

    foreach($_POST as $key => $value){
      $ehProd = strpos($key, "proEstoque__") !== false;
      if($ehProd){
        if(is_numeric($value)){
          $idProd = str_replace("proEstoque__", "", $key);
          $arrProdutos[] = array(
            "pro_id" => $idProd,
            "pro_estoque" => $value,
          );
        }
      }
    }

    $retInventario = $this->Tb_Produto->addInventario($arrProdutos);
    if($retInventario["erro"]){
      $data["errorMsg"] = showWarning("Erro ao gravar inventário: Msg: " . $retInventario["msg"]);
    } else {
      $data["errorMsg"] = showSuccess("Inventário gravado com sucesso!");
    }

    $retProdutos = $this->Tb_Produto->getProdutos();
    if(!$retProdutos["erro"]){
      $data["arrProdutos"] = $retProdutos["arrProdutos"];
    }

    $this->template->load('template', 'Produto/inventario', $data);
  }

  public function geraEtiqueta($proId){
    $data = [];

    $this->load->model('Tb_Produto');
    $retProduto      = $this->Tb_Produto->getProduto($proId);
    $arrProdutoDados = [];
    if(!$retProduto["erro"]){
      $Produto = $retProduto["arrProdutoDados"];
    }

    $data["Produto"] = $Produto;
    $this->load->view('Produto/etiqueta', $data);
  }

  public function jsonRemoveProImagem(){
    $this->load->model('Tb_Produto');
    $this->load->helper("alerts");

    $data         = [];
    $data["erro"] = false;
    $data["msg"]  = "";

    // variaveis ============
    $proId = $this->input->post('proId');
    // ======================

    $retRemove    = $this->Tb_Produto->removeImagem($proId);
    $data["erro"] = $retRemove["erro"];
    $data["msg"]  = $retRemove["msg"];

    echo json_encode($data);
  }
}
