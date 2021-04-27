<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Contactus extends Base_Controller
{

    private $data;
    public function index()
    {
        $this->action();
        //if (_is_user_login()) {
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view("webshop/contactus", $this->
                data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        //} else {
        //    redirect("login");
        //}
    }

    private function action()
    {
        $post = $this->input->post();

        $this->load->library('form_validation');
        $this->form_validation->set_rules('fullname', _l("Full Name"), 'trim|required');
        $this->form_validation->set_rules('phoneno', _l("Phone No."), 'trim|required');
        $this->form_validation->set_rules('content', _l("Content"), 'trim|required');

        $responce = array();
        if ($this->form_validation->run() == false) {
            if ($this->form_validation->error_string() != "") {
                _set_flash_message($this->form_validation->error_string(), "error");
            }
        } else {
            $this->load->library("apicall");
            $form_params = array(
                "user_id" => _get_current_user_id(),
                "fullname" => $post["fullname"],
                "phone" => $post["phoneno"],
                "message" => $post["content"]);
            $res = $this->apicall->request("/rest/contact/send", $form_params);
            if ($res->responce) {
                $this->data["script"] = _toastmessage($res->message,'success');
                 redirect();
            }

        }
    }


}
