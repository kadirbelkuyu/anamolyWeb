<?php class Products_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'products';
        $this->primary_key= 'product_id';
    }
	 /**
     * Data listing with filter
     * @param array $filter add database fields with value in array for filter and condition
     * @param string $search search value from product category table
     * @param string $offcet for set starting point of limit
     * @param string $limit for set number of rows in limit
     * @return array
     */
    function get($filter = array(),$search = "",$offcet="",$limit="",$select="",$group_concat=false,$ordering=""){
        $return_extra_fields = "";
        if(!empty($filter))
        {
            if(key_exists("in",$filter)){
                $where_id = $filter["in"];
                unset($filter["in"]);
                $this->db->where_in(key($where_id),$where_id[key($where_id)]);
            }
            if(key_exists("cart_user_id",$filter)){
                $user_id = $filter["cart_user_id"];
                unset($filter["cart_user_id"]);

                /*

                $this->db->join("cart","cart.product_id = products.product_id");
                $this->db->where("cart.user_id",$user_id);
                $select = "{$this->table_name}.*,cart.qty,cart.cart_id";
                */
                $this->db->join("(Select sum(qty) as qty, product_id from cart where user_id = ".$user_id." group by product_id ) as cart_qty","cart_qty.product_id = products.product_id","left");
                $return_extra_fields .= ",ifnull(cart_qty.qty,0) as cart_qty";
            }
            if(key_exists("or",$filter)){
                $where_id = $filter["or"];
                unset($filter["or"]);
                //$this->db->or_where($where_id);
                $this->db->where($where_id);
            }
            if(key_exists("user_id",$filter)){
                $this->db->join("order_items","order_items.product_id = products.product_id");
                $this->db->join("orders","order_items.order_id = orders.order_id");
                $this->db->where("orders.user_id",$filter["user_id"]);

                unset($filter["user_id"]);
            }
            if(key_exists("products.product_tags",$filter)){
                $this->db->where("FIND_IN_SET('".$filter["products.product_tags"]."',products.product_tags) > 0");
                unset($filter["products.product_tags"]);
            }
            if(key_exists("product_groups.category_id",$filter) || key_exists("product_groups.sub_category_id",$filter)){

            }
            $this->db->where($filter);
        }

        $this->db->join("product_maps","product_maps.product_id = ".$this->table_name.".product_id","left");
        $this->db->join("product_groups","product_groups.product_group_id = product_maps.group_id","left");
        $this->db->join("categories","categories.category_id = product_maps.category_id","left");
        $this->db->join("sub_categories","sub_categories.sub_category_id = product_maps.sub_category_id","left");

        if($group_concat){
            $return_extra_fields .= ",GROUP_CONCAT(product_groups.group_name_nl SEPARATOR ',') as group_name_nl";
            $this->db->group_by("products.product_id");
        }else{
            $return_extra_fields .= ",product_maps.category_id,product_maps.sub_category_id,product_maps.group_id,product_groups.product_group_id,product_groups.group_name_en,product_groups.group_name_ar,product_groups.group_name_nl,product_groups.group_name_tr,product_groups.group_name_de";
        }
        if($search != ""){
            $this->db->where("(".$this->table_name.".product_name_en like '%".$search."%' or ".$this->table_name.".product_name_ar like '%".$search."%' or ".$this->table_name.".product_barcode like '%".$search."%' or ".$this->table_name.".product_name_nl like '%".$search."%'  or  DATE_FORMAT(".$this->table_name.".created_at,'%Y-%m-%d') like DATE_FORMAT('".date("Y-m-d",strtotime($search))."','%Y-%m-%d') )");
        }
        if($select == ""){
            $select = "{$this->table_name}.*";
        }
        $this->db->distinct();
		$this->db->select("$select,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,product_offers.offer_discount,product_offers.offer_type,product_offers.number_of_products,product_offers.product_offer_id,ifnull(product_stock.productstock,0) as productstock,ifnull(order_stock_items.orderqty,0) as orderqty,(ifnull(product_stock.productstock,0)-ifnull(order_stock_items.orderqty,0)) as finalstock {$return_extra_fields}");
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("(select product_discounts.* from product_discounts where product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0 group by product_discounts.product_id ) as product_discounts","product_discounts.product_id = ".$this->table_name.".product_id","left");
        $this->db->join("(select product_offers.* , product_id from product_offers join product_offers_map on product_offers.product_offer_id = product_offers_map.product_offer_id and product_offers.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_offers.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_offers.status = 1 and product_offers.draft = 0 group by product_offers_map.product_id ) as product_offers", "product_offers.product_id = products.product_id","left");

        $this->db->join("(select sum(product_stock.qty) as productstock,product_stock.product_id from product_stock where product_stock.draft = 0 group by product_stock.product_id) as product_stock","product_stock.product_id =".$this->table_name.".product_id","left");
        $this->db->join("(select sum(order_items.order_qty) as orderqty,order_items.product_id from orders inner join order_items on orders.order_id=order_items.order_id where orders.draft = 0 and orders.status not in(".ORDER_CANCEL.",".ORDER_DECLINE.") group by order_items.product_id) as order_stock_items","order_stock_items.product_id =".$this->table_name.".product_id","left");

        //$this->db->join("product_discounts_map","product_discounts_map.product_id = ".$this->table_name.".product_id","left");
        //$this->db->join("product_discounts","product_discounts.product_discount_id = product_discounts_map.product_discount_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
        //$this->db->join("product_discounts","FIND_IN_SET(".$this->table_name.".product_id, product_discounts.product_id ) > 0 and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");

       // $this->db->join("(Select product_image,product_id from product_images group by product_id order by sort_order) as p_image","p_image.product_id = ".$this->table_name.".product_id","left");
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

    function get_images($id){
        $this->db->where("product_images.draft","0");
        $this->db->order_by("product_images.sort_order");
        $this->db->where("product_images.product_id",$id);
        $q = $this->db->get("product_images");
        return $q->result();
    }
    function get_product_image_by_id($id){
        $this->db->where("product_images.product_image_id",$id);
        $q = $this->db->get("product_images");
        return $q->row();
    }
    function get_by_id($id,$filter=array()){
        $this->db->where($this->table_name.".draft","0");
        $this->db->join("(select product_discounts.* from product_discounts where product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0 group by product_discounts.product_id ) as product_discounts","product_discounts.product_id = ".$this->table_name.".product_id","left");
        $this->db->join("(select product_offers.* , product_id from product_offers join product_offers_map on product_offers.product_offer_id = product_offers_map.product_offer_id and product_offers.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_offers.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_offers.status = 1 and product_offers.draft = 0 group by product_offers_map.product_id ) as product_offers", "product_offers.product_id = products.product_id","left");

        //$this->db->join("product_discounts_map","product_discounts_map.product_id = ".$this->table_name.".product_id","left");
        //$this->db->join("product_discounts","product_discounts.product_discount_id = product_discounts_map.product_discount_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
        //$this->db->join("product_discounts","FIND_IN_SET(".$this->table_name.".product_id, product_discounts.product_id ) > 0 and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");

        //$this->db->join("(Select product_image,product_id from product_images group by product_id order by sort_order) as p_image","p_image.product_id = ".$this->table_name.".product_id","left");
        if($id!="")
            $this->db->where($this->table_name.".".$this->primary_key,$id);

            $return_extra_fields = "";
        if(!empty($filter)){
            if(is_array($filter) && key_exists("cart_user_id",$filter)){
                $user_id = $filter["cart_user_id"];
                unset($filter["cart_user_id"]);
                $this->db->join("(Select sum(qty) as qty, product_id from cart where user_id = ".$user_id." group by product_id ) as cart_qty","cart_qty.product_id = products.product_id","left");
                $return_extra_fields .= ",cart_qty.qty as cart_qty";
            }
            $this->db->where($filter);
        }
        $this->db->select("{$this->table_name}.*,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,product_offers.offer_discount,product_offers.offer_type,product_offers.number_of_products,product_offers.product_offer_id,product_stock.productstock,order_items_stock.orderqty,(ifnull(product_stock.productstock,0)-ifnull(order_items_stock.orderqty,0)) as finalstock $return_extra_fields");

        $this->db->join("(select sum(product_stock.qty) as productstock,product_stock.product_id from product_stock where product_stock.draft = 0 group by product_stock.product_id) as product_stock","product_stock.product_id =".$this->table_name.".product_id","left");
        $this->db->join("(select sum(order_items.order_qty) as orderqty,order_items.product_id from orders inner join order_items on orders.order_id=order_items.order_id where orders.draft = 0 and orders.status not in(".ORDER_CANCEL.",".ORDER_DECLINE.") group by order_items.product_id) as order_items_stock","order_items_stock.product_id =".$this->table_name.".product_id","left");

        $q = $this->db->get($this->table_name);
        return $q->row();
    }
    function get_maps($id){
        $this->db->join("categories","categories.category_id = product_maps.category_id");
        $this->db->join("sub_categories","sub_categories.sub_category_id = product_maps.sub_category_id");
        $this->db->join("product_groups","product_groups.product_group_id = product_maps.group_id");
        $this->db->where("product_maps.product_id",$id);
        $q = $this->db->get("product_maps");
        return $q->result();
    }
    function get_map_by_id($id){
        $this->db->join("categories","categories.category_id = product_maps.category_id");
        $this->db->join("sub_categories","sub_categories.sub_category_id = product_maps.sub_category_id");
        $this->db->join("product_groups","product_groups.product_group_id = product_maps.group_id");
        $this->db->where("product_maps.product_map_id",$id);
        $q = $this->db->get("product_maps");
        return $q->row();
    }
    function get_sub_categories($filter = array()){
        $this->db->select("sub_categories.sub_category_id,sub_categories.category_id,sub_categories.sub_cat_name_en,sub_categories.sub_cat_name_ar,sub_categories.sub_cat_name_nl");
        $this->db->join("product_maps","sub_categories.sub_category_id = product_maps.sub_category_id");

        $this->db->join($this->table_name,"product_maps.product_id = ".$this->table_name.".product_id");
        $this->db->join("(select product_discounts.* from product_discounts where product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0 group by product_discounts.product_id ) as product_discounts","product_discounts.product_id = ".$this->table_name.".product_id","left");
        $this->db->join("(select product_offers.* , product_id from product_offers join product_offers_map on product_offers.product_offer_id = product_offers_map.product_offer_id and product_offers.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_offers.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_offers.status = 1 and product_offers.draft = 0 group by product_offers_map.product_id ) as product_offers", "product_offers.product_id = products.product_id","left");

        //$this->db->join("product_discounts_map","product_discounts_map.product_id = ".$this->table_name.".product_id","left");
        //$this->db->join("product_discounts","product_discounts.product_discount_id = product_discounts_map.product_discount_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
        //$this->db->join("product_discounts","FIND_IN_SET(".$this->table_name.".product_id, product_discounts.product_id ) > 0 and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");

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
            if(key_exists("user_id",$filter)){
                $this->db->join("order_items","order_items.product_id = product_maps.product_id");
                $this->db->join("orders","order_items.order_id = orders.order_id");
                $this->db->where("orders.user_id",$filter["user_id"]);
                unset($filter["user_id"]);
            }
            if(key_exists("products.product_tags",$filter)){
                $this->db->where("FIND_IN_SET('".$filter["products.product_tags"]."',products.product_tags) > 0");
                unset($filter["products.product_tags"]);
            }

            $this->db->where($filter);
        }
        $this->db->group_by("sub_categories.sub_category_id");
        $q = $this->db->get("sub_categories");
        return $q->result();
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
