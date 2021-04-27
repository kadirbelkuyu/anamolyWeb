<?php class Productstocks_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'product_stock';
        $this->primary_key= 'product_stock_id';
    }
	 /**
     * Data listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = "",$offcet="",$limit="",$ordering=""){
        $return_extra_fields = "";
        if(!empty($filter))
        {          
            if(key_exists("or",$filter)){
                $where_id = $filter["or"];
                unset($filter["or"]);                
                $this->db->where($where_id);
            }
                      
            $this->db->where($filter);
        }
        $this->db->join("products","products.product_id = ".$this->table_name.".product_id");
        $this->db->join("product_maps","product_maps.product_id = products.product_id","left");
                   
        if($search != ""){
            $this->db->where("(products.product_name_en like '%".$search."%' or products.product_name_ar like '%".$search."%'  or products.product_name_nl like '%".$search."%' or  DATE_FORMAT(".$this->table_name.".stock_date,'%Y-%m-%d') like DATE_FORMAT('".date("Y-m-d",strtotime($search))."','%Y-%m-%d') )");
        }
       
        $this->db->distinct();
		$this->db->select("{$this->table_name}.*,products.product_name_en,products.product_name_ar,products.product_name_nl");
        $this->db->where($this->table_name.".draft","0");
      
         if($ordering != ""){
            $this->db->order_by($ordering);
        }else{
            $this->db->order_by($this->table_name.".".$this->primary_key." desc");
        }
		
        if($offcet !="" && $limit != ""){
            $this->db->limit($limit,$offcet);
        }
        $q = $this->db->get($this->table_name);
        
        return $q->result();
    }
    
 function get_by_id($id){
        $this->db->where($this->table_name.".".$this->primary_key,$id);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
   
    function getoutofstocks($filter = array(),$stockalert){
      
        if(!empty($filter))
        {   
            if(key_exists("in",$filter)){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $this->db->where_in(key($where_id),$where_id[key($where_id)]);
            }
           
            if(key_exists("or",$filter)){
                $where_id = $filter["or"];
                unset($filter["or"]);               
                $this->db->where($where_id);
            }
           
            $this->db->where($filter);
        }
        
        $this->db->where("(ifnull(product_stock.productstock,0)-ifnull(order_items.orderqty,0))<=".$stockalert);
        
        $this->db->join("product_maps","product_maps.product_id = products.product_id","left");       
        $this->db->join("categories","categories.category_id = product_maps.category_id","left");
        $this->db->join("sub_categories","sub_categories.sub_category_id = product_maps.sub_category_id","left");
        
        $this->db->distinct();
		$this->db->select("products.product_name_nl,ifnull(product_stock.productstock,0) as productstock,ifnull(order_items.orderqty,0) as orderqty,(ifnull(product_stock.productstock,0)-ifnull(order_items.orderqty,0)) as finalstock");
        $this->db->where("products.draft","0");      
        $this->db->join("(select sum(product_stock.qty) as productstock,product_stock.product_id from product_stock where product_stock.draft = 0 group by product_stock.product_id) as product_stock","product_stock.product_id =products.product_id","left");
        $this->db->join("(select sum(order_items.order_qty) as orderqty,order_items.product_id from orders inner join order_items on orders.order_id=order_items.order_id where orders.draft = 0 and orders.status not in(".ORDER_CANCEL.",".ORDER_DECLINE.") group by order_items.product_id) as order_items","order_items.product_id =products.product_id","left");
  
        $this->db->order_by("products.product_name_nl");
        $q = $this->db->get("products");        
        return $q->result();
    }
}