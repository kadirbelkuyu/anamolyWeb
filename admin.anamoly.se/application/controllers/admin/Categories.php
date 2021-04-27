<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Categories extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "categories";
        $this->primary_key= "category_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;

        if(!_is_admin() && !_is_buyer()  && !_is_sub_admin("admin/categories")){
            redirect("login");
            exit();
        }

		$this->load->model("categories_model");
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;
        $this->data["data"] = $this->categories_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/category_script",array(),true);

        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);

        $row = $this->categories_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Category"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Categories not available");
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
            $this->data["active_menu_link"] = array(site_url("admin/categories"));
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
        $this->form_validation->set_rules('cat_name_nl', _l("Category Name"), 'trim|required');
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
                "cat_name_en"=>$post["cat_name_en"],
                "cat_name_ar"=>$post["cat_name_ar"],
                "cat_name_nl"=>$post["cat_name_nl"],
                "cat_name_tr"=>$post["cat_name_tr"],
                "cat_name_de"=>$post["cat_name_de"],
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0,
                "is_featured"=>(isset($post["is_featured"]) && $post["is_featured"] == "on") ? 1 : 0
            );

			if(isset($_FILES["cat_image"]) && $_FILES['cat_image']['size'] > 0){
                $path = CATEGORY_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp =  $this->imagecomponent->getuniquefilename($_FILES['cat_image']['name']);//md5(uniqid())."_".$_FILES['cat_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('cat_image',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["cat_image"] = $file_name_temp;
            }
            if(isset($_FILES["cat_banner"]) && $_FILES['cat_banner']['size'] > 0){
                $path = CATEGORY_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['cat_banner']['name']);//md5(uniqid())."_".$_FILES['cat_banner']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('cat_banner',680,200,$path ,'crop',true,$file_name_temp);
                $add_data["cat_banner"] = $file_name_temp;
            }

            if(!empty($post["id"])){

				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Category"),false);

                redirect($this->controller);
            }else{

                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Category"),false);

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
            $field = $this->categories_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/categories"));
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
		$data=$this->categories_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}

    function updateOrder(){
        header('Content-type: text/json');
        $data = json_decode($this->input->post("data"));
        foreach($data as $dt){
            $this->db->update("categories",array("cat_sort_order"=>$dt->s_index),array("category_id"=>_decrypt_val($dt->ref)));
        }
        echo json_encode($data);
    }
	public function change_status(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->categories_model->get_by_id($id);
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
