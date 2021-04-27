<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Password extends MY_Controller
{

    public function reset($key)
    {
        $otp = _decrypt_val($key);
        if($otp == NULL || $otp == ""){
            echo _l("Opps! You are invalid");
            return;
        }
        $vals = explode("####",$otp);
        $otp = $vals[0];
        $email = $vals[1];

        $this->db->where("user_email",$email);
        $this->db->where("verify_token",md5($otp));
        $q = $this->db->get("users");
        $user = $q->row();

        if(empty($user)){
            echo _l("Opps! You are not authorized");
            return;
        }
        $data["success"] = false;
        if($_POST){
            $this->load->library('form_validation');

                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                $this->form_validation->set_rules('repeat_password', 'Repeat Password', 'trim|required');
                if ($this->form_validation->run() == false) {
                    if ($this->form_validation->error_string() != "") {
                        $this->message_model->error($this->form_validation->error_string());
                    }

                } else {
                    $password = $this->input->post("password");
                    $r_password = $this->input->post("repeat_password");
                    if($password == $r_password){
                        $array = array("user_password"=>md5($password),"verify_token"=>"");
                        $this->common_model->data_update("users",$array,array("user_id"=>$user->user_id));
                        $this->message_model->success(_l("Password reset successfully. You may try login with new password now."));
                        $data["success"] = true;
                    }else{
                        $this->message_model->error(_l("Repeat password do not match with existing"));
                    } 
                }
        }
        $this->load->view('password/reset',$data);
    }
}
