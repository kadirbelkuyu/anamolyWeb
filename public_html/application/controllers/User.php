<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends Base_Controller
{

    private $data;
    public function changepassword()
    {
        $this->actionchangepassword();
        if (_is_user_login()) {
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view("webshop/changepassword", $this->
                data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }

    private function actionchangepassword()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');

        $this->form_validation->set_rules('old_password', _l("Old Password"),
            'trim|required');

        $this->form_validation->set_rules('new_password', _l("New Password"),
            'trim|required');
        $this->form_validation->set_rules('confirm_password', _l("Confirm Password"),
            'trim|required|matches[new_password]');

        $responce = array();
        if ($this->form_validation->run() == false) {

            if ($this->form_validation->error_string() != "") {
                _set_flash_message($this->form_validation->error_string(), 'error');
            }
        } else {
            $userid = _get_current_user_id();

            $this->load->library("apicall");
            $form_params = array(
                "user_id" => _get_current_user_id(),
                "c_password" => $post["old_password"],
                "n_password" => $post["new_password"],
                "r_password" => $post["confirm_password"]);

            $res = $this->apicall->request("/rest/user/changepassword", $form_params);

            if ($res->responce) {
                 $this->data["script"] = _toastmessage($res->message,'success');
                redirect();
            } else {
                $this->data["script"] = _toastmessage($res->message,'error');
            }
        }
    }

    public function myprofile()
    {
        if (_is_user_login()) {
            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id());
            $res = $this->apicall->request("/rest/user/profile", $form_params);
            if ($res->responce) {
                $users = $res->data;
                if (!empty($users)) {
                    $this->data["users"] = $users;
                }
            }

            $this->data["page_content"] = $this->load->view("webshop/myprofile", $this->data, true);
             $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }

    public function update_name()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "user_firstname"=>$this->input->post("user_firstname"),
            "user_lastname"=>$this->input->post("user_lastname"));

          $res = $this->apicall->request("/rest/user/update_name",$form_params);

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }

    public function update_phone()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "user_phone"=>$this->input->post("user_phone"));

          $res = $this->apicall->request("/rest/user/update_phone",$form_params);

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }

    public function update_address()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "postal_code"=>$this->input->post("postal_code"),
            "house_no"=>$this->input->post("house_no"),
            "add_on_house_no"=>$this->input->post("add_on_house_no"),
            "city"=>$this->input->post("city"),
            "street_name"=>$this->input->post("street_name"));

          $res = $this->apicall->request("/rest/address/update",$form_params);

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }

    public function update_setfamily()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "no_of_adults"=>$this->input->post("no_of_adults"),
            "no_of_child"=>$this->input->post("no_of_child"),
            "no_of_dogs"=>$this->input->post("no_of_dogs"),
            "no_of_cats"=>$this->input->post("no_of_cats"));

          $res = $this->apicall->request("/rest/user/setfamily",$form_params);

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }

    public function update_email()
    {
       if(_is_user_login()){
        if(isset($_POST["user_email"])){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "user_email"=>$this->input->post("user_email"));
          $res = $this->apicall->request("/rest/user/update_email",$form_params);
          if($res->responce){
              $this->session->set_userdata("new_user_email",$res->data->user_email);
          }
          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
    function varify_email_otp(){
        if(_is_user_login()){

                  if(isset($_POST["otp"])){
                        $this->load->library("apicall");
                        $form_params = array("otp"=>$this->input->post("otp"),"user_id"=>_get_current_user_id(),"user_email"=>$this->session->userdata("new_user_email"));
                        $res = $this->apicall->request("/rest/user/verify_update_email",$form_params);
                        if($res->responce){
                            $this->session->set_userdata($res->data);
                            $this->data["script"] = _toastmessage($res->message,"success");
                            $this->session->set_userdata("user_email",$this->session->userdata("new_user_email"));

                            redirect("user/myprofile");
                        }else{

                            $this->data["script"] = _toastmessage($res->message);

                        }
                    }
                    $this->data["email"] = $this->session->userdata("new_user_email");
                    $this->data["page_content"] = $this->load->view("webshop/varify",$this->data,true);
                    $this->load->view(BASE_WEB_SHOTP_TEMPLATE,$this->data);
        }
    }

    public function update_photo()
    {
       if(_is_user_login()){
        if(isset($_POST)){

                $file_data = _do_upload("fileUpload","./uploads/temp","jpeg|jpg|png");
                $file_name = $file_data["file_name"];

              $this->load->library("apicall");
              $fileupload=array("name"=>"user_image","contents"=>fopen("./uploads/temp/".$file_name, 'r'));
              $userid=array("name"=>"user_id","contents"=>_get_current_user_id());
              $form_params = array($userid,
              $fileupload);

                $res = $this->apicall->requestmultipart("/rest/user/photo",$form_params);

                if($res != NULL && $res->responce){

                            $this->session->set_userdata("user_image",$res->data);
                            unlink("./uploads/temp/".$file_name);
                }

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
}
