<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Start extends MY_Controller {
  public function __construct(){
    parent::__construct();
  }

	public function index()
	{
    $this->template->load('template', 'Start/index');
	}
}
