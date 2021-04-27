<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Base_Controller {
    private $data;
    /*
  	public function index($tab="")
  	{
        if(_is_user_login()){

          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id());
          $res = $this->apicall->request("/rest/products/tabs",$form_params);
          if($res->responce){
              $tabs = $res->data;
              $this->data["tabs"] = $tabs;
              if($tab == "" && !empty($tabs)){
                  $tab = $tabs[0]->tag_ref;
              }
          }
          if($tab != ""){
              $form_params["tab_ref"] = $tab;
              $form_params["webshop"] = "true";
              $res = $this->apicall->request("/rest/products/tabdata",$form_params);
              if(!empty($res) && $res->responce){
                  $products = $res->data;
                  $this->data["products"] = $products->products;
                  $this->data["banners"] = $products->banners;
              }
          }
  		      $this->data["page_content"] = $this->load->view("webshop/home",$this->data,true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
        }else{
            redirect("login");
        }
  	}
  	*/
  	public function index($tab="")
  	{
        if(_is_user_login()){

          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id());
    			$res = $this->apicall->request("/rest/home/list", $form_params);
    			if ($res->responce) {
    				$data = $res->data;
    				$this->data["categories"] = $data->categories;
    				$this->data["banners"] = $data->banners;
    				$this->session->set_userdata("is_featured",$data->is_featured);
    			}

  		    $this->data["page_content"] = $this->load->view("webshop/home",$this->data,true);
			    $this->data["page_script"] = $this->load->view("webshop/script/home",$this->data,true);
          $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
        }else{
            redirect("login");
        }
  	}
}
