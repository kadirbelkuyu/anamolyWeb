<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
	 * Planner
	 * @return planner dashboard page
	 */
	public function index()
	{
		if (_is_planner()){
			$this->load->model("orders_model");
			$orders_with_status = $this->orders_model->order_counts_with_status();
			$data["order_with_status"] = $orders_with_status;

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
			$data["page_content"] = $this->load->view("planner/dashboard",$data,true).$this->load->view("admin/orders/list",$data,true);
			$data["page_script"] = $this->load->view("admin/orders/order_script",$data,true);
        
        }
	    $this->load->view(BASE_TEMPLATE,$data);
	}
}
