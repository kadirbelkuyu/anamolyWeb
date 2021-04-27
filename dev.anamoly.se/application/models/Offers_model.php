<?php class Offers_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'product_offers';
        $this->primary_key= 'product_offer_id';
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
        $this->db->select("{$this->table_name}.*,product_group.product_name_nl,product_group.product_name_ar,product_group.product_name_en");
        //$this->db->join("products","products.product_id = product_offerss.product_id and products.draft = 0");
        $this->db->join("(Select GROUP_CONCAT(products.product_name_nl SEPARATOR ',') as product_name_nl,GROUP_CONCAT(products.product_name_en SEPARATOR ',') as product_name_en, GROUP_CONCAT(products.product_name_ar SEPARATOR ',') as product_name_ar, product_offer_id from products join product_offers_map on product_offers_map.product_id = products.product_id group by product_offer_id ) as product_group","product_group.product_offer_id = product_offers.product_offer_id");
        
        if(!empty($filter))
        {
            if(key_exists("product_maps.category_id",$filter) || key_exists("product_maps.sub_category_id",$filter) || key_exists("product_maps.group_id",$filter))
            {
                $this->db->join("product_offers_map","product_offers_map.product_offer_id = product_offers.product_offer_id","left");
                $this->db->join("product_maps","product_maps.product_id = product_offers_map.product_id","left");
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
