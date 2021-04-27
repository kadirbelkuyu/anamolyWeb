<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Banners extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "banners";
        $this->primary_key= "banner_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;

        if(!_is_admin()  && !_is_sub_admin("admin/banners")){
            redirect("login");
            exit();
        }
		$this->load->model("banners_model");
        $this->load->model("subcategories_model");
        $this->load->model("tags_model");

        $tags = $this->tags_model->get();
        $tags[] = (object)array("tag_name_nl"=>"Favorites","tag_id"=>"-1");
        $tags[] = (object)array("tag_name_nl"=>"Offers","tag_id"=>"-2");
        $this->data["tags"] = $tags;

        $this->data["subcategories"] = $this->subcategories_model->get();

    }

    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;
        $this->data["data"] = $this->banners_model->get();
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

        $row = $this->banners_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Banner"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Banners not available");
            echo json_encode($data);
        }
    }

    public function add(){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/banners"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('banner_title_nl', _l("Banner Title"), 'trim|required');
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
            $tag_ids = implode(",",$post["tag_ids"]);
            $sub_cat_ids = implode(",",$post["sub_cat_ids"]);


            $add_data = array(
                "banner_title_nl"=>$post["banner_title_nl"],
                "banner_title_ar"=>$post["banner_title_ar"],
                "banner_title_en"=>$post["banner_title_en"],
                "banner_title_tr"=>$post["banner_title_tr"],
                "banner_title_de"=>$post["banner_title_de"],
                "tag_ids" => $tag_ids,
                "sub_cat_ids" => $sub_cat_ids
            );

			if(isset($_FILES["banner_image"]) && $_FILES['banner_image']['size'] > 0){
                $path = BANNER_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['banner_image']['name']); //md5(uniqid())."_".$_FILES['banner_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('banner_image',840,200,$path ,'crop',false,$file_name_temp);
                $add_data["banner_image"] = $file_name_temp;
            }


            if(!empty($post["id"])){

				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Banner"),false);

                redirect($this->controller);
            }else{

                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Banner"),false);

                redirect($this->controller);
            }

        }
    }

    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->banners_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/banners"));
			$this->data["fileupload"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

	public function get_category_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->banners_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}


}
