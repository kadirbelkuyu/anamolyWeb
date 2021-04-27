<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Order extends REST_Controller {
    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("orders_model");

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
        $order_id = $this->post("order_id");
        if($order_id == NULL){
            $orders = $this->orders_model->get(array("orders.user_id"=>$user_id,"orders.status !="=>ORDER_UNPAID));
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("You orders"),
                DATA => $orders,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }else{
            $orders = $this->orders_model->get_by_id($order_id);
            $orders->items = $this->orders_model->get_order_items($order_id);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("You orders"),
                DATA => $orders,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }

    }
    /*
    function send_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
        $this->form_validation->set_rules('delivery_date', 'Delivery Date', 'trim|required');
        $this->form_validation->set_rules('delivery_time', 'Delivery Time', 'trim|required');
        $this->form_validation->set_rules('order_items', 'Order Items', 'trim|required');
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
        $this->form_validation->set_rules('house_no', 'House No', 'trim|required');

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
            $post = $this->post();
            $user_id = $post["user_id"];
            $order_date = date(MYSQL_DATE_FORMATE);
            $delivery_date = date(MYSQL_DATE_FORMATE,strtotime($post["delivery_date"]));
            $times = explode("-",$post["delivery_time"]);
            if(count($times) != 2){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Delivery time is not in valid format"),
                    DATA => _l("Delivery time is not in valid format"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }


            $delivery_time = date(MYSQL_TIME_FORMATE,strtotime($times[0]));
            $delivery_time_end = date(MYSQL_TIME_FORMATE,strtotime($times[1]));

            $postal_code = $post["postal_code"];
            $house_no = $post["house_no"];
            $add_on_house_no = $post["add_on_house_no"];
            $street_name = $post["street_name"] ;
            $area = $post["area"];
            $city = (isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "";
            $latitude = $post["latitude"];
            $longitude = $post["longitude"];
            $coupon_code = $post["coupon_code"];
            $order_note = $post["order_note"];

            $this->db->select("Max(order_id) as max_id");
            $q = $this->db->get("orders");
            $max_order = $q->row();
            $order_no = $max_order->max_id + 1;
            $order_items = json_decode($post["order_items"]);



            if($order_items == NULL || empty($order_items)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Something wrong in item inputs"),
                    DATA =>_l("Something wrong in item inputs"),
                    CODE => 100
                ), REST_Controller::HTTP_OK);
            }

            // Add Order Items
            // items are in json array [{product_id : 1, order_qty : 1, }]
            $this->load->model("products_model");
            $total_order_amount = 0;
            $init_items = array();

            $plusone_items = array();
            foreach($order_items as $item){
                $product = $this->products_model->get_by_id($item->product_id);
                if(empty($product)){
                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Some product items are invalid"),
                        DATA =>_l("Some product items are invalid"),
                        CODE => 102
                    ), REST_Controller::HTTP_OK);
                }
                $qty = $item->order_qty;

                $product_price = $product->price;
                $discount_id = "";
                $discount = 0;
                $discount_amount = 0;
                $discount_type = "";

                $offer_id = "";
                $offer_discount = 0;
                $offer_amount = 0;
                $offer_type = 0;
                if($product->discount != NULL && $product->discount > 0){

                    if($item->product_discount_id != $product->product_discount_id){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE =>$product->product_name_nl." "._l(" applied discount is not still valid"),
                            DATA =>$product->product_name_nl." "._l(" applied discount is not still valid"),
                            CODE => 102
                        ), REST_Controller::HTTP_OK);
                    }
                    $discount_type = $product->discount_type;
                    $discount = $product->discount;
                    $discount_id = $product->product_discount_id;
                    if($product->discount_type == "flat"){
                        $discount_amount = $product->discount;
                        $product_price = $product_price - $product->discount;
                    }else if($product->discount_type == "percentage"){
                        $discount_amount = $product->discount * $product_price  / 100;
                        $product_price = $product_price - $discount_amount;
                    }
                }
                if($product->offer_discount != NULL && $product->offer_discount >0){

                    if($item->product_offer_id != $product->product_offer_id){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE =>$product->product_name_nl." "._l("'s offer still not valid"),
                            DATA =>$product->product_name_nl." "._l("'s offer still not valid"),
                            CODE => 102
                        ), REST_Controller::HTTP_OK);
                    }
                    $offer_type = $product->offer_type;
                    $offer_discount = $product->offer_discount;
                    $offer_id = $product->product_offer_id;
                    if($product->offer_type == "plusone"){

                        if($qty > 1){
                            $this->response(array(
                                RESPONCE => false,
                                MESSAGE =>$product->product_name_nl." "._l("Should not allow more then one QTY"),
                                DATA =>$product->product_name_nl." "._l("Should not allow more then one QTY"),
                                CODE => 102
                            ), REST_Controller::HTTP_OK);
                        }
                        if($item->is_free){
                            $product_price = 0;
                        }else{
                            $offer_amount = $product->offer_discount * $product_price  / 100;
                            $product_price = $product_price - $discount_amount;
                        }
                    }else if($product->offer_type == "flatcombo"){

                        if($qty > 1){
                            $this->response(array(
                                RESPONCE => false,
                                MESSAGE =>$product->product_name_nl." "._l("Should not allow more then one QTY"),
                                DATA =>$product->product_name_nl." "._l("Should not allow more then one QTY"),
                                CODE => 102
                            ), REST_Controller::HTTP_OK);
                        }
                        if($item->is_free){
                            $product_price = 0;
                        }else{
                            $offer_amount = $product->offer_discount;
                            $product_price = $product->offer_discount;
                        }
                    }
                }
                $price = $product_price * $qty;

                $init_items[] = array(
                    "product_id"=>$product->product_id,
                    "order_qty"=>$qty,
                    "product_price"=>$product->price,
                    "vat"=>$product->vat,
                    "discount_id"=>$discount_id,
                    "discount_amount"=>$discount_amount,
                    "discount"=>$discount,
                    "discount_type"=>$discount_type,
                    "offer_id"=>$offer_id,
                    "offer_amount"=>$offer_amount,
                    "offer_discount"=>$offer_discount,
                    "offer_type"=>$offer_type,
                    "price"=>$price
                );


            }

            // Calculate total amount of order
            foreach($init_items as $itm){
                $total_order_amount = $total_order_amount + $itm["price"];
            }
            // Validate Coupon First
            $coupon_responce = array();
            if($coupon_code != NULL || $coupon_code != ""){
                $this->load->model("coupons_model");
                $coupon_responce = $this->coupons_model->validate($user_id,$coupon_code);
                if(!$coupon_responce[RESPONCE]){
                    $this->response($coupon_responce, REST_Controller::HTTP_OK);
                }
            }
            // Applu Coupon on Total Amount if applicable
            $net_amount = $total_order_amount;
            $order_discount = 0;
            $order_discount_type = "";
            $order_discount_amount = 0;
            if(!empty($coupon_responce) && $coupon_responce[RESPONCE]){
                $coupon = (Object)$coupon_responce[DATA];
                if(!empty($coupon)){
                    if($total_order_amount < $coupon->min_order_amount){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            DATA =>_l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            CODE => 101
                        ), REST_Controller::HTTP_OK);
                    }else{
                        if($coupon->discount_type == "flat"){
                            $order_discount_amount = $coupon->discount;
                        }else if($coupon->discount_type == "percentage"){
                            $order_discount_amount = $coupon->discount * $product_price  / 100;
                        }
                        if($order_discount_amount > $coupon->max_discount_amount){
                            $order_discount_amount = $coupon->max_discount_amount;
                        }
                        $net_amount = $net_amount - $order_discount_amount;
                        $order_discount_type = $coupon->discount_type;
                        $order_discount = $coupon->discount;
                    }
                }
            }
            // Initial order insert
            $order_init = array(
                "order_no"=>$order_no,
                "order_date"=>date(MYSQL_DATE_TIME_FORMATE),
                "user_id"=>$user_id,
                "delivery_date"=>$delivery_date,
                "delivery_time"=>$delivery_time,
                "delivery_time_end"=>$delivery_time_end,
                "order_note"=>$order_note,
                "coupon_code"=>$coupon_code,
                "discount"=>$order_discount,
                "discount_type"=>$order_discount_type,
                "discount_amount"=>$order_discount_amount,
                "order_amount"=>$total_order_amount,
                "net_amount"=>$net_amount,
                "status"=>ORDER_PENDING
            );
            $order_id = $this->common_model->data_insert("orders",$order_init,true);

            $init_address = array(
                "order_id"=>$order_id,
                "house_no"=>$house_no,
                "add_on_house_no"=>$add_on_house_no,
                "postal_code"=>$postal_code,
                "street_name"=>$street_name,
                "city"=>$city,
                "area"=>$area,
                "latitude"=>$latitude,
                "longitude"=>$longitude
            );

            $this->common_model->data_insert("order_delivery_address",$init_address,true);

            foreach($init_items as $item){
                $item["order_id"] = $order_id;
                $this->db->insert("order_items",$item);
            }

            $order = $this->orders_model->get_by_id($order_id);
            $order_items = $this->orders_model->get_order_items($order_id);
            $order->items = $order_items;
            $this->load->model("email_model");
            $this->email_model->send_order_mail($order,$order_items);
            $msg = _l("Thanks for your order with {APP_NAME}, Order with No #order_no# amount #net_amount# is placed successfully");
            $msg = str_replace(array("#order_no#","#net_amount#","{APP_NAME}"),array($order->order_no,$order->net_amount,APP_NAME),$msg);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => $msg,
                DATA => $order,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }
    }
    */
    function send_post(){
        $post = $this->post();
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
            $this->response(array(
                        RESPONCE => false,
                        MESSAGE => strip_tags($this->form_validation->error_string()),
                        DATA =>strip_tags($this->form_validation->error_string()),
                        CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }else
        {

            $user_id = $post["user_id"];
            $paid_by = $post["paid_by"];
            $postal_code = str_replace(" ","",$post["postal_code"]);


            $order_date = date(MYSQL_DATE_FORMATE);

            $is_express = false;
            if(isset($post["is_express"]) && $post["is_express"] == 1){
                $is_express = true;
            }
            if($is_express){
                $delivery_date = date(MYSQL_DATE_FORMATE);
                $delivery_time = date(MYSQL_TIME_FORMATE);
                $express_delivery_time = get_option("express_delivery_time");
                $delivery_time_end = date(MYSQL_TIME_FORMATE,strtotime("+".$express_delivery_time." minutes", strtotime($delivery_time)));
            }else{


                $delivery_date = date(MYSQL_DATE_FORMATE,strtotime($post["delivery_date"]));
                $times = explode("-",$post["delivery_time"]);
                if(count($times) != 2){
                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Delivery time is not in valid format"),
                        DATA => _l("Delivery time is not in valid format"),
                        CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
                }
                $delivery_time = date(MYSQL_TIME_FORMATE,strtotime($times[0]));
                $delivery_time_end = date(MYSQL_TIME_FORMATE,strtotime($times[1]));



            }

            $day = date("D",strtotime($delivery_date));
            $this->db->where("postal_code",$postal_code);
            $this->db->where("days",$day);
            //$this->db->where("from_time",$delivery_time);
            //$this->db->where("to_time",$delivery_time_end);
            $q = $this->db->get("delivery_times");
            $available_time = $q->row();
            if(empty($available_time)){
                $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Unfortunately we do not deliver to you yet, please choose another zip code"),
                        DATA =>_l("Unfortunately we do not deliver to you yet, please choose another zip code"),
                        CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
            }


            $this->db->where("postal_code",$postal_code);
            $q = $this->db->get("postal_codes");
            $is_postal_available = $q->row();

            if(empty($is_postal_available)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    DATA => _l("Unfortunately, we do not deliver to your postcode yet!"),
                    CODE => 105
                ), REST_Controller::HTTP_OK);
            }

            $house_no = $post["house_no"];

            /*
            $this->load->library("postcodeapi");
            $res_postal_code = json_decode( $this->postcodeapi->get($postal_code ,$house_no));
            if(!isset($res_postal_code->postcode))
            {
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Please provide valid postalcode and house no"),
                    DATA =>_l("Please provide valid postalcode and house no"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
            $add_on_house_no = $post["add_on_house_no"];
            $street_name = (isset($res_postal_code->street)) ? $res_postal_code->street : "" ;
            $area = (isset($res_postal_code->province)) ? $res_postal_code->province : "";
            $city = (isset($res_postal_code->city)) ? $res_postal_code->city : "";
            $latitude = (isset($res_postal_code->location->coordinates[0])) ? $res_postal_code->location->coordinates[0] : "";
            $longitude = (isset($res_postal_code->location->coordinates[1])) ? $res_postal_code->location->coordinates[1] : "";

            */

            $add_on_house_no = $post["add_on_house_no"];
            $street_name = (isset($post["street_name"]) && $post["street_name"] != NULL) ? $post["street_name"] : "" ;
            $area = (isset($post["area"]) && $post["area"] != NULL) ? $post["area"] : "";
            $city = (isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "";
            $latitude = (isset($post["latitude"]) && $post["latitude"] != NULL) ? $post["latitude"] : "";
            $longitude = (isset($post["longitude"]) && $post["longitude"] != NULL) ? $post["longitude"] : "";
            $coupon_code = $post["coupon_code"];
            $order_note = $post["order_note"];

            $this->db->select("Max(order_id) as max_id");
            $q = $this->db->get("orders");
            $max_order = $q->row();
            $order_no = $max_order->max_id + 1;

            $this->load->model("cart_model");
            $cart = $this->cart_model->manage_cart($user_id);

            $order_products= $cart["products"];



            if($order_products == NULL || empty($order_products)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Something wrong in item inputs"),
                    DATA =>_l("Something wrong in item inputs"),
                    CODE => 100
                ), REST_Controller::HTTP_OK);
            }
            $out_of_stock = "";
            // Add Order Items
            // items are in json array [{product_id : 1, order_qty : 1, }]
            $this->load->model("products_model");

            foreach($order_products as $o_p){
                foreach($o_p["items"] as $item){
                  if($item->finalstock >= $item->qty){
                    $init_items[] = array(
                        "product_id"=>$item->product_id,
                        "order_qty"=>$item->qty,
                        "product_price"=>$item->price,
                        "vat"=>$item->vat,
                        "discount_id"=>($item->product_discount_id == NULL) ? 0 : $item->product_discount_id,
                        "discount"=>($item->discount == NULL) ? 0 : $item->discount,
                        "discount_type"=>($item->discount_type == NULL) ? "" : $item->discount_type,
                        "offer_id"=>($item->product_offer_id == NULL) ? 0 : $item->product_offer_id,
                        "offer_discount"=>($item->offer_discount == NULL) ? 0 : $item->offer_discount,
                        "offer_type"=>($item->offer_type == NULL) ? "" : $item->offer_type,
                        "number_of_products"=>($item->number_of_products == NULL) ? "0" : $item->number_of_products,
                        "price"=>$item->effected_price
                    );
                  }else{
                      $out_of_stock .= $item->product_name_nl." "._l("is out of stock")."\n";
                  }
                }

            }
            if($out_of_stock != ""){
              $this->response(array(
                  RESPONCE => false,
                  MESSAGE => $out_of_stock,
                  DATA =>$out_of_stock,
                  CODE => 100
              ), REST_Controller::HTTP_OK);
            }

            $total_order_amount = $cart["net_paid_amount"];
            $cart_total_amount = $cart["total_amount"];
            $final_discount = $cart_total_amount - $total_order_amount;
            // Validate Coupon First
            $coupon_responce = array();
            if($coupon_code != NULL || $coupon_code != ""){
                $this->load->model("coupons_model");
                $coupon_responce = $this->coupons_model->validate($user_id,$coupon_code);
                if(!$coupon_responce[RESPONCE]){
                    $this->response($coupon_responce, REST_Controller::HTTP_OK);
                }
            }

            // Applu Coupon on Total Amount if applicable
            $net_amount = $total_order_amount;
            $order_discount = 0;
            $order_discount_type = "";
            $order_discount_amount = 0;
            if(!empty($coupon_responce) && $coupon_responce[RESPONCE]){
                $coupon = (Object)$coupon_responce[DATA];
                if(!empty($coupon)){
                    if($total_order_amount < $coupon->min_order_amount){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            DATA =>_l("Discount coupon is not applicable, Please try with min order amount ".$coupon->min_order_amount),
                            CODE => 101
                        ), REST_Controller::HTTP_OK);
                    }else{
                        if($coupon->discount_type == "flat"){
                            $order_discount_amount = $coupon->discount;
                        }else if($coupon->discount_type == "percentage"){
                            $order_discount_amount = $coupon->discount * $net_amount  / 100;
                        }
                        if($order_discount_amount > $coupon->max_discount_amount){
                            $order_discount_amount = $coupon->max_discount_amount;
                        }
                        $net_amount = $net_amount - $order_discount_amount;
                        $order_discount_type = $coupon->discount_type;
                        $order_discount = $coupon->discount;
                    }
                }
            }
            // Initial order insert
            $order_status = ORDER_PENDING;
            $gateway_charges = 0;
            if($paid_by == "ideal"){
                $order_status = ORDER_UNPAID;
                $gateway_charges = get_option("gateway_charges");
            }
            $delivery_charges = 0;
            $site_options = get_options(array("express_delivery_charge","currency_symbol"));
            if($is_express){
                $delivery_charges = $site_options["express_delivery_charge"];
            }
            $net_amount = $net_amount + $gateway_charges + $delivery_charges;
            $order_discount_amount = $order_discount_amount + $final_discount;
            if($is_postal_available->min_order_amount > 0 && $net_amount < $is_postal_available->min_order_amount){
                $e_msg = _l("We will deliver to you from #currency# #minamount#! Free delivery service with a smile : )");
                $e_msg = str_replace(array("#minamount#","#currency#"),array($is_postal_available->min_order_amount,$site_options["currency_symbol"]),$e_msg);
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => $e_msg,
                    DATA =>$e_msg,
                    CODE => 100
                ), REST_Controller::HTTP_OK);
            }
            $order_init = array(
                "order_no"=>$order_no,
                "order_date"=>date(MYSQL_DATE_TIME_FORMATE),
                "user_id"=>$user_id,
                "delivery_date"=>$delivery_date,
                "delivery_time"=>$delivery_time,
                "delivery_time_end"=>$delivery_time_end,
                "order_note"=>$order_note,
                "coupon_code"=>$coupon_code,
                "discount"=>$order_discount,
                "discount_type"=>$order_discount_type,
                "discount_amount"=>$order_discount_amount,
                "order_amount"=>$total_order_amount,
                "net_amount"=>$net_amount,
                "status"=>$order_status,
                "paid_by"=>$paid_by,
                "gateway_charges"=>$gateway_charges,
                "is_express"=>($is_express) ? "1" : "0",
                "delivery_amount"=>$delivery_charges
            );
            $order_id = $this->common_model->data_insert("orders",$order_init,true);

            $init_address = array(
                "order_id"=>$order_id,
                "house_no"=>$house_no,
                "add_on_house_no"=>$add_on_house_no,
                "postal_code"=>$postal_code,
                "street_name"=>$street_name,
                "city"=>$city,
                "area"=>$area,
                "latitude"=>$latitude,
                "longitude"=>$longitude
            );

            $this->common_model->data_insert("order_delivery_address",$init_address,true);

            foreach($init_items as $item){
                $item["order_id"] = $order_id;
                $this->db->insert("order_items",$item);
            }

            $order = $this->orders_model->get_by_id($order_id);
            $order_items = $this->orders_model->get_order_items($order_id);
            $order->items = $order_items;

            if($paid_by == "cod"){
                // Flush Cart
                $this->db->where("user_id",$user_id);
                $this->db->delete("cart");

                $this->load->model("email_model");
                $this->email_model->send_order_mail($order,$order_items);

                $msg = _l("Thanks for your order with {APP_NAME}, Order with No #order_no# amount #net_amount# is placed successfully");
                $msg = str_replace(array("#order_no#","#net_amount#","{APP_NAME}"),array($order->order_no,$order->net_amount,APP_NAME),$msg);
                $this->response(array(
                    RESPONCE => true,
                    MESSAGE => $msg,
                    DATA => $order,
                    CODE => CODE_SUCCESS
                ), REST_Controller::HTTP_OK);
            }else{
                //$this->load->library("payvision");
                //$payment_response =    $this->payvision->paynow($order_id,$net_amount,APP_NAME." Order #".$order_no);
                $this->load->library("payvisioncheckout");
                $checkout = $this->payvisioncheckout->checkout($order->net_amount);
                if(!empty($checkout) && isset($checkout["checkoutId"])){
                    $this->db->update("orders",array("payment_ref"=>$checkout["checkoutId"]),array("order_id"=>$order_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => site_url("idealpayment/checkout/".$checkout["checkoutId"]),
                        DATA => array("responseURL"=>site_url("idealpayment/checkout/".$checkout["checkoutId"])) ,
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
                }else{
                    $this->db->where("order_id",$order_id);
                    $this->db->delete("orders");

                    $this->db->where("order_id",$order_id);
                    $this->db->delete("order_items");

                    $this->db->where("order_id",$order_id);
                    $this->db->delete("order_delivery_address");

                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Sorry failed to make payment"),
                        DATA =>_l("Sorry failed to make payment"),
                        CODE => 101
                    ), REST_Controller::HTTP_OK);
                }
            }
        }
    }
}
