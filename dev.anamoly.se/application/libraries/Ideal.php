<?php include_once APPPATH . 'third_party/ing/vendor/autoload.php';

use \GingerPayments\Payment\Ginger;
class Ideal
{
    protected $api_key;
    protected $client;
    protected $return_url;
    public function __construct()
    {
        //parent::__construct();
        
        $this->api_key = "95a4f3e99f434f8db5f688b8b89cd0e4";
        $this->client = Ginger::createClient($this->api_key, 'ingcheckout');
        $this->return_url = site_url("idealpayment/response");
    }
    function paynow($order_id,$amount,$desc){
        
        $order = $this->client->createIdealOrder($amount,'EUR','INGBNL2A',$desc,$order_id,$this->return_url);
        $paymentUrl = $order->firstTransactionPaymentUrl();
        if($paymentUrl != NULL && $paymentUrl != ""){
            return "".$paymentUrl;
        }else{
            return "";
        }       
        
        
        // print_r($order);
    }
    function orderdetails($project_id,$order_id){
        $order = $this->client->getOrder($order_id);
        return $order->toArray(); 
    }
}