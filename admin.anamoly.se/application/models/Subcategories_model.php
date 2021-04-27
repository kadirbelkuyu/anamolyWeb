<?php class Subcategories_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'sub_categories';
        $this->primary_key= 'sub_category_id';
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
            $this->db->or_like(array($this->table_name.'.sub_cat_name_en'=>$search,
                                    $this->table_name.'.sub_cat_name_ar'=>$search,
                                    $this->table_name.'.sub_cat_name_nl'=>$search,
                                    $this->table_name.'.sub_cat_name_tr'=>$search,
                                    $this->table_name.'.sub_cat_name_de'=>$search));
        }
		$this->db->select("{$this->table_name}.*,categories.cat_name_en,categories.cat_name_ar,categories.cat_name_nl,categories.cat_name_tr,categories.cat_name_de");
        $this->db->join("categories","categories.category_id = ".$this->table_name.".category_id","left");
        $this->db->where($this->table_name.".draft","0");
        //$this->db->order_by($this->table_name.".".$this->primary_key." desc");
        $this->db->order_by("sub_cat_sort_order");
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
