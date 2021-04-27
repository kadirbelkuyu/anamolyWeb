<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Productgroups extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "product_groups";
        $this->primary_key= "product_group_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_buyer() && !_is_sub_admin("admin/productgroups")){
            redirect("login");
            exit();
        }
        
        $this->load->model("productgroups_model");
		$this->load->model("subcategories_model");
        $this->load->model("categories_model");
        
        $this->data["categories"] = $this->categories_model->get();
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
        $post = $this->input->get();	
        $this->data["field"] = $post;
        $filter = array();
        $subcategories = array();
        if(isset($post["category_id"]) && $post["category_id"] != NULL && $post["category_id"] != ""){
            $filter[$this->table_name.".category_id"] = $post["category_id"];
            $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$post["category_id"]));
            
        }
        if(isset($post["sub_category_id"]) && $post["sub_category_id"] != NULL && $post["sub_category_id"] != ""){
            $filter[$this->table_name.".sub_category_id"] = $post["sub_category_id"];
        }
        if(isset($post["f_status"]) && $post["f_status"] != NULL && $post["f_status"] != ""){
            $filter[$this->table_name.".status"] = $post["f_status"];
        }
        $this->data["f_status"] = array("1"=>_l("Enable"),"0"=>_l("Disable"));
        $this->data["ajax_subcat"] = true;
        $this->data["subcategories"] = $subcategories;
        $this->data["select2"] = true;
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->productgroups_model->get($filter);
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/productgroup_script",array(),true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->productgroups_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Group"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Groups not available");
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
            $this->data["active_menu_link"] = array(site_url("admin/productgroups"));
			$this->data["fileupload"] = true;
            $this->data["ajax_subcat"] = true;
            $field = $this->input->post();
            $this->data["field"] = $field;
            if(isset($field["category_id"]))
            $this->data["subcategories"] = $this->subcategories_model->get(array("sub_categories.category_id"=>$field->category_id));
        
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
        $this->form_validation->set_rules('group_name_nl', _l("Group Name"), 'trim|required');
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
                "group_name_en"=>$post["group_name_en"],
                "group_name_ar"=>$post["group_name_ar"],
                "group_name_nl"=>$post["group_name_nl"],
                "group_name_tr"=>$post["group_name_tr"],
                "group_name_de"=>$post["group_name_de"],
                "category_id"=>$post["category_id"],
                "sub_category_id"=>$post["sub_category_id"],
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0
            );
			
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Group"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Group"),false);
				
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
            $field = $this->productgroups_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/productgroups"));
			$this->data["fileupload"] = true;
            $this->data["ajax_subcat"] = true;
            $this->data["field"] = $field;
            
            if(isset($field->category_id))
            $this->data["subcategories"] = $this->subcategories_model->get(array("sub_categories.category_id"=>$field->category_id));
        
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get group by id
	* @return category json
	*/
	public function get_group_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->productgroups_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
    public function get_by_sub_category_id()
	{
		$id=$this->input->post("sub_category_id");
		$data=$this->productgroups_model->get(array($this->table_name.'.sub_category_id'=>$id));
		echo json_encode($data);
	}
    public function change_status(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->productgroups_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_update($this->table_name,array($this->table_name.".status"=>$status),array($this->table_name.".".$pk=>$id));
            $row->status = $status;
            $data['responce'] = true;
            $data['data'] = $row;
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
            echo json_encode($data);
        }
    }
	
}
