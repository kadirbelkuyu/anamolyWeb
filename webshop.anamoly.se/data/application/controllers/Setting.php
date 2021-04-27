<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends Base_Controller {

    private $data;
	public function index()
	{
      if(_is_user_login()){

        $this->load->library("apicall");
        $form_params = array("user_id"=>_get_current_user_id());
        $res = $this->apicall->request("/rest/user/profile",$form_params);
        if($res->responce){
            $users = $res->data;
            if(!empty($users)){
                $this->data["field"] = $users->settings;
            }
        }

          $this->data["page_content"] = $this->load->view("webshop/setting",$this->data,true);
          $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
      }else{
          redirect("login");
      }
	}

}
