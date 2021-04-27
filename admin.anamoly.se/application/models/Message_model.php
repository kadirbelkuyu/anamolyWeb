<?php
class Message_model extends CI_Model{
    function get_message($message){
        return ($this->lang->line($message) != "" ) ? $this->lang->line($message) : $message;
    }
    function confirm_delete($title){
        $title = $this->get_message($title);
        return str_replace("##",strtolower($title),$this->lang->line("msg_delete_confirm"));
    }
    function confirm_delete_script($title){
        $msg = $this->confirm_delete($title);
        return "return confirm('".$msg."')";
    }
    
    function error($message,$string = false){
        if($string){
            return _error_message($message);    
        }else{
            _set_flash_message($message,"error");
        }
        
    }
    function custom_messages($case,$parms){
        $action_string = "";
        switch($case){
            case 'log' :
                $action_string =  str_replace(array("{0}","{1}"),$parms,$this->get_message("msg_action_log"));         
                break;
            case 'new_user_success' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string =  str_replace(array("{0}"),array($parms),$this->get_message("msg_new_user_add"));         
                break;
            case 'update_user' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string = str_replace(array("{0}"),array($parms),$this->get_message("msg_user_update"));         
                break;
            case 'remove_user' :
                if(is_array($parms)){
                    $parms = $parms[0];    
                }
                $action_string =  str_replace(array("{0}"),array($parms),$this->get_message("msg_user_update"));         
                break;
        }
        return $action_string;
    }
    function action_mesage($action,$title,$string=false){
        $action_string;
        $type ;
        switch($action){
            case 'add' :
                $action_string = $this->get_message("added");         
                $type = "success";
                break;
            case 'update' :
                $action_string = $this->get_message("saved");
                $type = "success";
                break;
            case 'delete' :
                $action_string = $this->get_message("deleted");
                $type = "error";         
                break;
            case 'cancel' :
                $action_string = $this->get_message("cancelled");
                $type = "warning";         
                break;
                
            
        }
        $message = str_replace(array("{0}","{1}"),array($title,$action_string),$this->lang->line("msg_action"));
         if($string){
            switch($type){
                case 'success':
                    return _success_message($message);
                    break;
                case 'warning':
                    return _warning_message($message);
                    break;
                case 'error':
                    return _error_message($message);
                    break;
            } 
        }else{
            _set_flash_message($message,$type);
        }
    }
    
    function success($message,$string = false){
       if($string){
            return _success_message($message);
        }else{
            _set_flash_message($message,"success");
        }
        
    }
    
}
?>