<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Idealpayment extends MY_Controller
{

    function checkout($checkoutid){
        $this->load->library("payvisioncheckout");
        if($this->payvisioncheckout->checkOutStatusIsPending($checkoutid)){
            $this->load->view('checkout', array("checkoutId"=>$checkoutid));
        }
    }



    function response(){
        $get = $this->input->get();
        //header('Content-type: text/json');
        $data = array();
        $this->load->library("payvision");
        $get = $this->input->get();
        if(isset($get["id"]) && isset($get["checkoutId"])){
            $payment_response = $this->payvision->get_payment($get["id"]);
            $payment_ref = $get["checkoutId"];
            $this->db->where("payment_ref",$payment_ref);
            $q = $this->db->get("orders");
            $order = $q->row();
			if(!empty($order)){
            $order_id = $order->order_id;

            if($payment_response["response"]){
                $this->load->model("orders_model");
                $order_data = $this->orders_model->get_by_id($order_id);
                $order_items = $this->orders_model->get_order_items($order_id);
                $order_data->items = $order_items;
                $user_id = $order_data->user_id;

                $this->db->where("user_id",$user_id);
                $this->db->delete("cart");

                $this->load->model("email_model");
                $this->email_model->send_order_mail($order_data,$order_items);

                $msg = _l("Thanks for your order with {APP_NAME}, Order with No #order_no# amount #net_amount# is placed successfully");
                $msg = str_replace(array("#order_no#","#net_amount#","APP_NAME"),array($order_data->order_no,$order_data->net_amount,APP_NAME),$msg);


                $this->common_model->data_update("orders",array("payment_log"=>json_encode($payment_response),"status"=>ORDER_PAID),array("order_id"=>$order_id));
                $data = array(
                    RESPONCE => true,
                    MESSAGE => $msg,
                    DATA => $order_data,
                    CODE => CODE_SUCCESS
                );
            }else{
                $this->db->where("order_id",$order_id);
                $this->db->delete("orders");

                $this->db->where("order_id",$order_id);
                $this->db->delete("order_items");

                $this->db->where("order_id",$order_id);
                $this->db->delete("order_delivery_address");

                $data = array(
                    RESPONCE => false,
                    MESSAGE => $payment_response["message"],
                    DATA =>$payment_response["message"],
                    CODE => 101
                );
            }
			}else{
				$data = array(
                    RESPONCE => false,
                    MESSAGE => _("Invalid Order Referance"),
                    DATA =>_("Invalid Order Referance"),
                    CODE => 101
                );
			}
        }
        if($data[RESPONCE]){
            $order = $data[DATA];
            redirect("anamoly://success/".$order->order_id);
        }else{
            redirect("anamoly://failed");
        }
        //echo json_encode($data);
    }
    function response_ing(){
        header('Content-type: text/json');
        $data = array();
        $this->load->library("ideal");
        $get = $this->input->get();
        if(isset($get["project_id"]) && isset($get["order_id"])){
            $order = $this->ideal->orderdetails($get["project_id"],$get["order_id"]);
            $order_id = $order["merchant_order_id"];
            if($order["status"] == "completed"){

                $this->load->model("orders_model");
                $order_data = $this->orders_model->get_by_id($order_id);
                $order_items = $this->orders_model->get_order_items($order_id);
                $order_data->items = $order_items;
                $user_id = $order_data->user_id;

                $this->db->where("user_id",$user_id);
                $this->db->delete("cart");

                $this->load->model("email_model");
                $this->email_model->send_order_mail($order_data,$order_items);

                $msg = _l("Thanks for your order with {APP_NAME}, Order with No #order_no# amount #net_amount# is placed successfully");
                $msg = str_replace(array("#order_no#","#net_amount#","{APP_NAME}"),array($order_data->order_no,$order_data->net_amount,APP_NAME),$msg);


                $this->common_model->data_update("orders",array("payment_ref"=>$get["order_id"],"payment_log"=>json_encode($order),"status"=>ORDER_PAID),array("order_id"=>$order_id));
                $data = array(
                    RESPONCE => true,
                    MESSAGE => $msg,
                    DATA => $order_data,
                    CODE => CODE_SUCCESS
                );
            }else{
                $this->db->where("order_id",$order_id);
                $this->db->delete("orders");

                $this->db->where("order_id",$order_id);
                $this->db->delete("order_items");

                $this->db->where("order_id",$order_id);
                $this->db->delete("order_delivery_address");

                $data = array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry failed to make payment"),
                    DATA =>_l("Sorry failed to make payment"),
                    CODE => 101
                );
            }
        }
        echo json_encode($data);
    }
    function test(){
        $this->load->library("payvision/payvision");
        //$res = $this->payvision->paynow("39",47.48,APP_NAME." Order #30");
        //redirect($res["url"]);
        //$this->payvision->get_payment("8f670365-7ff0-401c-84b8-3d6e2413c211");
        $this->payvision->refund("8f670365-7ff0-401c-84b8-3d6e2413c211");
    }
    function webhook(){
        $id = $this->input->post("id");
        if($id == NULL){
            echo "Invalid referance";
            exit;
        }

        $this->load->library("mollie");
        $response = $this->mollie->webhook($id);
        if($response["response"]){

        }else{

        }
    }
}
