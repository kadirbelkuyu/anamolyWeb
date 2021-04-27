<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends Base_Controller
{
    private $data;
    public function index($cat_id, $sub_cat_id)
    {
        if (_is_user_login()) {
            $this->load->library("apicall");
            $form_params = array(
                "user_id" => _get_current_user_id(),
                "category_id" => $cat_id,
                "sub_category_id" => $sub_cat_id);
            $res = $this->apicall->request("/rest/products/list", $form_params);
            if ($res->responce) {
                $this->data["data"] = $res->data;
                $this->data["subcategories"] = $res->subcategories;
            }

            $this->data["page_content"] = $this->load->view("webshop/products", $this->data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }

    function details($product_id)
    {
        if (_is_user_login()) {
            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id(), "product_id" => $product_id);
            $res = $this->apicall->request("/rest/products/details", $form_params);
            if ($res->responce) {

                $this->data["data"] = $res->data;
                $this->data["page_content"] = $this->load->view("webshop/product_details", $this->
                    data, true);
                $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
            }

        } else {
            redirect("login");
        }
    }

    public function productlist($id, $type)
    {
        if (_is_user_login()) {
            $this->load->library("apicall");
            $form_params = array("user_id" => _get_current_user_id());

            if ($type == "product") {
                $form_params["product_id"] = $id;
            }

            if ($type == "category") {
                $form_params["category_id"] = $id;
            }

            if ($type == "sub_category") {
                $form_params["sub_category_id"] = $id;
            }

            if ($type == "group") {
                $form_params["product_group_id"] = $id;
            }

            $res = $this->apicall->request("/rest/products/search", $form_params);
            if ($res->responce) {
                $this->data["data"] = $res->data;
            }

            $this->data["page_content"] = $this->load->view("webshop/product_list", $this->
                data, true);
            $this->load->view(BASE_WEB_SHOTP_TEMPLATE, $this->data);
        } else {
            redirect("login");
        }
    }


    public function send()
    {
        if (_is_user_login()) {
            if (isset($_POST)) {

                $fileupload = array("name" => "image", "contents" =>null);
                $file_name="";

                if (isset($_FILES["fileUpload"])) {
                    $file_data = _do_upload("fileUpload", "./uploads/temp", "jpeg|jpg|png");
                    $file_name = $file_data["file_name"];

                    $this->load->library("apicall");
                    $fileupload = array("name" => "image", "contents" => fopen("./uploads/temp/" . $file_name,
                            'r'));
                }

                $userid = array("name" => "user_id", "contents" => _get_current_user_id());
                $suggestion = array("name" => "suggestion", "contents" => $this->input->post("suggest"));
                $form_params = array(
                    $userid,
                    $suggestion,
                    $fileupload);

                $res = $this->apicall->requestmultipart("/rest/products/suggest", $form_params);

                if ($res != null && $res->responce) {
                     if (isset($_FILES["fileUpload"])) {
                    unlink("./uploads/temp/" . $file_name);
                    }
                }

                header('Content-Type: application/json');
                echo json_encode($res);
            }
        }
    }

}
