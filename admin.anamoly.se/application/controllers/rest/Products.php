<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Products extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("products_model");
        $this->load->model("productgroups_model");
    }
    function ids_post(){
        $ids = $this->post("ids");
        if($ids != NULL){
            $products = $this->products_model->get(array("in"=>array("products.product_id"=>explode(",",$ids)),"products.status"=>"1"));
            $this->response(array(
                RESPONCE => true,
                MESSAGE => _l("Products"),
                DATA => $products,
                CODE => CODE_SUCCESS
            ), REST_Controller::HTTP_OK);
        }
    }
    function search_post(){
        $cat_id = $this->post("category_id");
        $sub_cat_id = $this->post("sub_category_id");
        $product_group_id = $this->post("product_group_id");
        $product_id = $this->post("product_id");
        $ids = $this->post("ids");
        $filter["products.status"] = "1";

        $user_id = $this->post("user_id");
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
        }

        if($cat_id != NULL){
            $filter["product_groups.category_id"] = $cat_id;
        }
        if($sub_cat_id != NULL){
            $filter["product_groups.sub_category_id"] = $sub_cat_id;
        }
        if($product_group_id != NULL){
            $filter["product_groups.product_group_id"] = $product_group_id;
        }
        if($product_id != NULL){
            $filter["products.product_id"] = $product_id;
        }
        if($ids != NULL){
            $filter["in"] = array("products.product_id"=>explode(",",$ids));
        }
        $filter["product_groups.status"] = 1;
        $filter["categories.status"] = 1;
        $filter["sub_categories.status"] = 1;

        $products = $this->products_model->get($filter);
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Products"),
            DATA => $products,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function list_post(){
        $cat_id = $this->post("category_id");
        $sub_cat_id = $this->post("sub_category_id");
        if($cat_id == NULL && $sub_cat_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide category relation"),
                DATA => _l("Please provide category relation"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $filter = array();
        if($cat_id != NULL){
            $filter["product_groups.category_id"] = $cat_id;
        }
        if($sub_cat_id != NULL){
            $filter["product_groups.sub_category_id"] = $sub_cat_id;
        }
        $filter["product_groups.status"] = 1;
        $filter["categories.status"] = 1;
        $filter["sub_categories.status"] = 1;

        $groups = $this->productgroups_model->get($filter);

        $user_id = $this->post("user_id");
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
        }
        $filter["products.status"] = 1;
        $products = $this->products_model->get($filter);


        foreach($groups as $group){
            $product_array = array();
            foreach($products as $product){
                if($product->group_id == $group->product_group_id){
                    $product_array[] = $product;
                }
            }
            $group->products = $product_array;
        }

        $this->load->model("subcategories_model");
        $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$cat_id));

        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Products"),
                                        DATA => $groups,
                                        CODE => CODE_SUCCESS,
                                        "subcategories"=>$subcategories
                                    ), REST_Controller::HTTP_OK);
    }
    function details_post(){
        $product_id = $this->post("product_id");
        if($product_id == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide product identifire"),
                DATA => _l("Please provide product identifire"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $filter = array();
        $user_id = $this->post("user_id");
        if($user_id != NULL){
            $filter["cart_user_id"] = $user_id;
        }
        $product = $this->products_model->get_by_id($product_id,$filter);
        $product = $this->get_details($product);


        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Product Details"),
            DATA => $product,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function barcode_post(){
        $barcode = $this->post("barcode");
        if($barcode == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide barcode identifire"),
                DATA => _l("Please provide barcode identifire"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        //array("products.product_barcode"=>$barcode)
        $search = " products.product_barcode = '$barcode' or products.barcode_two = '$barcode' or products.barcode_three = '$barcode' or  products.barcode_four = '$barcode' or products.barcode_five = '$barcode'";
        $product = $this->products_model->get_by_id("",$search);
        if(empty($product)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! No product found with this barcode"),
                DATA => _l("Sorry! No product found with this barcode"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
        $product = $this->get_details($product);


        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Product Details"),
            DATA => $product,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function get_details($product){
        $product->product_desc_en = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_en),TRUE);
        $product->product_desc_nl = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_nl),TRUE);
        $product->product_desc_ar = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_ar),TRUE);
        $product->product_desc_tr = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_tr),TRUE);
        $product->product_desc_de = $message = $this->load->view('api/product_desc',array("desc"=>$product->product_desc_de),TRUE);
        $this->load->model("ingredients_model");
        $product->ingredients = $this->ingredients_model->get(array("in"=>array("ingredient_id"=>$product->product_ingredients)));
        $product->images = $this->products_model->get_images($product->product_id);
        return $product;
    }
    function offers_post(){
        $filter = array("product_discounts.discount >"=>"0","products.status"=>"1");
        $filter["categories.status"] = 1;
        $filter["sub_categories.status"] = 1;
        $filter["product_groups.status"] = 1;
        $offer_products = $this->products_model->get($filter);
    }
    function home_post(){

        $user_id = $this->post("user_id");
        $order_product_categories = array();
        if($user_id != NULL){
            $order_products = $this->products_model->get(array("user_id"=>$user_id,"cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"));
            $order_product_categories = $this->products_model->get_sub_categories(array("user_id"=>$user_id));
            foreach($order_product_categories as $category){
                $prods = [];
                foreach($order_products as $prod){
                    if($category->sub_category_id == $prod->sub_category_id){
                        unset($prod->product_desc_en);
                        unset($prod->product_desc_nl);
                        unset($prod->product_desc_ar);
                        unset($prod->product_desc_tr);
                        unset($prod->product_desc_de);
                        unset($prod->product_extra_en);
                        unset($prod->product_extra_nl);
                        unset($prod->product_extra_ar);
                        unset($prod->product_extra_tr);
                        unset($prod->product_extra_de);
                        unset($prod->created_at);
                        unset($prod->created_by);
                        unset($prod->modified_by);
                        $prods[] = $prod;
                    }
                }
                $category->products = $prods;
            }
        }

        $new_products = $this->products_model->get(array("products.is_new"=>"1","cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"));
        $new_prod_categories = $this->products_model->get_sub_categories(array("products.is_new"=>"1"));
        foreach($new_prod_categories as $category){
            $prods = [];
            foreach($new_products as $prod){
                if($category->sub_category_id == $prod->sub_category_id){
                        unset($prod->product_desc_en);
                        unset($prod->product_desc_nl);
                        unset($prod->product_desc_ar);
                        unset($prod->product_desc_tr);
                        unset($prod->product_desc_de);
                        unset($prod->product_extra_en);
                        unset($prod->product_extra_nl);
                        unset($prod->product_extra_ar);
                        unset($prod->product_extra_tr);
                        unset($prod->product_extra_de);
                        unset($prod->created_at);
                        unset($prod->created_by);
                        unset($prod->modified_by);
                    $prods[] = $prod;
                }
            }
            $category->products = $prods;
        }

        $offer_products = $this->products_model->get(array("or"=>array("product_discounts.discount !="=>"null", "product_offers.product_offer_id !="=>"null"),"cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1") );
        $offer_prod_categories = $this->products_model->get_sub_categories(array("or"=>array("product_discounts.discount !="=>"null", "product_offers.product_offer_id !="=>"null")) );
        foreach($offer_prod_categories as $category){
            $prods = [];
            foreach($offer_products as $prod){
                if($category->sub_category_id == $prod->sub_category_id){
                        unset($prod->product_desc_en);
                        unset($prod->product_desc_nl);
                        unset($prod->product_desc_ar);
                        unset($prod->product_desc_tr);
                        unset($prod->product_desc_de);
                        unset($prod->product_extra_en);
                        unset($prod->product_extra_nl);
                        unset($prod->product_extra_ar);
                        unset($prod->product_extra_tr);
                        unset($prod->product_extra_de);
                        unset($prod->created_at);
                        unset($prod->created_by);
                        unset($prod->modified_by);
                    $prods[] = $prod;
                }
            }
            $category->products = $prods;
        }


        $output_array = array();
        if(!empty($order_product_categories)){
            $output_array[] = array(
                "tag_name_en"=>"Favorite",
                "tag_name_ar"=>"المفضلة",
                "tag_name_nl"=>"Favorieten",
                "tag_name_tr"=>"Favorite",
                "tag_name_de"=>"Favorite",
                "products"=>$order_product_categories,
                "banners"=>array()
            );
        }
        $output_array[] = array(
            "tag_name_en"=>"Offers",
            "tag_name_ar"=>"عروض",
            "tag_name_nl"=>"bieden",
            "tag_name_tr"=>"Offers",
            "tag_name_de"=>"Offers",
            "products"=>$offer_prod_categories,
            "banners"=>array()
        );
        $output_array[] = array(
            "tag_name_en"=>"News",
            "tag_name_ar"=>"أخبار",
            "tag_name_nl"=>"Nieuws",
            "tag_name_tr"=>"News",
            "tag_name_de"=>"News",
            "products"=>$new_prod_categories,
            "banners"=>array()
        );



        $this->load->model("tags_model");
        $this->load->model("banners_model");
        $tags = $this->tags_model->get(array("tags.show_on_app_home"=>"1"));
        foreach($tags as $tag){
            $products = $this->products_model->get(array("products.product_tags"=>$tag->tag_id,"cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"));
            $products_categories = $this->products_model->get_sub_categories(array("products.product_tags"=>$tag->tag_id));
            foreach($products_categories as $category){
                $prods = [];
                foreach($products as $prod){
                    if($category->sub_category_id == $prod->sub_category_id){
                        unset($prod->product_desc_en);
                        unset($prod->product_desc_nl);
                        unset($prod->product_desc_ar);
                        unset($prod->product_desc_tr);
                        unset($prod->product_desc_de);
                        unset($prod->product_extra_en);
                        unset($prod->product_extra_nl);
                        unset($prod->product_extra_ar);
                        unset($prod->product_extra_tr);
                        unset($prod->product_extra_de);
                        unset($prod->created_at);
                        unset($prod->created_by);
                        unset($prod->modified_by);
                        $prods[] = $prod;
                    }
                }
                $category->products = $prods;
            }

            $banners = $this->banners_model->get(array("banners.tag_ids"=>$tag->tag_id));
            $output_array[] = array(
                "tag_name_en"=>$tag->tag_name_en,
                "tag_name_ar"=>$tag->tag_name_ar,
                "tag_name_nl"=>$tag->tag_name_nl,
                "tag_name_tr"=>$tag->tag_name_tr,
                "tag_name_de"=>$tag->tag_name_de,
                "products"=>$products_categories,
                "banners"=>$banners
            );

        }


        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Products"),
                                        DATA => $output_array,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
    function tabs_post(){
        $user_id = $this->post("user_id");
        if($user_id == NULL ){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("User referance required"),
                DATA => _l("User referance required"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }

        $this->db->where("user_id",$user_id);
        $q = $this->db->get("orders");
        $orders = $q->result();


        $output_array = array();
        if(!empty($orders)){
            $output_array[] = array(
                "tag_name_en"=>"Favorite",
                "tag_name_ar"=>"المفضلة",
                "tag_name_nl"=>"Favorieten",
                "tag_name_tr"=>"Favorite",
                "tag_name_de"=>"Favorite",
                "tag_ref"=>-1
            );
        }
        $output_array[] = array(
            "tag_name_en"=>"Offers",
            "tag_name_ar"=>"عروض",
            "tag_name_nl"=>"Acties",
            "tag_name_tr"=>"Offers",
            "tag_name_de"=>"Offers",
            "tag_ref"=>-2
        );
        /*
        $output_array[] = array(
            "tag_name_en"=>"News",
            "tag_name_ar"=>"أخبار",
            "tag_name_nl"=>"Nieuws",
            "tag_ref"=>-3
        );
        */

        $this->load->model("tags_model");
        $tags = $this->tags_model->get(array("tags.show_on_app_home"=>"1"));
        foreach($tags as $tag){
            $output_array[] = array(
                "tag_name_en"=>$tag->tag_name_en,
                "tag_name_ar"=>$tag->tag_name_ar,
                "tag_name_nl"=>$tag->tag_name_nl,
                "tag_name_tr"=>$tag->tag_name_tr,
                "tag_name_de"=>$tag->tag_name_de,
                "tag_ref"=>$tag->tag_id
            );
        }

        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("Tabs"),
            DATA => $output_array,
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
    function tabdata_post(){
            $user_id = $this->post("user_id");
            $tab_ref = $this->post("tab_ref");
            $webshop = $this->post("webshop");
            $this->load->model("banners_model");
            if($user_id == NULL || $tab_ref == NULL){
                $this->response(array(
                    RESPONCE => false,
                    MESSAGE => _l("Please provide tab ref"),
                    DATA => _l("Please provide tab ref"),
                    CODE => 100
                ), REST_Controller::HTTP_OK);
            }

            $output_products = array();
            $output_banners = array();

            $select_output = "products.product_id, products.product_name_en, products.product_name_ar, products.product_name_nl, products.product_name_tr, products.product_name_de, products.is_express, products.in_stock,products.price_vat_exclude, products.vat, products.vat, products.price, products.qty, products.unit, products.unit_ar, products.unit_en, products.unit_tr, products.unit_de, products.unit_value, products.price_note, products.product_barcode,products.product_image";

            if($tab_ref == -1){
                $order_product_categories = array();
                if($user_id != NULL){
                    $order_products = $this->products_model->get(array("user_id"=>$user_id,"cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"),"","","",$select_output);
                    $order_product_categories = $this->products_model->get_sub_categories(array("user_id"=>$user_id));
                    foreach($order_product_categories as $category){
                        $prods = [];
                        foreach($order_products as $prod){
                            if($category->sub_category_id == $prod->sub_category_id){

                                $prods[] = $prod;
                            }
                        }
                        $category->products = $prods;
                    }
                }
                $output_products = $order_product_categories;
                if($webshop != NULL){
                    $output_banners = $this->banners_model->get(array("banners.tag_ids"=>$tab_ref));
                }
            }else if($tab_ref == -3){
                $new_products = $this->products_model->get(array("products.is_new"=>"1","cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"),"","","",$select_output);
                $new_prod_categories = $this->products_model->get_sub_categories(array("products.is_new"=>"1"));
                foreach($new_prod_categories as $category){
                    $prods = [];
                    foreach($new_products as $prod){
                        if($category->sub_category_id == $prod->sub_category_id){

                            $prods[] = $prod;
                        }
                    }
                    $category->products = $prods;
                }
                $output_products = $new_prod_categories;
            }else if($tab_ref == -2){
                $offer_products = $this->products_model->get(array("or"=>"(product_discounts.discount IS NOT NULL or product_offers.product_offer_id IS NOT NULL)","cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1") ,"","","",$select_output);
                $offer_prod_categories = $this->products_model->get_sub_categories(array("or"=>"(product_discounts.discount IS NOT NULL or product_offers.product_offer_id IS NOT NULL )"));


                foreach($offer_prod_categories as $category){
                    $prods = [];
                    foreach($offer_products as $prod){
                        if($category->sub_category_id == $prod->sub_category_id){

                            $prods[] = $prod;
                        }
                    }
                    $category->products = $prods;
                }
                $output_products = $offer_prod_categories;
                if($webshop != NULL){
                    $output_banners = $this->banners_model->get(array("banners.tag_ids"=>$tab_ref));
                }
            }else{



                $this->load->model("banners_model");

                    $products = $this->products_model->get(array("products.product_tags"=>$tab_ref,"cart_user_id"=>$user_id,"products.status"=>"1","categories.status"=>"1","sub_categories.status"=>"1","product_groups.status"=>"1"),"","","",$select_output);
                    $products_categories = $this->products_model->get_sub_categories(array("products.product_tags"=>$tab_ref));
                    foreach($products_categories as $category){
                        $prods = [];
                        foreach($products as $prod){
                            if($category->sub_category_id == $prod->sub_category_id){

                                $prods[] = $prod;
                            }
                        }
                        $category->products = $prods;
                    }

                    $output_banners = $this->banners_model->get(array("banners.tag_ids"=>$tab_ref));
                    $output_products = $products_categories;


            }

            $output_array = array("products"=>$output_products,"banners"=>$output_banners);
            $this->response(array(
                                            RESPONCE => true,
                                            MESSAGE => _l("Products"),
                                            DATA => $output_array,
                                            CODE => CODE_SUCCESS
                                        ), REST_Controller::HTTP_OK);

    }
    function suggest_post(){
        $user_id = $this->post("user_id");
        $suggestion = $this->post("suggestion");

        if($user_id == NULL || $suggestion == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please provide required fields"),
                DATA => _l("Please provide required fields"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }
                $update_array = array("user_id"=>$user_id,"suggestion"=>$suggestion);
                $file_name = "";
                if(isset($_FILES["image"]) && $_FILES['image']['size'] > 0){
                    $path = SUGGESTION_IMAGE_PATH;

                    if(!file_exists($path)){
                        mkdir($path);
                    }
                    $this->load->library("imagecomponent");
                    $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['image']['name']);//md5(uniqid())."_".$_FILES['image']['name'];
                    $file_name = $this->imagecomponent->upload_image_and_thumbnail('image',680,200,$path ,'crop',false,$file_name_temp);
                    $update_array["image"] = $file_name;

                }
        $this->common_model->data_insert("product_suggest",$update_array);
        $this->response(array(
            RESPONCE => true,
            MESSAGE => _l("We received your suggesion, We will happy to provide this product to you soon"),
            DATA => _l("We received your suggesion, We will happy to provide this product to you soon"),
            CODE => CODE_SUCCESS
        ), REST_Controller::HTTP_OK);
    }
}
