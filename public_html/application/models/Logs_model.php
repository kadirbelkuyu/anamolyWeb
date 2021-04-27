<?php
class Logs_model extends CI_Model
{
    function set_log($table,$action,$message="",$array=array(),$condition=array()){
        $key = "";
        if(key_exists("key",$condition)){
            $key = $condition["key"];
        }
        
        switch($table){
            case 'test':
                $parm_0 = "User";
                if(key_exists("user_type_id",$array)){
                    if($array["user_type_id"] == USER_ADMIN){
                        $parm_0 = "Admin";
                    }else if($array["user_type_id"] == USER_PLANNER){
                        $parm_0 = "Planner";
                    }else if($array["user_type_id"] == USER_BUYER){
                        $parm_0 = "Buyer";
                    }
                    else if($array["user_type_id"] == USER_CUSTOMER){
                        $parm_0 = "Customer";
                    }
                    else if($array["user_type_id"] == USER_COMPANY){
                        $parm_0 = "Company";
                    }
                }
                $parm_1 = "";
                if($action == "add"){
                    $parm_1 = "added";
                }
                if($action == "update"){
                    $parm_1 = "updated";
                }
                if($action == "delete"){
                    $parm_1 = "deleted";
                }
                $message = $this->message_model->custom_messages("log",array($parm_0." ".$array["user_fullname"],$parm_1));    
                if(key_exists("user_id",$condition))
                    $key = $condition["user_id"];

                break;    
            
            default:
                $q = $this->db->query("SHOW KEYS FROM $table WHERE Key_name = 'PRIMARY'");
                $row = $q->row();
                if(!empty($row)){
                    $p_k = $row->Column_name;
                    if(key_exists($p_k,$condition))
                        $key = $condition[$p_k];
    
                }
                break;
            
        }
        $user_id = 0;
        if(empty($message)){
            $message = "";
        }
        if(_is_frontend_user_login($this)){
            $user_id = _get_current_user_id($this);
        }
        if($table == "employee_permission")
            return 0;
        $created_at = date("Y-m-d h:s:i");
        $ip = $this->get_client_ip();
        $insert_array = array(
        "data_table"=>$table,
        "data_action"=>$action, 
        "log"=>$message, 
        "user_id"=>$user_id,
        "created_at"=>$created_at,
        "ip"=>$ip,        
        "tablepkid"=>$key);
        $this->db->insert("logs",$insert_array);
        $insert_array["log_id"] = $this->db->insert_id();
        return $insert_array;
    }
    
    function get_client_ip() {
    $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    public function get_user_logs(){
        $user_id = _get_current_user_id($this);
        $user_type_id = _get_current_user_type_id($this);
        
        $filter = "";
        $join = "";
        
        $sql = "Select * from logs where 1 $filter order by created_at DESC limit 0,40";
        
        $q = $this->db->query($sql);
        return $q->result();
    }
}
?>