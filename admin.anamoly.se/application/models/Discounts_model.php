<?php class Discounts_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'product_discounts';
        $this->primary_key= 'product_discount_id';
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
        $this->db->select("{$this->table_name}.*,products.product_name_nl,products.product_name_ar,products.product_name_en");
        $this->db->join("products","products.product_id = product_discounts.product_id and products.draft = 0");
        
        if(!empty($filter))
        {
            if(key_exists("product_maps.category_id",$filter) || key_exists("product_maps.sub_category_id",$filter) || key_exists("product_maps.group_id",$filter))
            {
                $this->db->join("product_maps","product_maps.product_id = products.product_id","left");
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array('products.product_name_en'=>$search,
                                    'products.product_name_ar'=>$search,
                                    'products.product_name_nl'=>$search));
        }
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