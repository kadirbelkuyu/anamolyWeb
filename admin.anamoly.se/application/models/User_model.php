<?php class User_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'users';
        $this->primary_key= 'user_id';
    }
	 /**
     * Data listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        $select_fields = "";
        if(!empty($filter))
        {   
            if(key_exists("in",$filter)){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $val = $where_id[key($where_id)];
                if(!is_array($where_id[key($where_id)])){
                    $val = explode(",",$val);
                }
                $this->db->where_in(key($where_id),$val);
            }
            if(key_exists("user_type_id",$filter)){
                if($filter["user_type_id"] == USER_SUBADMIN){
                    $this->db->join("user_roles","user_roles.role_id = users.role_id");
                    $select_fields .=",user_roles.role_title";
                }
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.user_firstname'=>$search,
                                    $this->table_name.'.user_last'=>$search,
                                    $this->table_name.'.user_email'=>$search,
                                    $this->table_name.'.user_phone'=>$search,
                                    $this->table_name.'.user_company_name'=>$search,
                                    $this->table_name.'.user_company_id'=>$search));
        }
		$this->db->select("{$this->table_name}.*,user_address.house_no,user_address.user_address_id,user_address.add_on_house_no,user_address.postal_code,user_address.street_name,user_address.area,user_address.city,user_address.latitude,user_address.longitude,ifnull(postal_codes.postal_code_id,0) as postal_code_id $select_fields");
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("user_address","user_address.user_id = users.user_id","left");
        $this->db->join("postal_codes","postal_codes.postal_code = user_address.postal_code","left");
		$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
    function check_match_password($old_pass,$userid)
	{	
		$q=$this->db->get_where('users',array('user_id'=>$userid,'user_password'=>$old_pass));
		if($q->num_rows()>0)
		{
			return 1;
		}
		else
		{
			return 0;
		}			
	}
    
    function get_by_id($id){
        $this->db->where($this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    
    function get_address($user_id){
        $this->db->where("user_address.user_id",$user_id);
        $this->db->where("user_address.draft","0");
        $q = $this->db->get("user_address");
        return $q->result();
    }
    
    function get_family($user_id){
        $this->db->where("user_family.user_id",$user_id);
        $this->db->where("user_family.draft","0");
        $q = $this->db->get("user_family");
        return $q->row();
    }
    
    function get_settings($user_id){
        $this->db->where("user_settings.user_id",$user_id);
        $q = $this->db->get("user_settings");
        return $q->row();
    }
    function count($filter){
        if(!empty($filter))
        {   
            if(key_exists("in",$filter)){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $val = $where_id[key($where_id)];
                if(!is_array($where_id[key($where_id)])){
                    $val = explode(",",$val);
                }
                $this->db->where_in(key($where_id),$val);
            }
            $this->db->where($filter);
        }
        $this->db->where($this->table_name.".draft","0");
        return $this->db->count_all_results($this->table_name);
    }
}