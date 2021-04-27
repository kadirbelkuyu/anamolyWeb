<?php defined('BASEPATH') or exit('No direct script access allowed');
class Subadmin extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "users";
        $this->primary_key= "user_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;
 
        if (!_is_admin()) {
            redirect("login");
            exit();
        }
        $this->load->model("user_model");
        $this->load->model("roles_model");
        
    }

    public function index()
    {
        $this->data["user_type_id"] = USER_SUBADMIN;
        $this->data["data"] = $this->user_model->get(array("user_type_id"=>USER_SUBADMIN));
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
        
        $row = $this->user_model->get_by_id($id);
        if (!empty($row)) {
            $pk=$this->primary_key;
            $this->db->query("Insert into users_trash select * from users where user_id = $row->user_id");
            $this->common_model->data_remove($this->table_name, array($this->primary_key=>$row->$pk), true);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete", _l("User"), true);
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
        $this->data["active_menu_link"] = array(site_url("admin/subadmin"));
        $this->data["fileupload"] = true;
        $field = $this->input->post();
        $this->data["user_type_id"] = USER_SUBADMIN;
        $this->data["field"] = $field;
        $this->data["roles"] = $this->roles_model->get();
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
        if (empty($post["id"])) {
            $this->form_validation->set_rules('user_email', 'Email Address', 'trim|required|valid_email|is_unique[users.user_email]');
            $this->form_validation->set_message('is_unique', 'Email address is already register');
            $this->form_validation->set_rules('user_password', 'Password', 'trim|required');
        }
        $this->form_validation->set_rules('user_firstname', 'First Name', 'trim|required');
        $this->form_validation->set_rules('user_lastname', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('user_phone', 'Phone', 'trim|required');
    
        $responce = array();
        if ($this->form_validation->run() == false) {
            if ($this->form_validation->error_string()!="") {
                _set_flash_message($this->form_validation->error_string(), "error");
            }
        } else {
            $add_data = array(
            "user_firstname"=>$post["user_firstname"],
            "user_lastname"=>$post["user_lastname"],
            "user_phone"=>$post["user_phone"],
            "user_type_id"=>$post["user_type_id"],
            "role_id"=>$post["role_id"],
            "status"=>(isset($post["status"]) && $post["status"] == "on") ? 1 : 0
        );
        
        if (isset($_FILES["user_image"]) && $_FILES['user_image']['size'] > 0) {
                $path = PROFILE_IMAGE_PATH;
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->load->library("imagecomponent");
                $file_name_temp = $this->imagecomponent->getuniquefilename($_FILES['user_image']['name']);//md5(uniqid())."_".$_FILES['user_image']['name'];
                $file_name = $this->imagecomponent->upload_image_and_thumbnail('user_image', 680, 200, $path, 'crop', true, $file_name_temp);
                $add_data["user_image"] = $file_name_temp;
        }
        
            $id = "";
            if (!empty($post["id"])) {
                if(isset($post["user_password"]) && $post["user_password"] != ""){
                    $add_data["user_password"] = md5($post["user_password"]);
                }
                $id = _decrypt_val($post["id"]);
                $this->common_model->data_update($this->table_name, $add_data, array($this->primary_key=>$id), true);
                $this->message_model->action_mesage("update", _l("User"), false);
            
                
            } else {
                $add_data["user_password"] = md5($post["user_password"]);
                $add_data["user_email"] = $post["user_email"];

                $id = $this->common_model->data_insert($this->table_name, $add_data, true);
                $this->message_model->action_mesage("add", _l("User"), false);
            
               
            }
            

            //redirect($this->controller);
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
        $field = $this->user_model->get_by_id($id);
        if (empty($field)) {
            exit();
        }
        $this->data["user_type_id"] = $field->user_type_id;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/subadmin"));
        $this->data["fileupload"] = true;
        $this->data["field"] = $field;
        $this->data["roles"] = $this->roles_model->get();
        $this->data["page_content"] = $this->load->view($this->controller."/add", $this->data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    /**
    * get sub admin by id
    * @return sub admin json
    */
    public function get_user_by_id()
    {
        $id=$this->input->post("id");
        $data=$this->user_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
        echo json_encode($data);
    }
}
