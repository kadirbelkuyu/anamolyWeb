<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
	 * dashboard 
	 * @return dashboard page
	 */
	public function index()
	{
        $data = array();
		if (_is_sub_admin("admin/orders")){
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
			$data["page_content"] = $this->load->view("admin/dashboard",$data,true).$this->load->view("admin/orders/list",$data,true);
			$data["page_script"] = $this->load->view("admin/orders/order_script",$data,true);
        
        }
		$this->load->view(BASE_TEMPLATE,$data);
		
	}
}
