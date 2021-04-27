<?php defined('BASEPATH') or exit('No direct script access allowed');
class Report extends MY_Controller
{
    protected $controller;
    protected $data;

    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/" . $this->router->fetch_class();
        $this->data["controller"] = $this->controller;

        if (!_is_admin() && !_is_sub_admin()) {
            redirect("login");
            exit();
        }
        $this->load->model("user_model");
        $this->load->model("report_model");
        $this->data["date_type"] = array(
            "Per Day",
            "Per Week",
            "Per Month",
            "Per Quater",
            "Per Year");
         
        $this->data["order_status"] = array(
            "Pending",
            "Confirmed",
            "Out Of Delivery",
            "Delivered",
            "Decline",
            "Cancel",
            "Un Paid",
            "Paid");
    }

    public function user()
    {
        $date = date("Y-m-d");
        $last_seven_date = date('Y-m-d', strtotime('-7 day', strtotime($date)));

        $this->data["new_user"] = $this->user_model->count(array("registration_date >="=>$last_seven_date));
        $this->data["active_users"] = $this->user_model->count(array("status"=>"1","in"=>array("user_type_id"=>array(USER_CUSTOMER,USER_COMPANY))));
        $this->data["companies_users"] = $this->user_model->count(array("status"=>"1","user_type_id"=>USER_COMPANY));
        $this->data["customer_users"] = $this->user_model->count(array("status"=>"1","user_type_id"=>USER_CUSTOMER));
        
        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $date_range = "";
        if ($this->input->post("date_range") != null) {
            $date_range = $this->input->post("date_range");
        }

        $date_type = "";
        if ($this->input->post("date_type") != null) {
            $date_type = $this->input->post("date_type");
        }

        $chart_array = $this->report_model->getUserReport($date_range, $date_type);

        $days_str = '';
        $person_str = '';
        $company_str = '';

        foreach ($chart_array as $days) {
            $days_str = $days_str . "'" . $days->Day . "',";
            $person_str = $person_str . $days->CustomerCount . ',';
            $company_str = $company_str . $days->CompanyCount . ',';
        }

        $this->data["chart_label"] = trim($days_str, ',');
       
        $final_array = array();

        $array = array();
        $array["label"] ="Companies";
        $array["backgroundColor"] = "rgba(60,141,188,0.9)";
        $array["borderColor"] = "rgba(60,141,188,0.8)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#3b8bba";
        $array["pointStrokeColor"] = "rgba(60,141,188,1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(60,141,188,1)";
        $array["data"] = "[". trim($company_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Persons";
        $array["backgroundColor"] = "rgba(210, 214, 222, 1)";
        $array["borderColor"] = "rgba(210, 214, 222, 0.9)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "rgba(210, 214, 222, 1.1)";
        $array["pointStrokeColor"] = "#c1c7d1";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(220,220,220,1)";
        $array["data"] = "[". trim($person_str, ',')."]";
        $final_array[] = $array;
        
        $this->data["chart_array"] = $final_array;

        $this->data["page_content"] = $this->load->view($this->controller . "/user", $this->
            data, true);
        $this->data["page_script"] = $this->load->view($this->controller .
            "/user_script", $this->data, true);

        
        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    public function product()
    {
        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $this->load->model("products_model");
        $this->data["active_products"] = $this->products_model->count(array("status"=>"1"));
        $this->data["inactive_products"] = $this->products_model->count(array("status"=>"0"));
        
        $this->data["recent_products"] = $this->products_model->get(array(),"","0","6");

        $filter = "";
        if ($this->input->post("date_range") != null) {
            $date_range = $this->input->post("date_range");
            $dates = explode(" - ", $date_range);
            if (count($dates) == 2) {
                $filter = " products.created_at >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                    ($dates[0]))) . " 00:00:00' and products.created_at <= '" . date(MYSQL_DATE_FORMATE,
                    strtotime(trim($dates[1]))) . " 23:59:59'";
            }
        }

        $final_array = array();

        $this->db->where("draft", "0");
        $this->db->where("products.product_barcode!=''");
        if ($filter != "") {
            $this->db->where($filter);
        }

        $count_products = $this->db->count_all_results("products");
        $this->data["barcode_products"] = $count_products;

        $array = array();
        $array["value"] = $count_products;
        $array["color"] = "#f56954";
        $array["highlight"] = "#f56954";
        $array["label"] = "Total Products With Barcode";
        $final_array[] = $array;

        $this->db->where("draft", "0");
        $this->db->where("products.product_ingredients!=''");
        if ($filter != "") {
            $this->db->where($filter);
        }

        $count_products = $this->db->count_all_results("products");
        $this->data["ingredients_products"] = $count_products;
        
        $array = array();
        $array["value"] = $count_products;
        $array["color"] = "#00a65a";
        $array["highlight"] = "#00a65a";
        $array["label"] = "Total Products With Ingredients";
        $final_array[] = $array;

        $this->db->where("draft", "0");
        $this->db->where("is_nutritional", "1");
        if ($filter != "") {
            $this->db->where($filter);
        }

        $count_products = $this->db->count_all_results("products");
        $this->data["nutritional_products"] = $count_products;
        
        $array = array();
        $array["value"] = $count_products;
        $array["color"] = "#f39c12";
        $array["highlight"] = "#f39c12";
        $array["label"] = "Total Products With Nutritional Value";
        $final_array[] = $array;

        $this->db->where("draft", "0");
        $this->db->where("is_express", "1");
        if ($filter != "") {
            $this->db->where($filter);
        }

        $count_products = $this->db->count_all_results("products");
        $this->data["express_products"] = $count_products;
        
        $array = array();
        $array["value"] = $count_products;
        $array["color"] = "#00c0ef";
        $array["highlight"] = "#00c0ef";
        $array["label"] = "Total Products Is Express (Boolean)";
        $final_array[] = $array;

        $this->db->where("draft", "0");
        $this->db->where("products.product_desc_nl!=''");
        if ($filter != "") {
            $this->db->where($filter);
        }

        $count_products = $this->db->count_all_results("products");
        $this->data["description_products"] = $count_products;
        
        $array = array();
        $array["value"] = $count_products;
        $array["color"] = "#3c8dbc";
        $array["highlight"] = "#3c8dbc";
        $array["label"] = "Total Products With Description";
        $final_array[] = $array;

        $this->data["chart_array"] = $final_array;

        $this->data["page_content"] = $this->load->view($this->controller . "/product",
            $this->data, true);
        $this->data["page_script"] = $this->load->view($this->controller .
            "/product_script", $this->data, true);

        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    public function order()
    {

        /**
         * All Count Boxes
         */
        $this->load->model("orders_model");
        $orders_with_status = $this->orders_model->order_counts_with_status();
        $data["order_with_status"] = $orders_with_status;

        $this->db->where("draft","0");
        $data["count_orders"] = $this->db->count_all_results("orders");

        
        $this->db->where("draft","0");
        $this->db->where_in("status",array(ORDER_PENDING,ORDER_CONFIRMED,ORDER_OUT_OF_DELIVEY));
        $this->db->where("is_express","1");
        $data["count_orders_express"] = $this->db->count_all_results("orders");

        $this->db->where("draft","0");
        $this->db->where("status",ORDER_UNPAID);
        $data["count_orders_unpaid"] = $this->db->count_all_results("orders");
        

        $this->load->model("deliveryboy_model");
        $data["deliveryboys"] = $this->deliveryboy_model->get();
        $orders = $this->orders_model->get(array("in"=>
        array("orders.status"=>
            array(
                ORDER_PENDING,
                ORDER_CONFIRMED,
                ORDER_OUT_OF_DELIVEY,
                ORDER_PAID,
                ORDER_DECLINE,
                ORDER_DELIVERED,
                ORDER_CANCEL
            )
        ),"DATE(orders.order_date)"=>date(MYSQL_DATE_FORMATE)));
        $data["data"] = $orders;
        $admin_dashboard = $this->load->view("admin/dashboard",$data,true).$this->load->view("admin/orders/list",$data,true);
         /**
          * End All Counts
          */

        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $date_range = "";
        if ($this->input->post("date_range") != null) {
            $date_range = $this->input->post("date_range");
        }

        $date_type = "";
        if ($this->input->post("date_type") != null) {
            $date_type = $this->input->post("date_type");
        }

        $chart_array = $this->report_model->getOrderReport($date_range, $date_type);

        $days_str = '';
        $order_str = '';
        $pending_str = '';
        $confirm_str='';
        $ondelivery_str = '';
        $express_str = '';
        $deliver_str='';
        $unpaid_str='';
        
        foreach ($chart_array as $days) {
            $days_str = $days_str . "'" . $days->Day . "',";
            $order_str = $order_str . $days->Total . ',';
            $pending_str = $pending_str . $days->PendingOrder . ',';
            $confirm_str = $confirm_str . $days->ConfirmOrder . ',';
            $ondelivery_str = $ondelivery_str . $days->OnDeliveryOrder . ',';
            $express_str = $express_str . $days->ExpressOrder . ',';
            $deliver_str = $deliver_str . $days->DeliverOrder . ',';     
            $unpaid_str = $unpaid_str . $days->UnPaidOrder . ',';       
        }

        $this->data["chart_label"] = trim($days_str, ',');
       
        $final_array = array();
        

        $array = array();
        $array["label"] ="Total Orders";
        $array["backgroundColor"] = "rgba(60,141,188,0.5)";
        $array["borderColor"] = "rgba(60,141,188,1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#3b8bba";
        $array["pointStrokeColor"] = "rgba(60,141,188,1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(60,141,188,1)";
        $array["data"] = "[". trim($order_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Total Orders Pending";
        $array["backgroundColor"] = "rgba(210, 214, 222, 0.5)";
        $array["borderColor"] = "rgba(210, 214, 222, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#c1c7d1";
        $array["pointStrokeColor"] = "rgba(210, 214, 222, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(210, 214, 222, 1)";
        $array["data"] = "[". trim($pending_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Total Orders Confirmed";
        $array["backgroundColor"] = "rgba(80, 214, 222, 0.5)";
        $array["borderColor"] = "rgba(80, 214, 222, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#d1d7c1";
        $array["pointStrokeColor"] = "rgba(80, 214, 222, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(80, 214, 222, 1)";
        $array["data"] = "[". trim($confirm_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Total Orders On Delivery";
        $array["backgroundColor"] = "rgba(80, 100, 222, 0.5)";
        $array["borderColor"] = "rgba(80, 100, 222, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#f1f7f1";
        $array["pointStrokeColor"] = "rgba(80, 100, 222, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(80, 100, 222, 1)";
        $array["data"] = "[". trim($ondelivery_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Total Orders Express Orders";
        $array["backgroundColor"] = "rgba(110, 214, 222, 0.5)";
        $array["borderColor"] = "rgba(110, 214, 222, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#g1g7c1";
        $array["pointStrokeColor"] = "rgba(110, 214, 222, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(110, 214, 222, 1)";
        $array["data"] = "[". trim($express_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Total Orders Delivered";
        $array["backgroundColor"] = "rgba(80, 214, 100, 0.5)";
        $array["borderColor"] = "rgba(80, 214, 100, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#s1s7c1";
        $array["pointStrokeColor"] = "rgba(80, 214, 100, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(80, 214, 100, 1)";
        $array["data"] = "[". trim($deliver_str, ',')."]";
        $final_array[] = $array;
        $this->data["chart_array"] = $final_array;
        
        $array = array();
        $array["label"] ="Total Orders UnPaid";
        $array["backgroundColor"] = "rgba(100, 100, 100, 0.5)";
        $array["borderColor"] = "rgba(100, 100, 100, 1)";
        $array["pointRadius"] = "false";
        $array["pointColor"] = "#f39c12";
        $array["pointStrokeColor"] = "rgba(100, 100, 100, 1)";
        $array["pointHighlightFill"] = "#fff";
        $array["pointHighlightStroke"] = "rgba(100, 100, 100, 1)";
        $array["data"] = "[". trim($unpaid_str, ',')."]";
        $final_array[] = $array;
        $this->data["chart_array"] = $final_array;
        
        $this->data["page_content"] = $admin_dashboard.$this->load->view($this->controller . "/order", $this->
            data, true);
        $this->data["page_script"] = $this->load->view($this->controller .
            "/order_script", $this->data, true);

        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    
    function vat(){
        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $this->db->select("order_items.vat, SUM( (order_items.price - (order_items.price / 1 + (order_items.vat /100))) ) as vat_amount , SUM(order_items.price) as totla_amount");
        $this->db->join("orders","orders.order_id = order_items.order_id");
        
        $this->db->group_by("order_items.vat");
                
        if ($this->input->post("date_range") != null) {
            $daterange = $this->input->post("date_range");
            
            if ($daterange != "") {            
                $dates = explode(" - ", $daterange);
                if (count($dates) == 2) {
                    $filter = " orders.order_date >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                        ($dates[0]))) . " 00:00:00' and orders.order_date <= '" . date(MYSQL_DATE_FORMATE,
                        strtotime(trim($dates[1]))) . " 23:59:59'";
                        if($filter != ""){
                            $this->db->where($filter);
                        } 
                }
            }
        }
        
        $q = $this->db->get("order_items");
        $this->data["data"] = $q->result();
        $this->data["page_content"] = $this->load->view($this->controller . "/vats", $this->
            data, true);
       
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    
    function sale(){
        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $q = $this->db->query("select DISTINCT LEFT(postal_code,4) as postal_code from postal_codes");
        $this->data["postal_codes"] = $q->result();
        
        $date_range = "";
        if ($this->input->post("date_range") != null) {
            $date_range = $this->input->post("date_range");
        }

        $date_type = "";
        if ($this->input->post("date_type") != null) {
            $date_type = $this->input->post("date_type");
        }
        
        $order_status = "";
        if ($this->input->post("order_status") != null) {
            $order_status = $this->input->post("order_status");
        }
        
        $postal_code = "";
        if ($this->input->post("postal_code") != null) {
            $postal_code = $this->input->post("postal_code");
        }
        
        $chart_array = $this->report_model->getSaleReport($date_range, $date_type,$order_status,$postal_code);

        $days_str = '';
        $zip_str = '';
        $company_str = '';
        $user_str = '';

        foreach ($chart_array as $days) {
            $days_str = $days_str . "'" . $days->Day . "',";
            $zip_str = $zip_str . $days->ZipOrder . ',';
            $company_str = $company_str . $days->CompanyOrder . ',';
            $user_str = $user_str . $days->UserOrder . ',';
        }

        $this->data["chart_label"] = trim($days_str, ',');
       
        $final_array = array();

        $array = array();
        $array["label"] ="Sales Per Zip";
        $array["fillColor"] = "#00c0ef";
        $array["strokeColor"] = "#00c0ef";       
        $array["data"] = "[". trim($zip_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Sales Companies";
        $array["fillColor"] = "#00a65a";
        $array["strokeColor"] = "#00a65a";        
        $array["data"] = "[". trim($company_str, ',')."]";
        $final_array[] = $array;
        
         $array = array();
        $array["label"] ="Sales Personal Users";
        $array["fillColor"] = "#f39c12";
        $array["strokeColor"] = "#f39c12";      
        $array["data"] = "[". trim($user_str, ',')."]";
        $final_array[] = $array;
        
        $this->data["chart_array"] = $final_array;        
       
        $this->data["page_content"] = $this->load->view($this->controller . "/sale", $this->data, true);
        $this->data["page_script"] = $this->load->view($this->controller."/sale_script", $this->data, true);
            
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    
  function sales(){
        $this->data["active_menu_link"] = array(site_url("admin/report"));
        $this->data["daterangepicker"] = true;
        $this->data["field"] = $this->input->post();

        $this->load->model("productgroups_model");
        $this->load->model("subcategories_model");
        $this->load->model("categories_model");
        
        $this->data["categories"] = $this->categories_model->get();
      
        $categoryid="";
        $subcategories = array();
        if($this->input->post("category_id")!=null){  
             $categoryid=$this->input->post("category_id");
             $subcategories = $this->subcategories_model->get(array("sub_categories.category_id"=>$categoryid));           
        }
        
        $subcategoryid="";
        $groups = array();
        if($this->input->post("sub_category_id")!=null){
            $subcategoryid=$this->input->post("sub_category_id");
            $groups = $this->productgroups_model->get(array("product_groups.sub_category_id"=>$subcategoryid));          
        }
        
        $groupid = "";
        if ($this->input->post("product_group_id") != null) {
            $groupid = $this->input->post("product_group_id");
        }
        
        $date_range = "";
        if ($this->input->post("date_range") != null) {
            $date_range = $this->input->post("date_range");
        }

        $date_type = "";
        if ($this->input->post("date_type") != null) {
            $date_type = $this->input->post("date_type");
        }        
       
        $chart_array = $this->report_model->getSalesReport($date_range, $date_type,$categoryid,$subcategoryid,$groupid);

        $days_str = '';
        $category_str = '';
        $subcategory_str = '';
        $group_str = '';

        foreach ($chart_array as $days) {
            $days_str = $days_str . "'" . $days->Day . "',";
            $category_str = $category_str . $days->CategoryOrder . ',';
            $subcategory_str = $subcategory_str . $days->SubCategoryOrder . ',';
            $group_str = $group_str . $days->GroupOrder . ',';
        }

        $this->data["chart_label"] = trim($days_str, ',');
       
        $final_array = array();

        $array = array();
        $array["label"] ="Sales Categorie";
        $array["fillColor"] = "#00c0ef";
        $array["strokeColor"] = "#00c0ef";       
        $array["data"] = "[". trim($category_str, ',')."]";
        $final_array[] = $array;
        
        $array = array();
        $array["label"] ="Sales Sub Categorie";
        $array["fillColor"] = "#00a65a";
        $array["strokeColor"] = "#00a65a";        
        $array["data"] = "[". trim($subcategory_str, ',')."]";
        $final_array[] = $array;
        
         $array = array();
        $array["label"] ="Sales Product Group";
        $array["fillColor"] = "#f39c12";
        $array["strokeColor"] = "#f39c12";      
        $array["data"] = "[". trim($group_str, ',')."]";
        $final_array[] = $array;
        
        $this->data["chart_array"] = $final_array;        
       
        $this->data["select2"] = true;       
        $this->data["ajax_subcat"] = true;
        $this->data["ajax_group"] = true;
        $this->data["subcategories"] = $subcategories;
        $this->data["productgroups"] = $groups;
       
        $this->data["page_content"] = $this->load->view($this->controller . "/sales", $this->data, true);
        $this->data["page_script"] = $this->load->view($this->controller."/sales_script", $this->data, true);
            
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
}
