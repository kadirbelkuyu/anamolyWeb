<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="box">
        <div class="box-header with-border">
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
        <div class="box-body">
			<div class="col-md-12"><?php echo _get_flash_message(); ?></div>
            
            <div>
            <canvas id="orderChart" height="320" width="100%"></canvas>
            <ul class="list-inline">
                <?php foreach($chart_array as $c){ ?>
                <li><i class="fa fa-square" style="color: <?php echo $c["pointColor"]; ?>;"></i> <?php echo _l($c["label"]) ; ?> </li>
                <?php } ?>
            </ul> 
            </div>
        
        </div>
    </div>
    <!-- /.box -->
</section>

