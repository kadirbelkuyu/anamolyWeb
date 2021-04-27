<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Referralcodes extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "coupons_referral";
        $this->primary_key= "coupon_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/referralcodes")){
            redirect("login");
            exit();
        }
        
		$this->load->model("referralcodes_model");
        $this->load->model("user_model");
        
        $this->data["discount_types"] = $this->config->item("coupon_discount_type");
        $this->data["coupon_type"] = $this->config->item("coupon_type");
        $this->data["users"] = $this->user_model->get(array("users.status"=>"1","in"=>array("users.user_type_id"=>array(USER_CUSTOMER,USER_COMPANY))));
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->referralcodes_model->get();
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
        
        $row = $this->referralcodes_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Coupon"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Coupons not available");
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
            $this->data["active_menu_link"] = array(site_url("admin/referralcodes"));
			$this->data["daterangepicker"] = true;
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
        $this->form_validation->set_rules('coupon_code', _l("Coupon Code"), 'trim|required');
        $this->form_validation->set_rules('discount',_l("Discount"),'trim|required');
        $this->form_validation->set_rules('valid_days',_l("Validity"),'trim|required');
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
            $validity = explode("-",$post["validity"]);
            $users = "";
            if(isset($post["users"])){
                $users = implode(",",$post["users"]);
            }
            $add_data = array(
                "tnc_en"=>$post["tnc_en"],
                "tnc_ar"=>$post["tnc_ar"],
                "tnc_nl"=>$post["tnc_nl"],
                "tnc_tr"=>$post["tnc_tr"],
                "tnc_de"=>$post["tnc_de"],
                "coupon_code"=>$post["coupon_code"],
                "coupon_type"=>$post["coupon_type"],
                "multi_usage"=>(isset($post["multi_usage"]) && $post["multi_usage"] == "on") ? 1 : 0,
                "discount"=>$post["discount"],
                "discount_type"=>$post["discount_type"],
                "min_order_amount"=>$post["min_order_amount"],
                "max_discount_amount"=>$post["max_discount_amount"],
                "valid_days"=>$post["valid_days"],
                "maximum_usages"=>$post["maximum_usages"],
                "status"=>($post["status"] == "on") ? 1 : 0
            );
			
			
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Coupon"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Coupon"),false);
				
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
            $field = $this->referralcodes_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/referralcodes"));
			$this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_coupon_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->referralcodes_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}