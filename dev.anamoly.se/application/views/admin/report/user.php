<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $new_user; ?></h3>
              <p><?php echo _l("New Users"); ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $active_users; ?></h3>
              <p><?php echo _l("Active Users"); ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $companies_users; ?></h3>
              <p><?php echo _l("Companies"); ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $customer_users; ?></h3>
              <p><?php echo _l("Persons"); ?></p>
            </div>
          </div>
        </div>
    </div>
    <div class="box">
        <div class="box-body">
        <?php
            
            echo "<div class=''>";
            echo "<form method='post' action=''>";
            
            echo _input_field("date_range", _l("Date Range"), _get_post_back($field,'date_range'), 'text', array(),array(),"col-md-3","daterangepicker_field");
            echo _select("date_type",(isset($date_type))?$date_type : array(),_l("Date Filter Type"),array(),_get_post_back($field,'date_type'),array(),array("form_group_class"=>"col-md-3"));

            echo '<div class="col-md-3" ><button type="submit" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button>';
          
            echo '</div>';
            
            echo "</form>";
            echo "</div>";    
        ?>
        </div>
    </div>
    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title"><?php echo _l("Reports"); ?> / <?php echo _l("Users"); ?></h3>

        </div>
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            
            <div>
            <canvas id="userChart" height="400" width="800"></canvas>
             
            </div>
        
        </div>
    </div>
    <!-- /.box -->
</section>

