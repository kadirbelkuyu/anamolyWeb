<div class="container">
    <div class="section ui-buttons">
        <div class="row section-to-print">
            <div>
             <div class="card no-shadow darken-1 col s12">
                <div class="card-content">
                  <span class="card-title text-center"><?php echo _l("Order Detail"); ?></span>
                </div>
              </div>
              <div class="card no-shadow card-panel  bg-primary col s12">
                  <div class="card-content">
                <div class="row">
                    <div class="col m4 s12 vartical-divider">
                        <div class="">
                         <?php
                           $qr_string = _l("Order ID:").$order->order_id."\n";
                           $qr_string .= _l("Order Date:").$order->order_date."\n";
                           $qr_string .= _l("Total Items:").count($order->items)."\n";
                           $qr_string .= _l("Net Amount:").$order->net_amount."\n";

                         ?>
                         <img src="https://chart.googleapis.com/chart?chs=120x120&cht=qr&chld=H|0&chl=<?php echo $qr_string; ?>&choe=UTF-8" title="<?php echo _l("Order");?> <?php echo $order->order_id?>" />
                      </div>
                        <div class="">
                           <div class="col s6"><?php echo _l("Order ID"); ?></div>
                           <div class="col s6"><b><?php echo $order->order_id; ?></b></div>
                           <div class="col s6"><?php echo _l("Order Date"); ?></div>
                           <div class="col s6"><b><?php echo date("d-m-Y",strtotime($order->order_date)); ?></b></div>
                       </div>
                    </div>
                    <div class="col m8 s12">
                <div class="row mb-10">
                     <div class="col s6">
                       <?php echo _l("Total Items"); ?>
                     </div>
                     <div class="col s6 text-right">
                       <?php echo count($order->items); ?>
                     </div>
                </div>
                <div class="row mb-10">

                     <div class="col s6">
                      <?php echo _l("Sub Total"); ?>
                    </div>
                    <div class="col s6 text-right">
                      <?php echo _lprice($order->order_amount); ?>
                    </div>
                </div>
                <?php if($order->discount > 0){ ?>
                <div class="row">
                    <div class="col s6 red-text">
                      <?php echo _l("Discount"); ?>
                    </div>
                    <div class="col s6 text-right red-text">
                      <?php echo _lprice($order->discount); ?>
                    </div>
                </div>
                <?php } ?>
                     <div class="clearfix padding-top-20"></div>
                    <?php
                      if(isset($order->vat_prices)){
                        foreach ($order->vat_prices as $vat) {
                    ?>
                    <div class="row mb-10">
                      <div class="col s6">
                          <?php echo $vat->vat_title_en; ?>
                      </div>
                      <div class="col s6 text-right">
                        <?php echo _lprice($vat->amount); ?>
                      </div>
                    </div>
                    <?php }
                    }?>
                    <div class="row mb-10">

                       <div class="col s6">
                       <b><?php echo _l("Total"); ?></b>
                      </div>
                      <div class="col s6 text-right">
                        <strong><?php echo _lprice($order->net_amount); ?></strong>
                      </div>
                    </div>
                     <div class="clearfix"></div>
                   </div>
                   </div>
                 </div>
              </div>
          <div class="col s12 text-center">
          <a href="<?php echo site_url(); ?>" class="btn btn-primary btn-flat" name="order"><?php echo _l("Continue to home"); ?></a>
          <a href="<?php echo site_url("order/orderdetail/"._encrypt_val($order->order_id)); ?>" class="btn red lighten-2 btn-flat" name="order"><?php echo _l("View Order"); ?></a>
          </div>
            </div>
        </div>
    </div>
</div>
