<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produto extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data = [];

    $this->load->model('Tb_Produto');
    $htmlProdTable         = $this->Tb_Produto->getHtmlList();
    $data["htmlProdTable"] = $htmlProdTable;

    $this->template->load('template', 'Produto/index', $data);
	}

  public function novoProduto(){
    $data = [];
    $this->template->load('template', 'Produto/novo', $data);
  }
}
