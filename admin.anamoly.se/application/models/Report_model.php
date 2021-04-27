<?php
class Report_model extends CI_Model{
    
   public function __construct()
    {
        parent::__construct();       
    }
    
       function getUserReport($daterange = "",$datetype = ""){
       
        $filter = "";
        if ($daterange != "") {            
            $dates = explode(" - ", $daterange);
            if (count($dates) == 2) {
                $filter = " users.created_at >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                    ($dates[0]))) . " 00:00:00' and users.created_at <= '" . date(MYSQL_DATE_FORMATE,
                    strtotime(trim($dates[1]))) . " 23:59:59'";
            }
        }
        
        $date_type = "DATE_FORMAT(users.created_at, '%d/%m/%Y')";
        
        if($datetype=="1")
        {
            $date_type = "DATE_FORMAT(users.created_at, '%b %e-%Y')";
        }
        else if($datetype=="2")
        {
            $date_type = "DATE_FORMAT(users.created_at, '%m/%Y')";
        }
        else if($datetype=="3")
        {
            $date_type = "QUARTER(users.created_at)";
        }
        else if($datetype=="4")
        {
            $date_type = "DATE_FORMAT(users.created_at, '%Y')";
        }
      
		$this->db->select($date_type." as Day,IFNULL(CompanyCount,0) as CompanyCount,IFNULL(CustomerCount,0) as CustomerCount");
        $this->db->where("users.draft","0");
        if($filter != ""){
            $this->db->where($filter);
        }
        $this->db->join("(select count(users.user_type_id) as CompanyCount,".$date_type." as CDay from users where users.user_type_id =".USER_COMPANY." and users.draft = 0 group by ".$date_type.") as user_company","user_company.CDay =".$date_type,"left");
        $this->db->join("(select count(users.user_type_id) as CustomerCount,".$date_type." as CSDay from users where users.user_type_id =".USER_CUSTOMER." and users.draft = 0 group by ".$date_type.") as user_custmer","user_custmer.CSDay =".$date_type,"left");
        $this->db->group_by($date_type);      
		$this->db->order_by("users.created_at asc");
      
        $q = $this->db->get("users");
        return $q->result();
    }
    
     function getOrderReport($daterange = "",$datetype = ""){
       
        $filter = "";
        if ($daterange != "") {            
            $dates = explode(" - ", $daterange);
            if (count($dates) == 2) {
                $filter = " orders.order_date >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                    ($dates[0]))) . " 00:00:00' and orders.order_date <= '" . date(MYSQL_DATE_FORMATE,
                    strtotime(trim($dates[1]))) . " 23:59:59'";
            }
        }
        
        $date_type = "DATE_FORMAT(orders.order_date, '%d/%m/%Y')";
        
        if($datetype=="1")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%b %e-%Y')";
        }
        else if($datetype=="2")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%m/%Y')";
        }
        else if($datetype=="3")
        {
            $date_type = "QUARTER(orders.order_date)";
        }
        else if($datetype=="4")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%Y')";
        }
      
	    $this->db->select($date_type." as Day,count(orders.order_id) as Total,IFNULL(PendingOrder,0) as PendingOrder,IFNULL(ConfirmOrder,0) as ConfirmOrder,IFNULL(OnDeliveryOrder,0) as OnDeliveryOrder,IFNULL(ExpressOrder,0) as ExpressOrder,IFNULL(DeliverOrder,0) as DeliverOrder,IFNULL(UnPaidOrder,0) as UnPaidOrder");
        $this->db->where("orders.draft","0");
        if($filter != ""){
            $this->db->where($filter);
        } 
                
        $this->db->join("(select count(orders.status) as PendingOrder,".$date_type." as ODay from orders where orders.status =".ORDER_PENDING." and orders.draft = 0 group by ".$date_type.") as corder","corder.ODay =".$date_type,"left");
        $this->db->join("(select count(orders.status) as ConfirmOrder,".$date_type." as ODay from orders where orders.status =".ORDER_CONFIRMED." and orders.draft = 0 group by ".$date_type.") as porder","porder.ODay =".$date_type,"left");        
        $this->db->join("(select count(orders.status) as OnDeliveryOrder,".$date_type." as ODay from orders where orders.status =".ORDER_OUT_OF_DELIVEY." and orders.draft = 0 group by ".$date_type.") as oorder","oorder.ODay =".$date_type,"left");
        $this->db->join("(select count(orders.status) as ExpressOrder,".$date_type." as ODay from orders where orders.is_express =1 and orders.draft = 0 group by ".$date_type.") as eorder","eorder.ODay =".$date_type,"left");
        $this->db->join("(select count(orders.status) as DeliverOrder,".$date_type." as ODay from orders where orders.status =".ORDER_DELIVERED." and orders.draft = 0 group by ".$date_type.") as dorder","dorder.ODay =".$date_type,"left");
        $this->db->join("(select count(orders.status) as UnPaidOrder,".$date_type." as ODay from orders where orders.status =".ORDER_UNPAID." and orders.draft = 0 group by ".$date_type.") as uorder","uorder.ODay =".$date_type,"left");
        
        $this->db->group_by($date_type);      
		$this->db->order_by("orders.order_date asc");
      
        $q = $this->db->get("orders");
        return $q->result();
    }
    
    
    function getSaleReport($daterange = "",$datetype = "",$orderstatus="",$postalcode=""){
       
        $filter = "";
        if ($daterange != "") {            
            $dates = explode(" - ", $daterange);
            if (count($dates) == 2) {
                $filter = " orders.order_date >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                    ($dates[0]))) . " 00:00:00' and orders.order_date <= '" . date(MYSQL_DATE_FORMATE,
                    strtotime(trim($dates[1]))) . " 23:59:59'";
            }
        }
        
        $filterorderstatus="";
        
        if($orderstatus!="")
        {
           $filterorderstatus=" and orders.status=".$orderstatus;
           
           if($filter!="")
            {
                $filter=$filter.$filterorderstatus;
            }
            else{
                $filter=" orders.status=".$orderstatus;
            }
        }               
        
        $filterpostalcode="";
        
        if($postalcode!="")
        {
           $filterpostalcode=" and LEFT(order_delivery_address.postal_code,4)=".$postalcode;           
        }               
        
        $date_type = "DATE_FORMAT(orders.order_date, '%d/%m/%Y')";
        
        if($datetype=="1")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%b %e-%Y')";
        }
        else if($datetype=="2")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%m/%Y')";
        }
        else if($datetype=="3")
        {
            $date_type = "QUARTER(orders.order_date)";
        }
        else if($datetype=="4")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%Y')";
        }
              
		$this->db->select($date_type." as Day,IFNULL(ZipOrder,0) as ZipOrder,IFNULL(CompanyOrder,0) as CompanyOrder,IFNULL(UserOrder,0) as UserOrder");
        $this->db->where("orders.draft","0");
                
        if($filter != ""){
            $this->db->where($filter);
        }  
         
        $this->db->join("(select sum(orders.net_amount) as ZipOrder,".$date_type." as ODay from orders INNER JOIN order_delivery_address on orders.order_id=order_delivery_address.order_id where orders.draft = 0 ".$filterpostalcode.$filterorderstatus." group by ".$date_type.") as corder","corder.ODay =".$date_type,"left");
        $this->db->join("(select sum(orders.net_amount) as CompanyOrder,".$date_type." as ODay from orders INNER JOIN users on orders.user_id=users.user_id where users.user_type_id =".USER_COMPANY." and orders.draft = 0 ".$filterorderstatus." group by ".$date_type.") as porder","porder.ODay =".$date_type,"left");        
        $this->db->join("(select sum(orders.net_amount) as UserOrder,".$date_type." as ODay from orders INNER JOIN users on orders.user_id=users.user_id where users.user_type_id =".USER_CUSTOMER." and orders.draft = 0 ".$filterorderstatus." group by ".$date_type.") as oorder","oorder.ODay =".$date_type,"left");
        		
        $this->db->group_by($date_type);      
		$this->db->order_by("orders.order_date asc");
      
        $q = $this->db->get("orders");
        return $q->result();
    }
    
