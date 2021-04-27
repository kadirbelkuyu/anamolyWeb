<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Category extends Base_Controller
{
    private $data;
    public function index()
    {
        $this->load->library("apicall");
        $form_params = array("user_id" => _get_current_user_id());
        $res = $this->apicall->request("/rest/categories/list", $form_params);
        if ($res->responce) {
            $this->data["categories"] = $res->data;
        }
        $this->data["page_content"] = $this->load->view("webshop/category", $this->data, true);
        $this->data["page_script"] = $this->load->view("webshop/script/search", $this->data, true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
    }
    function subcat($cat_id)
    {
        $this->load->library("apicall");
        $form_params = array("category_id" => $cat_id);
        $res = $this->apicall->request("/rest/categories/subcat", $form_params);
        if ($res->responce) {
            $this->data["categories"] = $res->data;
        }
        $this->data["page_content"] = $this->load->view("webshop/sub_category", $this->
            data, true);
        $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
    }

    public function search()
    {
        $listitem="";
        if (isset($_POST)) {
            $this->load->library("apicall");
            $form_params = array("search" => $this->input->post("keyword"));

            $res = $this->apicall->request("/rest/search/list", $form_params);

            if (!empty($res) && $res->responce) {
                $listitem="<ul class='collection'>";
                 
                foreach ($res->data as $item) {
                    $link=site_url("products/productlist/".$item->s_type_id."/".$item->s_type);
                                        
                   $listitem=$listitem."<li class='collection-item'><a href=".$link.">".$item->search_en."</a></li>";
                   }
                   
            $listitem=$listitem."</ul>";
            }
        }
        
        echo $listitem;
    }
    function load_sub_cat(){
        $html = "<div class='sub-cat-grid'>";
        $html .= "<div class='row'>";
        $cat_id = $this->input->post("cat_id");
        $is_featured = $this->session->userdata("is_featured");
        foreach($is_featured as $featured){
            if($featured->category_id == $cat_id){
              foreach ($featured->subcategories as $key => $value) {
                  $html .= $this->load->view("webshop/views/sub_category_grid",array("sub_cat"=>$value),true);
              }
            }
        }
        $html .='</div>';
        $html .='</div>';
        echo $html;
    }
}
