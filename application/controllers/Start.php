<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index(){
    $data = [];

    // verifica script de atualizacao
    $scriptPath = "";
    foreach (glob(APPPATH . "cache/script*.sql") as $arquivo) {
      $scriptPath = $arquivo;
    }
    // ==============================

    // pega arr info ================
    $this->load->model("M_Start");
    $retArrInfoStart = $this->M_Start->getArrInfoStart();
    $arrInfoStart    = (!$retArrInfoStart["erro"]) ? $retArrInfoStart["array"]: array();
    // ==============================

    $data["scriptPath"]   = $scriptPath;
    $data["arrInfoStart"] = $arrInfoStart;
    $this->template->load('template', 'Start/index', $data);
	}

  public function jsonHtmlUpdateBd(){
    $data           = [];
    $retArr         = [];
    $retArr["html"] = "";

    // variaveis ============
    $vScriptPath = $this->input->post('scriptPath');
    // ======================

    $data["scriptPath"] = $vScriptPath;
    $retArr["html"]     = $this->load->view('Start/updateBd', $data, true);
    echo json_encode($retArr);
  }

  public function jsonUpdateDbScript(){
    $retArr         = [];
    $retArr["erro"] = true;
    $retArr["msg"]  = "";

    // variaveis ============
    $vScriptPath = $this->input->post('scriptPath');
    // ======================

    $this->load->model("Database");
    $retScript = $this->Database->execScriptFile($vScriptPath);

    $retArr["erro"] = $retScript["erro"];
    $retArr["msg"]  = $retScript["msg"];

    if(!$retArr["erro"]){
      unlink($vScriptPath);
    }

    echo json_encode($retArr);
  }

  public function indexBkp(){
    $data     = [];
    $userData = $this->session->get_userdata();
    $errorMsg = isset($userData["indexBkp_error_msg"]) ? $userData["indexBkp_error_msg"]: "";

    if($errorMsg != ""){
      $this->load->helper('alerts');
      $errorMsg = showError($errorMsg);
      $this->session->unset_userdata('indexBkp_error_msg');
    }

    $data["errorMsg"] = $errorMsg;
    $this->template->load('template', 'Start/indexBkp', $data);
  }

  public function jsonIniciaBackup(){
    $arrRet = [];
    $arrRet["erro"] = false;
    $arrRet["msg"]  = "";
    $arrRet["html"] = "";

    $this->load->model("M_Start");
    $retBKP = $this->M_Start->sendBackup();

    if($retBKP["erro"]){
      $arrRet["erro"] = true;
      $arrRet["msg"]  = $retBKP["msg"];
    } else {
      $arrRet["erro"] = false;

      $this->load->helper("alerts");
      $arrRet["html"] = showSuccess($retBKP["msg"]);
    }

    echo json_encode($arrRet);
  }
}
