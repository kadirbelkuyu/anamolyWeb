<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Offers extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "product_offers";
        $this->primary_key= "product_offer_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/offers")){
            redirect("login");
            exit();
        }
        
		$this->load->model("offers_model");
        $this->load->model("products_model");
        
        $this->data["offer_types"] = $this->config->item("offers_type");
        $this->data["products"] = $this->products_model->get();
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
        //$this->data["datatable_export"]=true;	
        $this->load->model("productgroups_model");
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");
        $filter = array();
        $this->data["categories"] = $this->categories_model->get();
        $post = $this->input->get();
        $subcategories = array();
        if(isset($post["category_id"]) && $post["category_id"] != NULL  && $post["category_id"] != ""){
            $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$post["category_id"]));
            $filter["product_maps.category_id"] = $post["category_id"];
        }
        $groups = array();
        if(isset($post["sub_category_id"]) && $post["sub_category_id"] != NULL  && $post["sub_category_id"] != ""){
            $groups = $this->productgroups_model->get(array("product_groups.sub_category_id"=>$post["sub_category_id"]));
            $filter["product_maps.sub_category_id"] = $post["sub_category_id"];
        }
        if(isset($post["product_group_id"])  && $post["product_group_id"] != NULL && $post["product_group_id"] != ""){
            $filter["product_maps.group_id"] = $post["product_group_id"];
        }
        if(isset($post["f_status"])  && $post["f_status"] != NULL && $post["f_status"] != ""){
            $filter[$this->table_name.".status"] = $post["f_status"];
        }
        if(isset($post["discount_types"])  && $post["discount_types"] != NULL && $post["discount_types"] != ""){
            $filter[$this->table_name.".offer_type"] = $post["discount_types"];
        }
      
        if(isset($post["date_range"])  && $post["date_range"] != NULL && $post["date_range"] != ""){
            $dates = explode("-",$post["date_range"]);

            $filter["product_offers.start_date >="] = date(MYSQL_DATE_FORMATE,strtotime(trim($dates[0])));
            $filter["product_offers.end_date <="] = date(MYSQL_DATE_FORMATE,strtotime(trim($dates[1])));
        }
        $this->data["f_status"] = array("1"=>_l("Published"),"0"=>_l("Pending"));

        $this->data["field"] = $post;
        $this->data["select2"] = true;
        $this->data["ajax_subcat"] = true;
        $this->data["ajax_group"] = true;
        $this->data["daterangepicker"] = true;
        $this->data["subcategories"] = $subcategories;
        $this->data["productgroups"] = $groups;

        $this->data["data"] = $this->offers_model->get($filter);
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/offer_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->offers_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Offers"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Offers not available");
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
            $this->data["active_menu_link"] = array(site_url("admin/offers"));
			$this->data["daterangepicker"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->data["page_script"] = $this->load->view($this->controller."/offer_script",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* action add or edit product category
	* @return redirect to product category list
	*/
    private function action(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_ids[]', _l("Product"), 'trim|required');     
        $this->form_validation->set_rules('offer_type',_l("Offer Type"),'trim|required');
        if (isset($post["offer_type"]) && $post["offer_type"] != "plusone") {
            $this->form_validation->set_rules('offer_discount', _l("Discount"), 'trim|required');
        }
        $this->form_validation->set_rules('validity',_l("Validity"),'trim|required');
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
            $product_ids = $post["product_ids"];
            $offer_type = $post["offer_type"];
            $offer_discount = $post["offer_discount"];
            if($offer_type == "plusone"){
                $offer_discount = 0;
            }
            $add_data = array(
                "offer_discount"=>$offer_discount,
                "offer_type"=>$offer_type,
                "number_of_products"=>$post["number_of_products"],
                "start_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($validity[0]))),
                "end_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($validity[1]))),
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0
            );
			
			
			
            if(!empty($post["id"])){
                $id = _decrypt_val($post["id"]);
                $this->db->delete("product_offers_map",array("product_offer_id"=>$id));
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Offers"),false);
				foreach($product_ids as $product_id){
                    $this->db->insert("product_offers_map",array("product_offer_id"=>$id,"product_id"=>$product_id));
                }
                redirect($this->controller);
            }else{
                foreach($product_ids as $product_id){
                    $this->db->where("product_offers_map.product_id",$product_id);
                    $this->db->join("product_offers_map","product_offers_map.product_offer_id = product_offers.product_offer_id");
                    $this->db->where("product_offers.start_date <=",date(MYSQL_DATE_FORMATE));
                    $this->db->where("product_offers.end_date >=",date(MYSQL_DATE_FORMATE));
                    $this->db->where("product_offers.draft","0");
                    $q = $this->db->get("product_offers");
                    $row = $q->result();
                    if(!empty($row)){
                        _set_flash_message(_l("There is currently active offer with this product"),"error");
                        return;
                    }
                    
                    $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                    foreach($product_ids as $product_id){
                        $this->db->insert("product_offers_map",array("product_offer_id"=>$id,"product_id"=>$product_id));
                    }
                    $this->message_model->action_mesage("add",_l("Offer"),false);
                    redirect($this->controller);
				
                
                }

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
            $field = $this->offers_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            

			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/offers"));
			$this->data["daterangepicker"] = true;
            $field->validity = date("m/d/Y",strtotime($field->start_date))." - ".date("m/d/Y",strtotime($field->end_date));
            $this->db->where("product_offers_map.product_offer_id",$id);
            $q = $this->db->get("product_offers_map");
            $field->product_ids = array_column($q->result(), 'product_id') ;
            
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->data["page_script"] = $this->load->view($this->controller."/offer_script",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_coupon_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->coupons_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	public function change_status(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->offers_model->get_by_id($id);
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