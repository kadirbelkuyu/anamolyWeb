<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Products extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "products";
        $this->primary_key= "product_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(_is_admin() || _is_buyer() || _is_sub_admin("admin/products")){

        }else{
            redirect("login");
            exit();
        }
		$this->load->model("products_model");
        $this->load->model("tags_model");
        $this->load->model("ingredients_model");
        $this->load->model("units_model");
        $this->load->model("vats_model");
       

        $this->data["units"] = $this->units_model->get(); //$this->config->item("units");
        $this->data["tags"] = $this->tags_model->get();
        $this->data["vats"] = $this->vats_model->get();
        $this->data["ingredients"] = $this->ingredients_model->get();
        
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
        $this->load->model("productgroups_model");
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");
        //$this->data["datatable_export"]=true;	
        $filter = array();
        $this->data["categories"] = $this->categories_model->get();
        $post = $this->input->get();
        $subcategories = array();
        if(isset($post["category_id"])){
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
        if(isset($post["f_express"])  && $post["f_express"] != NULL && $post["f_express"] != ""){
            $filter[$this->table_name.".is_express"] = $post["f_express"];
        }
        if(isset($post["f_nutritional"])  && $post["f_nutritional"] != NULL && $post["f_nutritional"] != ""){
            $filter[$this->table_name.".is_nutritional"] = $post["f_nutritional"];
        }
        $this->data["f_status"] = array("1"=>_l("Enable"),"0"=>_l("Disable"));
        $this->data["f_express"] = array("1"=>_l("Express"),"0"=>_l("None Express"));
        $this->data["f_nutritional"] = array("1"=>_l("Nutritional"),"0"=>_l("None Nutritional"));
        

        $this->data["field"] = $post;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/products"));
        $this->data["ajax_subcat"] = true;
        $this->data["ajax_group"] = true;
        $this->data["datepicker"] = true;
        $this->data["subcategories"] = $subcategories;
        $this->data["productgroups"] = $groups;
        //$this->data["data"] = $this->products_model->get($filter,"","","","",true);
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/list_script",$this->data,true);
            
        $this->load->view(BASE_TEMPLATE,$this->data);
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
        if(isset($post["product_group_id"])  && $post["product_group_id"] != NULL && $post["product_group_id"] != ""){
            $filter["product_maps.group_id"] = $post["product_group_id"];
        }
        if(isset($post["f_status"])  && $post["f_status"] != NULL  && $post["f_status"] != ""){
            $filter["products.status"] = $post["f_status"];
        }
        if(isset($post["f_express"])  && $post["f_express"] != NULL  && $post["f_express"] != ""){
            $filter["products.is_express"] = $post["f_express"];
        }
        if(isset($post["f_nutritional"]) && $post["f_nutritional"] != NULL  && $post["f_nutritional"] != ""){
            $filter["products.is_nutritional"] = $post["f_nutritional"];
        }
        $search=$post["search"]["value"];
		$start=$post["start"];
        $length=$post["length"];
        
        /*
        $columns = $post["columns"];
        foreach($columns as $key=>$column){
            if($column["orderable"]){
                if($key == 1){

                }
            }
        }
        */
        $ordering = "";
        if(isset($post["order"])){
            $order_column = $post["order"][0]["column"];
            $order_dir = $post["order"][0]["dir"];
            if($order_column == 1){
                $ordering = "products.product_name_nl ".$order_dir;
            }
            if($order_column == 2){
                $ordering = "products.product_barcode ".$order_dir;
            }
            if($order_column == 3){
                $ordering = "product_groups.group_name_nl ".$order_dir;
            }
            if($order_column == 6){
                $ordering = "products.price ".$order_dir;
            }
            if($order_column == 7){
                $ordering = "products.qty ".$order_dir;
            }
            if($order_column == 8){
                $ordering = "products.is_express ".$order_dir;
            }
            if($order_column == 9){
                $ordering = "products.status ".$order_dir;
            }
            if($order_column == 10){
                $ordering = "products.created_at ".$order_dir;
            }
            if($order_column == 11){
                $ordering = "finalstock ".$order_dir;
            }
        }

        $all = $this->products_model->get($filter,"","","","",true);
        $allSearch = $this->products_model->get($filter,$search,"","","",true);
        $data = $this->products_model->get($filter,$search,$start,$length,"",true,$ordering);
        $output = array();
        $count = 0;
        foreach($data as $dt){
            $e_product_id = _encrypt_val($dt->product_id);
            $count++;	
            $array = array();
            if ($dt->product_image!="" && file_exists(PRODUCT_IMAGE_PATH."/crop/small/".$dt->product_image)){ 
                $img =  "<img class='productImage' data-ref='$e_product_id' src='";
                    if(isset($dt->product_image) && $dt->product_image != ""){  
                        $img.= base_url(PRODUCT_IMAGE_PATH."/crop/small/".$dt->product_image); 
                    } 
                    $img .="' alt='"._l("Preview")."' height='50'/>";
                    $array[] =  $img;
            }else{
                $array[] =  "";
            }
            $array[] = $dt->product_name_nl;
            $array[] = $dt->product_barcode;
            $array[] = $dt->group_name_nl;
            $array[] = $dt->unit_value." ".$dt->unit;
            $array[] = $dt->vat."%";
            $array[] = MY_Controller::$site_settings["currency_symbol"]." ".$dt->price;
            $array[] = $dt->qty;
            $array[] = ($dt->is_express == 1)? '<a href="javascript:changeExpress(\'0\',\''.$dt->product_id.'\',\''.($count-1).'\')" id="ref_'.$dt->product_id.'"><span class="label label-success">'._l("Yes").'</span>' : '<a href="javascript:changeExpress(\'1\',\''.$dt->product_id.'\',\''.($count-1).'\')" id="ref_'.$dt->product_id.'"><span class="label label-default">'._l("No").'</span>';
            $array[] = ($dt->status == 1)? '<a href="javascript:changeStatus(\'0\',\''.$dt->product_id.'\',\''.($count-1).'\')" id="ref_'.$dt->product_id.'"><span class="label label-success">'._l("Enable").'</span></a>' : '<a href="javascript:changeStatus(\'1\',\''.$dt->product_id.'\',\''.($count-1).'\')" id="ref_'.$dt->product_id.'"><span class="label label-danger">'._l("Disable").'</span></a>';
            $array[] = date(DEFAULT_DATE_TIME_FORMATE,strtotime($dt->created_at));
            $array[] = $dt->finalstock;
            $action = '<div class="btn-group">
            <a href="javascript:addStock(\''.$e_product_id.'\',\''.$count.'\')" class="btn btn-primary btn-xs"><i class="fa fa-plus"></i></a>
            <a href="javascript:quickEdit(\''.$e_product_id.'\',\''.$count.'\')" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
                            <a href="javascript:quickGroup(\''.$e_product_id.'\',\''.$count.'\')" class="btn btn-primary btn-xs"><i class="fa fa-list"></i></a>
                            <a href="'.site_url("admin/products/edit/".$e_product_id).'" class="btn btn-success btn-xs"><i class="fa fa-edit"></i></a>';
                        if(_is_admin()){ 
                            $action .= '<a href="javascript:deleteProductRecord(\''.site_url("admin/products/delete/".$e_product_id).'\',\''.$count.'\')" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></a>';
                        } 
                        $action .= "</div>";
                        $array[] = $action;
            $array[] = "<input type='checkbox' name='del[$dt->product_id]' data-ref='".$dt->product_id."' class='del' />";
            $output[] = $array;
        }
        $draw=$post["draw"];
        echo json_encode(array("draw"=>$draw,"recordsTotal"=>count($all),"recordsFiltered"=>count($allSearch),"data"=>$output));
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->products_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete","Product",true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
            echo json_encode($data);
        }
    }
    function multipledelete(){
        $ids = $this->input->post("ids");
        $ids = explode(',',$ids);
        $responce = false;
        foreach ($ids as $id) {
            $row = $this->products_model->get_by_id($id);
            if (!empty($row)) {
                $pk=$this->primary_key;
                $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), false);
                $responce = true;
            } 
        }
        $data['responce'] = $responce ;
        echo json_encode($data);
    }
    function delete_image(){
        $id = _decrypt_val($this->input->post("id"));
        $row = $this->products_model->get_product_image_by_id($id);
        if(!empty($row)){
			unlink(PRODUCT_IMAGE_PATH."/".$row->product_image);
            unlink(PRODUCT_IMAGE_PATH."/crop/small/".$row->product_image);
            
            $this->common_model->data_remove("product_images",array("product_image_id"=>$id),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete","Product",true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
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
            $this->data["active_menu_link"] = array(site_url("admin/products"));
			$this->data["fileupload"] = true;
            $this->data["ckeditor"] = array("product_desc_en","product_desc_ar","product_desc_nl","product_desc_tr","product_desc_de");
            $field = $this->input->post();
            $this->data["field"] = $field;
            $this->data["page_script"] = $this->load->view($this->controller."/script",$this->data,true);
            
            $this->data["ajax_subcat"] = true;
            $this->data["ajax_group"] = true;
            $this->load->model("productgroups_model");
            $this->load->model("subcategories_model");
            $this->load->model("categories_model");
            
            if(isset($field["category_id"])){
                $this->data["subcategories"]=$this->subcategories_model->get(array('sub_categories.category_id'=>$field["category_id"]));       
            }
            if(isset($field["sub_category_id"])){
                $this->data["productgroups"]=$this->productgroups_model->get(array('product_groups.sub_category_id'=>$field["sub_category_id"]));       
            }
            
            
            $this->data["categories"] = $this->categories_model->get();

            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* action add or edit product category
	* @return redirect to product category list
	*/
    private function action(){
        $post = $this->input->post();
        $barcode = (isset($post["product_barcode"])) ? $post["product_barcode"] : "";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name_nl', 'Product Name', 'trim|required');
        //$this->form_validation->set_rules('product_desc_nl', 'Product Desc', 'trim|required');
        $this->form_validation->set_rules('price_vat_exclude', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
        $this->form_validation->set_rules('unit_value', 'Unit Value', 'trim|required');
        
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
            if($barcode != NULL && $barcode !="" ){
                $this->db->where("product_barcode",$barcode);
                if(!empty($post["id"])){
                    $this->db->where("product_id !=",_decrypt_val($post["id"]));
                }
                $q = $this->db->get("products");
                $row = $q->row();
                if(!empty($row)){
                    _set_flash_message(_l("Barcode already applied to another product."),"error");
                    return;
                }
            }
            

            $product_ingredients = "";
            if(isset($post["product_ingredients"])){
                $product_ingredients = implode(",",$post["product_ingredients"]);
            }
            
            $product_tags = "";
            if(isset($post["product_tags"])){
                $product_tags = implode(",",$post["product_tags"]);
            }
            
            $unit = $post["unit"];
            $this->db->where("unit_nl",$unit);
            $q = $this->db->get("units");
            $unit_data = $q->row();

            $price_vat_exclude = $post["price_vat_exclude"];
            if($price_vat_exclude <= 0){
                _set_flash_message(_l("Price should be above zero"),"error");
                return;
            }
            $price = $price_vat_exclude;
            $vat = $post["vat"];
            if($vat > 0){
                $vat_price = $vat * $price_vat_exclude / 100;
                $price = $price + $vat_price;
            }

            $add_data = array(
                "product_name_en"=>$post["product_name_en"],
                "product_name_ar"=>$post["product_name_ar"],
                "product_name_nl"=>$post["product_name_nl"],
                "product_name_tr"=>$post["product_name_tr"],
                "product_name_de"=>$post["product_name_de"],
                "product_desc_en"=>$post["product_desc_en"],
                "product_desc_ar"=>$post["product_desc_ar"],
                "product_desc_nl"=>$post["product_desc_nl"],
                "product_desc_tr"=>$post["product_desc_tr"],
                "product_desc_de"=>$post["product_desc_de"],
                "picker_note"=>$post["picker_note"],
                "is_express"=>(isset($post["is_express"]) && $post["is_express"] == "on") ? 1 : 0,
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0,
                "is_nutritional"=>(isset($post["is_nutritional"]) && $post["is_nutritional"] == "on") ? 1 : 0,
                "in_stock"=>"1",
                "product_ingredients"=>$product_ingredients,
                "product_tags"=>$product_tags,
                "price"=>$price,
                "vat"=>$vat,
                "price_vat_exclude"=>$price_vat_exclude,
                "qty"=>$post["qty"],
                "unit"=>$post["unit"],
                "unit_ar"=>$unit_data->unit_ar,
                "unit_en"=>$unit_data->unit_en,
                "unit_tr"=>$unit_data->unit_tr,
                "unit_de"=>$unit_data->unit_de,
                "unit_value"=>$post["unit_value"],
                "price_note"=>$post["price_note"],
                "product_barcode"=>$post["product_barcode"],
                "barcode_two"=>$post["barcode_two"],
                "barcode_three"=>$post["barcode_three"],
                "barcode_four"=>$post["barcode_four"],
                "barcode_five"=>$post["barcode_five"],

                "max_cart_qty"=>$post["max_cart_qty"],
            );
			
			/*,
                "product_extra_en"=>$post["product_extra_en"],
                "product_extra_ar"=>$post["product_extra_ar"],
                "product_extra_nl"=>$post["product_extra_nl"],
            */
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update","Product",false);
				$this->uploadImage($id);
                redirect($this->controller."/edit/".$post["id"]);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add","Product",false);
				$this->uploadImage($id);
                if(isset($post["product_group_id"]) && $post["product_group_id"] != NULL){
                    $insert = array(
                        "product_id"=>$id,
                        "category_id"=>$post["category_id"],
                        "sub_category_id"=>$post["sub_category_id"],
                        "group_id"=>$post["product_group_id"]
                    );
                    
                    $this->common_model->data_insert("product_maps",$insert,true);
                }
                redirect($this->controller."/add");
            }

        }
    }
    public function send_image(){
        $post = $this->input->post();
        $id = _decrypt_val($post["id"]);
        $array = $this->uploadImage($id);
        
        echo '<div class="col-md-6" style="height:120px; background:url('.base_url(PRODUCT_IMAGE_PATH."/crop/small/".$array["product_image"]).'); background-size: cover; background-position: center; background-repeat: no-repeat;"></div>';
    }
    function set_groups(){
        $post = $this->input->post();
        $id = _decrypt_val($post["id"]);
        $count = $post["count"];
        $insert = array(
            "product_id"=>$id,
            "category_id"=>$post["category_id"],
            "sub_category_id"=>$post["sub_category_id"],
            "group_id"=>$post["product_group_id"]
        );
        
        $id = $this->common_model->data_insert("product_maps",$insert,true);
        $dt = $this->products_model->get_map_by_id($id);
        $this->load->view("admin/products/row_groups",array("dt"=>$dt,"count"=>$count,"controller"=> $this->controller));
    }
	private function uploadImage($product_id){
            if(isset($_FILES["product_image"]) && $_FILES['product_image']['size'] > 0){
                $path = PRODUCT_IMAGE_PATH;
                if(!file_exists($path)){
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['product_image']['name']); // md5(uniqid())."_".$_FILES['product_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('product_image',840,200,$path ,'crop',false,$file_name_temp);
                
                $add_data = array("product_image"=>$file_name_temp);
                //$id = $this->common_model->data_insert("product_images",$add_data,true);
                $this->common_model->data_update("products",$add_data,array("product_id"=>$product_id));
                $json = array("product_id"=>$product_id,"product_image"=>$file_name_temp);
                
                return $json;
            }
    }
	/**
	* edit product size
	* @return edit product size page
	*/
    public function edit($id){
            $id = _decrypt_val($id);
            $this->action();
            $field = $this->products_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/products"));
			$this->data["fileupload"] = true;
            
            $this->data["ckeditor"] = array("product_desc_en","product_desc_ar","product_desc_nl","product_desc_tr","product_desc_de");
            $this->data["field"] = $field;
            
            $this->data["ajax_subcat"] = true;
            $this->data["ajax_group"] = true;
            $this->load->model("productgroups_model");
            $this->load->model("subcategories_model");
            $this->load->model("categories_model");
            
            if(isset($field->category_id)){
                $this->data["subcategories"]=$this->subcategories_model->get(array('sub_categories.category_id'=>$field->category_id));       
            }
            if(isset($field->sub_category_id)){
                $this->data["productgroups"]=$this->productgroups_model->get(array('product_groups.sub_category_id'=>$field->sub_category_id));       
            }
            
            
            $this->data["categories"] = $this->categories_model->get();
            $this->data["maps"] = $this->products_model->get_maps($id);

            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
        
            $this->data["page_script"] = $this->load->view($this->controller."/script",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	function settings($id){
        $id = _decrypt_val($id);
            $this->action();
            $field = $this->products_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/products"));
			$this->data["fileupload"] = true;
            $this->data["ajax_subcat"] = true;
            $this->data["ajax_group"] = true;
            
            $this->data["field"] = $field;
            $this->data["productupload"] = true;
            $this->data["images"] = $this->products_model->get_images($id);
        
            $this->load->model("productgroups_model");
            $this->load->model("subcategories_model");
            $this->load->model("categories_model");
        
            $this->data["categories"] = $this->categories_model->get();
            $this->data["maps"] = $this->products_model->get_maps($id);    
            $this->data["page_content"] = $this->load->view($this->controller."/settings",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
    /**
	* delete product relation
	* @param id for product category id
	* @return delete product category
	*/
    public function delete_map($id){
        $id = _decrypt_val($id);
        
        $row = $this->products_model->get_map_by_id($id);
        if(!empty($row)){
            $this->common_model->data_remove("product_maps",array("product_map_id"=>$row->product_map_id),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete","Product Map",true);
           
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
            echo json_encode($data);
        }
    }
	/**
	* get category by id
	* @return category json
	*/
	public function get_product_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->coupons_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
    
    
    public function quickedit($id){
        $id = _decrypt_val($id);
        $field = $this->products_model->get_by_id($id);
        if(empty($field)){
            exit();
        }
        $this->data["r_index"] = $this->input->post("r_index");
        $this->data["field"] = $field;
        
        $this->load->view($this->controller."/quick_edit",$this->data);
        $this->load->view($this->controller."/script",$this->data);
        
    }
	function quicksubmit(){
        header('Content-type: text/json');
        $responce = array();

        $post = $this->input->post();
        $barcode = (isset($post["product_barcode"])) ? $post["product_barcode"] : "";
        $this->load->library('form_validation');
        $this->form_validation->set_rules('product_name_nl', 'Product Name', 'trim|required');
        $this->form_validation->set_rules('price_vat_exclude', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('unit', 'Unit', 'trim|required');
        $this->form_validation->set_rules('unit_value', 'Unit Value', 'trim|required');
        
        $responce = array();
        if ($this->form_validation->run() == FALSE)
        {
            if($this->form_validation->error_string()!="")
            {
                $responce["response"] = false;
                $responce["data"] = _error_message($this->form_validation->error_string());
                echo json_encode($responce);
            }
        }
        else
        {
            if($barcode != NULL && $barcode !="" ){
                $this->db->where("product_barcode",$barcode);
                if(!empty($post["id"])){
                    $this->db->where("product_id !=",_decrypt_val($post["id"]));
                }
                $q = $this->db->get("products");
                $row = $q->row();
                if(!empty($row)){
                    $responce["response"] = false;
                    $responce["data"] = _error_message(_l("Barcode already applied to another product."));
                    echo json_encode($responce);
                    exit();
                }
            }
            

            
            
            $unit = $post["unit"];
            $this->db->where("unit_nl",$unit);
            $q = $this->db->get("units");
            $unit_data = $q->row();

            $price_vat_exclude = $post["price_vat_exclude"];
            if($price_vat_exclude <= 0){
                $responce["response"] = false;
                $responce["data"] = _error_message(_l("Price should be above zero"));
                echo json_encode($responce);
                    exit();
            }
            $price = $price_vat_exclude;
            $vat = $post["vat"];
            if($vat > 0){
                $vat_price = $vat * $price_vat_exclude / 100;
                $price = $price + $vat_price;
            }

            $add_data = array(
                "product_name_en"=>$post["product_name_en"],
                "product_name_ar"=>$post["product_name_ar"],
                "product_name_nl"=>$post["product_name_nl"],
                "product_name_tr"=>$post["product_name_tr"],
                "product_name_de"=>$post["product_name_de"],
                "picker_note"=>$post["picker_note"],
                "is_express"=>(isset($post["is_express"]) && $post["is_express"] == "on") ? 1 : 0,
                "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0,
                "is_nutritional"=>(isset($post["is_nutritional"]) && $post["is_nutritional"] == "on") ? 1 : 0,
                "in_stock"=>"1",
                "price"=>$price,
                "vat"=>$vat,
                "price_vat_exclude"=>$price_vat_exclude,
                "qty"=>$post["qty"],
                "unit"=>$post["unit"],
                "unit_ar"=>$unit_data->unit_ar,
                "unit_en"=>$unit_data->unit_en,
                "unit_tr"=>$unit_data->unit_tr,
                "unit_de"=>$unit_data->unit_de,
                "unit_value"=>$post["unit_value"],
                "price_note"=>$post["price_note"],
                "product_barcode"=>$post["product_barcode"],
                "barcode_two"=>$post["barcode_two"],
                "barcode_three"=>$post["barcode_three"],
                "barcode_four"=>$post["barcode_four"],
                "barcode_five"=>$post["barcode_five"],
                "max_cart_qty"=>$post["max_cart_qty"],
            );
			
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                
                $responce["response"] = true;
                $responce["data"] = $this->message_model->action_mesage("update","Product",true);
                $responce["prod"] = $this->products_model->get(array("products.product_id"=>$id));
                echo json_encode($responce);
                exit();
                
            }

        }
    }

    function quickgroup($id){
        $id = _decrypt_val($id);
        $field = $this->products_model->get_by_id($id);
        if(empty($field)){
            exit();
        }

        $this->load->model("productgroups_model");
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");
        
        if(isset($field->category_id)){
            $this->data["subcategories"]=$this->subcategories_model->get(array('sub_categories.category_id'=>$field->category_id));       
        }
        if(isset($field->sub_category_id)){
            $this->data["productgroups"]=$this->productgroups_model->get(array('product_groups.sub_category_id'=>$field->sub_category_id));       
        }
        
        
        $this->data["categories"] = $this->categories_model->get();
        $this->data["maps"] = $this->products_model->get_maps($id);

        
        $this->data["r_index"] = $this->input->post("r_index");
        $this->data["field"] = $field;
        
        $this->load->view($this->controller."/quick_groups",$this->data);
        
        $this->load->view($this->controller."/cat_script",$this->data);
    }

    public function change_status(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->products_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_update("products",array("status"=>$status),array("product_id"=>$id));
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
    public function change_express(){
        header('Content-Type: application/json');
        $post = $this->input->post();
        $id = $post["prod_id"];
        $status = $post["status"];
        $row = $this->products_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_update("products",array("is_express"=>$status),array("product_id"=>$id));
            $row->is_express = $status;
            $data['responce'] = true;
            $data['data'] = $row;
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = $this->controller." not available";
            echo json_encode($data);
        }
    }
    function imagepreview(){
        $post = $this->input->post();
        $id = _decrypt_val($post["prod_id"]);
        $row = $this->products_model->get_by_id($id);
        if(!empty($row)){
			$this->load->view("admin/products/image_preview",array("product"=>$row));
        }else{
            echo _error_message(_l("Product Not Found"));
        }
    }
    
 function addstock(){
        header('Content-type: text/json');
        $responce = array();

        $post = $this->input->post();        
        $this->load->library('form_validation');
        $this->form_validation->set_rules('qty', _l("Qty"), 'trim|required');
        $this->form_validation->set_rules('stockdate', _l("Date"), 'trim|required');
        
        $responce = array();
        if ($this->form_validation->run() == FALSE)
        {
            if($this->form_validation->error_string()!="")
            {
                $responce["response"] = false;
                $responce["data"] = _error_message($this->form_validation->error_string());
                echo json_encode($responce);
            }
        }
        else
        {          
            $add_data = array(
                "qty"=>$post["qty"],
                "stock_date"=>date(MYSQL_DATE_FORMATE,strtotime(trim($post["stockdate"])))             
            );
						
            if(!empty($post["pid"])){
                
				$id = _decrypt_val($post["pid"]);
                
                $add_data["product_id"] = $id;
                
                $sid = $this->common_model->data_insert("product_stock",$add_data,true);
                
                $responce["response"] = true;
                $responce["data"] = $this->message_model->action_mesage("add","Product Stock",true);
                $responce["prod"] = $this->products_model->get(array("products.product_id"=>$id));
                echo json_encode($responce);
                exit();                
            }
        }
    }
    
    
    
}