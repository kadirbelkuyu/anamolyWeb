<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Base_Controller {
    private $data;
  	public function index()
  	{
  	    $this->load->library("apicall");
        $form_params = array();
        $res = $this->apicall->request("/rest/settings/list",$form_params);
        if($res->responce){
            $settings = $res->data;
            foreach($settings as $key=>$setting){
                if($setting != ""){
                  $this->session->set_userdata($key,$setting);
                }
            }


        }
        if(isset($_POST["email"]) && isset($_POST["password"])){
            $this->load->library("apicall");
            $form_params = array("user_email"=>$this->input->post("email"),"user_password"=>$this->input->post("password"));
            $res = $this->apicall->request("/rest/user/login",$form_params);
            if($res->responce){
                if($res->data->user_id > 0){
                   $userdata = array("user_id"=>$res->data->user_id,
                  "user_type_id"=>$res->data->user_type_id,
                  "user_email"=>$res->data->user_email,
                  "user_fullname"=>$res->data->user_firstname." ".$res->data->user_lastname,
                  "user_company_name"=>$res->data->user_company_name,
                  "user_image"=>$res->data->user_image,
                  "addresses"=>$res->data->addresses
                );

                  $this->session->set_userdata($userdata);
                    redirect();
                    /*
                  $res = $this->apicall->request("/rest/settings/list",array());
                  if($res->responce){
                     $this->session->set_userdata($res->data);
                     
                  }
                    */
                }
            }else{
              if(isset($res->code) && $res->code == 108){
                $this->session->set_userdata("user_email",$res->data->user_email);
                redirect("login/varify");
              }else if(isset($res->code) && $res->code == 106){
                $this->session->set_userdata("req_queue",$res->data->req_queue);
                redirect("login/waitinglist");
              }else if(isset($res->code) && $res->code == 105){
                redirect("login/nodelivery");
              }else{
                $this->data["script"] = _toastmessage($res->message);
              }
            }
        }

        $this->data["page_content"] = $this->load->view("webshop/login",$this->data,true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
    }

    private function generateApiSession($user){
      $this->session->set_userdata(array("user_id"=>$res->data->user_id,"user_type_id"=>$res->data->user_type_id));
    }

    public function add()
  	{

        if(isset($_POST["email"]) && isset($_POST["password"])){
            $this->load->library("apicall");
            $form_params = array(
              "kvkNumber"=>$this->input->post("company_id"),
              "user_firstname"=>$this->input->post("first_name"),
              "user_lastname"=>$this->input->post("last_name"),
              "user_email"=>$this->input->post("email"),
              "user_password"=>$this->input->post("password"),
              "user_phone"=>$this->input->post("mobile_number"),
              "postal_code"=>$this->input->post("postal_code"),
              "house_no"=>$this->input->post("house_no"),
              "add_on_house_no"=>$this->input->post("adon_no"),
              "ios_token"=>"",
              "android_token"=>"",
              "is_company"=>($this->input->post("is_company") == "on") ? "true" : "false",
              "user_company_name"=>$this->input->post("company_name"),
              "user_company_id"=>$this->input->post("company_id")
            );
          if(isset($_POST["referred_by"])){
              $form_params["referred_by"] = $this->input->post("referred_by");
          }
          if(isset($_GET["referred_by"])){
              $form_params["referred_by"] = $this->input->get("referred_by");
          }
            $res = $this->apicall->request("/rest/user/register",$form_params);
            if($res->responce){
                $this->session->set_userdata("user_email",$res->data->user_email);
                redirect("login/varify");
            }else{

                $this->data["script"] = _toastmessage($res->message);

            }
        }

        $this->data["page_content"] = $this->load->view("webshop/register",$this->data,true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
  	}

    public function varify()
  	{
  	  if(isset($_POST["otp"])){
            $this->load->library("apicall");
            $form_params = array("otp"=>$this->input->post("otp"),"user_email"=>$this->session->userdata("user_email"));
            $res = $this->apicall->request("/rest/user/verifyemail",$form_params);
            if($res->responce){
                //$this->session->set_userdata($res->data);
                set_flash_alert($res->message);
                redirect("login");
            }else{
              if(isset($res->code) && $res->code == 106){
                $this->session->set_userdata("req_queue",$res->data->req_queue);
                redirect("login/waitinglist");
              }else if(isset($res->code) && $res->code == 105){
                redirect("login/nodelivery");
              }else {
                $this->data["script"] = _toastmessage($res->message);
              }

            }
        }
        $this->data["email"] = $this->session->userdata("user_email");
        $this->data["page_content"] = $this->load->view("webshop/varify",$this->data,true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
  	}
    public function resend()
  	{
            header('Content-Type: application/json');
            $this->load->library("apicall");
            $form_params = array("user_email"=>$this->session->userdata("user_email"));
            $res = $this->apicall->request("/rest/user/resendotp",$form_params);
            echo json_encode($res);
  	}
    function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }
    function forgotpassword(){
        if(isset($_POST["email"])){
            $this->load->library("apicall");
            $form_params = array("user_email"=>$this->input->post("email"));
            $res = $this->apicall->request("/rest/user/forgotpassword",$form_params);
            if($res->responce){
              $this->data["script"] = _toastmessage($res->message,"success");
            }else{
              $this->data["script"] = _toastmessage($res->message,"error");
            }
        }

        $this->data["page_content"] = $this->load->view("webshop/forgotpassword",$this->data,true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
    }
    function nodelivery(){
      $this->data["page_content"] = $this->load->view("webshop/nodelivery",$this->data,true);
      $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
    }
    function waitinglist(){
      $this->data["page_content"] = $this->load->view("webshop/waitinglist",$this->data,true);
      $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
    }
    function whencanstart(){
      $this->data["page_content"] = $this->load->view("webshop/whencanstart",$this->data,true);
      $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
    }
}
