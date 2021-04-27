<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Contact extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        
    }
    function send_post(){
        $user_id = $this->post("user_id");
        $fullname = $this->post("fullname");
        $phone = $this->post("phone");
        $message = $this->post("message");

        if($user_id == NULL || $fullname == NULL || $phone == NULL || $message == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide required fields"),
                DATA => _l("Please provide required fields"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
                $update_array = array("user_id"=>$user_id,"fullname"=>$fullname,"phone"=>$phone,"message"=>$message);
                
        $this->common_model->data_insert("contact_request",$update_array);
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("We received your request, Our support team will contact you soon"),
            DATA => _l("We received your request, Our support team will contact you soon"),
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
}