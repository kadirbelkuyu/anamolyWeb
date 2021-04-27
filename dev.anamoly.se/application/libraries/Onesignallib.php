<?php
class Onesignallib
{
    protected $os_key;
    protected $os_id;
    public function __construct()
    {
        //parent::__construct();
        $this->os_key = "NmNhMjY0Y2ItMjA4MC00NTY0LThhMDMtZDEzMjE4ODNhMzU5";
        $this->os_id = "5c7254e8-5255-47a2-b45f-9b11c93daecc";
		
    }
    // Send to all subscribers
    /*
     * Send to all subscriber notification
     * @param $message string notification message which want to sent to all
     * @param $hashes_array array  Array of app link buttons
     * array_push($hashes_array, array(
            "id" => "like-button",
            "text" => "Like",
            "icon" => "http://i.imgur.com/N8SN8ZS.png",
            "url" => "https://yoursite.com"
        ));
     *  @param $data_array array of data which we want to sent with notification.
     *  array(
                "foo" => "bar"
            )
     *
     *
     */
    function sendToAll($message,$heading,$hashes_array = array(),$data_array = array("foo" => "bar"),$image="")
    {
        $content = array(
            "en" => $message
        );
		
		$head = array(
            "en" => $heading
        );
		
        $fields = array(
            'app_id' => $this->os_id,
            'included_segments' => array(
                'All'
            ),
            'data' => $data_array,
            'contents' => $content,
            'web_buttons' => $hashes_array,
			"headings" => $head
        );
		if($image!="")
		{
			/* $fields["chrome_web_image"]	= $image;
			$fields["firefox_icon"]	= $image;
			$fields["large_icon"]	= $image;
			$fields["adm_large_icon"]	= $image;
			$fields["big_picture"]	= $image; */
			$fields["chrome_web_image"]	= $image;
			$fields["firefox_icon"]	= $image;
			$fields["big_picture"]	= $image;
			$fields["adm_big_picture"]	= $image;
			$fields["chrome_big_picture"]	= $image;
		}
        return $this->callApi($fields);

    }

    /*
     * Send Notification to specific segments
     * @param $message String  Message which want to send
     * @param $segments_array Array  with segments
     * array('Active Users')
     * @param $data_array array of data which we want to sent with notification.
     *  array(
                "foo" => "bar"
            )
     *
     */
    function sendToSegments($message,$heading,$segments_array=array(),$data_array=array("foo" => "bar")){
        $content = array(
            "en" => $message
        );

		
		$head = array(
            "en" => $heading
        );

        $fields = array(
            'app_id' => $this->os_id,
            'included_segments' => $segments_array,
            'data' => $data_array,
            'contents' => $content,
			"headings" => $head
        );

        return $this->callApi($fields);
    }

    /*
     * Send Notification base on filter tags
     * @param $message String  Message which want to send
     * @param $filter_array Array  with segments
     * array(array("field" => "tag", "key" => "level", "relation" => "=", "value" => "10"),array("operator" => "OR"),array("field" => "amount_spent", "relation" => "=", "value" => "0"))
     * @param $data_array array of data which we want to sent with notification.
     *  array(
                "foo" => "bar"
            )
     *
     */
    function sendWithFilterTags($message,$heading,$filter_array=array(),$data_array=array("foo" => "bar")){
        $content = array(
            "en" => $message
        );
		
		$head = array(
            "en" => $heading
        );
	
        $fields = array(
            'app_id' => $this->os_id,
            'filters' => $filter_array,
            'data' => $data_array,
            'contents' => $content,
			"headings" => $head
        );


        return $this->callApi($fields);
    }
    /*
     * Send Notification base on filter tags
     * @param $message String  Message which want to send
     * @param $player_ids Array  with ids
     * array("6392d91a-b206-4b7b-a620-cd68e32c3a76","76ece62b-bcfe-468c-8a78-839aeaa8c5fa","8e0f21fa-9a5a-4ae7-a9a6-ca1f24294b86")
     * @param $data_array array of data which we want to sent with notification.
     *  array(
                "foo" => "bar"
            )
     *
     */
	
    function sendToPlayerIds($message,$heading,$player_ids=array(),$data_array=array("foo" => "bar")){
        $content = array(
            "en" => $message
        );
		
		$head = array(
            "en" => $heading
        );

        $fields = array(
            'app_id' => $this->os_id,
            'include_player_ids' => $player_ids,
            'data' => $data_array,
            'contents' => $content,
			'headings' => $head 
        );


        return $this->callApi($fields);
    }
    function callApi($fields){
        $fields = json_encode($fields);
		//return $fields;	
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic '.$this->os_key));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}