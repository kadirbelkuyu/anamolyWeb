<?php class Coupons_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'coupons';
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
            $this->db->or_like(array($this->table_name.'.coupon_code'=>$search,
                                    $this->table_name.'.description_en'=>$search,
                                    $this->table_name.'.description_ar'=>$search,
                                    $this->table_name.'.description_nl'=>$search,
                                    $this->table_name.'.description_tr'=>$search,
                                    $this->table_name.'.description_de'=>$search));
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

    function validate($user_id,$coupon_code){
        $date = date(MYSQL_DATE_FORMATE);
        $coupons = $this->coupons_model->get(array("user_id"=>$user_id,"coupon_code"=>$coupon_code,"validity"=>$date));
        if(empty($coupons)){
            return array(
                RESPONCE => false,
                MESSAGE => _l("Opps! Sorry Coupon code not valid"),
                DATA => _l("Opps! Sorry Coupon code not valid"),
                CODE => 101
            );
        }else{
            $coupon = $coupons[0];
            if($coupon->multi_usage != 1){
                $this->load->model("orders_model");
                $order = $this->orders_model->get(array("orders.user_id"=>$user_id,"orders.coupon_code"=>$coupon_code));
                if (!empty($order)) {
                  return array(
                      RESPONCE => false,
                      MESSAGE => _l("Sorry you already applied this code"),
                      DATA => _l("Sorry you already applied this code"),
                      CODE => 101
                  );
                }
            }

            $this->load->model("cart_model");
            $cart = $this->cart_model->manage_cart($user_id);
            if (!empty($cart)) {
                $amount = $cart["net_paid_amount"];
                if($coupon->min_order_amount > 0){
                    if($amount < $coupon->min_order_amount){
                        return array(
                            RESPONCE => false,
                            MESSAGE => _l("Minimum order amount must be ".$coupon->min_order_amount),
                            DATA => _l("Minimum order amount must be ".$coupon->min_order_amount),
                            CODE => 101
                        );
                    }
                }
                if($coupon->discount_type == "flat"){
                    if($coupon->discount > $amount){
                        return array(
                            RESPONCE => false,
                            MESSAGE => _l("Sorry you can no apply this code"),
                            DATA => _l("Sorry you can no apply this code"),
                            CODE => 101
                        );
                    }
                    $coupon->deduct_amount = $coupon->discount;
                }else {
                    $discount_amount = $amount * $coupon->discount / 100;
                    if ($coupon->max_discount_amount > 0 && $discount_amount > $coupon->max_discount_amount) {
                        $discount_amount = $coupon->max_discount_amount;
                    }
                    $coupon->deduct_amount = $discount_amount;
                }
                return array(
                    RESPONCE => true,
                    MESSAGE => _l("Coupon vlaidated"),
                    DATA => $coupon,
                    CODE => CODE_SUCCESS
                );
            }else{
                return array(
                    RESPONCE => false,
                    MESSAGE => _l("Sorry no item in cart to apply coupon"),
                    DATA => _l("Sorry no item in cart to apply coupon"),
                    CODE => 101
                );
            }
        }
    }
}
