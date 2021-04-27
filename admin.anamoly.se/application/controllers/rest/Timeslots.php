<?php defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';
class Timeslots extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        //$this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        //$this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        //$this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->model("deliverytimes_model");
        
    }
    function list_post(){
        $postal_code = str_replace(" ","",$this->post("postal_code"));
        if($postal_code == NULL){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Please enter your postal code"),
                DATA => _l("Please enter your postal code"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }

        $this->load->model("orders_model");
        $db_times = $this->deliverytimes_model->get(array("delivery_times.postal_code"=>$postal_code));
        
        if(empty($db_times)){
            $this->response(array(
                RESPONCE => false,
                MESSAGE => _l("Sorry! No Time for delivery"),
                DATA => _l("Sorry! No Time for delivery"),
                CODE => 100
            ), REST_Controller::HTTP_OK);
        }

        $today = date(MYSQL_DATE_FORMATE);
        $timeslots = [];
        $count = 0;
        do{
            if($count >0){
                $date = date(MYSQL_DATE_FORMATE,strtotime('+ '.$count.' day',strtotime($today)));
            }else{
                $date = $today;
            }
            
            $day = date("D",strtotime($date));
            //$time_index = array_search($day, array_column($db_times, 'days'));
            //$time = $db_times[$time_index];
                
            foreach ($db_times as $time) {
                if ($day == $time->days) {
                    
                    $from_time = $time->from_time;
                    $to_time = $time->to_time;
                
                    $available = true;
                    if ($today == $date) {
                        $before_time = date(MYSQL_TIME_FORMATE, strtotime('-'.$time->allow_book_before.' minutes', strtotime($from_time)));
                        if (time() >= strtotime($before_time)) {
                            $available = false;
                        }
                    }
                    if ($available) {
                        $orders = $this->orders_model->get_orders_by_date($date);
                        if (count($orders) < $time->max_no_of_orders) {
                            $timeslots[] = ['date'=>$date,"from_time"=>$from_time,"to_time"=>$to_time];
                        }
                    }
                }
            }
            $count++;
        }while($count <= 7);
        
        $this->response(array(
                                        RESPONCE => true,
                                        MESSAGE => _l("TimeSlots"),
                                        DATA => $timeslots,
                                        CODE => CODE_SUCCESS
                                    ), REST_Controller::HTTP_OK);
    }
}