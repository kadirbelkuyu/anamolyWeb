<?php defined('BASEPATH') or exit('No direct script access allowed');
class Change_password extends MY_Controller
{
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = $this->router->fetch_class();

        if (!_is_user_login()) {
            redirect("login");
            exit();
        }

        $this->load->model("user_model");
    }

    public function index()
    {
        if (isset($_POST['change_password'])) {
            $this->action();
        }
        $this->data["field"] = $this->input->post();
        $this->data["page_content"] = $this->load->view("admin/change_password", $this->
            data, true);
        $this->load->view(BASE_TEMPLATE, $this->data);
    }

    function action()
    {
        $post = $this->input->post();
        $this->load->library('form_validation');

        $this->form_validation->set_rules('old_password', _l("Old Password"),
            'trim|required');

        $this->form_validation->set_rules('new_password', _l("New Password"),
            'trim|required');
        $this->form_validation->set_rules('confirm_password', _l("Confirm Password"),
            'trim|required|matches[new_password]');

        $responce = array();
        if ($this->form_validation->run() == false) {

            if ($this->form_validation->error_string() != "") {              
                _set_flash_message($this->form_validation->error_string(), 'error');
            }
        } else {
            $userid = _get_current_user_id();

            $old_pass = md5($post['old_password']);
            $is_match = $this->user_model->check_match_password($old_pass,$userid);
            if ($is_match == 1) {
                $add_data = array("user_password" => md5($post['confirm_password']));
                $this->common_model->data_update("users", $add_data, array("user_id" => $userid), false);
                _set_flash_message(_l("msg_password_change_success"), 'success');
            } else {
                _set_flash_message(_l("msg_old_password_do_not_match"), 'error');
            }
        }
    }

}
