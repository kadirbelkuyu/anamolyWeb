<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Order extends Base_Controller
{

    private $data;
    public function myorder()
    {
        if (_is_user_login()) {

            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id());
            $res = $this->apicall->request("/rest/order/list", $form_params);
            if ($res->responce) {
                $this->data["orders"] = $res->data;
            }

            $this->data["page_content"] = $this->load->view("webshop/myorder", $this->data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }

    public function orderdetail($id)
    {
        $id = _decrypt_val($id);

        if (_is_user_login()) {

            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id(), "order_id" => $id);
            $res = $this->apicall->request("/rest/order/list", $form_params);
            if ($res->responce) {
                $this->data["orders"] = $res->data;
            }
            $this->data["page_script"] = $this->load->view("webshop/script/reorder", $this->
                data, true);
            $this->data["page_content"] = $this->load->view("webshop/orderdetail", $this->
                data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }
/*
    function ordersend(){
        if (_is_user_login()) {
            $post = $this->input->post();
            $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        if(isset($post["is_express"])){
            $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|required');
            $this->form_validation->set_rules('delivery_time', 'Delivery Time', 'trim|required');
        }
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
        $this->form_validation->set_rules('house_no', 'House No', 'trim|required');
        $this->form_validation->set_rules('paid_by', 'Payment Type', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->data["script"] = _toastmessage($this->form_validation->error_string());
        }else
        {
            $this->load->library("apicall");
            $post["user_id"] = _get_current_user_id();
            $form_params = $post;
            $res = $this->apicall->request("/rest/order/send", $form_params);
            if ($res->responce) {
                if ($post["paid_by"] == "cod") {
                    $json_string = json_encode($res->data);
                    $json_string = _encrypt_val($json_string);

                    $data = array("responce"=>true,"data"=>$json_string);

                    header('Content-Type: application/json');
                    echo json_encode($data);
                }else{

                }
            }

            $this->data["page_content"] = $this->load->view("webshop/orderdetail", $this->
                data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }
  */
  function reorder(){
    if(_is_user_login()){
      if(isset($_POST["order_id"])){
        $this->load->library("apicall");
        $form_params = array("user_id"=>_get_current_user_id(),
          "order_id"=>_decrypt_val($this->input->post("order_id"))
        );
        $res = $this->apicall->request("/rest/cart/reorder",$form_params);
        header('Content-Type: application/json');
        echo json_encode($res);
      }
    }
  }
}
