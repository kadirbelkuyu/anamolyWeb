<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Search extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("productgroups_model");
    }
    function list_post(){
        $search = $this->post("search");
        if($search != NULL){

            $result = array();

            $this->db->select("'group' as s_type, product_group_id as s_type_id, group_name_en as search_en, group_name_ar as search_ar, group_name_nl as search_nl, group_name_tr as search_tr, group_name_de as search_de");
            //$this->db->or_like(array("group_name_en"=>$search,"group_name_nl"=>$search,"group_name_ar"=>$search));
            $this->db->where("(group_name_en like '%".$search."%' or group_name_nl like '%".$search."%' or group_name_ar like '%".$search."%' or group_name_tr like '%".$search."%' or group_name_de like '%".$search."%')");
            $this->db->where("draft","0");
            $this->db->where("status","1");
            $q = $this->db->get("product_groups");
            $product_groups = $q->result();
            if(!empty($product_groups))
                $result = array_merge($result,$product_groups);

            $this->db->select("'category' as s_type, category_id as s_type_id, cat_name_en as search_en, cat_name_ar as search_ar, cat_name_nl as search_nl, cat_name_tr as search_tr, cat_name_de as search_de");
            //$this->db->or_like(array("cat_name_en"=>$search,"cat_name_nl"=>$search,"cat_name_ar"=>$search));
            $this->db->where("(cat_name_en like '%".$search."%' or cat_name_nl like '%".$search."%' or cat_name_ar like '%".$search."%' or cat_name_tr like '%".$search."%' or cat_name_de like '%".$search."%')");
            $this->db->where("draft","0");
            $this->db->where("status","1");
            $q = $this->db->get("categories");
            $categories = $q->result();
            if(!empty($categories))
                $result = array_merge($result,$categories);

            $this->db->select("'sub_category' as s_type, sub_category_id as s_type_id, sub_cat_name_en as search_en, sub_cat_name_ar as search_ar, sub_cat_name_nl as search_nl, sub_cat_name_tr as search_tr, sub_cat_name_de as search_de");
            //$this->db->or_like(array("sub_cat_name_en"=>$search,"sub_cat_name_nl"=>$search,"sub_cat_name_ar"=>$search));
            $this->db->where("(sub_cat_name_en like '%".$search."%' or sub_cat_name_nl like '%".$search."%' or sub_cat_name_ar like '%".$search."%' or sub_cat_name_tr like '%".$search."%' or sub_cat_name_de like '%".$search."%')");
            $this->db->where("draft","0");
            $this->db->where("status","1");
            $q = $this->db->get("sub_categories");
            $sub_categories = $q->result();
            if(!empty($sub_categories))
                $result = array_merge($result,$sub_categories);

            

                $this->db->select("'product' as s_type, products.product_id as s_type_id, products.product_name_en as search_en, products.product_name_ar as search_ar, products.product_name_nl as search_nl, products.product_name_tr as search_tr, products.product_name_de as search_de");
                $this->db->join("product_maps","product_maps.product_id = products.product_id","left");
                $this->db->join("product_groups","product_groups.product_group_id = product_maps.group_id","left");   
                $this->db->join("categories","categories.category_id = product_maps.category_id","left");
                $this->db->join("sub_categories","sub_categories.sub_category_id = product_maps.sub_category_id","left");
        //$this->db->or_like(array("product_name_ar"=>$search,"product_name_en"=>$search,"product_name_nl"=>$search));
                $this->db->where("(products.product_name_en like '%".$search."%' or products.product_name_ar like '%".$search."%' or products.product_name_nl like '%".$search."%' or products.product_name_tr like '%".$search."%' or products.product_name_de like '%".$search."%' )");
                $this->db->where("products.draft","0");
                $this->db->where("products.status","1");
                $this->db->where("categories.status","1");
                $this->db->where("sub_categories.status","1");
                $this->db->where("product_groups.status","1");

                $q = $this->db->get("products");
                $products = $q->result();
                if(!empty($products))
                    $result = array_merge($result,$products);

            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Search"),
                DATA => $result,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }

}