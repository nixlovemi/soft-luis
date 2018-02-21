<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends CI_Controller {
  public function __construct(){
    CI_Controller::__construct();
  }

	public function index()
	{
    $sessionData = $this->session->get_userdata('logged_in');
    $loggedData  = (isset($sessionData["logged_in"])) ? $sessionData["logged_in"]: array();

    if( count($loggedData) <= 0 ){
      $redirect = base_url() . "Login";
      header("location:$redirect");
      return;
    }

    $data = [];
    $data["username"] = $loggedData["usu_nome"];

		$this->load->view('Start/index', $data);
	}
}
