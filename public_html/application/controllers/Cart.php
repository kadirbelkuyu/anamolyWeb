<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends Base_Controller {
    private $data;
  	public function add()
  	{
      if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "qty"=>$this->input->post("qty"),
            "product_id"=>$this->input->post("product_id"));
          $res = $this->apicall->request("/rest/cart/add",$form_params);
          if($res->responce){
              $res->html = $this->load->view("webshop/views/cart_items_lists",array("carts"=>$res->data),true);
          }
          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
    public function remove()
  	{
      if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "qty"=>$this->input->post("qty"),
            "product_id"=>$this->input->post("product_id"));
          $res = $this->apicall->request("/rest/cart/minus",$form_params);
          if($res->responce){
              $res->html = $this->load->view("webshop/views/cart_items_lists",array("carts"=>$res->data),true);
          }
          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
    public function delete()
  	{
      if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "cart_id"=>$this->input->post("cart_id"));
          $res = $this->apicall->request("/rest/cart/delete",$form_params);
          if($res->responce){
              $res->html = $this->load->view("webshop/views/cart_items_lists",array("carts"=>$res->data),true);
          }
          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
    function get(){
      if(_is_user_login()){

          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id());
          $res = $this->apicall->request("/rest/cart/list",$form_params);
          header('Content-Type: application/json');
          echo json_encode($res);

      }
    }

    public function viewcart()
    {
        if (_is_user_login()) {
            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id());
            $res = $this->apicall->request("/rest/cart/list",$form_params);
            $carts = array();
            if ($res->responce) {
                $carts = $res->data;
                if (!empty($carts)) {
                    $this->data["carts"] = $carts;
                }
            }
            $user_pref = $this->session->userdata("addresses");
            $postal_code = "";
            if(!empty($user_pref)){
                $postal_code =  $user_pref[0]->postal_code;
            }
            $restimeslot = $this->apicall->request("/rest/timeslots/list",array("time_type"=>"all","postal_code"=>$postal_code));
            if ($restimeslot->responce) {

                $timeslots = $restimeslot->data;
                $this->data["timeslots"] = $timeslots;
                /*
                // Pickup and Delivery both options
                if (!empty($timeslots)) {

                    $delivery_times = array();
                    $pickup_times = array();
                    foreach($timeslots as $times){
                        if($times->time_type == "delivery"){
                            $delivery_times[] = $times;
                        }else if($times->time_type == "pickup"){
                            $pickup_times[] = $times;
                        }
                    }
                    $this->data["timeslots"] = $delivery_times;
                    $this->data["pickup_timeslots"] = $pickup_times;
                }
                */
            }
            if (isset($_GET["order"]) && $this->input->get("order") == "failed") {
                 $this->data["script"] = _toastmessage("Sorry! Order failed, something wrong while payment or order processing","error");
            }
            if(!empty($carts) && !empty($carts->products)){
              $this->data["page_content"] = $this->load->view("webshop/cart", $this->data, true);
            }
             $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }

    public function checkout()
    {
          if (_is_user_login()) {
                $this->load->library("apicall");

                $post = $this->input->post();
                if(!isset($post["delivery_date"])){
                    redirect("cart/viewcart");
                    return;
                }
                $this->data["post"] = $post;
                if(isset($post["paid_by"])){
                  $this->load->library('form_validation');

                  if(!isset($post["is_express"])){
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
                      $post["user_id"] = _get_current_user_id();
                      $res = $this->apicall->request("/rest/order/send",$post);
                      if ($res->responce) {
                        if ($post["paid_by"] == "cod" || $post["paid_by"] == "oncredit") {

                            $json_string = _encrypt_val($res->data->order_id);
                            set_flash_alert($res->message);
                            redirect("cart/ordersuccess/".$json_string);
                        }else{
                            redirect($res->data->responseURL."?webshop=true");
                        }
                      }else{
                          $this->data["script"] = _toastmessage($res->message);
                      }
                  }
                }

              $form_params = array("user_id" => _get_current_user_id());
              $res = $this->apicall->request("/rest/cart/list",$form_params);
              if ($res->responce) {
                  $carts = $res->data;
                  if (!empty($carts)) {
                      $this->data["carts"] = $carts;
                  }
              }

              $resuser = $this->apicall->request("/rest/user/profile", $form_params);
              if ($resuser->responce) {
                  $users = $resuser->data;
                  if (!empty($users)) {
                      $this->data["users"] = $users;
                  }
              }

              if(isset($post["coupon_code"])){
                $form_params["coupon_code"] = $post["coupon_code"];
                $coupon_res = $this->get_coupon($form_params);
                $this->data["coupon"] = $coupon_res->data;
              }

            $this->data["page_content"] = $this->load->view("webshop/checkout", $this->data, true);
             $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
          } else {
              redirect("login");
          }

    }
    public function validate_address()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "postal_code"=>$this->input->post("postal_code"),
            "house_no"=>$this->input->post("house_no"));

          $res = $this->apicall->request("/rest/address/validate",$form_params);

          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }

    public function validate_couponcode()
    {
       if(_is_user_login()){
        if(isset($_POST)){
          $this->load->library("apicall");
          $form_params = array("user_id"=>_get_current_user_id(),
            "coupon_code"=>$this->input->post("coupon_code"));
          $res = $this->get_coupon($form_params);
          header('Content-Type: application/json');
          echo json_encode($res);
        }
      }
    }
    private function get_coupon($form_params){
        $res = $this->apicall->request("/rest/coupon/validate",$form_params);

        if($res->responce)
        {
           $res->data->discountlabel="Discount".($res->data->discount_type!="flat"?" (".$res->data->discount."%)":"");
           $res->data->deduct_amount=_lprice($res->data->deduct_amount);
           $res->data->paid_amount=_lprice($res->data->paid_amount);
        }
        return $res;
    }
    public function orderfailed(){
      $this->data["page_content"] = $this->load->view("webshop/orderfailed", $this->data, true);
      $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
    }
    public function ordersuccess($details)
    {
        if (_is_user_login()) {
          $id = _decrypt_val($details);
          $this->load->library("apicall");
          $form_params = array("user_id" => _get_current_user_id(), "order_id" => $id);

          $res = $this->apicall->request("/rest/order/list", $form_params);

          if ($res->responce) {
              $this->data["order"] = $res->data;
          }

            $this->data["page_content"] = $this->load->view("webshop/ordersuccess", $this->data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }
}
