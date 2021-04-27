<?php class Orders_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'orders';
        $this->primary_key= 'order_id';
    }
	 /**
     * listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = "",$offcet="",$limit=""){
        if(!empty($filter))
        {
            if(key_exists("in",$filter)){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $this->db->where_in(key($where_id),$where_id[key($where_id)]);
            }
            $this->db->where($filter);
        }
        if($search != ""){
            $this->db->or_like(array($this->table_name.'.cat_name_en'=>$search,
                                    $this->table_name.'.cat_name_ar'=>$search,
                                    $this->table_name.'.cat_name_nl'=>$search,
                                    $this->table_name.'.cat_name_tr'=>$search,
                                    $this->table_name.'.cat_name_de'=>$search));
        }
		$this->db->select($this->table_name.".*,oi.total_qty,order_delivery_address.house_no,order_delivery_address.add_on_house_no,order_delivery_address.postal_code,order_delivery_address.street_name,order_delivery_address.area,order_delivery_address.city,order_delivery_address.latitude,order_delivery_address.longitude,users.user_firstname,users.user_lastname,users.user_phone,users.user_email,users.user_company_name,users.user_company_id,vehicles.vehicle_no,vehicles.vehicle_type,vehicles.driver_name,vehicles.driver_phone,delivery_boy.boy_name,delivery_boy.boy_phone,delivery_boy.boy_email,delivery_boy.boy_photo");
        $this->db->join("users","users.user_id = ".$this->table_name.".user_id");
        $this->db->join("order_delivery_address","order_delivery_address.order_id = ".$this->table_name.".".$this->primary_key);
        $this->db->join("delivery_boy","delivery_boy.delivery_boy_id = ".$this->table_name.".delivery_boy_id","left");
        $this->db->join("vehicles","vehicles.vehicle_id = delivery_boy.vehicle_id","left");
        $this->db->join("(select sum(order_qty) as total_qty,order_id from order_items group by order_id ) as oi","oi.order_id = orders.order_id","left");
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
        $this->db->select($this->table_name.".*,order_delivery_address.house_no,order_delivery_address.add_on_house_no,order_delivery_address.postal_code,order_delivery_address.street_name,order_delivery_address.area,order_delivery_address.city,order_delivery_address.latitude,order_delivery_address.longitude,users.user_firstname,users.user_lastname,users.user_phone,users.user_email,users.user_company_name,users.user_company_id,users.android_token,users.ios_token,vehicles.vehicle_no,vehicles.vehicle_type,vehicles.driver_name,vehicles.driver_phone,delivery_boy.boy_name,delivery_boy.boy_phone,delivery_boy.boy_email,delivery_boy.boy_photo");
        $this->db->where($this->table_name.".".$this->primary_key,$id);
        $this->db->join("users","users.user_id = ".$this->table_name.".user_id");
        $this->db->join("delivery_boy","delivery_boy.delivery_boy_id = ".$this->table_name.".delivery_boy_id","left");
        $this->db->join("vehicles","vehicles.vehicle_id = delivery_boy.vehicle_id","left");
        $this->db->join("order_delivery_address","order_delivery_address.order_id = ".$this->table_name.".".$this->primary_key);
        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    function get_order_items($order_id){
        $this->db->select("order_items.*,products.product_image ,products.product_name_en,products.product_name_ar,products.product_name_nl,products.product_name_tr,products.product_name_de,products.unit,products.unit_en,products.unit_ar,products.unit_tr,products.unit_de,products.unit_value,products.picker_note");
        $this->db->join("products","products.product_id = order_items.product_id");
        
        $this->db->where("order_id",$order_id);
        $q = $this->db->get("order_items");
        return $q->result();
    }
    function order_counts_with_status(){
        $this->db->select("count(order_id) as order_total,status, sum(net_amount) as total_net_amount");
        $this->db->where("draft",0);
        $this->db->group_by("status");
        $q = $this->db->get("orders");
        return $q->result();
    }
    function get_orders_by_date($date){
        return $this->get(array("orders.delivery_date",$date));

    }
}
