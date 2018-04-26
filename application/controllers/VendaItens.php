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
    $arrRet["erro"]       = true;
    $arrRet["msg"]        = "";
    $arrRet["htmlTbProd"] = "";

    // variaveis ============
    $vdaId       = $this->input->post('vdaId');
    $vdiProId    = $this->input->post('vdiProId');
    $vdiQtde     = $this->input->post('vdiQtde');
    $vdiValor    = $this->input->post('vdiValor');
    $vdiDesconto = $this->input->post('vdiDesconto');
    // ======================

    $arrVendaItensDados = [];
    $arrProdutoDados["vdi_vda_id"]   = $vdaId;
    $arrProdutoDados["vdi_pro_id"]   = $vdiProId;
    $arrProdutoDados["vdi_qtde"]     = $vdiQtde;
    $arrProdutoDados["vdi_valor"]    = ($vdiValor != "") ? acerta_moeda($vdiValor): null;
    $arrProdutoDados["vdi_desconto"] = ($vdiDesconto != "") ? acerta_moeda($vdiDesconto): null;

    $retAdd = $this->Tb_Venda_Itens->insert($arrProdutoDados);
    if( $retAdd["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retAdd["msg"];
    } else {
      $arrRet["erro"]         = false;
      $arrRet["htmlTbProd"]   = $this->Tb_Venda_Itens->getHtmlList($vdaId);
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Itens->getHtmlTotVenda($vdaId);
    }

    echo json_encode($arrRet);
  }

  public function jsonRemoveProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Itens');

    $arrRet = [];
    $arrRet["erro"]       = true;
    $arrRet["msg"]        = "";
    $arrRet["htmlTbProd"] = "";

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
    }

    echo json_encode($arrRet);
  }
}
