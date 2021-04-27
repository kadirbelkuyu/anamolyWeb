<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MY_Controller
{

    /**
     * login page
     * @param username for user name
     * @param password for password
     * @return login page
     */
    public function index()
    {
        if (_is_user_login($this)) {
            redirect(_get_user_redirect($this));
        } else {
            $this->load->helper('cookie');
            $data = array("error" => "");
            if (isset($_POST)) {

                $this->load->library('form_validation');

                $this->form_validation->set_rules('username', 'User Name', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                if ($this->form_validation->run() == false) {
                    if ($this->form_validation->error_string() != "") {
                        $this->message_model->error($this->form_validation->error_string());
                    }

                } else {

                    $q = $this->db->query("Select * from `users` where (`user_email`='" . $this->
                        input->post("username") . "') and user_password='" . md5($this->input->post("password")) ."'   Limit 1");

                    if ($q->num_rows() > 0) {
                        $row = $q->row();
                        if ($row->status == "0") {
                            _set_flash_message(_l("msg_account_active"), "error");

                        } else {
                            $rememberme = $this->input->post('remember');

                            if (isset($rememberme) && $rememberme == "on") {

                                set_cookie("c_username", $this->input->post("username"), '3600');
                                set_cookie("c_password", $this->input->post("password"), '3600');
                                set_cookie("remiber_me", $rememberme, '3600');
                            } else {
                                delete_cookie('c_username');
                                delete_cookie('c_password');
                                delete_cookie("remiber_me");
                            }

                            $newdata = array(
                                'user_fullname' => $row->user_firstname." ".$row->user_lastname,
                                'user_email' => $row->user_email,
                                'user_phone' => $row->user_phone,
                                'logged_in' => true,
                                'user_id' => $row->user_id,
                                'user_type_id' => $row->user_type_id,
                                'user_image' => $row->user_image);

                            $this->session->set_userdata($newdata);

                            redirect(_get_user_redirect($this));

                        }
                    } else {
                        _set_flash_message(_l("msg_correct_user_n_password"), "error");
                    }
                }
            }
            $data["active"] = "login";
            $this->load->view('admin/login', $data);
        }

    }

    /**
     * logout page
     * @return login page
     */
    function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }
    function test(){
        /*
        $message = "Your order is confirmed";
        $title = "Order";
        $this->load->library("onesignallib");
        $res=$this->onesignallib->sendToPlayerIds($message,$title,array("993bfd36-dd80-44d0-a6fa-d54a909f9946"),array("type"=>"orders","ref_id"=>"7"));
        print_r($res);
        */
        /*
        $this->load->library("postcodeapi");
        $res = json_decode( $this->postcodeapi->get("7601XJ","206"));
        if(isset($res->postcode))
        print_r($res);
        echo $res->location->coordinates[0];

        //$this->load->library("ideal");
        //$this->ideal->paynow();
        */
        //$this->load->library("mailboxlib");
        //$this->mailboxlib->getEmails(0,10);

        /*
        $order_id = "40";
        $order_no = "40";
        $net_amount = 40;
        $this->load->library("payvision");
        $payment_response =    $this->payvision->paynow($order_id,$net_amount,APP_NAME." Order #".$order_no);
                if($payment_response["response"]){
                    $this->db->update("orders",array("payment_ref"=>$payment_response["id"]),array("order_id"=>$order_id));
                    redirect($payment_response["url"]);
                }
        */
        $this->load->model("email_model");
        echo $this->email_model->send_registration_otp("Subhash Sanghani", "subhashsanghani@gmail.com","1234");
    }
    function refund(){
        $this->load->library("payvision");
        $this->payvision->refund("1be4764f-f76e-4f62-9e90-56d74c90057f");
    }
    function checkout(){

            $this->load->library("payvisioncheckout");
            $checkout = $this->payvisioncheckout->checkout();
            if(!empty($checkout)){
                $this->load->view('checkout', $checkout);
            }
        /*
        $order_id = "10";
        $order_no = "10";
        $net_amount = 10;
        $this->load->library("payvision");
        $payment_response =    $this->payvision->paynow($order_id,$net_amount,APP_NAME." Order #".$order_no);

                if($payment_response["response"]){
                    //$this->db->update("orders",array("payment_ref"=>$payment_response["id"]),array("order_id"=>$order_id));
                    //redirect($payment_response["url"]);
                    $this->load->view('payout', $payment_response);
                }
         */
    }
    function finish(){
        $this->load->library("ideal");
        $get = $this->input->get();
        $this->ideal->checkout($get["project_id"],$get["order_id"]);
    }
}
