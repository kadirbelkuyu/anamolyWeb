<section class="content" style="min-height:0px;">
    <div class="row">
        <?php 
        $box_array_list = array();
        if(isset($count_orders)){
            $box_array["url"] = site_url("admin/orders");
                    $box_array["box_bg"] = "bg-purple";
                    $box_array["box_icon"] = "ion ion-ios-cart";
                    $box_array["box_title"] = _l("All Orders");
                    $box_array["box_desc"] = $count_orders;
                    $box_array_list[] = $box_array;
        }
        if(isset($count_orders_express)){
            $box_array["url"] = site_url("admin/orders/express");
                    $box_array["box_bg"] = "bg-red";
                    $box_array["box_icon"] = "ion ion-android-car";
                    $box_array["box_title"] = _l("Express Orders");
                    $box_array["box_desc"] = $count_orders_express;
                    $box_array_list[] = $box_array;
        }
        foreach($order_with_status as $order){
            
            $url = "";
            $box_bg = "";
            $box_icon = "";
            $box_title = "";
            $box_desc = "";

            switch($order->status){
                case ORDER_PENDING:
                    $box_array["url"] = site_url("admin/orders/pending");
                    $box_array["box_bg"] = "bg-gray";
                    $box_array["box_icon"] = "ion ion-ios-cart";
                    $box_array["box_title"] = _l("Pending Orders");
                    $box_array["box_desc"] = $order->order_total;
                    $box_array_list[] = $box_array;
                break;
                case ORDER_CONFIRMED:
                    $box_array["url"] = site_url("admin/orders/confirmed");
                    $box_array["box_bg"] = "bg-aqua";
                    $box_array["box_icon"] = "ion ion-bag";
                    $box_array["box_title"] = _l("Confirmed Orders");
                    $box_array["box_desc"] = $order->order_total;
                    $box_array_list[] = $box_array;
                break;
                case ORDER_OUT_OF_DELIVEY:
                    $box_array["url"] = site_url("admin/orders/outdelivery");
                    $box_array["box_bg"] = "bg-orange";
                    $box_array["box_icon"] = "ion ion-android-bus";
                    $box_array["box_title"] = _l("Out of Delivery Orders");
                    $box_array["box_desc"] = $order->order_total;
                    $box_array_list[] = $box_array;
                break;
                case ORDER_DELIVERED:
                    $box_array["url"] = site_url("admin/orders/delivered");
                    $box_array["box_bg"] = "bg-green";
                    $box_array["box_icon"] = "ion ion-android-send";
                    $box_array["box_title"] = _l("Delivered Orders");
                    $box_array["box_desc"] = $order->order_total;
                    $box_array_list[] = $box_array;
                break;
                case ORDER_DECLINE:
                    $box_array["url"] = site_url("admin/orders/declined");
                    $box_array["box_bg"] = "bg-red";
                    $box_array["box_icon"] = "ion ion-android-cancel";
                    $box_array["box_title"] = _l("Declined Orders");
                    $box_array["box_desc"] = $order->order_total;
                    $box_array_list[] = $box_array;
                break;
            }
            
        }
        if(isset($count_orders_unpaid)){
            $box_array["url"] = site_url("admin/orders/unpaid");
                    $box_array["box_bg"] = "bg-yellow";
                    $box_array["box_icon"] = "ion ion-card";
                    $box_array["box_title"] = _l("Orders Unpaid");
                    $box_array["box_desc"] = $count_orders_unpaid;
                    $box_array_list[] = $box_array;
        }
        foreach($box_array_list as $box){
            if($box["box_bg"] != ""){
                ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="<?php echo $box["url"]; ?>">
                <div class="info-box">
                    <span class="info-box-icon <?php echo $box["box_bg"]; ?>"><i class="<?php echo $box["box_icon"]; ?>"></i></span>
    
                    <div class="info-box-content">
                    <span class="info-box-text"><?php echo $box["box_title"]; ?></span>
                    <span class="info-box-number"><?php echo $box["box_desc"]; ?></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                </a>
                <!-- /.info-box -->
                </div>
            <?php
                }
        } ?>
    </div>
</section>