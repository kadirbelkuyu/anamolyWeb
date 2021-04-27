<div class="container">
    <div class="section ui-buttons">
        <div class="row ">
            <div class="col s12 pad-0">
                <h5 class="bot-20 sec-tit"><?php echo _l("Order Details"); ?></h5>

                 <div class="card-panel no-shadow  bg-primary invoice-to">
                    <h5 class="top-0 bot-0"><div class="pull-left"><?php echo date('l d, M Y',strtotime($orders->order_date)); ?></div> <div style="float: right;"><?php echo _lprice($orders->net_amount); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                  </div>
            </div>



            <ul class="collection invoice-item">
             <?php foreach($orders->items as $item){ ?>
     <li class="collection-item avatar">
              <div class="item-det">
                  <img src="<?php echo _limage($item->product_image,PRODUCT_IMAGE_PATH); ?>" alt="" class="circle">
                  <span class="title"><?php echo _lname($item,"product_name"); ?></span>
                  <p class="red-text"><?php echo $item->unit_value." "._lunit($item,"unit"); ?>
                  </p>
              </div>
                <div class="secondary-content">
                    <span class="price_label"><?php echo _lprice($item->price); ?></span>&nbsp;&nbsp; <span class="cart_qty_label m_l_30"><?php echo $item->order_qty; ?></span>
                </div>
            </li>
      <?php } ?>


        </ul>

         <div class="card-panel bg-primary no-shadow invoice-to">
                    <h5 class="grey-text top-0"><div class="pull-left"><?php echo _l("Total Items"); ?></div><div style="float: right;"><?php echo count($orders->items); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                    <h5 class="grey-text top-0"><div class="pull-left"><?php echo _l("Subtotal"); ?></div> <div style="float: right;"><?php echo _lprice($orders->order_amount); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                    <?php if($orders->discount > 0){ ?>
                    <h5 class="red-text top-0"><div class="pull-left"><?php echo _l("Discount")?></div> <div style="float: right;"><?php echo _lprice($orders->discount_amount); ?></div>
                    <div class="clearfix"></div>
                    <?php } ?>
                    </h5>
                    <?php
                      if(isset($orders->vat_prices)){
                        foreach ($orders->vat_prices as $key => $value) {
                            ?>
                            <h5 class="grey-text top-0"><div class="pull-left"><?php echo _lvat($value,"vat_title"); ?></div> <div style="float: right;"><?php echo _lprice($value->amount); ?></div>
                            <div class="clearfix"></div>
                            </h5>
                            <?php
                        }
                      }
                    ?>
                    <h5 class="top-0"><div class="pull-left"><?php echo _l("Total Paid"); ?></div> <div style="float: right;"><?php echo _lprice($orders->net_amount); ?></div>
                    <div class="clearfix"></div>
                    </h5>
                  </div>
                  <a href="#modalReOrder" class="btn red lighten-2 btn-flat modal-trigger" name="order"><i class="mdi mdi-refresh"></i> <?php echo _l("Re Order");?></a>
        </div>
    </div>
</div>
<div id="modalReOrder" class="modal">
       <div class="modal-content">
          <h4><?php echo APP_NAME; ?></h4>
          <p><?php echo _l("If you re order your product than old cart data is cleared and re order data will be added."); ?></p>
       </div>
       <div class="modal-footer">
         <input type="hidden" id="order_id" value="<?php echo _encrypt_val($orders->order_id); ?>" />
         <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat"><?php echo _l("Cancel"); ?></a>
         <button type="button" class="waves-effect waves-green btn-flat btn btn-primary" onclick="reOrderClick();"><?php echo _l("OK"); ?></button>
       </div>
</div>
