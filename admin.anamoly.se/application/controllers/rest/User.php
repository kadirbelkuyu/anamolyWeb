<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class User extends REST_Controller {

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
    }
    public function test_post(){
        $headers = getallheaders();
        $headers["test"] = generate_encryption_key();
        //$this->lang->load("rest_controller",$headers["X-APP-LANGUAGE"]);
        $headers["lang"] = _l("text_rest_invalid_credentials");
        print_r($headers);

    }
    public function login_post(){

                $this->load->library('form_validation');
                // Validate The Logi User
                $this->form_validation->set_rules('user_email', 'Email Address',  'trim|required|valid_email');
                $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

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

                    $this->db->where("user_email",$post["user_email"]);
                    $q = $this->db->get("users");
                    $user = $q->row();

                    if(empty($user)){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! No User with this email"),
                            DATA =>_l("Sorry! No User with this email"),
                            CODE => CODE_USER_NOT_FOUND
                        ), REST_Controller::HTTP_OK);
                    }
                    if($user->is_email_verified == 0){
                        $OTP = generateNumericOTP(6);
                        $this->load->model("email_model");
		                $res = $this->email_model->send_registration_otp($user->user_firstname." ".$user->user_lastname,$user->user_email,$OTP);
                        $this->common_model->data_update("users",array("verify_token"=>md5($OTP)),array("user_id"=>$user->user_id));
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Please enter verification OTP sent to your email"),
                            DATA =>array("user_email"=>$user->user_email),
                            CODE => 108
                        ), REST_Controller::HTTP_OK);
                    }
                    if($user->status == 3){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! You status is in waiting list. You will join soon"),
                            DATA => array("user_firstname"=>$user->user_firstname,"user_lastname"=>$user->user_lastname,"req_queue"=>$user->req_queue,"user_id"=>$user->user_id,"user_email"=>$user->user_email),
                            CODE => 106
                        ), REST_Controller::HTTP_OK);
                    }
                    if($user->status == 2){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! Your account is suspended"),
                            DATA =>_l("Sorry! Your account is suspended"),
                            CODE => 107
                        ), REST_Controller::HTTP_OK);
                    }

                    if($user->user_type_id != USER_CUSTOMER && $user->user_type_id != USER_COMPANY ){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! User not allowed to use this app"),
                            DATA =>_l("Sorry! User not allowed to use this app"),
                            CODE => 108
                        ), REST_Controller::HTTP_OK);
                    }

                    if($user->user_password != md5($post["user_password"])){
                            $this->response(array(
                                        RESPONCE => false,
                                        MESSAGE => _l("Incorrect password"),
                                        DATA => _l("Incorrect password"),
                                        CODE => 109
                                    ), REST_Controller::HTTP_OK);
                    }

                    $this->db->where("user_address.user_id",$user->user_id);
                    $this->db->join("user_address","user_address.postal_code = postal_codes.postal_code");
                    $q = $this->db->get("postal_codes");
                    $postal_code = $q->row();
                    if(empty($postal_code)){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Unfortunately, we do not deliver to your postcode yet!"),
                            DATA => _l("Unfortunately, we do not deliver to your postcode yet!"),
                            CODE => 105
                        ), REST_Controller::HTTP_OK);
                    }
                    if($user->status == 0){
                        $this->response(array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry! Your account is disable"),
                            DATA =>_l("Sorry! Your account is disable"),
                            CODE => 107
                        ), REST_Controller::HTTP_OK);
                    }
                    $this->common_model->data_update("users",array("login_date"=>date(MYSQL_DATE_TIME_FORMATE)),array("user_id"=>$user->user_id),false);
                    $this->userInfo($user);

                }

    }
    function verifyemail_post(){
        $email = $this->post("user_email");
        $otp = $this->post("otp");
        $this->load->model("email_model");
        if($email == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide email"),
                DATA => _l("Please provide email"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        if($otp == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide otp"),
                DATA => _l("Please provide otp"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("users.user_email",$email);
        $this->db->join("user_address","user_address.user_id = users.user_id");
        $q = $this->db->get("users");
        $user = $q->row();
        if(empty($user)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Email not registered with our system"),
                DATA => _l("Email not registered with our system"),
                CODE => 102
            ), REST_Controller::HTTP_OK);
        }
        if($user->verify_token == md5($otp)){
            $req_queue = "";
            $array = array("is_email_verified"=>"1","verify_token"=>"","status"=>"1","activation_date"=>date(MYSQL_DATE_TIME_FORMATE));

            if($user->status == 0){

                $this->common_model->data_update("users",$array,array("user_id"=>$user->user_id));

                $this->db->where("user_address.user_id",$user->user_id);
                $this->db->where("postal_codes.draft","0");
                $this->db->join("user_address","user_address.postal_code = postal_codes.postal_code");
                $q = $this->db->get("postal_codes");
                $postal_code = $q->row();
                if(empty($postal_code)){
                    $this->email_model->send_no_postalcode_mail($user,$user->postal_code);
                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Unfortunately, we do not deliver to your postcode yet!"),
                        DATA => _l("Unfortunately, we do not deliver to your postcode yet!"),
                        CODE => 105
                    ), REST_Controller::HTTP_OK);
                }

                /*
                // Remove Queue management
                $this->db->select("count(users.user_id) as total_users");
                $this->db->join("user_address","user_address.user_id = users.user_id");
                $this->db->where("user_address.postal_code",$user->postal_code);
                $qr = $this->db->get("users");
                $qc = $qr->row();
                if($qc->total_users > $postal_code->users_limits){
                    //$req_queue = $qc->total_users - $postal_code->users_limits;
                    $req_queue = $qc->total_users;
                    $array["req_queue"] = $req_queue ;
                    $array["status"] = "3";
                }
                */

                if($req_queue == ""){
                    $user->is_email_verified = 1;
                    $this->email_model->send_welcome_mail($user);
                    $this->userInfo($user);

                }else{
                    $this->common_model->data_update("users",$array,array("user_id"=>$user->user_id));
                    $this->email_model->send_waitinglist_mail($user,$req_queue);
                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("Thanks for registration, You status is in waiting list. You will join soon"),
                        DATA => array("user_firstname"=>$user->user_firstname,"user_lastname"=>$user->user_lastname,"req_queue"=>$req_queue,"user_id"=>$user->user_id,"user_email"=>$user->user_email),
                        CODE => 106
                    ), REST_Controller::HTTP_OK);
                }
            }/*else{
                $array["status"] = "1";
            }*/


        }else{
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Invalid OTP Try again"),
                DATA => _l("Invalid OTP Try again"),
                CODE => 103
            ), REST_Controller::HTTP_OK);
        }
    }
    private function userInfo($user){
        unset($user->user_password);
        $user->addresses = $this->user_model->get_address($user->user_id);
        $user->settings = $this->user_model->get_settings($user->user_id);
        $user->family = $this->user_model->get_family($user->user_id);



        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Welcome")." ".$user->user_firstname." ".$user->user_lastname,
            DATA => $user,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);

    }
    public function profile_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide user referance"),
                DATA => _l("Please provide user referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_id",$user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        $this->userInfo($user);

    }
    public function register_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_email', 'Email Address','trim|required|valid_email|is_unique[users.user_email]');
        $this->form_validation->set_rules('user_firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('user_lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
        $this->form_validation->set_message('is_unique', _l('Email address is already register'));
        $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');

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
            $postal_code = $post["postal_code"];
            $house_no = $post["house_no"];
            /*
            $this->db->where("postal_code",$postal_code);
            $this->db->where("draft","0");
            $q = $this->db->get("postal_codes");
            $p_code = $q->row();
            if(empty($p_code)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry We are not allowed service on this postal code"),
                    DATA =>_l("Sorry We are not allowed service on this postal code"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
            */
            /*
            $this->load->library("postcodeapi");
            $res_postal_code = json_decode( $this->postcodeapi->get($postal_code,$house_no));
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

            $OTP = generateNumericOTP(6);
            $this->load->model("email_model");
		    $sent_otp_email = $this->email_model->send_registration_otp($post["user_firstname"]." ".$post["user_lastname"],$post["user_email"],$OTP);
            $referral_code = app_generate_hash();
            if($sent_otp_email){
                $insert = array(
                    "user_email"=>$post["user_email"],
                    "user_firstname"=>$post["user_firstname"],
                    "user_lastname"=>$post["user_lastname"],
                    "user_password"=>md5($post["user_password"]),
                    "user_phone"=>$post["user_phone"],
                    "ios_token"=>$post["ios_token"],
                    "android_token"=>$post["android_token"],
                    "is_email_verified"=>"0",
                    "is_mobile_verified"=>"1",
                    "verify_token"=>md5($OTP),
                    "referral_code"=>$referral_code,
                    "status"=>"0",
                    "registration_date"=>date(MYSQL_DATE_TIME_FORMATE)
                );
                if($post["is_company"] == "true"){
                    $insert["user_type_id"] = USER_COMPANY;
                    $insert["user_company_name"] = $post["user_company_name"];
                    $insert["user_company_id"] = $post["user_company_id"];


                }else{
                    $insert["user_type_id"] = USER_CUSTOMER;

                }

                $user_id = $this->common_model->data_insert("users",$insert,true);
                /*
                $address = array(
                    "user_id"=>$user_id,
                    "postal_code"=>$postal_code,
                    "house_no"=>$post["house_no"],
                    "add_on_house_no"=>$post["add_on_house_no"],
                    "street_name"=>$res_postal_code->street,
                    "city"=>$res_postal_code->city,
                    "area"=>$res_postal_code->province,
                    "municipality"=>$res_postal_code->municipality,
                    "latitude"=>(isset($res_postal_code->location->coordinates[0])) ? $res_postal_code->location->coordinates[0] : "",
                    "longitude"=>(isset($res_postal_code->location->coordinates[1])) ? $res_postal_code->location->coordinates[1] : "",
                    "is_active"=>"1"
                );
                */
                $address = array(
                    "user_id"=>$user_id,
                    "postal_code"=>$postal_code,
                    "house_no"=>$post["house_no"],
                    "add_on_house_no"=>$post["add_on_house_no"],
                    "street_name"=>"",
                    "city"=>"",
                    "area"=>"",
                    "municipality"=>"",
                    "latitude"=>"",
                    "longitude"=>"",
                    "is_active"=>"1"
                );

                $this->common_model->data_insert("user_address",$address,true);
                $insert["user_id"] = $user_id;

                $referred_by = $this->post("referred_by");
                if($referred_by != NULL){
                    $this->db->where("user_email",$referred_by);
                    $q = $this->db->get("users");
                    $user = $q->row();
                    if(!empty($user)){
                        $this->load->model("referralcodes_model");
                        $this->load->model("coupons_model");
                        $coupons = $this->referralcodes_model->get(array("status"=>"1"));
                        if(!empty($coupons)){
                            $coupon = $coupons[0];
                            $c_exist = $this->coupons_model->get(array("users"=>$user_id,"coupon_code"=>$coupon->coupon_code));
                            if(empty($c_exist)){
                                $start_date = date("Y-m-d");
                                $end_date = date('Y-m-d', strtotime($start_date. ' + '.$coupon->valid_days.' days'));
                                $coupon_insert = array(
                                    "coupon_code"=>$coupon->coupon_code,
                                    "users"=>$user_id,
                                    "coupon_type"=>"share",
                                    "multi_usage"=>$coupon->multi_usage,
                                    "maximum_usages"=>$coupon->maximum_usages,
                                    "discount"=>$coupon->discount,
                                    "discount_type"=>$coupon->discount_type,
                                    "min_order_amount"=>$coupon->min_order_amount,
                                    "max_discount_amount"=>$coupon->max_discount_amount,
                                    "validity_start"=>$start_date,
                                    "validity_end"=>$end_date,
                                    "description_en"=>$coupon->tnc_en,
                                    "description_ar"=>$coupon->tnc_ar,
                                    "description_nl"=>$coupon->tnc_nl,
                                    "description_tr"=>$coupon->tnc_tr,
                                    "description_de"=>$coupon->tnc_de,
                                );
                                $coupon_id = $this->common_model->data_insert("coupons",$coupon_insert);
                                $this->email_model->custom_coupon_mail($coupon_id);
                            }
                        }
                    }
                }



                $this->userInfo((Object)$insert);
            }else{
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Something wrong with your email address, Please use correct email"),
                    DATA =>_l("Something wrong with your email address, Please use correct email"),
                    CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
            }
        }
    }
    public function setfamily_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('no_of_adults', 'No Of Adults', 'trim|required');
        $this->form_validation->set_rules('no_of_child', 'No of Childs', 'trim|required');
        $this->form_validation->set_rules('no_of_dogs', 'No of Dogs', 'trim|required');
        $this->form_validation->set_rules('no_of_cats', 'No of Cats', 'trim|required');
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
                "user_id"=>$post["user_id"],
                "no_of_adults"=>$post["no_of_adults"],
                "no_of_child"=>$post["no_of_child"],
                "no_of_dogs"=>$post["no_of_dogs"],
                "no_of_cats"=>$post["no_of_cats"]
            );
            $family = $this->user_model->get_family($post["user_id"]);
            if(empty($family)){
                $id = $this->common_model->data_insert("user_family",$insert,true);
                $insert["user_family_id"] = $id;
            }else{
                $this->common_model->data_update("user_family",$insert,array("user_id"=>$post["user_id"]),true);
                $insert["user_family_id"] = $family->user_family_id;
            }
            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Family Updated Success"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
    public function updatesettings_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $user_id =  $this->post("user_id");
        $user_email =  $this->post("user_email");

        if($user_id == NULL && $user_email == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _("Provide user referance"),
                DATA =>_("Provide user referance"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        //$this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('general_notifications', 'General Notification', 'trim|required');
        $this->form_validation->set_rules('order_notifications', 'Order Notifcation', 'trim|required');
        $this->form_validation->set_rules('general_emails', 'General Emails', 'trim|required');
        $this->form_validation->set_rules('order_emails', 'Order Emails', 'trim|required');
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
            if($user_id == NULL){
                $this->db->where("user_email",$user_email);
                $q = $this->db->get("users");
                $user = $q->row();
                $user_id = $user->user_id;
            }
            $post = $this->post();
            $insert = array(
                "user_id"=>$user_id,
                "general_notifications"=>$post["general_notifications"],
                "order_notifications"=>$post["order_notifications"],
                "general_emails"=>$post["general_emails"],
                "order_emails"=>$post["order_emails"]
            );
            $setting = $this->user_model->get_settings($user_id);
            if(empty($setting)){
                $id = $this->common_model->data_insert("user_settings",$insert,true);
                $insert["user_setting_id"] = $id;
            }else{
                $this->common_model->data_update("user_settings",$insert,array("user_id"=>$user_id),true);
                $insert["user_setting_id"] = $setting->user_setting_id;
            }
            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Settings Updated Success"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }

    function update_name_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('user_firstname', 'User First Name', 'trim|required');
        $this->form_validation->set_rules('user_lastname', 'User Last Name', 'trim|required');
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
                "user_firstname"=>$post["user_firstname"],
                "user_lastname"=>$post["user_lastname"]
            );

            $this->common_model->data_update("users",$insert,array("user_id"=>$post["user_id"]),true);

            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("User Full name updated"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }


    function update_email_post(){
        $user_id = $this->post("user_id");
        $user_email = $this->post("user_email");

        if($user_id == NULL || $user_email == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please enter user referance"),
                DATA =>_l("Please enter user referance"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_id",$user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        if(empty($user)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Wrong user referance"),
                DATA =>_l("Wrong user referance"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);

        }

        if($user->user_email == $user_email){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please try different email address"),
                DATA =>_l("Please try different email address"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("user_email",$user_email);
        $q = $this->db->get("users");
        $existing_email = $q->row();
        if(!empty($existing_email)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("This email already registered with our system, Please try different email"),
                DATA =>_l("This email already registered with our system, Please try different email"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);
        }

            $OTP = generateNumericOTP(6);
            $this->load->model("email_model");
		    $res = $this->email_model->send_registration_otp($user->user_firstname." ".$user->user_lastname,$user_email,$OTP);

            if($res){
                    $this->common_model->data_update("users",array("verify_token"=>md5($OTP)),array("user_id"=>$user->user_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Verify Your email"),
                        DATA =>array("user_id"=>$user->user_id,"user_email"=>$user_email),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this email"),
                    DATA =>_l("Faild to sent verification on this email"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }

    }
    function resendotp_post(){
            $user_email = $this->post("user_email");

            if( $user_email == NULL){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Please enter user referance"),
                    DATA =>_l("Please enter user referance"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
            $this->db->where("users.user_email",$user_email);
            $this->db->join("user_address","user_address.user_id = users.user_id");
            $q = $this->db->get("users");
            $user = $q->row();
            if(empty($user)){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Email not registered with our system"),
                    DATA => _l("Email not registered with our system"),
                    CODE => 102
                ), REST_Controller::HTTP_OK);
            }

            $OTP = generateNumericOTP(6);
            $this->load->model("email_model");
		    $res = $this->email_model->send_registration_otp($user->user_firstname." ".$user->user_lastname,$user_email,$OTP);

            if($res){
                    $this->common_model->data_update("users",array("verify_token"=>md5($OTP)),array("user_id"=>$user->user_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Verify Your email"),
                        DATA =>array("user_id"=>$user->user_id,"user_email"=>$user_email),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this email"),
                    DATA =>_l("Faild to sent verification on this email"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
    }
    function verify_update_email_post(){
        $email = $this->post("user_email");
        $otp = $this->post("otp");
        $user_id = $this->post("user_id");
        if($email == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide email"),
                DATA => _l("Please provide email"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        if($otp == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide otp"),
                DATA => _l("Please provide otp"),
                CODE => 101
            ), REST_Controller::HTTP_OK);
        }
        if($user_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide user referance"),
                DATA => _l("Please provide  user referance"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $this->db->where("users.user_id",$user_id);
        $q = $this->db->get("users");
        $user = $q->row();
        if(empty($user)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User not registered with our system"),
                DATA => _l("User not registered with our system"),
                CODE => 102
            ), REST_Controller::HTTP_OK);
        }
        if($user->verify_token == md5($otp)){

            $this->common_model->data_update("users",array("user_email"=>$email),array("user_id"=>$user->user_id));
            $user->user_email = $email;
            $this->userInfo($user);

        }else{
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Invalid OTP Try again"),
                DATA => _l("Invalid OTP Try again"),
                CODE => 103
            ), REST_Controller::HTTP_OK);
        }
    }

    function update_phone_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
        $this->form_validation->set_rules('user_phone', 'User Phone', 'trim|required');
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
                "user_phone"=>$post["user_phone"]
            );

            $this->common_model->data_update("users",$insert,array("user_id"=>$post["user_id"]),true);

            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("User Phone updated"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
    public function photo_post(){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
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
                $file_name = "";
                if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                    $path = PROFILE_IMAGE_PATH;

                    if(!file_exists($path)){
                        mkdir($path);
                    }
                    $this->load->library("imagecomponent");
                    $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['user_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',false,$file_name_temp);
                    $update_array["user_image"] = $file_name;
                    $this->common_model->data_update("users", $update_array, array("user_id"=>$this->post("user_id")));

                    $this->response(array(
                        RESPONCE => true,
                                        MESSAGE => _l("User photo updated"),
                                        DATA =>$file_name,
                                        CODE => CODE_SUCCESS)
                                , REST_Controller::HTTP_OK);
                }else{
                    $this->response(array(
                        RESPONCE => false,
                        MESSAGE => _l("No File Selected"),
                        DATA => _l("No File Selected"),
                        CODE => CODE_MISSING_INPUT
                    ), REST_Controller::HTTP_OK);
                }
             }
    }
    function forgotpassword_post(){
        $user_email = $this->post("user_email");
        if($user_email == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide your registered email"),
                DATA => _l("Please provide your registered email"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);

        }
        $this->db->where("user_email",$user_email);
        $q = $this->db->get("users");
        $user = $q->row();
        if(empty($user)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! email not exist with our system"),
                DATA => _l("Sorry! email not exist with our system"),
                CODE => CODE_MISSING_INPUT
            ), REST_Controller::HTTP_OK);

        }
            $OTP = generateNumericOTP(6);
            $this->load->model("email_model");
		    $res = $this->email_model->forgot_password_mail($user,$OTP);

            if($res){
                    $this->common_model->data_update("users",array("verify_token"=>md5($OTP)),array("user_id"=>$user->user_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("We sent you password recovery link to your email id, You may use it to reset password"),
                        DATA =>_l("We sent you password recovery link to your email id, You may use it to reset password"),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);
            }else{
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Faild to sent verification on this email"),
                    DATA =>_l("Faild to sent verification on this email"),
                    CODE => CODE_MISSING_INPUT
                ), REST_Controller::HTTP_OK);
            }
    }
    function changepassword_post(){
        $user_id = $this->post("user_id");
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
        $this->db->where("user_id",$user_id);
        $this->db->where("user_password",md5($c_password));
        $q = $this->db->get("users");
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

                    $this->common_model->data_update("users",array("user_password"=>md5($n_password)),array("user_id"=>$user->user_id));
                    $this->response(array(
                        RESPONCE => true,
                        MESSAGE => _l("Your password change successfully"),
                        DATA =>_l("Your password change successfully"),
                        CODE => CODE_SUCCESS
                    ), REST_Controller::HTTP_OK);

    }
    function playerid_post(){
        $this->load->library('form_validation');
        // Validate The Logi User
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required');
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

            $this->common_model->data_update("users",$insert,array("user_id"=>$post["user_id"]),true);

            $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("One Signal Token Updated"),
                                        DATA => $insert,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
        }
    }
}
?>
