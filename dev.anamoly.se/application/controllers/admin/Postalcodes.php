<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Postalcodes extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "postal_codes";
        $this->primary_key= "postal_code_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if(!_is_admin() && !_is_sub_admin("admin/postalcodes")){
            redirect("login");
            exit();
        }
        
		$this->load->model("postalcodes_model");
    }
    

    public function index(){
        _set_back_url();
		$this->data["data"] = $this->postalcodes_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }

    public function delete($id){
        $id = _decrypt_val($id);
        
        $row = $this->postalcodes_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Postal Code"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Postal Code not available");
            echo json_encode($data);
        }
    }
	
    public function add($postal_code=""){
            $this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/postalcodes"));
            $this->data["field"] = $this->input->post();
            if ($postal_code != "") {
                $this->db->where("postal_code", $postal_code);
                $q = $this->db->get("delivery_times");
                $this->data["delivery_times"] = $q->result();
            }
            $this->data["from_to_timepicker"] = true;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }
	/**
	* action add or edit product category
	* @return redirect to product category list
	*/
    private function action($check_unique=true){
        $post = $this->input->post();
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('users_limits', _l("User Limits"), 'trim|required');
        if ($check_unique) {
            $this->form_validation->set_rules('postal_code', _l("Postal Code"), 'trim|required|is_unique[postal_codes.postal_code]');
            $this->form_validation->set_message('is_unique', 'Postal Code Already Exist');
        }else{
            $this->form_validation->set_rules('postal_code', _l("Postal Code"), 'trim|required');
        }
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
            $add_data = array(
                "postal_code"=>str_replace(" ","",$post["postal_code"]),
                "users_limits"=>$post["users_limits"],
                "min_order_amount"=>$post["min_order_amount"]
            );
			
            if(!empty($post["id"])){
                
				$id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name,$add_data,array($this->primary_key=>$id),true);
                $this->message_model->action_mesage("update",_l("Postal Code"),false);
				$this->updateTimeSlots($post,$post["postal_code"]);
                redirect($this->controller);
            }else{
				
                $id = $this->common_model->data_insert($this->table_name,$add_data,true);
                $this->message_model->action_mesage("add",_l("Postal Code"),false);
                $this->updateTimeSlots($post,$post["postal_code"]);
                redirect($this->controller);
            }

        }
    }
	private function updateTimeSlots($post,$postal_code){
                $days = $post["days"];
                if (!empty($days)) {
                    foreach ($days as $day) {
                        $from_time = $post["from_time"][$day];
                        $to_time = $post["to_time"][$day];
                        if ($from_time != "" && $to_time != "") {
                            $add_data = array(
                            "postal_code"=>$postal_code,
                            "from_time"=>date(MYSQL_TIME_FORMATE, strtotime($from_time)),
                            "to_time"=>date(MYSQL_TIME_FORMATE, strtotime($to_time)),
                            "days"=>$day,
                            "allow_book_before"=>$post["allow_book_before"][$day],
                            "max_no_of_orders"=>$post["max_no_of_orders"][$day]
                        );
                            $this->db->where("days", $day);
                            $this->db->where("postal_code", $postal_code);
                            $q = $this->db->get("delivery_times");
                            $time = $q->row();
                            if (!empty($time)) {
                                $id = $time->delivery_time_id;
                                $this->common_model->data_update("delivery_times", $add_data, array("delivery_time_id"=>$id), true);
                            } else {
                                $id = $this->common_model->data_insert("delivery_times", $add_data, true);
                            }

                            $this->message_model->action_mesage("update", _l("Delivery Time"), false);
                        }
                    }
                }
    }

    public function edit($id){
            $id = _decrypt_val($id);
            $field = $this->postalcodes_model->get_by_id($id);
            if(empty($field)){
                exit();
            }
            $check_unique = true;
            if(isset($_POST["id"])){
                if($_POST["postal_code"] == $field->postal_code){
                    $check_unique = false;
                }
            }
            $this->action($check_unique);
            
			$this->data["active_menu_link"] = array(site_url("admin/postalcode"));
            $this->data["field"] = $field;
            $this->db->where("postal_code",$field->postal_code);
            $q = $this->db->get("delivery_times");
            $this->data["delivery_times"] = $q->result();

            $this->data["from_to_timepicker"] = true;
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

	public function get_postalcode_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->postalcodes_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
	
	
}
