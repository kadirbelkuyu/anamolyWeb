<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Stocks extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "product_stock";
        $this->primary_key= "product_stock_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/stocks")){
            redirect("login");
            exit();
        }
        
        $this->load->model("products_model");    
        $this->load->model("productstocks_model");    
    }
    
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
        
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");       
        $filter = array();
        $this->data["categories"] = $this->categories_model->get();
        $post = $this->input->get();
        $subcategories = array();
        if(isset($post["category_id"])&& $post["category_id"] != NULL  && $post["category_id"] != ""){
            $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$post["category_id"]));
            $filter["product_maps.category_id"] = $post["category_id"];
        }
       
       if(isset($post["sub_category_id"])&& $post["sub_category_id"] != NULL  && $post["sub_category_id"] != ""){           
            $filter["product_maps.sub_category_id"] = $post["sub_category_id"];
        }
        
        $this->data["field"] = $post;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/stocks"));
        $this->data["ajax_subcat"] = true;       
        $this->data["subcategories"] = $subcategories;
        $this->data["products"] = $this->products_model->get($filter,"","","","",true);        
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/list_script",$this->data,true);
            
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    
    public function get_product_by_category_sub_category_id()
	{
		$cid=$this->input->post("category_id");
        $subid=$this->input->post("sub_category_id");
        
        $filter = array();
       
        if(isset($cid) && $cid != NULL  && $cid != ""){            
            $filter["product_maps.category_id"] = $cid;
        }
      
        if(isset($subid) && $subid != NULL  && $subid != ""){           
            $filter["product_maps.sub_category_id"] = $subid;
        }
        
		$data=$this->products_model->get($filter,"","","","",true);   
		echo json_encode($data);
	}
    
     function ajax_list(){
        $post = $this->input->post();
        $filter = array();
        if(isset($post["category_id"])  && $post["category_id"] != NULL  && $post["category_id"] != ""){
            $filter["product_maps.category_id"] = $post["category_id"];
        }
        if(isset($post["sub_category_id"]) && $post["sub_category_id"] != NULL  && $post["sub_category_id"] != ""){
            $filter["product_maps.sub_category_id"] = $post["sub_category_id"];
        }
        if(isset($post["product_id"])  && $post["product_id"] != NULL && $post["product_id"] != ""){
            $filter["product_stock.product_id"] = $post["product_id"];
        }
        
        $search=$post["search"]["value"];
		$start=$post["start"];
        $length=$post["length"];
       
        $ordering = "";
        if(isset($post["order"])){
            $order_column = $post["order"][0]["column"];
            $order_dir = $post["order"][0]["dir"];
            if($order_column == 0){
                $ordering = "products.product_name_nl ".$order_dir;
            }
            if($order_column == 1){
                $ordering = "product_stock.qty ".$order_dir;
            }
            if($order_column == 2){
                $ordering = "product_stock.stock_date ".$order_dir;
            }           
        }

        $all = $this->productstocks_model->get($filter,"","","");
        $allSearch = $this->productstocks_model->get($filter,$search,"","");
        $data = $this->productstocks_model->get($filter,$search,$start,$length,$ordering);
        $output = array();
        $count = 0;
        foreach($data as $dt){
            $e_product_stock_id = _encrypt_val($dt->product_stock_id);
            $count++;	
            $array = array();
            
            $array[] = $dt->product_name_nl;        
            $array[] = $dt->qty;
            $array[] = date(DEFAULT_DATE_FORMATE,strtotime($dt->stock_date));           
            $action = '<div class="btn-group">           
                            <a href="'.site_url("admin/stocks/edit/".$e_product_stock_id).'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>';
                        if(_is_admin()){ 
                            $action .= '<a href="javascript:deleteProductStockRecord(\''.site_url("admin/stocks/delete/".$e_product_stock_id).'\',\''.$count.'\')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>';
                        } 
                        $action .= "</div>";
                        $array[] = $action;
           
            $output[] = $array;
        }
        $draw=$post["draw"];
        echo json_encode(array("draw"=>$draw,"recordsTotal"=>count($all),"recordsFiltered"=>count($allSearch),"data"=>$output));
    }
    
     public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->productstocks_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete","Product Stock",true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
            echo json_encode($data);
        }
    }
    
     public function add(){
            $this->action();
            $this->data["select2"] = true;
            $this->data["datepicker"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/stocks"));		
            $this->data["field"] = $this->input->post();
            $this->data["products"] = $this->products_model->get(array(),"","","","",true);
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
        
        $this->form_validation->set_rules('product_id', _l("Product"), 'trim|required');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        $this->form_validation->set_rules('stock_date', _l("Date"), 'trim|required');
        
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
                "product_id"=>$post["product_id"],
                "qty"=>$post["qty"],
                "stock_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($post["stock_date"])))             
            );
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Product Stock"),false);
				
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Product Stock"),false);
				
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
            $field = $this->productstocks_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            
            $field->stock_date=date(DEFAULT_DATE_FORMATE,strtotime($field->stock_date));
            
			$this->data["select2"] = true;
            $this->data["datepicker"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/stocks"));		
            $this->data["field"] = $field;
            $this->data["products"] = $this->products_model->get(array(),"","","","",true);
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
    
    public function outofstocks(){
        _set_back_url();
        
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");       
        $filter = array();
        $this->data["categories"] = $this->categories_model->get();
        $post = $this->input->get();
        $subcategories = array();
        if(isset($post["category_id"])&& $post["category_id"] != NULL  && $post["category_id"] != ""){
            $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$post["category_id"]));
            $filter["product_maps.category_id"] = $post["category_id"];
        }
       
       if(isset($post["sub_category_id"])&& $post["sub_category_id"] != NULL  && $post["sub_category_id"] != ""){           
            $filter["product_maps.sub_category_id"] = $post["sub_category_id"];
        }
        
        $this->data["field"] = $post;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/stocks/outofstocks"));
        $this->data["ajax_subcat"] = true;       
        $this->data["subcategories"] = $subcategories;
        $stockalert=get_option("stock_alert");
        
        if(empty($stockalert))
        {
         $stockalert=20;   
        }
        
        $this->data["data"] = $this->productstocks_model->getoutofstocks($filter,$stockalert);        
        $this->data["page_content"] = $this->load->view($this->controller."/outofstock",$this->data,true);
       
        $this->load->view(BASE_TEMPLATE,$this->data);
    }

}