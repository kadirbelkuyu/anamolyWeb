<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Notifications extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("address_model");
       
    }
    function list_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User referance required"),
                DATA => _l("User referance required"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where_in("user_id",array("0",$user_id));
        $q = $this->db->get("notifications");
        $data = $q->result();
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Notifications"),
            DATA => $data,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
}