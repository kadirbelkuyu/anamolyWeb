<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends MY_Controller {
    protected $controller;
    protected $table_name;
    protected $primary_key;
    protected $data;
    public function __construct()
    {
        parent::__construct();
        $this->controller = "admin/".$this->router->fetch_class();
        $this->table_name = "orders";
        $this->primary_key= "order_id";
        $this->data["controller"] = $this->controller;
        $this->data["table_name"] = $this->table_name;
        $this->data["primary_key"] = $this->primary_key;

        if(!_is_admin() && !_is_planner() && !_is_sub_admin("admin/orders")){
            redirect("login");
            exit();
        }

        $this->load->model("orders_model");
        $this->load->model("deliveryboy_model");
        $this->data["deliveryboys"] = $this->deliveryboy_model->get();
    }


    public function index(){
        $this->session->set_userdata("backurl",current_url());
        //$this->data["datatable_export"]=true;




        $this->data["data"] = $this->orders_model->get(array("in"=>array("orders.status"=>
        array(
            ORDER_PENDING,
            ORDER_CONFIRMED,
            ORDER_OUT_OF_DELIVEY,
            ORDER_PAID,
            ORDER_DECLINE,
            ORDER_DELIVERED,
            ORDER_CANCEL
        ))));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function pending(){
        $this->getByStatus(ORDER_PENDING);
    }
    function confirmed(){
        $this->getByStatus(ORDER_CONFIRMED);
    }
    function outdelivery(){
        $this->getByStatus(ORDER_OUT_OF_DELIVEY);
    }
    function delivered(){
        $this->getByStatus(ORDER_DELIVERED);
    }
    function declined(){
        $this->getByStatus(ORDER_DECLINE);
    }
    function canceled(){
        $this->getByStatus(ORDER_CANCEL);
    }
    function unpaid(){
        $this->getByStatus(ORDER_UNPAID);
    }
    function express(){
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->orders_model->get(array("in"=>
        array("orders.status"=>array(ORDER_PENDING,ORDER_CONFIRMED,ORDER_OUT_OF_DELIVEY,ORDER_PAID))
        ,"is_express"=>"1"));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function neworders(){
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->orders_model->get(array("in"=>
        array("orders.status"=>
            array(
                ORDER_PENDING,
                ORDER_CONFIRMED,
                ORDER_OUT_OF_DELIVEY,
                ORDER_PAID,
                ORDER_DECLINE,
                ORDER_DELIVERED,
                ORDER_CANCEL
            )
        ),"DATE(orders.order_date)"=>date(MYSQL_DATE_FORMATE)));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    private function getByStatus($status){
        $this->session->set_userdata("backurl",current_url());
        $this->data["data"] = $this->orders_model->get(array("orders.status"=>$status));
        $this->data["page_content"] = $this->load->view($this->controller."/list",$this->data,true);
        $this->data["page_script"] = $this->load->view($this->controller."/order_script",$this->data,true);
        $this->load->view(BASE_TEMPLATE,$this->data);
    }
    function updatestatus($id){
        header('Content-type: text/json');
        $id = _decrypt_val($id);
        if($id == NULL)
            return;

            $order = $this->orders_model->get_by_id($id);
            if($order == NULL)
                return;

            $status = $this->input->post("status");
            $update = array("status"=>$status);
            $title = "";
            $message = "";
            $delivery_date = date(DEFAULT_DATE_FORMATE,strtotime($order->delivery_date));
            $delivery_time = date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time));
            $delivery_time_end = date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time_end));
            if($status == ORDER_CONFIRMED){
                $title = "Order Confirmed";
                $msg = "Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date# during #delivery_time# - #delivery_time_end#";
                $message = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);
                //$message = _l("Your Order No. ".$order->order_no." is Confrimed, Delivery will proceed on time ".date(DEFAULT_DATE_FORMATE,strtotime($order->delivery_date))." during ".date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time))." - ".date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time_end)));

                $title_nl = "Beställning bekräftad";
                $msg = "Ditt beställningsnummer #order_no# är fastsatt, leverans sker i tid #delivery_date# medan #delivery_time# - #delivery_time_end#";
                $message_nl = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

                $title_ar = "Beställning bekräftad";
                $msg = "Ditt beställningsnummer #order_no# är fastsatt, leverans sker i tid #delivery_date# medan #delivery_time# - #delivery_time_end#";
                $message_ar = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

                $title_tr = "Beställning bekräftad";
                $msg = "Ditt beställningsnummer #order_no# är fastsatt, leverans sker i tid #delivery_date# medan #delivery_time# - #delivery_time_end#";
                $message_tr = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

                $title_de = "Beställning bekräftad";
                $msg = "Ditt beställningsnummer #order_no# är fastsatt, leverans sker i tid #delivery_date# medan #delivery_time# - #delivery_time_end#";
                $message_de = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);
            }
            if($status == ORDER_DECLINE){
                $title = "Order Declined";
                $message = "Your Order No. #order_no# is declined by {APP_NAME}.";
                $message = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message);

                $title_nl = "Beställning nekad";
                $message_nl = "ditt beställningsnummer #order_no# har vägrats av {APP_NAME}.";
                $message_nl = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);

                $title_ar = "Beställning nekad";
                $message_ar = "ditt beställningsnummer #order_no# har vägrats av {APP_NAME}.";
                $message_ar = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);

                $title_tr = "Beställning nekad";
                $message_tr = "ditt beställningsnummer #order_no# har vägrats av {APP_NAME}.";
                $message_tr = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);

                $title_de = "Beställning nekad";
                $message_de = "ditt beställningsnummer #order_no# har vägrats av {APP_NAME}.";
                $message_de = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);
            }
            if($status == ORDER_OUT_OF_DELIVEY){
                $title = "Order Out of Delivery";
                $message = "Your Order No. #order_no# is out of delivery you may track it on your phone.";
                $message = str_replace("#order_no#",$order->order_no,$message);

                $title_nl = "Beställ före leverans";
                $message_nl = "ditt beställningsnummer #order_no# är ute för leverans kan du spåra det på din telefon.";
                $message_nl = str_replace("#order_no#",$order->order_no,$message_nl);

                $title_ar = "Beställ före leverans";
                $message_ar = "ditt beställningsnummer #order_no# är ute för leverans kan du spåra det på din telefon.";
                $message_ar = str_replace("#order_no#",$order->order_no,$message_nl);

                $title_tr = "Beställ före leverans";
                $message_tr = "ditt beställningsnummer #order_no# är ute för leverans kan du spåra det på din telefon.";
                $message_tr = str_replace("#order_no#",$order->order_no,$message_nl);

                $title_de = "Beställ före leverans";
                $message_de = "ditt beställningsnummer #order_no# är ute för leverans kan du spåra det på din telefon.";
                $message_de = str_replace("#order_no#",$order->order_no,$message_nl);
            }
            if($status == ORDER_DELIVERED){
                $title = "Order Delivered";
                $message = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
                $message = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message);

                $title_nl = "Beställning levererad";
                $message_nl = "Tack för att du gick med {APP_NAME} är ditt beställningsnummer #order_no# is bezorgd, snälla dela dina recensioner.";
                $message_nl = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_nl);
            
                $title_ar = "Order Delivered";
                $message_ar = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
                $message_ar = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_ar);

                $title_tr = "Order Delivered";
                $message_tr = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
                $message_tr = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_tr);

                $title_de = "Order Delivered";
                $message_de = "Thanks being with {APP_NAME}, Your Order No. #order_no# is delivered, Please share your reviews.";
                $message_de = str_replace(array("#order_no#","{APP_NAME}"),array($order->order_no,APP_NAME),$message_de);
              }
            
            if($title != "" && $message != ""){
                $this->load->library("onesignallib");
                $player_ids = array();
                if(isset($order->android_token) && $order->android_token != ""){
                    $player_ids[] = $order->android_token;
                }
                if(isset($order->ios_token) && $order->ios_token != ""){
                    $player_ids[] = $order->ios_token;
                }
                $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
                $this->common_model->data_insert("notifications",
                    array("user_id"=>$order->user_id,
                    "title_nl"=>$title_nl,
                    "title_en"=>$title,
                    "title_ar"=>$title_ar,
                    "title_tr"=>$title_tr,
                    "title_de"=>$title_de,
                    "message_nl"=>$message_nl,
                    "message_en"=>$message,
                    "message_ar"=>$message_ar,
                    "message_tr"=>$message_tr,
                    "message_de"=>$message_de,
                    "type"=>NOTIFICATION_TYPE_ORDER,
                    "type_id"=>$order->order_id),true
                );
            }

            $this->common_model->data_update("orders",$update,array("order_id"=>$id));

            $data["message"] = $this->message_model->action_mesage("update",_l("Order Status"),true);
            $order->status = $status;
            $data["data"] = $this->load->view("admin/orders/order_status_label",array("dt"=>$order,"count"=>$this->input->post("row_index")),true);
            $data["response"] = true;
            echo json_encode($data);
        }

    public function delete($id){
        $id = _decrypt_val($id);

        $row = $this->orders_model->get_by_id($id);
        if(!empty($row)){
			$pk=$this->primary_key;
            $this->common_model->data_remove($this->table_name,array($this->primary_key=>$row->$pk),false);
            $data['responce'] = true;
            $data['message'] = $this->message_model->action_mesage("delete",_l("Order"),true);
            echo json_encode($data);
        }else{
            $data['responce'] = false;
            $data['error'] = _l("Order not available");
            echo json_encode($data);
        }
    }

    public function add(){
            //$this->action();
            $this->data["select2"] = true;
            $this->data["active_menu_link"] = array(site_url("admin/orders"));

            $this->data["field"] = $this->input->post();
            $this->data["page_content"] = $this->load->view($this->controller."/add",$this->data,true);
            $this->load->view(BASE_TEMPLATE,$this->data);
    }

	public function get_by_id()
	{
		$id=$this->input->post("id");
		$data=$this->orders_model->get(array($this->table_name.'.'.$this->primary_key=>$id));
		echo json_encode($data);
	}
    public function assign_deliveryboy(){
        header('Content-type: text/json');
        $order_id = $this->input->post("assign_order_id");
        $count = $this->input->post("row_index") + 1;
        $id = _decrypt_val($this->input->post("assign_order_id"));
        $delivery_boy_id = $this->input->post("delivery_boy_id");
        $this->load->model("deliveryboy_model");
        if(isset($_POST["delivery_boy_id"])){
            $order = $this->orders_model->get_by_id($id);

            $delivery_boy_id = $this->input->post("delivery_boy_id");
            $deliveryboy = $this->deliveryboy_model->get_by_id($delivery_boy_id);
            $this->common_model->data_update("orders",array("vehicle_id"=>$deliveryboy->vehicle_id,"delivery_boy_id"=>$deliveryboy->delivery_boy_id,"status"=>ORDER_CONFIRMED),array("order_id"=>$id));
            $action_button[] = "<a href='javascript:updateStatus(\"".site_url("admin/orders/updatestatus/".$order_id)."\",".$count.",".ORDER_OUT_OF_DELIVEY.")' >"._l("Out Of Delivery").'</a>';
            $link = '<div class="dropdown">
                        <button class="btn btn-info btn-xs dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            '._l("Confirmed").'
                        </button>';
                        if(!empty($action_button)){
                            $link .= '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                                foreach($action_button as $btn){
                                    $link .="<li class='dropdown-item'>".$btn."</li>";
                                }
                            $link .= '</ul>';
                         }
                         $link .= '</div>';
            $data = array("response"=>true,"data"=>$link);
            echo json_encode($data);


            $title = _l("Order Assigned");
            $message = _l("Assigned with new Order No. #order_no#, Please Picked Items.");
            $message = str_replace("#order_no#",$order->order_no,$message);

            if($title != "" && $message != ""){
                $this->load->library("onesignallib");
                $player_ids = array();
                if(isset($deliveryboy->android_token) && $deliveryboy->android_token != ""){
                    $player_ids[] = $deliveryboy->android_token;
                }
                if(isset($deliveryboy->ios_token) && $deliveryboy->ios_token != ""){
                    $player_ids[] = $deliveryboy->ios_token;
                }
                if(!empty($player_ids)){
                    $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
                }
            }
            $delivery_date = date(DEFAULT_DATE_FORMATE,strtotime($order->delivery_date));
            $delivery_time = date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time));
            $delivery_time_end = date(DEFAULT_TIME_FORMATE,strtotime($order->delivery_time_end));

            $title = "Order Confirmed";
            $msg = "Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date# during #delivery_time# - #delivery_time_end#";
            $message = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

            $title_nl = "Beställning bekräftad";
            $msg = "Ditt beställningsnummer #order_no# är fastsatt, leverans sker i tid #delivery_date# medan #delivery_time# - #delivery_time_end#";
            $message_nl = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

            $title_ar = "Order Confirmed";
            $msg = "Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date# during #delivery_time# - #delivery_time_end#";
            $message_ar = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

            $title_tr = "Order Confirmed";
            $msg = "Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date# during #delivery_time# - #delivery_time_end#";
            $message_tr = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

            $title_de = "Order Confirmed";
            $msg = "Your Order No. #order_no# is Confrimed, Delivery will proceed on time #delivery_date# during #delivery_time# - #delivery_time_end#";
            $message_de = str_replace(array("#order_no#","#delivery_date#","#delivery_time#","#delivery_time_end#"),array($order->order_no,$delivery_date,$delivery_time,$delivery_time_end),$msg);

            if($title != "" && $message != ""){
                $this->load->library("onesignallib");
                $player_ids = array();
                if(isset($order->android_token) && $order->android_token != ""){
                    $player_ids[] = $order->android_token;
                }
                if(isset($order->ios_token) && $order->ios_token != ""){
                    $player_ids[] = $order->ios_token;
                }
                $res=$this->onesignallib->sendToPlayerIds($message,$title,$player_ids,array("type"=>NOTIFICATION_TYPE_ORDER,"ref_id"=>$order->order_id));
                $this->common_model->data_insert("notifications",
                    array("user_id"=>$order->user_id,
                    "title_nl"=>$title_nl,
                    "title_en"=>$title,
                    "title_ar"=>$title_ar,
                    "title_tr"=>$title_tr,
                    "title_de"=>$title_de,
                    "message_nl"=>$message_nl,
                    "message_en"=>$message,
                    "message_ar"=>$message_ar,
                    "message_tr"=>$message_tr,
                    "message_de"=>$message_de,
                    "type"=>NOTIFICATION_TYPE_ORDER,
                    "type_id"=>$order->order_id),true
                );
            }
        }
    }
    public function receipt($id,$type=""){
        $id = _decrypt_val($id);

        $this->load->model("deliveryboy_model");
        if(isset($_POST["delivery_boy_id"])){
            $delivery_boy_id = $this->input->post("delivery_boy_id");
            $deliveryboy = $this->deliveryboy_model->get_by_id($delivery_boy_id);
            $this->common_model->data_update("orders",array("vehicle_id"=>$deliveryboy->vehicle_id,"delivery_boy_id"=>$deliveryboy->delivery_boy_id),array("order_id"=>$id));
        }

        $row = $this->orders_model->get_by_id($id);
        $items = $this->orders_model->get_order_items($id);


        $deliveryboys = $this->deliveryboy_model->get();

        $this->data["deliveryboys"] = $deliveryboys;
        $this->data["order"] = $row;
        $this->data["items"] = $items;
        $this->data["select2"] = true;
        $this->data["active_menu_link"] = array(site_url("admin/orders"));

        $this->data["field"] = $this->input->post();
        $this->data["setting"] = get_options_by_type("billing");
        if($type == "packing"){
            $this->data["page_content"] = $this->load->view($this->controller."/packing_slip",$this->data,true);
        }else{
            $this->data["page_content"] = $this->load->view($this->controller."/receipt",$this->data,true);
        }
        $this->load->view(BASE_TEMPLATE,$this->data);
    }


}
