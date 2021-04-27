  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo _get_current_user_image(); ?>" class="img-circle" alt="<?php echo _get_current_user_fullname(); ?>">
        </div>
        <div class="pull-left info">
          <p><?php echo _get_current_user_fullname(); ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> <?php echo _l("Online"); ?></a>
        </div>
      </div>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header"><?php echo _l("MAIN NAVIGATION"); ?></li>
        <?php
            
            $this->db->where("draft","0");
            $count_products = $this->db->count_all_results("products");

            $this->db->where("draft","0");
            $count_categories = $this->db->count_all_results("categories");
            
            $this->db->where("draft","0");
            $count_subcategories = $this->db->count_all_results("sub_categories");

            $this->db->where("draft","0");
            $count_subcategories = $this->db->count_all_results("sub_categories");

            $this->db->where("draft","0");
            $count_product_groups = $this->db->count_all_results("product_groups");

            $this->db->where("draft","0");
            $count_product_suggest = $this->db->count_all_results("product_suggest");

            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_PLANNER);
            $count_user_planners = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_BUYER);
            $count_user_buyer = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_COMPANY);
            $count_user_company = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_CUSTOMER);
            $count_user_customer = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where("user_type_id",USER_SUBADMIN);
            $count_sub_admin = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where_in("user_type_id",array(USER_CUSTOMER,USER_COMPANY));
            $count_all_app_users = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $this->db->where("status","3");
            $this->db->where_in("user_type_id",array(USER_CUSTOMER,USER_COMPANY));
            $count_waitinglist_users = $this->db->count_all_results("users");

            $this->db->where("draft","0");
            $count_postal_codes = $this->db->count_all_results("postal_codes");

            $this->db->where("draft","0");
            $count_orders = $this->db->count_all_results("orders");


            $this->db->where("draft","0");
            $this->db->where("status",ORDER_PENDING);
            $count_orders_pending = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_CONFIRMED);
            $count_orders_confirmed = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_OUT_OF_DELIVEY);
            $count_orders_outofdelivery = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where_in("status",array(ORDER_PENDING,ORDER_CONFIRMED,ORDER_OUT_OF_DELIVEY));
            $this->db->where("is_express","1");
            $count_orders_express = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_DELIVERED);
            $count_orders_delivered = $this->db->count_all_results("orders");

            $this->db->where("draft","0");
            $this->db->where("status",ORDER_UNPAID);
            $count_orders_unpaid = $this->db->count_all_results("orders");

            $menu_array = array();
          

			if(_is_admin())
			{
				$dashboardLink=site_url("admin/dashboard");
			}
			else if(_is_planner())
			{
				$dashboardLink=site_url("planner/dashboard");
			}
			else if(_is_buyer())
			{
				$dashboardLink=site_url("buyer/dashboard");
			}else if(_is_sub_admin())
			{
				$dashboardLink=site_url("subadmin/dashboard");
			}
			else
            {
                return redirect("login");
            }
            
            $menu_array[] = array("menu_title"=>_l("Dashboard"),"role"=>"*","link"=>$dashboardLink,"menu_icon"=>"fa fa-dashboard","badge"=>""); 
            
            
            if(_is_admin() || _is_sub_admin()){   
                
                $product_menu[] = array("menu_title"=>_l("Dashboard"),"role"=>"admin/report","link"=>site_url("admin/report/product"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Products"),"role"=>"admin/products","link"=>site_url("admin/products"),"menu_icon"=>"fa fa-shopping-cart","badge"=>$count_products);
                $product_menu[] = array("menu_title"=>_l("Categories"),"role"=>"admin/categories","link"=>site_url("admin/categories"),"menu_icon"=>"fa fa-cog","badge"=>$count_categories);
                $product_menu[] = array("menu_title"=>_l("Sub Categories"),"role"=>"admin/subcategories","link"=>site_url("admin/subcategories"),"menu_icon"=>"fa fa-cog","badge"=>$count_subcategories);
                $product_menu[] = array("menu_title"=>_l("Groups"),"role"=>"admin/productgroups","link"=>site_url("admin/productgroups"),"menu_icon"=>"fa fa-cog","badge"=>$count_product_groups);
                $product_menu[] = array("menu_title"=>_l("Ingredients"),"role"=>"admin/ingredients","link"=>site_url("admin/ingredients"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Tags"),"role"=>"admin/tags","link"=>site_url("admin/tags"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Units"),"role"=>"admin/units","link"=>site_url("admin/units"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Vats"),"role"=>"admin/vats","link"=>site_url("admin/vats"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Product Suggestions"),"role"=>"suggestions","link"=>site_url("suggestions"),"menu_icon"=>"fa fa-tag","badge"=>$count_product_suggest);
                $product_menu[] = array("menu_title"=>_l("Stocks"),"role"=>"admin/stocks","link"=>site_url("admin/stocks"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $product_menu[] = array("menu_title"=>_l("Out of Stocks"),"role"=>"admin/stocks/outofstocks","link"=>site_url("admin/stocks/outofstocks"),"menu_icon"=>"fa fa-cog","badge"=>"");
               
                $menu_array[] = array("menu_title"=>_l("Products"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-product-hunt","badge"=>"","sub_menu"=>$product_menu);
                

                $acties_menu[] = array("menu_title"=>_l("Product Discount"),"role"=>"admin/discounts","link"=>site_url("admin/discounts"),"menu_icon"=>"fa fa-tag","badge"=>"");
                $acties_menu[] = array("menu_title"=>_l("Product Offers"),"role"=>"admin/offers","link"=>site_url("admin/offers"),"menu_icon"=>"fa fa-tag","badge"=>"");
                $acties_menu[] = array("menu_title"=>_l("Coupons"),"role"=>"admin/coupons","link"=>site_url("admin/coupons"),"menu_icon"=>"fa fa-tags","badge"=>"");
                $acties_menu[] = array("menu_title"=>_l("Banners"),"role"=>"admin/banners","link"=>site_url("admin/banners"),"menu_icon"=>"fa fa-image","badge"=>"");              
                $acties_menu[] = array("menu_title"=>_l("Referral Codes"),"role"=>"admin/referralcodes","link"=>site_url("admin/referralcodes"),"menu_icon"=>"fa fa-share","badge"=>"");                
                $menu_array[] = array("menu_title"=>_l("Actions"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-product-hunt","badge"=>"","sub_menu"=>$acties_menu);
                
                //$user_menu[] = array("menu_title"=>_l("Planner"),"role"=>"admin/users/planner","link"=>site_url("admin/users/planner"),"menu_icon"=>"fa fa-cog","badge"=>$count_user_planners);
                //$user_menu[] = array("menu_title"=>_l("Buyer"),"role"=>"admin/users/buyer","link"=>site_url("admin/users/buyer"),"menu_icon"=>"fa fa-cog","badge"=>$count_user_buyer);
                //$menu_array[] = array("menu_title"=>_l("Manage Users"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-user","badge"=>"","sub_menu"=>$user_menu);
                
                
                $role_menu[] = array("menu_title"=>_l("Roles"),"role"=>"admin/roles","link"=>site_url("admin/roles"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $role_menu[] = array("menu_title"=>_l("Sub Admin"),"role"=>"admin/subadmin","link"=>site_url("admin/subadmin"),"menu_icon"=>"fa fa-cog","badge"=>$count_sub_admin);
                $menu_array[] = array("menu_title"=>_l("Users"),"role"=>"admin/roles","link"=>"#","menu_icon"=>"fa fa-user","badge"=>"","sub_menu"=>$role_menu);


                $app_user_menu[] = array("menu_title"=>_l("Dashboard"),"role"=>"admin/report","link"=>site_url("admin/report/user"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $app_user_menu[] = array("menu_title"=>_l("All"),"role"=>"admin/users/appuser","link"=>site_url("admin/users/appuser"),"menu_icon"=>"fa fa-cog","badge"=>$count_all_app_users);
                $app_user_menu[] = array("menu_title"=>_l("Customer"),"role"=>"admin/users/customer","link"=>site_url("admin/users/customer"),"menu_icon"=>"fa fa-cog","badge"=>$count_user_customer);
                $app_user_menu[] = array("menu_title"=>_l("Company"),"role"=>"admin/users/company","link"=>site_url("admin/users/company"),"menu_icon"=>"fa fa-cog","badge"=>$count_user_company);
                $app_user_menu[] = array("menu_title"=>_l("Waiting List"),"role"=>"admin/users/waiting","link"=>site_url("admin/users/waiting"),"menu_icon"=>"fa fa-cog","badge"=>$count_waitinglist_users);
                $app_user_menu[] = array("menu_title"=>_l("Delivery Times"),"role"=>"admin/deliverytimes","link"=>site_url("admin/deliverytimes"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $app_user_menu[] = array("menu_title"=>_l("Postal Codes"),"role"=>"admin/postalcodes","link"=>site_url("admin/postalcodes"),"menu_icon"=>"fa fa-cog","badge"=>$count_postal_codes);               
                $menu_array[] = array("menu_title"=>_l("App Users"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-mobile","badge"=>"","sub_menu"=>$app_user_menu);

                $trans_array[] = array("menu_title"=>_l("Vehicle"),"role"=>"admin/vehicle","link"=>site_url("admin/vehicle"),"menu_icon"=>"fa fa-truck","badge"=>"");
                $trans_array[] = array("menu_title"=>_l("Delivery Boy"),"role"=>"admin/deliveryboy","link"=>site_url("admin/deliveryboy"),"menu_icon"=>"fa fa-user","badge"=>"");
                $menu_array[] = array("menu_title"=>_l("Transport"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-truck","badge"=>"","sub_menu"=>$trans_array);


                $order_menu[] = array("menu_title"=>_l("Dashboard"),"role"=>"admin/report","link"=>site_url("admin/report/order"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Orders"),"role"=>"admin/orders","link"=>site_url("admin/orders"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders);
                $order_menu[] = array("menu_title"=>_l("Order Pendings"),"role"=>"admin/orders","link"=>site_url("admin/orders/pending"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_pending);
                $order_menu[] = array("menu_title"=>_l("Order Confirmed"),"role"=>"admin/orders","link"=>site_url("admin/orders/confirmed"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_confirmed);
                $order_menu[] = array("menu_title"=>_l("Order On Delivery"),"role"=>"admin/orders","link"=>site_url("admin/orders/outdelivery"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_outofdelivery);
                $order_menu[] = array("menu_title"=>_l("Express Order"),"role"=>"admin/orders","link"=>site_url("admin/orders/express"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_express);
                $order_menu[] = array("menu_title"=>_l("Order Delivered"),"role"=>"admin/orders","link"=>site_url("admin/orders/delivered"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_delivered);
                $order_menu[] = array("menu_title"=>_l("Order Un Paid"),"role"=>"admin/orders","link"=>site_url("admin/orders/unpaid"),"menu_icon"=>"fa fa-cog","badge"=>$count_orders_unpaid);
                $menu_array[] = array("menu_title"=>_l("Orders"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-cart-arrow-down","badge"=>"","sub_menu"=>$order_menu);

                
                $settings_menu[] = array("menu_title"=>_l("General Settings"),"role"=>"setting","link"=>site_url("setting"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $settings_menu[] = array("menu_title"=>_l("App Settings"),"role"=>"setting/app","link"=>site_url("setting/app"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $settings_menu[] = array("menu_title"=>_l("Billing Details"),"role"=>"setting/billing","link"=>site_url("setting/billing"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $settings_menu[] = array("menu_title"=>_l("Email Settings"),"role"=>"setting/email","link"=>site_url("setting/email"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $settings_menu[] = array("menu_title"=>_l("Email Templates"),"role"=>"admin/emailtemplates","link"=>site_url("admin/emailtemplates"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $settings_menu[] = array("menu_title"=>_l("App Pages"),"role"=>"admin/apppages","link"=>site_url("admin/apppages"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $menu_array[] = array("menu_title"=>_l("Settings"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-cog","badge"=>"","sub_menu"=>$settings_menu); 
                
                $reports_menu[] = array("menu_title"=>_l("Sales"),"role"=>"admin/report","link"=>site_url("admin/report/sale"),"menu_icon"=>"fa fa-cog","badge"=>"");
                 $reports_menu[] = array("menu_title"=>_l("Sales Summary"),"role"=>"admin/report","link"=>site_url("admin/report/sales"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $reports_menu[] = array("menu_title"=>_l("Vats"),"role"=>"admin/report","link"=>site_url("admin/report/vat"),"menu_icon"=>"fa fa-cog","badge"=>"");                
                $menu_array[] = array("menu_title"=>_l("Reports"),"role"=>"admin/report","link"=>"#","menu_icon"=>"fa fa-file-o","badge"=>"","sub_menu"=>$reports_menu); 
                
            }else if(_is_planner()){
                $order_menu[] = array("menu_title"=>_l("Orders"),"role"=>"","link"=>site_url("admin/orders"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Order Pendings"),"role"=>"","link"=>site_url("admin/orders/pending"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Order Confirmed"),"role"=>"","link"=>site_url("admin/orders/confirmed"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Order On Delivery"),"role"=>"","link"=>site_url("admin/orders/outdelivery"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Express Order"),"role"=>"","link"=>site_url("admin/orders/express"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $order_menu[] = array("menu_title"=>_l("Order Delivered"),"role"=>"","link"=>site_url("admin/orders/delivered"),"menu_icon"=>"fa fa-cog","badge"=>"");
                
                
                $menu_array[] = array("menu_title"=>_l("Orders"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-cart-arrow-down","badge"=>"","sub_menu"=>$order_menu);

            }else if(_is_buyer()){
                $sub_menu[] = array("menu_title"=>_l("Products"),"role"=>"","link"=>site_url("admin/products"),"menu_icon"=>"fa fa-shopping-cart","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Categories"),"role"=>"","link"=>site_url("admin/categories"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Sub Categories"),"role"=>"","link"=>site_url("admin/subcategories"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Groups"),"role"=>"","link"=>site_url("admin/productgroups"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Ingredients"),"role"=>"","link"=>site_url("admin/ingredients"),"menu_icon"=>"fa fa-cog","badge"=>"");              
                $sub_menu[] = array("menu_title"=>_l("Tags"),"role"=>"","link"=>site_url("admin/tags"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Units"),"role"=>"","link"=>site_url("admin/units"),"menu_icon"=>"fa fa-cog","badge"=>"");
                $sub_menu[] = array("menu_title"=>_l("Product Suggestions"),"role"=>"","link"=>site_url("suggestions"),"menu_icon"=>"fa fa-tag","badge"=>"");
                $menu_array[] = array("menu_title"=>_l("Products"),"role"=>"","link"=>"#","menu_icon"=>"fa fa-product-hunt","badge"=>"","sub_menu"=>$sub_menu);
                
                
            }
            
            $active_menu = array();
            if(isset($active_menu_link)){
                $active_menu = $active_menu_link;
            }
            foreach($menu_array as $menu){
                create_menu($menu,$active_menu);
            }
            function create_menu($menu,$active = array()){
                $permission_array = MY_Controller::$permission_array;
                $treemenu = "";
                if(isset($menu["sub_menu"])){
                    $treemenu = "treeview";
                }
                $class_active = "";
                if(isset($menu["link"]) && $menu["link"] != "" && $menu["link"] == current_url()){
                    $class_active = "active";
                }
                foreach($active as $act){
                    if(isset($menu["link"]) && $act == $menu["link"]){
                        $class_active = "active";
                    }
                }
                $menu_with_permission_submenu = false;
                if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                    foreach($menu["sub_menu"] as $sub_menu){
                        if (_is_sub_admin()) {
                            if ($sub_menu["role"] == "*" || in_array($sub_menu["role"], $permission_array)) {
                                $menu_with_permission_submenu = true;
                            }
                        }
                        if(isset($sub_menu["link"]) && current_url() == $sub_menu["link"]){
                            $class_active = "active";
                        }else{
                            foreach($active as $act){
                                if(isset($sub_menu["link"]) && $act == $sub_menu["link"]){
                                    $class_active = "active";
                                }
                            }
                        }       
                    }
                }
                if($treemenu != "" && $class_active == "active"){
                    $treemenu = $treemenu." menu-open";
                }
                $draw_menu = '<li class="'.$treemenu.' '.$class_active.'">';
                $link = "#";
                if(isset($menu["link"]) && $menu["link"] != "")
                    $link = $menu["link"];
                $draw_menu .= '<a href="'.$link.'">';
                if(isset($menu["menu_icon"]) && $menu["menu_icon"] != ""){
                    $draw_menu .= '<i class="'.$menu["menu_icon"].'"></i>';
                }
                $draw_menu .= '<span>'._l($menu["menu_title"]).'</span>';
                if(isset($menu["badge"]) && $menu["badge"] != ""){
                    $draw_menu .=   '<span class="pull-right-container">
                                      <span class="label label-primary pull-right">'.$menu["badge"].'</span>
                                    </span>';
                }else if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                    $draw_menu .=   '<span class="pull-right-container">
                                        <i class="fa fa-angle-left pull-right"></i>
                                    </span>';
                }
                $draw_menu .= '</a>';
                if (_is_sub_admin()) {
                    if ($menu_with_permission_submenu || $menu["role"] == "*" || in_array($menu["role"], $permission_array)) {
                        echo $draw_menu;
                    }
                }else{
                    echo $draw_menu;
                }
                    if(isset($menu["sub_menu"]) && !empty($menu["sub_menu"])){
                        echo '<ul class="treeview-menu">';
                        foreach($menu["sub_menu"] as $sub_menu){
                            create_menu($sub_menu,$active);
                        }
                        echo '</ul>';
                    }
                echo '</li>';

            }
        ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
