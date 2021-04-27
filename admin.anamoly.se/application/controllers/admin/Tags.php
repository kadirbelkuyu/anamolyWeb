<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tags extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "tags";
        $this->primary_key= "tag_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_buyer() && !_is_sub_admin("admin/tags")){
            redirect("login");
            exit();
        }
        
		$this->load->model("tags_model");
    }
    

    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->tags_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }

    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->tags_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Tag"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Tags not available");
            echo json_encode($data);
        }
    }
	
    public function add(){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/tags"));
			$this->data["fileupload"] = true;
            $this->data["colorpickerjs"] = true;
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
       
        if (empty($post["id"])) {
            $this->form_validation->set_rules('tag_name_nl', _l("Tag Name"), 'trim|required|is_unique[tags.tag_name_nl]');
            $this->form_validation->set_message('is_unique', 'Tag Name is already exist');
          }
          else
          {
            $this->form_validation->set_rules('tag_name_nl', _l("Tag Name"), 'trim|required');
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
                "tag_name_en"=>$post["tag_name_en"],
                "tag_name_ar"=>$post["tag_name_ar"],
                "tag_name_nl"=>$post["tag_name_nl"],
                "tag_name_tr"=>$post["tag_name_tr"],
                "tag_name_de"=>$post["tag_name_de"],
                "show_on_app_home"=>($post["show_on_app_home"] == "on") ? 1 : 0
            );
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Tag"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Tag"),false);
				
                redirect($this->controller);
            }

        }
    }
	

    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->tags_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/tags"));
			$this->data["fileupload"] = true;
            $this->data["colorpickerjs"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

	public function get_tag_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->tags_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}
