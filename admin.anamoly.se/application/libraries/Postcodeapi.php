<?php
class Postcodeapi
{
    protected $os_key, $os_url;
    protected $is_production = true;
    public function __construct()
    {
        //parent::__construct();
        if($this->is_production){
            $this->os_key = "";
            $this->os_url = "";
        }else{

            $this->os_key = "";
            $this->os_url = "";
        }
    }
    function get($postal_code,$house_no){
        return $this->callApi($postal_code,$house_no);
    }
    function callApi($postal_code,$house_no){
      /*
        $this->os_url = $this->os_url."/".$postal_code."/".$house_no;


		//return $fields;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->os_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'X-Api-Key: '.$this->os_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, FALSE);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
        */
        return (object)array("street"=>"","city"=>"","province"=>"","municipality"=>"","location"=>array("0","0"),"postal_code"=>$postal_code,"house_no"=>$house_no);
    }
}
