<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendaItens extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function jsonAddProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Itens');

    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["htmlTbProd"]      = "";
    $arrRet["htmlContasVenda"] = "";

    // variaveis ============
    $vdaId       = $this->input->post('vdaId');

    $vdiProId    = $this->input->post('vdiProId');
    $vdiQtde     = $this->input->post('vdiQtde');
    $vdiValor    = $this->input->post('vdiValor');
    $vdiDesconto = $this->input->post('vdiDesconto');

    $ean         = $this->input->post('ean8');
    $eanQtde     = $this->input->post('eanQtde');
    // ======================

    $arrVendaItensDados              = [];
    $arrProdutoDados["vdi_vda_id"]   = $vdaId;
    $arrProdutoDados["vdi_desconto"] = ($vdiDesconto != "") ? acerta_moeda($vdiDesconto): null;

    // verifica se veio ean8
    if( strlen($ean) == 13 ){
      $this->load->model('Tb_Produto');
      $retProdEan = $this->Tb_Produto->getProdutoByEan($ean);

      $retProduto = $retProdEan["Produto"];
      $vlrEan     = $retProdEan["valor"];

      if(!$retProduto["erro"]){
        $Produto = $retProduto["arrProdutoDados"];
        if( !empty($Produto) ){
          $arrProdutoDados["vdi_pro_id"]   = $Produto["pro_id"];
          $arrProdutoDados["vdi_qtde"]     = $eanQtde;
          $arrProdutoDados["vdi_valor"]    = $vlrEan;
        }
      }
    } else {
      $arrProdutoDados["vdi_pro_id"]   = $vdiProId;
      $arrProdutoDados["vdi_qtde"]     = $vdiQtde;
      $arrProdutoDados["vdi_valor"]    = ($vdiValor != "") ? acerta_moeda($vdiValor): null;
    }

    $retAdd = $this->Tb_Venda_Itens->insert($arrProdutoDados);
    if( $retAdd["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retAdd["msg"];
    } else {
      $arrRet["erro"]         = false;
      $arrRet["htmlTbProd"]   = $this->Tb_Venda_Itens->getHtmlList($vdaId);
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);

      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vdaId);
      $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vdaId);
      $arrRet["htmlContasVenda"] = $htmlContasVenda . "<br />" . $htmlTotalContasVenda;
    }

    echo json_encode($arrRet);
  }

  public function jsonRemoveProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Itens');

    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["htmlTbProd"]      = "";
    $arrRet["htmlContasVenda"] = "";

    // variaveis ============
    $vdiId = $this->input->post('vdiId');
    // ======================

    $retVendaItem = $this->Tb_Venda_Itens->getVendaItem($vdiId);
    $VendaItem    = isset($retVendaItem["arrVendaItemDados"]) ? $retVendaItem["arrVendaItemDados"]: array();

    $retDelete = $this->Tb_Venda_Itens->delete($vdiId);
    if($retDelete["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao remover item da venda. Msg: " . $retDelete["msg"];
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = $retDelete["msg"];

      $vdaId = isset($VendaItem["vdi_vda_id"]) ? $VendaItem["vdi_vda_id"]: 0;
      $htmlTbProd = $this->Tb_Venda_Itens->getHtmlList($vdaId);
      $arrRet["htmlTbProd"] = $htmlTbProd;
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);

      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vdaId);
      $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vdaId);
      $arrRet["htmlContasVenda"] = $htmlContasVenda . "<br />" . $htmlTotalContasVenda;
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlEditVendaItem(){
    $data  = [];
    $vdiId = $this->input->post('vdiId');

    $this->load->model('Tb_Venda_Itens');
    $retVendaItem = $this->Tb_Venda_Itens->getVendaItem($vdiId);
    if(!$retVendaItem["erro"]){
      $VendaItem = $retVendaItem["arrVendaItemDados"];
    } else {
      $VendaItem = [];
    }

    $data["VendaItem"] = $VendaItem;
    $htmlView          = $this->load->view('VendaItens/editar', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonEditProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Itens');

    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["htmlTbProd"]      = "";
    $arrRet["htmlContasVenda"] = "";
    $arrRet["htmlTbTotais"]    = "";

    // variaveis ============
    $vdiId       = $this->input->post('editVdiId');
    $vdiQtde     = $this->input->post('editVdiQtde');
    $vdiValor    = $this->input->post('editVdiValor');
    $vdiDesconto = $this->input->post('editVdiDesconto');
    // ======================

    // recupera venda item
    $VendaItem    = [];
    $retVendaItem = $this->Tb_Venda_Itens->getVendaItem($vdiId);
    if($retVendaItem["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao editar item. Msg: " . $retVendaItem["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $VendaItem = $retVendaItem["arrVendaItemDados"];
    }
    // ===================

    $VendaItem["vdi_qtde"]     = $vdiQtde;
    $VendaItem["vdi_valor"]    = ($vdiValor != "") ? acerta_moeda($vdiValor): null;
    $VendaItem["vdi_desconto"] = ($vdiDesconto != "") ? acerta_moeda($vdiDesconto): null;

    $retAdd = $this->Tb_Venda_Itens->edit($VendaItem);
    if( $retAdd["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retAdd["msg"];
    } else {
      $vdaId = $VendaItem["vdi_vda_id"];

      $arrRet["erro"]         = false;
      $arrRet["htmlTbProd"]   = $this->Tb_Venda_Itens->getHtmlList($vdaId);
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);

      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vdaId);
      $htmlTotalContasVenda = $this->Tb_Cont_Receber->getHtmlTotaisContasVenda($vdaId);
      $arrRet["htmlContasVenda"] = $htmlContasVenda . "<br />" . $htmlTotalContasVenda;
    }

    echo json_encode($arrRet);
  }
}
