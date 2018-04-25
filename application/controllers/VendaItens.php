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
      $arrRet["erro"]       = false;
      $arrRet["htmlTbProd"] = $this->Tb_Venda_Itens->getHtmlList($vdaId);
    }

    echo json_encode($arrRet);
  }
}
