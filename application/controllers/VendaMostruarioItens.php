<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendaMostruarioItens extends MY_Controller {
  public function jsonAddProdutoMostruario(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Mostruario_Itens');

    $arrRet = [];
    $arrRet["erro"]         = true;
    $arrRet["msg"]          = "";
    $arrRet["htmlTbProd"]   = "";
    $arrRet["htmlTbTotais"] = "";

    // variaveis ============
    $vmiId       = $this->input->post('vdmId');

    $vmiProId    = $this->input->post('vmiProId');
    $vmiQtde     = $this->input->post('vmiQtde');
    $vmiValor    = $this->input->post('vmiValor');
    $vmiDesconto = $this->input->post('vmiDesconto');

    $ean         = $this->input->post('ean8');
    $eanQtde     = $this->input->post('eanQtde');
    // ======================

    $arrProdutoDados                 = [];
    $arrProdutoDados["vmi_vdm_id"]   = $vmiId;
    $arrProdutoDados["vmi_desconto"] = ($vmiDesconto != "") ? acerta_moeda($vmiDesconto): null;

    // verifica se veio ean8
    if( strlen($ean) == 13 ){
      $this->load->model('Tb_Produto');
      $retProdEan = $this->Tb_Produto->getProdutoByEan($ean);

      $retProduto = $retProdEan["Produto"];
      $vlrEan     = $retProdEan["valor"];

      if(!$retProduto["erro"]){
        $Produto = $retProduto["arrProdutoDados"];
        if( !empty($Produto) ){
          $arrProdutoDados["vmi_pro_id"]   = $Produto["pro_id"];
          $arrProdutoDados["vmi_qtde"]     = $eanQtde;
          $arrProdutoDados["vmi_valor"]    = $vlrEan;
        }
      }
    } else {
      $arrProdutoDados["vmi_pro_id"]   = $vmiProId;
      $arrProdutoDados["vmi_qtde"]     = $vmiQtde;
      $arrProdutoDados["vmi_valor"]    = ($vmiValor != "") ? acerta_moeda($vmiValor): null;
    }

    $retAdd = $this->Tb_Venda_Mostruario_Itens->insert($arrProdutoDados);
    if( $retAdd["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retAdd["msg"];
    } else {
      $arrRet["erro"]         = false;
      $arrRet["htmlTbProd"]   = $this->Tb_Venda_Mostruario_Itens->getHtmlList($vmiId);
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Mostruario_Itens->getHtmlTotVendaMostru($vmiId);
    }

    echo json_encode($arrRet);
  }

  public function jsonHtmlEditVendaItemMostru(){
    $data  = [];
    $vmiId = $this->input->post('vmiId');

    $this->load->model('Tb_Venda_Mostruario_Itens');
    $retVendaMostruItem = $this->Tb_Venda_Mostruario_Itens->getVendaItemMostru($vmiId);
    if(!$retVendaMostruItem["erro"]){
      $VendaMostruarioItem = $retVendaMostruItem["VendaMostruarioItem"];
    } else {
      $VendaMostruarioItem = [];
    }

    $data["VendaMostruarioItem"] = $VendaMostruarioItem;
    $htmlView = $this->load->view('VendaMostruarioItens/editar', $data, true);

    $arrRet = [];
    $arrRet["html"] = $htmlView;
    echo json_encode($arrRet);
  }

  public function jsonEditProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Mostruario_Itens');

    $arrRet = [];
    $arrRet["erro"]            = true;
    $arrRet["msg"]             = "";
    $arrRet["htmlTbProd"]      = "";
    $arrRet["htmlTbTotais"]    = "";

    // variaveis ============
    $vmiId       = $this->input->post('editVmiId');
    $vmiQtde     = $this->input->post('editVmiQtde');
    $vmiValor    = $this->input->post('editVmiValor');
    $vmiDesconto = $this->input->post('editVmiDesconto');
    // ======================

    // recupera venda item
    $VendaMostruarioItem = [];
    $retVendaMostruItem = $this->Tb_Venda_Mostruario_Itens->getVendaItemMostru($vmiId);
    if($retVendaMostruItem["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao editar item. Msg: " . $retVendaMostruItem["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $VendaMostruarioItem = $retVendaMostruItem["VendaMostruarioItem"];
    }
    // ===================

    $VendaMostruarioItem["vmi_qtde"]     = $vmiQtde;
    $VendaMostruarioItem["vmi_valor"]    = ($vmiValor != "") ? acerta_moeda($vmiValor): null;
    $VendaMostruarioItem["vmi_desconto"] = ($vmiDesconto != "") ? acerta_moeda($vmiDesconto): null;

    $retAdd = $this->Tb_Venda_Mostruario_Itens->edit($VendaMostruarioItem);
    if( $retAdd["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retAdd["msg"];
    } else {
      $vmiId = $VendaMostruarioItem["vmi_vdm_id"];

      $arrRet["erro"]         = false;
      $arrRet["htmlTbProd"]   = $this->Tb_Venda_Mostruario_Itens->getHtmlList($vmiId);
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Mostruario_Itens->getHtmlTotVendaMostru($vmiId);
    }

    echo json_encode($arrRet);
  }

  public function jsonRemoveProduto(){
    $this->load->helper('utils');
    $this->load->model('Tb_Venda_Mostruario_Itens');

    $arrRet = [];
    $arrRet["erro"]         = true;
    $arrRet["msg"]          = "";
    $arrRet["htmlTbProd"]   = "";
    $arrRet["htmlTbTotais"] = "";

    // variaveis ============
    $vmiId = $this->input->post('vmiId');
    // ======================

    $retVendaMostruItem  = $this->Tb_Venda_Mostruario_Itens->getVendaItemMostru($vmiId);
    $VendaMostruarioItem = isset($retVendaMostruItem["VendaMostruarioItem"]) ? $retVendaMostruItem["VendaMostruarioItem"]: array();

    $retDelete = $this->Tb_Venda_Mostruario_Itens->delete($vmiId);
    if($retDelete["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao remover item do mostruário. Msg: " . $retDelete["msg"];
    } else {
      $arrRet["erro"] = false;
      $arrRet["msg"]  = $retDelete["msg"];

      $vdmId = isset($VendaMostruarioItem["vmi_vdm_id"]) ? $VendaMostruarioItem["vmi_vdm_id"]: 0;
      $htmlTbProd = $this->Tb_Venda_Mostruario_Itens->getHtmlList($vdmId);
      $arrRet["htmlTbProd"] = $htmlTbProd;
      $arrRet["htmlTbTotais"] = $this->Tb_Venda_Mostruario_Itens->getHtmlTotVendaMostru($vdmId);
    }

    echo json_encode($arrRet);
  }
}
