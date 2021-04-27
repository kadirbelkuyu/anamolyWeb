<?php defined('BASEPATH') or exit('No direct script access allowed');
class Roles extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "user_roles";
        $this->primary_key= "role_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if (!_is_admin()) {
            redirect("login");
            exit();
        }
        $this->load->model("roles_model");
        $this->load->model("permissions_model");
        
    }

    public function index()
    {
        $this->data["user_type_id"] = USER_SUBADMIN;
        $this->data["data"] = $this->roles_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/list", $this->data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    /**
    * delete product sub admin
    * @param id for product sub admin id
    * @return delete product sub admin
    */
    public function delete($id)
    {
        $id = _decrypt_val($id);
        
        $row = $this->roles_model->get_by_id($id);
        if (!empty($row)) {
            $pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete", _l("Role"), true);
            echo json_encode($data);
        } else {
            $data['responce'] = false;
            $data['error'] = _l("User not available");
            echo json_encode($data);
        }
    }

    /**
    * add product sub admin
    * @return add product sub admin page
    */
    public function add()
    {
        $this->action();
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/roles"));
        $field = $this->input->post();
        $this->data["field"] = $field;
        $this->data["permissions"] = $this->permissions_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/add", $this->data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }
    /**
    * action add or edit product sub admin
    * @return redirect to product sub admin list
    */
    private function action()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('role_title', 'Role Title', 'trim|required');
        
        $responce = array();
        if ($this->form_validation->run() == false) {
            if ($this->form_validation->error_string()!="") {
                _set_flash_message($this->form_validation->error_string(), "error");
            }
        } else {
            $add_data = array(
            "role_title"=>$post["role_title"]
        );
            $id = "";
            if (!empty($post["id"])) {
                
                $id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name, $add_data, array($this->primary_key=>$id), false);
                $this->message_model->action_mesage("update", _l("Role"), false);
            
                
            } else {
                $id = $this->common_model->data_insert($this->table_name, $add_data, false);
                $this->message_model->action_mesage("add", _l("Role"), false);
            
               
            }
            if($id != ""){
                $post_permission = $this->input->post("permission");
                $this->db->delete("user_role_permissions",array("role_id"=>$id));
                foreach($post_permission as $key=>$val){
                    $this->db->insert("user_role_permissions",array("role_id"=>$id,"permission_id"=>$key));
                }
            }
            redirect($this->controller);
        }
    }

    /**
    * edit product size
    * @return edit product size page
    */
    public function edit($id)
    {
        $id = _decrypt_val($id);
        $this->action();
        $field = $this->roles_model->get_by_id($id);
        if (empty($field)) {
            exit();
        }
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/roles"));
        $this->data["field"] = $field;
        $this->data["permissions"] = $this->permissions_model->get_by_role_id($field->role_id,"left");
        $this->data["page_content"] = $this->load->view($this->controller."/add", $this->data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    /**
    * get sub admin by id
    * @return sub admin json
    */
    public function get_by_id()
    {
        $id=$this->input->post("id");
        $data=$this->roles_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
        echo json_encode($data);
    }
}
