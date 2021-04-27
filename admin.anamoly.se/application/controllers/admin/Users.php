<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Users extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "users";
        $this->primary_key= "user_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;

        if(!_is_admin() && !_is_sub_admin()){
            redirect("login");
            exit();
        }
		$this->load->model("user_model");

    }

    function planner(){
        if(!_is_admin() && !_is_sub_admin("admin/users/planner")){
            redirect("login");
            exit();
        }
        $this->data["user_type_id"] = USER_PLANNER;
        $this->data["data"] = $this->user_model->get(array("user_type_id"=>USER_PLANNER));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function buyer(){
        if(!_is_admin() && !_is_sub_admin("admin/users/buyer")){
            redirect("login");
            exit();
        }
        $this->data["user_type_id"] = USER_BUYER;
        $this->data["data"] = $this->user_model->get(array("user_type_id"=>USER_BUYER));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function appuser(){
        if(!_is_admin() && !_is_sub_admin("admin/users/appuser")){
            redirect("login");
            exit();
        }

        _set_back_url();
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->user_model->get(array("in"=> array("user_type_id"=>array(USER_COMPANY,USER_CUSTOMER))));
        $this->data["page_content"] = $this->load->view($this->controller."/appusers",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function customer(){
        if(!_is_admin() && !_is_sub_admin("admin/users/customer")){
            redirect("login");
            exit();
        }
        _set_back_url();
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->user_model->get(array("in"=> array("user_type_id"=>array(USER_CUSTOMER))));
        $this->data["page_content"] = $this->load->view($this->controller."/appusers",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function company(){
        if(!_is_admin() && !_is_sub_admin("admin/users/company")){
            redirect("login");
            exit();
        }
        _set_back_url();
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->user_model->get(array("in"=> array("user_type_id"=>array(USER_COMPANY))));
        $this->data["page_content"] = $this->load->view($this->controller."/appusers",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function waiting(){
        if(!_is_admin() && !_is_sub_admin("admin/users/waiting")){
            redirect("login");
            exit();
        }

        _set_back_url();
        $this->session->set_userdata("backurl",current_url());
        $this->data["waiting"] = true;
        $this->data["data"] = $this->user_model->get(array("status"=>"3","req_queue !="=>"0"));
        $this->data["page_content"] = $this->load->view($this->controller."/appusers",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/user_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function details($id){
        $id = _decrypt_val($id);
        if($id == NULL){
            return;
        }
        $this->data["data"] = $this->user_model->get_by_id($id);
        $address = $this->user_model->get_address($id);
        if(!empty($address)){
            $this->data["address"] = $address[0];
        }
        $this->data["page_content"] = $this->load->view($this->controller."/details",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function accpet_waiting($id){
        $id = _decrypt_val($id);
        if($id == NULL){
            return;
        }
        $update = array("status"=>"1","req_queue"=>"0");
        $this->common_model->data_update("users",$update,array("user_id"=>$id));
        echo  $this->message_model->action_mesage("update",_l("User Waiting"),true);
    }
    /**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);

        $row = $this->user_model->get_by_id($id);
        if(!empty($row)){
            $pk=$this->primary_key;
            $this->db->query("Insert into users_trash select * from users where user_id = $row->user_id");
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("User"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("User not available");
            echo json_encode($data);
        }
    }
	/**
	* add product category
	* @return add product category page
	*/
    public function add($type){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/users"));
			$this->data["fileupload"] = true;
            $field = $this->input->post();
            if($type == "planner"){
                $this->data["user_type_id"] = USER_PLANNER;
            }else if($type == "buyer"){
                $this->data["user_type_id"] = USER_BUYER;
            }
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* action add or edit product category
	* @return redirect to product category list
	*/
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        if(empty($post["id"])){
            $this->form_validation->set_rules('user_email', 'Email Address','trim|required|valid_email|is_unique[users.user_email]');
            $this->form_validation->set_message('is_unique', 'Email address is already register');
            $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

        }
        $this->form_validation->set_rules('user_firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('user_lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');

        $responce = array();
        if ($this->form_validation->run() == FALSE)
        {
            if($this->form_validation->error_string()!="")
            {
                _set_flash_message($this->form_validation->error_string(),"error");
            }
        }
        else
        {
            $add_data = array(
                "user_firstname"=>$post["user_firstname"],
                "user_lastname"=>$post["user_lastname"],
                "user_phone"=>$post["user_phone"],
                "user_type_id"=>$post["user_type_id"],
                "status"=>($post["status"] == "on") ? 1 : 0
            );

			if(isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0){
                $path = PROFILE_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['user_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["user_image"] = $file_name_temp;
            }

            $redirect = "customer";
            if($post["user_type_id"] == USER_PLANNER){
                $redirect = "planner";
            }else if($post["user_type_id"] == USER_BUYER){
                $redirect = "buyer";
            }

            if(!empty($post["id"])){

				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("User"),false);

                redirect($this->controller."/".$redirect);
            }else{
                $add_data["user_password"] = md5($post["user_password"]);
                $add_data["user_email"] = $post["user_email"];

                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("User"),false);

                redirect($this->controller."/".$redirect);
            }

        }
    }

	/**
	* edit product size
	* @return edit product size page
	*/
    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->user_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            $this->data["user_type_id"] = $field->user_type_id;
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/users"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

	/**
	* get category by id
	* @return category json
	*/
	public function get_category_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->user_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
    }

    /**
	* add product category
	* @return add product category page
	*/
    public function add_appuser(){
        $this->action_appuser();
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/users"));
        $this->data["fileupload"] = true;
        $field = $this->input->post();
        $this->data["user_type_id"] = USER_CUSTOMER;

        $this->data["field"] = $field;
        $this->data["page_content"] = $this->load->view($this->controller."/appuser/add",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/appuser/script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    public function edit_appuser($id){
        $id = _decrypt_val($id);
        $this->action_appuser();
        $field = $this->user_model->get_by_id($id);
        if(empty($field)){
            exit();
        }
        $addresss = $this->user_model->get_address($id);
        $address = $addresss[0];
        $field->house_no = $address->house_no;
        $field->add_on_house_no = $address->add_on_house_no;
        $field->postal_code = $address->postal_code;
        $field->street_name = $address->street_name;
        $field->area = $address->area;
        $field->city = $address->city;
        $field->municipality = $address->municipality;
        $field->latitude = $address->latitude;
        $field->longitude = $address->longitude;

        $this->data["user_type_id"] = $field->user_type_id;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/users"));
        $this->data["fileupload"] = true;
        $this->data["field"] = $field;
        $this->data["page_content"] = $this->load->view($this->controller."/appuser/add",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/appuser/script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    private function action_appuser(){
        $this->data["user_types"] = array( USER_CUSTOMER => _l("Customer"), USER_COMPANY => _l("Company") );

        $post = $this->input->post();
        $this->load->library('form_validation');
        if(empty($post["id"])){
            $this->form_validation->set_rules('user_email', 'Email Address','trim|required|valid_email|is_unique[users.user_email]');
            $this->form_validation->set_message('is_unique', 'Email address is already register');
            $this->form_validation->set_rules('user_password', 'Password', 'trim|required');

        }
        $this->form_validation->set_rules('user_firstname', 'First Name','trim|required');
        $this->form_validation->set_rules('user_lastname', 'Last Name','trim|required');
        $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');
        if(isset($post["user_type_id"]) && $post["user_type_id"] == USER_COMPANY){
            $this->form_validation->set_rules('user_company_name', 'Company Name','trim|required');
            $this->form_validation->set_rules('user_company_id', 'Comapny Id','trim|required');
        }
        $this->form_validation->set_rules('postal_code', 'Postal Code', 'trim|required');
        $this->form_validation->set_rules('house_no', 'House No', 'trim|required');

        $responce = array();
        if ($this->form_validation->run() == FALSE)
        {
            if($this->form_validation->error_string()!="")
            {
                _set_flash_message($this->form_validation->error_string(),"error");
            }
        }
        else
        {
            $postal_code = $post["postal_code"];
            $house_no = $post["house_no"];

            $this->load->library("postcodeapi");
            //$res_postal_code = json_decode( $this->postcodeapi->get($postal_code,$house_no));
            //if(!isset($res_postal_code->postcode))
            //{
            //    _set_flash_message( _l("Please provide valid postalcode and house no"),"error");
            //}else{
                $add_data = array(
                    "user_firstname"=>$post["user_firstname"],
                    "user_lastname"=>$post["user_lastname"],
                    "user_phone"=>$post["user_phone"],
                    "user_type_id"=>$post["user_type_id"],
                    "status"=>($post["status"] == "on") ? 1 : 0,
                    "is_email_verified"=>"1",
                    "is_mobile_verified"=>"1",
                    "registration_date"=>date(MYSQL_DATE_TIME_FORMATE),
                );

                if (isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0) {
                    $path = PROFILE_IMAGE_PATH;
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $this->load->library("imagecomponent");
                    $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['user_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image', 680, 200, $path, 'crop', true, $file_name_temp);
                    $add_data["user_image"] = $file_name_temp;
                }

                $redirect = "customer";
                if ($post["user_type_id"] == USER_PLANNER) {
                    $redirect = "planner";
                } elseif ($post["user_type_id"] == USER_BUYER) {
                    $redirect = "buyer";
                }
                /*
                $address = array(
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

                if (!empty($post["id"])) {
                    $id = _decrypt_val($post["id"]);
                    $this->common_model->data_update($this->table_name, $add_data, array($this->primary_key=>$id), true);
                    $this->common_model->data_update("user_address",$address,array("user_id"=>$id),true);
                    $this->message_model->action_mesage("update", _l("User"), false);

                    redirect($this->controller."/".$redirect);
                } else {
                    $add_data["user_password"] = md5($post["user_password"]);
                    $add_data["user_email"] = $post["user_email"];

                    $id = $this->common_model->data_insert($this->table_name, $add_data, true);
                    $address["user_id"] = $id;

                    $this->common_model->data_insert("user_address",$address,true);

                    $this->message_model->action_mesage("add", _l("User"), false);

                    redirect($this->controller."/".$redirect);
                }
            //}
        }
    }

}
