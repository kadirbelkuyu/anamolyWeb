<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Setting extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();

        if(!_is_admin() && !_is_sub_admin()){
            redirect("login");
            exit();

        }
    }

    /**
	* setting
	* @return setting page
	*/
    public function index(){
        if(!_is_admin() && !_is_sub_admin("setting")){
            redirect("login");
            exit();
        }
            $id = _get_current_user_id();

			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"general_setting",true,true);
            }
            $setting =  get_options_by_type("general_setting");//get_options(array("name","copyright","website","currency","currency_symbol","gateway_charges"));

            $this->data["field"] = $setting;

            $this->data["page_content"] = $this->load->view("admin/settings/general_setting",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

    public function app(){
        if(!_is_admin() && !_is_sub_admin("setting/app")){
            redirect("login");
            exit();
        }
        $id = _get_current_user_id();

        if($_POST)
        {
            $post = $this->input->post();

            if(isset($_FILES["header_logo"]) && $_FILES['header_logo']['size'] > 0){
                $path = APP_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['header_logo']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('header_logo',680,200,$path ,'crop',true,$file_name_temp);
                $post["header_logo"] = $file_name_temp;
            }

            if(isset($_FILES["login_top_image"]) && $_FILES['login_top_image']['size'] > 0){
                $path = APP_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['login_top_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('login_top_image',680,200,$path ,'crop',true,$file_name_temp);
                $post["login_top_image"] = $file_name_temp;
            }

            add_options($post,"app_setting",true,true);
        }

        $setting = get_options_by_type("app_setting");

        $this->data["field"] = $setting;
        $this->data["fileupload"] = true;
        $this->data["colorpickerjs"] = true;
        $this->data["page_content"] = $this->load->view("admin/settings/app_setting",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	function email(){
        if(!_is_admin() && !_is_sub_admin("setting/email")){
            redirect("login");
            exit();
        }
        $id = _get_current_user_id();

			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"sendgrid",true,true);
            }
            $setting = get_options(array("email_sender","sendgrid_id","sendgrid_key","receiver_email"));

            $this->data["field"] = $setting;

            $this->data["page_content"] = $this->load->view("admin/settings/email",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function billing(){
        if(!_is_admin() && !_is_sub_admin("setting/billing")){
            redirect("login");
            exit();
        }
        $id = _get_current_user_id();

			if($_POST)
            {
                $post = $this->input->post();
                add_options($post,"billing",true,true);
            }
            $setting = get_options_by_type("billing");

            $this->data["field"] = $setting;

            $this->data["page_content"] = $this->load->view("admin/settings/billing",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
}
