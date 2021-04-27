<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Address extends REST_Controller {

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

        $addresses = $this->address_model->get(array("user_address.user_id"=>$user_id));

        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("User Addresses"),
                                        DATA => $addresses,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
    function add_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
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

            $inser_array = array(
                "user_id"=>$post["user_id"],
                "house_no"=>$post["house_no"],
                "add_on_house_no"=>$post["add_on_house_no"],
                "postal_code"=>str_replace(" ","",$post["postal_code"]),
                "street_name"=>$post["street_name"],
                "city"=>(isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "",
                "area"=>$post["area"],
                "latitude"=>$post["latitude"],
                "longitude"=>$post["longitude"]
            );
            $user_address_id = $this->common_model->data_insert("user_address",$inser_array,true);
            $inser_array["user_address_id"] = $user_address_id;
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Address Added Successfully"),
                DATA => $inser_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function update_post(){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_id', 'User Referance','trim|required');
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
            $postal_code = str_replace(" ","",$post["postal_code"]) ;

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
            */

            $addresses = $this->address_model->get(array("user_address.user_id"=>$user_id));
            $address = $addresses[0];
            /*
            $inser_array = array(
                "user_id"=>$post["user_id"],
                "house_no"=>$post["house_no"],
                "add_on_house_no"=>$post["add_on_house_no"],
                "postal_code"=>$postal_code,
                "street_name"=>(isset($res_postal_code->street)) ? $res_postal_code->street : "",
                "city"=>(isset($res_postal_code->city)) ? $res_postal_code->city : "",
                "area"=>(isset($res_postal_code->province)) ? $res_postal_code->province : "",
                "municipality"=>(isset($res_postal_code->municipality)) ? $res_postal_code->municipality : "",
                "latitude"=>(isset($res_postal_code->location->coordinates[0])) ? $res_postal_code->location->coordinates[0] : "",
                "longitude"=>(isset($res_postal_code->location->coordinates[1])) ? $res_postal_code->location->coordinates[1] : "",

            );
            */
            $inser_array = array(
                "user_id"=>$post["user_id"],
                "house_no"=>$post["house_no"],
                "add_on_house_no"=>$post["add_on_house_no"],
                "postal_code"=>$postal_code,
                "street_name"=>(isset($post["street_name"]) && $post["street_name"] != NULL) ? $post["street_name"] : "",
                "city"=>(isset($post["city"]) && $post["city"] != NULL) ? $post["city"] : "",
                "area"=>(isset($post["area"]) && $post["area"] != NULL) ? $post["area"] : "",
                "municipality"=>"",
                "latitude"=>(isset($post["latitude"]) && $post["latitude"] != NULL) ? $post["latitude"] : "",
                "longitude"=>(isset($post["longitude"]) && $post["longitude"] != NULL) ? $post["longitude"] : "",
            );
            $this->common_model->data_update("user_address",$inser_array,array("user_address_id"=>$address->user_address_id));
            $inser_array["user_address_id"] = $address->user_address_id;
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Address Added Successfully"),
                DATA => $inser_array,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function validate_post(){
        $postal_code = str_replace(" ","",$this->post("postal_code"));
        $house_no = $this->post("house_no");
        if($postal_code == NULL || $house_no == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide valid postalcode and house no"),
                DATA =>_l("Please provide valid postalcode and house no"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }

            $this->db->where("postal_codes.postal_code",$postal_code);
            $this->db->join("delivery_times","delivery_times.postal_code = postal_codes.postal_code");
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
            }else{
                $address = array(
                    "postal_code"=>$postal_code,
                    "house_no"=>$house_no,
                    "street_name"=>(isset($res_postal_code->street)) ? $res_postal_code->street : "",
                    "city"=>(isset($res_postal_code->city)) ? $res_postal_code->city : "",
                    "area"=>(isset($res_postal_code->province)) ? $res_postal_code->province : "",
                    "municipality"=>(isset($res_postal_code->municipality)) ? $res_postal_code->municipality : "",
                    "latitude"=>(isset($res_postal_code->location->coordinates[0])) ? $res_postal_code->location->coordinates[0] : "",
                    "longitude"=>(isset($res_postal_code->location->coordinates[1])) ? $res_postal_code->location->coordinates[1] : "",

                );
            */
                $address = array(
                    "postal_code"=>$postal_code,
                    "house_no"=>$house_no,
                    "street_name"=>"",
                    "city"=>"",
                    "area"=>"",
                    "municipality"=>"",
                    "latitude"=>"",
                    "longitude"=>"",

                );
                $this->response(array(
                    RESPONCE => true,
                    MESSAGE => _l("Address Added Successfully"),
                    DATA => $address,
                    CODE => CODE_SUCCESS
                ), REST_Controller::HTTP_OK);
            //}
    }
}
