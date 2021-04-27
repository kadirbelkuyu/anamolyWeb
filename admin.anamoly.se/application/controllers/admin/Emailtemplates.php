<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Emailtemplates extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "email_templates";
        $this->primary_key= "template_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
       if(!_is_admin() && !_is_sub_admin("admin/emailtemplates")){
            redirect("login");
            exit();
        }
        
		$this->load->model("emailtemplates_model");
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->emailtemplates_model->get();
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
        
        $row = $this->emailtemplates_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Email Template"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Template not available");
            echo json_encode($data);
        }
    }
	/**
	* add product category
	* @return add product category page
	*/
    public function add(){
            $this->action();
            $this->data["active_menu_link"] = array(site_url("admin/emailtemplates"));
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
        $lang = $this->input->get("lang");
        if($lang == NULL){
            $lang = "dutch";
        }
        $this->data["lang"] = $lang;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('email_subject', _l("Email Subject"), 'trim|required');
        $this->form_validation->set_rules('email_message', _l("Email Message"), 'trim|required');
        
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
            $save_lang = $post["lang"];
            
            switch($save_lang)
            {
                case "english":
                    $add_data = array(
                        "email_message_en"=>$post["email_message"],
                        "email_subject_en"=>$post["email_subject"]
                    );                
                break;
                
                case "turkish":
                    $add_data = array(
                        "email_message_tr"=>$post["email_message"],
                        "email_subject_tr"=>$post["email_subject"]
                    );                
                break;
                
                case "arabic":
                    $add_data = array(
                         "email_message_ar"=>$post["email_message"],
                         "email_subject_ar"=>$post["email_subject"]
                    );                
                break;
                
                case "german":
                    $add_data = array(
                         "email_message_de"=>$post["email_message"],
                         "email_subject_de"=>$post["email_subject"]
                    );                
                break;
                
                default:
                   $add_data = array(
                         "email_message"=>$post["email_message"],
                         "email_subject"=>$post["email_subject"]
                            );
                break;
            }
            
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Email Template"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Email Template"),false);
				
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
            $field = $this->emailtemplates_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            $lang = $this->data["lang"];
          
            switch($lang)
            {
                case "english":
                   $field->email_subject = $field->email_subject_en;
                    $field->email_message = $field->email_message_en;           
                break;
                
                case "turkish":
                     $field->email_subject = $field->email_subject_tr;
                    $field->email_message = $field->email_message_tr; 
                break;
                
                case "arabic":
                    $field->email_subject = $field->email_subject_ar;
                    $field->email_message = $field->email_message_ar;             
                break;
                
                case "german":
                    $field->email_subject = $field->email_subject_de;
                    $field->email_message = $field->email_message_de;           
                break;
               }
            
			$this->data["active_menu_link"] = array(site_url("admin/emailtemplates"));
			$this->data["field"] = $field;
            $this->data["ckeditor"] = array("email_message");
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_email_template_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->emailtemplates_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}