function getSalesReport($daterange = "",$datetype = "",$categoryid="",$subcategoryid="",$groupid=""){
       
        $filter = "";
        if ($daterange != "") {            
            $dates = explode(" - ", $daterange);
            if (count($dates) == 2) {
                $filter = " orders.order_date >= '" . date(MYSQL_DATE_FORMATE, strtotime(trim
                    ($dates[0]))) . " 00:00:00' and orders.order_date <= '" . date(MYSQL_DATE_FORMATE,
                    strtotime(trim($dates[1]))) . " 23:59:59'";
            }
        }
                
        $date_type = "DATE_FORMAT(orders.order_date, '%d/%m/%Y')";
        
        if($datetype=="1")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%b %e-%Y')";
        }
        else if($datetype=="2")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%m/%Y')";
        }
        else if($datetype=="3")
        {
            $date_type = "QUARTER(orders.order_date)";
        }
        else if($datetype=="4")
        {
            $date_type = "DATE_FORMAT(orders.order_date, '%Y')";
        }
              
		$this->db->select($date_type." as Day,IFNULL(CategoryOrder,0) as CategoryOrder,IFNULL(SubCategoryOrder,0) as SubCategoryOrder,IFNULL(GroupOrder,0) as GroupOrder");
        $this->db->where("orders.draft","0");
                
        if($filter != ""){
            $this->db->where($filter);
        }  
         
        $filtercategory="";
        
        if($categoryid!="")
        {
           $filtercategory=" and product_maps.category_id=".$categoryid;           
        } 
        
        $filtersubcategory="";
        
        if($subcategoryid!="")
        {
           $filtersubcategory=" and product_maps.sub_category_id=".$subcategoryid;           
        }       
        
        $filtergroup="";
        
        if($groupid!="")
        {
           $filtergroup=" and product_maps.group_id=".$groupid;           
        }             
        
        $this->db->join("(select sum(order_items.price) as CategoryOrder,".$date_type." as ODay from orders INNER join order_items on orders.order_id=order_items.order_id INNER join product_maps ON order_items.product_id=product_maps.product_id where orders.draft = 0 ".$filtercategory." group by ".$date_type.") as corder","corder.ODay =".$date_type,"left");
        $this->db->join("(select sum(order_items.price) as SubCategoryOrder,".$date_type." as ODay from orders INNER join order_items on orders.order_id=order_items.order_id INNER join product_maps ON order_items.product_id=product_maps.product_id where orders.draft = 0 ".$filtersubcategory." group by ".$date_type.") as porder","porder.ODay =".$date_type,"left");        
        $this->db->join("(select sum(order_items.price) as GroupOrder,".$date_type." as ODay from orders INNER join order_items on orders.order_id=order_items.order_id INNER join product_maps ON order_items.product_id=product_maps.product_id where orders.draft = 0 ".$filtergroup." group by ".$date_type.") as oorder","oorder.ODay =".$date_type,"left");
        		
        $this->db->group_by($date_type);      
		$this->db->order_by("orders.order_date asc");
      
        $q = $this->db->get("orders");
        return $q->result();
    }
}
?>