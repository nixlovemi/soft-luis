<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ContaReceber extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

  public function jsonAddContaVenda(){
    $this->load->helper('utils');

    $arrRet = [];
    $arrRet["erro"]            = false;
    $arrRet["msg"]             = "";
    $arrRet["htmlContasVenda"] = "";

    // variaveis ============
    $vCtrContaPaga    = $this->input->post('ctrContaPaga');
    $vCtrDtVencimento = $this->input->post('ctrDtVencimento');
    $vCtrValor        = $this->input->post('ctrValor');
    $vVdaId           = $this->input->post('vdaId');
    // ======================

    $this->load->model('Tb_Venda');
    $retVenda = $this->Tb_Venda->getVenda($vVdaId);
    if($retVenda["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao pesquisar Venda.";

      echo json_encode($arrRet);
      return;
    } else {
      $VendaDados = $retVenda["arrVendaDados"];
    }

    $vVcto  = ($vCtrDtVencimento != "") ? acerta_data($vCtrDtVencimento): "";
    $vValor = ($vCtrValor != "") ? acerta_moeda($vCtrValor): "";

    $ContaReceber = [];
    $ContaReceber["ctr_cli_id"]       = $VendaDados["vda_cli_id"];
    $ContaReceber["ctr_vda_id"]       = $VendaDados["vda_id"];
    $ContaReceber["ctr_dtvencimento"] = $vVcto;
    $ContaReceber["ctr_valor"]        = $vValor;
    if($vCtrContaPaga == "S"){
      $ContaReceber["ctr_dtpagamento"] = $vVcto;
      $ContaReceber["ctr_valor_pago"]  = $vValor;
    }

    $this->load->model('Tb_Cont_Receber');
    $retInsert = $this->Tb_Cont_Receber->insert($ContaReceber);

    if( $retInsert["erro"] ){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = "Erro ao inserir Parcela. Msg: " . $retInsert["msg"];

      echo json_encode($arrRet);
      return;
    } else {
      $this->load->model('Tb_Cont_Receber');
      $htmlContasVenda = $this->Tb_Cont_Receber->getHtmlContasVenda($vVdaId);

      $arrRet["htmlContasVenda"] = $htmlContasVenda;
    }

    echo json_encode($arrRet);
  }
}
