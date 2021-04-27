<!-- Main content -->
<section class="content">
<div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-orange">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $active_products; ?></h3>
              <p><?php echo _l("Active Products"); ?></p>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3 style="margin: 0px;"><?php echo $inactive_products; ?></h3>
              <p><?php echo _l("Not Active Products"); ?></p>
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
            
            echo '<div class="col-md-3" ><button type="submit" style="margin-top:25px;" class=" btn btn-primary btn-flat">'._l("Filter").'</button>';
          
            echo '</div>';
            
            echo "</form>";
            echo "</div>";
            
    
        ?>
        </div>
    </div>
    <!-- Default box -->
    <div class="row">
        <div class="col-md-8">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo _l("Reports"); ?> / <?php echo _l("Products"); ?></h3>

                </div>
                <div class="box-body">
                    <div class="col-md-12"><?php echo _get_flash_message(); ?></div>
                    
                    <div>
                        <div class="col-md-6">
                            <canvas id="productChart" style="height:250px"></canvas>
                            
                        </div>
                        <div class="col-md-6">
                      
                        <ul class="chart-legend clearfix">
                            <li><i class="fa fa-circle-o text-red"></i> <?php echo _l("TOTAL PRODUCTS WITH BARCODE"); ?></li>
                            <li><i class="fa fa-circle-o text-green"></i> <?php echo _l("TOTAL PRODUCTS WITH INGREDIENTS"); ?></li>
                            <li><i class="fa fa-circle-o text-yellow"></i> <?php echo _l("TOTAL PRODUCTS WITH NUTRITIONAL VALUE"); ?></li>
                            <li><i class="fa fa-circle-o text-aqua"></i> TOTAL PRODUCTS IS EXPRESS</li>
                            <li><i class="fa fa-circle-o text-light-blue"></i> TOTAL PRODUCTS WITH DESCRIPTION</li>
                        </ul>
               
                        </div>
                        <div class="col-md-12">
                            <table class="table table-condensed table-responsive">
                                <tr>
                                    <td><?php echo _l("TOTAL PRODUCTS WITH BARCODE"); ?></td><td><?php echo $barcode_products; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo _l("TOTAL PRODUCTS WITH INGREDIENTS"); ?></td><td><?php echo $ingredients_products; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo _l("TOTAL PRODUCTS WITH NUTRITIONAL VALUE"); ?></td><td><?php echo $nutritional_products; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo _l("TOTAL PRODUCTS IS EXPRESS"); ?></td><td><?php echo $express_products; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo _l("TOTAL PRODUCTS WITH DESCRIPTION"); ?></td><td><?php echo $description_products; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <!-- PRODUCT LIST -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo _l("Recently Added Products") ?></h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <?php foreach($recent_products as $prod){?>
                <li class="item">
                  <div class="product-img">
                    <?php 
                    $img = base_url("themes/backend/img/boxed-bg.jpg");
                    if(isset($dt->product_image) && $dt->product_image != ""){  
                        $img = base_url(PRODUCT_IMAGE_PATH."/crop/small/".$dt->product_image); 
                    } ?>
                    <img src="<?php echo $img; ?>" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title"><?php echo $prod->product_name_nl; ?>
                      <span class="label label-warning pull-right"><?php echo MY_Controller::$site_settings["currency_symbol"]." ".$prod->price; ?></span></a>
                    <span class="product-description">
                          <?php echo $prod->group_name_nl; ?>
                        </span>
                  </div>
                </li>
                <?php } ?>
                
                <!-- /.item -->
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <a href="<?php echo site_url("admin/products"); ?>" class="uppercase"><?php echo _l("View All Products"); ?></a>
            </div>
            <!-- /.box-footer -->
          </div>
          <!-- /.box -->
        </div>
    </div>
    
    <!-- /.box -->
</section>

