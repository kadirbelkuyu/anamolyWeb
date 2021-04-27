<?php class Attachment_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'attachments';
        $this->primary_key= 'attachment_id';
    }
	 /**
     * add attachment
     * @param array $attachment add file 
     * @param string $classis_id classis id
     * @param string $type for type
     * @param string $type_id for type id
     * @return null
     */
	public function add_attachment($attachment,$classis_id,$type,$type_id)
	{
		$cond_data=array();
		$add_data=array();
		$cond_data['type']=$add_data['type']=$type;
		$cond_data['type_id']=$add_data['type_id']=$type_id;
		
		$existExe=$this->db->get_where($this->table_name,$cond_data);
		$existData=$existExe->row();
		
		$add_data['file']=$attachment;
		$add_data['file_type']=pathinfo(base_url($attachment), PATHINFO_EXTENSION);
		$add_data['file_size']=filesize($attachment);
		$add_data['classis_id']=$classis_id;
		if(!empty($existData))
		{
			$this->common_model->data_update($this->table_name,$add_data,$cond_data,true);
		}
		else
		{
			$id = $this->common_model->data_insert($this->table_name,$add_data,true);
		}
	}
	
    

}