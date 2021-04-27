<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo site_url(); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini">S</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><?php echo APP_NAME; ?></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <?php if(_is_admin() || _is_sub_admin("admin/orders")){?>
          <li>
            <?php
            $this->db->where("draft","0");
            $this->db->where(array("DATE(orders.order_date)"=>date(MYSQL_DATE_FORMATE)));
            $this->db->where_in("status",array(ORDER_PENDING,ORDER_PAID,ORDER_DELIVERED,ORDER_CONFIRMED,ORDER_OUT_OF_DELIVEY,ORDER_CANCEL,ORDER_DECLINE));
            $count_orders_today = $this->db->count_all_results("orders");
            ?>
            <a href="<?php echo site_url("admin/orders/neworders"); ?>" ><i class="ion ion-ios-cart" style="font-size:20px"></i><span class="label label-success"><?php echo $count_orders_today; ?></span></a>
          </li>

          <li>
            <?php
            $this->db->where("draft","0");
            $this->db->where_in("status",array(ORDER_PENDING,ORDER_CONFIRMED,ORDER_OUT_OF_DELIVEY));
            $this->db->where("is_express","1");
            $count_orders_express = $this->db->count_all_results("orders");
            ?>
            <a href="<?php echo site_url("admin/orders/express"); ?>" ><img src="<?php echo base_url("themes/backend/dist/img/express.png") ?>" width="22px" height="22px" /><span class="label label-primary"><?php echo $count_orders_express; ?></span></a>
          </li>
          <?php } ?>
          <?php if(_is_admin() || _is_sub_admin("admin/mailbox")){?>
          <li>
            <a href="<?php echo site_url("admin/mailbox/inbox"); ?>" ><i class="ion ion-email" style="font-size:20px"></i></a>
          </li>
          <?php } ?>
          <li class="dropdown ">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-xs"><?php echo _l("Language")." : ".(($this->session->userdata('site_lang') != NULL) ? $this->session->userdata('site_lang') : "Swedish"); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li>
                <a href="<?php echo site_url("languageswitcher/switchlang/english") ?>">English</a>
              </li>
              <li>
                <a href="<?php echo site_url("languageswitcher/switchlang/dutch") ?>">Swedish</a>
              </li>
              <li>
                <a href="<?php echo site_url("languageswitcher/switchlang/turkish") ?>">Turkish</a>
              </li>
              <li>
                <a href="<?php echo site_url("languageswitcher/switchlang/arabic") ?>">Arabic</a>
              </li>
              <li>
                <a href="<?php echo site_url("languageswitcher/switchlang/german") ?>">German</a>
              </li>
            </ul>
          </li>
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?php echo _get_current_user_image(); ?>" class="user-image" alt="<?php echo _get_current_user_fullname(); ?>">
              <span class="hidden-xs"><?php echo _get_current_user_fullname(); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?php echo _get_current_user_image(); ?>" class="img-circle" alt="<?php echo _get_current_user_fullname(); ?>">
                <p>
                  <?php echo _get_current_user_fullname(); ?>
                  <small><?php echo _get_current_user_email(); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
              <li class="bg-blue-active">
                <ul class="nav nav-stacked">
               <li><a href="<?php echo site_url("profile"); ?>"><?php echo _l("Profile"); ?></a></li>
                    <li><a href="<?php echo site_url("change_password"); ?>"><?php echo _l("Change Password"); ?></a></li>
                </ul>
                <!-- /.row -->
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-right">
                  <a href="<?php echo site_url("login/logout"); ?>" class="btn btn-default btn-flat"><?php echo _l("Sign Out"); ?></a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
