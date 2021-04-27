<?php class Address_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_address';
        $this->primary_key= 'user_address_id';
    }
	 /**
     * Product category Data listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {
            
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.city'=>$search,
                                    $this->table_name.'.postal_code'=>$search,
                                    $this->table_name.'.street_name'=>$search,
                                    $this->table_name.'.area'=>$search));
        }
		$this->db->select("{$this->table_name}.*,users.user_firstname,users.user_lastname,users.user_phone,users.user_company_name,users.user_company_id");
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("users","users.user_id = ".$this->table_name.".user_id");
		$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        return $q->result();

    }
	/**
	* get product category by id
	* @param string $id for product category id
	* @return object
	*/ 	
    function get_by_id($id){
        $this->db->where($this->table_name.".".$this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
}
