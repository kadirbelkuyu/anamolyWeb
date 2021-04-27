<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_controller extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library("apicall");
        $this->loadsettings();
    }
    function loadsettings(){
      if($this->session->userdata("currency_symbol") != NULL && $this->session->userdata("currency_symbol") != ""){

      }else{

        $res = $this->apicall->request("/rest/settings/list",array());
        if($res->responce){
              $this->session->set_userdata((array)$res->data);
        }
      }
    }
}
