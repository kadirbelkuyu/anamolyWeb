<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'third_party/vendor/autoload.php');
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
class Apicall
{
    protected $api_key;
    protected $CI;
    protected $lang;
    public function __construct()
    {
        //parent::__construct();
        $this->CI =& get_instance();
        $this->lang = _language();
        if($this->lang == "swedish"){
          $this->lang = "dutch";
        }
        $this->api_key = "99e1bb00b05ec7b10343b92815a2e7f4";
    }
    /**
    ** @params $end_point = "/rest/api"
    **/
    function request($end_point,$form_params=array(),$method="POST"){
        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => REMOTE_URL,
                // You can set any number of default request options.
                'timeout'  => 300.0,
            ]);
            $response = $client->request($method, "/index.php".$end_point, [
                'form_params' => $form_params,
                'headers' => ['X-API-KEY' => $this->api_key,'X-APP-LANGUAGE'=>$this->lang,'X-APP-DEVICE'=>'web','X-APP-VERSION'=>'1.0']
            ]);

            $code = $response->getStatusCode();
            if($code == 200){
                $body = $response->getBody();
                //print_r($body->getContents());
                return json_decode($body->getContents());

                //if($response_array->status == 1){
                //    redirect($response_array->gateway_response);
                //}else{
                //    redirect('payment/gcc_responce/'.$order_id);
                //}
            }
        } catch (ClientException $e) {

            return array("responce"=>false,"message"=>$e->getMessage());
            //print_r($resObj);
        }
    }

     function requestmultipart($end_point,$form_params=array(),$method="POST"){
        try {
            $client = new Client([
                // Base URI is used with relative requests
                'base_uri' => REMOTE_URL,
                // You can set any number of default request options.
                'timeout'  => 300.0,
            ]);
            $response = $client->request($method, "/index.php/".$end_point, [
                'multipart' => $form_params,
                'headers' => ['X-API-KEY' => $this->api_key,'X-APP-LANGUAGE'=>$this->lang,'X-APP-DEVICE'=>'web','X-APP-VERSION'=>'1.0']
            ]);

            $code = $response->getStatusCode();
            if($code == 200){

                $body = $response->getBody();

                //print_r($body->getContents());
                return json_decode($body->getContents());

                //if($response_array->status == 1){
                //    redirect($response_array->gateway_response);
                //}else{
                //    redirect('payment/gcc_responce/'.$order_id);
                //}
            }
        } catch (ClientException $e) {

            return array("responce"=>false,"message"=>$e->getMessage());
            //print_r($resObj);
        }
    }
}
