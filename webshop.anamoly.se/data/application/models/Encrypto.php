<?php class Encrypto extends CI_Model
{
    protected $key;

    public function __construct()
    {
        parent::__construct();
        /*
        $this->encryption->initialize(
            array(
                'cipher' => 'aes-256',
                'mode' => 'ctr',
                'key' => $this->encryption->create_key(32)
            )
        );
        */
    }
    public function encrypt($plain_text){
        return $this->encryption->encrypt($plain_text);
    }
    public function decrypt($ciphertext){
        return $this->encryption->decrypt($ciphertext);

    }
}
