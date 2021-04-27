<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Appinstruction extends Base_Controller {
    private $data;
    public $lang = "english";

  	public function index()
  	{

            $this->data["page_content"] = $this->load->view("webshop/appinstruction",$this->data,true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);

  	}
    public function policy()
  	{

            $this->data["page_content"] = $this->load->view("webshop/policy",$this->data,true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
        
  	}
}
