<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Subcategories extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "sub_categories";
        $this->primary_key= "sub_category_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_buyer() && !_is_sub_admin("admin/subcategories")){
            redirect("login");
            exit();
        }
        
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
        //$this->data["datatable_export"]=true;
        $post = $this->input->get();	
        $this->data["field"] = $post;
        $filter = array();
        if(isset($post["category_id"]) && $post["category_id"] != NULL && $post["category_id"] != ""){
            $filter[$this->table_name.".category_id"] = $post["category_id"];
        }
        if(isset($post["f_status"]) && $post["f_status"] != NULL && $post["f_status"] != ""){
            $filter[$this->table_name.".status"] = $post["f_status"];
        }
        $this->data["f_status"] = array("1"=>_l("Enable"),"0"=>_l("Disable"));
        $this->data["data"] = $this->subcategories_model->get($filter);
        $this->data["select2"] = true;
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/subcategory_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->subcategories_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Sub Category"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Sub Categories not available");
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
            $this->data["active_menu_link"] = array(site_url("admin/subcategories"));
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
        $this->form_validation->set_rules('sub_cat_name_nl', _l("Sub Category Name"), 'trim|required');
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
                "sub_cat_name_en"=>$post["sub_cat_name_en"],
                "sub_cat_name_ar"=>$post["sub_cat_name_ar"],
                "sub_cat_name_nl"=>$post["sub_cat_name_nl"],
                "sub_cat_name_tr"=>$post["sub_cat_name_tr"],
                "sub_cat_name_de"=>$post["sub_cat_name_de"],
                "category_id"=>$post["category_id"],
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0
            );
			
			if(isset($_FILES["sub_cat_image"]) && $_FILES['sub_cat_image']['size'] > 0){
                $path = CATEGORY_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['sub_cat_image']['name']);//md5(uniqid())."_".$_FILES['sub_cat_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('sub_cat_image',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["sub_cat_image"] = $file_name_temp;
            }
            if(isset($_FILES["sub_cat_banner"]) && $_FILES['sub_cat_banner']['size'] > 0){
                $path = CATEGORY_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['sub_cat_banner']['name']);//md5(uniqid())."_".$_FILES['sub_cat_banner']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('sub_cat_banner',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["sub_cat_banner"] = $file_name_temp;
            }
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Sub Category"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Sub Category"),false);
				
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
            $field = $this->subcategories_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/subcategories"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_sub_category_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->subcategories_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
    /**
	* get sub category by categories
	* @return category json
	*/
	public function get_sub_category_by_category_id()
	{
		$id=$this->input->post("category_id");
		$data=$this->subcategories_model->get(array($this->table_name.'.category_id'=>$id));
		echo json_encode($data);
	}
	function updateOrder(){
        header('Content-type: text/json');
        $data = json_decode($this->input->post("data"));
        foreach($data as $dt){
            $this->db->update("sub_categories",array("sub_cat_sort_order"=>$dt->s_index),array("sub_category_id"=>_decrypt_val($dt->ref)));
        }
        echo json_encode($data);
    }
    public function change_status(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->subcategories_model->get_by_id($id);
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
