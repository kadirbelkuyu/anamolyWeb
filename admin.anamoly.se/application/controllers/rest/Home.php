<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Home extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("categories_model");
        $this->load->model("subcategories_model");
        $this->load->model("banners_model");
    }
    function list_post(){
        $categories = $this->categories_model->get(array("categories.status"=>1));
        //$is_featured = $this->categories_model->get(array("categories.status"=>1,"is_featured"=>1));
        $subcategories = $this->subcategories_model->get(array("sub_categories.status"=>1));
        $is_featured = array();
        foreach($categories as $cat){
            $subcat_array = array();
            foreach($subcategories as $subcat){
                if($subcat->category_id == $cat->category_id){
                    $subcat_array[] = $subcat;
                }
            }
            $cat->subcategories = $subcat_array;
            if($cat->is_featured == 1){
              $is_featured[] = $cat;
            }
        }
        $banners = $this->banners_model->get();
        $data = array("categories"=>$categories,"banners"=>$banners,"is_featured"=>$is_featured);

        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("Categories"),
                                        DATA => $data,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
}
