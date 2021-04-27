<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
	 * Buyer
	 * @return buyer dashboard page
	 */
	public function index()
	{
	    if (_is_buyer()){
            $data["page_content"] = $this->load->view("buyer/dashboard",array(),true);
        }
	        $this->load->view(BASE_TEMPLATE,$data);
	}
}
