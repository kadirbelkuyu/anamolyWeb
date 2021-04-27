<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Deliverytimes extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "delivery_times";
        $this->primary_key= "delivery_time_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/deliverytimes")){
            redirect("login");
            exit();
        }
        
        $this->load->model("deliverytimes_model");
        $this->load->model("postalcodes_model");
        $this->data["postalcodes"] = $this->postalcodes_model->get();
    }
	/**
	* Product category
	* @return product category page
	*/
    public function index(){
        _set_back_url();
		//$this->data["datatable_export"]=true;	
        $this->data["data"] = $this->deliverytimes_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* delete product category
	* @param id for product category id
	* @return delete product category
	*/
    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->deliverytimes_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Delivery Time"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Delivery times not available");
            echo json_encode($data);
        }
    }
	/**
	* add product category
	* @return add product category page
	*/
    public function add(){
            $this->action_add();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/deliverytimes"));
			$this->data["from_to_timepicker"] = true;
            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
    private function action_add(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('postal_code', _l("Postal Code"), 'trim|required');
        $this->form_validation->set_rules('days[]', _l("Choose Working Days"), 'trim|required');
        
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
            $days = $post["days"];
            foreach($days as $day){
                $from_time = $post["from_time"][$day];
                $to_time = $post["to_time"][$day];
                if($from_time != "" && $to_time != ""){
                    $add_data = array(
                        "postal_code"=>$post["postal_code"],
                        "from_time"=>date(MYSQL_TIME_FORMATE,strtotime($from_time)),
                        "to_time"=>date(MYSQL_TIME_FORMATE,strtotime($to_time)),
                        "days"=>$day,
                        "allow_book_before"=>$post["allow_book_before"][$day],
                        "max_no_of_orders"=>$post["max_no_of_orders"][$day]
                    );
                    $this->db->where("days",$day);
                    $this->db->where("postal_code",$post["postal_code"]);
                    $q = $this->db->get($this->table_name);
                    $time = $q->row();
                    if(!empty($time)){
                        $id = $time->delivery_time_id;
                        $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                        
                    }else{
                        $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                
                    }

                    $this->message_model->action_mesage("update",_l("Delivery Time"),false);
                        
                }
            }
            redirect($this->controller);
        }
    }
	/**
	* action add or edit product category
	* @return redirect to product category list
	*/
    private function action_edit(){
        $post = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('postal_code', _l("Postal Code"), 'trim|required');
        $this->form_validation->set_rules('from_time[]', _l("From Time"), 'trim|required');
        $this->form_validation->set_rules('to_time[]', _l("To Time"), 'trim|required');
        $this->form_validation->set_rules('allow_book_before[]', _l("Limit Book Before"), 'trim|required');
        
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
            $day = $post["day"];
            $from_time = $post["from_time"][$day];
                $to_time = $post["to_time"][$day];
                if($from_time != "" && $to_time != ""){
                    $add_data = array(
                        "postal_code"=>$post["postal_code"],
                        "from_time"=>date(MYSQL_TIME_FORMATE,strtotime($from_time)),
                        "to_time"=>date(MYSQL_TIME_FORMATE,strtotime($to_time)),
                        "days"=>$day,
                        "allow_book_before"=>$post["allow_book_before"][$day],
                        "max_no_of_orders"=>$post["max_no_of_orders"][$day]
                    );
                    $this->db->where("days",$day);
                    $this->db->where("postal_code",$post["postal_code"]);
                    $q = $this->db->get($this->table_name);
                    $time = $q->row();
                    if(!empty($time)){
                        $id = $time->delivery_time_id;
                        $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                        
                    }else{
                        $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                
                    }

                    $this->message_model->action_mesage("update",_l("Delivery Time"),false);
                }       
                redirect($this->controller); 
        }
    }
	
	/**
	* edit product size
	* @return edit product size page
	*/
    public function edit($id){
            $id = _decrypt_val($id);
            $this->action_edit();
            $field = $this->deliverytimes_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
			$this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/deliverytimes"));
			$this->data["from_to_timepicker"] = true;
            $this->data["field"] = $field;
            $this->data["page_content"] = $this->load->view($this->controller."/edit",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	
	/**
	* get category by id
	* @return category json
	*/
	public function get_category_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->deliverytimes_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
    
    function get_postal_timeslots(){
        $postal_code=$this->input->post("postal_code");
        $result = array();
        $prefix_postal = substr($postal_code,0,4);
        if($prefix_postal == 4){
            $this->db->like("postal_code",$prefix_postal);
            $q = $this->db->get("delivery_times");
            $row = $q->row();
            
            $this->db->where("postal_code",$row->postal_code);
            $q = $this->db->get("delivery_times");
            $result = $q->result();
            
        }
        echo json_encode($result);
    }
	
}
