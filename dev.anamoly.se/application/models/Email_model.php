<?php class Email_model extends CI_Model{

    public function __construct()
    {
        parent::__construct();
        $this->load->library("emailsenders/sendgrid_email");
    }
    public function get_template_by_id($id){
        $this->db->where("template_id",$id);
        $q = $this->db->get("email_templates");
        return $q->row();
    }
    function send_registration_otp($fullname, $email,$otp){
        $language = getheaderlanguage();

        $template = $this->get_template_by_id(3);

        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);

        $body = str_replace(array("##OTP##","##user_full_name##"),array($otp,$fullname),$message);
        $subject = str_replace(array("##OTP##","##user_full_name##"),array($otp,$fullname),$subject);
        $to = array();
        $to[$email] = $fullname;
        //$subject = _l("Registration in {APP_NAME} App");
        $body = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$body),TRUE);
        return $this->sendgrid_email->send($to,$subject,$body);
    }
    function send_welcome_mail($user){
            $language = getheaderlanguage();
            $template = $this->get_template_by_id(1);
            $message = $template->email_message;
            $subject = $template->email_subject;
            if($language == "english"){
                $message = $template->email_message_en;
                $subject = $template->email_subject_en;
            }
            $this->lang->load('base',$language);

            //$tags = explode(",",$template->email_tags);
            $subject = str_replace(array("##user_full_name##","##user_email##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email),$subject);
            $message = str_replace(array("##user_full_name##","##user_email##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email),$message);
            $to = array();
            $to[$user->user_email] = $user->user_firstname;
            $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
            $return = $this->sendgrid_email->send($to,$subject,$message);
            $this->receve_new_register_mail($user);
            return $return;
    }
    function receve_new_register_mail($user){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(9);
        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);
        $settings = get_options_by_type("sendgrid");
        //$tags = explode(",",$template->email_tags);
        $subject = str_replace(array("##user_full_name##","##user_email##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email),$subject);
        $message = str_replace(array("##user_full_name##","##user_email##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email),$message);
        $to = array();
        $receivers = explode(",",$settings["receiver_email"]);
        foreach($receivers as $receiver){
            $to[$receiver] = APP_NAME;
        }

        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
        $this->sendgrid_email->send($to,$subject,$message);
    }
    function send_waitinglist_mail($user,$waitinglistno){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(7);
        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);

        //$tags = explode(",",$template->email_tags);
        $subject = str_replace(array("##user_full_name##","##user_email##","##waiting_no##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email,$waitinglistno),$subject);
        $message = str_replace(array("##user_full_name##","##user_email##","##waiting_no##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email,$waitinglistno),$message);
        $to = array();
        $to[$user->user_email] = $user->user_firstname;
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
        $return = $this->sendgrid_email->send($to,$subject,$message);
        $this->receve_new_register_mail($user);
        return $return;
    }
    function send_no_postalcode_mail($user,$postal_code){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(8);
        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);

        //$tags = explode(",",$template->email_tags);
        $subject = str_replace(array("##user_full_name##","##user_email##","##postal_code##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email,$postal_code),$subject);
        $message = str_replace(array("##user_full_name##","##user_email##","##postal_code##"),array($user->user_firstname." ".$user->user_lastname,$user->user_email,$postal_code),$message);
        $to = array();
        $to[$user->user_email] = $user->user_firstname;
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$message),TRUE);
        $return = $this->sendgrid_email->send($to,$subject,$message);
        $this->receve_new_register_mail($user);
        return $return;
    }
    function send_order_mail($order,$order_items){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(2);
        $message = $template->email_message;
        if($language == "english"){
            $message = $template->email_message_en;
        }
        $this->lang->load('base',$language);

        $settings = get_options_by_type("billing");
        $body = $this->load->view("emails/order_email_template",array("order"=>$order,"items"=>$order_items,"setting"=>$settings,"language"=>$language),true);
        $message = str_replace(array("##order_details##"),array($body),$message);

        $to = array();
        $to[$order->user_email] = $order->user_firstname;
        $subject = $template->email_subject; //." "._l("#order_no# with amount #net_amount#")
        $subject = str_replace(array("#order_no#","#net_amount#"),array($order->order_no,$order->net_amount),$subject);
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$body),TRUE);
        $return = $this->sendgrid_email->send($to,$subject,$message);

        $this->received_order_mail($order,$order_items);

        return $return;
    }
    function received_order_mail($order,$order_items){
        $language = getheaderlanguage();
        $template = $this->get_template_by_id(10);
        $message = $template->email_message;
        if($language == "english"){
            $message = $template->email_message_en;
        }
        $this->lang->load('base',$language);

        $settings = get_options_by_type(array("billing","sendgrid"));
        $body = $this->load->view("emails/order_email_template",array("order"=>$order,"items"=>$order_items,"setting"=>$settings,"language"=>$language),true);
        $message = str_replace(array("##order_details##"),array($body),$message);

        $to = array();
        $receivers = explode(",",$settings["receiver_email"]);
        foreach($receivers as $receiver){
            $to[$receiver] = APP_NAME;
        }
        $subject = $template->email_subject; //." "._l("#order_no# with amount #net_amount#")
        $subject = str_replace(array("#order_no#","#net_amount#"),array($order->order_no,$order->net_amount),$subject);
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$body),TRUE);
        $this->sendgrid_email->send($to,$subject,$message);
    }
    function forgot_password_mail($user,$otp){
        $enc = _encrypt_val($otp."####".$user->user_email);
        $url = site_url("password/reset/".$enc);

        $language = getheaderlanguage();
        $template = $this->get_template_by_id(4);
        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);

        $reset_link = "<a href='".$url."' target='_blank'>"._l("CLICK ME TO RESET PASSWORD")."</a>";
        $body = str_replace(array("##reset_link##","##user_full_name##"),array($reset_link,$user->user_firstname),$message);
        $subject = str_replace(array("##user_full_name##"),array($user->user_firstname),$subject);


        //$body = $this->load->view("emails/forgot_password_template",array("user"=>$user,"url"=>$url),true);
        $to = array();
        $to[$user->user_email] = $user->user_firstname;
        //$subject = _l("Password Reset Request");
        $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$body),TRUE);
        return $this->sendgrid_email->send($to,$subject,$message);
    }
    function custom_coupon_mail($coupon_id){

        $language = getheaderlanguage();
        $template = $this->get_template_by_id(6);
        $message = $template->email_message;
        $subject = $template->email_subject;
        if($language == "english"){
            $message = $template->email_message_en;
            $subject = $template->email_subject_en;
        }
        $this->lang->load('base',$language);

        $this->db->where("coupons.coupon_id",$coupon_id);
        $this->db->join("users","users.user_id = coupons.users");
        $q = $this->db->get("coupons");
        $coupon = $q->row();
        if(!empty($coupon)){


            $body = str_replace(array("##COUPONCODE##","##DESCRIPTION##","##user_full_name##"),array($coupon->coupon_code,$coupon->description_en,$coupon->user_firstname),$message);
            $to = array();
            $to[$coupon->user_email] = $coupon->user_firstname;
            $message = $this->load->view('emails/email_base_template',array("subject"=>$subject,"message"=>$body),TRUE);
            return $this->sendgrid_email->send($to,$subject,$message);
        }
    }
}
