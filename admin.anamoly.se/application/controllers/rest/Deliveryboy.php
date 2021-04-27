<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Deliveryboy extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("user_model");
        $this->load->model("orders_model");
        $this->load->model("deliveryboy_model");
    }
    public function login_post(){

        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('boy_phone', 'Boy Phone',  'trim|required');
        $this->form_validation->set_rules('boy_password', 'Password', 'trim|required');

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

            $this->db->where("boy_phone",$post["boy_phone"]);
            $q = $this->db->get("delivery_boy");
            $user = $q->row();

            if(empty($user)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! No Delivery Boy Register"),
                    DATA =>_l("Sorry! No Delivery Boy Register"),
                    CODE => CODE_USER_NOT_FOUND
                ), REST_Controller::HTTP_OK);
            }


            if($user->boy_password != md5($post["boy_password"])){
                    $this->response(array(
                                RESPONCE => false,
                                MESSAGE => _l("Incorrect password"),
                                DATA => _l("Incorrect password"),
                                CODE => 109
                            ), REST_Controller::HTTP_OK);
            }
            unset($user->boy_password);
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Welcome")." ".$user->boy_name,
                DATA => $user,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);

        }

    }
    function home_post(){
        $delivery_boy_id = $this->post("delivery_boy_id");
        if($delivery_boy_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Delivery Boy Referance"),
                DATA =>_l("Sorry! Delivery Boy Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }

        $orders_assigned = $this->orders_model->get(array("orders.delivery_boy_id"=>$delivery_boy_id,"orders.status"=>ORDER_CONFIRMED));
        $orders_picked = $this->orders_model->get(array("orders.delivery_boy_id"=>$delivery_boy_id,"orders.status"=>ORDER_OUT_OF_DELIVEY));
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("You orders"),
            DATA => array("assigned"=>$orders_assigned,"picked"=>$orders_picked),
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function completedorders_post(){
        $delivery_boy_id = $this->post("delivery_boy_id");
        if($delivery_boy_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Delivery Boy Referance"),
                DATA =>_l("Sorry! Delivery Boy Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }

        $orders_assigned = $this->orders_model->get(array("orders.delivery_boy_id"=>$delivery_boy_id,"orders.status"=>ORDER_DELIVERED));
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Order History"),
            DATA => $orders_assigned,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function pick_order_post(){

        $language = getheaderlanguage();
        $this->lang->load('base',$language);

        $delivery_boy_id = $this->post("delivery_boy_id");
        if($delivery_boy_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Delivery Boy Referance"),
                DATA =>_l("Sorry! Delivery Boy Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }
        $order_id = $this->post("order_id");
        if($order_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Order Referance"),
                DATA =>_l("Sorry! Order Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }

        $order = $this->orders_model->get_by_id($order_id);
        if(!empty($order)){
                $title = "Order Out of Delivery";
                $message = "Your Order No. #order_no# is out of delivery you may track it on your phone.";
                $message = str_replace("#order_no#",$order->order_no,$message);

                $title_nl = "Beställ före leverans";
                $message_nl = "ditt beställningsnummer #order_no# är ute för leverans kan du spåra det på din telefon.";
                $message_nl = str_replace("#order_no#",$order->order_no,$message_nl);
        
                $title_ar = "Order Out of Delivery";
                $message_ar = "Your Order No. #order_no# is out of delivery you may track it on your phone.";
                $message_ar = str_replace("#order_no#",$order->order_no,$message_ar);
                
                $title_tr = "Order Out of Delivery";
                $message_tr = "Your Order No. #order_no# is out of delivery you may track it on your phone.";
                $message_tr = str_replace("#order_no#",$order->order_no,$message_tr);
                
                $title_de = "Order Out of Delivery";
                $message_de = "Your Order No. #order_no# is out of delivery you may track it on your phone.";
                $message_de = str_replace("#order_no#",$order->order_no,$message_de);
        
        }
        if($title != "" && $message != ""){
            $this->load->library("onesignallib");
            $player_ids = array();
            if(isset($order->android_token) && $order->android_token != ""){
                $player_ids[] = $order->android_token;
            }
            if(isset($order->ios_token) && $order->ios_token != ""){
                $player_ids[] = $order->ios_token;
            }
            $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER_TRACK,"ref_id"=>$order->order_id));
            $this->common_model->data_insert("notifications",
                    array("user_id"=>$order->user_id,
                    "title_nl"=>$title_nl,
                    "title_en"=>$title,
                    "title_ar"=>$title_ar,
                    "title_tr"=>$title_tr,
                    "title_de"=>$title_de,
                    "message_nl"=>$message_nl,
                    "message_en"=>$message,
                    "message_ar"=>$message_ar,
                    "message_tr"=>$message_tr,
                    "message_de"=>$message_de,
                    "type"=>NOTIFICATION_TYPE_ORDER,
                    "type_id"=>$order->order_id),true
                );
        }

        $this->common_model->data_update("orders",array("status"=>ORDER_OUT_OF_DELIVEY),array("order_id"=>$order->order_id));

        $order->status = ORDER_OUT_OF_DELIVEY;
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Order Picked"),
            DATA => $order,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function delivered_order_post(){
        $language = getheaderlanguage();
        $this->lang->load('base',$language);

        $delivery_boy_id = $this->post("delivery_boy_id");
        if($delivery_boy_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Delivery Boy Referance"),
                DATA =>_l("Sorry! Delivery Boy Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }
        $order_id = $this->post("order_id");
        if($order_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! Order Referance"),
                DATA =>_l("Sorry! Order Referance"),
                CODE => CODE_USER_NOT_FOUND
            ), REST_Controller::HTTP_OK);
        }
        $order = $this->orders_model->get_by_id($order_id);
        if(!empty($order)){
            $title = "Order Delivered";
            $message = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
            $message = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message);

            $title_nl = "Beställning levererad";
            $message_nl = "Tack för att du gick med {APP_NAME} är ditt beställningsnummer #order_no# is bezorgd, snälla dela dina recensioner.";
            $message_nl = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);
        
            $title_ar = "Order Delivered";
            $message_ar = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
            $message_ar = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_ar);

            $title_tr = "Order Delivered";
            $message_tr = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
            $message_tr = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_tr);

            $title_de = "Order Delivered";
            $message_de = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
            $message_de = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_de);
        }
        
        if($title != "" && $message != ""){
            $this->load->library("onesignallib");
            $player_ids = array();
            if(isset($order->android_token) && $order->android_token != ""){
                $player_ids[] = $order->android_token;
            }
            if(isset($order->ios_token) && $order->ios_token != ""){
                $player_ids[] = $order->ios_token;
            }
            $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
            $this->common_model->data_insert("notifications",
                    array("user_id"=>$order->user_id,
                    "title_nl"=>$title_nl,
                    "title_en"=>$title,
                    "title_ar"=>$title_ar,
                    "title_tr"=>$title_tr,
                    "title_de"=>$title_de,
                    "message_nl"=>$message_nl,
                    "message_en"=>$message,
                    "message_ar"=>$message_ar,
                    "message_tr"=>$message_tr,
                    "message_de"=>$message_de,
                    "type"=>NOTIFICATION_TYPE_ORDER,
                    "type_id"=>$order->order_id),true
                );
        }

        $this->common_model->data_update("orders",array("status"=>ORDER_DELIVERED),array("order_id"=>$order->order_id));

        $order->status = ORDER_OUT_OF_DELIVEY;
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Order Picked"),
            DATA => $order,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function playerid_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('delivery_boy_id', 'Delivery Boy ID', 'trim|required');
        $this->form_validation->set_rules('player_id', 'OneSignal Player ID', 'trim|required');
        $this->form_validation->set_rules('device', 'Device Type', 'trim|required');
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

            $insert = array(
                "android_token"=>$post["player_id"]
            );

            if($post["device"] == "ios"){
                $insert = array(
                    "ios_token"=>$post["player_id"]
                );
            }

            $this->common_model->data_update("delivery_boy",$insert,array("delivery_boy_id"=>$post["delivery_boy_id"]),true);

            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("One Signal Toke Updated"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
    function status_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('delivery_boy_id', 'Delivery Boy ID', 'trim|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
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

            $insert = array(
                "status"=>$post["status"]
            );
            $this->common_model->data_update("delivery_boy",$insert,array("delivery_boy_id"=>$post["delivery_boy_id"]),true);

            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Status Changed"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }

    function changepassword_post(){
        $user_id = $this->post("delivery_boy_id");
        $c_password = $this->post("c_password");
        $n_password = $this->post("n_password");
        $r_password = $this->post("r_password");
        if($c_password == NULL || $n_password == NULL || $r_password == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide required fields"),
                DATA => _l("Please provide required fields"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("delivery_boy_id",$user_id);
        $this->db->where("boy_password",md5($c_password));
        $q = $this->db->get("delivery_boy");
        $user = $q->row();
        if(empty($user)){

                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! You provide wrong existing password"),
                    DATA => _l("Sorry! You provide wrong existing password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }
        if($n_password != $r_password){

                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry! Repeat password not match with new password"),
                    DATA => _l("Sorry! Repeat password not match with new password"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
        }

                    $this->common_model->data_update("delivery_boy",array("boy_password"=>md5($n_password)),array("delivery_boy_id"=>$user->delivery_boy_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Your password change successfully"),
                        DATA =>_l("Your password change successfully"),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);

    }
}
