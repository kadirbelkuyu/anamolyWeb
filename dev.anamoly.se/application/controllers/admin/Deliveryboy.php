<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Deliveryboy extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "delivery_boy";
        $this->primary_key= "delivery_boy_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/deliveryboy")){
            redirect("login");
            exit();
        }
        
        $this->load->model("deliveryboy_model");
        $this->load->model("vehicle_model");
        $this->data["vehicles"] = $this->vehicle_model->get();
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->deliveryboy_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->deliveryboy_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Vehicle"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Vehicle not available");
            echo json_encode($data);
        }
    }
	/**
	* add product category
	* @return add product category page
	*/
    public function add(){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/vehicle"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $this->input->post();
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
        $this->form_validation->set_rules('vehicle_id', _l("Vehicle No"), 'trim|required');
        $this->form_validation->set_rules('boy_name', _l("Delivery Boy"), 'trim|required');
        if(!empty($post["id"])){
            
        }else{
            $this->form_validation->set_rules('boy_password', _l("Delivery Boy"), 'trim|required');
            $this->form_validation->set_rules('boy_phone', _l("Boy Phone"), 'trim|required|is_unique[delivery_boy.boy_phone]');
            $this->form_validation->set_message('is_unique', 'Delivery Boy Phone Must be Unique');
        }
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
                "vehicle_id"=>$post["vehicle_id"],
                "boy_name"=>$post["boy_name"],
                "boy_email"=>$post["boy_email"],
                "boy_id_proof"=>$post["boy_id_proof"]
            );
			if(isset($_FILES["boy_photo"]) && $_FILES['boy_photo']['size'] > 0){
                $path = PROFILE_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp =  $this->imagecomponent->getuniquefilename($_FILES['boy_photo']['name']);//md5(uniqid())."_".$_FILES['cat_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('boy_photo',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["boy_photo"] = $file_name_temp;
            }
			if(isset($_FILES["id_photo"]) && $_FILES['id_photo']['size'] > 0){
                $path = PROFILE_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp =  $this->imagecomponent->getuniquefilename($_FILES['id_photo']['name']);
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('id_photo',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["id_photo"] = $file_name_temp;
            }
            if(isset($_FILES["licence_photo"]) && $_FILES['licence_photo']['size'] > 0){
                $path = PROFILE_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp =  $this->imagecomponent->getuniquefilename($_FILES['licence_photo']['name']);
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('licence_photo',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["licence_photo"] = $file_name_temp;
            }
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Delivery Boy"),false);
				
                redirect($this->controller);
            }else{
                $add_data["boy_phone"]=$post["boy_phone"];
                $add_data["boy_password"]=md5($post["boy_password"]);
                
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Delivery Boy"),false);
				
                redirect($this->controller);
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
            $field = $this->deliveryboy_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/deliveryboy"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	public function login($id){
        $id = _decrypt_val($id);
        $field = $this->deliveryboy_model->get_by_id($id);
        if(empty($field)){
            exit();
        }
        
            $post = $this->input->post();
            if(isset($post["boy_phone"])){
            $this->load->library('form_validation');
            if($field->boy_phone != $post["boy_phone"]){
                $this->form_validation->set_rules('boy_phone', _l("Boy Phone"), 'trim|required|is_unique[delivery_boy.boy_phone]');
                $this->form_validation->set_message('is_unique', 'Delivery Boy Phone Must be Unique');    
            }else{
                $this->form_validation->set_rules('boy_phone', _l("Boy Phone"), 'trim|required');
            }
               
            $responce = array();
            if ($this->form_validation->run() == FALSE)
            {
                if($this->form_validation->error_string()!="")
                {
                    _set_flash_message($this->form_validation->error_string(),"error");
                }
            }else{
                $update_array = array("boy_phone"=>$post["boy_phone"]);
                if($post["boy_password"] != "" && $post["boy_password"] != $field->boy_password){
                    $update_array["boy_password"] = md5($post["boy_password"]);
                }
                $this->common_model->data_update($this->table_name,$update_array,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Delivery Boy"),false);
				
                redirect($this->controller);
            }
        }
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/deliveryboy"));
        $this->data["field"] = $field;
        $this->data["page_content"] = $this->load->view($this->controller."/login",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* get category by id
	* @return category json
	*/
	public function get_deliveryboy_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->deliveryboy_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}
