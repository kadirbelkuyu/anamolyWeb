<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Content-Type: text/html; charset=utf-8');

/**
 * Success Message
 * @param  string $message
 * @return string
 */
function _success_message($message){
    $return = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> '._l("Success!").'</h4>
                '.$message.'
              </div>';
    return $return;
}
/**
 * Warning Message
 * @param  string $message
 * @return string
 */
function _warning_message($message){
    $return = '<div class="alert alert-warning alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> '._l("Warning!").'</h4>
                '.$message.'
              </div>';
    return $return;
}
/**
 * Error Message
 * @param  string $message
 * @return string
 */
function _error_message($message){
    $return = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h4><i class="icon fa fa-check"></i> '._l("Error!").'</h4>
                '.$message.'
              </div>';
    return $return;
}
/**
 * Set Flash Message
 * @param  string $message
 * @param  string $type = [success,warning,error]
 */
function _set_flash_message($message,$type){
    $CI =& get_instance();
    switch($type){
        case "success" :
            $CI->session->set_flashdata("flash_message",_success_message($message)); 
            break;
        case "warning" :
            $CI->session->set_flashdata("flash_message",_warning_message($message)); 
            break;
        case "error" :
            $CI->session->set_flashdata("flash_message",_error_message($message)); 
            break;
                  
    }
}
/**
 * Get Flash Message
 * @return  string $message
 */
function _get_flash_message(){
    $CI =& get_instance();
    return $CI->session->flashdata("flash_message"); 
}
?>
