<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('_is_user_login'))
{
     function _is_user_login($user_type_id = "")
    {
        $thi =& get_instance();
        $usertype = _get_current_user_type_id();
        $user_id = _get_current_user_id();
        if($user_id != NULL && $user_id != "" && $user_id > 0 && $usertype != NULL && $usertype != "" && $usertype > 0){
            return true;
        }
        return false;    
    }
}
if ( ! function_exists('_do_upload_scale_image'))
{
     function _do_upload_scale_image($field_name,$path,$big_size = 680, $small_size = 200,$is_crop = true)
    {
        $CI =& get_instance();
        if(isset($_FILES[$field_name]) && $_FILES[$field_name]['size'] > 0){
                                    if(!file_exists($path)){
                                        mkdir($path);
                                    }
                                    $CI->load->library("imagecomponent");
                                    $file_name_temp = md5(uniqid())."_".str_replace(" ","",$_FILES[$field_name]['name']);
                                    $file_name = $CI->imagecomponent->upload_image_and_thumbnail($field_name,$big_size,$small_size,$path ,'crop',$is_crop,$file_name_temp);
                                    return $file_name;
        }

        return "";
    }
}
if ( ! function_exists('_is_frontend_user_login'))
{
     function _is_frontend_user_login()
    {
        $userid = _get_current_user_id();
        $usertype = _get_current_user_type_id();

        if(isset($userid) && $userid!="" && isset($usertype))
        {
                 return true;
        }else
        {

            return false;
        }

    }
}
if(! function_exists('_get_post_back')){
    function _get_post_back($post,$title="",$type="",$return = ""){
        $thi =& get_instance();
        
        if($post == NULL)
            return $return;
        if(is_object($post)){
            if($title != "" &&  isset($post->$title)){
                $return = $post->$title ;
            }

        }else if(is_array($post)){
            if($title != "" &&  isset($post[$title])){
                $return = $post[$title] ;
            }

        }else{
            $return = ($thi->input->post($post)!="")? $thi->input->post($post) : ""; ;
        }
        if($type == "date"){
            $return = date(DEFAULT_DATE_FORMATE,strtotime($return));
        }
        if($type == "date_time"){
            $return = date(DEFAULT_DATE_TIME_FORMATE,strtotime($return));
        }
        if($type == "time"){
            $return = date(DEFAULT_TIME_FORMATE,strtotime($return));
        }
        return $return;
    }
}
if(! function_exists('_get_current_user_id')){
    function _get_current_user_id(){
        $thi =& get_instance();
        return $thi->session->userdata("user_id");
    }
}
if(! function_exists('_get_current_user_email')){
    function _get_current_user_email(){
        $thi =& get_instance();
        return $thi->session->userdata("user_email");
    }
}
if(! function_exists('_get_current_user_fullname')){
    function _get_current_user_fullname(){
        $thi =& get_instance();
        return $thi->session->userdata("user_fullname");
    }
}
if(! function_exists('_get_current_user_image')){
    function _get_current_user_image(){
        $thi =& get_instance();
        //echo dirname(__FILE__)."../../uploads/profile/".$thi->session->userdata("user_image");
        if($thi->session->userdata("user_image") != "" && file_exists(dirname(__FILE__)."./../../uploads/profile/".$thi->session->userdata("user_image")))
            return base_url("uploads/profile/".$thi->session->userdata("user_image"));
        else
            return base_url(ADMIN_THEME_BASE."/img/avatar.png");
    }
}
if(! function_exists('_get_current_user_type_id')){
    function _get_current_user_type_id(){
        $thi =& get_instance();
        return $thi->session->userdata("user_type_id");
    }
}

if(! function_exists('_is_admin')){
    function _is_admin(){
        $user_type_id = _get_current_user_type_id();        
        if($user_type_id == USER_ADMIN)
            return true;
        else
            return false;
    }
}
if(! function_exists('_is_sub_admin')){
    function _is_sub_admin($permission=""){
        $user_type_id = _get_current_user_type_id();        
        if ($user_type_id == USER_SUBADMIN) {
            if($permission != ""){
                return _check_permission($permission);
            }
            return true;
        }
        else
            return false;
    }
}
if(! function_exists('_is_planner')){
    function _is_planner(){
        $user_type_id = _get_current_user_type_id();
        if($user_type_id == USER_PLANNER)
            return true;
        else
            return false;
    }
}

if(! function_exists('_is_buyer')){
    function _is_buyer(){
        $user_type_id = _get_current_user_type_id();
        if($user_type_id == USER_BUYER)
            return true;
        else
            return false;
    }
}
if(! function_exists('_check_permission')){
    function _check_permission($role){
        if (_is_sub_admin()) {
            $permission_array = MY_Controller::$permission_array;
            if (!empty($permission_array) && in_array($role, $permission_array)) {
                return true;
            }
        }else if(_is_admin()){
            return true;
        }
    }
}



if(! function_exists('_get_user_type_access')){
    function _get_user_type_access($user_type_id){
            $thi =& get_instance();
            $cur_class = $thi->router->fetch_class();
            $cur_method = $thi->router->fetch_method();
            $result = $thi->db->query("select * from user_type_access where user_type_id = '".$user_type_id."' and class = '".$cur_class."' and (method = '".$cur_method."' or method='*')");

            $row = $result->row();

            if($result->num_rows() > 0){
                return true;
            }else{
                return false;
            }
            return false;
    }
}

if(! function_exists('_get_user_redirect')){
    function _get_user_redirect(){
        if(_is_user_login()){
            if (_is_admin()){
                return "admin/dashboard";
            }else if (_is_sub_admin()){
                return "subadmin/dashboard";
            }else if (_is_planner()){
                return "planner/dashboard";
            }else if (_is_buyer()){
                return "buyer/dashboard";
            }else{
                return "";
            }
        }else{
            return "login";
        }
    }
}

if(! function_exists('_is_active_menu')){
    function _is_active_menu($class,$method){
        $thi =& get_instance();
        $c_class = $thi->router->fetch_class();
        $c_method = $thi->router->fetch_method();

        if(!empty($class)){
            if(is_array($c_class)){
                if(!empty($method)){
                    if(in_array($c_class,$class) && in_array($c_method,$method)){
                        return "active";
                    }
                }else{
                    if(in_array($c_class,$class)){
                        return "active";
                    }
                }
            }else{
                if(!empty($method)){
                    if($c_class == $class && $c_method == $method){
                        return "active";
                    }
                }else{
                    if($c_class == $class){
                        return "active";
                    }
                }
            }

        }else{
            if(is_array($c_class)){
                if(in_array($c_method,$method)){
                    return "active";
                }
            }else{
                if($c_method == $method){
                    return "active";
                }
            }
        }
    }
}
