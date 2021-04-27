<?php class Banners_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'banners';
        $this->primary_key= 'banner_id';
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
            if(key_exists("banners.tag_ids",$filter)){
                $this->db->where("FIND_IN_SET('".$filter["banners.tag_ids"]."',banners.tag_ids) > 0");
                unset($filter["banners.tag_ids"]);
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.banner_title_en'=>$search,
                                    $this->table_name.'.banner_title_ar'=>$search,
                                    $this->table_name.'.banner_title_nl'=>$search,
                                    $this->table_name.'.banner_title_tr'=>$search,
                                    $this->table_name.'.banner_title_de'=>$search));
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
