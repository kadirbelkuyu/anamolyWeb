<?php class Referralcodes_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'coupons_referral';
        $this->primary_key= 'coupon_id';
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
            if(key_exists("user_id",$filter)){
                $this->db->where_in("users",array("0",$filter["user_id"]));
                unset($filter["user_id"]);
            }
            if(key_exists("validity",$filter)){
                $this->db->where("validity_start <=",date(MYSQL_DATE_FORMATE,strtotime($filter["validity"])));
                $this->db->where("validity_end >=",date(MYSQL_DATE_FORMATE,strtotime($filter["validity"])));
                
                unset($filter["validity"]);
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.coupon_code'=>$search));
        }
		$this->db->select("{$this->table_name}.*");
        $this->db->where($this->table_name.".draft","0");
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
