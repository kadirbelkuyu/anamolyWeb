<?php class Cart_model extends CI_Model{
    protected $table_name;
    protected $primary_key;
    public function __construct()
    {
        parent::__construct();
        $this->table_name = 'cart';
        $this->primary_key= 'cart_id';
    }
    function get_cart_items($user_id){
        $this->db->distinct();
        $this->db->select("cart.*,products.product_name_en,products.product_name_ar,products.product_name_nl,products.product_name_tr,products.product_name_de,products.is_express,products.in_stock,products.price_vat_exclude,products.vat,products.price,products.unit,products.unit_ar,products.unit_en,products.unit_tr,products.unit_de,products.unit_value,products.price_note,product_discounts.discount,product_discounts.discount_type,product_discounts.product_discount_id,product_offers.offer_discount,product_offers.offer_type,product_offers.number_of_products,product_offers.product_offer_id,products.product_image,(ifnull(product_stock.productstock,0)-ifnull(order_items.orderqty,0)) as finalstock");
        $this->db->join("products","products.product_id = cart.product_id");
        $this->db->join("product_discounts","product_discounts.product_id = ".$this->table_name.".product_id and product_discounts.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_discounts.status = 1 and product_discounts.draft = 0","left");
        $this->db->join("(select product_offers.* , product_id from product_offers join product_offers_map on product_offers.product_offer_id = product_offers_map.product_offer_id and product_offers.start_date <= '".date(MYSQL_DATE_FORMATE)."' and product_offers.end_date >= '".date(MYSQL_DATE_FORMATE)."' and product_offers.status = 1 and product_offers.draft = 0 group by product_id) as product_offers", "product_offers.product_id = products.product_id","left");
        // Count Stock with Join
        $this->db->join("(select sum(product_stock.qty) as productstock,product_stock.product_id from product_stock where product_stock.draft = 0 group by product_stock.product_id) as product_stock","product_stock.product_id =products.product_id","left");
        $this->db->join("(select sum(order_items.order_qty) as orderqty,order_items.product_id from orders inner join order_items on orders.order_id=order_items.order_id where orders.draft = 0 and orders.status not in(".ORDER_CANCEL.",".ORDER_DECLINE.") group by order_items.product_id) as order_items","order_items.product_id =".$this->table_name.".product_id","left");
        // Count Stock with Join

        $this->db->where("cart.user_id",$user_id);
        $this->db->where("products.draft","0");
        $this->db->where("cart.draft","0");
        $this->db->order_by("cart_id");
        $q = $this->db->get("cart");
        return $q->result();
    }
    function manage_cart($user_id){
        $order_items = $this->get_cart_items($user_id);

        $cart_array = array();
        $total_price = 0;
        $is_express = 0;
        foreach($order_items as $product){
            $total_price = $total_price + ($product->price * $product->qty);
            $items_array = array();
            $update_index = -1;
            if($product->product_offer_id != NULL && $product->product_offer_id >0){

                if(!empty($cart_array)){
                    $ia = array();
                    foreach($cart_array as $k_index=>$c_array){
                        if(!empty($ia) && count($ia) < ($product->number_of_products + 1)){
                            continue;
                        }
                        if(isset($c_array["items"])){

                            foreach($c_array["items"] as $c_items){
                                if($product->offer_type == "plusone"){

                                    if(count($c_array["items"]) < ($product->number_of_products + 1)){

                                        if($c_items->product_offer_id == $product->product_offer_id){

                                            $ia = $c_array["items"];
                                            $update_index = $k_index;
                                            $items_array = $ia;

                                        }
                                    }else{
                                        $update_index = -1;
                                    }
                                }else if($product->offer_type == "flatcombo"){

                                    if(count($c_array["items"]) < $product->number_of_products){

                                        if($c_items->product_offer_id == $product->product_offer_id){
                                            $ia = $c_array["items"];
                                            $update_index = $k_index;
                                            $items_array = $ia;
                                        }
                                    }else{
                                        $update_index = -1;
                                    }
                                }
                            }
                        }
                    }

                }
            }
            $items_array[] = $product;



            $final_price = 0;
            $min_price = 0;

            $free_item_index = -1;
            $items_total_price = 0;
            foreach($items_array as $k=>$product){
                if($product->is_express == 1){
                    $is_express = 1;
                }

                $product_price = $product->price;
                $items_total_price = $items_total_price + $product->price * $product->qty;
                $product->effected_price = $product_price;
                if($product->discount != NULL && $product->discount > 0){
                    if($product->discount_type == "flat"){
                        $discount_amount = $product->discount;
                        $product_price = $product_price - $product->discount;
                    }else if($product->discount_type == "percentage"){
                        $discount_amount = $product->discount * $product_price  / 100;
                        $product_price = $product_price - $discount_amount;
                    }
                    $product->effected_price = $product_price;
                }

                if($product->product_offer_id != NULL && $product->product_offer_id >0){
                    if($product->offer_type == "plusone"){
                            $offer_amount = $product->offer_discount * $product_price  / 100;
                            $product_price = $product_price - $offer_amount;
                            if(count($items_array) == ($product->number_of_products + 1)){
                                if($min_price == 0){
                                    $min_price = $product_price;
                                    $free_item_index = $k;
                                }else if($min_price > $product_price){
                                    $min_price = $product_price;
                                    $free_item_index = $k;
                                }
                            }
                            $product->effected_price = $product_price;
                    }else if($product->offer_type == "flatcombo"){
                        if(count($items_array) == $product->number_of_products){
                            $offer_amount = $product->offer_discount / $product->number_of_products;
                            $product_price = $product->offer_discount - $offer_amount;
                            $product->effected_price = $product_price;
                            //$min_price = $product_price;
                        }
                    }

                }
                $product_price = $product_price * $product->qty;

                $final_price = $final_price + $product_price;
            }
            if($free_item_index > -1){
                $items_array[$free_item_index]->effected_price = 0;
            }
            $final_price = $final_price - $min_price;

            $array = array("items" => $items_array,"items_total_price"=>$items_total_price, "effected_price"=>$final_price);

            if($update_index >= 0){
                $cart_array[$update_index] = $array;
            }else{
                $cart_array[] = $array;
            }


        }
        $cart_total = 0;
        foreach($cart_array as $array){
            $cart_total = $cart_total + $array["effected_price"];
        }

        $min_order_amount = "0";

        $this->db->join("postal_codes","postal_codes.postal_code = user_address.postal_code");
        $this->db->where("user_address.user_id",$user_id);
        $q = $this->db->get("user_address");
        $postal_code = $q->row();
        if(!empty($postal_code)){
            $min_order_amount = $postal_code->min_order_amount;
        }
        $final_discount = $total_price - $cart_total;
        return array("products"=>$cart_array,"discount"=>$final_discount,"vat_prices"=>array(),"is_express"=>$is_express,"cart_total"=>$cart_total,"net_paid_amount"=>$cart_total,"total_amount"=>$total_price,"min_order_amount"=>$min_order_amount);
    }
}
