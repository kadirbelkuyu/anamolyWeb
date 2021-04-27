<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Suggestions extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();
        $this->table_name = "product_suggest";
        $this->primary_key= "suggest_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_buyer() && !_is_sub_admin()){
            redirect("login");
            exit();
        }
		$this->load->model("suggestions_model");
       
        
    }

    public function index(){
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->suggestions_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/script",array(),true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->suggestions_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Suggestion"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Suggestion not available");
            echo json_encode($data);
        }
    }

    public function accept($id){
        $id = _decrypt_val($id);
        
        $row = $this->suggestions_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_update($this->table_name,array("status"=>"1"),array($this->primary_key=>$row->$pk));
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("update",_l("Suggestion"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Suggestion not available");
            echo json_encode($data);
        }
    }
	
	
}
