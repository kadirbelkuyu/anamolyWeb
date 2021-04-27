<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Cart extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("products_model");
        $this->load->model("cart_model");
    }
    function add_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('product_id', 'Product ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");
            $product_id = $this->post("product_id");
            $qty = $this->post("qty");

            $product = $this->products_model->get_by_id($product_id,array("cart_user_id"=>$user_id));
            if(empty($product)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Product not found"),
                    DATA =>_l("Sorry! Product not found"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
            if($product->finalstock - $product->cart_qty <= 0){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Product out of stock"),
                    DATA =>_l("Sorry! Product out of stock"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
            $this->db->select("SUM(qty) as total_qty");
            $this->db->where("user_id",$user_id);
            $this->db->where("product_id",$product_id);
            $q = $this->db->get("cart");
            $tq = $q->row();
            $total_cart_qty = $tq->total_qty;
            if($product->max_cart_qty != 0 && $total_cart_qty > $product->max_cart_qty){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("You can no longer add this product to your basket. You have already reached the maximum number per order."),
                    DATA =>_l("You can no longer add this product to your basket. You have already reached the maximum number per order."),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }


            $this->db->where(array("cart.user_id"=>$user_id,"cart.product_id"=>$product_id));
            $q = $this->db->get("cart");
            $cart_item = $q->row();
            $cart_id = 0;
            if(!empty($cart_item)){
                if($product->product_offer_id != NULL && $product->product_offer_id > 0){
                    $cart_id = $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product_id));
                }else{
                    $cart_id = $cart_item->cart_id;
                    $qty = $cart_item->qty + $qty;
                    $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                }
            }else{
                $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product_id));
            }

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                "cart_id" => $cart_id,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }
    /*
    function minus_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('cart_id', 'Product ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");
            $cart_id = $this->post("cart_id");
            $qty = $this->post("qty");

            $this->db->where("cart_id",$cart_id);
            $this->db->where("user_id",$user_id);
            $q = $this->db->get("cart");
            $cart_item  = $q->row();

            if(!empty($cart_item)){
                $qty = $cart_item->qty - $qty;

                if($qty <= 0){
                    $this->db->where_in("cart_id",$cart_id);
                    $this->db->where("user_id",$user_id);
                    $this->db->delete("cart");
                }else{

                    $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                }
            }

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }
    */
    function minus_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('product_id', 'Product ID', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");
            $product_id = $this->post("product_id");
            $qty = $this->post("qty");

            $this->db->where("product_id",$product_id);
            $this->db->where("user_id",$user_id);
            $this->db->order_by("cart_id desc");
            $q = $this->db->get("cart");
            $cart_item  = $q->row();

            $cart_id = 0;
            if(!empty($cart_item)){
                $qty = $cart_item->qty - $qty;
                $cart_id = $cart_item->cart_id;
                if($qty <= 0){
                    $this->db->where_in("cart_id",$cart_item->cart_id);
                    $this->db->where("user_id",$user_id);
                    $this->db->delete("cart");
                }else{

                    $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                }
            }

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                "cart_id" => $cart_id,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }
    function list_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User referance required"),
                DATA =>_l("User referance required"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $cart_array = $this->cart_model->manage_cart($user_id);

        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("User Cart"),
            DATA => $cart_array,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }


    function delete_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('cart_id', 'Cart ID', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");
            $cart_id = explode(",",$this->post("cart_id"));

            $this->db->where_in("cart_id",$cart_id);
            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }

    function clean_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");

            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }

    function reorder_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('order_id', 'Order ID', 'trim|required');

        if ($this->form_validation->run() == FALSE)
        {
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {
            $user_id = $this->post("user_id");
            $order_id = $this->post("order_id");

            $this->db->where("user_id",$user_id);
            $this->db->delete("cart");

            $this->load->model("orders_model");
            $order_items = $this->orders_model->get_order_items($order_id);
            foreach($order_items as $item){
                $qty = $item->order_qty;
                $product = $this->products_model->get_by_id($item->product_id);
                if(empty($product))
                {
                    continue;
                }
                $this->db->where(array("cart.user_id"=>$user_id,"cart.product_id"=>$product->product_id));
                $q = $this->db->get("cart");
                $cart_item = $q->row();

                if(!empty($cart_item)){
                    if($product->product_offer_id != NULL && $product->product_offer_id > 0){
                        $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product->product_id));
                    }else{
                        $qty = $cart_item->qty + $qty;
                        $this->common_model->data_update("cart",array("qty" => $qty),array("cart_id"=>$cart_item->cart_id));
                    }
                }else{
                    $this->common_model->data_insert("cart",array("qty" => $qty,"user_id"=>$user_id,"product_id"=>$product->product_id));
                }
            }
            $cart_array = $this->cart_model->manage_cart($user_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("User Cart"),
                DATA => $cart_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }
}
